<?
function get_size($size) {
$bytes = array('B','KB','MB','GB','TB');
  foreach($bytes as $val) {
   if($size > 1024){
	$size = $size / 1024;
   }else{
	break;
   }
  }
  return round($size, 2)." ".$val;
}

$cpu_good = get_param('cpu_good');
$good = $q->select1("select * from ".$prefix."goods where cpu = ".to_sql($cpu_good)." and status=1 ");
$goods_row = file_get_contents($inc_path.'parts/goods_full.php');                                             


//$price= $good['last_price'];
$price= $good['price'];

$goods_row = str_replace('{GOOD_TITLE}',$good['name'],$goods_row );
$goods_row = str_replace('{GOOD_PRICE}',$price,$goods_row );			
$goods_row = str_replace('{GOOD_ID}',(int)$good['id'],$goods_row );
$goods_row = str_replace('{GOOD_TEXT}',$good['full'],$goods_row );
$goods_row = str_replace('{GOOD_ANONS}',$good['anons'],$goods_row );
$goods_row = str_replace('{GOOD_ARTICUL}',$good['articul'],$goods_row );

$goods_row = str_replace('{GOOD_PRINT}','print_goods.php?id='.$good['id'],$goods_row );		

$bimg = $simg = '';
for($i=1; $i<=4;$i++){
	$goodimg = $root_path.'files/catalog/'.$i.'/pre2_'.$good['id'].$good['img'.$i];
	$goodimg2 = $root_path.'files/catalog/'.$i.'/pre3_'.$good['id'].$good['img'.$i];
	$goodimg3 = $root_path.'files/catalog/'.$i.'/pre'.$good['id'].$good['img'.$i];
	
	if(is_file($goodimg)){ 
		$bimg .= '<a href="'.$goodimg2.'"  title="'.my_htmlspecialchars($good['name']).'" rel="gal_pict"';
		if($i != 1) $bimg .= ' style="display:none"';
		$bimg .= ' id="gimg'.$i.'"><img src="'.$goodimg.'" border="0" class="pic3"></a>';
		
		$simg .= '<img src="'.$goodimg3.'" border="0" class="pic3"
		onclick = "ch_img('.$i.')"	style="cursor:pointer"
		>&nbsp;&nbsp;&nbsp;';
	}
	
}
$goods_row = str_replace('{GOOD_IMG}',$bimg,$goods_row );
$goods_row = str_replace('{GOOD_SMALL_IMG}',$simg,$goods_row );

$params = '<table cellpadding="0" cellspacing="0" style="line-height:32px;">';
$settings = $q->select("select C.*,P.name,P.types, P.id as pid, P.dimension from ".$prefix."catalog_params as C
join ".$prefix."adv_params as P on P.id = C.param_id
where C.status=1 and C.cat_id=".to_sql($this_cat['id'])." order by C.rank desc");
$f = 0;

foreach($settings as $v){
	$check = $q->select1("select * from ".$prefix."goods_param where good_id=".to_sql($good['id'])." and param_id=".to_sql($v['pid']));
	if(!empty($check)){
		if($v['types'] == 'c_int'){
			$params .= '<tr>
			<td style="padding-right:60px;">'.$v['name'].'</td>
			<td>'.$check['ival'].' '.$v['dimension'].'</td>
			</tr>';	
		}else{
			$params .= '<tr>
			<td style="padding-right:60px;">'.$v['name'].'</td>
			<td>'.$check['cval'].' '.$v['dimension'].'</td>
			</tr>';
		}
		$f++;
	}
}
$params .= '</table>';

$goods_row = str_replace('{GOOD_PARAMS}',$params,$goods_row );
	 

echo $goods_row;




/*comments*/
/*
$action = get_param('action');
if($action == 'add_comm'){
	$code = get_param('code');
	$name = get_param('fio');
	$email = get_param('email');
	$text = get_param('text2');
	$check = get_param('text');
	
	if($_SESSION['str'] == $code && !empty($_SESSION['str']) && empty($check)){
		$q->insert("insert into ".$prefix."goods_comm set
		name=".to_sql($name).",
		email=".to_sql($email).",
		text=".to_sql($text).",
		gid=".to_sql($id).",
		date_add = NOW(),
		status = 0		
		");
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= "From: teploexert.com - Отзывы! <ceo@teploexpert.com>\r\n";
		$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
		$msg = '<b>имя</b>:'.$name.'<br><br><b>email</b>:'.$email.'<br><br><b>Отзыв</b>:'.$text;
		mail('ceo@teploexpert.com', 'Добавлен отзыв', $msg, $headers);

		$goods_row = str_replace('{GOODS_COMM_MSG}','<div style="color:green">Спасибо за сообщение! После проверки администратором, сообщение будет отображено на сайте!</div>',$goods_row );	
		$_SESSION['str'] = '';
	}else{
		if(!empty($check)){
			$goods_row = str_replace('{GOODS_COMM_MSG}','<div style="color:red">Сообщение похоже на спам.</div>',$goods_row );
		}else{
			$goods_row = str_replace('{GOODS_COMM_MSG}','<div style="color:red">Не правильно набран "код"</div>',$goods_row );		
		}
	}

}else{
	$goods_row = str_replace('{GOODS_COMM_MSG}','',$goods_row );
}
$comm = $q->select("select * from ".$prefix."goods_comm where status=1 and gid=".to_sql($id));
$tmp = '';
$f = 0;
foreach($comm as $row){	
	if($f == 0){	
		$tmp .= '<div class="white" align="justify">';
		$f = 1;
	}else{
		$tmp .= '<div class="grey" align="justify">';
		$f = 0;
	}
	
	$tmp .= '
	  <div class="header">
	 <div class="date">'.date('d.m.Y',to_phpdate($row['date_add'])).'&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;'.date('H:i',to_phpdate($row['date_add'])).'</div>
	 <b>'.$row['name'].'</b> </div>
	  <!--header-->
	  '.$row['text'].' 
	  </div>
	  <!--white-->';

}
$goods_row = str_replace('{GOODS_COMM}',$tmp,$goods_row );		
*/



/*
			$goods_git = $q->select("select * from ".$prefix."goods where cat_id = ".to_sql($cat_id)."  and status=1 and hit=1 LIMIT 3");
			if(sizeof($goods_git) > 0){
				$tmpl = file_get_contents($inc_path.'parts/hit.php');
				
				foreach($goods_git as $row){
					$goods_row = $tmpl;	
					$goodimg = $root_path.'files/catalog/pre1_'.$row['id'].$row['img'];
					if(is_file($goodimg)){
						
						$goodimg =  '<img src="'.$goodimg.'" border="0" alt="'.$row['name'].'">';
					 
					}
					else $goodimg =  '';
					$price = $row['last_price'];	
					
					
					$goods_row = str_replace('{GOOD_LINK}','/'.(int)$row['id'].'-'.rutranslit($row['name']).'.html',$goods_row );
					$goods_row = str_replace('{GOOD_TITLE}',$row['name'],$goods_row );
					$goods_row = str_replace('{GOOD_IMG}',$goodimg,$goods_row );	
					$goods_row = str_replace('{GOOD_PRICE}',$price,$goods_row );
					$goods_row = str_replace('{GOOD_ID}',(int)$row['id'],$goods_row );
					echo $goods_row;
				}
				
				
				echo '<div class="clear"></div>';
			
			}
*/

?>