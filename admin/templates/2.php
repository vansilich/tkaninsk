{HEADER}
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


<div id="banner">
<?
$main = $q->select("select * from ".$prefix."main where types=1 and status=1 order by rank desc");
foreach($main as $row){
	
		$file = 'files/main/'.$row['id'].$row['img'];
		$file_m = 'files/m_main/'.$row['id'].$row['m_img'];
		$img = '';
		if(is_file($root_path.$file)){
			$alt = my_htmlspecialchars($row['name']);
			$img = '<img src="/files/main/pre1_'.$row['id'].$row['img'].'"  alt="'.$alt.'" class="img_pc"/>
			<img src="/files/m_main/pre1_'.$row['id'].$row['m_img'].'"  alt="'.$alt.'" class="img_mob"/>
			';
			if(!empty($row['url'])) $img = '<a href="'.$row['url'].'">'.$img.'</a>';
			echo '<div>'.$img.'</div>';				
		}
}
?>
   </div>
<? /*
<div id="action">
  <div class="container">
    <div class="row">
    <?
	$i = 1;
    foreach($main as $row){
		echo ' <div class="col-md-3 col-sm-3';
		if($i>1) echo ' border';
		echo '" > <a href="" id="mm'.$i.'">'.$row['name'].'</a> </div>';
		$i++;
	}
	?>
    </div>
  </div>
</div>
* /?>
<div class="grey">
  <div class="container">
    <div class="title-big"> почему мы?
      <div class="h1"> почему мы? </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-sm-4" align="center"> <img src="/img/w1.png" width="65" height="59" alt=""/>
        <div class="name">все ткани в наличии</div>
        Наши ткани не под заказ, они находятся у нас на складе </div>
      <div class="col-md-4 col-sm-4" align="center"> <img src="/img/w2.png" width="85" height="56" alt=""/>
        <div class="name">быстрая доставка</div>
        Отправляем заказы на следующий день после оплаты </div>
      <div class="col-md-4 col-sm-4" align="center"> <img src="/img/w3.png" width="60" height="60" alt=""/>
        <div class="name">обновление ассортимента</div>
        В нашем интернет магазине постоянно появляются новинки </div>
    </div>
  </div>
</div>
*/?>
{TEXT}


 

<?
include($inc_path.'templates/bottom.php');
?>