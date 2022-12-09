<?$inc_path = "../../admin/"; $root_path="../../" ;include($inc_path."class/header.php");$this_page_id = 6;	$q = new query();
			$site_pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');?><? $this_block_id = 9;?>
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
			//$where = "and catalog=".to_sql($this_cat['id'])."";
			$where = " and catalog in (".$_all_child.") ";
			$for_search_where = " and catalog in (".$_all_child.") ";
			$draw_params = array();
			$pf = get_param('pf');	
			$pt = get_param('pt');	
			if(!empty($pf)){
				$where .= " and price >=".to_sql($pf);
				$draw_params['pf']= $pf;
			}
			
			if(!empty($pt)){
				$where .= " and price <=".to_sql($pt);
				$draw_params['pt']= $pt;
			}
?>
  <div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="menu-left">
      <div class="menu-left-sm">
      <div class="select-div"><a href="#0" onclick="$('#filtr').toggle();return false;">Фильтры <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      </div>
      <div class="menu-left-big">
<?
if(empty($cpu)) include($inc_path.'protoblocks/catalog/search.php');
?>      
<? /*      
      <div class="select-div"><a href="">Основной состав <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Плотность, гр/м. пог. <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Ширина, см <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Производитель <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Цвет <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Назначение <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      
*/ ?>
      <ul>      
<?
$catalog = $q->select("select id,name,cpu from ".$prefix."catalog where parent=0 and status=1 order by name");
foreach($catalog as $c){
	echo '<li> <a href="/catalog/'.$c['cpu'].'/" class="one">'.$c['name'].'</a>';
	$catalog2 = $q->select("select id,name,cpu from ".$prefix."catalog where parent=".to_sql($c['id'])." and status=1 order by name");
	if(sizeof($catalog2) > 0){
		echo '<ul>';
		foreach($catalog2 as $c2){
			echo '<li> <a href="/catalog/'.$c2['cpu'].'/" class="two">'.$c2['name'].'</a>';
			$catalog3 = $q->select("select id,name,cpu from ".$prefix."catalog where parent=".to_sql($c2['id'])." and status=1 order by name");
			if(sizeof($catalog3) > 0){
				echo '<ul>';
				foreach($catalog3 as $c3){
					echo '<li> <a href="/catalog/'.$c3['cpu'].'/" class="three">'.$c3['name'].'</a>';
					echo '</li>';
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	echo '</li>';
}
?>      
        </ul>
      
      
      
      
    </div>
    </div>
    </div>
    <div class="col-md-9 col-sm-12 col-xs-12">
    


<?
		$page_size = 24;
		$page_name = 'page';	
?>
<div id="crumbs"> 
	<?
	echo '<a href="/" class="grey_a">Главная</a>&nbsp; / &nbsp;';
		echo '<a href="/catalog/" class="grey_a" >'.$site_pages->get_page_name($this_page_id).'</a> ';
		if(!empty($this_cat['all_parent'])){
			$cats = $q->select("select id,cpu,name from ".$prefix."catalog where id in (".$this_cat['all_parent'].") order by level");
			foreach($cats as $c){
				echo '&nbsp; / &nbsp;<a href="/catalog/'.$c['cpu'].'/" class="grey_a">'.$c['name'].'</a>';
			}
		}
		if(!empty($this_good['name'])){
			echo '&nbsp; / &nbsp;<a href="/catalog/'.$this_cat['cpu'].'/" class="grey_a">'.$this_cat['name'].'</a>';
			echo '&nbsp; / &nbsp;'.$this_good['name'].'';
		}else{
			if(!empty($this_cat['name'])){
				echo '&nbsp; / &nbsp;'.$this_cat['name'].'';
			}
		}
?>    
	</div>

<?
$q = new query(); 
if($_SESSION['back_url'] != $_SERVER['REQUEST_URI']){
	$_SESSION['back_url_old'] = $_SESSION['back_url'];
	$_SESSION['back_url'] = $_SERVER['REQUEST_URI'];
}

if(empty($this_good) && empty($this_cat)){		
?>	
<div id="catalog_tovarov">
<?

	$catalog = $q->select("select id,name,cpu,img from ".$prefix."catalog where parent=0 and status=1 order by name");
		foreach($catalog as $c){
			$file = 'files/razd/'.$c['id'].$c['img'];
				$img = '';
				if(is_file($root_path.$file)){
					$img = '<img src="/'.$file.'" border="0" >';
				}
			echo '<div class="col_t">'.$img.'
		   <div class="h2"><a href="/catalog/'.$c['cpu'].'/">'.$c['name'].'</a></div>
		   </div>';
			
			
			$child = $q->select("select id,name,cpu from ".$prefix."catalog where parent=".to_sql($c['id'])." and status=1 order by name");
			if(!empty($child)){
				echo '<div class="row">';
				foreach($child as $c2){
					echo '<div class="col-md-4"><a href="/catalog/'.$c2['cpu'].'/">'.$c2['name'].'</a></div>';
				}
				echo '
				</div>
';
			}
			echo '
			<hr class="hr2" />
			';	
		}
?>		
   <br class="clear" />
   </div>	
	
	
	
<?

}elseif(empty($this_good)){		

		if(!empty($this_catalog['id'])){
			echo '<h1>'.$this_catalog['name'].'</h1>';
		}

	
		$cats = $q->select("SELECT * FROM ".$prefix."catalog where status=1 and parent=".to_sql($this_catalog['id'])." order by name  ");
		$numb = $q->select1("select count(id) as number from ".$prefix."catalog where status=1 and parent=".to_sql($this_catalog['id'])."");
		$cnt = count($data);
		$total_number = $numb['number'];
		

		if(!empty($this_catalog['id'])){
			
	
	
	
	
			/***************************************************/
			/********************    Поиск    *******************************/
			/***************************************************/
			
			//include($inc_path.'protoblocks/catalog/search.php');
			/***************************************************/
			/********************    end Поиск    *******************************/
			/***************************************************/			
			$order = ' order by rank2 ';
			
			
			
			if(empty($this_cat['id'])) $this_cat['id'] = 0;
			if(!empty($par_where)){
		
				if($par_col >1){
					$sql_d = " group by P.good_id having count(*)=".$par_col;
				}
				$sql = "select G.* from ".$prefix."goods as G 
				join 			
				(
					select distinct good_id
					from ".$prefix."goods_param as P
					where  ".$par_where."  ".$sql_d."
				) as PP on G.id = PP.good_id
				where  G.status=1 ".$where." ".$order." LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size;
				
				$goods = mysql_query($sql,$db);
				
				$numb = $q->select1("select count(*) as number from ".$prefix."goods as G 
				join 
				(
					select distinct good_id
					from ".$prefix."goods_param as P
					where  (".$par_where.")  ".$sql_d."
				) as PP on G.id = PP.good_id			
				where status=1 ".$where."  ");
				$total_number = $numb['number'];
			}else{			
				$sql = "select G.*, price from ".$prefix."goods as G
				where G.status=1 ".$where." ".$order." LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size;
				// G.catalog = ".to_sql($this_cat['id'])."  and
				$goods = mysql_query($sql,$db);
				$numb = $q->select1("select count(*) as number from ".$prefix."goods as G
				where G.status=1 ".$where."");
				$total_number = $numb['number'];
			}
			//echo $sql;

						if($total_number >0){
							
							//draw_pages($page_size, $total_number, $page_name, '',$draw_params );
							include($inc_path.'templates/goods.php');

							draw_pages($page_size, $total_number, $page_name, "" );


			

			}//if($total_number >0){

	//&& $show!='model'
			if($pn == 0 ){ 
				echo '<br/>'.$this_catalog['text'];
			}//if($pn == 0 && $show!='model'){ 

	}//if(!empty($this_catalog['id']))
?>

<?
}else{//if(empty($nname)){	
	include($inc_path.'protoblocks/catalog/good.php');
}

?>
    </div>
    
</div>
 </div>



<?
include($inc_path.'templates/bottom.php');
?>