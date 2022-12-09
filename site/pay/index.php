<?$inc_path = "../../admin/"; $root_path="../../" ;include($inc_path."class/header.php");$this_page_id = 14;	$q = new query();
			$site_pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');?><? $this_block_id = 21;?>
<?
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $redirect);
		exit();
	}

$this_page = $q->select1("select * from ".$prefix."pages where id=".to_sql($this_page_id));
$title  = $this_page['title'];
$descr = $this_page['description'];
$keys = $this_page['keywords'];
//$cat_id= get_param('cat_id',0);
$this_block = $q->select1("select folder from ".$prefix."blocks where id=".to_sql($this_page['block']));
if(!empty($this_block['folder'])){
  if(is_file($inc_path.'protoblocks/'.$this_block['folder'].'/_settings.php')){
    include($inc_path.'protoblocks/'.$this_block['folder'].'/_settings.php');  
  }
}

$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
$title = $title;
$descr = htmlspecialchars($descr);
$keys = htmlspecialchars($keys);

?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo  $title;?></title>
<meta name="description" content="<? echo  $descr;?>">
<meta name="Keywords" content="<? echo  $keys;?>">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="/css/justified-nav.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?=rand(1,9999);?>" />
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/favicon2.ico" rel="shortcut icon" type="image/x-icon" />
<!-- JavaScript includes -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NQ85BQ07JF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NQ85BQ07JF');
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2925159064474256');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2925159064474256&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?168",t.onload=function(){VK.Retargeting.Init("VK-RTRG-777495-duTrp"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-777495-duTrp" style="position:fixed; left:-999px;" alt="/></noscript>
</head>


<body>

<?
include($inc_path.'templates/top.php');
?>
<img src="/img/about.jpg" width="100%"  alt=""/>
<div class="container"><h1>Оплата заказа</h1>
<?
require $root_path.'cron/mail/PHPMailerAutoload.php';
define('MAIL_PASS',$_settings['email_pass']);


$user = 'tkaninsk1-api';
$pas = 'PninaSSninword*1';
$sberbank_url = 'securepayments.sberbank.ru';
//$sberbank_url = '3dsec.sberbank.ru';
/*
На тестовой среде https://3dsec.sberbank.ru/mportal3

Создан мерчант tkaninsk1

API:    tkaninsk1-api
Оператор:   tkaninsk1-operator
Пароль на оба логина: tkaninsk1


API:    tkaninsk1-api
Оператор:   tkaninsk1-opninor
Пароль на 1 вход: PninaSSninword*1

https://securepayments.sberbank.ru/mportal/

*/


$orderId = get_param('orderId');
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
}else{


$cmd = get_param('cmd');
if($cmd == 'pay'){
	$good = $good_id = get_param('good');
	$ord = $q->select1("select * from ".$prefix."orders where id=".to_sql($good)." ");
	if(!empty($ord['pay_link'])){
		header('location:'.$ord['pay_link']);		
	}
	if(empty($good_id)){
		echo '<div style="color:red">Не указан номер заказа</div>';
	}else{
		$full = $q->select("select * from ".$prefix."orders_full where order_id=".to_sql($good_id));
		$sum = 0;
		foreach($full as $v){
				$good = $q->select1("select * from ".$prefix."goods as G
				where G.id=".to_sql($v['good_id']));
/*
				if($v['col'] >=$col_opt && $good['price_opt']>0){
					$price = $good['price_opt'];	
				}else{
					$price = $good['price'];	
				}*/
				$price = $v['price'];
				$sum += $v['col']*$price;
		}
		$sum += $ord['delivery_price'];
		/*
		sessionTimeoutSecs N...9 нет Продолжительность жизни заказа в секундах.
В случае если параметр не задан, будет использовано значение, указанное в настройках мерчанта
или время по умолчанию (1200 секунд = 20 минут).
Если в запросе присутствует параметр expirationDate, то значение параметра sessionTimeout
Secs не учитывается.
expirationDate ANS нет Дата и время окончания жизни заказа. Формат: yyyy-MM-ddTHH:mm:ss.
Если этот параметр не передаётся в запросе, то для определения времени окончания жизни заказа
используется sessionTimeoutSecs .
date('Y-m-dTH:i:s')
date('Y-m-d\TH:i:s')
date("Y-m-d",mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));
expirationDate=date('Y-m-d\TH:i:s',mktime(0, 0, 0, date("m") , date("d")+4, date("Y")));
		*/
		$sum = $sum*100;
		$url = 'amount='.$sum.'&language=ru&orderNumber='.$good_id.'&password='.urlencode($pas).'&expirationDate='.date('Y-m-d\TH:i:s',mktime(0, 0, 0, date("m") , date("d")+4, date("Y"))).'&returnUrl=http://'.$domen_name.'/pay/?good='.$good_id.'&userName='.urlencode($user).'';
		$url = 'https://'.$sberbank_url.'/payment/rest/register.do?'.$url;
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
		echo $mas->errorCode.'<br>';
		echo $mas->errorMessage.'<br>';
		/*
		$mas->orderId;
		$mas->formUrl;
		*/
		if(empty($mas->errorCode) && !empty($mas->formUrl)){
			$q->exec("update ".$prefix."orders set orderId=".to_sql($mas->orderId).",sum=".to_sql($sum).", pay_link=".to_sql($mas->formUrl).",pay_data=".to_sql(date('Y-m-d H:i:s',mktime(0, 0, 0, date("m") , date("d")+4, date("Y"))))." where id=".to_sql($good_id));
			header('location:'.$mas->formUrl);	
//			echo $mas->formUrl;
		}else{
			echo '<div style="color:red">Произошла ошибка попробуйте еще раз</div>';
		}
	}
}




	$good = get_param('good');
	$p = trim(get_param('p'));
	$ord = $q->select1("select * from ".$prefix."orders where id=".to_sql($good)." and 
	(
	phone=".to_sql($p)." or phone=".to_sql('+'.$p)."
	)
	");
	if(!empty($ord)){
		?>
		
		<div class="page_in" id="action pay">
		<form method="post" id="form_pay">
		<input type="hidden" name="cmd" value="pay">
        <input type="hidden" name="good" value="<?=$good;?>">
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
		
		$full = $q->select("select * from ".$prefix."orders_full where order_id=".to_sql($good));
		$sum = 0;
		foreach($full as $v){
				$good = $q->select1("select * from ".$prefix."goods as G
				where G.id=".to_sql($v['good_id']));
/*
				if($v['col'] >=$col_opt && $good['price_opt']>0){
					$price = $good['price_opt'];	
				}else{
					$price = $good['price'];	
				}*/
				$price = $v['price'];
				$sum += $v['col']*$price;
		}
		$sum +=$ord['delivery_price'];
		echo '<div style="font-size:20px; padding:20px;">Итоговая сумма: <b>'.$sum.'</b> рублей</div>';
		
		  ?>
		  
		  
		  <?
          if($ord['payed'] == 1){
			?>
			<div style="color:green; padding:15px; font-size:20px; border:solid 1px green;">Заказ оплачен</div>
			<?  
		  }else{
		  ?>
		  <div class="clearfix button_pay">
          <a href="javascript:" onClick="$('#form_pay').submit();return false;" class="btn left" style="width:135px;">Оплатить</a> <img src="/img/sberbank.jpg" alt="" class="left"/></div>
          <?
		  }
		  ?>
          
          
		</form> 
		  
		</div>
		<?
	}else{
		echo 'Не правильная ссылка';
	}
}
?></div>



<?
include($inc_path.'templates/bottom.php');
?>