<?
$q = new query(); 
$n_name = get_param('n_name');
if(empty($n_name)){	
	
	$page_size = 9;
	$page_name = 'page';	
	$data = $q->select("SELECT * FROM ".$prefix."video WHERE status=1 order by created desc  LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	$numb = $q->select1("select count(id) as number from ".$prefix."video where status=1");
	$cnt = count($data);
	$total_number = $numb['number'];
	
	
	$tmpl = file_get_contents($inc_path.'parts/video.php'); 
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
	$tmp_begin[1] = str_replace('{TOP}',$top, $tmp_begin[1]);
	echo $tmp_begin[1];	
	$f = 0;
	foreach($data as $row){
		if($f == 3){
			echo $tmp_delim[1];
			$f = 0;	
		}
		$date = date('d.m.Y',to_phpdate($row['created']));
		$img = $link_img = '';
		if(is_file($root_path.'files/video/pre'.$row['id'].$row['img'])){
			$img = '<a href="/nashe_video/'.$row['cpu'].'/"><img src="/files/video/pre1_'.$row['id'].$row['img'].'" alt="'.my_htmlspecialchars($row['title']).'" border="0" class="border"></a>';							
		}
		$news_row = $tmp_row[1];
		$news_row = str_replace('{NEWS_LINK}','/video/'.$row['cpu'].'/',$news_row);
		$news_row = str_replace('{NEWS_TITLE}',$row['title'],$news_row);
		$news_row = str_replace('{NEWS_DATE}',$date,$news_row);
		$news_row = str_replace('{NEWS_IMG}',$img,$news_row);
		$news_row = str_replace('{NEWS_ANONS}',$row['anons'],$news_row);
		echo $news_row;
		$f++;
	}
	echo $tmp_end[1];
	echo '<div align="right">';
	draw_pages($page_size, $total_number, $page_name, "index.php" );
	echo '</div>';

}else{
	$row = $q->select1("SELECT * FROM ".$prefix."video WHERE status=1 AND 
	cpu=".to_sql($n_name));	
	$date = date('d.m.Y',to_phpdate($row['created']));
	
	$news_row = file_get_contents($inc_path.'parts/news_full.php'); 
	$news_row = str_replace('{NEWS_TITLE}',$row['title'],$news_row);
	$news_row = str_replace('{NEWS_ANONS}',$row['anons'],$news_row);
	$news_row = str_replace('{NEWS_DATE}',$date,$news_row);
	$news_row = str_replace('{NEWS_TEXT}',$row['text'],$news_row);
	echo $news_row;

	echo '<div><a href="/nashe_video/">Все видео</a></div>';	
}


?>