<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$this_block_id = $_GET['this_block_id'];
?>
<div class="sub_menu">
<a href="./">�����</a>
</div>
<?
	$table = new cTable('���� �� ������� �����',$prefix.'main','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' types=2 ');
	$table->insertparam('types',2);

	$table->insertcol(new c_text('���������','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_image('��������','img',1,1,$inc_path.'../files/main/', ''));
	$table->insertcol(new c_text('������','url',1,1,$size='100',$max=''));	
	$table->draw();


?>