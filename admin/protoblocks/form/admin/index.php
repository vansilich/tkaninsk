<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<div class="sub_menu">
<a href="form.php">������ �����</a>
</div>
<?

$table = new cTable('�����',$prefix.'form' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->order(' date_add desc');


$table->insertcol(new c_data('����','date_add',1,1));
/*$table->insertcol(new c_text('���������� ����','fio',1,1,$size='100',$max=''));
$table->insertcol(new c_text('E-mail','email',1,1,$size='100',$max=''));
$table->insertcol(new c_text('�������','phone',1,1,$size='100',$max=''));
*/
$table->insertcol(new c_textarea('������','order_text',1,1,'80','4'));



$table->draw();
include($inc_path."class/bottom_adm.php");
?>