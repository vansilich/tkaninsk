<? //header('Content-Type: text/javascript; charset=Windows-1251');
$inc_path = "../admin/";	$root_path = "../"; 
include($inc_path."class/header.php");
$q = new query();
//$site_pages = new pages($table=$prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');
$name =  strip_tags(get_param('name'));
$phone =  strip_tags(get_param('tel'));
$email =  strip_tags(get_param('email'));
$adres = strip_tags(get_param('adres'));
$comm = strip_tags(get_param('comm'));
$type = strip_tags(get_param('type'));

if(!empty($phone)){

?>
<div>
<div id="text">
<?
$q->exec("insert into ".$prefix."ospec set 
name = ".to_sql($name).", 
phone = ".to_sql($phone).", 
email = ".to_sql($email).",
adres = ".to_sql($adres).",
type = ".to_sql($type).",
ip = ".to_sql($_SERVER['REMOTE_ADDR']).",
date_add = NOW(),
comm = ".to_sql($comm)."");

$msg = '<table>
<tr><td>Имя:</td><td>'.$name.'</td></tr>
<tr><td>Телефон:</td><td>'.$phone.'</td></tr>
<tr><td>Email:</td><td>'.$email.'</td></tr>
<tr><td>Тип сообщения:</td><td>'.$type.'</td></tr>
</table>';

$subject = 'Сообщение с сайта '.$_SERVER['HTTP_HOST'];
$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
$headers = "From: ".$_SERVER['HTTP_HOST']." <site@".$_SERVER['HTTP_HOST'].">\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
mail($_settings['email'], $subject, $msg, $headers);
echo '<div class="name">Ваше сообщение отправлено</div>';
?>
</div>
</div>
<?
}else{
?>
<html>
<head><META http-equiv=Content-Type content="text/html; charset=utf-8"></head>
<body> <div>
<div id="text">
Ошибка</div>
</div>
</body>
</html>

<?
}
?>