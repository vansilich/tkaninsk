<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?

$this_block_id = get_param('this_block_id');

$table = new cTable('������',$prefix.'orders' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->order(' dateadd desc');


$table->insertcol(new c_data('����','dateadd',1,1));
$table->insertcol(new c_text('�������� ��������','company',1,1,$size='100',$max=''));
$table->insertcol(new c_text('���������� ����','face',1,1,$size='100',$max=''));
$table->insertcol(new c_text('E-mail','email',1,1,$size='100',$max=''));
$table->insertcol(new c_text('�������','phone',1,1,$size='100',$max=''));
$table->insertcol(new c_text('site','site',1,1,$size='100',$max=''));
$table->insertcol(new c_text('�����','city',1,1,$size='100',$max=''));

$table->insertcol(new c_textarea('������� ����� ������������','obl',1,1,'80','4'));
$table->insertcol(new c_textarea('����� �� ������� ��������','likes',1,1,'80','4'));
$table->insertcol(new c_textarea('����� �������� ������','task',1,1,'80','4'));



$table->draw();

?>