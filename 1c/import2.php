<?

libxml_use_internal_errors(true);
$xml = simplexml_load_file('temp/'.$filename);
if (!$xml){

	echo "failure\n
	Your script is not valid due to the following errors:\n";

	//Process error messages
	foreach(libxml_get_errors() as $error){
	   echo $error;
	}

	//Exit because we can't process a broken file
	exit;
}
libxml_clear_errors();
/*********************************************************************************/
/********************************Разделы*************************************************/
/*********************************************************************************/
$p_cnt = count($xml->Классификатор->Группы->Группа->Группы->Группа);
foreach($xml->Классификатор->Группы->Группа as $g){
	getcatalog($g,0);
}
function getcatalog($g,$parent){
	global $q,$prefix;
	if(!empty($g->Ид)){
		$check = $q->select1("select id,name from ".$prefix."catalog where 1cid=".to_sql($g->Ид));
		if(empty($check)){
			$newp = $q->insert("insert into ".$prefix."catalog set name=".to_sql($g->Наименование).", 1cid=".to_sql($g->Ид).", parent=".to_sql($parent));	
		}else{
			$newp = $check['id'];
			$q->exec("update ".$prefix."catalog set name=".to_sql($g->Наименование).", parent=".to_sql($parent)." where id=".to_sql($newp));	
		}
/*	echo $g->Ид;
	echo $g->Наименование;
	echo '<br>';*/
		if(!empty($g->Группы)){
			foreach($g->Группы->Группа as $g){
				getcatalog($g,$newp);
			}
		}
	}
}

$table = $prefix."catalog ";
$cats = $q->select("select id,name from ".$table." where cpu='' ");
$fcol = 0 ;
foreach($cats as $row){
	//echo translit(trim($row['name'])).'<br>';
	$name=CleanFileName(translit(trim($row['name'])));	
	$check = $q->select1("select id from ".$table." where  cpu=".to_sql($name)."");
	if($check == 0){
		$q->exec("update  ".$table." set cpu=".to_sql($name)." where id=".$row['id']);
	}else{
		$name .= '_'.$row['id'];
		//echo $name.'<br>';
		$check = $q->select1("select id from ".$table." where  cpu=".to_sql($name)."");
		if($check == 0){
			$q->exec("update  ".$table." set cpu=".to_sql($name)." where id=".$row['id']);
		}else{
			$name .= $row['id'];
			//echo $name.'<br>';
		}		
	}

}
/*********************************************************************************/
/********************************end Разделы*************************************************/
/*********************************************************************************/


foreach($xml->Классификатор->Свойства->Свойство as $g){
	
	//Свойство	
		
	$check = $q->select1("select id,name from ".$prefix."adv_params where 1cid=".to_sql($g->Ид));
	if($check == 0){
		$param_id =$q->insert("insert into ".$prefix."adv_params set 
		name=".to_sql($g->Наименование).",
		1cid=".to_sql($g->Ид)
		);
	}else{
		$param_id =$check['id']; 	
	}
		
	// Значения Свойство	
	foreach($g->ВариантыЗначений->Справочник as $v){
		$check = $q->select1("select id,name from ".$prefix."adv_params_value where 1cid=".to_sql($v->ИдЗначения));
		if($check == 0){
			$q->insert("insert into ".$prefix."adv_params_value set 
			name=".to_sql($v->Значение).",
			pid=".to_sql($param_id).",
			1cid=".to_sql($v->ИдЗначения)
			);
		}else{
			$q->exec("update ".$prefix."adv_params_value set 
			name=".to_sql($v->Значение)."
			where id=".to_sql($check['id']));
				
		}
	}
}

/*********************************************************************************/
/******************************** Товары*************************************************/
/*********************************************************************************/
//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
foreach($xml->Каталог->Товары->Товар as $g){
//	 echo '<pre>';print_r($g);echo '</pre>';
	$check = $q->select1("select id,name from ".$prefix."catalog where 1cid=".to_sql($g->Группы->Ид));
	if($check == 0){
		continue;
	}else{
		$cat_id =$check['id']; 	
	}
	$images = '';
	foreach( $g->Картинка as $img){
		$images .= $img.'|';	
	}
	$site_name=0;
	$color = 0;
	$ves = 0;
	$hit =$group=$novinka=$sale=0;
	$price_old=0;	
	foreach($g->ЗначенияСвойств->ЗначенияСвойства as $v){
		$param = $q->select1("select id,name from ".$prefix."adv_params where 1cid=".to_sql($v->Ид));
		$value = $q->select1("select id,name from ".$prefix."adv_params_value where 1cid=".to_sql($v->Значение));
//		echo $param['id'].'=p<br>';
		
		if($param['id'] == 2){
			$site_name=$value['id'];	
			$site_name2=$value['name'];		
		}
		if($param['id'] == 3){
			$color=$value['id'];		
		}
		if($param['id'] == 25){
			$value['name']=str_replace(',','.',$value['name'])*1000;
			$ves=$value['name'];		
		}
		if($param['id'] == 27){
			$group=$value['id'];		
		}
		if($param['id'] == 28){
			$hit=1;		
		}
		if($param['id'] == 29){
			$novinka=1;		
		}
		if($param['id'] == 30){
			$sale=1;		
		}
		
		if($param['id'] == 31){
			$price_old=$value['name'];		
		}
		
		
		
/*			 
27
Группировка цвет

28
Хит продаж

29
Новинка

30
Скидка
31
старая цена
*/		
		
	}
	

	
	
	
	$main_good = '';
	/*
	$check = $q->select1("select id,name from ".$prefix."goods where 1cid=".to_sql($g->Ид));
	if(empty($check) && $site_name > 0){
		$main_good = $q->select1("select * from ".$prefix."goods where site_name=".to_sql($site_name));
	}
	*/
	
	if(empty($main_good)){
		$check = $q->select1("select id,name from ".$prefix."goods where 1cid=".to_sql($g->Ид));
		//echo 'z='.$check['id'].'<br>';
		//echo 'z='.$site_name2.'='.$site_name.'<br>';
		if($check == 0){
			$good_id = $q->insert("insert into ".$prefix."goods set 
			
			status=1,
			lider = ".to_sql($hit).",			
			new = ".to_sql($novinka).",
			action = ".to_sql($sale).",
			groups = ".to_sql($group).",
			
			color=".to_sql($color).",
			price_old=".to_sql($price_old).",
			ves=".to_sql($ves).",
			all_img=".to_sql($images).",
			pict=0,
			name=".to_sql($site_name2).",
			text=".to_sql($g->Описание).",
			articul=".to_sql($g->Артикул).",
			catalog=".to_sql($cat_id).",
			1cid=".to_sql($g->Ид).",
			site_name=".to_sql($site_name)."
			
			"
			);
			//name=".to_sql($g->Наименование).",
			
		}else{
			$ds = '';
			if(!empty($images)){
				$ds = " pict=0, all_img=".to_sql($images).",";
			}
			$good_id = $check['id'];
			$q->exec("update ".$prefix."goods set 
			status=1,
			ves=".to_sql($ves).",
			color=".to_sql($color).",
			lider = ".to_sql($hit).",
			groups = ".to_sql($group).",				
			new = ".to_sql($novinka).",
			action = ".to_sql($sale).",
			".$ds."
			price_old=".to_sql($price_old).",
			name=".to_sql($site_name2).",
			text=".to_sql($g->Описание).",
			articul=".to_sql($g->Артикул).",
			site_name=".to_sql($site_name).",
			catalog=".to_sql($cat_id)."
			where id=".to_sql($check['id'])."
			
			"
			);
			//name=".to_sql($site_name).",
		}
		$q->exec("delete from ".$prefix."goods_param where good_id=".to_sql($good_id)."");
		foreach($g->ЗначенияСвойств->ЗначенияСвойства as $v){
			
			$param = $q->select1("select id,name from ".$prefix."adv_params where 1cid=".to_sql($v->Ид));
			$value = $q->select1("select id,name from ".$prefix."adv_params_value where 1cid=".to_sql($v->Значение));
			
			$q->exec("insert into ".$prefix."goods_param set
			good_id=".to_sql($good_id).",
			param_id=".to_sql($param['id']).",
			cval=".to_sql($value['name'])."		
			");
			
		}
	}else{
		/*************************COLOR****************************************************/
		$check = $q->select1("select id,name from ".$prefix."goods_price where 1cid=".to_sql($g->Ид));
		if($check == 0){
			$good_id = $q->insert("insert into ".$prefix."goods_price set 
			product_id = ".to_sql($main_good['id']).",
			status=1,
			color=".to_sql($color).",
			all_img=".to_sql($images).",
			name=".to_sql($site_name2).",
			articul=".to_sql($g->Артикул).",
			1cid=".to_sql($g->Ид)."			
			"
			);
			//name=".to_sql($g->Наименование).",
			
		}else{
			$good_id = $check['id'];
			$q->exec("update ".$prefix."goods_price set 
			product_id = ".to_sql($main_good['id']).",
			status=1,
			color=".to_sql($color).",
			all_img=".to_sql($images).",
			name=".to_sql($site_name2).",
			articul=".to_sql($g->Артикул).",
			1cid=".to_sql($g->Ид)."		
			where id=".to_sql($check['id'])."
			
			"
			);
		}
		
		
	}//if(empty($main_good)){
	
	

	
//	$g->Ид
  //  $g->Артикул
 // Описание

}



$table = $prefix."goods ";
$cats = $q->select("select id,name from ".$table." where cpu='' ");
$fcol = 0 ;
foreach($cats as $row){
	//echo translit(trim($row['name'])).'<br>';
	$name=CleanFileName(translit(trim($row['name'])));	
	$check = $q->select1("select id from ".$table." where  cpu=".to_sql($name)."");
	if($check == 0){
		$q->exec("update  ".$table." set cpu=".to_sql($name)." where id=".$row['id']);
	}else{
		$name .= '_'.$row['id'];
		//echo $name.'<br>';
		$check = $q->select1("select id from ".$table." where  cpu=".to_sql($name)."");
		if($check == 0){
			$q->exec("update  ".$table." set cpu=".to_sql($name)." where id=".$row['id']);
		}else{
			$name .= $row['id'];
			//echo $name.'<br>';
		}		
	}

}

/*********************************************************************************/
/********************************end Товары*************************************************/
/*********************************************************************************/

?>