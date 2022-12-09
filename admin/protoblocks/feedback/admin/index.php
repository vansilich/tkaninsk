<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

function check_title($id,$title){
	global $prefix,$this_block_id;
	$title = trim($title);
	$q = new query();
	$check = $q->select1("select cpu,gtitle from ".$prefix."feedback where id=".to_sql($id));
	if(empty($check['gtitle'])){
		$q->exec("update ".$prefix."feedback set gtitle=".to_sql($title)." where id=".to_sql($id));			
	}
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($title));
		$check = $q->select1("select id from ".$prefix."feedback where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."feedback set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."feedback set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}

$table = new cTable('Отзывы',$prefix.'feedback' ,'id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(10);
$table->order(" created desc ");
$table->insertcol(new c_data('Дата','created',1,1));
$table->insertcol(new c_text('Имя','name',1,1,$size='100',$max=''));

$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/feedback/', '140;'));
$table->insertcol(new c_fck('Текст','text',0,1,$height='400'));

$table->insertcol(new c_text('Чпу','cpu',0,1,$size='100',$max=''));
$table->insertcol(new c_text('title','gtitle',1,1,$size='100',$max=''));		
$table->insertcol(new c_textarea('description','gdescription',0,1,60,5));
$table->insertcol(new c_textarea('keywords','gkeywords',0,1,60,5));

$table->insert_after_add('check_title({id},{title});');
$table->insert_after_update('check_title({id},{title});');


$table->draw();



include($inc_path."class/bottom_adm.php");
?>