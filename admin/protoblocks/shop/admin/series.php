<?
	$inc_path = '../../../';
	include($inc_path."class/header_adm.php");
	
	
	
$word = get_param('word');
		$brand = get_param('brand');
		echo '<form method="post">
		<input type="text" name="word" value="'.$word.'">
		<input type="submit" value="найти">
		</form><br>';
		$where .= ' 1 ';
	
		if(!empty($word)){
			$where .= " and name like ".to_sql('%'.$word.'%')."  ";
			
		}
		
	
	$table = new cTable('Линейки',$prefix.'series' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings($where.' order by img desc, id desc');
	
	$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/series/', '125;500'));
	$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_fck('Полное описание','text',0,1,$height='400'));
	



	$table->draw();
	
?>