<?
$q = new query(); 
$nname = get_param('nname');
if(empty($nname)){	
	$page_size = 10;
	$page_name = 'page';	
	$data = $q->select("SELECT * FROM ".$prefix."gallery WHERE status=1 order by rank desc LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size);
	$numb = $q->select1("select count(id) as number from ".$prefix."gallery where status=1");
	$cnt = count($data);
	$total_number = $numb['number'];
	draw_pages($page_size, $total_number, $page_name, "index.php" );
	
	$tmpl = file_get_contents($inc_path.'parts/gallery.php'); 
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
	
	echo $tmp_begin[1];	
	foreach($data as $row){
		$news_row = $tmp_row[1];
		$file = 'files/gallery/pre1_'.$row['id'].$row['img'];
		$img = '';
		if(is_file($root_path.$file)){
			$img = '<img src="/'.$file.'" border="0" align="left" style="margin-right:7px;">';
		}
		$news_row = str_replace('{NEWS_IMG}',$img,$news_row);
		$news_row = str_replace('{NEWS_LINK}','/left/fotogalereia/'.$row['cpu'].'/',$news_row);
		$news_row = str_replace('{NEWS_TITLE}',$row['title'],$news_row);
		$news_row = str_replace('{NEWS_ANONS}',$row['anons'],$news_row);
		echo $news_row;
		echo $tmp_delim[1];
	}
	echo $tmp_end[1];

}else{
	$row = $q->select1("SELECT * FROM ".$prefix."gallery WHERE status=1 AND cpu=".to_sql($nname)."");	
	echo '<h1>'.$row['title'].'</h1>';
	echo $row['text'];
	$foto = $q->select("select * from ".$prefix."gallery_foto where status=1 and gid=".to_sql($row['id'])." order by rank desc");
	echo '<table><tr valign="top">';
	$col=0;
	foreach($foto as $row){
        	$file = 'files/gallery_foto/pre1_'.$row['id'].$row['img'];
		$file2 = 'files/gallery_foto/pre2_'.$row['id'].$row['img'];
		$img = '';
		if(is_file($root_path.$file)){
			if($col == 4){
				echo '</tr><tr valign="top">';
				$col=0;
			}
			$img = '<img src="/'.$file.'" border="0" align="left" style="margin-right:7px;">';
			echo '<td><a href="/'.$file2.'" rel="gal_pict">
			<img src="/'.$file.'" border="0" style="border:1px solid #febd01"></a></td>';
			$col++;
		}

	}
	echo '</tr></table>';
	echo '<div><a href="/left/fotogalereia/">Назад</a></div>';
}
?>