<?php

$inc_path = "../admin/"; 
$root_path="../" ; 
include($inc_path."class/header.php");		
$q = new query();


$q->exec("insert into ".$prefix."dream_kassa set url=".to_sql($_SERVER['REQUEST_URI']).",fname=".to_sql(get_get('filename')).", datas=NOW()");
?>