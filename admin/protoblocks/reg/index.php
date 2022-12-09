<div class="order">
<?
require $root_path.'cron/mail/PHPMailerAutoload.php';

if(!empty($_SESSION['user_info']['id'])){
	echo 'Вы авторизованы!';
	header('location: /account/');
}


$login = trim(get_param('login'));
$pass = trim(get_param('pass'));
if(!empty($login) && !empty($pass)){
	$check = $q->select1("select * from ".$prefix."members where mail=".to_sql($login)." and pass=".to_sql(PasswordCrypt($pass)));
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

<div class="row">
<h1>Вход в личный кабинет</h1>
<div class="col-md-6">
<?

$done = strip_tags(get_param('done'));
if($done == 'true'){
	$temp = $q->select1("select * from ".$prefix."tmpl where id=2");
	echo '<div class="msg">'.$temp['text'].'</div>';	
	
}else{


	$action=strip_tags(get_param('action'));
	if($action == 'add'){
		$phone=strip_tags(get_param('phone'));
		$name=strip_tags(get_param('name'));
		$fam=strip_tags(get_param('fam'));
		$otch=strip_tags(get_param('otch'));
		$surname=strip_tags(get_param('surname'));
		$mail=strip_tags(get_param('mail'));
		$login=strip_tags(get_param('login'));
		$pass=strip_tags(get_param('pass'));
		$pass2=strip_tags(get_param('pass2'));
		$code=strip_tags(get_param('code'));
		
		
	
		$check = $q->select1("select id from ".$prefix."members where mail=".to_sql($mail));
		if($check != 0){
			$err = 1;
			$err_msg .= 'На данный email "'.$mail.'" уже зарегистрирован пользователь<br>';
		}
		
		if(empty($pass)){
			$err = 1;
			$err_msg .= 'Не указан пароль<br>';	
		}

		if(empty($_SESSION['secret_code']) || ($_SESSION['secret_code'] != $code)){
			$err = 1;
			echo  '<div style="color:red; font-size:16px;">Не правильно указан параметр "Код потверждения"</div><br>';
			$_SESSION['secret_code'] = '';				
		}
		if($err == 0){
			
			$k = md5(date('d.m.y h:i').$mail.'z');
			$new_id = $q->insert("insert into ".$prefix."members set
			date_reg = NOW(),
			active = 0,
			
			
			login=".to_sql($login).",
			phone=".to_sql($phone).",
			name=".to_sql($name).",
			fam=".to_sql($fam).",
			otch=".to_sql($otch).",
			surname=".to_sql($surname).",
			mail=".to_sql($mail).",
			pass=".to_sql(PasswordCrypt($pass)).",
			code = ".to_sql($k));	
			
			
			
			$_SESSION['secret_code'] = '';
			
			$temp = $q->select1("select * from ".$prefix."tmpl where id=1");
			$msg = str_replace('{NAME}',$name.' '.$surname,$temp['text']);
			$msg = str_replace('{LINK}','http://'.$domen_name.'/reg/activate.php?c='.$k,$msg);
			$msg = str_replace('{EMAIL}',$mail,$msg);
			$msg = str_replace('{LOGIN}',$mail,$msg);
			$msg = str_replace('{PASS}',$pass,$msg);
			
			
			$headers = "From: ".$_SERVER['HTTP_HOST']." <info@".$_SERVER['HTTP_HOST'].">\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			
			//mail($mail, $temp['theme'],$msg  , $headers);
			$sended = smtpmail($mail,$temp['theme'],$msg);
			
			$err = 2;
			header('location: ?done=true');
	
	
		}else{
			echo '<div class="error">'.$err_msg.'</div>';	
		}	
			
	}
	
	if($err != 2){
	?>
	

   
  
 <form action="" method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" onsubmit="return check_reg(this);">
 <input type="hidden" name="action" value="add" />
	   <div class="h2">Регистрация:</div>
      
      
   <input placeholder="Фамилия" class="input" id="fam" name="fam" />
   <input placeholder="Имя" class="input" id="name" name="name" />
   <input placeholder="Отчество" class="input" id="otch" name="otch" />
   <input placeholder="Телефон" class="input"  name="phone" id="phone"/>
   <input placeholder="Email"  name="mail" id="mail" class="input" />
   <input placeholder="Пароль"  type="password" name="pass" class="input" />
   <input placeholder="Повторите пароль" type="password" id="pass2" name="pass2" class="input" />
       
      <table cellpadding="0" cellspacing="0"> 
       <tr>
			<td><label class="label2 label3" for="code">Введите цифры <input  class="input" style="width:107px"  name="code" maxlength="8" id="code" autocomplete="off" type="text"></label></td>
            
			<td class="captcha">
			  <img id="captcha"	src="/img.php" alt="Цифры с картинки" title="Цифры с картинки"></td><td> <a class="dot_a a_in" id="captcha_refresh" href="#" 
			  onclick="$('#captcha').attr('src', '/img.php?z='+(new Date()).valueOf()); return false;">Обновить цифры </a></td>
		
		  </tr>
          
       </table>
       
       
       
          <div class="label2"><a href="javascript:" onclick="$('#registerForm').submit();metrika_goal('reg'); return false;" class="btn button_green">Зарегистрироваться</a></div>

	</form>
  
    
    
    
    
    
    
    
    
    
    
    
	<?
	}//if($err == 2){
}
?>
</div>
<div class="col-md-6">
<div class="h2">Авторизация:</div>
<form id="login-form" action="" method="post">
  <input type="hidden" name="cmd" value="login" />
 
  <input class="input"   name="login" placeholder="E-mail"/>
 
 <input class="input" type="password" name="pass"  placeholder="Пароль"/>
 
   
    
    <div class="left">
    <a href="javascript:" onclick="$('#login-form').submit();return false;" class="btn button_green">Войти</a>
    </div>
    <div class="left"><a href="/reg/forget.php" >Забыли пароль?</a></div>
    <br class="clear" />
  

</form>
</div>
</div>
</div>