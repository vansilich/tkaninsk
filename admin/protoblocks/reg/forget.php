<h1>Восстановить пароль</h1>
<?
require $root_path.'cron/mail/PHPMailerAutoload.php';

	$email = get_param('email');
	if(!empty($email)){
		$check = $q->select1("select * from ".$prefix."members where mail=".to_sql($email));
		if(empty($check)){
			echo 'На данный E-mail никто не зарегистрирован!';
		}else{
			echo '<br>Вам выслано письмо с вашими данными<br><br>';
			$headers = "From: ".$_SERVER['HTTP_HOST']." <info@".$_SERVER['HTTP_HOST'].">\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			
			
			$msg =  '
			Восстановление пароля на сайте tkaninsk.com<br>
			Ваш логин: '.$email.'<br>
			Ваш пароль: '.PasswordDeCrypt($check['pass']);
			
			$sended = smtpmail($email,'Восстановление пароля на сайте '.$_SERVER['HTTP_HOST'],$msg);
			
			/*
			mail($email, 'Восстановление пароля на сайте '.$_SERVER['HTTP_HOST'], '
			Восстановление пароля на сайте tkaninsk.com<br>
			Ваш логин: '.$email.'<br>
			Ваш пароль: '.PasswordDeCrypt($check['pass']), $headers);*/
			
		}		
	}
			echo '<form method="post">
	<input type="text" name="email" value="" placeholder="Ваш E-mail" />
	<input type="submit" value="Восстановить пароль" />
	</form>';
?>
