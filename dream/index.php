<?php

$inc_path = "/var/www/user1893611/data/www/tkaninsk.com/admin/"; 
$root_path="/var/www/user1893611/data/www/tkaninsk.com/" ; 
include($inc_path."class/header.php");
include($root_path.'dream/config.php');				
include($root_path.'dream/api.php');		
$q = new query();
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Интернет магазин тканей в Новосибирске - купить в розницу и оптом</title>
<meta name="description" content="">
<meta name="Keywords" content="">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css?v=2843" />
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<!-- JavaScript includes -->

</head>


<body>
<?


$orders = $q->select("select * from ".$prefix."orders where  dream_status=1 and dream_id_operations!=''");

foreach($orders as $o){
	$status = get_operation($o['dream_id_operations']);
	if($status == 'SUCCESS'){
		$q->exec("update ".$prefix."orders set dream_status=2 where id=".to_sql($o['id']));	
	}
	if($status == 'ERROR'){
		$q->exec("update ".$prefix."orders set dream_status=0, dream_id_operations='' where id=".to_sql($o['id']));	
	}
		
}



$orders = $q->select("select * from ".$prefix."orders where order_status=1 and dream_status=0
and id>134 
  order by id desc limit 1");
echo 'zzz';
foreach($orders as $o){
	$data = "{
	  \"deviceId\": ".$id_kassa.",
	  \"type\": \"SALE\",
	  \"timeout\": 180,
	  \"taxMode\": \"DEFAULT\",
	  \"positions\": [";

	$full = $q->select("select * from ".$prefix."orders_full where order_id = ".to_sql($o['id']));
	$sum = 0;
	$z = 0;
	foreach($full as $f){
		$good = $q->select1("select * from ".$prefix."goods where id=".to_sql($f['good_id']));
		
		if($good['edizm'] == 'шт'){
			$f['col'] = $f['col'];
			$f['price'] = $f['price']*100;
			$sum += $f['price']*$f['col'];
			$good['name']=str_replace('"','',$good['name']).', кол-во в шт.';
			if($z > 0) $data .= ',' ;
			$data .= "{
			  \"name\": \"".$good['name']."\",
			  \"type\": \"SCALABLE\",
			  \"quantity\": ".$f['col'].",
			  \"price\": ".$f['price'].",
			  \"priceSum\": ".($f['price']*$f['col'])."    }";

		}else{
		
			$f['col'] = $f['col']*100;
			$f['price'] = $f['price'];
			$sum += $f['price']*$f['col'];
			$good['name']=str_replace('"','',$good['name']).', кол-во в см.';
			if($z > 0) $data .= ',' ;
			$data .= "{
			  \"name\": \"".$good['name']."\",
			  \"type\": \"SCALABLE\",
			  \"quantity\": ".$f['col'].",
			  \"price\": ".$f['price'].",
			  \"priceSum\": ".($f['price']*$f['col'])."    }";
		}
		  $z++;
	}
	
	if($o['delivery_price']>0){
		if($z > 0) $data .= ',' ;
		$sum += $o['delivery_price']*100;
		$data .= "{
		  \"name\": \"Доставка\",
		  \"type\": \"SCALABLE\",
		  \"quantity\": 1,
		  \"price\": ".($o['delivery_price']*100).",
		  \"priceSum\": ".($o['delivery_price']*100)."    }";	
	}
	
	$data .= "],
	  \"payments\": [
		{
		  \"sum\": ".$sum.",
		  \"type\": \"CASHLESS\"
		}
	  ],
	  \"attributes\": {
		\"email\": \"".$o['email']."\",
		\"phone\": \"".$o['phone']."\"
	  },
	  \"total\": {
		\"priceSum\": ".$sum."
	  }
	}";
	
	$oper = send_check($data);
	if(!empty($oper)){
	$q->exec("update ".$prefix."orders set dream_status=1,dream_id_operations=".to_sql($oper)." where id=".to_sql($o['id']));
	}
	echo '<pre>';
	echo $data;
	echo '</pre>';
	echo 'num='.$oper;
}

echo 'xxx';



$orders = $q->select("select * from ".$prefix."orders where id=142
  order by id desc limit 1");

foreach($orders as $o){
	$data = "{
	  \"deviceId\": ".$id_kassa.",
	  \"type\": \"SALE\",
	  \"timeout\": 180,
	  \"taxMode\": \"DEFAULT\",
	  \"positions\": [";

	$full = $q->select("select * from ".$prefix."orders_full where order_id = ".to_sql($o['id']));
	$sum = 0;
	$z = 0;
	foreach($full as $f){
		$good = $q->select1("select * from ".$prefix."goods where id=".to_sql($f['good_id']));
		
		$f['col'] = $f['col']*100;
		$f['price'] = $f['price'];
		$sum += $f['price']*$f['col'];
		//$good['name']=str_replace('"','',$good['name']).', кол-во в см.';
		if($z > 0) $data .= ',' ;
		$data .= print_r($good,true).'='.$f['good_id']."{
		  \"name\": \"".$good['name']."\",
		  \"type\": \"SCALABLE\",
		  \"quantity\": ".$f['col'].",
		  \"price\": ".$f['price'].",
		  \"priceSum\": ".($f['price']*$f['col'])."    }";
		  $z++;
	}
	$data .= "],
	  \"payments\": [
		{
		  \"sum\": ".$sum.",
		  \"type\": \"CASHLESS\"
		}
	  ],
	  \"attributes\": {
		\"email\": \"".$o['email']."\",
		\"phone\": \"".$o['phone']."\"
	  },
	  \"total\": {
		\"priceSum\": ".$sum."
	  }
	}";
	
	//mail('vakas@ya.ru','test',$data);
}

		
	  

//get_goods();
//send_check();
/*
string(91) "{"id":"5b360724eaa6a0206cfe2d59","createdAt":"2018-06-29T10:17:08.431Z","status":"PENDING"}" 

5b360a89e4f8ff20502ee74b

5b360b25eaa6a0206cfe3b41

5b39feacb88c451a4d6e0e72
5b39ff2cb88c451a4d6e0fc8

5b3a0015d7c98d19df735f6b
5b3a0792b88c451a4d6e2d1b
5b3a0861520c9619cf29f0d4
string(91) "{"id":"5b34d57ceaa6a0206cfc4a46","createdAt":"2018-06-28T12:33:00.371Z","status":"PENDING"}" done

*/


?>