<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$this_block_id = $_GET['this_block_id'];
?>
<div class="sub_menu">
<a href="./">�����</a>
</div>
<?
	$table = new cTable('������� ���� �� �������',$prefix.'main_cat','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);

	$table->insertcol(new c_textarea('���������','name',1,1,60,2));
	$table->insertcol(new c_image('��������','img',1,1,$inc_path.'../files/maincat/', ''));
	$table->insertcol(new c_text('������','url',1,1,$size='100',$max=''));	
	$table->draw();
?>