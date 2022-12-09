<?

$xml = simplexml_load_file('temp/offers0_1.xml');


/*********************************************************************************/
/********************************Разделы*************************************************/
/*********************************************************************************/
$p_cnt = count($xml->ПакетПредложений);
//echo 'x='.$p_cnt.'<br>';
//echo '<pre>';
foreach($xml->ПакетПредложений->ТипыЦен->ТипЦены as $g){
	//print_r($g);
}
//echo '</pre>';

/*
[Ид] => de0ac8dd-2016-11e8-9390-f2e13e0fe8d9
    [Наименование] => Для Сайта
    [Валюта] => RUB
	
[Ид] => a8062a63-2ca7-11e8-9393-f2e13e0fe8d9
    [Наименование] => Для Сайта Оптовое	
	
				<ТипЦены>
				<Ид>53b26a25-28cb-11e8-9086-3c95099cd27c</Ид>
				<Наименование>Для Сайта</Наименование>
				<Валюта>RUB</Валюта>
				<Налог>
					<Наименование>НДС</Наименование>
					<УчтеноВСумме>false</УчтеноВСумме>
					<Акциз>false</Акциз>
				</Налог>
			</ТипЦены>
			<ТипЦены>
				<Ид>9403ecc3-437e-11e8-9da5-309c2384e69b</Ид>
				<Наименование>Для Сайта Оптовые</Наименование>
				<Валюта>RUB</Валюта>
				<Налог>
					<Наименование>НДС</Наименование>
					<УчтеноВСумме>false</УчтеноВСумме>
					<Акциз>false</Акциз>
				</Налог>
			</ТипЦены>
			<ТипЦены>
				<Ид>9403ecc4-437e-11e8-9da5-309c2384e69b</Ид>
				<Наименование>Для Заказов с Сайта</Наименование>
				<Валюта>RUB</Валюта>
				<Налог>
					<Наименование>НДС</Наименование>
					<УчтеноВСумме>false</УчтеноВСумме>
					<Акциз>false</Акциз>
				</Налог>
			</ТипЦены>

	
*/

$p_cnt = count($xml->ПакетПредложений);
//echo 'x='.$p_cnt.'<br>';

foreach($xml->ПакетПредложений->Предложения->Предложение as $g){
	$price = 0; 
	$price_opt = 0;
	$col = 0;
	$nal = 0;
	
	$kol = $g->Количество;
	if($kol > 0) $nal =1;
	foreach($g->Цены->Цена as $v){
		if($v->ИдТипаЦены == '53b26a25-28cb-11e8-9086-3c95099cd27c'){
			$price = $v->ЦенаЗаЕдиницу;
		}
		if($v->ИдТипаЦены == '9403ecc3-437e-11e8-9da5-309c2384e69b'){
			$price_opt = $v->ЦенаЗаЕдиницу;
		}
	}
	
	
	$check = $q->select1("select id,name from ".$prefix."goods where 1cid=".to_sql($g->Ид));
	if(!empty($check)){
		//echo 'z<br>';
		$q->exec("update ".$prefix."goods set 
			price=".to_sql($price).",
			price_opt=".to_sql($price_opt).",
			kol=".to_sql($kol).",
			nal=".to_sql($nal)."
			where id=".to_sql($check['id'])."
			
			"
			);
	}else{
		$check = $q->select1("select id,name from ".$prefix."goods_price where 1cid=".to_sql($g->Ид));
		if(!empty($check)){
			$q->exec("update ".$prefix."goods_price set 
				price=".to_sql($price).",
				price_opt=".to_sql($price_opt).",
				kol=".to_sql($kol).",
				nal=".to_sql($nal)."
				where id=".to_sql($check['id'])."
				
				"
				);
		}
		
		
	}
	

}
$q->exec("update ".$prefix."goods set status=0 where price=0");
$q->exec("update ".$prefix."goods set status=0 where kol<0.5");

?>