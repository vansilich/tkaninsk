<? 
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../";	$root_path = "../"; 
include($inc_path."class/header_adm.php");
$q = new query();
$s = get_param('s');
$table = get_param('table');


?>
<div>
<div id="text"><? 
if(!empty($table)){
	$msort = array();
	$ids = array();
	foreach($s as $v){
		$m = explode('_',$v);
		$msort[] = $m[2]; //запоминаем rank
		$ids[] = $m[1]; //запоминаем id
	}
	arsort($msort); //сортируем массив
	$msort2 = array();
	foreach($msort as $v) $msort2[] = $v; // создаем массив в нужном порядке
	
	foreach($ids as $k=>$v){
//		echo 'id['.$v.']='.$msort2[$k].'<br>';	
		$q->exec("update ".$table." set rank = ".to_sql($msort2[$k])." where id=".to_sql($v));
	}
}
?></div>




</div>
