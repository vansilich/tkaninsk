<?
$q = new query(); 
$id = get_param('id');
if(!empty($id)){	
	$row = $q->select1("SELECT * FROM ".$prefix."disport WHERE status=1 AND id='".to_sql($id,"Number")."'");
		
/*	$file = $root_path.'files/port/pre2_'.$row['id'].$row['img'];	
	if(is_file($file)) $file = '<img src="'.$file.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;">';
	else $file = '';*/
	$full_row = file_get_contents($inc_path.'templates/port_full.php'); 
//	$full_row = str_replace('{PORT_LINK}','/design/?id='.$row['id'],$full_row);
//	$full_row = str_replace('{PORT_IMG}',$file,$full_row);
	$full_row = str_replace('{PORT_NAME2}',$row['name2'],$full_row);
	$full_row = str_replace('{PORT_NAME}',$row['name'],$full_row);
	$full_row = str_replace('{PORT_TEXT}',$row['text'],$full_row);
	//$full_row = str_replace('{PORT_COMPANY}',$row['company'],$full_row);
	$full_row = str_replace('{PORT_YEAR}',$row['year'],$full_row);
	$full_row = str_replace('{PORT_SITE}', str_replace('http://','',$row['link']),$full_row);
	
	
	
	$where = '';	
	/*$check = $q->select1("SELECT id FROM ".$prefix."disport WHERE rank < ".$row['rank']." and status=1 ".$where." order by rank desc LIMIT 1");
	if($check == 0){
		$full_row = str_replace('{LINK_NEXT}','',$full_row);
	}else{
		$full_row = str_replace('{LINK_NEXT}','<a href="/design/one.php?id='.$check['id'].'"><img src="/img/next.jpg" title="следующая работа" alt="следующая работа" border="0"></a>',$full_row);
	}	
	
	
	$check = $q->select1("SELECT id FROM ".$prefix."disport WHERE rank > ".$row['rank']." and status=1 ".$where." order by rank LIMIT 1");
	if($check == 0){
		$full_row = str_replace('{LINK_PREV}','',$full_row);
	}else{
		$full_row = str_replace('{LINK_PREV}','<a href="/design/one.php?id='.$check['id'].'"><img src="/img/prev.jpg" title="предыдущая работа" alt="предыдущая работа" border="0"></a>',$full_row);
	}
	*/
	
	
	for($i=1;$i<=4;$i++){
		$file = $root_path.'files/disport/'.$i.'/pre2_'.$row['id'].$row['img'.$i];	
		$filer = '/files/disport/'.$i.'/pre3_'.$row['id'].$row['img'.$i];	
		//$file2 = '/files/disport/'.$i.'/pre3_'.$row['id'].$row['img'.$i];	
$file2 = '/files/disport/'.$i.'/'.$row['id'].$row['img'.$i];	
		if(is_file($file)){
			//$file = '<a href="'.$file2.'" class="thickbox" rel="lightbox" title="'.$row['img'.$i.'_text'].'"><img src="'.$filer.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"></a><br>';
			//$file = '<img src="'.$filer.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"><br>';
			$file = '<img src="'.$filer.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"><br>';
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