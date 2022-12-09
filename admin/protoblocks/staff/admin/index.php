<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

 echo '<div class="sub_menu"><a href="templates.php">Настройки вывода</a></div>';

function check_title($id,$name){
	global $prefix,$this_block_id;
	$name = trim($name);
	$q = new query();
	$check = $q->select1("select cpu,seo_title from ".$prefix."staff where id=".to_sql($id));
	if(empty($check['seo_title'])){
		$q->exec("update ".$prefix."staff set seo_title=".to_sql($name)." where id=".to_sql($id));			
	}
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($name));
		$check = $q->select1("select id from ".$prefix."staff where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."staff set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."staff set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}

$table = new cTable('Наша команда',$prefix.'staff' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(30);

$table->insertcol(new obj_html('
<div class="nav-tabs-custom"> 
<!-- Tabs within a box -->
<ul class="nav nav-tabs ">
	<li class="active"><a href="#tab_text" data-toggle="tab"><i class="fa fa-file-text-o"></i> Текст</a></li>
	<li><a href="#tab_seo" data-toggle="tab"><i class="fa fa-search"></i> SEO-параметры</a></li>
</ul>
<div class="tab-content no-padding">
<div class="tab-pane active" id="tab_text" style="position: relative; padding:20px; ">'));

	$table->insertcol(new c_text('Имя','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('Должность','dolj',1,1,$size='100',$max=''));
	$table->insertcol(new c_image('Фото','img',1,1,$inc_path.'../files/staff/', '187x284;'));
	/*$table->insertcol(new c_textarea('Анонс','anons',0,1,60,5));
	$table->insertcol(new c_fck('Текст','text',0,1,$height='400'));
*/


$table->insertcol(new obj_html('</div><div class="tab-pane" id="tab_seo" style="position: relative;  padding:20px;">'));
	$table->insertcol(new c_text('Чпу','cpu',0,1,$size='100',$max=''));
	$table->insertcol(new c_text('title','seo_title',1,1,$size='100',$max=''));		
	$table->insertcol(new c_textarea('description','seo_description',0,1,60,5));
	$table->insertcol(new c_textarea('keywords','seo_keywords',0,1,60,5));
$table->insertcol(new obj_html('</div></div></div>'));



$table->insert_after_add('check_title({id},{name});');
$table->insert_after_update('check_title({id},{name});');

$table->draw();


include($inc_path."class/bottom_adm.php");

?>