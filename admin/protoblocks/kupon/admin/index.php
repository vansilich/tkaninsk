<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");


$table = new cTable('Купоны',$prefix.'kupon' ,'id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(40);
$table->order(' id desc ');
$table->insertcol(new c_data('Дата начала','date_beg',1,1));
$table->insertcol(new c_data('Дата конца','date_end',1,1));
$table->insertcol(new c_text('Заголовок','title',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Скидка в процентах','sale_proc',1,1,$size='100',$max=''));
//$table->insertcol(new c_text('Скидка в рублях','sale_sum',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Минимальная сумма заказа','min_sum',1,1,$size='100',$max=''));


$table->insertcol(new c_text('email','email',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Код','code',1,1,$size='100',$max=''));
$table->draw();




?>
<? include($inc_path."class/bottom_adm.php"); ?>