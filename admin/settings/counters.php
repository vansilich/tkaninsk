<?
$inc_path = "../";
$_menu_type = 'settings';
$_menu_active = 'counter';
include($inc_path."class/header_adm.php");
$q = new query();
if(isset($_POST['block'])){
	$q->exec("update ".$prefix."settings set
	block=".to_sql($_POST['block'])." 
	where id='phone'");
}

$phone = $q->select1("select * from ".$prefix."settings where id='phone'");


?>
 <div class="box box-success">
                      <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-bar-chart"></i> Код счетчиков</a></h3>
                    </div>
                      <div class="box-body">
<form method="post" id="contact_from">

<div class="form-group">
<textarea class="form-control" type="text" name="block" rows="15" cols="90"><?echo $phone['block'];?></textarea> 
</div>

<input type="submit" value="Задать"  class="btn btn-success">
</form>
</div></div>
<?
include($inc_path."class/bottom_adm.php");
?>
