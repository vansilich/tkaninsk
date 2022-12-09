<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

echo '<a href="makeprice.php">Обновить цены</a><br>';

function check_title_cat($id,$title){
	global $prefix;
	$q = new query();
	$check = $q->select1("select cpu from ".$prefix."catalog where id=".to_sql($id));
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($title));
		$check = $q->select1("select id from ".$prefix."catalog where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."catalog set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."catalog set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}


function make_date($id){
	global $prefix;
	$q = new query();
	$q->exec("update ".$prefix."goods set added=NOW() where id = ".to_sql($id));
}	


function make_anons($id ,$cat){
	global $prefix;
	$q = new query();
	$settings = $q->select("select C.*,P.name,P.types, P.id as pid,P.dimension from ".$prefix."catalog_params as C
	join ".$prefix."adv_params as P on P.id = C.param_id
	where C.status=1 and C.cat_id=".to_sql($cat)." order by C.rank desc");
	$f = 0;

	foreach($settings as $v){
		echo 'x';
		$check = $q->select1("select * from ".$prefix."goods_param where good_id=".to_sql($id)." and param_id=".to_sql($v['pid']));
		if(!empty($check)){
			if($v['types'] == 'c_int'){
				$params .= '<div>'.$v['name'].': '.$check['ival'].' '.$v['dimension'].'</div>';	
			}else{
				$params .= '<div>'.$v['name'].': '.$check['cval'].' '.$v['dimension'].'</div>';	
			}
			$f++;
		}
		
		if($f == 5) break;
	}
	$q->exec("update ".$prefix."goods set anons=".to_sql($params)." where id = ".to_sql($id));
	
	
	
	$check = $q->select1("select cpu,name from ".$prefix."goods where id=".to_sql($id));
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($check['name']));
		$check = $q->select1("select id from ".$prefix."goods where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."goods set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."goods set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
	
}


$catalog = new c_catalog('Каталог',$prefix.'catalog' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);

$catalog->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/catimg/', '200'));
$catalog->insertcol(new c_textarea('Анонс','anons',0,1,60,5));
$catalog->insertcol(new c_fck('описание','text',0,1,$height='400'));


$catalog->insertcol(new c_text('ЧПУ','cpu',0,1,$size='100',$max=''));
$catalog->insertcol(new c_text('title','title',0,1,$size='100',$max=''));
$catalog->insertcol(new c_textarea('description','description',0,1,60,5));
$catalog->insertcol(new c_textarea('keywords','keywords',0,1,60,5));

$catalog->insert_action('Параметры', 'settings', 'cur_ctgr');
$catalog->insert_after_add('check_title_cat({id},{name});');
$catalog->insert_after_update('check_title_cat({id},{name});');

$catalog->draw();



$cur_cat = get_param('cur_cat');
$q = new query();
if(!empty($cur_cat)){
		$word = get_param('word');
		echo '<form method="post">
		<input type="text" name="word" value="'.$word.'"><input type="submit" value="найти">
		</form><br>';
		if(!empty($word)){
			$table = new cTable('Товары ',$prefix.'goods' ,'id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
			$table->set_page_size(20);
			$where = " and (name like ".to_sql('%'.$word.'%')." or articul like ".to_sql('%'.$word.'%')." ) ";
			$table->settings(' catalog ='.$cur_cat.$where);
			$table->draw_params('cur_cat='.$cur_cat.'&word='.$word);
			$table->insertparam('catalog', $cur_cat);
		
		}else{
		
			$table = new cTable('Товары ',$prefix.'goods' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
			$table->set_page_size(20);
			$table->settings(' catalog ='.$cur_cat);
			$table->draw_params('cur_cat='.$cur_cat);
			$table->insertparam('catalog', $cur_cat);
		
		}


		
		$table->insertcol(new c_text('Артикул','articul',0,1,$size='100',$max=''));
		$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
		$table->insertcol(new c_text('Цена в руб','price',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Цена в евро','price_evro',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Цена в дол','price_dol',1,1,$size='100',$max=''));
		$table->insertcol(new c_image('Картинка','img1',1,1,$inc_path.'../files/catalog/1/', '120;324;700'));		
		$table->insertcol(new c_image('Картинка2','img2',0,1,$inc_path.'../files/catalog/2/', '120;324;700'));		
		$table->insertcol(new c_image('Картинка3','img3',0,1,$inc_path.'../files/catalog/3/', '120;324;700'));		
		$table->insertcol(new c_image('Картинка4','img4',0,1,$inc_path.'../files/catalog/4/', '120;324;700'));		
	
		
		
		$table->insertcol(new c_checkbox('В наличии','nal',0,1,1));
		$table->insertcol(new c_checkbox('ЛИДЕРЫ-ПРОДАЖ','hit',0,1,0));
		$table->insertcol(new c_checkbox('Новинка','isnew',0,1,0));
		$table->insertcol(new c_checkbox('СПЕЦИАЛЬНЫЕ ЦЕНЫ','spec',0,1));


		
		
		
	
		$settings = $q->select("select C.*,P.name,P.types, P.id as pid from ".$prefix."catalog_params as C
		join ".$prefix."adv_params as P on P.id = C.param_id
		where C.status=1 and C.cat_id=".to_sql($cur_cat)." order by C.rank desc");
		foreach($settings as $rows){
			if($rows['types'] == 'c_int'){
					//$table->insertcol(new c_text($rows['title'].'(число)','p'.$rows['p'],0,1,$size='100',$max=''));
$table->insertcol(new c_outfield($rows['name'],'doppg'.$rows['pid'],$prefix.'goods_param','good_id','param_id',$rows['pid'],'ival',''));								
			}else{
					//$table->insertcol(new c_text($rows['title'],'p'.$rows['p'],0,1,$size='100',$max=''));
$table->insertcol(new c_outfield($rows['name'],'doppg'.$rows['pid'],$prefix.'goods_param','good_id','param_id',$rows['pid'],'cval',''));								
			}
		}
		
		
		
		$table->insertcol(new c_text('Хар-ки','anons',1,0,$size='100',$max=''));
		
		$table->insertcol(new c_fck('Полное описание','full',0,1,$height='400'));

		
		$table->insert_action('Перенести', 'move', 'cur_cat='.$cur_cat.'&page='.$page.'&gid');
		$table->insertcol(new c_text('ЧПУ','cpu',0,1,$size='100',$max=''));
		$table->insertcol(new c_text('title','title',0,1,$size='100',$max=''));
		$table->insertcol(new c_textarea('description','description',0,1,60,5));
		$table->insertcol(new c_textarea('keywords','keywords',0,1,60,5));
		
		$table->insert_after_add('make_anons({id}, '.$cur_cat.');');
		$table->insert_after_add('make_date({id});');
		$table->insert_after_update('make_anons({id}, '.$cur_cat.');');
		
		$table->draw();


}


?>