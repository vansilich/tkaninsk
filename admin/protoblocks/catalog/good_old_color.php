<?
  $dir_folder = 'files/goods/';
  $row = $this_good;
  $color = get_param('color');
 
 $news_row = file_get_contents($inc_path.'parts/goods_full.php'); 
 if(!empty($color)){
	$this_color = $q->select1("select G.*,C.code,C.name as cname from ".$prefix."goods_price as G
	left join ".$prefix."color as C on C.id = G.color
	where product_id=".to_sql($row['id'])." and G.id=".to_sql($color));
	if(!empty($this_color)){
		$row['name'] = $this_color['name'];
		$row['articul'] = $this_color['articul'];
		$row['nal'] = $this_color['nal'];
		$row['kol'] = $this_color['kol'];
		$row['price'] = $this_color['price'];
		$row['price_opt'] = $this_color['price_opt'];
		for($i=1;$i<=6;$i++) $row['img'.$i] = $this_color['img'.$i];
		$dir_folder = 'files/color/';
		$news_row = str_replace('{TYPE_ID}', $this_color['id'],$news_row);
	}else{
		$news_row = str_replace('{TYPE_ID}', 0,$news_row);	
	}
	
 }
  
  
  //$main_color = $q->select1("select C.code,C.name as cname from ".$prefix."color as C where id=".to_sql($row['color']));
  $main_color = $q->select1("select C.name as cname from ".$prefix."adv_params_value as C where id=".to_sql($row['color']));
  
  
  
  $good_colors = $q->select("select G.*,C.code,C.name as cname from ".$prefix."goods_price as G
	left join ".$prefix."color as C on C.id = G.color
	where product_id=".to_sql($row['id']));
	
	
$good_colors = $q->select("select G.*,C.name as cname from ".$prefix."goods_price as G
	left join ".$prefix."adv_params_value as C on C.id = G.color
	where product_id=".to_sql($row['id']));
	/* селектор цветов*/
  $sel_color  = '';
  if(!empty($good_colors)){
	 $sel_color = '<select class="select js_change_url">';
	 $sel_color .= '<option value="/goods/'.$row['cpu'].'/"';
	 if(empty($color)) $sel_color .= ' selected';
	 $sel_color .= '>'.$main_color['cname'].'</option>';
	 foreach($good_colors as $c){
		$sel_color .= '<option value="/goods/'.$row['cpu'].'/?color='.$c['id'].'"';
		if($color == $c['id']) $sel_color .= ' selected';
		$sel_color .= '>'.$c['cname'].'</option>';	 
	 }
	 $sel_color .= '</select>';
	  
  }
  if(empty($sel_color)){
	   $news_row = str_replace('{select_color}', 'Цвет: '.$main_color['cname'],$news_row);
	 }else{
  $news_row = str_replace('{select_color}', $sel_color,$news_row);
	 }
	/* end селектор цветов*/

  $big_i = '';  
  $small_i = '';
  $flag = 0;
  for($i=1; $i<=6; $i++){
	  if(!empty($this_color)){
		$img = get_image_folder($this_color,$dir_folder.$i.'/','img'.$i,0);
		$img1 = get_image_folder($this_color,$dir_folder.$i.'/','img'.$i,1);
		$img2 = get_image_folder($this_color,$dir_folder.$i.'/','img'.$i,2);
	  }else{
		$img = get_image_cpu($row,$dir_folder.$i.'/','img'.$i,0);
		$img1 = get_image_cpu($row,$dir_folder.$i.'/','img'.$i,1);
		$img2 = get_image_cpu($row,$dir_folder.$i.'/','img'.$i,2);
  
	  }

    if(!empty($img)){
      $alt = $row['alt'.$i];
      if(empty($alt)) $alt=$row['title'];
      
      $big_i .= '<a href="'.$img.'"  id="_img'.$i.'" class="products-group';
	  if($flag > 0) $big_i .= ' none';
	  $big_i .= '"><img src="'.$img2.'" alt="'.htmlspecialchars($alt).'" class="img"/></a>
      ';
      $small_i .= '
      <div class="item">
      <img src="'.$img2.'" data-id="_img'.$i.'" width="97" height="97" class="js-smfoto foto_sm" alt=""/>
      </div>';
	  $flag++;
    }
  }
  
  if(empty($big_i)) $big_i = '<img src="/img/no_photo.jpg" alt="'.htmlspecialchars($alt).'" class="foto"/>';
  
  $filem = 'files/maker/'.$row['mid'].$row['m_img'];
  $m_img = '';
  if(is_file($root_path.$filem)){
    $m_img = '<img src="/'.$filem.'" border="0">';
  }
  $news_row = str_replace('{brend_img}',$m_img,$news_row);
  
////////////////////////////////////////////////  



  $news_row = str_replace('{SMALL_FOTO}',$small_i,$news_row);
  $news_row = str_replace('{BIG_FOTO}',$big_i,$news_row);
  $news_row = str_replace('{PRICE}',$row['price'],$news_row);
  if($row['price_opt'] > 0){
	$news_row = str_replace('{PRICE_OPT}','<span class="price-opt"> '.$row['price_opt'].' р</span> / {edizm} - оптовая цена от 5 метров',$news_row);
  }else{
	$news_row = str_replace('{PRICE_OPT}','',$news_row);
  }
  
  $news_row = str_replace('{edizm}',$this_cat['edizm'],$news_row);
  if($this_cat['edizm'] == 'шт'){
	$news_row = str_replace('{b_type_col}','_cel',$news_row);		  
  }else{
	$news_row = str_replace('{b_type_col}','',$news_row);	  
  }
  
  $news_row = str_replace('{NEWS_MAXKOL}',$row['kol'],$news_row);
  $news_row = str_replace('{NEWS_ID}',$row['id'],$news_row);
  $news_row = str_replace('{ARTICUL}',$row['articul'],$news_row);
  $news_row = str_replace('{NEWS_TITLE}',$row['name'],$news_row);
  $news_row = str_replace('{NEWS_MAKER}',$maker['name'],$news_row);
  $news_row = str_replace('{FULL_TEXT}',$row['text'],$news_row);
  $news_row = str_replace('{TEH}',$row['teh'],$news_row);

  if($row['price_old'] >0){
    $news_row = str_replace('{PRICE_OLD}','<span class="old_price">'.$row['price_old'].' <span class="rub">р</span></span>',$news_row); 
  }else{
    $news_row = str_replace('{PRICE_OLD}','',$news_row); 
  }
  
  if($row['nal'] == 1 && $row['kol'] >= 0.5){
	  $news_row = str_replace('{CLASS_NAL}','none',$news_row); 
	  $news_row = str_replace('{CLASS_BASKET}','',$news_row); 
      $news_row = str_replace('{NAL}','<div class="presence">В наличии</div>',$news_row); 
  }else{
	  $news_row = str_replace('{CLASS_NAL}','',$news_row); 
	  $news_row = str_replace('{CLASS_BASKET}','none',$news_row); 
      $news_row = str_replace('{NAL}','<div class="no_presence">Нет в наличии</div>',$news_row); 
  }

  
$params = '<table class="technik">';
$f = 0;
/*
$settings = $q->select("select C.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."catalog_params as C
join ".$prefix."adv_params as P on P.id = C.param_id
where C.status=1 and C.cat_id=".to_sql($this_cat['id'])." order by C.rank desc");*/
/*
$settings = $q->select("select C.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."catalog_params as C
join ".$prefix."adv_params as P on P.id = C.param_id
where C.status=1 and C.cat_id=".to_sql(1)." order by C.rank desc");



foreach($settings as $v){
  $check = $q->select1("select * from ".$prefix."goods_param where good_id=".to_sql($row['id'])." and param_id=".to_sql($v['pid']));  
  
  if(!empty($check)){
    if($v['types'] == 'c_int'){
      if(!empty($check['ival'])){
      $params .= '<tr class="tr">
      <td class="td">'.$v['name'].'</td>
      <td class="td2">'.$check['ival'].' '.$v['dimension'].'</td>
      </tr>';  
      }
      
    }elseif($v['types'] == 'c_select'){
      if(!empty($check['ival'])){
        $temp = $q->select1("select id,name from ".$prefix."adv_params_value where id=".$check['ival']);
        $params .= '<tr class="tr">
        <td class="td">'.$v['name'].'</td>
        <td class="td2">'.$temp['name'].'</td>
        </tr>';
      }
    }elseif($v['types'] == 'c_list'){
      if(!empty($check['ival'])){
		 $checks = $q->select("select * from ".$prefix."goods_param where good_id=".to_sql($row['id'])." and param_id=".to_sql($v['pid']));  
		 $params .= '<tr class="tr">
			<td class="td">'.$v['name'].'</td>
			<td class="td2">';
		$f = 0;
  		foreach($checks as $check){
			$temp = $q->select1("select id,name from ".$prefix."adv_params_value where id=".$check['ival']);
			if($f>0)$params .= ', ';
			$params .= $temp['name'];
			$f++;
		}
		$params .='</td>
			</tr>';
      }
    }elseif($v['types'] == 'c_bool'){
      if($check['ival'] == 1) {
        $box = 'да';      
        $params .= '<tr class="tr">
        <td class="td">'.$v['name'].'</td>    
        <td class="td2">'.$box.'</td>
        </tr>';      
      }
    }else{
      if(!empty($check['cval'])){
        $params .= '<tr class="tr">
        <td class="td">'.$v['name'].'</td>
        <td class="td2">'.$check['cval'].' '.$v['dimension'].'</td>
        </tr>';
      }
      
    }
    $f++;
  }
}
*/


$settings = $q->select("select G.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."goods_param as G
join ".$prefix."adv_params as P on P.id = G.param_id
where good_id=".to_sql($row['id'])." and P.hideit=0 order by P.name ");

foreach($settings as $v){
  $check = $q->select1("select * from ".$prefix."goods_param where good_id=".to_sql($row['id'])." and param_id=".to_sql($v['pid']));  
  
  if(!empty($check)){
    if($v['types'] == 'c_int'){
      if(!empty($check['ival'])){
      $params .= '<tr class="tr">
      <td class="td">'.$v['name'].'</td>
      <td class="td2">'.$check['cval'].' '.$v['dimension'].'</td>
      </tr>';  
      }
      
    }
    $f++;
  }
}

$params .= '</table>';  
  
$news_row = str_replace('{PARAMS}',$params,$news_row);
$news_row = str_replace('{MAIN_PARAM}','',$news_row);
echo $news_row;

		
				$sql = "select G.*,C.code,C.name as cname from ".$prefix."goods_price as G
				left join ".$prefix."color as C on C.id = G.color
				where kol>=0.5 and product_id=".to_sql($row['id'])." and G.id !=".to_sql($color);
				
				$goods = mysql_query($sql,$db);
				$num_rows = mysql_num_rows($goods);
				if($num_rows > 0) echo '<div class="h3">Другие расцветки</div>';
				include($inc_path.'templates/goods_color.php');

  /*
  
  $similar_exst = '<li class="cognate"><span>Похожие товары</span></li>';
  
  $look_row = '';

  if(!empty($row['look_like'])){
    
    $news_row = str_replace('{LOOK_EXST}',$similar_exst,$news_row);
    
    $look = $q->select("select id,cpu,img,title,price,skidka FROM ".$prefix."goods WHERE id in(".$row['look_like'].") and status=1 order by rank LIMIT 10");

    $look_row .= '<div class="list_carousel">
    
        <div id="similar_products">';

    foreach($look as $l){

      $look_row .= '<div class="similar">';

      $file = 'files/goods/pre1_'.$l['id'].$l['img'];

      $img = '';

      if(is_file($root_path.$file)){

        $img = '<img src="/'.$file.'" border="0">';

      }
      
      $price_similar = 'Цена: '.number_format(round($l['price']), 0, ',', ' ').' руб.';

      $look_row .= '<a href="/goods/'.$l['cpu'].'/">'.$l['title'].'</a><div class="imgWrap">'.$img.'</div><p>'.$price_similar.'</p></div>';

    }

    $look_row .= '</div>
    
    <a id="prev-sim" class="prev" href="#">Назад</a>
    <a id="next-sim" class="next" href="#">Вперед</a>
    <div id="pager-sim" class="pager"></div>
    
    </div>';
    
    $news_row = str_replace('{LOOK_LIKE}',$look_row,$news_row);

  }else{
     $news_row = str_replace('{LOOK_LIKE}','',$news_row);
     $news_row = str_replace('{LOOK_EXST}','',$news_row);
  }

  
  */
  
  

/*
  $cat = $q->select1("select cpu from ".$prefix."catalog where id=".to_sql($row['catalog']));
  
  echo '<a class="more" href="/catalog/'.$cat['cpu'].'/">Все модели</a>';
*/
?>