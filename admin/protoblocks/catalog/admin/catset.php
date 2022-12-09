<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$q = new query();
$cur_cat = (int)get_param('cur_cat');
$cat = $q->select1("select name from ".$prefix."catalog where id=".to_sql($cur_cat));

echo '<a href="index.php?cur_cat='.$cur_cat.'"><b>Назад</b></a><br><br>';

	$table = new cTable('Настройки категории "'.$cat['name'].'"',$prefix.'catalog','id',$is_rank=0, $is_status=0, $is_add=0,$is_edit=1,$is_del=0);
	$table->set_page_size(15);
	$table->settings(' id ='.$cur_cat);
	$table->draw_params('cur_cat='.$cur_cat);
	$table->insertcol(new c_text('title','title',1,1,$size='100',$max=''));
	$table->insertcol(new c_textarea('description','description',1,1,60,5));
	$table->insertcol(new c_textarea('keywords','keywords',1,1,60,5));
	$table->insertcol(new c_fck('Текст','text',1,1,$height='400'));

	
	$table->draw();

?>
