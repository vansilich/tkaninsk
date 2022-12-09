<?
require_once($root_path.'xls/PHPExcel/PHPExcel.php'); 
require_once($root_path.'xls/PHPExcel/PHPExcel/Writer/Excel2007.php');

function GetNewFile($num,$date,$target,$buyer,$products)
{
	
	global $root_path;
	$fn = $root_path."files/invoices/$num.xlsx";
	copy($root_path."xls/etalon.xlsx",$fn);

	$xls = new PHPExcel();

	$objReader = PHPExcel_IOFactory::createReader('Excel2007');

	$xls = $objReader->load($fn);
	$xls->setActiveSheetIndex(0);
	$sheet = $xls->getActiveSheet();
			
	$sheet->setCellValue("B17", "Счет на оплату № $num от $date г.");
	$sheet->setCellValue("B13", $target);
	$sheet->setCellValue("F21", $buyer);
	
	$cnt = count($products);
	$add = $cnt - 1;
	if ($cnt > 1) $sheet->insertNewRowBefore(26, $add);
	for ($i=26;$i<26+$add;$i++)
	{
		$sheet->mergeCells("B$i:C$i");
		$sheet->mergeCells("D$i:F$i");
		$sheet->mergeCells("G$i:AA$i");
		$sheet->mergeCells("AB$i:AC$i");
		$sheet->mergeCells("AD$i:AF$i");
		$sheet->mergeCells("AG$i:AJ$i");
		$sheet->mergeCells("AK$i:AR$i");
	}
	$summ = 0;
	for ($i=0;$i<count($products);$i++)
	{
		$y = 25 + $i;
		$sheet->setCellValue("B$y",$i+1);
		$sheet->setCellValue("D$y",$products[$i]->id);
		$sheet->setCellValue("D$y",$products[$i]->articul);
		$sheet->setCellValue("G$y",$products[$i]->title);
		$sheet->setCellValue("AB$y",$products[$i]->min_count);
		$sheet->setCellValue("AD$y",$products[$i]->edizm);
		$sheet->setCellValue("AG$y",$products[$i]->price);		
		$sheet->setCellValue("AK$y",$products[$i]->min_count * $products[$i]->price);
		$summ += $products[$i]->min_count * $products[$i]->price;
	}
//$y = count($products) <= 1 ? 29 : $y + 2;
$y =  $y + 2;
	$sheet->setCellValue("AK".($y),$summ);
	$summ_str = number_format($summ, 2, ',', ' ');
	$sheet->setCellValue("B".($y+2),"Всего наименований ".count($products).", на сумму $summ_str RUB");
	$sheet->setCellValue("B".($y+3),num2str($summ));
			
	$objWriter = new PHPExcel_Writer_Excel2007($xls);
	$objWriter->save($fn);
}

function num2str($num) {
	$nul='ноль';
	$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array( // Units
		array('копейка' ,'копейки' ,'копеек',	 1),
		array('рубль'   ,'рубля'   ,'рублей'    ,0),
		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
	);
	//
	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) { 
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1; 
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			$out[] = $hundred[$i1];
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; 
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; 
			if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		}
	}
	else $out[] = $nul;
	$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]);
	$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]);
	return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

function morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}
?>