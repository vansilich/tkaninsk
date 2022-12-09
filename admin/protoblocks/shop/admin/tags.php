<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<a href="index.php">Назад</a>  <br> 
<?
$catalog = new c_catalog('Теги',$prefix.'tags' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);

$catalog->draw();



?>