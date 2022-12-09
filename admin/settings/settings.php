<?
$inc_path = "../";
$_menu_type = 'settings';
$_menu_active = 'contacts';
include($inc_path."class/header_adm.php");
$q = new query();
if(isset($_POST['email'])){
	$q->exec("update ".$prefix."settings set
	
	phone=".to_sql($_POST['phone'])." ,
	vk=".to_sql($_POST['vk'])." ,
	insta=".to_sql($_POST['insta'])." ,
	fb=".to_sql($_POST['fb'])." ,
	ok=".to_sql($_POST['ok'])." ,
	site_email=".to_sql($_POST['site_email'])." ,
	email_pass=".to_sql($_POST['email_pass'])." ,
 	email=".to_sql($_POST['email'])."	
	where id='phone'");
}
/*if(isset($_POST['icq'])){
	$q->exec("update settings set name=".to_sql($_POST['icq'])." where id='icq'");
}
<form method="post">
ICQ:<textarea name="icq" ><?echo $icq['name'];?></textarea><br>
<input type="submit" value="Задать">
</form>

*/
$phone = $q->select1("select * from ".$prefix."settings where id='phone'");


?>
 <div class="box box-success">
                      <div class="box-header with-border">
                      <h3 class="box-title"><i class="fa fa-envelope"></i> Контакты</a></h3>
                    </div>
                      <div class="box-body">
<form method="post" id="contact_from">

<div class="form-group">
<label for="">Email для уведомлений</label>
<div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
  <input type="text" class="form-control"  name="email" value="<?echo $phone['email'];?>">
</div>
</div>


    <div class="form-group">
        <label for="">Пароль от mail.ru</label>
        <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
            <input type="text" class="form-control"  name="email_pass" value="<?echo $phone['email_pass'];?>">
        </div>
    </div>



<div class="form-group">
        <label for="">Email на сайт</label>
        <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
            <input type="text" class="form-control"  name="site_email" value="<?echo $phone['site_email'];?>">
        </div>
    </div>


<div class="form-group">
<label for="">Телефон</label>

<div class="input-group"> <span class="input-group-addon"><i class="fa fa-phone"></i></span>
  <input type="text" class="form-control" name="phone" value="<?echo $phone['phone'];?>">
</div>
</div>



<div class="form-group">
<label for="">Группа в контакте</label>
<div class="input-group"> <span class="input-group-addon"><i class="fa fa-vk"></i></span>
  <input type="text" class="form-control"  name="vk" value="<?echo $phone['vk'];?>">
</div>
</div>

<div class="form-group">
<label for="">Группа в instagram</label>
<div class="input-group"> <span class="input-group-addon"><i class="fa fa-instagram"></i></span>
  <input type="text" class="form-control"  name="insta" value="<?echo $phone['insta'];?>">
</div>
</div>

<div class="form-group">
<label for="">Группа в facebook</label>
<div class="input-group"> <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
  <input type="text" class="form-control"  name="fb" value="<?echo $phone['fb'];?>">
</div>
</div>

<div class="form-group">
<label for="">Группа в однокласниках</label>
<div class="input-group"> <span class="input-group-addon"><i class="fa fa-odnoklassniki"></i></span>
  <input type="text" class="form-control"  name="ok" value="<?echo $phone['ok'];?>">
</div>
</div>


<input type="submit" value="Задать" class="btn btn-success">
</form>
</div></div>
<?
include($inc_path."class/bottom_adm.php");
?>
