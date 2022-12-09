<?
$inc_path = "../";
include($inc_path."class/header_adm.php");


$text = get_param('text');
if(!empty($text)){
	$filename = '../css/vakas.css';
	if (!$handle = fopen($filename, 'w+')) {
		echo "Ошибка создания файла (".$filename.")";
		return -1;
	}


	if (fwrite($handle, stripslashes($text)) === FALSE) {
		echo "Cannot write to file (".$filename.")";
		return -2;
	}
	fclose($handle);
}
$tmpl = file_get_contents('../css/vakas.css'); 
?>
<form method="post">
<textarea name="text" style="width:95%;height:500px;"><?echo $tmpl;?></textarea><br>
<input type="submit" value="изменить">
</form>

<?
include($inc_path."class/bottom_adm.php");
?>
