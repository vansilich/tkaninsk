<?
$inc_path = "../";
$_menu_type = 'admin';

include($inc_path."class/header_adm.php");


$id_block = get_param('id_block');
$block = $q->select1("select * from ".$prefix."blocks where id=".to_sql($id_block));
if(!empty($block)){
	$table = new cTable('Файлы '.$block['name'],$prefix.'block_files','id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(15);
	
	$table->draw_params('id_block='.$id_block);
	$table->settings(' block_id='.$id_block);
	$table->insertparam('block_id',$id_block);
	
	$table->insertcol(new c_anyfile('Файл','file_name',1,1,$inc_path.'protoblocks/'.$block['folder'].'/'));
	
	$select = Array();
	$select[1] = 'yes';
	$select[2] = 'no';
	$table->insertcol(new c_select('Генерить шаблон','gen_page',1,1,$select));
	
	
	
	$table->draw();
}
?>
<?
include($inc_path."class/bottom_adm.php");
?>
