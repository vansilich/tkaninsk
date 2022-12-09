<?
$inc_path = '../../../';
include($inc_path."class/header_adm_pop.php");
$cat_id = (int)get_param('cat_id');
$zid = (int)get_param('zid');

$root_path = '../'.$inc_path;

$row = $good = $q->select1("select * from ".$prefix."goods where id=".to_sql($zid));


	$table = new cTable('Цвета на "'.$good['name'].'"',$prefix.'goods_price','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' product_id ='.$zid);
	$table->draw_params('zid='.$zid);
	$table->insertparam('product_id', $zid);	


$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));		
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
$table->insertcol(new c_text('Артикул','articul',1,1,$size='100',$max=''));

$table->insertcol(new obj_html('</div><div class="col-md-4">'));	
	$select = Array();$select[0]='---';
	$temp = $q->select("select * from ".$prefix."color");
	foreach($temp as $v) $select[$v['id']]=$v['name'];
	$table->insertcol(new c_select('Цвет','color',1,1,$select));

$table->insertcol(new obj_html('</div></div>'));


	$table->insertcol(new obj_html('<div class="row"><div class="col-md-3">'));	
	$table->insertcol(new c_ajax_checkbox('В наличии','nal',1,1,1));
	$table->insertcol(new obj_html('</div><div class="col-md-3">'));	
	$table->insertcol(new obj_html('</div><div class="col-md-3">'));
	$table->insertcol(new obj_html('</div><div class="col-md-3">'));
	$table->insertcol(new obj_html('</div></div>'));
	

//	$table->insertcol(new c_text('цена','price',1,1,$size='100',$max=''));
$table->insertcol(new obj_html('<div class="row"><div class="col-md-4">'));
	$table->insertcol(new c_image_folder('Картинка 1','img1',1,1,$inc_path.'../files/color/1/', '265x175;600;1000'));	
$table->insertcol(new obj_html('</div><div class="col-md-4">'));

	$table->insertcol(new c_image_folder('Картинка 2','img2',0,1,$inc_path.'../files/color/2/', '265x290;600;1000'));	
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));
	$table->insertcol(new c_image_folder('Картинка 3','img3',0,1,$inc_path.'../files/color/3/', '265x290;600;1000'));	
	$table->insertcol(new obj_html('</div></div>
	<div class="row"><div class="col-md-4">'));
	$table->insertcol(new c_image_folder('Картинка 4','img4',0,1,$inc_path.'../files/color/4/', '265x290;600;1000'));	
	$table->insertcol(new obj_html('</div><div class="col-md-4">'));
	$table->insertcol(new c_image_folder('Картинка 5','img5',0,1,$inc_path.'../files/color/5/', '265x290;600;1000'));	
	$table->insertcol(new obj_html('</div></div>'));

		

	$table->draw();	
	
	
	


include($inc_path."class/bottom_adm_pop.php");
?>
