<?
$inc_path = "../";
$_menu_type = 'settings';
$_menu_active = 'users';
include($inc_path."class/header_adm.php");
?>
<div class="sub_menu">
<a href="user_group.php">Группы</a>
</div>
<?
$table = new cTable('<i class="fa fa-users"></i> Пароли и доступ',$prefix.'users','id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->settings("type=0");
//$table->insertcol('Имя','name',1,1,'text',$size='',$max='');

$table->insertcol(new c_text('Login','login',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Password','password',1,1,$size='100',$max=''));

$select[1]='администратор';
$group = $q->select("select id, name from ".$prefix."users_group where id<>1");
foreach($group as $g){
	$select[$g['id']]=$g['name'];	
}
$table->insertcol(new c_select('Тип','group_id',1,1,$select));


$table->draw();


include($inc_path."class/bottom_adm.php");
?>
