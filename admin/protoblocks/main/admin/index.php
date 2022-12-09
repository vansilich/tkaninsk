<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<div class="sub_menu">
<a href="./">Назад</a>
</div>
<?

	$table = new cTable('Фото на слайдер',$prefix.'main','id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
	$table->settings(' types=1 ');
	$table->insertparam('types',1);
//	$table->insertcol(new c_data('Дата','created',1,1));
	$table->insertcol(new c_text('Заголовок','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/main/', '1900x447'));
	$table->insertcol(new c_image('Картинка для мобильных','m_img',1,1,$inc_path.'../files/m_main/', '480x480'));
	$table->insertcol(new c_text('Ссылка','url',1,1,$size='100',$max=''));
	$table->draw();


?>
<?
include($inc_path."class/bottom_adm.php");
?>