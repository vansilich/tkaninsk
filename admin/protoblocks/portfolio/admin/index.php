<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<style>
.move{width:2%;cursor:move; text-align:center;}
.tdcell_link{width:20%; overflow:hidden; }
.tdcell_img{width:10%; overflow:hidden;}
.tdcell_name{width:20%; overflow:hidden;}
.tdcell_best{width:10%; overflow:hidden;}
.tdcell_name2{width:20%; overflow:hidden;}
.tdcell_answer{width:20%; overflow:hidden;}
.td_cell_center{width:80%;};



</style>
<?

$this_block_id = get_param('this_block_id');

$table = new cTable2('�������',$prefix.'port' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);


$table->insertcol(new c_text('��������','name',1,1,$size='100',$max=''));
$table->insertcol(new c_text('��� ������','name2',1,1,$size='100',$max=''));

$select = Array();
$select[0] = '---';
for($i=2007;$i<=date('Y');$i++){
	$select[$i] = $i;
}

//$table->insertcol(new c_select('���','year',1,1,$select));
$table->insertcol(new c_ajax_checkbox('������','best',1,1));
//$table->insertcol(new c_checkbox('���������','last',1,1));

$table->insertcol(new c_text('������','link',1,1,$size='100',$max=''));

$table->insertcol(new c_image('������','img',1,1,$inc_path.'../files/port/', ''));
/*$table->insertcol(new c_image('�������� �� ��.','img_main',1,1,$inc_path.'../files/port/main/', '125'));

$table->insertcol(new c_image('��������1','img1',0,1,$inc_path.'../files/port/1/', '125;500xauto;800xauto'));
$table->insertcol(new c_text('��������1 �������','img1_text',0,1,$size='100',$max=''));

$table->insertcol(new c_image('��������2','img2',0,1,$inc_path.'../files/port/2/', '125;500xauto;800xauto'));
$table->insertcol(new c_text('��������2 �������','img2_text',0,1,$size='100',$max=''));

$table->insertcol(new c_image('��������3','img3',0,1,$inc_path.'../files/port/3/', '125;500xauto;800xauto'));
$table->insertcol(new c_text('��������3 �������','img3_text',0,1,$size='100',$max=''));

$table->insertcol(new c_image('��������4','img4',0,1,$inc_path.'../files/port/4/', '125;500xauto;800xauto'));
$table->insertcol(new c_text('��������4 �������','img4_text',0,1,$size='100',$max=''));

$table->insertcol(new c_fck('��������','text',0,1,$height='400'));*/
$table->insert_action('������� �����', 'copy', 'zid');

$table->draw();

?>
<? include($inc_path."class/bottom_adm.php"); ?>