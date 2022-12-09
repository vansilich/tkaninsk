<?php

$inc_path = "/var/www/user1893611/data/www/tkaninsk.com/admin/"; 
$root_path="/var/www/user1893611/data/www/tkaninsk.com/" ; 
include($inc_path."class/header.php");
$q = new query();

require $root_path.'cron/mail/PHPMailerAutoload.php';
include($root_path."xls/GetNewFile.php");


$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
    define('MAIL_PASS',$_settings['email_pass']);

$orders = $q->select("SELECT *
FROM  `".$prefix."orders` 
WHERE email_pay_send=-1");



foreach($orders as $o){
	echo 'id='.$o['id'];
	$full = $q->select("select * from ".$prefix."orders_full where order_id = ".to_sql($o['id']));
	
	$arr = array();
foreach($full as $f){
	$good = $q->select1("select * from ".$prefix."goods as G
				where G.id=".to_sql($f['good_id']));
	$main_color = $q->select1("select C.name as cname from ".$prefix."adv_params_value as C where id=".to_sql($good['color']));
	if(!empty($main_color['cname']))$good['name'] = $good['name'].' ['.$main_color['cname'].']'; 	
					
	$mas['articul']=$good['articul'];
	$mas['title']=$good['name'];
	if($good['edizm'] == 'шт'){
		$mas['edizm']='шт.';	  
	  }else{
		$mas['edizm']='пог.м.';  
	  }
	
	
	$mas['min_count']=$f['col'];
	$mas['price']=$f['price'];
	array_push($arr,$mas);
}
	$mas['articul']='d';
	$mas['title']='Доставка';
	$mas['edizm']='шт.';
	$mas['min_count']=1;
	$mas['price']=$o['delivery_price'];
	array_push($arr,$mas);
	$products = json_encode($arr);
	
	//Переменные, которые надо получить через $_POST, тут они для примера
	$num = $o['id'];
	$date = date("d.m.Y",to_phpdate($o['date_add']));
	$target = "Оплата по заказу клиента №".$o['id'];
	$buyer = $o['fio'].','.$o['adres'].', тел '.$o['phone'].', email '.$o['email'].',
                    '.$o['delivery'];
	$product_json = json_decode($products);
	
	GetNewFile($num,$date,$target,$buyer,$product_json);
	
	$sended = smtpmail($_settings['email'],'Заказ с сайта tkaninsk.com',$o['letter_order']);
	$sended = smtpmail($o['email'],'Заказ с сайта tkaninsk.com',$o['letter_body']);
	$q->exec("update  `".$prefix."orders` set email_pay_send=0 where id=".to_sql($o['id']));
}







echo 'done';
?>