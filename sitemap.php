<?
$inc_path = "admin/"; $root_path="" ; include($inc_path."class/header.php");	$q = new query();
$main_domen = 'mcflyshop.ru';
$domain = $_SERVER['HTTP_HOST'];
if($domain == 'www.mcflyshop.ru') $domain = 'mcflyshop.ru';

if($domain != $main_domen){
	$city_cpu = str_replace('.mcflyshop.ru','',$domain);
	
	$city = $q->select1("select * from ".$prefix."city where cpu=".to_sql($city_cpu));
	if(!empty($city)){
		
	}
}

$data = '';			
$data .= '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
<!-- Last update of sitemap '.date('c').' -->
';

if($domain != $main_domen){
	$pages = $q->select("select id,uri from ".$prefix."pages where status=1 and id in(25) ");
	foreach($pages as $p){
		$data .= '
		<url>
		<loc>http://'.$domain.''.$p['uri'].'</loc>
		<lastmod>'.date('c').'</lastmod>
		<changefreq>daily</changefreq>
		</url>';
		
	}
}else{
	$pages = $q->select("select id,uri from ".$prefix."pages where id>1 ");
	foreach($pages as $p){
		$data .= '
		<url>
		<loc>http://'.$domain.''.$p['uri'].'</loc>
		<lastmod>'.date('c').'</lastmod>
		<changefreq>daily</changefreq>
		</url>';
		
	}
	
	
	
}
$temp = $q->select("SELECT cpu FROM ".$prefix."catalog WHERE status=1 order by level");
	foreach($temp as $row){
		$data .= '
		<url>
		<loc>http://'.$domain.'/catalog/'.$row['cpu'].'/</loc>
		<lastmod>'.date('c').'</lastmod>
		<changefreq>daily</changefreq>
		</url>';	
	}
	$temp = $q->select("SELECT cpu FROM ".$prefix."goods WHERE status=1 order by id");
	foreach($temp as $row){
		$data .= '
		<url>
		<loc>http://'.$domain.'/goods/'.$row['cpu'].'/</loc>
		<lastmod>'.date('c').'</lastmod>
		<changefreq>daily</changefreq>
		</url>';	
	}

$data .= '</urlset>';

echo  $data;

?>