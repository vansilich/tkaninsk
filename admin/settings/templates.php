<?
$inc_path = "../";
$_menu_type = 'admin';
$_menu_active = 'templates';

include($inc_path."class/header_adm.php");

//создание папки protoblocks если ее не существует
//if(!is_dir('templates')) mkdir('templates');

$table = new cTable('<i class="fa fa-object-ungroup"></i> Шаблоны</a>',$prefix.'templates','id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
$table->insertcol(new c_fileid('Шаблон','file_ext',1,1,$inc_path.'templates/'));
$table->insert_action('Изменить', 'template_edit', 'tid');
$table->draw();


include($inc_path."class/bottom_adm.php");
?>
