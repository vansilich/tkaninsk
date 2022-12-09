<?
	$cpu = get_param('cpu');
	if(!empty($cpu)){	
		$row = $q->select1("SELECT seo_title,seo_description,seo_keywords,id FROM ".$prefix."news WHERE status=1 AND cpu=".to_sql($cpu));	
		$title = $row['seo_title']; 
		$descr = $row['seo_description']; 
		$keys = $row['seo_keywords']; 
	}	


?>