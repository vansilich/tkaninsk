<?
$inc_path = '../../../';
include($inc_path."class/header_adm_pop.php");
?>
<?
$good = get_param('good');

$ord = $q->select1("select * from ".$prefix."orders where id=".to_sql($good));

header('location: /pay/?good='.$ord['id'].'&p='.$ord['phone']);

include($inc_path."class/bottom_adm_pop.php");


?>