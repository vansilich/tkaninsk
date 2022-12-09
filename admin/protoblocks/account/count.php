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

	echo '<h1>Счета</h1>';
	$id = get_param('id');
	if(empty($id)){
		$shet = $q->select("select * from ".$prefix."shet where user_id=".to_sql($_SESSION['user_info']['id']));
		echo '<table cellpadding="3">';
		foreach($shet as $row){
			echo '<tr><td><a href="?id='.$row['id'].'">Счет №'.$row['id'].'</a> на сумму '.$row['sum'].'</td><td>';	
			if($row['payed'] == 1){
				echo 'оплачен';
			}else{
				echo 'не оплачен';
			}
			echo '</td></tr>';
			
		}
		echo '</table>';
	}else{
		$shet = $q->select1("select * from ".$prefix."shet where id=".to_sql($id));
		
		if($shet['user_id'] == $_SESSION['user_info']['id']){
			$shet_full = $q->select("select S.*,T.name as tname from ".$prefix."shet_full as S 
			join ".$prefix."tarif as T on T.id = S.tarif_id
			where S.shet_id=".to_sql($id));
			echo '<table cellpadding="3"><tr>
			<td>Тариф</td>
			<td>Месяцев</td>
			<td>Сумма</td></tr>';
			foreach($shet_full as $row){			
				echo '<tr>
				<td>'.$row['tname'].'</td>
				<td>'.$row['kol'].'</td>
				<td>'.$row['sum'].'</td>';				
				echo '</tr>';
			}
			echo '</table>';	
		}
	}
}

?>