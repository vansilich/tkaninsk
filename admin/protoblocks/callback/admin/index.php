<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");


$table = new cTable('Заявки',$prefix.'ospec' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(10);
$table->order(" date_add desc ");
$table->insertcol(new c_data('Дата','date_add',1,1));
$table->insertcol(new c_text('Имя','name',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Телефон','phone',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Email','email',1,1,$size='100',$max=''));



//$table->insertcol(new c_textarea('adres','adres',0,1,60,5));
$table->insertcol(new c_textarea('Текст','text',1,1,60,5));

$table->insertcol(new c_text('ip','ip',0,1,$size='100',$max=''));
$table->draw();
include($inc_path."class/bottom_adm.php");
?>