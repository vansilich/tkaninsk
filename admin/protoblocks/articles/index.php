<?
$page_size = 10;
$page_name = 'page';	
$link = '/articles/';
$img_folder = 'files/articles/';

$cpu = get_param('cpu');
if(empty($cpu)){	

	$data = $q->select("SELECT * FROM ".$prefix."articles WHERE status=1 order by created desc  LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	$numb = $q->select1("select count(id) as number from ".$prefix."articles where status=1");
	$cnt = count($data);
	$total_number = $numb['number'];
	
	
	$tmpl = file_get_contents($inc_path.'protoblocks/articles/templates/articles.php'); 
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
	
	echo $tmp_begin[1];	
	foreach($data as $row){
		$date = date('d.m.Y',to_phpdate($row['created']));
		$img = $link_img = '';
		if(is_file($root_path.$img_folder.'pre'.$row['id'].$row['img'])){
			$img = '<img src="/'.$img_folder.'pre1_'.$row['id'].$row['img'].'" alt="'.my_htmlspecialchars($row['name']).'" border="0" class="border">';							
		}
		$tmpl_row = $tmp_row[1];
		$tmpl_row = str_replace('{F_LINK}',$link.$row['cpu'].'/',$tmpl_row);
		$tmpl_row = str_replace('{F_NAME}',$row['name'],$tmpl_row);
		$tmpl_row = str_replace('{F_DATE}',$date,$tmpl_row);
		$tmpl_row = str_replace('{F_IMG}',$img,$tmpl_row);
		$tmpl_row = str_replace('{F_ANONS}',$row['anons'],$tmpl_row);
		echo $tmpl_row;
		echo $tmp_delim[1];
	}
	echo $tmp_end[1];
	echo '<div align="right">';
	draw_pages($page_size, $total_number, $page_name, "index.php" );
	echo '</div>';

}else{
	$row = $q->select1("SELECT * FROM ".$prefix."articles WHERE status=1 AND 
	cpu=".to_sql($cpu));	
	$date = date('d.m.Y',to_phpdate($row['created']));
	
	$tmpl_row = file_get_contents($inc_path.'protoblocks/articles/templates/articles_full.php'); 
	$tmpl_row = str_replace('{F_NAME}',$row['name'],$tmpl_row);
	$tmpl_row = str_replace('{F_ANONS}',$row['anons'],$tmpl_row);
	$tmpl_row = str_replace('{F_DATE}',$date,$tmpl_row);
	$tmpl_row = str_replace('{F_TEXT}',$row['text'],$tmpl_row);
	echo $tmpl_row;

	echo '<div><a href="'.$link.'">Все статьи</a></div>';	
}


?>