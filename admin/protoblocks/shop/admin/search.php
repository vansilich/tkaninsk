<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");



function check_title_cat($id,$title){
	global $prefix;
	$q = new query();
	$check = $q->select1("select alias from ".$prefix."catalog where id=".to_sql($id));
	if(empty($check['alias'])){
		$new_cpu = CleanFileName(translit($title));
		$check = $q->select1("select id from ".$prefix."catalog where alias=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."catalog set alias=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."catalog set alias=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}


function make_date($id){
	global $prefix;
	$q = new query();
	$q->exec("update ".$prefix."goods set added=NOW() where id = ".to_sql($id));
}	

function make_anons($id){
	global $prefix;
	$q = new query();
	$good = $q->select1("select * from ".$prefix."goods where id = ".to_sql($id));
	$cat = $good['cat_id'];
	$cat_set = $q->select("select * from ".$prefix."catalog_set where catalog = ".to_sql($cat)." and title <> '' and ( types=1
	or (types=2 and title not like '��������%' ))
	  order by rank desc");
	$f = 0;
	foreach($cat_set as $v){
		if(!empty($good['p'.$v['p']])){
			 $params .= '<div>'.$v['title'].': '.$good['p'.$v['p']].'</div>';	
			 $f++;	
		}
		if($f == 5) break;
	}
	$q->exec("update ".$prefix."goods set anons=".to_sql($params)." where id = ".to_sql($id));

}





		$word = get_param('word');
		$brand = get_param('brand');
		$cat_id = get_param('cat_id');
		echo '<form method="get">
		<input type="text" name="word" value="'.$word.'">
		<select name="brand">';
		$maker = $q->select("select id,name from ".$prefix."maker order by name");
		echo '<option value="0">---</option>';
		foreach($maker as $row){
			echo '<option value="'.$row['id'].'"';
			if($brand == $row['id']) echo ' selected';
			echo '>'.$row['name'].'</option>';
		}
		echo '</select>';
		
		$where .= ' 1 ';
		if(!empty($word)){
			$where .= " and  (name like ".to_sql('%'.$word.'%')." or articul like ".to_sql('%'.$word.'%')." ) ";
			
		}
		if(!empty($brand)){
			$where .= " and  maker=".to_sql($brand)." ";
		}
		
		if(!empty($brand)){
			$cats = $q->select("select id,name from ".$prefix."catalog where id in (select cat_id from ".$prefix."goods where ".$where.")");	
			echo '<select name="cat_id">';
			echo '<option value="0">---</option>';
			foreach($cats as $row){
				echo '<option value="'.$row['id'].'"';
				if($cat_id == $row['id']) echo ' selected';
				echo '>'.$row['name'].'</option>';
			}
			echo '</select>';
		}
		echo '<input type="submit" value="�����">
		</form><br>';
		if(!empty($cat_id)){
			$where .= " and  cat_id=".to_sql($cat_id)." ";
		}
		
		
		$table = new cTable('������ ',$prefix.'goods' ,'id',$is_rank=0, $is_status=1, $is_add=0,$is_edit=1,$is_del=1);
		$table->set_page_size(20);
		
		$col = $q->select1("select count(*) as col from ".$prefix."goods where ".$where);
		echo '����������:'.$col['col'].'<br><br>';
		$table->settings(' '.$where);
		$table->draw_params('word='.$word.'&brand='.$brand.'&cat_id='.$cat_id);
$table->order(' series ');
		
	


		$table->insertcol(new c_image('��������','img',1,1,$inc_path.'../files/catalog/', '150;500'));		
		$table->insertcol(new c_text('�������','articul',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('��������','name',1,1,$size='100',$max=''));
		
		
		
		$table->insertcol(new c_checkbox('���� �����������','spec',0,1));
		$table->insertcol(new c_checkbox('� �������','nal',0,1,1));
		$table->insertcol(new c_checkbox('��� ������','hit',0,1,0));
		$table->insertcol(new c_checkbox('�������','isnew',0,1,0));
		
		$select = Array();
		$select[''] = '�� ������';
		$maker = $q->select("select * from ".$prefix."maker order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('�������������','maker',1,1,$select));		
		
		
		$select = Array();
		$select[''] = '�� ������';
		$maker = $q->select("select * from ".$prefix."series order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('�������','series',1,1,$select));	

		$table->insertcol(new c_multiselect('����','link',$prefix.'tags','id','name',$prefix.'tags_goods','good_id','cat_id',$cur_cat));

		$table->insertcol(new c_text('�������� ��� ������','power',1,1,$size='100',$max=''));

		$settings = $q->select("select * from ".$prefix."catalog_set where status=1 and catalog=".to_sql($cur_cat)." order by rank desc");
		foreach($settings as $rows){
			if(!empty($rows['title'])){
				if($rows['types'] == 2){
						$table->insertcol(new c_text($rows['title'].'(�����)','p'.$rows['p'],0,1,$size='100',$max=''));
				}else{
						$table->insertcol(new c_text($rows['title'],'p'.$rows['p'],0,1,$size='100',$max=''));
				}
			}
		}
		
		
		
		$table->insertcol(new c_fck('������ ��������','full',0,1,$height='400'));

		$table->insertcol(new c_text('���� ���','price',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('���� ����','price_evro',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('�������','rating',1,1,$size='100',$max=''));
		$table->insert_action('�����', 'files', 'gid');
		$page = get_param('page');
		$table->insert_action('���������', 'move', 'cur_cat='.$cur_cat.'&page='.$page.'&gid');
		
		$table->insert_after_add('make_anons({id});');
		$table->insert_after_add('make_date({id});');
		$table->insert_after_update('make_anons({id});');
		
		$table->draw();





?>