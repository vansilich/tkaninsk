<? $starttimer = time()+microtime();
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$q = new query();


$q->exec("update ".$prefix."goods set last_price = price where price > 0");
die();


$kurs = $q->select1("select * from ".$prefix."kurs where val <> '' and kdate=".to_sql(date('Y-m-d')));
if($kurs == 0){
	/*$content = get_content(); 
	$pattern = '#<Valute ID="([^"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i'; 
	preg_match_all($pattern, $content, $out, PREG_SET_ORDER); 
	$dollar = ""; 
	$euro = ""; 
	foreach($out as $cur){ 
		if($cur[2] == 840) $dollar = str_replace(",",".",$cur[4]); 
		if($cur[2] == 978) $euro = str_replace(",",".",$cur[4]); 
	}*/
	if($euro > 0){
		$q->exec("insert into ".$prefix."kurs set val=".to_sql($euro).",kdate=".to_sql(date('Y-m-d')));
		$kurs = $q->select1("select * from ".$prefix."kurs where kdate=".to_sql(date('Y-m-d')));
	}else{
	        $kurs = $q->select1("select * from ".$prefix."kurs where val <> '' order by id desc");
	}
}




echo 'evro='.$kurs['val'].'<br>';

$q->exec("update ".$prefix."goods set last_price = price where price > 0");
if(!empty($kurs['val'])){ 
	$q->exec("update ".$prefix."goods set last_price = price_evro*".$kurs['val']." where price_evro > 0");
//	echo "update ".$prefix."goods set last_price = price_evro*".$kurs['val']." where price_evro > 0";
}


//$maker = $q->select("select id,sales from ".$prefix."maker where sales > 0 or id=2");
$maker = $q->select("select id,sales from ".$prefix."maker ");
$t_berreta = $kurs['val']*1.015;
//$t_berreta = $kurs['val'];
foreach($maker as $row){
	$maker_sale[$row['id']] = $row['sales'];
	if($row['sales'] == 0){
		$sales = 1;	
	}else{
		$sales = 1-$row['sales']/100;
	}
	if($sales > 0){
		$q->exec("update ".$prefix."goods set last_price = price*".$sales." where price > 0 and maker=".to_sql($row['id']));
		if(!empty($kurs['val'])){
		
			if($row['id'] == 2){
				//$q->exec("update ".$prefix."goods set last_price = price_evro*".(31.2)."*".$sales." where price_evro > 0 and maker=".to_sql($row['id']));
				$q->exec("update ".$prefix."goods set last_price = price_evro*".($t_berreta)."*".$sales." where price_evro > 0 and maker=".to_sql($row['id']));
				echo $t_berreta;
			}else{ 
				$q->exec("update ".$prefix."goods set last_price = price_evro*".$kurs['val']."*".$sales." where price_evro > 0 and maker=".to_sql($row['id']));
			}
		}
	}
}



$maker = $q->select("select * from ".$prefix."catalog_sales where sales > 0");
foreach($maker as $row){
	$sales = 1-$row['sales']/100;
	if($sales > 0){
		$q->exec("update ".$prefix."goods set last_price = price*".$sales." where price > 0 and cat_id=".to_sql($row['catalog'])." and maker=".to_sql($row['maker_id']));
		if(!empty($kurs['val'])){ 
			
				$q->exec("update ".$prefix."goods set last_price = price_evro*".$kurs['val']."*".$sales." where price_evro > 0 and cat_id=".to_sql($row['catalog'])."  and maker=".to_sql($row['maker_id']));
			
		}
	}
}


/*
$price=0;
if(!empty($row['price_evro'])){
	$price = round($row['price_evro']*$kurs['val']);
}else{
	$price = $row['price'];			
}
if(!empty($maker_sale[$row['maker']])){			
	$price = round($price-($price*$maker_sale[$row['maker']]/100));
}
*/



$stoptimer = time()+microtime();
$timer = round($stoptimer-$starttimer,10);
echo "\n выполнено за ".$timer." s <br>";


echo '<a href="index.php"><b>Назад</b></a><br><br>';  
?>
