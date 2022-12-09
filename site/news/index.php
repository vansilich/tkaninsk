<?$inc_path = "../../admin/"; $root_path="../../" ;include($inc_path."class/header.php");$this_page_id = 8;	$q = new query();
			$site_pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');?><? $this_block_id = 14;?>
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
<div class="container">﻿<?
$page_size = 10;
$page_name = 'page';
$link = '/news/';
$img_folder = 'files/news/';

$cpu = get_param('cpu');
if(empty($cpu)){

	$data = $q->select("SELECT * FROM ".$prefix."news WHERE status=1 order by created desc  LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	$numb = $q->select1("select count(id) as number from ".$prefix."news where status=1");
	$cnt = count($data);
	$total_number = $numb['number'];


	$tmpl = file_get_contents($inc_path.'protoblocks/news/templates/news.php');
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);

	echo $tmp_begin[1];
	foreach($data as $row){
		$date = date('d.m.Y',to_phpdate($row['created']));
		$img = $link_img = '';
		if(is_file($root_path.$img_folder.'pre'.$row['id'].$row['img'])){
			$img = '<img src="/'.$img_folder.'pre1_'.$row['id'].$row['img'].'" alt="'.my_htmlspecialchars($row['name']).'" border="0" class="border">';
		}
		$tmpl_row = $tmp_row[1];
		$tmpl_row = str_replace('{NEWS_LINK}',$link.$row['cpu'].'/',$tmpl_row);
		$tmpl_row = str_replace('{NEWS_NAME}',$row['name'],$tmpl_row);
		$tmpl_row = str_replace('{NEWS_DATE}',$date,$tmpl_row);
		$tmpl_row = str_replace('{NEWS_IMG}',$img,$tmpl_row);
		$tmpl_row = str_replace('{NEWS_ANONS}',$row['anons'],$tmpl_row);
		echo $tmpl_row;
		echo $tmp_delim[1];
	}
	echo $tmp_end[1];
	echo '<div align="right">';
	draw_pages($page_size, $total_number, $page_name, "index.php" );
	echo '</div>';

}else{
	$row = $q->select1("SELECT * FROM ".$prefix."news WHERE status=1 AND
	cpu=".to_sql($cpu));
	$date = date('d.m.Y',to_phpdate($row['created']));

	$tmpl_row = file_get_contents($inc_path.'protoblocks/news/templates/news_full.php');
	$tmpl_row = str_replace('{NEWS_NAME}',$row['name'],$tmpl_row);
	$tmpl_row = str_replace('{NEWS_ANONS}',$row['anons'],$tmpl_row);
	$tmpl_row = str_replace('{NEWS_DATE}',$date,$tmpl_row);
	$tmpl_row = str_replace('{NEWS_TEXT}',$row['text'],$tmpl_row);
	echo $tmpl_row;

	echo '<div><a href="'.$link.'">Все новости</a></div>';
}


?></div>



<?
include($inc_path.'templates/bottom.php');
?>