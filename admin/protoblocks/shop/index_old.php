<?
if(empty($cat_id)){
	$catalog = new c_catalog('Каталог',$prefix.'catalog',$id_key='id' ,$is_rank=1);
	$child = $catalog->children(1);
	$f = 0;
	
	$makers = $q->select("select id ,name , img  from ".$prefix."maker");
	
	echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:28px 0px; ">
        <tr>  ';
	$f = 0;
	foreach($makers as $m){
		if($f == 7){
			$f = 0;
			echo ' </tr><tr>
        	<td colspan="7" height="28">&nbsp;<img src="include/img/blank.gif" width="1" height="28"/></td>
        	</tr><tr> ';
		}
		$makerimg = $root_path.'files/cmaker/'.$m['id'].$m['img'];
		if(is_file($makerimg)) $makerimg = '<img src="'.$makerimg.'" height="25" border="0" alt="'.$m['name'].'">';
		else $makerimg = $m['name'];
		if($f == 0)	{
			echo '<td><p align="left"><a href="/brands/'.$m['name'].'/">'.$makerimg.'</a></p></td>';
		}else{
			echo '<td><p align="center"><a href="/brands/'.$m['name'].'/">'.$makerimg.'</a></p></td>';
		}
		$f++;
	}
	echo '</tr></table>';
	
	$f = 0;
	
	echo ' <span class="h2_text">Каталог оборудования</span> <table width="100%">
        <tr><td colspan="3"><img src="/include/img/blank.gif" width="1" height="20" /></td></tr><tr valign="top">';
	foreach($child as $row){
		if($f == 3){			
			echo '</tr> <tr height="50px">&nbsp;<td colspan="3" height><img src="include/img/blank.gif" width="1" height="50" /></td></tr><tr valign="top">';
			$f = 0;
		}
		echo '<td>';
		if($catalog->has_children($row['id'])){
			echo '<span class="h3_text"><b><span class="dot_li"><a href="/catalog/'.$row['id'].'/" class="h4_text">'.$row['name'].'</a></span></b></span><br />';
			echo '<div class="paddingleft">                
                    <span class="h4_text">';
			$child2 = $catalog->children($row['id']);
			foreach($child2 as $row2){
				echo '-<a href="/catalog/'.$row2['id'].'/" class="h4_text">'.$row2['name'].'</a><br>';
			}
			echo '</span>
                </div>';
		}else{
			echo '<span class="h3_text"><b><span class="dot_li"><a href="/catalog/'.$row['id'].'/" class="h3_text">'.$row['name'].'</a></span></b></span><br />';
		}
		
		$f++;
		
		echo '</td>';
	
	}
	echo '</tr></table>';
	
}else{//if(empty($cat_id)){ // т.е. выбрана категория
	$catalog = new c_catalog('Каталог',$prefix.'catalog',$id_key='id' ,$is_rank=1);
	$cat_parent = $catalog->parent($cat_id);
	$good = $q->select1("select * from ".$prefix."goods where cat_id = ".to_sql($cat_id));

	
	if($cat_parent == 1 && $good == 0){ // выводим бренды а в них категории

		$this_cat = $q->select1("select * from ".$prefix."catalog where id = ".to_sql($cat_id)." and status=1");
		
		echo '<p class="br18"></p>
		<span class="h2_text">'.$this_cat['h1'].'</span>
		<p class="br9"></p>
		<p class="br3"></p>
		<p class="br3"></p>
		<p class="h4_text" id="span_cont_1">'.$this_cat['text'].'</p>        
		<p class="br18"></p>
		<p class="br3"></p>';
		

		
		$makers = $q->select("select distinct maker from ".$prefix."goods where status=1 and (cat_id = ".to_sql($cat_id)." or cat_id in (select id from ".$prefix."catalog where parent=".to_sql($cat_id)."))");
		foreach($makers as $row){
			$maker = $q->select1("select * from ".$prefix."maker where id=".to_sql($row['maker']));
			$makerimg = $root_path.'files/maker/'.$maker['id'].$maker['img'];
			if(is_file($makerimg)) $makerimg =  '<img src="'.$makerimg.'" border="0" alt="'.$maker['name'].'">';
			else $makerimg =  $maker['name'];
			
			echo '  <table width="100%" border="0">
			<col width="150"><col width="170"><col ><col width="35">
			<tr>
			<td><img src="/include/img/blank.gif" width="70" height="1"  alt=""/></td>
			<td><a class="h2_lin">'.$maker['name'].'</a></td>
			<td align="right">'.$makerimg.'</td>
			<td><img src="/include/img/blank.gif" width="35" height="1"  alt=""/></td>
			</tr>
			</table>
			
			<img src="/include/img/blank.gif" width="1" height="10"  alt=""/>
			
			<table border="0">
			<tr>
			<td rowspan="3" valign="top" width="150px">';
			
			$good = $q->select1("select id,img from ".$prefix."goods 
			where img <>'' and status=1 and maker = ".to_sql($maker['id'])."
			and (cat_id = ".to_sql($cat_id)." or cat_id in (select id from ".$prefix."catalog where parent=".to_sql($cat_id)."))");
			
			$goodimg = $root_path.'files/catalog/pre1_'.$good['id'].$good['img'];
			if(is_file($goodimg)) $goodimg = '<img src="'.$goodimg.'" border="0">';
			else $goodimg = ''; 
			echo $goodimg;
			
			echo '</td>
			<td valign="top"><p class="h4_text">
			'.$maker['text'].'</p>
			</td>
			<td><img src="/include/img/blank.gif" width="25" height="1"  alt=""/></td>
			</tr>
			<tr>
			<td><div id="span_cont_2">';
			
			$child2 = $q->select("select id,name from ".$prefix."catalog where parent = ".to_sql($cat_id)." and status=1 and id in 
			(select distinct cat_id from ".$prefix."goods where status=1 and maker=".to_sql($maker['id'])." and
			cat_id in (select id from ".$prefix."catalog where parent=".to_sql($cat_id).")
			) 
			order by rank desc");
			foreach($child2 as $row2){
				echo '<a href="/catalog/'.$row2['id'].'/?maker_id='.$maker['id'].'" class="h4_lin">'.$row2['name'].'</a><br />';
			}
			
			echo '</div>
			</td>
			<td></td>
			</tr>
			<tr>
			<td colspan="2"><div id="href_line"></div></td>
			</tr>
			</table>
			<p class="br18"></p>
			<p class="br3"></p>';

		}//foreach($makers as $row){
		
		
		
	}else{//if($cat_parent == 1 && $good == 0){ 
	
		$maker_id = get_param('maker_id');
		$cat_info = $q->select1("select * from ".$prefix."catalog where id = ".to_sql($cat_id)." and status=1");
		/*echo '<h1 style="margin:26px 0 0 8px;">'.$cat_info['name'].'</h1><div style="margin:0 0 0 8px;width:751px;">'.$cat_info['text'].'</div>';
		*/
		$maker = $q->select("SELECT * FROM ".$prefix."maker WHERE id IN (SELECT DISTINCT maker FROM ".$prefix."goods where cat_id = ".to_sql($cat_id).")");
		echo '<div style="margin:45px 0 0 8px;" id="menu_tovar"><b>Все производители: </b>';
		foreach($maker as $row){
		
			echo '<a href="?cat_id='.$cat_id.'&maker_id='.$row['id'].'"';
			if($maker_id == $row['id']) echo ' class="maker_ac"';
			echo '>'.$row['name'].'</a> ';		
		}
		echo '</div>';
/*      <div style="margin:45px 0 0 56px;" id="menu_tovar"><b>Все производители:</b> <a href="">Electrolux</a> <a href="">Kospel</a> <a href="">Stiebel</a> <a href="">Eltron</a> <a href="" class="a_ac">Unitherm</a> <a href="">Vaillant</a> <a href="">Эван</a> </div>
	*/
		if(!empty($maker_id)){
			$group_by = 'series';
			$where = " and maker=".to_sql($maker_id);
			
			$maker = $q->select1("select * from ".$prefix."maker where id=".to_sql($maker_id));
					
			//$makerimg = $root_path.'files/maker/pre1_'.$maker['id'].$maker['img'];
			$makerimg = $root_path.'files/maker/'.$maker['id'].$maker['img'];
			if(is_file($makerimg)) $makerimg = '<img src="'.$makerimg.'" border="0">';
			else $makerimg = '';  
			
			echo '<table width="100%" border="0">
				<tr>
					<td colspan="2"><img src="/include/img/blank.gif" width="1" height="25" alt="" /></td>
				</tr>
				
				<tr>
					<td></td>
					<td>
						<p class="h2_text_555">'.$cat_info['name'].'</p>
						<p class="br18"></p>
					
					</td>
				</tr>
				<tr>
					<td width="316px" align="center">'.$makerimg.'</td>
					<td>
						<div class="h4_text_lh14_727272">
						   '.$cat_info['text'].'
						</div>
					</td>
				</tr>     
				</table>';
			
			$main_makerimg = $makerimg;
			
			/*********goods maker**********/
			$goods = $q->select("select * from ".$prefix."goods where cat_id = ".to_sql($cat_id).$where." and status=1 order by ".$group_by.",rank desc");
		
			$cat_set = $q->select("select * from ".$prefix."catalog_set where catalog = ".to_sql($cat_id)." and title <> '' LIMIT 20");
			
			$f = 0;
			$m = '';
			$cat_set2 = Array();
			$tmpl = file_get_contents($inc_path.'parts/goods.php'); 
			preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
			preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
			preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
			preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
			
		
			foreach($goods as $row){
				if($m != $row[$group_by]){
					if(!empty($m)) echo $tmp_end[1];
					$m = $row[$group_by];
					$maker = $q->select1("select * from ".$prefix.$group_by." where id=".to_sql($row[$group_by]));
					if($group_by == 'maker'){
						$makerimg = $root_path.'files/'.$group_by.'/'.$maker['id'].$maker['img'];
					}else{
						$makerimg = $root_path.'files/'.$group_by.'/pre1_'.$maker['id'].$maker['img'];
					}
					if(is_file($makerimg)) $makerimg = '<img src="'.$makerimg.'" border="0">';
					else $makerimg = '';  				
					
					$goods_begin = $tmp_begin[1];
					$goods_begin = str_replace('{MAKER_MAIN_IMG}',$main_makerimg,$goods_begin );
					$goods_begin = str_replace('{MAKER_IMG}',$makerimg,$goods_begin );
					$goods_begin = str_replace('{MAKER_NAME}',$maker['name'],$goods_begin );
					$goods_begin = str_replace('{MAKER_TEXT}',$maker['text'],$goods_begin );
					$goods_begin = str_replace('{MAKER_ID}',$maker['id'],$goods_begin );
					$params = '';
	
					unset($cat_set2);			
					$cat_set2 = Array();
	
	
					foreach($cat_set as $v){
						if(!empty($row['p'.$v['p']])){
							$cat_set2[sizeof($cat_set2)+1] = $v['p'];
							$params .=  '<td >'.$v['title'].'</td>';			
						}
					}				
	
	
	
					/*foreach($cat_set as $v){
						$params .= '<th align="left">'.$v['title'].'</th>';			
					}*/				
					$goods_begin = str_replace('{PARAMS_TITLE}',$params,$goods_begin );			
					echo $goods_begin;				
					$f = 0;
				}
				
				$goods_row = $tmp_row[1];
				
				
				if($f == 0){
					$goods_row = str_replace('{TR_CLASS}','white_str',$goods_row );
					$f=1;
				}else{
					$goods_row = str_replace('{TR_CLASS}','grey_str',$goods_row );
					$f=0;
				}			
				$price=0;
				if(!empty($row['price_evro'])){
					$price = round($row['price_evro']*$kurs['val']);
				}else{
					$price = $row['price'];			
				}
				if(!empty($maker_sale[$row['maker']])){			
					$price = round($price-($price*$maker_sale[$row['maker']]/100));
				}
				$goods_row = str_replace('{GOOD_LINK}','/goods/'.$row['id'].'/',$goods_row );
				$goods_row = str_replace('{GOOD_TITLE}',$row['name'],$goods_row );
				$goods_row = str_replace('{GOOD_PRICE}',$price,$goods_row );			
				$goods_row = str_replace('{GOOD_ID}',(int)$row['id'],$goods_row );
				$params = '';
	/* 			foreach($cat_set as $v){
					$params .= '<td style="border-right:1px solid #ccc;border-bottom:1px solid #ccc;">'.$row['p'.$v['p']].'</td>';			
				}
	*/
				foreach($cat_set2 as $v){
					$params .= '<td >'.$row['p'.$v].'</td>';			
				}
	
				$goods_row = str_replace('{GOOD_PARAMS}',$params,$goods_row );
				echo $goods_row;
			}
			echo $tmp_end[1];
			/*********end goods maker**********/
			
			
		}else{
			$group_by = 'maker';	
			$where = '';	
			
			/*********goods maker222**********/
			$makers = $q->select("select * from ".$prefix."maker 
			where id in (select maker from ".$prefix."goods where cat_id = ".to_sql($cat_id).$where." and status=1 )");
		
			$tmpl = file_get_contents($inc_path.'parts/goods.php'); 
			preg_match("/<!--maker_begin-->(.+)<!--end_maker_begin-->/isU", $tmpl,$tmp_begin);
			
			foreach($makers as $maker){
					$makerimg = $root_path.'files/maker/'.$maker['id'].$maker['img'];
					if(is_file($makerimg)) $makerimg = '<img src="'.$makerimg.'" border="0">';
					else $makerimg = '';  				
					
					$goods_begin = $tmp_begin[1];
					$goods_begin = str_replace('{MAKER_IMG}',$makerimg,$goods_begin );
					$goods_begin = str_replace('{MAKER_NAME}',$maker['name'],$goods_begin );
					$goods_begin = str_replace('{MAKER_TEXT}',$maker['text'],$goods_begin );
					$goods_begin = str_replace('{MAKER_ID}',$maker['id'],$goods_begin );			
					echo $goods_begin ;
			}
			
			/*********end goods maker2222**********/
			
			
		}
		
		
		
		
		
		
	}

}
?>