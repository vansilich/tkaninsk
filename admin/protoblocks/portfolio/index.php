<?

$order = get_param('order');
if(empty($order)) $order = 'best';

echo '<div id="filtr_fon">
<div style="float:left"><img src="/img/filtr_fon_left.jpg" width="3" height="40" alt="" /></div>
<div style="float:left; margin:3px 0 0 10px"><span class="white_text">Сортировать по годам:</span> ';
echo '<a href="/?order=best" class="filtr_a';
if($order == 'best') echo 'c_a';
echo '">Лучшие</a>';

$years = $q->select("select distinct year from  ".$prefix."port where year>0 order by year desc");
foreach($years as $row){
	echo '  <a href="/?order='.$row['year'].'"  class="filtr_a';
	if($order == $row['year']) echo 'c_a';
	echo '">'.$row['year'].'</a>';
}

echo '  <a href="/?order=all" class="filtr_a';
if($order == 'all') echo 'c_a';
echo '">Вcе</a>  <a href="/design/" class="filtr_a">Работы нашего дизайнера</a></div></div>';

$q = new query(); 
$id = get_param('id');
if(empty($id)){	
	$page_size = 100;
	$page_name = 'page';
	$where = '';
	if($order == 'best'){
		$where = ' and best=1 ';
	
	}else{
		if($order != 'all'){
			$order = (int)$order;
			$where = ' and year='.$order;
		
		}	
	}
		


	$data = $q->select("SELECT * FROM ".$prefix."port WHERE status=1 ".$where." order by rank desc LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	
	
	$numb = $q->select1("select count(id) as number from ".$prefix."port where status=1 ".$where);
	$cnt = count($data);
	$total_number = $numb['number'];
	
	
	$tmpl = file_get_contents($inc_path.'templates/port.php'); 
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
	
	echo $tmp_begin[1];	
	

	
	foreach($data as $row){
		$full_row = $tmp_row[1];
		/*border:1px solid #a09f9e;*/
		$file = $root_path.'files/port/'.$row['id'].$row['img'];		
		if(is_file($file)){ 
		//$file = '<a href="/portfolio/?id='.$row['id'].'&order='.$order.'"><img src="'.$file.'" border="0" style=" margin:0  15px 0 0;"></a>';
			list($w, $h) = getimagesize($file);
			$file = '<a href="/portfolio/?id='.$row['id'].'&order='.$order.'"><div  style="cursor:pointer;width:'.$w.'px; height:'.$h.'px;	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.$file.'\');margin:0  15px 0 0;">
				<img src="'.$file.'" alt="" width="'.$w.'" height="'.$h.'" border="0" style="filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" /></div></a>';
				
			$file = '/files/port/'.$row['id'].$row['img'];


  
		}else $file = '';
		
		$full_row = str_replace('{PORT_LINK}','/portfolio/one.php?id='.$row['id'].'&order='.$order,$full_row);
		$full_row = str_replace('{PORT_IMG}',$file,$full_row);
		$full_row = str_replace('{PORT_NAME}',$row['name'],$full_row);
		//$full_row = str_replace('{PORT_COMPANY}',$row['company'],$full_row);
		$full_row = str_replace('{PORT_YEAR}',$row['year'],$full_row);
		if(!empty($row['link'])){
			$port_site = str_replace('{PORT_SITE}', str_replace('http://','',$row['link']),'| <noindex><a href="http://{PORT_SITE}" rel="nofollow" class="name" target="_blank">{PORT_SITE}</a></noindex>');
		}else{
			$port_site = '';
		}
		$full_row = str_replace('{PORT_SITE}', $port_site,$full_row);

		echo $full_row;
		echo $tmp_delim[1];
	}
	$order = get_param('order');
	if(empty($order)){
		$page = $q->select1("select text from ".$prefix."pages where id=".to_sql($this_page_id));
		echo '<br style="clear:both;"><div class="main_text">'.$page['text'].'</div>';
	}
	
	
	echo $tmp_end[1];
	
	draw_pages($page_size, $total_number, $page_name, "/portfolio/",'order='.$order,'name' );

}else{
	$row = $q->select1("SELECT * FROM ".$prefix."port WHERE status=1 AND id='".to_sql($id,"Number")."'");
		
/*	$file = $root_path.'files/port/pre2_'.$row['id'].$row['img'];	
	if(is_file($file)) $file = '<img src="'.$file.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;">';
	else $file = '';*/
	$full_row = file_get_contents($inc_path.'templates/port_full.php'); 
//	$full_row = str_replace('{PORT_LINK}','/portfolio/?id='.$row['id'],$full_row);
//	$full_row = str_replace('{PORT_IMG}',$file,$full_row);
	$full_row = str_replace('{PORT_NAME}',$row['name'],$full_row);
	$full_row = str_replace('{PORT_TEXT}',$row['text'],$full_row);
	//$full_row = str_replace('{PORT_COMPANY}',$row['company'],$full_row);
	$full_row = str_replace('{PORT_YEAR}',$row['year'],$full_row);
	$full_row = str_replace('{PORT_SITE}', str_replace('http://','',$row['link']),$full_row);
	
	
	
	$where = '';
	if($order == 'best'){
		$where = ' and best=1 ';
	
	}else{
		if($order != 'all'){
			$order = (int)$order;
			$where = ' and year='.$order;
		
		}	
	}
	$check = $q->select1("SELECT id FROM ".$prefix."port WHERE rank < ".$row['rank']." and status=1 ".$where." order by rank desc LIMIT 1");
	if($check == 0){
		$full_row = str_replace('{LINK_NEXT}','',$full_row);
	}else{
		$full_row = str_replace('{LINK_NEXT}','<a href="/portfolio/?id='.$check['id'].'&order='.$order.'"><img src="/img/next.jpg" title="следующая работа" alt="следующая работа" border="0"></a>',$full_row);
	}
	
	
	
	
	
	$check = $q->select1("SELECT id FROM ".$prefix."port WHERE rank > ".$row['rank']." and status=1 ".$where." order by rank LIMIT 1");
	if($check == 0){
		$full_row = str_replace('{LINK_PREV}','',$full_row);
	}else{
		$full_row = str_replace('{LINK_PREV}','<a href="/portfolio/?id='.$check['id'].'&order='.$order.'"><img src="/img/prev.jpg" title="предыдущая работа" alt="предыдущая работа" border="0"></a>',$full_row);
	}
	
	
	
	for($i=1;$i<=4;$i++){
		$file = $root_path.'files/port/'.$i.'/pre2_'.$row['id'].$row['img'.$i];	
		$filer = '/files/port/'.$i.'/pre2_'.$row['id'].$row['img'.$i];	
		//$file2 = '/files/port/'.$i.'/pre3_'.$row['id'].$row['img'.$i];	
		$file2 = '/files/port/'.$i.'/'.$row['id'].$row['img'.$i];	
		if(is_file($file)){
			$file = '<a href="'.$file2.'" class="thickbox" rel="lightbox" title="'.$row['img'.$i.'_text'].'"><img src="'.$filer.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"></a><br>';
		//$file = '<img src="'.$filer.'" title="'.$row['img'.$i.'_text'].'" alt="'.$row['img'.$i.'_text'].'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"><br>';
			$full_row = str_replace('{PORT_IMG'.$i.'}',$file,$full_row);
			$full_row = str_replace('{PORT_IMG'.$i.'_TEXT}',$row['img'.$i.'_text'].'<br><br>',$full_row);
		}else{
			$full_row = str_replace('{PORT_IMG'.$i.'}','',$full_row);
			$full_row = str_replace('{PORT_IMG'.$i.'_TEXT}','',$full_row);
		
		}
	}


	echo $full_row;
	
}




?>
