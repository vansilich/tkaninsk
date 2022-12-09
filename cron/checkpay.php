<?php
$inc_path = "/var/www/user1893611/data/www/tkaninsk.com/admin/"; 
$root_path="/var/www/user1893611/data/www/tkaninsk.com/" ; 
include($inc_path."class/header.php");
require $root_path.'cron/mail/PHPMailerAutoload.php';
    $q = new query();
$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
define('MAIL_PASS',$_settings['email_pass']);
 ?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Интернет магазин тканей в Новосибирске - купить в розницу и оптом</title>
<meta name="description" content="">
<meta name="Keywords" content="">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css?v=8587" />
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/favicon2.ico" rel="shortcut icon" type="image/x-icon" />
<!-- JavaScript includes -->

</head>


<body>
<?

$orders = $q->select("SELECT *
FROM  `".$prefix."orders` 
WHERE pay_type='card'  and payed=0 and id>640 and
TIMESTAMPDIFF( 
MINUTE , date_add, NOW( ) ) >8 and orderId!='' order by id desc limit 5");


$user = 'tkaninsk1-api';
$pas = 'PninaSSninword*1';
$sberbank_url = 'securepayments.sberbank.ru';

foreach($orders as $ord){
sleep(3);
?>
<hr>№<?=$ord['id']?><hr>
<?
$orderId = $ord['orderId'];
if(!empty($orderId)){
	echo '<div class="page_in" id="action pay">';
	$url = 'https://'.$sberbank_url.'/payment/rest/getOrderStatus.do?orderId='.$orderId.'&language=ru&password='.urlencode($pas).'&userName='.urlencode($user).'';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		
		$result = curl_exec($ch);
		if (curl_errno($ch)) { 
            print "Error: " . curl_error($ch); 
        } else { 
            // Show me the result 
            curl_close($ch); 
        } 
		$mas = json_decode($result);
		if($mas->errorCode == 0){
			switch($mas->OrderStatus){
				case 0: $msg = 'Заказ зарегистрирован, но не оплачен'; break;
				case 1: $msg = 'Предавторизованная сумма захолдирована (для двухстадийных платежей)'; break;
				case 2: $msg = 'Проведена полная авторизация суммы заказа'; break;
				case 3: $msg = 'Авторизация отменена'; break;
				case 4: $msg = 'По транзакции была проведена операция возврата'; break;
				case 5: $msg = 'Инициирована авторизация через ACS банка-эмитента'; break;
				case 6: $msg = 'Авторизация отклонена	'; break;
			}
		}
//		$mas->Amount
	/*	echo $mas->errorCode.'<br>';
		echo $mas->OrderStatus.'<br>';
		*/
		
	
	//echo '<pre>'.htmlspecialchars($result).'</pre>';
	
	$ord = $order = $q->select1("select * from ".$prefix."orders where orderId=".to_sql($orderId));	
	if($mas->OrderStatus == 2){ echo '<div style="color:green">Заказ: '.$order['id'].' оплачен</div>';
		$q->exec("update ".$prefix."orders set order_status=1,payed=1,1c_export=0,changed=NOW() where orderId=".to_sql($orderId));

        $temp = $q->select1("select * from ".$prefix."tmpl where id=7");
        $msg = str_replace('{NUMBER}',$order['id'],$temp['text']);
        $msg = str_replace('{SUM}',$ord['sum']/100,$msg);

		/*$msg = 'Получена оплата по заказу №'.$order['id'].' на сайте '.$domen_name.'<br>
		Сумма: '.($ord['sum']/100).' руб
		';*/
		//<tr><td>Подробнее:</td><td>'.$comm.'</td></tr>
		
		//$subject = 'Получена оплата по заказу №'.$order['id'].' на сайте '.$domen_name.'';
        $subject = str_replace('{NUMBER}',$order['id'],$temp['theme']);
        $subject = str_replace('{SUM}',$ord['sum']/100,$subject);

		$headers = "From: ".$_SERVER['HTTP_HOST']." <site@".$_SERVER['HTTP_HOST'].">\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//		mail($_settings['email'], $subject, $msg, $headers);
		$sended = smtpmail($_settings['email'],$subject, $msg);
		//$sended = smtpmail('vakas@ya.ru',$subject, $msg);
	
	}
	else echo '<div style="color:red">'.$msg.'</div>';
	
	?>
	Детали заказа:
    
   
	<?
		echo '
		<h3>Ваши данные</h3>
		<div class="table-responsive">
		<table class="table table-striped table-condensed">';
		echo '<tr><th>ФИО:</th><td>'.$ord['fio'].'</td></tr>';
		echo '<tr><th>Адрес доставки:</th><td>'.$ord['adres'].'</td></tr>';
		echo '<tr><th>Телефон:</th><td>'.$ord['phone'].'</td></tr>';
		echo '<tr><th>Email:</th><td>'.$ord['email'].'</td></tr>';
		echo '<tr><th>Комментарии:</th><td>'.$ord['comm'].'</td></tr>';
		echo '</table></div>';
		echo '<h3>Заказ</h3><br>
		<div class="table-responsive">
		'.str_replace('<table','<table class="table table-striped"',$ord['order_text']);
		echo '</div>';
		echo 'Доставка: <b>'.$ord['delivery_price'].'</b> руб.';
		echo '<div style="font-size:20px; padding:20px;">Итоговая сумма: <b>'.($ord['sum']/100).'</b> рублей</div>';
		
	echo '</div>';
}
}
?>