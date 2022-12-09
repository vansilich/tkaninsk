<?php
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../admin/";	$root_path = "../";
include($inc_path."class/header.php");

//function _russianpostcalc_api_communicate($request)
//{
//    $curl = curl_init();
//    curl_setopt($curl, CURLOPT_URL, "https://russianpostcalc.ru/api_beta_077.php");
//    curl_setopt($curl, CURLOPT_POST, true);
//    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//    $data = curl_exec($curl);
//    curl_close($curl);
//
//    if($data === false) {
//	    return "10000 server error";
//    }
//
//    return json_decode($data, true);
//}

//function russianpostcalc_api_calc($apikey, $password, $from_index, $to_index, $weight, $ob_cennost_rub)
//{
//    $request = array(
//        "apikey"=>$apikey,
//        "method"=>"calc",
//        "from_index"=>$from_index,
//        "to_index"=>$to_index,
//        "weight"=>$weight,
//        "ob_cennost_rub"=>$ob_cennost_rub
//    );
//
//    if ($password != "") {
//        //если пароль указан, аутентификация по методу API ключ + API пароль.
//        $all_to_md5 = $request;
//        $all_to_md5[] = $password;
//        $hash = md5(implode("|", $all_to_md5));
//        $request["hash"] = $hash;
//    }
//
//    return _russianpostcalc_api_communicate($request);
//}

/**
 * Расчет тарифа отправки нестандартной посылке по Почте России
 *
 * Документация - https://tariff.pochta.ru/post-calculator-api.pdf?639
 *
 * @param int $from_index - индекс отделения отправления
 * @param int $to_index - индекс отделения адресата
 * @param int $weight - вес посылки в граммах
 *
 * @throws Exception
 * @return mixed
 */
function russianpostcalc_api_calc($from_index, $to_index, $weight){
    $max_weight = 10000;
    if ($weight > $max_weight) {
        throw new Exception('Вес посылки больше максимального для 1 отправки');
    }

    $apiURL = 'https://tariff.pochta.ru/v2/calculate/tariff';
    $query_params = array(
        'object' => 4030, //посылка нестандартная
        'weight' => $weight + 100, // + 100 грамм (константный вес упаковки)
        'from' => $from_index,
        'to' => $to_index,
    );

    $query_url = $apiURL . '?' . http_build_query($query_params);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $query_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    if (curl_getinfo($curl, CURLINFO_RESPONSE_CODE) !== 200) {
        throw new Exception('При выполнении запроса возникли ошибки');
    }

    curl_close($curl);

    return json_decode($data, true);
}

$toindex = get_param('postindex');
$ves = get_param('ves');
$ves_kg = $ves / 1000;
$sum = (int)get_param('sum');

//запрос расчета стоимости отправления из 101000 МОСКВА во ВЛАДИМИР 600000.
try {
    $ret = russianpostcalc_api_calc( "630000", $toindex, $ves);
} catch (Exception $exception) {
    echo "ошибка! Не возможно рассчитать стоимость доставки<br/>";
    die();
}

if ( !isset($ret['paynds']) ) {
    echo "ошибка! Не возможно рассчитать стоимость доставки<br/>";
    die();
}

$price = $ret['paynds'] / 100;

//До 2кг – 50руб,  2-3,7кг – 75руб, от 3,7кг – 100руб
if( $ves_kg > 3.7 ){
    $price += 100;
} elseif( $ves_kg > 2 ){
    $price += 75;
} else {
    $price += 50;
}
echo 'Цена доставки: <span id="s_res">'.$price.'</span> Руб';