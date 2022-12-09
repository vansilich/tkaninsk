<?
$inc_path = "admin/";
$root_path = ""; 
include($inc_path."class/header.php");
/*
$newoid = $q->insert("insert into ".$prefix."orders_test set
					
					date_add = DATE_ADD(NOW(), INTERVAL 2 HOUR)
					");
*/




		$orders = $q->select("select * from ".$prefix."orders   order by id desc limit 3");

foreach($orders as $o){

	$date = date('Y-m-d',to_phpdate($o['changed'])+7200);
	$time = date('H:i:s',to_phpdate($o['changed'])+7200);
	echo $o['changed'].'='.$date.'='.$time.'<br><br>';
	
}
die();
function get_pict_sizes2($param,$w,$h){
		$picts = explode(';',$param);
		$i=0;
		foreach($picts as $row){
			if(!empty($row)){
				$one_pict = explode('x',$row);
				//  1    ,    2     
				if(empty($one_pict[1])){
					if($w>$h){
						if($one_pict[0] < $w){
							$w2 = $one_pict[0];
							$h2 = round(($h/($w/$w2)), 0);
						}else{
							$w2 = $w;
							$h2 = $h;
						
						}
					}else{
					
					
						if($one_pict[0] < $h){
							$h2 = $one_pict[0];
							$w2 = round(($w/($h/$h2)), 0);
						}else{
							$w2 = $w;
							$h2 = $h;						
						}
					
					
						
					}	
				}else{
					//    ()      
					if(!empty($one_pict[0]) && $one_pict[1] == 'auto'){
							$w2 = $one_pict[0];
							$h2 = round(($h/($w/$w2)), 0);						
					}else{
						//    ()      
						if(!empty($one_pict[1]) && $one_pict[0] == 'auto'){
								$h2 = $one_pict[1];
								$w2 = round(($w/($h/$h2)), 0);					
						}else{
							if(!empty($one_pict[0]) && !empty($one_pict[1])){
									$w2 = $one_pict[0];	
									$h2 = $one_pict[1];
									$w2 = $one_pict[0];					
							}
						}
					}
				}
				$res[$i]['w'] = $w2;
				$res[$i]['h'] = $h2;
				$i++;		
			}	
	}
	return $res;
}
/*
$goods = $q->select("select id,img1 from ".$prefix."port  order by id ");
$zz = 1;
$path = 'files/port/'.$zz.'/';
foreach($goods as $row){
	$filename = 'files/port/'.$zz.'/'.$row['id'].$row['img'.$zz];
	if(is_file($filename)){
		list($w, $h) = getimagesize($filename);	
		$pre_size = get_pict_sizes2('125;500xauto;800xauto',$w,$h);
		$i = 1;
		echo $path.'pre'.$i.'_'.$row['id'].$row['img'.$zz].'<br>';
		resize_then_crop($filename,$path.'pre'.$row['id'].$row['img'.$zz],50,50,255,255,255); 
		foreach($pre_size as $ps){
			resize_then_crop($filename,$path.'pre'.$i.'_'.$row['id'].$row['img'.$zz],$ps['w'],$ps['h'],255,255,255); 
			chmod($path.'pre'.$i.'_'.$row['id'].$row['img'.$zz], 0777);
			$i++;
			echo $path.'pre'.$i.'_'.$row['id'].$row['img'.$zz].'<br>';
			
		}
	}

}
*/
/*$zz = 1;
$goods = $q->select("select id,img".$zz.",img from ".$prefix."disport  order by id ");

$path = 'files/disport/';
foreach($goods as $row){
	$filename = 'files/disport/'.$zz.'/'.$row['id'].$row['img'.$zz];
	if(is_file($filename)){
		list($w, $h) = getimagesize($filename);	
		$pre_size = get_pict_sizes2('125',$w,$h);
		$i = 1;
		//echo $path.'pre'.$i.'_'.$row['id'].$row['img'.$zz].'<br>';
		resize_then_crop($filename,$path.$row['id'].$row['img'.$zz],238,356,255,255,255); 
		echo $path.$row['id'].$row['img'.$zz];
		resize_then_crop($filename,$path.'pre'.$row['id'].$row['img'.$zz],50,50,255,255,255); 
		foreach($pre_size as $ps){
			resize_then_crop($filename,$path.'pre'.$i.'_'.$row['id'].$row['img'.$zz],$ps['w'],$ps['h'],255,255,255); 
			chmod($path.'pre'.$i.'_'.$row['id'].$row['img'.$zz], 0777);
			$i++;
			//echo $path.'pre'.$i.'_'.$row['id'].$row['img'.$zz].'<br>';
			
		}
		$q->exec("update ".$prefix."disport set img=".to_sql($row['img'.$zz])." where id=".to_sql($row['id']));
	}

}
*/

$colimg = 0;
$z = 1;

$goods = $q->select("select id,img".$z." from ".$prefix."goods where img1!='' and pict=-1 order by id limit 3");
$path = 'files/goods/'.$z.'/0/';
$folder = 'files/goods/1/';

echo 'zz';
foreach($goods as $row){
	$fol = (int)($row['id']/1000);
	$path = $folder.$fol.'/';
	
	
	$filename =$img = get_image_cpu($row,'files/goods/1/','img1',0);
	$filename = ltrim($filename,'/');
	if(is_file($filename)){
		
		list($w, $h) = getimagesize($filename);	
		$pre_size = get_pict_sizes2('265x175;600;1000',$w,$h);
		$i = 1;
		resize_then_crop($filename,$path.'pre'.$row['img1'],50,50,255,255,255); 
		echo $filename.'='.(filesize($filename)/pow(1024, 2)).'<br>';
		foreach($pre_size as $ps){
			resize_then_crop($filename,$path.'pre'.$i.'_'.$row['img1'],$ps['w'],$ps['h'],255,255,255); 
			chmod($path.'pre'.$i.'_'.$row['img1'], 0777);
			
			//echo $path.'pre'.$i.'_'.$row['img1'].'<br>';
			$i++;
		}
		
		
               $q->exec("update ".$prefix."goods set pict=2 where id=".to_sql($row['id']));
$colimg++;
	}
}


for($z = 2;$z<=6;$z++){
$folder = 'files/goods/'.$z.'/';



$goods = $q->select("select id,img1,img".$z." from ".$prefix."goods where img".$z."!='' and pict=".($z)." order by id limit 3");
echo "select id,img1,img".$z." from ".$prefix."goods where img".$z."!='' and pict=".($z)." order by id limit 3<br>";
echo 'z='.$z.'<br>';
foreach($goods as $row){
	
	
	$fol = (int)($row['id']/1000);
	$path = $folder.$fol.'/';
	
	$filename =$img = get_image_cpu($row,'files/goods/'.$z.'/','img'.$z,0);
	$filename = ltrim($filename,'/');
	if(is_file($filename)){
		
		list($w, $h) = getimagesize($filename);	
		$pre_size = get_pict_sizes2('265x175;600;1000',$w,$h);
		$i = 1;
		resize_then_crop($filename,$path.'pre'.$row['img'.$z],50,50,255,255,255); 
		echo $filename.'='.(filesize($filename)/pow(1024, 2)).'<br>';
		foreach($pre_size as $ps){
			resize_then_crop($filename,$path.'pre'.$i.'_'.$row['img'.$z],$ps['w'],$ps['h'],255,255,255); 
			chmod($path.'pre'.$i.'_'.$row['img'.$z], 0777);
			
			//echo $path.'pre'.$i.'_'.$row['img1'].'<br>';
			$i++;
		}
		
		
               $q->exec("update ".$prefix."goods set pict=".to_sql($z+1)." where id=".to_sql($row['id']));
		$colimg++;
	}
}
}
  /*
$zz = 1;
$goods = $q->select("select id,img".$zz." from ".$prefix."goods_price where all_img<>'' and pict=0 order by id limit 10 ");

$path = 'files/color/1/0/';
foreach($goods as $row){
	$filename = 'files/color/1/0/'.$row['id'].$row['img'.$zz];
	if(is_file($filename)){
		echo $filename.'<br>';
		list($w, $h) = getimagesize($filename);	
		$pre_size = get_pict_sizes2('265x175;600;1000',$w,$h);
		$i = 1;
		foreach($pre_size as $ps){
			resize_then_crop($filename,$path.'pre'.$i.'_'.$row['id'].$row['img'.$zz],$ps['w'],$ps['h'],255,255,255); 
			chmod($path.'pre'.$i.'_'.$row['id'].$row['img'.$zz], 0777);
			
			echo $path.'pre'.$i.'_'.$row['id'].$row['img'.$zz].'<br>';
			$i++;
		}
		$q->exec("update ".$prefix."goods_price set pict=1 where id=".to_sql($row['id']));
$colimg++;
	}

}

  */
$t = get_param('t');
echo $t.'='.$z;

if($colimg > 0){
	echo $colimg.'<script>
	
	setTimeout("setPoints()",1000);
	function setPoints(){
		document.location.href="/pict.php?t='.($t+$colimg).'";	
	}
	</script>';	
}


?>