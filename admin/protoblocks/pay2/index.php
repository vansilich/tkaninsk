<h1>Оплата заказа</h1>
<?
$user = 'per21-api';
$pas = 'per21';

$id = get_param('id');
$this_cat = $q->select1("select * from ".$prefix."catalog where id=".to_sql($id));

$orderId = get_param('orderId');
if(!empty($orderId)){
	echo '<div class="page_in" id="action pay">';
	$url = 'https://3dsec.sberbank.ru/payment/rest/getOrderStatus.do?orderId='.$orderId.'&language=ru&password='.urlencode($pas).'&userName='.urlencode($user).'';

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
	
	$order = $q->select1("select * from ".$prefix."orders where orderId=".to_sql($orderId));		
	if($mas->OrderStatus == 2) echo '<div style="color:green">Заказ: '.$order['id'].' оплачен</div>';
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
	
	echo '</div>';
}else{


$cmd = get_param('cmd');
if($cmd == 'pay'){
	$good = $good_id = get_param('good');
	if(empty($good_id)){
		echo '<div style="color:red">Не указан номер заказа</div>';
	}else{
		$full = $q->select("select * from ".$prefix."orders_full where order_id=".to_sql($good_id));
		$sum = 0;
		foreach($full as $v){
				$good = $q->select1("select * from ".$prefix."goods as G
				where G.id=".to_sql($v['good_id']));

				if($v['col'] >=3 && $good['price_opt']>0){
					$price = $good['price_opt'];	
				}else{
					$price = $good['price'];	
				}
				$sum += $v['col']*$price;
		}
		
		
		$url = 'amount='.$sum.'00&language=ru&orderNumber='.$good_id.'&password='.urlencode($pas).'&returnUrl=http://tkani.vakas2.ru/pay/?good='.$good.'&userName='.urlencode($user).'';
		$url = 'https://3dsec.sberbank.ru/payment/rest/register.do?'.$url;
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
			$q->exec("update ".$prefix."orders set orderId=".to_sql($mas->orderId)." where id=".to_sql($good));
			header('location:'.$mas->formUrl);	
		}else{
			echo '<div style="color:red">Произошла ошибка попробуйте еще раз</div>';
		}
	}
}




	$good = get_param('good');
	$p = get_param('p');
	
	$ord = $q->select1("select * from ".$prefix."orders where id=".to_sql($good)." and phone=".to_sql($p));
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
		
		$full = $q->select("select * from ".$prefix."orders_full where order_id=".to_sql($good));
		$sum = 0;
		foreach($full as $v){
				$good = $q->select1("select * from ".$prefix."goods as G
				where G.id=".to_sql($v['good_id']));

				if($v['col'] >=3 && $good['price_opt']>0){
					$price = $good['price_opt'];	
				}else{
					$price = $good['price'];	
				}
				$sum += $v['col']*$price;
		}
		//echo 'Сумма:'.$sum;
		
		  ?>
		  
		  
		  
		  <div class="clearfix button_pay">
          <a href="javascript:" onClick="$('#form_pay').submit();return false;" class="btn left" style="width:135px;">Оплатить</a> <img src="/img/sberbank.jpg" alt="" class="left"/></div>
		</form> 
		  
		</div>
		<?
	}else{
		echo 'Не правильная ссылка';
	}
}
?>