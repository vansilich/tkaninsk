<?
	$inc_path = '../../../';
	include($inc_path."class/header_adm.php");
	
function check_title($id,$title){
	global $prefix;
	$q = new query();
	$check = $q->select1("select cpu from ".$prefix."brend where id=".to_sql($id));
	if(empty($check['cpu'])){
		$new_cpu = CleanFileName(translit($title));
		$check = $q->select1("select id from ".$prefix."brend where cpu=".to_sql($new_cpu)." and id<>".to_sql($id));
		if($check == 0){
			$q->exec("update ".$prefix."brend set cpu=".to_sql($new_cpu)." where id=".to_sql($id));
		}else{
			$new_cpu .= $id;
			$q->exec("update ".$prefix."brend set cpu=".to_sql($new_cpu)." where id=".to_sql($id));		
		}	
	}
}
	
	$table = new cTable('Бренд',$prefix.'brend' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' 1 order by name');
	$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('Чпу','cpu',0,1,$size='100',$max=''));
	
	$table->insert_after_add('check_title({id},{name});');
	$table->insert_after_update('check_title({id},{name});');
	$table->draw();
	
?>