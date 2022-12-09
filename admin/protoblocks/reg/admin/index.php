<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

?>
<div class="sub_menu"><a href="export.php" target="_blank">Экспорт</a></div>
<?

$table = new cTable('Пользователи',$prefix.'members' ,'id',$is_rank=0, $is_status=0, $is_add=$this_block_edit,$is_edit=$this_block_edit,$is_del=$this_block_del);
$table->set_page_size(10);
$table->insertcol(new c_data('Дата регистрации','date_reg',1,0));
$table->insertcol(new c_text('email','mail',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Имя','name',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Фамилия','surname',1,1,$size='100',$max=''));
$table->insertcol(new c_checkbox('активирован','active',1,1));



$table->draw();


include($inc_path."class/bottom_adm.php");

?>