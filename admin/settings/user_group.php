<?
$inc_path = "../";
$_menu_type = 'settings';

include($inc_path."class/header_adm.php");
$table = new cTable('Группы',$prefix.'users_group','id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
$table->insert_action('Права', 'user_group_rights', 'gid','c_iframe');
$table->draw();


include($inc_path."class/bottom_adm.php");
?>
