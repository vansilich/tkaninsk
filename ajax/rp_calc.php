<?php
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../admin/";	$root_path = "../";
include($inc_path."class/header.php");

require_once __DIR__ . '/../admin/class/ExternalApi/RussianPostApi.php';

$russianPostApi = new RussianPostApi();

$toindex = get_param('postindex');
$ves = get_param('ves');
$ves_kg = $ves / 1000;
$sum = (int)get_param('sum');

try {
    $price = $russianPostApi->calcShippingCost($toindex, $ves);
} catch (Exception $exception) {
    echo "ошибка! Не возможно рассчитать стоимость доставки<br/>";
    die();
}

echo 'Цена доставки: <span id="s_res">'.$price.'</span> Руб';