<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$q = new query();
$cur_ctgr = (int)get_param('cur_ctgr');
echo '<a href="index.php?cur_cat='.$cur_ctgr.'"><b>Назад</b></a><br><br>';
$cat = $q->select1("select name from ".$prefix."catalog where id=".to_sql($cur_ctgr));








$catalog = new c_catalog('Каталог',$prefix.'catalog' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);


	function draw_list2($parent, $lev){
			global $catalog, $prefix, $cur_ctgr;
			$q =  new query();
			$childs = $q->select("select id,name from ".$prefix."catalog where parent =".$parent." order by rank desc");
			foreach($childs as $row){
				if($row['id'] == $cur_ctgr) continue;
				echo '<option value="'.$row['id'].'">';
				for($i=0; $i<=$lev; $i++){
					echo ' &mdash;';
				}
				echo $row['name'];
				if($catalog->has_children($row['id'])){
					draw_list2($row['id'], $lev+1);
				}
			}
	}
	echo '
	<form method="post">
	<select  name="new_cat" id="new_cat">';
	echo '<option style="padding-left: 10px;" value="0">Корень</option>';
	draw_list2(0,0);
	echo '</select><br>';
	echo '<input type="submit" value="Скопировать параметры">';
	echo '</form>';


	if(isset($_POST['new_cat'])){
		$cat = $q->select("select * from ".$prefix."catalog_params where cat_id=".to_sql($_POST['new_cat']));
		foreach($cat as $v){
			$check = $q->select1("select * from ".$prefix."catalog_params where param_id = ".to_sql($v['param_id'])." and cat_id=".to_sql($cur_ctgr));
			if($check == 0){
				$q->insert("insert into ".$prefix."catalog_params set param_id = ".to_sql($v['param_id']).", cat_id=".to_sql($cur_ctgr).", status=1");		
				
			}
			
		}
		
		//header('location: index.php?cur_cat='.$cur_cat.'&page='.$page);	
	}


/*$settings = $q->select1("select count(id) as col from ".$prefix."catalog_set where types=1 and catalog=".to_sql($cur_ctgr));
//вставка 10 доп полей
if($settings['col'] < 10){

	for($i=$settings['col']+1;$i <= 10; $i++){
		$q->insert("insert into ".$prefix."catalog_set set
		parametr =".to_sql('доп.поле '.$i).",
		p =".to_sql($i).",
		catalog =".to_sql($cur_ctgr).",
		types = 1,
		rank = ".to_sql(11-$i)."");
	}

	
}

$settings = $q->select1("select count(id) as col from ".$prefix."catalog_set where types=2 and catalog=".to_sql($cur_ctgr));
//вставка 10 доп полей
if($settings['col'] < 10){
	for($i=$settings['col']+1;$i <= 10; $i++){
		$q->insert("insert into ".$prefix."catalog_set set
		parametr =".to_sql('Число '.$i).",
		p =".to_sql('10'.$i).",
		catalog =".to_sql($cur_ctgr).",
		types = 2,
		rank = ".to_sql(21-$i)."");
	}	
}
*/
/*
echo '<table><tr valign="top"><td>';
	$table = new cTable('Настройки категории "'.$cat['name'].'"',$prefix.'catalog_set','id',$is_rank=1, $is_status=1, $is_add=0,$is_edit=1,$is_del=0);
	$table->set_page_size(30);
	$table->settings(' catalog ='.$cur_ctgr);
	$table->draw_params('cur_ctgr='.$cur_ctgr);
	$table->insertparam('catalog', $cur_ctgr);	
	$table->insertcol(new c_text('поле','parametr',1,0,$size='100',$max=''));
	$table->insertcol(new c_text('название','title',1,1,$size='100',$max=''));
	$table->insertcol(new c_checkbox('для поиска','for_search',1,1,0));
	$table->draw();
	
echo '</td><td>';	*/


$aid = get_param('aid');
if(!empty($aid)){
	$check = $q->select1("select * from ".$prefix."catalog_params where param_id = ".to_sql($aid)." and cat_id=".to_sql($cur_ctgr));
	if($check == 0){
		$q->insert("insert into ".$prefix."catalog_params set param_id = ".to_sql($aid).", cat_id=".to_sql($cur_ctgr).", status=1");
		header('location: settings.php?cur_ctgr='.$cur_ctgr.'&reorder=true');
		
	}
}

	$table = new cTable('Настройки категории "'.$cat['name'].'"',$prefix.'catalog_params','id',$is_rank=1, $is_status=1, $is_add=0,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' cat_id ='.$cur_ctgr);
	$table->draw_params('cur_ctgr='.$cur_ctgr);
	$table->insertparam('cat_id', $cur_ctgr);	
	
		$select = Array();
		$select[''] = 'не выбран';
		$maker = $q->select("select * from ".$prefix."adv_params order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('поле','param_id',1,0,$select));	
			
//	$table->insertcol(new c_text('название','title',1,1,$size='100',$max=''));
	$table->insertcol(new c_checkbox('для поиска','for_search',1,1,0));
	$table->draw();	
	
	
	
	
/*	
	
	$select = Array();
	$select['c_int']='Число';
	$select['c_char']='Строка';
	
	$word = get_param('word');
	echo '<br><br><br><form method="post">
	Поиск<input type="text" name="word" value="'.$word.'">
	
	<input type="submit" value="найти">
	</form>';	

$where = ' 1 ';
	if(!empty($word)){
		$where .= " and name like ".to_sql('%'.$word.'%')."  ";
		
	}
	
	$page_size = 30;
	$page_name = 'apage';	
	$data = $q->select("select * from ".$prefix."adv_params where ".$where." order by name desc LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	$numb = $q->select1("select count(id) as number from ".$prefix."adv_params where ".$where." ");
	$total_number = $numb['number'];
	
	echo '<br><br><div align="left" >';
	draw_pages($page_size, $total_number, $page_name, "settings.php" ,'cur_ctgr='.$cur_ctgr);
	echo '</div>';
	
	echo '<table cellpadding="5" border="1">';
	$logic = 0;
	foreach($data as $row){
		echo '<tr><td>'.$row['name'].'</td><td>['.$select[$row['types']].']</td><td><a href="?cur_ctgr='.$cur_ctgr.'&aid='.$row['id'].'">Добавить</a></td></tr>';
		
	}
	
	echo '</table>';	
*/	
	
	
	
$word = get_param('word');
echo '<hr><br><form method="post">
Поиск<input type="text" name="word" value="'.$word.'">

<input type="submit" value="найти">
</form>';	

$where = ' 1 ';
if(!empty($word)){
	$where .= " and name like ".to_sql('%'.$word.'%')."  ";
	
}	
	
$table2 = new cTable('Все Параметры(Чтобы добавить параметр в раздел нажмите ссылку "Добавить к разделу")',$prefix.'adv_params','id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table2->set_page_size(30);
$table2->settings($where);
$table2->draw_params('cur_ctgr='.$cur_ctgr);
$table2->order(' name ');

$table2->insertcol(new c_text('Заголовок','name',1,1,$size='100',$max=''));

$select = Array();
$select['c_int']='Число';
$select['c_char']='Строка';

$table2->insertcol(new c_select('Тип','types',1,1,$select));
$table2->insertcol(new c_text('Поле','column_name',1,0,$size='100',$max=''));
$table2->insertcol(new c_text('ед. измерения','dimension',1,1,$size='100',$max=''));

$table2->insertcol(new c_checkbox('Обязательное поле','is_required',1,1,1));
$table2->insertcol(new c_checkbox('Для поиска','for_search',0,1,1));
$table2->insert_action('Добавить к разделу', 'settings', 'cur_ctgr='.$cur_ctgr.'&aid');
$table2->draw();	
	
	
?>
