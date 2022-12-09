<? 
header('Content-Type: text/html; charset=Windows-1251');
$inc_path = "../";	$root_path = "../"; 
include($inc_path."class/header_adm.php");
$q = new query();
$id_name = get_param('id_name');
$id = get_param('id');
$table = get_param('table');
$field_name = get_param('field_name');
$value = get_param('value');


?>
<div>
	<div id="text"><? 
		$q->exec("update ".$table." set ".$field_name." = ".to_sql($value)." where ".$id_name."=".to_sql($id));
	?></div>
	<div id="id"><? echo $id;?></div>
</div>
