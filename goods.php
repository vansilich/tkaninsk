<?php
/*header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"filename.xls\"");
header("Cache-Control: max-age=0");*/
$inc_path = "admin/";	
$root_path = ""; 
include($inc_path."class/header.php");
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo  $title;?></title>
<meta name="description" content="<? echo  $descr;?>">
<meta name="Keywords" content="<? echo  $keys;?>">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?=rand(1,9999);?>" />
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<!-- JavaScript includes -->

</head>
<?
$q = new query();
echo '<table border="1">';
echo '<tr><td>id Раздела</td><td>название раздела</td><td>id родителя</td>
<td>id товара</td><td>артикул</t
d><td>название товара</td><td>Кол-во</td>
<td>цвет</td>

<td>цена</td>
<td>Цена старая</td>
<td>Цена опт</td>


<td>Фото</td>
<td>Вес</td>
<td>Характеристики</td>
<td>Описание</td>


</tr>';
function draw_cat($parent){
	global $prefix,$q;
	$cats = $q->select("select * from ".$prefix."catalog where parent=".to_sql($parent)." order by rank desc");
	foreach($cats as $c){
		echo '<tr><td>'.$c['id'].'</td><td>'.$c['name'].'</td><td>'.$c['parent'].'</td></tr>';
		draw_cat($c['id']);
		draw_goods($c['id']);
	}
	
	
}
draw_cat(0);
echo '</table>';
function draw_goods($cat){
	global $prefix,$q;
	$goods = $q->select("select G.*, C.name as cname from ".$prefix."goods as G
	left join ".$prefix."color as C on C.id=G.color
	 where catalog=".to_sql($cat)." order by rank desc");
	foreach($goods as $c){
		$row = $c;
		echo '<tr><td></td><td></td><td></td><td>'.$c['id'].'</td><td>'.$c['articul'].'</td><td>'.$c['name'].'</td><td>'.$c['kol'].'</td><td>'.$c['cname'].'</td>
		<td>'.$c['price'].'</td>
		<td>'.$c['price_old'].'</td>
		<td>'.$c['price_opt'].'</td>
		
		';
		$dir_folder = 'files/goods/';
		$images = '';
		for($i=1; $i<=6; $i++){
			$img = get_image_cpu($c,$dir_folder.$i.'/','img'.$i,0);
			if(!empty($img)){$images .= 'http://tkani.vakas2.ru'.$img.';';}
		}
		echo '<td>'.$images.'</td>';
		
		
$params = '';		
$settings = $q->select("select C.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."catalog_params as C
join ".$prefix."adv_params as P on P.id = C.param_id
where C.status=1 and C.cat_id=".to_sql(1)." order by C.rank desc");
foreach($settings as $v){
  $check = $q->select1("select * from ".$prefix."goods_param where good_id=".to_sql($row['id'])." and param_id=".to_sql($v['pid']));  
  
  if(!empty($check)){
    if($v['types'] == 'c_int'){
      if(!empty($check['ival'])){
      $params .= $v['name'].':'.$check['ival'].' '.$v['dimension'].';'."\n"; 
      }
      
    }elseif($v['types'] == 'c_select'){
      if(!empty($check['ival'])){
        $temp = $q->select1("select id,name from ".$prefix."adv_params_value where id=".$check['ival']);
        $params .= $v['name'].':'.$temp['name'].';'."\n"; 
      }
    }elseif($v['types'] == 'c_list'){
      if(!empty($check['ival'])){
		 $checks = $q->select("select * from ".$prefix."goods_param where good_id=".to_sql($row['id'])." and param_id=".to_sql($v['pid']));  
		 $params .= $v['name'].':';
		$f = 0;
  		foreach($checks as $check){
			$temp = $q->select1("select id,name from ".$prefix."adv_params_value where id=".$check['ival']);
			if($f>0)$params .= ', ';
			$params .= $temp['name'];
			$f++;
		}
		$params .=';'."\n"; 
      }
    }elseif($v['types'] == 'c_bool'){
      if($check['ival'] == 1) {
        $box = 'да';      
        $params .= $v['name'].':'.$box.';'."\n";      
      }
    }else{
      if(!empty($check['cval'])){
        $params .= $v['name'].':'.$check['cval'].' '.$v['dimension'].";\n"; 
      }
      
    }
    $f++;
  }
}
$params .= '';  		
		
		
		echo '<td>'.$row['ves'].'</td>';
		echo '<td>'.$params.'</td>';
		echo '<td>'.$row['text'].'</td>';
		
		
		
		
		
		
		
		
		
		
		echo '</tr>';
		
		
		
		$goods2 = $q->select("select G.*, C.name as cname from ".$prefix."goods_price as G
	left join ".$prefix."color as C on C.id=G.color
	 where product_id=".to_sql($c['id'])." order by rank desc");
		foreach($goods2 as $c2){
			echo '<tr><td></td><td></td><td>другой цвет</td><td>'.$c2['id'].'</td><td>'.$c2['articul'].'</td><td>'.$c2['name'].'</td><td>'.$c2['kol'].'</td><td>'.$c2['cname'].'</td><td>'.$c2['price'].'</td>
			
			<td>'.$c2['price_old'].'</td>
		<td>'.$c2['price_opt'].'</td>
			';
			
			$dir_folder = 'files/color/';
			$images = '';
			for($i=1; $i<=6; $i++){
				$img = get_image_folder($c2,$dir_folder.$i.'/','img'.$i,0);
				if(!empty($img)){$images .= 'http://tkani.vakas2.ru'.$img.';';}
			}
			echo '<td>'.$images.'</td>';
			echo '</tr>';
			
		}
		
		
	}
	
}
?>
