<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$this_block_id = $_GET['this_block_id'];
$cat_id = (int)get_param('cat_id');
$table = new cTable('Мы рекомендуем',$prefix.'rekom','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(10);
//$table->insertcol(new c_text('Заголовок','title',1,1,$size='100',$max=''));
//$table->insertcol(new c_textarea('Анонс','anons',1,1,60,5));



$select = Array();
$table->settings(' cat_id ='.$cat_id);
$table->draw_params('cat_id='.$cat_id);
$table->insertparam('cat_id', $cat_id);	
$table->insertcol(new c_fck('Текст','text',1,1,$height='400'));
$table->insertcol(new c_text('id1','id1',1,1,$size='100',$max=''));
$table->insertcol(new c_text('id2','id2',1,1,$size='100',$max=''));
$table->insertcol(new c_text('id3','id3',1,1,$size='100',$max=''));
$table->insertcol(new c_text('id4','id4',1,1,$size='100',$max=''));
$table->draw();



?>