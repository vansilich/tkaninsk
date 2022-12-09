<?php

$inc_path = "/var/www/user1893611/data/www/tkaninsk.com/admin/"; 
$root_path="/var/www/user1893611/data/www/tkaninsk.com/" ; 
include($inc_path."class/header.php");
require $root_path.'cron/mail/PHPMailerAutoload.php';
    $q = new query();
$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
define('MAIL_PASS',$_settings['email_pass']);


$orders = $q->select("SELECT *
FROM  `".$prefix."orders` 
WHERE (pay_type='card' or pay_type='schet') and payed=0 and id>640 and email_pay_send=0 and
TIMESTAMPDIFF( 
MINUTE , date_add, NOW( ) ) >10 ");



foreach($orders as $o){
	$temp = $q->select1("select * from ".$prefix."tmpl where id=6");
	$msg = str_replace('{NAME}',$name.' '.$surname,$temp['text']);
	$msg = str_replace('{NUM}',$o['id'],$msg);
	$msg = str_replace('{LINK_PAY}','http://'.$domen_name.'/pay/?good='.$o['id'].'&p='.$o['phone'],$msg);
	$msg = str_replace('{EMAIL}',$mail,$msg);
	$msg = str_replace('{LOGIN}',$mail,$msg);
	$msg = str_replace('{PASS}',$pass,$msg);
	
	
	$headers = "From: tkaninsk.com <nsk-tkani@mail.ru>\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	echo "<br><br>update  `".$prefix."orders` set email_pay_send=1 where id=".to_sql($o['id']);
	
	//mail($o['email'], $temp['theme'],$msg  , $headers);
	if(is_file($root_path."files/invoices/".$o['id'].".xlsx")){
		$sended = smtpmail($o['email'],$temp['theme'],$msg,$root_path."files/invoices/".$o['id'].".xlsx");
}else{
		$sended = smtpmail($o['email'],$temp['theme'],$msg);	
	}
	$q->exec("update  `".$prefix."orders` set email_pay_send=1 where id=".to_sql($o['id']));
}




    $q = new query();

    $orders = $q->select("SELECT *
FROM  `".$prefix."orders` 
WHERE payed=1 and id>1811 and email_got_pay=0 ");



    foreach($orders as $o){
        $temp = $q->select1("select * from ".$prefix."tmpl where id=8");
        $msg = str_replace('{NAME}',$o['fio'],$temp['text']);
        $msg = str_replace('{NUMBER}',$o['id'],$msg);
        $msg = str_replace('{SUM}',$o['sum']/100,$msg);

        $subject = str_replace('{NUMBER}',$o['id'],$temp['theme']);
        $subject = str_replace('{SUM}',$o['sum']/100,$subject);

        $headers = "From: tkaninsk.com <nsk-tkani@mail.ru>\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        echo "<br><br>update  `".$prefix."orders` set email_got_pay=1 where id=".to_sql($o['id']);


        $sended = smtpmail($o['email'],$subject,$msg);
        $q->exec("update  `".$prefix."orders` set email_got_pay=1 where id=".to_sql($o['id']));
    }

?>done