<?

$inc_path = '../../../';
include($inc_path."class/header_adm.php");


$table = new cTable('Вопрос-ответ',$prefix.'faq' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(10);
$table->insertcol(new c_data('Дата','date_add',1,1));
$table->insertcol(new c_text('ФИО', 'name',1,1,$size='100',$max=''));
$table->insertcol(new c_text('E-mail', 'email',1,1,$size='100',$max=''));
$table->insertcol(new c_fck('Текст','text',1,1,$height='400'));
$table->insertcol(new c_fck('Ответ', 'answer',1,1,$height='400'));
$table->draw();

include($inc_path."class/bottom_adm.php");
?>