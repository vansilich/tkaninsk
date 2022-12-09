<?php

$inc_path = "../admin/"; 
$root_path="../" ; 
include($inc_path."class/header.php");		
$q = new query();

/*
http://v8.1c.ru/edi/edi_stnd/131/
*/
$q->exec("insert into ".$prefix."1c_urs set url=".to_sql($_SERVER['REQUEST_URI']).",fname=".to_sql(get_get('filename')).", datas=NOW()");

// Папка для хранения временных файлов синхронизации
$dir = 'temp/';

// Обновлять все данные при каждой синхронизации
$full_update = true;

function get_get($val){
	return $_GET[$val];	
}
// Название параметра товара, используемого как бренд
$brand_option_name = 'Производитель';

$start_time = microtime(true);
$max_exec_time = min(300, @ini_get("max_execution_time"));
if(empty($max_exec_time))
	$max_exec_time = 300;
//session_start();

	$filename = 'test/import0_1.xml';

	$findme   = 'import';
	$pos = strpos($filename, $findme);
	
	// Заметьте, что используется ===.  Использование == не даст верного 
	// результата, так как 'a' в нулевой позиции.
	if ($pos !== false) {
//	if($filename === 'import0_1.xml')
//	{
echo 'start';
		include('import2.php');
		print "success";
	}




function translit2($text)
{
	$ru = explode('-', "А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я"); 
	$en = explode('-', "A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch---Y-y---E-e-YU-yu-YA-ya");

 	$res = str_replace($ru, $en, $text);
	$res = preg_replace("/[\s]+/ui", '-', $res);
	$res = strtolower(preg_replace("/[^0-9a-zа-я\-]+/ui", '', $res));
 	
    return $res;  
}
	
