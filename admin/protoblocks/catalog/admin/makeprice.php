<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

function get_content() { 
	$date = date("d/m/Y"); 
	$link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=$date"; 
	$fd = fopen($link, "r"); 
	$text=""; 
	if (!$fd) echo "¦ðÿ¨ð°øòðõüð  ¸ª¨ðýø¡ð ýõ ýðùôõýð"; 
	else 
	{ 
		while (!feof ($fd)) $text .= fgets($fd, 4096); 
	} 
	fclose ($fd); 
	return $text; 
}


	
$kurs = $q->select1("select * from ".$prefix."kurs where kdate=".to_sql(date('Y-m-d')));
$q->exec("update ".$prefix."goods set price = price_r ");
if($kurs == 0){
	$content = get_content(); 
	$pattern = '#<Valute ID="([^"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i'; 
	preg_match_all($pattern, $content, $out, PREG_SET_ORDER); 
	$dollar = ""; 
	$euro = ""; 
	foreach($out as $cur){ 
		if($cur[2] == 840) $dollar = str_replace(",",".",$cur[4]); 
		if($cur[2] == 978) $euro = str_replace(",",".",$cur[4]); 
	}
	if($euro > 0){
		$q->exec("insert into ".$prefix."kurs set evro=".to_sql($euro).",dol=".to_sql($dollar).",kdate=".to_sql(date('Y-m-d')));
		$kurs = $q->select1("select * from ".$prefix."kurs where kdate=".to_sql(date('Y-m-d')));
	}else{
		$kurs = $q->select1("select * from ".$prefix."kurs order by id desc");
	}
}

echo $kurs['dol'].'/'.$kurs['evro'];

$q->exec("update ".$prefix."goods set price = price_e*".($kurs['evro'])." where price_e > 0 ");
$q->exec("update ".$prefix."goods set price = price_d*".($kurs['dol'])." where price_d > 0 ");

$cats = $q->select("select id, skidka, name from ".$prefix."catalog where skidka>0 order by name");

foreach($cats as $row){
	echo '<br>'.$row['skidka'].'% - '.$row['name'];
	$skidka = (100-$row['skidka'])/100;
	
	$child = $q->select("select id, skidka, name from ".$prefix."catalog where skidka=0 and parent=".to_sql($row['id'])." order by name");
	foreach($child as $c){
		$q->exec("update ".$prefix."goods set price = price*".($skidka)." where catalog =".to_sql($c['id']));	
		
		$child2 = $q->select("select id, skidka, name from ".$prefix."catalog where skidka=0 and parent=".to_sql($c['id'])." order by name");
		foreach($child2 as $c2){
	        	$q->exec("update ".$prefix."goods set price = price*".($skidka)." where catalog =".to_sql($c2['id']));	
		}
		
	}
        

	
	$q->exec("update ".$prefix."goods set price = price*".($skidka)." where catalog =".to_sql($row['id']));
}
$q->exec("update ".$prefix."goods set price = price*(100-skidka)/100 where skidka>0");



?>