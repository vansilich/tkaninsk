<?
$logout = get_param('logout');
if($logout == 1){
	$_SESSION['user_info'] = '';
	header('location: /');
}
if(empty($_SESSION['user_info']['id'])){
	echo 'Вы не авторизованы!';
	header('location: /auth/');
}/*elseif($_SESSION['user_info']['active'] == 0){
	echo 'Ваш аккаунт еще не активирован!';
}*/else{


		echo '<div class="tasks">
		<a href="edit.php">Личные данные</a> | 	<a href="orders.php">Заказы</a> | <a href="?logout=1">Выход</a>
		</div><hr>';
}
?>