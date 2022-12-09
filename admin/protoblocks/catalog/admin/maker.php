<?
	$inc_path = '../../../';
	include($inc_path."class/header_adm.php");
	$table = new cTable('фирмы производители',$prefix.'maker' ,'id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' 1 order by name');
	$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('Страна','country',1,1,$size='100',$max=''));
	$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/maker/', '100'));
	$table->draw();
	
?>