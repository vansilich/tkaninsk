<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?
$this_block_id = get_param('this_block_id');
$cat_id = get_param('cat_id');

$q = new query();

$product_id = (int)get_param('gid');

	$prod = $q->select1("select * from ".$prefix."goods where id=".to_sql($product_id));
	$cat_id = (int)$prod['catalog'];

	echo '<h2>'.$prod['name'].'</h2>';

	$table = new cTable('�����',$prefix.'files','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(10);
	$table->settings(" gid=".to_sql($product_id));
	$table->draw_params('gid='.$product_id);
	$table->insertparam('gid',$product_id);

	$table->insertcol(new c_text('���������','name',1,1,$size='100',$max=''));
	
//	$table->insertcol('����','ext',1,1,'file_id',$inc_path.'../files/goodfiles/');
//	$table->insertcol(new c_anyfile('����','ext',1,1,$inc_path.'../files/goodfiles/'));
	$table->insertcol(new c_fileid('������','ext',1,1,$inc_path.'../files/goodfiles/'));

	
	$table->insertcol(new c_text('������','url',1,1,$size='100',$max=''));
	$table->draw();

echo '<br><br><a href="goods.php?cat_id='.$cat_id.'&this_block_id='.$this_block_id.'">�����</a>';


?>