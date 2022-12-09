<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?

$this_block_id = get_param('this_block_id');

$table = new cTable('Заказы',$prefix.'orders' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->order(' dateadd desc');


$table->insertcol(new c_data('Дата','dateadd',1,1));
$table->insertcol(new c_text('Название компании','company',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Контактное лицо','face',1,1,$size='100',$max=''));
$table->insertcol(new c_text('E-mail','email',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Телефон','phone',1,1,$size='100',$max=''));
$table->insertcol(new c_text('site','site',1,1,$size='100',$max=''));
$table->insertcol(new c_text('город','city',1,1,$size='100',$max=''));

$table->insertcol(new c_textarea('Область Вашей деятельности','obl',1,1,'80','4'));
$table->insertcol(new c_textarea('Сайты со схожими задачами','likes',1,1,'80','4'));
$table->insertcol(new c_textarea('Общее описание задачи','task',1,1,'80','4'));



$table->draw();

?>