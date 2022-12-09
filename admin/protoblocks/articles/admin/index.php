<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

 echo '<div class="sub_menu"><a href="templates.php">Настройки вывода</a></div>';

function check_title($id,$name){
	global $prefix,$this_block_id;
	$name = trim($name);
	$q = new query();
	$check = $q->select1("select cpu,seo_title from ".$prefix."articles where id=".to_sql($id));
	if(empty($check['seo_title'])){
		$q->exec("update ".$prefix."articles set seo_title=".to_sql($name)." where id=".to_sql($id));			
	}
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($name));
		$check = $q->select1("select id from ".$prefix."articles where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."articles set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."articles set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}

$table = new cTable('Статьи',$prefix.'articles' ,'id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(10);
$table->order(" created desc ");
$table->insertcol(new c_data('Дата','created',1,1));
$table->insertcol(new c_text('Заголовок','name',1,1,$size='100',$max=''));


$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/articles/', '145;'));
$table->insertcol(new c_textarea('Анонс','anons',0,1,60,5));
$table->insertcol(new c_fck('Текст','text',0,1,$height='400'));

$table->insertcol(new c_text('Чпу','cpu',0,1,$size='100',$max=''));
$table->insertcol(new c_text('title','seo_title',1,1,$size='100',$max=''));		
$table->insertcol(new c_textarea('description','seo_description',0,1,60,5));
$table->insertcol(new c_textarea('keywords','seo_keywords',0,1,60,5));

$table->insert_after_add('check_title({id},{name});');
$table->insert_after_update('check_title({id},{name});');


$table->draw();


include($inc_path."class/bottom_adm.php");

?>