<?php

function POST($url, $headers, $body)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return false;
    }

    return json_decode($response, 1);
}


function GET($url, $headers)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
        return false;
    }
    return json_decode($response, 1);
}

function json($data)
{
    echo json_encode(is_array($data) ? $data : [$data]);
    return 1;
}


class Ozon
{

    protected $access_token = '';
    protected $api_auth_endpoint = 'https://xapi.ozon.ru/principal-auth-api';
    protected $api_integration_endpoint = 'https://xapi.ozon.ru/principal-integration-api';
    protected $ozon_client_id = 'ApiUserTkaniNSK_f70b5472-5f51-4717-9dc3-5dc1ae3949e8';
    protected $ozon_client_secret = 'Wm51aLsP8i8CJ7KURKd76nYBTu8E3IUVsTZXSgZqax8=';

    public function __construct($data)
    {
        $this->access_token = $this->auth();
        file_put_contents(__DIR__.'/token.log', $this->access_token);
        $method = trim($data['method']);
        
        if (method_exists($this, $method)) {
            $this->$method($data);
        }
    }

    function auth()
    {
        $body = 'grant_type=client_credentials&client_id='.$this->ozon_client_id.'&client_secret='.$this->ozon_client_secret;

        $url = $this->api_auth_endpoint.'/connect/token';
        $headers = ['content-type: application/x-www-form-urlencoded'];

        $result = POST($url, $headers, $body);

        return is_array($result) ? $result['token_type'] . ' ' . $result['access_token'] : false;
    }

    /**
     * Возвращает список городов, куда будет возможна доставка
     *
     * @param $data
     * @return int
     */
    function cities($data = false)
    {
        $url = $this->api_integration_endpoint."/v1/delivery/cities/extended?deliveryTypes=PickPoint";
        $headers = array(
            "Accept: application/json",
            "Authorization: " . $this->access_token
        );
        $result = GET($url, $headers);

        $cities = array();
        foreach ($result['data'] as $delivery_city) {
//            if (urldecode($delivery_city['type']) === 'город'){
                $cities[] = $delivery_city['name'];
//            }
        }

        $cities = array_values(array_unique($cities));
        return json($cities);
    }

    /**
     * Возвращает пункты выдачи для самовывоза по переданному городу
     *
     * @param $data
     * @return int
     */
    function delivery($data = false)
    {
        $city = urlencode($data['city']);
        $url = $this->api_integration_endpoint.'/v1/delivery/variants?cityName=' . $city;
        $headers = array(
            "Accept: application/json",
            "Authorization: " . $this->access_token
        );

        $result = GET($url, $headers);
        $response_data = array();
        foreach ($result['data'] as $variant) {
            if ($variant['settlement'] === urldecode($city) && $variant['objectTypeName'] === 'Самовывоз') {
                $response_data[] = $variant;
            }
        }
        return json($response_data);
    }

    function fromPlaceId()
    {
        $url = $this->api_integration_endpoint."/v1/delivery/from_places";
        $headers = array(
            "Accept: application/json",
            "Authorization: " . $this->access_token
        );
        $result = GET($url, $headers);
        return $result['places'];
    }

    function calculate($data = false)
    {
        $deliveryVariantId = intval($data['deliveryVariantId']);
        $weight = floatval($data['weight']);

        $places = $this->fromPlaceId();
        $fromPlaceId = $places[0]['id'];

        $url = $this->api_integration_endpoint.'/v1/delivery/calculate';
        $url .= "?deliveryVariantId=$deliveryVariantId&weight=$weight&fromPlaceId=$fromPlaceId";
        $headers = array(
            "Accept: application/json",
            "Authorization: " . $this->access_token
        );

        $result = GET($url, $headers);

        $amount = $data['city'] = ceil($result['amount']) + 50;

        return json(['amount' => $amount, 'from' => $places]);
    }

}

$data = file_get_contents('php://input');
if ($data) {
    new Ozon(json_decode($data, 1));
}


?>