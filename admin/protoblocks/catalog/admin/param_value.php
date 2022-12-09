<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$q = new query();


$pid = get_param('pid');
$param = $q->select1("select * from ".$prefix."adv_params where id=".to_sql($pid));


	$table = new cTable('Значения для '.$param['name'],$prefix.'adv_params_value','id',$is_rank=1, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' pid ='.$pid);
	$table->draw_params('pid='.$pid);
	$table->insertparam('pid', $pid);

	$table->insertcol(new c_text('название','name',1,1,$size='100',$max=''));
	$table->draw();





include($inc_path."class/bottom_adm.php");

?>
