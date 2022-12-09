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

?></div>



<?
include($inc_path.'templates/bottom.php');
?>