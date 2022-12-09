<?
if(empty($_SESSION['user_info']['id'])){
	echo 'Вы не авторизованы!';
}elseif($_SESSION['user_info']['active'] == 0){
	echo 'Ваш аккаунт еще не активирован!';
}elseif($_SESSION['ask_password'] == 1){
	echo '<form action="/account/" method="post">
	Пароль <input type="password" name="repass"><input type="submit" value="войти">
	</form>';	
}else{
echo '<div class="tasks">
					
					<a href="count.php">Счета</a>
					<a href="access.php">Выписать счет</a>
					<a href="/08_feedback.html" class="feedback popup2">Обратная связь</a>
				</div><div class="hrm"></div>';
	
	$cmd = get_param('cmd');
	if($cmd == 'shet'){
		
		$sum = 0;
		$new_id = $q->insert("insert into ".$prefix."shet set user_id=".to_sql($_SESSION['user_info']['id']));
		$tarif = $q->select("select * from ".$prefix."tarif");	
		foreach($tarif as $row){
			$ch = get_param('ch'.$row['id']);
			if(!empty($ch)){
				$s = get_param('s'.$row['id']);	
				if($s == 24){
					$price = $s*$row['price4'];
				}elseif($s >= 12){
					$price = $s*$row['price3'];
				}elseif($s >= 3){
					$price = $s*$row['price2'];
				}else{
					$price = $s*$row['price1'];
				}
				$sum += $price;	
				$q->exec("insert into ".$prefix."shet_full set 
				shet_id=".to_sql($new_id).",
				tarif_id = ".to_sql($row['id']).", 
				kol=".to_sql($s).",
				sum = ".to_sql($price));	
			}

			
		}
		$q->exec("update ".$prefix."shet set sum=".$sum." where id=".to_sql($new_id));
		echo 'Счет выставлен<br><hr>';
	
	}
	
	$tarif = $q->select("select * from ".$prefix."tarif");
	echo '<form method="post" id="pays-form">
	<input type="hidden" name="cmd" value="shet">
	
<table>
	<tr>
		<th colspan="2"><a href="#">Наименование&nbsp;услуги</a></th>
		<th><a href="#">Текущий&nbsp;статус</a></th>
		<th><a href="#">Цена&nbsp;в&nbsp;месяц</a>&nbsp;&nbsp;&nbsp;</th>
		<th><a href="#">Период&nbsp;оплаты</a></th>
	</tr>';
	$f = 0;
	foreach($tarif as $row){
		echo '<tr';
		if($f == 0){
			echo ' class="odd"';
			$f = 1;
		}else{
			$f = 0;
		}
		echo '><td class="chk-td"><input type="checkbox" name="ch'.$row['id'].'" value="1"></td>
		<td>
		'.$row['name'].'<span>см <a href="#">полный список</a></span>
		</td>
		<td>';
		
		$check = $q->select1("select * from ".$prefix."access as A
		where tarif_id=".$row['id']."  and user_id = ".to_sql($_SESSION['user_info']['id'])." ");
		
		if(!empty($check['date_end'] )){
			$adate = explode('-',$check['date_end']);
			$date=$adate[2].'.'.$adate[1].'.'.$adate[0];
			echo '<span class="norm">оплачено до '.$date.'</span>';
		}
		echo '</td>
		<td>'.$row['price1'].'</td>
		<td>';
		echo '<select name="s'.$row['id'].'" id="s'.$row['id'].'">';
		for($i=1; $i<=12 ; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';		
		}
		echo '</select> мес.';
		echo '</td></tr>';
	}
	echo '</table>
	<input type="submit" value="">
	</form>
	';
	
}
?>