<?
header("Content-Type: text/plain");
$inc_path = "admin/"; $root_path="" ; include($inc_path."class/header.php");	$q = new query();
$_settings = $q->select1("select * from ".$prefix."settings where id='robots'");
echo $_settings['block'];
?>