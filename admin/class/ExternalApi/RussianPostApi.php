<?php

class RussianPostApi{

    private $from_index = 630000;

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
     * @return int - стоимость отправки посылки в рублях
     */
    function calcShippingCost($to_index, $weight){

        $max_weight = 10000;
        if ($weight > $max_weight) {
            throw new Exception('Вес посылки больше максимального для 1 отправки');
        }

        $apiURL = 'https://tariff.pochta.ru/v2/calculate/tariff';
        $query_params = array(
            'object' => 4030, //посылка нестандартная
            'weight' => $weight + 100, // + 100 грамм (константный вес упаковки)
            'from' => $this->from_index,
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

        $response = json_decode($data, true);

        if ( !isset($ret['paynds']) ) {
            throw new Exception("Отсутсвует параметр 'paynds'");
        }

        $price = $response['paynds'] / 100;

        //До 2кг – 50руб,  2-3,7кг – 75руб, от 3,7кг – 100руб
        if( $weight > 3700 ){
            $price += 100;
        } elseif( $weight > 2000 ){
            $price += 75;
        } else {
            $price += 50;
        }

        return $price;
    }
}