<?
/*
$inc_path = "../admin/"; $root_path="../" ; 
include($inc_path."class/header.php");		$q = new query();
*/

/*
$goods = $q->select("select all_img,id from ".$prefix."goods where all_img<>'' and pict=0 ");
//$goods = $q->select("select img,img1,bumba_id,id from ".$prefix."goods where img<>'' and bumba_id>0 and pict=1 and id >8600 limit 100");
$i = 2;
$x=0;
foreach($goods as $v){
	
	$z=1;
	
	$mas = explode('|',$v['all_img']);
	foreach($mas as $row){
		if(empty($row)) continue;
	
		$fol = (int)($v['id']/1000);
		$path2 = 'files/goods/'.$z.'/';
		$pathn = $root_path.'files/goods/'.$z.'/'.$fol.'/';
		make_dir($pathn);
		
		$f1 = 'temp/'.$row;
		if(is_file($f1)){
			$f2 = $pathn.$v['id'].'.jpg';
		//	echo $f1.'='.$f2.'<br>';
			copy($f1,$f2);
			chmod($f2,0777);
			
			$q->exec("update ".$prefix."goods set img".$z."='".$v['id'].".jpg', pict=1 where id=".to_sql($v['id']));
		}
		$z++;
	}
	//$q->exec("update ".$prefix."goods set pict=1 where id=".to_sql($v['id']));
	
}
*/

?>