
<?

$q = new query(); 
$id = get_param('id');
if(!empty($id)){	



	$row = $q->select1("SELECT * FROM ".$prefix."port WHERE status=1 AND id='".to_sql($id,"Number")."'");
		

	$full_row = file_get_contents($inc_path.'templates/port_full.php'); 

	$full_row = str_replace('{PORT_NAME2}',$row['name2'],$full_row);
	$full_row = str_replace('{PORT_NAME}',$row['name'],$full_row);
	$full_row = str_replace('{PORT_TEXT}',$row['text'],$full_row);
	$full_row = str_replace('{PORT_YEAR}',$row['year'],$full_row);
	if(!empty($row['link'])){
		$full_row = str_replace('{PORT_SITE}', 'http://'.str_replace('http://','',$row['link']),$full_row);
	}else{
		$full_row = str_replace('{PORT_SITE}', '',$full_row);	
	}
	
	
	
	$where = '';
	if($order == 'best'){
		$where = ' and best=1 ';
	
	}else{
		if($order != 'all'){
			$order = (int)$order;
			$where = ' and year='.$order;
		
		}	
	}
	/*$check = $q->select1("SELECT id FROM ".$prefix."port WHERE rank < ".$row['rank']." and status=1 ".$where." order by rank desc LIMIT 1");
	if($check == 0){
		$full_row = str_replace('{LINK_NEXT}','',$full_row);
	}else{
		$full_row = str_replace('{LINK_NEXT}','<a href="/portfolio/one.php?id='.$check['id'].'&order='.$order.'"><img src="/img/next.jpg" title="следующая работа" alt="следующая работа" border="0"></a>',$full_row);
	}
	
	
	
	
	
	$check = $q->select1("SELECT id FROM ".$prefix."port WHERE rank > ".$row['rank']." and status=1 ".$where." order by rank LIMIT 1");
	if($check == 0){
		$full_row = str_replace('{LINK_PREV}','',$full_row);
	}else{
		$full_row = str_replace('{LINK_PREV}','<a href="/portfolio/one.php?id='.$check['id'].'&order='.$order.'"><img src="/img/prev.jpg" title="предыдущая работа" alt="предыдущая работа" border="0"></a>',$full_row);
	}
	
	*/
	
	for($i=1;$i<=4;$i++){
		$file = $root_path.'files/port/'.$i.'/pre2_'.$row['id'].$row['img'.$i];	
		$filer = '/files/port/'.$i.'/pre3_'.$row['id'].$row['img'.$i];		
		$file2 = '/files/port/'.$i.'/'.$row['id'].$row['img'.$i];	
		if(is_file($file)){
			$file = '<a href="'.$file2.'" class="colorbox" rel="lightbox" title="'.$row['img'.$i.'_text'].'"><img src="'.$filer.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"></a><br>';
			$file = '<img src="'.$filer.'" border="0" style="border:1px solid #a09f9e; margin:5px 0 0 0;"><br>';
			$full_row = str_replace('{PORT_IMG'.$i.'}',$file.'<hr>',$full_row);
			$full_row = str_replace('{PORT_IMG'.$i.'_TEXT}','<b>'.$row['img'.$i.'_text'].'</b>',$full_row);
		}else{
			$full_row = str_replace('{PORT_IMG'.$i.'}','',$full_row);
			$full_row = str_replace('{PORT_IMG'.$i.'_TEXT}','',$full_row);
		
		}
	}


	echo $full_row;
	
}




	
?>