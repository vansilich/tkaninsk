<? 
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../";	$root_path = "../"; 
include($inc_path."class/header_adm_inc.php");
$q = new query();
$id_name = get_param('id_name');
$id = get_param('id');
$table = get_param('table');
$val = get_param('val');
$name = get_param('name');
?>
<div>
<div id="text"><? 
if(!empty($name) && !empty($table) && !empty($id_name) && !empty($id)){
	$q->exec("update ".$table." set ".$name." = ".to_sql($val)." where ".$id_name."=".to_sql($id));
}
	
?></div>
<div id="id"><? echo $id;?></div>



</div>
