<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");


$q = new query();
include($inc_path.'../Excel/excel.php');			


if(is_file($_FILES['filo']['tmp_name']) ){
	$exdata = new Spreadsheet_Excel_Reader();
	$exdata->setOutputEncoding('cp1251');
	$exdata->read($_FILES['filo']['tmp_name']);
	$numsheet = 2;
	$numCols = $exdata->sheets[$numsheet]['numCols'];
	$par = 0;
	$cat = 0;


	for ($i=1; $i<=$exdata->sheets[$numsheet]['numRows']; $i++) {
		$cell = $exdata->sheets[$numsheet]['cells'][$i];
		if(empty($cell[7]) || $cell[7]== 'Название') continue;
		echo $cell[7].'<br>';
	/*	$q->exec("insert into ".$prefix."goods set
title = ".to_sql($cell[7]).",
schet = ".to_sql($cell[2]).",
date_post = ".to_sql($cell[4]).",
articul = ".to_sql($cell[6]).",
price_opt = ".to_sql($cell[8]).",
price = ".to_sql($cell[9]).",
sizes = ".to_sql($cell[10]).",
kol = ".to_sql($cell[11]).",
status=1,
catalog=0,
postav = 3

");    */
	}
}
	
?>




<form name="" ENCTYPE="multipart/form-data" action="" method="POST" target="_self">
файл(xls):<INPUT NAME="filo" TYPE="file"><br>
<input type="submit" value="импорт">
</form>
