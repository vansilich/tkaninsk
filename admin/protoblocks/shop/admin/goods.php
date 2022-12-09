<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?
$this_block_id = get_param('this_block_id');
$cat_id = get_param('cur_ctgr');
$cur_ctgr = get_param('cur_ctgr');
if(empty($cat_id)) $cat_id = 0;
else{
	$cat = $q->select1("select parent, name from ".$prefix."catalog where id=".to_sql($cat_id));
	echo '<a href="index.php?cat_id='.$cat['parent'].'&this_block_id='.$this_block_id.'">назад</a>';
	echo '<h1>Категория "'.$cat['name'].'"</h1>';
}




		$table = new cTable('Товары ',$prefix.'goods' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
		$table->set_page_size(20);
		$table->settings(' cat_id ='.$cur_ctgr);
		$table->draw_params('cur_ctgr='.$cur_ctgr);
		$table->insertparam('cat_id', $cur_ctgr);

		$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/catalog/', '150;500'));		
		$table->insertcol(new c_text('Артикул','articul',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
		
		$table->insertcol(new c_checkbox('Спец предложение','spec',1,1));
		
		$select = Array();
		$select[''] = 'не выбран';
		$maker = $q->select("select * from ".$prefix."maker order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('производитель','maker',1,1,$select));		
		
		
		$select = Array();
		$select[''] = 'не выбран';
		$maker = $q->select("select * from ".$prefix."series order by name");
		foreach($maker as $row) $select[$row['id']] = $row['name'];
		$table->insertcol(new c_select('Линейка','series',1,1,$select));	

		
		$table->insertcol('Краткое описание','anons',1,1,'fck',$height='200');
		


		$settings = $q->select("select * from ".$prefix."catalog_set where status=1 and catalog=".to_sql($cur_ctgr)." order by rank desc");
		foreach($settings as $rows){
			if(!empty($rows['title'])){
						$table->insertcol(new c_text($rows['title'],'p'.$rows['p'],0,1,$size='100',$max=''));
			}
		}
		
		
		
		$table->insertcol(new c_fck('Полное описание','full',0,1,$height='400'));

		$table->insertcol(new c_text('Цена руб','price',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('Цена евро','price_evro',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('Рейтинг','rating',1,1,$size='100',$max=''));
		$table->insert_action('Файлы', 'files', 'gid');
		$table->draw();
?>