<?php

$inc_path = "../admin/"; 
$root_path="../" ; 
include($inc_path."class/header.php");
include('config.php');				
include('api.php');		
$q = new query();
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Интернет магазин тканей в Новосибирске - купить в розницу и оптом</title>
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




$orders = $q->select("select * from ".$prefix."orders where 
order_status=1 and 
dream_status=2 and id=103 
  order by id desc limit 1");

foreach($orders as $o){
	$data = "{
	  \"deviceId\": ".$id_kassa.",
	  \"type\": \"REFUND\",
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
		$good['name']=str_replace('"','',$good['name']).', кол-во в см.';
		if($z > 0) $data .= ',' ;
		$data .= "{
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
	
	
	
	//$oper = send_check($data);
	/*
	$q->exec("update ".$prefix."orders set dream_status=1,dream_id_operations=".to_sql($oper)." where id=".to_sql($o['id']));
	*/
	echo $data;
	echo 'num='.$oper;
}

get_operation('5b3b5cec022dcf23bf208de2');

?>
</body>