<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");



$table = new cTable('Письма',$prefix.'tmpl' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=0);
$table->set_page_size(30);
//$table->order(" created desc ");

	$table->insertcol(new c_text('Заголовок','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('Тема письма','theme',1,1,$size='100',$max=''));
	$table->insertcol(new c_textarea('Текст письма','text',0,1,60,10));

$table->draw();


include($inc_path."class/bottom_adm.php");

?>