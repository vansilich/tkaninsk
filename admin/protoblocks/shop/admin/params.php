<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?
function mysql_field_array( $query ) {
	$result = mysql_query($query);
	$field = mysql_num_fields( $result );
	for ( $i = 0; $i < $field; $i++ ) {
		$names[] = mysql_field_name( $result, $i );
	}
	return $names;
}
/*
function check_column($id){
	global $prefix;
	$q = new query();
	$cid = get_param('cid');
	$param = $q->select1("select * from ".$prefix."adv_params where id=".to_sql($id));
	switch($param['types']){
		case 'c_int': $field = 'int'; break;
		case 'c_char': $field = 'char'; break;
		case 'c_text': $field = 'text'; break;
		case 'c_select': $field = 'int'; break;
		case 'c_table': $field = 'int'; 
			if(!empty($param['select_table'])){//������� �������
				$sql = "CREATE TABLE IF NOT EXISTS `".$prefix.$param['select_table']."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(255) NOT NULL,
				  `rank` int(11) NOT NULL,
				  `status` tinyint(4) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;";
				$q->exec($sql);
			}	
			break;
		case 'c_multiselect': $field = 'char'; break;//�������� ����� ������� ����� ��� ��� ���� ����� ������� ��� ����� ��������� ������� ������� (��� ������� �� ������ ������� ������)
	}
	for($i = 1;$i<=100;$i++){
		$cur_field = $field.$i;
		$check = $q->select1("select id from ".$prefix."adv_params where column_name=".to_sql($cur_field)." and  cid=".to_sql($cid));
		if($check == 0){
			break;
		}		
	}
	$q->exec("update ".$prefix."adv_params set column_name=".to_sql($cur_field)." where id=".to_sql($id));
	$cat = $q->select1("select * from ".$prefix."adv_catalog where id=".to_sql($cid));
	
    $fields = mysql_field_array("select * from ".$prefix.$cat['table_name']); //������ ����� ���� ���� � ������� $cat['table_name']

	if(in_array($cur_field,$fields)){
		echo '���� ����';
	}else{
		echo '���� ���';
		switch($field){
			case 'int': $q->exec("ALTER TABLE  `".$prefix.$cat['table_name']."` ADD  `".$cur_field."` INT NOT NULL"); break;
			case 'char': $q->exec("ALTER TABLE  `".$prefix.$cat['table_name']."` ADD  `".$cur_field."` VARCHAR( 255 ) NOT NULL"); break;
			case 'text': $q->exec("ALTER TABLE  `".$prefix.$cat['table_name']."` ADD  `".$cur_field."` TEXT NOT NULL"); break;
		}
	}

}

*/




$table = new cTable('���������',$prefix.'adv_params','id',$is_rank=1, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(30);

$table->insertcol(new c_text('���������','name',1,1,$size='100',$max=''));
$table->order(' name ');
$select = Array();
$select['c_int']='�����';
$select['c_char']='������';
/*$select['c_text']='c_text';
$select['c_select']='c_select';
$select['c_table']='c_table';
$select['c_multiselect']='c_multiselect';
*/
$table->insertcol(new c_select('���','types',1,1,$select));
$table->insertcol(new c_text('����','column_name',1,0,$size='100',$max=''));
$table->insertcol(new c_checkbox('������������ ����','is_required',1,1,1));
$table->insertcol(new c_checkbox('��� ������','for_search',0,1,1));
//$table->insert_action('��������', 'value', 'pid');

//$table->insert_after_add('check_column({id});');

$table->draw();

echo '<a href="./">�����</a>';

/*
�������� ���� ����������� ��� ����������
��� ������
��� ����������� ������


$select['c_int']='c_int';
$select['c_char']='c_char';
$select['c_text']='c_text';
��� ���������

$select['c_select']='c_select';
//������� ����� ������� (id,name,pid)
$select['c_table']='c_table';
// ������� ���� ��� ����� ����������� ��� ������� - � ������� 1 ���� ��� �������������� ����� ����� ������� (id,name)
$select['c_multiselect']='c_multiselect';
// ��� � c_table

��� ������� ��������� ����

*/
?>