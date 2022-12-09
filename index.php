<? $inc_path = "admin/";	$root_path = ""; include($inc_path."class/header.php");	$this_page_id = 1;	$q = new query();
			$site_pages = new pages($table=$prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');?><? $this_block_id = 4;?>
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

<div class="container">
<div align="center">
<div class="main_tabs_tabs">
<a href="" class="tabs-a tabs-a-ac" data-tab='tab_hit'>Хиты продаж</a> 
<a href="" class="tabs-a" data-tab='tab_new'>Новинки</a> 
<a href="" class="tabs-a" data-tab='tab_sale'>Скидки</a></div>
</div>
<div class="main_tabs">
    <div id="tab_hit" class="active">
    <?
	$tmpl_goods = 'goods_main.php';
	$where = " and lider=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." order by rand() LIMIT 12";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
    <div align="center"><a href="/hits/" class="btn">Смотреть все хиты</a></div>
    </div>
    <div id="tab_new">
    <?
	$where = " and new=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." order by rand()  LIMIT 12";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
    <div align="center"><a href="/novinki/" class="btn">Смотреть все новинки</a></div>
    </div>
    <div id="tab_sale">
    <?
	$where = " and action=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." order by rand()  LIMIT 12";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
    <div align="center"><a href="/sale/" class="btn">Смотреть все скидки</a></div>
    </div>    
</div>

</div>

<div class="grey why">
  <div class="container">
    <div class="title-big"> почему мы?
      <div class="h1"> почему мы? </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-sm-4" align="center"> <img src="img/w1.png" width="65" height="59" alt=""/>
        <div class="name">все ткани в наличии</div>
        Наши ткани не под заказ, они находятся у нас на складе </div>
      <div class="col-md-4 col-sm-4" align="center"> <img src="img/w2.png" width="85" height="56" alt=""/>
        <div class="name">быстрая доставка</div>
        Отправляем заказы на следующий день после оплаты </div>
      <div class="col-md-4 col-sm-4" align="center"> <img src="img/w3.png" width="60" height="60" alt=""/>
        <div class="name">обновление ассортимента</div>
        В нашем интернет магазине постоянно появляются новинки </div>
    </div>
  </div>
</div>

<div id="about">
 <div class="container">
    <div class="row">
    <div class="col-md-9 col-sm-12">
     <h2>Новости</h2>
     <div class="news">
     <?
     $row = $q->select1("SELECT * FROM ".$prefix."news WHERE status=1 order by created desc");
	 ?>
     <a href="/news/<?=$row['cpu']?>/"><?=$row['name']?></a>
<?=$row['anons']?>
     </div>
     <a href="/news/">Все новости <img src="img/point.png" width="6" height="8" alt=""/></a>
    </div>
    <div class="col-md-3 col-sm-12 text">
<?
echo $this_page['text'];
?>
    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?156"></script>

<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 3, width: "auto"}, 61221112);
</script>
    
    </div>
     
    </div>
    </div>
</div> 
<img src="img/about.jpg" width="100%"  alt=""/>
<? /*
<div class="grey command">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-sm-12">
        <div class="title-big"> Наша команда
          <div class="h1">Наша команда </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="look-div"><img src="img/look.png" width="22" height="15" alt="" class="look-icon"/> <a href="/staff/">Посмотреть весь коллектив</a></div>
      </div>
    </div>
<?
	$link = '/staff/';
	$img_folder = 'files/staff/';
	$data = $q->select("SELECT * FROM ".$prefix."staff WHERE status=1 order by rank desc  LIMIT 4");
	$tmpl = file_get_contents($inc_path.'protoblocks/staff/templates/main.php');
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);

	echo $tmp_begin[1];
	foreach($data as $row){
		$img = $link_img = '';
		if(is_file($root_path.$img_folder.'pre'.$row['id'].$row['img'])){
			$img = '<img src="/'.$img_folder.'pre1_'.$row['id'].$row['img'].'" alt="'.my_htmlspecialchars($row['name']).'" border="0">';
		}
		$tmpl_row = $tmp_row[1];
		$tmpl_row = str_replace('{NEWS_LINK}',$link.$row['cpu'].'/',$tmpl_row);
		$tmpl_row = str_replace('{NEWS_NAME}',$row['name'],$tmpl_row);
		$tmpl_row = str_replace('{NEWS_DOLJ}',$row['dolj'],$tmpl_row);
		$tmpl_row = str_replace('{NEWS_IMG}',$img,$tmpl_row);
		$tmpl_row = str_replace('{NEWS_ANONS}',$row['anons'],$tmpl_row);
		echo $tmpl_row;
		echo $tmp_delim[1];
	}
	echo $tmp_end[1];
?>    
    
  </div>
</div>
*/ ?>


 

<?
include($inc_path.'templates/bottom.php');
?>