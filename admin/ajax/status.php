<? 
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../";	$root_path = "../"; 
include($inc_path."class/header_adm.php");
$q = new query();
$id_name = get_param('id_name');
$id = get_param('id');
$table = get_param('table');
$action = get_param('action');


?>
<div>
<div id="text"><? 
if($action == 'show'){
	$temp = "скрыть";
	$q->exec("update ".$table." set status = 1 where ".$id_name."=".to_sql($id));
	echo '<span onclick="status_off'.$table.'(\''.$id.'\')"						
	style="cursor:pointer;" class="btn_look dd" 
	 title="'.$temp.'"><i class="fa fa-eye"></i></span>';
}elseif($action == 'hide'){
	$temp = "показать";
	$q->exec("update ".$table." set status = 0 where ".$id_name."=".to_sql($id));
	echo '<span onclick="status_on'.$table.'(\''.$id.'\')" 
	style="cursor:pointer;" class="btn_lookno dd" title="'.$temp.'"><i class="fa fa-eye-slash"></i></span>';
}


if($action == 'catshow'){
	$temp = "скрыть";
	$q->exec("update ".$table." set status = 1 where ".$id_name."=".to_sql($id));
	echo '<span onclick="cat_status_off(\''.$id.'\')"						
	style="cursor:pointer;" class="btn_look dd" 
	 title="'.$temp.'"><i class="fa fa-eye"></i></span>';
}elseif($action == 'cathide'){
	$temp = "показать";
	$q->exec("update ".$table." set status = 0 where ".$id_name."=".to_sql($id));
	echo '<span onclick="cat_status_on(\''.$id.'\')" 
	style="cursor:pointer;" class="btn_lookno dd" title="'.$temp.'"><i class="fa fa-eye-slash"></i></span>';
}
	
?></div>
<div id="id"><? echo $id;?></div>



</div>
