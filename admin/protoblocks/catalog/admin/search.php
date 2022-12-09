a<?

$inc_path = '../../../';

include($inc_path."class/header_adm.php");

?>

<div class="sub_menu">
<a href="./">Назад</a>

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
	
	$page_size = 50;
	
	$table = new cTable('Товары',$prefix.'goods','id',$is_rank=0,$is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	
	$table->set_page_size($page_size);
	$table->settings($swhere.' 1');
	$table->order(" articul ");
	
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
	
$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));		
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
$table->insertcol(new c_text('Артикул','articul',1,1,$size='100',$max=''));

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
	

	
	
	$table->insertcol(new c_fck('Описание','text',0,1,$height='400'));
	$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_params" style="position: relative;  padding:20px;">'));
	
	
	$table->insertcol(new c_text('Вес в гр','ves',0,1,$size='100',$max=''));


	
	
	
	$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_color" style="position: relative;  padding:20px;">'));
	$table->insertcol(new obj_iframe('price.php?zid={id}'));
	$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_seo" style="position: relative;  padding:20px;">'));
	
	$table->insertcol(new c_text('ЧПУ','cpu',0,1,$size='100',$max=''));
	$table->insertcol(new c_text('title','seo_title',0,1,$size='100',$max=''));  
	$table->insertcol(new c_textarea('keywords','seo_keywords',0,1,60,5));
	$table->insertcol(new c_textarea('description','seo_description',0,1,60,5));
	$table->insertcol(new obj_html('</div></div></div>'));
	//$table->insert_action('Копия', 'copy', '&zid' ,'copy_a');
	//$table->insert_action('Перенести', 'move_good', 'cur_cat='.$cur_cat.'&gid', 'move_a');
	//$table->insert_action('Цвета', 'price', 'cat_id='.$cur_cat.'&zid','c_iframe price_a');
	$table->insert_after_add('check_title({id},{name});');
	$table->insert_after_update('check_title({id},{name});');
	$table->draw();




include($inc_path."class/bottom_adm.php");


?>