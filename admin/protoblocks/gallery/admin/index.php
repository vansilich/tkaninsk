<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

function check_title($id,$title){
	global $prefix;
	$q = new query();
	$title = trim($title);
	$check = $q->select1("select cpu from ".$prefix."gallery where id=".to_sql($id));
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($title));
		$check = $q->select1("select id from ".$prefix."gallery where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."gallery set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."gallery set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}

	$table = new cTable('Галерея',$prefix.'gallery','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(10);
//	$table->insertcol(new c_data('Дата','created',1,1));
	$table->insertcol(new c_text('Заголовок','title',1,1,$size='100',$max=''));
	$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/gallery/', '120;500'));
	$table->insertcol(new c_text('ЧПУ','cpu',1,1,$size='100',$max=''));
	$table->insertcol(new c_textarea('Анонс','anons',1,1,60,5));
	$table->insertcol(new c_fck('Текст','text',0,1,$height='400'));
	$table->insertcol(new c_text('title','seo_title',0,1,$size='100',$max=''));	
	$table->insertcol(new c_textarea('keywords','seo_keywords',0,1,60,5));
	$table->insertcol(new c_textarea('description','seo_description',0,1,60,5));
	$table->insert_after_add('check_title({id},{title});');
	$table->insert_after_update('check_title({id},{title});');

	$table->insert_action('фото', 'photo', 'gid','c_iframe');

	$table->draw();

include($inc_path."class/bottom_adm.php");
?>