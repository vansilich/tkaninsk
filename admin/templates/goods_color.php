<?

	//draw_pages($page_size, $total_number, $page_name, '',$draw_params );
	if(!empty($tmpl_goods)){
		$tmpl = file_get_contents($inc_path.'parts/'.$tmpl_goods); 
	}else{
		$tmpl = file_get_contents($inc_path.'parts/goods_color.php'); 
	}
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--price_old-->(.+)<!--end_price_old-->/isU", $tmpl,$tmp_price_old);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
	echo $tmp_begin[1];	
	$f=0;
	
	while ($row = mysql_fetch_assoc($goods)) {							
		$news_row = $tmp_row[1];

		//$img = get_image_folder($row,'files/color/1/','img1',1);
		$img = get_image_cpu($row,'files/goods/1/','img1',1);
		if(!empty($img)){
			$img =  '<img src="'.$img.'?v=2" border="0" alt="'.my_htmlspecialchars($good['name']).'" class="img">';
		}
		
		$news_row = str_replace('{NEWS_IMG}',$img,$news_row);
		$news_row = str_replace('{NEWS_TITLE}',$row['name'],$news_row);
		$news_row = str_replace('{PRICE}',$row['price'],$news_row); 
		if(!empty($row['price_old'])){
			$old_price = $tmp_price_old[1];
			$old_price = str_replace('{NEWS_PRICE_OLD}',$row['price_old'],$old_price);
			$news_row = str_replace('{PRICE_OLD}',$old_price,$news_row); 
		}else{
			$news_row = str_replace('{PRICE_OLD}','',$news_row); 
		}
		
		if($row['nal'] == 1){
			$news_row = str_replace('{nal}','<div class="presence">В наличии</div>',$news_row); 
		}else{
			$news_row = str_replace('{nal}','<div class="no_presence">Нет в наличии</div>',$news_row); 
		}
		
		$news_row = str_replace('{NEWS_ID}',$row['id'],$news_row);
		$news_row = str_replace('{NEWS_LINK}','/goods/'.$row['cpu'].'/',$news_row);	
		
		echo $news_row;
		$f++;
									
	}

	echo $tmp_end[1];

?>