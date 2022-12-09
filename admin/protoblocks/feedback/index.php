<?
$q = new query(); 
$n_name = get_param('n_name');
if(empty($n_name)){	
	$page_size = 10;
	$page_name = 'page';	
	$data = $q->select("SELECT * FROM ".$prefix."feedback WHERE status=1 order by created desc  LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	$numb = $q->select1("select count(id) as number from ".$prefix."feedback where status=1");
	$cnt = count($data);
	$total_number = $numb['number'];
	
	
	$tmpl = file_get_contents($inc_path.'parts/feedback.php'); 
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
	
	echo $tmp_begin[1];	
	foreach($data as $row){
		$date = date('d.m.Y',to_phpdate($row['created']));
		$img = $link_img = '';
		if(is_file($root_path.'files/feedback/pre'.$row['id'].$row['img'])){
			$img = '<img src="/files/feedback/pre1_'.$row['id'].$row['img'].'" alt="'.my_htmlspecialchars($row['title']).'" border="0" class="border_foto_big">';							
		}
		$feedback_row = $tmp_row[1];
		$feedback_row = str_replace('{feedback_LINK}','/feedback/'.$row['cpu'].'/',$feedback_row);
		$feedback_row = str_replace('{feedback_TITLE}',$row['title'],$feedback_row);
		$feedback_row = str_replace('{feedback_DATE}',$date,$feedback_row);
		$feedback_row = str_replace('{feedback_IMG}',$img,$feedback_row);
		$feedback_row = str_replace('{feedback_ANONS}',$row['anons'],$feedback_row);
		echo $feedback_row;
		echo $tmp_delim[1];
	}
	echo $tmp_end[1];
	echo '<div align="right">';
	draw_pages($page_size, $total_number, $page_name, "index.php" );
	echo '</div>';

}else{
	$row = $q->select1("SELECT * FROM ".$prefix."feedback WHERE status=1 AND 
	cpu=".to_sql($n_name));	
	$date = date('d.m.Y',to_phpdate($row['created']));
	
	$feedback_row = file_get_contents($inc_path.'parts/feedback_full.php'); 
	$feedback_row = str_replace('{feedback_TITLE}',$row['title'],$feedback_row);
	$feedback_row = str_replace('{feedback_ANONS}',$row['anons'],$feedback_row);
	$feedback_row = str_replace('{feedback_DATE}',$date,$feedback_row);
	$feedback_row = str_replace('{feedback_TEXT}',$row['text'],$feedback_row);
	echo $feedback_row;

	echo '<div><a href="/feedback/">Назад</a></div>';	
}


?>