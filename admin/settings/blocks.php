<?
$inc_path = "../";
$_menu_type = 'admin';
$_menu_active = 'blocks';
include($inc_path."class/header_adm.php");

$q = new query();


$table = new cTable('<i class="fa fa-object-group"></i> Модули</a>',$prefix.'blocks','id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Папка','folder',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Иконка','icon',1,1,$size='100',$max=''));

$table->insert_action('Изменить файлы', 'block_edit', 'tid');

$table->insert_action('Файлы', 'block_files', 'id_block');
$table->draw();

?>

<?
include($inc_path."class/bottom_adm.php");
?>

