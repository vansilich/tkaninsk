<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$q = new query();
$cur_ctgr = (int)get_param('cur_ctgr');
echo '<a href="index.php?cur_cat='.$cur_ctgr.'"><b>�����</b></a><br><br>';



	$cat = $q->select1("select name from ".$prefix."catalog where id=".to_sql($cur_ctgr));

	$table = new cTable('������ ��������� "'.$cat['name'].'"',$prefix.'catalog_sales','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(15);
	$table->settings(' catalog ='.$cur_ctgr);
	$table->draw_params('cur_ctgr='.$cur_ctgr);
	$table->insertparam('catalog', $cur_ctgr);	
	$table->insertcol(new c_text('������','sales',1,1,$size='100',$max=''));
	
	
	$select = Array();
	$select[''] = '�� ������';
	$maker = $q->select("select * from ".$prefix."maker order by name");
	foreach($maker as $row) $select[$row['id']] = $row['name'];
	$table->insertcol(new c_select('�������������','maker_id',1,1,$select));	
	
	$table->draw();

?>
