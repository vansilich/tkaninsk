<?

$inc_path = '../../../';

include($inc_path."class/header_adm.php");

$table = $prefix."goods ";
$cats = $q->select("select id,name from ".$table." where cpu='' ");
$fcol = 0 ;
foreach($cats as $row){
	//echo translit(trim($row['name'])).'<br>';
	$name=CleanFileName(translit(trim($row['name'])));	
	$check = $q->select1("select id from ".$table." where  cpu=".to_sql($name)."");
	if($check == 0){
		$q->exec("update  ".$table." set cpu=".to_sql($name)." where id=".$row['id']);
	}else{
		$name .= '_'.$row['id'];
		//echo $name.'<br>';
		$check = $q->select1("select id from ".$table." where  cpu=".to_sql($name)."");
		if($check == 0){
			$q->exec("update  ".$table." set cpu=".to_sql($name)." where id=".$row['id']);
		}else{
			$name .= $row['id'];
			echo $name.'<br>';
		}		
	}

}


?>

<div class="sub_menu">
<a href="search.php">Найти</a>
<a href="color.php">Цвета</a>
<!--a href="param_value.php?page_back=&this_block_id=&pid=1">Состав</a>
<a href="param_value.php?page_back=&this_block_id=&pid=2">Назначение</a-->

</div> 
<?


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

  }else{

    $new_cpu = $check['cpu'];

    $check = $q->select1("select id from ".$prefix."catalog where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));

    if($check == 0){

      $q->exec("update ".$prefix."catalog set cpu=".to_sql($new_cpu)." where id=".to_sql($id));

    }else{

      $new_cpu .= $id;

      $q->exec("update ".$prefix."catalog set cpu=".to_sql($new_cpu)." where id=".to_sql($id));    

    }

  }

}



function check_title($id,$title){

  global $prefix;

  $q = new query();

  $title = trim($title);

  $check = $q->select1("select cpu from ".$prefix."goods where id=".to_sql($id));

  if(empty($check['cpu'])){

    $new_cpu = CleanFileName(translit($title));

    $check = $q->select1("select id from ".$prefix."goods where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));

    if($check == 0){

      $q->exec("update ".$prefix."goods set cpu=".to_sql($new_cpu)." where id=".to_sql($id));

    }else{

      $new_cpu .= $id;

      $q->exec("update ".$prefix."goods set cpu=".to_sql($new_cpu)." where id=".to_sql($id));    

    }  

  }else{

    $new_cpu = $check['cpu'];

    $check = $q->select1("select id from ".$prefix."goods where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));

    if($check == 0){

      $q->exec("update ".$prefix."goods set cpu=".to_sql($new_cpu)." where id=".to_sql($id));

    }else{

      $new_cpu .= $id;

      $q->exec("update ".$prefix."goods set cpu=".to_sql($new_cpu)." where id=".to_sql($id));    

    }  

  }
  $q->exec("update ".$prefix."goods set renew=renew+1 where id=".to_sql($id)); 

}

$cur_cat = get_param('cur_cat');
$catalog = new c_catalog('Каталог',$prefix.'catalog' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$catalog->insertcol(new obj_html('
<div class="nav-tabs-custom"> 
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs ">
      <li class="active"><a href="#tab_text" data-toggle="tab"><i class="fa fa-file-text-o"></i> Текст</a></li>
      <li><a href="#tab_seo" data-toggle="tab"><i class="fa fa-search"></i> SEO-параметры</a></li>
    </ul>
    <div class="tab-content no-padding">
      <div class="tab-pane active" id="tab_text" style="position: relative; padding:20px; ">'));
	  
	  
$select = Array();

$select['метр']='метр';
$select['шт']='шт';

	
	$catalog->insertcol(new c_select('Ед.изм.','edizm',1,1,$select));
		  
$catalog->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/razd/', '200x200'));
if(empty($cur_cat))$catalog->insertcol(new c_image('Иконка','ico',1,1,$inc_path.'../files/ico/'));
$catalog->insertcol(new c_fck('Текст','text',0,1,$height='400'));
$catalog->insertcol(new obj_html('</div>
      <div class="tab-pane" id="tab_seo" style="position: relative;  padding:20px;">'));

$catalog->insertcol(new c_text('Чпу','cpu',0,1,$size='100',$max=''));
$catalog->insertcol(new c_text('title','title',0,1,$size='100',$max=''));
$catalog->insertcol(new c_textarea('description','description',0,1,60,5));
$catalog->insertcol(new c_textarea('keywords','keywords',0,1,60,5));
$catalog->insert_action('Доп. поля', 'settings', 'cur_ctgr','c_iframe');
$catalog->insert_after_add('check_title_cat({id},{name});');
$catalog->insert_after_update('check_title_cat({id},{name});');

$catalog->insertcol(new obj_html('</div></div></div>'));


$catalog->draw();


$q = new query();

if(!empty($cur_cat) /*&& $level >= 1*/){

	$word = get_param('word');
	
	echo '<br><form method="post">
	Найти<input type="text" name="word" value="'.$word.'">
	<input type="submit" value="Отсортировать" class="btn_blue">
	</form>';
	
	$sp = $swhere = '';
	if(!empty($word)){
		$swhere .= " (name like ".to_sql('%'.$word.'%')." or articul like ".to_sql('%'.$word.'%').") and ";
		$sp = '&word='.$word;
	}
	
	
	$ps = get_param('ps');
	
	if($ps == 300){
	$sp .= '&ps=300';  
	$page_size = 300;
	echo '<a href="?cur_cat='.$cur_cat.'">Показать по 50 товаров</a><br>';
	
	}else{
	$page_size = 50;
	echo '<a href="?cur_cat='.$cur_cat.'&ps=300">Показать по 300 товаров</a><br>';
	}  
	
	$table = new cTable('Товары',$prefix.'goods','id',$is_rank=0,$is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	
	$table->set_page_size($page_size);
	$table->settings($swhere.' catalog ='.$cur_cat);

	$table->order(" rank2 ");
	//$table->reorder2();
	$table->draw_params('cur_cat='.$cur_cat.$sp);
	$table->insertparam('catalog', $cur_cat);
	
	$table->insertcol(new obj_html('
	<div class="nav-tabs-custom"> 
	<!-- Tabs within a box -->
	<ul class="nav nav-tabs ">
	<li class="active"><a href="#tab_text" data-toggle="tab"><i class="fa fa-file-text-o"></i> Текст</a></li>
	<li><a href="#tab_params" data-toggle="tab"><i class="fa fa-image"></i> Параметры</a></li>
	
	<li><a href="#tab_color" data-toggle="tab"><i class="fa fa-image"></i> Цвета</a></li>
	<li><a href="#tab_seo" data-toggle="tab"><i class="fa fa-search"></i> SEO-параметры</a></li>
	</ul>
	<div class="tab-content no-padding">
	<div class="tab-pane active" id="tab_text" style="position: relative; padding:20px; ">'));
	
	//$table->settings(" ".$swhere." id in(select good_id from ".$prefix."cat_goods where cat_id=".to_sql($cur_cat).")");
	//$table->insertcol(new c_text('id','id',1,1,$size='100',$max=''));    
$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));		
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
$table->insertcol(new c_ajax_text('<b>Порядок:</b>','rank2',1,1,$size='100',$max=''));



$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
$table->insertcol(new c_text('Артикул','articul',1,1,$size='100',$max=''));

$select = Array();

$select['метр']='метр';
$select['шт']='шт';

	
	$table->insertcol(new c_select('Ед.изм.','edizm',1,1,$select));


$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
	$select = Array();$select[0]='---';
	//$temp = $q->select("select * from ".$prefix."color");
	$temp = $q->select("select id,name from ".$prefix."adv_params_value as C where pid=3");
	foreach($temp as $v) $select[$v['id']]=$v['name'];
	$table->insertcol(new c_select('Цвет','color',1,1,$select));




$table->insertcol(new obj_html('</div></div>'));

$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));		
$table->insertcol(new c_ajax_text('<b>Цена:</b>','price',1,1,$size='100',$max=''));
$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
$table->insertcol(new c_text('<font size="-2">Цена старая</font>','price_old',0,1,$size='100',$max=''));
$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
$table->insertcol(new c_text('Цена опт','price_opt',0,1,$size='100',$max=''));

$table->insertcol(new obj_html('</div></div>'));
	

	$table->insertcol(new obj_html('<div class="row"><div class="col-md-3">'));	
	$table->insertcol(new c_ajax_checkbox('В наличии','nal',1,1,1));
	$table->insertcol(new obj_html('</div><div class="col-md-3">'));	
	$table->insertcol(new c_checkbox('хит продаж','lider',0,1));
	$table->insertcol(new obj_html('</div><div class="col-md-3">'));

	$table->insertcol(new c_checkbox('Новинка','new',0,1));
	$table->insertcol(new obj_html('</div><div class="col-md-3">'));
	
	$table->insertcol(new c_checkbox('Скидка','action',0,1));
	$table->insertcol(new obj_html('</div></div>'));	

	//$table->insertcol(new c_checkbox('В наличии','nal',0,1));
	//$table->insertcol(new c_multiselect('Категории','link',$prefix.'catalog','id','name',$prefix.'cat_goods','good_id','cat_id',$cur_cat));
	//  $table->insertcol(new c_text('SEO-заголовок','seoslogan',0,1,$size='100',$max=''));  
	//$table->insertcol(new c_text('Преимущества <sub>через запятую</sub>','advantages',0,1,$size='100',$max=''));
	
$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));			
	$table->insertcol(new c_cpu_image('Картинка 1','img1',1,1,$inc_path.'../files/goods/1/', '265x175;600;1000'));
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));
	$table->insertcol(new c_cpu_image('Картинка #2','img2',0,1,$inc_path.'../files/goods/2/', '265x290;600;1000'));
		$table->insertcol(new obj_html('</div><div class="col-md-4">'));
	$table->insertcol(new c_cpu_image('Картинка #3','img3',0,1,$inc_path.'../files/goods/3/', '265x290;600;1000'));
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));
	$table->insertcol(new c_cpu_image('Картинка #4','img4',0,1,$inc_path.'../files/goods/4/', '265x290;600;1000'));
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));
	$table->insertcol(new c_cpu_image('Картинка #5','img5',0,1,$inc_path.'../files/goods/5/', '265x290;600;1000'));
	
	
$table->insertcol(new obj_html('</div></div>'));	
	

	$table->insertcol(new c_textarea('Видео код с youtube','video_content',0,1,60,5));

	
	$table->insertcol(new c_fck('Описание','text',0,1,$height='400'));
	$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_params" style="position: relative;  padding:20px;">'));
	
	
	$table->insertcol(new c_text('Вес в гр','ves',0,1,$size='100',$max=''));

/*	
	$settings = $q->select("select C.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."catalog_params as C
		join ".$prefix."adv_params as P on P.id = C.param_id
		where C.status=1 and C.cat_id=".to_sql($cur_cat)." order by C.for_search desc, C.rank desc");
	*/	
	$settings = $q->select("select C.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."catalog_params as C
		join ".$prefix."adv_params as P on P.id = C.param_id
		where C.status=1 and C.cat_id=".to_sql(1)." order by C.for_search desc, C.rank desc");	
		
if(!empty($settings)){
	$table->insertcol(new obj_html('<div class="row">'));		
		foreach($settings as $rows){
			$table->insertcol(new obj_html('<div class="col-md-4">'));	
			if($rows['types'] == 'c_int'){
					//$table->insertcol(new c_text($rows['title'].'(число)','p'.$rows['p'],0,1,$size='100',$max=''));
					$table->insertcol(new c_outfield($rows['name'].' '.$rows['dimension'],'doppg'.$rows['pid'],$prefix.'goods_param','good_id','param_id',$rows['pid'],'ival',''));								
			}elseif($rows['types'] == 'c_select'){
				$temp = $q->select("select id,name from ".$prefix."adv_params_value where pid=".$rows['pid']);
				$select = Array();
				$select[0]='---';
				foreach($temp as $v) $select[$v['id']] = $v['name'];
				$table->insertcol(new c_outselect($rows['name'],'doppg'.$rows['pid'], $select,$prefix.'goods_param','good_id','param_id',$rows['pid'],'ival',''));
				
			}elseif($rows['types'] == 'c_list'){
				$temp = $q->select("select id,name from ".$prefix."adv_params_value where pid=".$rows['pid']);
				$select = Array();
				//$select[0]='---';
				foreach($temp as $v) $select[$v['id']] = $v['name'];
				$table->insertcol(new c_outmultiselect($rows['name'],'doppg'.$rows['pid'], $select,$prefix.'goods_param','good_id','param_id',$rows['pid'],'ival',''));
				
			}elseif($rows['types'] == 'c_bool'){
				$table->insertcol(new c_outcheckbox($rows['name'].' '.$rows['dimension'],'doppg'.$rows['pid'],$prefix.'goods_param','good_id','param_id',$rows['pid'],'ival',''));	
				
			}else{
					//$table->insertcol(new c_text($rows['title'],'p'.$rows['p'],0,1,$size='100',$max=''));
					$table->insertcol(new c_outfield($rows['name'].' '.$rows['dimension'],'doppg'.$rows['pid'],$prefix.'goods_param','good_id','param_id',$rows['pid'],'cval',''));								
			}
			$table->insertcol(new obj_html('</div>'));
	}	
	$table->insertcol(new obj_html('</div>'));	
}
	
	/*
	
	$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));	
	$select = Array();
	$temp = $q->select("select * from ".$prefix."goods_sostav");
	foreach($temp as $v) $select[$v['id']]=$v['name'];
	$table->insertcol(new c_multi_checkbox('Состав','sostav',1,1,$select));
	
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));

	$select = Array();
	$temp = $q->select("select * from ".$prefix."goods_naznach");
	foreach($temp as $v) $select[$v['id']]=$v['name'];
	$table->insertcol(new c_multi_checkbox('Назначение','naznach',1,1,$select));
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));

$table->insertcol(new c_text('ширина, см.','shirina',0,1,$size='100',$max=''));
$table->insertcol(new c_text('плотность, г/м2','plotnost',0,1,$size='100',$max=''));


	
	$table->insertcol(new obj_html('</div></div>'));*/
	
	
	
	
	$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_color" style="position: relative;  padding:20px;">'));
	$table->insertcol(new obj_iframe('price.php?cat_id='.$cur_cat.'&zid={id}'));
	$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_seo" style="position: relative;  padding:20px;">'));
	
	$table->insertcol(new c_text('ЧПУ','cpu',0,1,$size='100',$max=''));
	$table->insertcol(new c_text('title','seo_title',0,1,$size='100',$max=''));  
	$table->insertcol(new c_textarea('keywords','seo_keywords',0,1,60,5));
	$table->insertcol(new c_textarea('description','seo_description',0,1,60,5));
	$table->insertcol(new obj_html('</div></div></div>'));
	$table->insert_action('Копия', 'copy', 'cat_id='.$cur_cat.'&zid' ,'copy_a');
	$table->insert_action('Перенести', 'move_good', 'cur_cat='.$cur_cat.'&gid', 'move_a');
	$table->insert_action('Цвета', 'price', 'cat_id='.$cur_cat.'&zid','c_iframe price_a');
	$table->insert_after_add('check_title({id},{name});');
	$table->insert_after_update('check_title({id},{name});');
	$table->draw();


}

include($inc_path."class/bottom_adm.php");


?>