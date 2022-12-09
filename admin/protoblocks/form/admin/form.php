<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<div class="sub_menu">
<a href="./">Назад</a><br />
</div>
<?


$table = new cTable('Поля',$prefix.'form_field' ,'id',$is_rank=1, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));

$select = Array();
$select['c_text']='text';
$select['c_email']='Email';
$select['c_check']='Checkbox';
$select['c_radio']='Radio';
$select['c_textarea']='Textarea';
$select['c_select']='Селект';
$table->insertcol(new c_select('Тип поля','types',1,1,$select));


$table->insert_action('Список значений', 'value', 'fid', 'c_iframe');


$table->draw();
include($inc_path."class/bottom_adm.php");

?>