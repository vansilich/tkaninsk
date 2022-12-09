<?$inc_path = "../../admin/"; $root_path="../../" ;include($inc_path."class/header.php");$this_page_id = 11;	$q = new query();
			$site_pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');?><? $this_block_id = 17;?>
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
<div class="container"><?
$logout = get_param('logout');
if($logout == 1){
	$_SESSION['user_info'] = '';
	header('location: /');
}
if(empty($_SESSION['user_info']['id'])){
	echo 'Вы не авторизованы!';
	header('location: /auth/');
}else{



		echo '<div class="tasks">
		<a href="edit.php">Личные данные</a> | 	<a href="orders.php">Заказы</a> | <a href="?logout=1">Выход</a>
		</div><hr>';
$done = get_param('done');
if($done == 'true'){
	$temp = $q->select1("select * from ".$prefix."tmpl where id=2");
	echo '<div class="msg">'.$temp['text'].'</div>';	
	
}else{


	$action=get_param('action');
	if($action == 'edit'){
		$phone=get_param('phone');
		$adres=get_param('adres');
		$name=get_param('name');
		$surname=get_param('surname');
		
		$pass=get_param('pass');
		$pass2=get_param('pass2');
		$code=get_param('code');
		$is_agree=get_param('is_agree');
		
		$postindex=get_param('postindex');
		$order_email=get_param('order_email');
		$otch=get_param('otch');
	
		if($err == 0){
			$q->exec("update ".$prefix."members set
			name=".to_sql($name).",
			phone=".to_sql($phone).",
			adres=".to_sql($adres).",
			
			postindex=".to_sql($postindex).",
			order_email=".to_sql($order_email).",
			otch=".to_sql($otch).",
			
			
			surname=".to_sql($surname)."
			where id=".to_sql($_SESSION['user_info']['id']));	
			
			$_SESSION['secret_code'] = '';
			
			//header('location: /account/');
	
		}else{
			echo '<div class="error">'.$err_msg.'</div>';	
		}	
			
	}
	
	if($err != 2){
		$row = $q->select1("select * from ".$prefix."members where id=".to_sql($_SESSION['user_info']['id']));
		$_SESSION['user_info'] = $row;
	?>
	<script>
	function check_reg(form){	
		return true;
	}
	</script>
   

 <style>
 .h3_name{ padding-right:10px; text-align:right}
 </style>  
  
 <form action="" method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" onsubmit="return check_reg(this);">
 <input type="hidden" name="action" value="edit"/>
	   <div class="h2" style="margin:0px 0 10px 0">Личные данные:</div>
      

      
      
      <div>
      
       
		<table cellpadding="0" cellspacing="8" border="0" style="padding-left:13px">
		  <tr>
			<td class="h3_name right tr"><label for="name" >Имя </label></td>
			<td><input class="input" style="width:215px" id="name" name="name" type="text" value="<? echo htmlspecialchars($row['name']);?>"/></td>
			<td class="err"></td>
		  </tr>
          <tr>
			<td class="h3_name right tr"><label for="name" >Отчество </label></td>
			<td><input class="input" style="width:215px" id="otch" name="otch" type="text" value="<? echo htmlspecialchars($row['otch']);?>"/></td>
			<td class="err"></td>
		  </tr>
		  <tr>
			<td class="h3_name right tr"><label for="surname"> Фамилия </label></td>
			<td><input class="input" style="width:215px"  id="surname" name="surname" type="text" value="<? echo htmlspecialchars($row['surname']);?>"/></td>
			<td class="err"></td>
		  </tr>
          
          
          <tr>
			<td class="h3_name right tr"><label for="surname"> Телефон </label></td>
			<td><input class="input" style="width:215px"  id="phone" name="phone" type="text" value="<? echo htmlspecialchars($row['phone']);?>"/></td>
			<td class="err"></td>
		  </tr>
           <tr>
			<td class="h3_name right tr"><label for="surname"> Email для заказов </label></td>
			<td><input class="input" style="width:215px"  id="order_email" name="order_email" type="text" value="<? echo htmlspecialchars($row['order_email']);?>"/></td>
			<td class="err"></td>
		  </tr>
          
          
          
           <tr>
			<td class="h3_name right tr"><label for="surname"> Почтовый индекс </label></td>
			<td><input class="input" style="width:215px"  id="postindex" name="postindex" value="<? echo htmlspecialchars($row['postindex']);?>" />
            </td>
			<td class="err"></td>
		  </tr>
          
          <tr>
			<td class="h3_name right tr"><label for="surname"> Адрес </label></td>
			<td><textarea class="textarea" style="width:215px"  id="adres" name="adres"><? echo htmlspecialchars($row['adres']);?></textarea>
            </td>
			<td class="err"></td>
		  </tr>
          
		 
		  

		  <tr>
			<td>&nbsp;</td>
			<td style="padding-top:30px">
            		<!--input type="image" src="/img/button_renew.png" /-->
            <input type="submit" name="submit" value="Обновить" class="btn" /></td>
			<td>&nbsp;</td>
		  </tr>
		</table>
	  </div>
	</form>
  
    
    
    
    
    
    
    
    
    
    
    
	<?
	}//if($err == 2){
}

}
?>
</div>



<?
include($inc_path.'templates/bottom.php');
?>