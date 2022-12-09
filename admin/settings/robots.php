<?
$inc_path = "../";
$_menu_type = 'admin';
$_menu_active = 'robots';

include($inc_path."class/header_adm.php");

if(isset($_POST['block'])){
	$q->exec("update ".$prefix."settings set
	block=".to_sql($_POST['block'])." 	
	where id='robots'");
}
/*if(isset($_POST['icq'])){
	$q->exec("update settings set name=".to_sql($_POST['icq'])." where id='icq'");
}
<form method="post">
ICQ:<textarea name="icq" ><?echo $icq['name'];?></textarea><br>
<input type="submit" value="Задать">
</form>

*/
$phone = $q->select1("select * from ".$prefix."settings where id='robots'");


?>
 <div class="box box-success">
  <div class="box-header with-border">
     <h3 class="box-title"><i class="fa fa-file"></i> Robots.txt</a></h3>
   </div>
  <div class="box-body">
     <form method="post" id="contact_from">
      <div class="form-group">
      <div class="input-group">
      <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
      <textarea class="form-control" name="block" rows="10"><?echo $phone['block'];?></textarea>
      </div>
      </div>
      <input type="submit" value="Задать" class="btn btn-success">
    </form>
   </div>
</div>
<?



include($inc_path."class/bottom_adm.php");
?>
