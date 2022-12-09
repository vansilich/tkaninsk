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

if(get_get('type') == 'sale' && get_get('mode') == 'checkauth')
{
	print "success\n";
	print session_name()."\n";
	print session_id();
}

if(get_get('type') == 'sale' && get_get('mode') == 'init')
{
	$tmp_files = glob($dir.'*.*');
	if(is_array($tmp_files))
	foreach($tmp_files as $v)
	{
    //	unlink($v);
    }
	print "zip=no\n";
	print "file_limit=1000000\n";
}

if(get_get('type') == 'sale' && get_get('mode') == 'file')
{
	$filename = get_get('filename');
	$f = fopen($dir.$filename, 'ab');
	fwrite($f, file_get_contents('php://input'));
	fclose($f);
	print "success";
}

if(get_get('type') == 'sale' && get_get('mode') == 'query')
{
	/*Затем на сайт отправляется запрос вида
http://<сайт>/<путь> /1c_exchange.php?type=sale&mode=query*/
	include('order.php');
}

if(get_get('type') == 'sale' && get_get('mode') == 'success')
{
	/*
	Сайт передает сведения о заказах в формате CommerceML 2. В случае успешного получения и записи заказов "1С:Предприятие" передает на сайт запрос вида 
http://<сайт>/<путь> /1c_exchange.php?type=sale&mode=success
	*/
	$q->exec("update ".$prefix."orders set 1c_export=2,changed=changed where 1c_export=1");
}


if(get_get('type') == 'catalog' && get_get('mode') == 'checkauth')
{
	print "success\n";
	print session_name()."\n";
	print session_id();
}

if(get_get('type') == 'catalog' && get_get('mode') == 'init')
{	


	$tmp_files = glob($dir.'*.*');
	if(is_array($tmp_files))
	foreach($tmp_files as $v)
	{
    	unlink($v);
    }
	deleteDirectory($dir.'import_files');
	mkdir($dir.'import_files');
	chmod($dir.'import_files',0777);
	
	
	
    /*unset($_SESSION['last_1c_imported_variant_num']);
    unset($_SESSION['last_1c_imported_product_num']);
    unset($_SESSION['features_mapping']);
    unset($_SESSION['categories_mapping']);
    unset($_SESSION['brand_id_option']);    
	*/
   	print "zip=no\n";
	print "file_limit=1000000\n";
}

if(get_get('type') == 'catalog' && get_get('mode') == 'file')
{
	$dirs = get_get('filename');
	$filename = basename(get_get('filename'));
	$dirs = str_replace($filename,'',$dirs);
	$mas = explode('/',$dirs);
	
	$file_dir = '';
	foreach($mas as $m){
		if(empty($m)) continue;
		
		$file_dir .= $m;		
		mkdir($dir.$file_dir,0777);
		chmod($dir.$file_dir,0777);
		$file_dir .= '/';
	}
	//$filename = basename(get_get('filename'));
	
	
	
	$f = fopen($dir.$file_dir.$filename, 'ab');
	fwrite($f, file_get_contents('php://input'));
	fclose($f);
	chmod($dir.$file_dir.$filename,0777);
	print "success\n";
} 
 
if(get_get('type') == 'catalog' && get_get('mode') == 'import')
{
	$filename = basename(get_get('filename'));

	$findme   = 'import';
	$pos = strpos($filename, $findme);
	
	// Заметьте, что используется ===.  Использование == не даст верного 
	// результата, так как 'a' в нулевой позиции.
	if ($pos !== false) {
//	if($filename === 'import0_1.xml')
//	{
		include('import.php');
		//include('images.php');
		print "success";
		copy($dir.$filename,$dir.'copy/'.$filename);
		unlink($dir.$filename);
	}
	elseif($filename === 'offers0_1.xml')
	{
		include('offers.php');

		print "success";
		copy($dir.$filename,$dir.'copy/'.$filename);
		unlink($dir.$filename);
		unset($_SESSION['last_1c_imported_variant_num']);				

	}
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
	
