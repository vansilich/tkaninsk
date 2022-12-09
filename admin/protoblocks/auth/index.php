<div id="cart">
<?

if(!empty($_SESSION['user_info']['id'])){
	echo 'Вы авторизованы!';
	header('location: /account/');
}


$login = trim(get_param('login'));
$pass = trim(get_param('pass'));
if(!empty($login) && !empty($pass)){
	$check = $q->select1("select * from ".$prefix."members where login=".to_sql($login)." and pass=".to_sql(PasswordCrypt($pass)));
	if(!empty($check)){
		$_SESSION['user_info'] = $check;
		$back = get_param('back');
		if($back == 'basket'){
			header('location: /basket/#oform');
		}elseif($back == 'feedback'){
			header('location: /feedback/');
		}else{
			header('location: /account/');
		}
	}else{
		echo '<div class=""error>Не правильная пара логин/пароль</div>';	
	}
}

?>
<div class="h2" style="margin-bottom:10px">Авторизация</div>
<form id="login-form" action="" method="post">
  <input type="hidden" name="cmd" value="login" />
 
    <input class="input2"   name="login" placeholder="Логин"/>
    <br />
    <input class="input2" type="password" name="pass"  placeholder="Пароль"/>
    <br class="clear" />
    <div class="left"><input type="submit" value="Войти"/></div>
    <div class="left" style="margin:6px 0 0 84px"><a href="/reg/forget.php" class="button">Забыли пароль?</a></div>
    <br class="clear" />
  

</form>
</div>