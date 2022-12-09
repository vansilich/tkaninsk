<?
$logout = get_param('logout');
if($logout == 1){
	$_SESSION['user_info'] = '';
	header('location: /');
}
if(empty($_SESSION['user_info']['id'])){
	echo 'Вы не авторизованы!';
	header('location: /auth/');
}else{


$cmd = get_param('cmd');

if($cmd == 'repeat'){
	$oid = get_param('oid');
	$order = $q->select1("select * from ".$prefix."orders where id=".to_sql($oid));
	$full = $q->select("select * from ".$prefix."orders_full where order_id=".to_sql($oid));
	$_SESSION['basket_catalog'] = array();
	foreach($full as $f){
		$good = $q->select1("select G.*,C.edizm from ".$prefix."goods as G
				join ".$prefix."catalog as C on C.id=G.catalog
				where G.id=".to_sql($f['good_id']));
				
		if($good['kol']<=0){ echo '<div style="color:red">Товара "'.$good['name'].'" нет в наличии</div>';continue;}		
			
		$cart_row['good'] = $f['good_id'];
		$cart_row['type'] = $f['types'];
		$cart_row['type_id'] = $f['par am'];
		
		//echo '<br>'.$f['col'].' = '.$good['kol'];
		if($good['kol'] < $f['col']) $f['col'] = $good['kol'];
		$cart_row['q']=$f['col'];
		$_SESSION['basket_catalog'][]=$cart_row;
	}
echo '<script>document.addEventListener(\'DOMContentLoaded\', function () {refreshbasket();});</script>';
header('location: /basket/?refresh=true');
}

		echo '<div class="tasks">
		<a href="edit.php">Личные данные</a> | 	<a href="orders.php">Заказы</a> | <a href="?logout=1">Выход</a>
		</div><hr>';
		
		echo '<h1>Заказы</h1>';		
		
		$orders = $q->select("select * from ".$prefix."orders where user_id=".to_sql($_SESSION['user_info']['id'])." order by id desc");		
		echo '<table class="table" border="1" cellpadding="7"><tr><td>№</td><td>Заказ</td><td>Доставка</td>
		<td>Оплатить</td>
		<td>Изменить</td>
		
		</tr>';
		foreach($orders as $row){
			echo '<tr><td>'.$row['id'].'</td><td>'.$row['order_text'].'</td><td>'.$row['delivery_price'].' руб</td>';
			if($row['payed'] == 1){
				echo '<td style="padding:10px;">ЗАКАЗ ОПЛАЧЕН</td>
			
			<td style="padding:10px"><a href="?cmd=repeat&oid='.$row['id'].'">ПОВТОРИТЬ ЗАКАЗ</a></td>
			';				
			}else{
				echo '<td style="padding:10px;"><a href="/pay/?good='.$row['id'].'&p='.$row['phone'].'" class="btn" style="padding-left:10px; padding-right:10px">Оплатить заказ</a></td>
				
				<td style="padding:10px"><a href="?cmd=repeat&oid='.$row['id'].'">ИЗМЕНИТЬ И ОПЛАТИТЬ ЗАКАЗ</a></td>
				';
			}
			echo '
			</tr>';
		
		}
		echo '</table>';
		
}

?>