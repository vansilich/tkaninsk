<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?
$this_block_id = get_param('this_block_id');




		$table = new cTable('������ ',$prefix.'goods' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
		$table->set_page_size(10);
		$table->settings(" id not in (select distinct gid from ".$prefix."files ) ");

			$table->insertcol(new c_image('��������','img',1,1,$inc_path.'../files/catalog/', '150;500'));		
		$table->insertcol(new c_text('�������','articul',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('��������','name',1,1,$size='100',$max=''));
		
		$table->insertcol(new c_checkbox('���� �����������','spec',1,1));
		
		$select = Array();
		$select[''] = '�� ������';
		$maker = $q->select("select * from maker order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('�������������','maker',1,1,$select));		
		
		
		$select = Array();
		$select[''] = '�� ������';
		$maker = $q->select("select * from series order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('�������','series',1,1,$select));	

		
		$table->insertcol('������� ��������','anons',1,1,'fck',$height='200');


		
		
		
		$table->insertcol(new c_fck('������ ��������','full',0,1,$height='400'));

		$table->insertcol(new c_text('���� ���','price',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('���� ����','price_evro',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('�������','rating',1,1,$size='100',$max=''));
		
		$table->insert_action('�����', 'files', 'gid');
		$table->draw();
?>