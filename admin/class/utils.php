<? if (!defined("_utils_inc")) : ?>
<? define("_utils_inc",1); ?>
<?php
function get_user_info($field){
	if(!empty($_SESSION['user_info']['id'])){
		return my_htmlspecialchars( $_SESSION['user_info'][$field]);
	}
	return '';
}

function get_image($row,$folder,$iext,$npre){
	global $root_path;
	$pathn = $folder;
	$img = '';
	if($npre == 0){
		if(is_file($root_path.$pathn.$row['id'].$row[$iext])){
			$img = '/'.$pathn.$row['id'].$row[$iext];
		}
	}elseif($npre==-1){
		if(is_file($root_path.$pathn.'pre'.$row['id'].$row[$iext])){
			$img = '/'.$pathn.'pre'.$row['id'].$row[$iext];
		}		
	}else{
		if(is_file($root_path.$pathn.'pre'.$npre.'_'.$row['id'].$row[$iext])){
			$img = '/'.$pathn.'pre'.$npre.'_'.$row['id'].$row[$iext];
		}	
	}
	return $img;
}
function get_image_folder($row,$folder,$iext,$npre){
	$fol = (int)($row['id']/1000);
	$pathn = $folder.$fol.'/';
	return get_image($row,$pathn,$iext,$npre);
}
function get_image_cpu($row,$folder,$iext,$npre){
	global $root_path;
	$fol = (int)($row['id']/1000);
	$pathn = $folder.$fol.'/';
	$img = '';
	if($npre == 0){
		if(is_file($root_path.$pathn.$row[$iext])){
			$img = '/'.$pathn.$row[$iext];
		}
	}elseif($npre==-1){
		if(is_file($root_path.$pathn.'pre'.$row[$iext])){
			$img = '/'.$pathn.'pre'.$row[$iext];
		}		
	}else{
		if(is_file($root_path.$pathn.'pre'.$npre.'_'.$row[$iext])){
			$img = '/'.$pathn.'pre'.$npre.'_'.$row[$iext];
		}	
	}
	return $img;
}


function strip($value)
{
	if(get_magic_quotes_gpc() != 0)
	{
		if(is_array($value))
		{
	    	foreach($value as $key=>$val)
	    	{
	        	$value[$key] = stripslashes($val);
	    	}
		}
		else
		{
			$value = stripslashes($value);
		}
	}
	return $value;
}
function my_htmlspecialchars($str){
	return htmlspecialchars($str,ENT_COMPAT,'UTF-8');
}

function get_param($parameter_name, $default_value = "")
{
    $parameter_value = "";
    if (isset($_POST[$parameter_name]))
    {
    	$parameter_value = strip($_POST[$parameter_name]);
    }
    elseif (isset($_GET[$parameter_name]))
    {
        $parameter_value = strip($_GET[$parameter_name]);
    }
    else
    {
        $parameter_value = $default_value;
	}
    return $parameter_value;
}


function get_session($parameter_name)
{
    return isset($_SESSION[$parameter_name]) ? $_SESSION[$parameter_name] : "";
}
function set_session($param_name, $param_value)
{
    $_SESSION[$param_name] = $param_value;
}
function get_cookie($parameter_name)
{
    return isset($_COOKIE[$parameter_name]) ? $_COOKIE[$parameter_name] : "";
}
function set_cookie($parameter_name, $param_value, $expired = -1)
{
	if ($expired == -1)
	{
		$expired = time() + 3600 * 24 * 366;
	}
	elseif ($expired && $expired < time())
	{
		$expired = time() + $expired;
	}
	setcookie($parameter_name, $param_value, $expired, '/');
}

function to_tmpl($value)
{
	$value = nl2br($value);
	return $value;
}
function to_php($Value)
{
	$r = $Value;
	$r = str_replace("\"", "\\\"", $r);
	$r = str_replace("'", "&#39;", $r);
	#$r = my_htmlspecialchars($r, ENT_QUOTES);
	#$r = htmlentities($r);
	if (substr($r, 0, -1) == "\\") $r = substr($r, 0, (strlen($r) - 1));
	return $r;
}
function to_url($Value)
{
	return urlencode($Value);
}

function html2txt($document){
	$search = array('@<script[^>]*?>.*?</script>@si');// Strip out javascript
	$text = preg_replace($search, '', $document);
	return $text;
}


function to_sql($Value, $ValueType = "Text")
{
	//$Value = stripcslashes($Value);
	if ($ValueType == "Plain")
	{
		return addslashes($Value);
	}
	elseif ($ValueType == "Number" || $ValueType == "Float")
   	{
   		return doubleval(str_replace(",", ".", $Value));
   	}
   	elseif ($ValueType == "Check")
   	{
   		return ($Value == 1 ? "'Y'" : "'N'");
	}
	elseif ($ValueType == "Text")
	{
		return "'" . addslashes($Value) . "'";
	}
   	else
   	{
   		return "'" . addslashes($Value) . "'";
   	}
}
function clear_text($Value){
	$search = array('@<script[^>]*?>.*?</script>@si');// Strip out javascript
	$Value = preg_replace($search, '', $Value);
	$Value = strip_tags($Value);
	return to_sql($Value);
}

//time_mysql_dt2u
function to_phpdate($row)
{
	if ($row == "0000-00-00 00:00:00" or $row == "00000000000000" or $row == "")
	{
		$row = 1;
	}
	else
	{
		if (strlen($row) == 14)
		{
			$date[0] = substr($row, 0, 4);
			$date[1] = substr($row, 4, 2);
			$date[2] = substr($row, 6, 2);
			$time[0] = substr($row, 8, 2);
			$time[1] = substr($row, 10, 2);
			$time[2] = substr($row, 12, 2);
		}
		else
		{
			$d = explode(" ", $row);
			$time = explode(":", $d[1]);
			$date = explode("-", $d[0]);
		}
		$row = mktime($time[0], $time[1], $time[2], $date[1],  $date[2], $date[0]);
	}
	return $row;
}



function resize_then_crop( $filein,$fileout,
	$imagethumbsize_w,$imagethumbsize_h,$red,$green,$blue, $quality = 100)
{
// Get new dimensions
list($width, $height) = getimagesize($filein);
/*$new_width = $width * $percent;
$new_height = $height * $percent;
*/
	if(preg_match("/.jpg/i", "$filein"))
	{
		$format = 'image/jpeg';
	}
	if(preg_match("/.jpeg/i", "$filein"))
	{
		$format = 'image/jpeg';
	}
	if (preg_match("/.gif/i", "$filein"))
	{
		$format = 'image/gif';
	}
	if(preg_match("/.png/i", "$filein"))
	{
		$format = 'image/png';
	}
	switch($format)
	{
		case 'image/jpeg':

		if(@$image = imagecreatefromjpeg($filein)){
		}else{
			@$image = imagecreatefromgif($filein);
		}  
		      
		//           $image = imagecreatefromjpeg($filein);
		break;
		case 'image/gif';
		$image = imagecreatefromgif($filein);
		break;
		case 'image/png':
		$image = imagecreatefrompng($filein);
		break;
	}
	$width = $imagethumbsize_w ;
	$height = $imagethumbsize_h ;
	list($width_orig, $height_orig) = getimagesize($filein);

	if ($width_orig < $height_orig) {
	  $height = ($imagethumbsize_w / $width_orig) * $height_orig;
	} else {
	   $width = ($imagethumbsize_h / $height_orig) * $width_orig;
	}

	if ($width < $imagethumbsize_w)
	//if the width is smaller than supplied thumbnail size 
	{
	$width = $imagethumbsize_w;
	$height = ($imagethumbsize_w/ $width_orig) * $height_orig;;
	}

	if ($height < $imagethumbsize_h)
	//if the height is smaller than supplied thumbnail size 
	{
	$height = $imagethumbsize_h;
	$width = ($imagethumbsize_h / $height_orig) * $width_orig;
	}
	$thumb = imagecreatetruecolor($width , $height);  
	$bgcolor = imagecolorallocate($thumb, $red, $green, $blue);  
	ImageFilledRectangle($thumb, 0, 0, $width, $height, $bgcolor);
	imagealphablending($thumb, true);

	imagecopyresampled($thumb, $image, 0, 0, 0, 0,
	$width, $height, $width_orig, $height_orig);
	$thumb2 = imagecreatetruecolor($imagethumbsize_w , $imagethumbsize_h);
	// true color for best quality
	$bgcolor = imagecolorallocate($thumb2, $red, $green, $blue);  
	
	$im = imagecreatetruecolor($imagethumbsize_w, $imagethumbsize_h);
	$white = imagecolorallocate($im, 255, 255, 255);
	ImageFilledRectangle($thumb2, 0, 0,
	$imagethumbsize_w , $imagethumbsize_h , $white);
	
	//$imagethumbsize_w , $imagethumbsize_h , $white);
	imagealphablending($thumb2, true);

	$w1 =($width/2) - ($imagethumbsize_w/2);
	$h1 = ($height/2) - ($imagethumbsize_h/2);
	imagecopyresampled($thumb2, $thumb, 0,0, $w1, $h1,
	$imagethumbsize_w , $imagethumbsize_h ,$imagethumbsize_w, $imagethumbsize_h);

	// Output
	//header('Content-type: image/gif');
	//imagegif($thumb); //output to browser first image when testing

	if ($fileout !="") imagejpeg($thumb2, $fileout, $quality); //write to file

}

function deleteDirectory($dirname,$only_empty=false) {
   if (!is_dir($dirname))
       return false;
   $dscan = array(realpath($dirname));
   $darr = array();
   while (!empty($dscan)) {
       $dcur = array_pop($dscan);
       $darr[] = $dcur;
       if ($d=opendir($dcur)) {
           while ($f=readdir($d)) {
               if ($f=='.' || $f=='..')
                   continue;
               $f=$dcur.'/'.$f;
               if (is_dir($f))
                   $dscan[] = $f;
               else
                   unlink($f);
           }
           closedir($d);
       }
   }
   $i_until = ($only_empty)? 1 : 0;
   for ($i=count($darr)-1; $i>=$i_until; $i--) {
       //echo "\nDeleting '".$darr[$i]."' ... ";
       if (!rmdir($darr[$i]))
           echo "FAIL to remove dir ".$darr[$i];
   }
   return (($only_empty)? (count(scandir)<=2) : (!is_dir($dirname)));
}


function draw_pages($pagesize, $totalitems, $pagename, $file_name, $params='',$class=''){
	if(!empty($params)) $params = '&'.$params;
	$page = get_param($pagename);
	if (empty($page)) $page = 0;
	if($pagesize){

		if($totalitems){
			$totalpages = (int)(($totalitems-1)/$pagesize);
			if($totalpages > 0){
				echo '<b>Страницы:</b> ';
				
				$beg = $page - 9;
				if($beg < 0) $beg=0;
				$end = $page + 9;
				if($end > $totalpages) $end = $totalpages;
				if($page > 0){
					echo ' <span><a href="'.$file_name.'?';
					echo $pagename.'='.($page-1).$params.'" alt="Предыдущая " title="Предыдущая "><<</a></span> ';
				}
				for( $i = $beg ; $i <= $end ; $i++){
					if($i == $page ){
						echo '<span class="'.$class.'">'.($i+1).'</span>';
					}else{
						echo ' <span><a href="'.$file_name.'?';
						echo $pagename.'='.($i).$params.'">'.($i+1).'</a></span> ';
					}
				}	
				if($page < $totalpages){
					echo ' <span><a href="'.$file_name.'?';
					echo $pagename.'='.($page+1).$params.'" alt="Следующая" title="Следующая">>></a></span> ';
				}			
				echo "<br>\n";
			}
		}
	}
}

function draw_pages_adm($pagesize, $totalitems, $pagename, $file_name, $params='',$class=''){
	if(!empty($params)) $params = '&'.$params;
	$page = get_param($pagename);
	if (empty($page)) $page = 0;
	if($pagesize){

		if($totalitems){
			$totalpages = (int)(($totalitems-1)/$pagesize);
			if($totalpages > 0){
				echo '<ul class="pagination pagination-sm no-margin pull-right">';
				
				$beg = $page - 9;
				if($beg < 0) $beg=0;
				$end = $page + 9;
				if($end > $totalpages) $end = $totalpages;
				if($page > 0){
					echo ' <li><a href="'.$file_name.'?';
					echo $pagename.'='.($page-1).$params.'" alt="Предыдущая " title="Предыдущая ">«</a></li> ';
				}
				for( $i = $beg ; $i <= $end ; $i++){
					if($i == $page ){
						echo '<li ><a href="'.$file_name.'?';
						echo $pagename.'='.($i).$params.'">'.($i+1).'</a></li>';
					}else{
						echo '<li ><a href="'.$file_name.'?';
						echo $pagename.'='.($i).$params.'">'.($i+1).'</a></li>';
					}
				}	
				if($page < $totalpages){
					echo '<li ><a href="'.$file_name.'?';
					echo $pagename.'='.($page+1).$params.'" alt="Следующая" title="Следующая">»</a></li>';
				}			
				echo "</ul>";
			}
		}
	}
}

function send_email($mail ,$message,$subject=''){
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	mail($mail, $subject, $message, $headers);

}


function is_valid_email($email)
{
	if (strlen($email) > 50)
		return false;

	return preg_match('/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email);
}

function make_dir($path){
	$last = substr($path, -1);
	if($last == '/' || $last == '\\'){
		$path = substr($path, 0, -1);	
	}
	if(!is_dir($path)){ 
		mkdir($path);
		chmod($path, DIR_RIGHTS);	
	}
}



function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

function translit($text){ 
    $patterns = array("/а/", "/б/", "/в/", "/г/", "/д/", "/е/", "/ж/", "/з/", "/и/", "/й/", "/к/", "/л/", "/м/", "/н/", "/о/", "/п/", "/р/", "/с/", "/т/", "/у/", "/ф/", "/х/", "/ц/", "/ч/", "/ш/", "/щ/", "/ъ/", "/ы/", "/ь/", "/э/", "/ю/", "/я/", "/ё/", "/ /","/,/");
    $patterns_big = array("/А/", "/Б/", "/В/", "/Г/", "/Д/", "/Е/", "/Ж/", "/З/", "/И/", "/Й/", "/К/", "/Л/", "/М/", "/Н/", "/О/", "/П/", "/Р/", "/С/", "/Т/", "/У/", "/Ф/", "/Х/", "/Ц/", "/Ч/", "/Ш/", "/Щ/", "/Ъ/", "/Ы/", "/Ь/", "/Э/", "/Ю/", "/Я/", "/Ё/", "/ /","/,/");
    $replacements = array("a", "b", "v", "g", "d", "e", "zh", "z", "i", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "sh", "", "y", "", "e", "iu", "ia", "e", "-","");
    $text=preg_replace($patterns, $replacements, $text);
    $text=preg_replace($patterns_big, $replacements, $text);
    return $text;
}
function CleanFileName( $Raw ){ 
    $Raw = trim($Raw); 
    $RemoveChars  = array( "([\40])" , "([^a-zA-Z0-9_])", "(-{2,})" ); 
    $ReplaceWith = array("-", "-", "-"); 
    return preg_replace($RemoveChars, $ReplaceWith, $Raw); 
} 
/*
//не доделано
function undo_translit($text){ 
	
	  $replacements = array("/a/", "/b/", "/v/", "/g/", "/d/", "/e/", "/zh/", "/z/", "/i/", "/i/", "/k/", "/l/", "/m/", "/n/", "/o/", "/p/", "/r/", "/s/", "/t/", "/u/", "/f/", "/h/", "/c/", "/ch/", "/sh/", "/sh/", "/y/", "/e/", "/iu/", "/ia/","/w/");
	
	$replacements_big = array("/A/", "/B/", "/V/", "/G/", "/D/", "/E/", "/ZH/", "/Z/", "/I/", "/I/", "/K/", "/L/", "/M/", "/N/", "/O/", "/P/", "/R/", "/S/", "/T/", "/U/", "/F/", "/H/", "/C/", "/CH/", "/SH/", "/SH/", "/Y/", "/E/", "/IU/", "/IA/","/W/");
	
    $patterns = array("а", "б", "в", "г", "д", "е", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ",  "ы",  "э", "ю", "я", "в");
    $patterns_big = array("А", "Б", "В", "Г", "Д", "Е", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ",  "Ы",  "Э", "Ю", "Я", "В");
	
  

    $text = preg_replace($replacements,$patterns, $text);
    $text = preg_replace($replacements_big, $patterns_big, $text);
    return $text;
}*/




function upload_img($name,$id,$path,$sizes){
	if(is_file($_FILES[$name]['tmp_name'])){
		$ext = array(".gif", ".jpg", ".jpeg", ".png");
		$pict_ext = '';
		for($i=0;$i<sizeof($ext);$i++){
			if($data = explode($ext[$i], strtolower($_FILES[$name]['name']))){										
				if(count($data) == 2){
					$pict_ext = $ext[$i];
					break;
				}
			}
		}
		if(!empty($pict_ext)){
			$filename = $path.$id.$pict_ext;
			echo 'f='.$filename;
			copy($_FILES[$name]['tmp_name'], $filename);	
			list($w, $h) = getimagesize($filename);
			if($sizes){
				$pre_size = get_pict_sizes($sizes,$w,$h);
				$i = 1;
				foreach($pre_size as $ps){
					resize_then_crop($filename,$path.'pre'.$i.'_'.$id.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
//					chmod($path.'pre'.$i.'_'.$new_id.$pict_ext, FILE_RIGHTS);
					$i++;
				}
				resize_then_crop($filename,$path.'pre'.$id.$pict_ext,50,50,255,255,255); 
			}
			return $pict_ext;
		}													
	}
	return 0;
}

function get_pict_sizes($param,$w,$h){
	$picts = explode(';',$param);
	$i=0;
	foreach($picts as $row){
		if(!empty($row)){
			$one_pict = explode('x',$row);
			//  1    ,    2     
			if(empty($one_pict[1])){
				if($w>$h){
					$w2 = $one_pict[0];
					$h2 = round(($h/($w/$w2)), 0);
				}else{
					$h2 = $one_pict[0];
					$w2 = round(($w/($h/$h2)), 0);
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




function str_truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}           


function recalc_col_cat($cat_id){
	global $prefix;
	if(!empty($cat_id)){
		$q = new query();
		$where = " where date_end > NOW() and (TO_DAYS(NOW()) - TO_DAYS(date_add) < 30)  ";	
		$where .= " and A.cat_id=".to_sql($cat_id);
		$numb = $q->select1("select count(A.id) as number from ".$prefix."advs as A 
		join ".$prefix."city as T on A.id_city = T.id ".$where);
		
		$total_number = (int)$numb['number'];
		$q->exec("update ".$prefix."adv_catalog set col=".$total_number." where id=".to_sql($cat_id));
	}

}

//Џа®ўҐаЄ  ­  Є®ааҐЄв­®бвм  ¤аҐб  н«ҐЄва®­­®© Ї®звл
function check_email($email) {
    if (preg_match("%^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9_]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z])+$%", $email)) {
        return true;
    }
    return false;
}


function draw_banner($place){
	global $prefix, $root_path;
	$q = new query();
	/*$ban = $q->select1("select id,file,width,height,style from ".$prefix."banners where place=".to_sql($place)." and status=1 order by RAND();");
	*/
	$renew = get_param('renew');
	if($renew == 1)	$_SESSION['p'.$place]['max'] = '';
	
	if(empty($_SESSION['p'.$place]['max'])){
		$ban = $q->select("select id,file,width,height,style from ".$prefix."banners where place=".to_sql($place)." and status=1 order by RAND();");
		$f = 1;
		foreach($ban as $row){
			$_SESSION['p'.$place][$f] = $row['id'];
			$f++;	
		}
		$_SESSION['p'.$place]['max'] = $f-1;	
		$_SESSION['p'.$place]['cur'] = 0;	
	}
	
	$_SESSION['p'.$place]['cur']++;
	if($_SESSION['p'.$place]['cur'] > $_SESSION['p'.$place]['max']){
		$_SESSION['p'.$place]['cur']=1;
	}
	$ban = $q->select1("select id,file,width,height,style from ".$prefix."banners where place=".to_sql($place)." and status=1 and id=".to_sql($_SESSION['p'.$place][$_SESSION['p'.$place]['cur']]));
	

	if(!empty($ban)){		
			$file2 = $root_path.'files/banners/'.$ban['id'].$ban['file'];
			$file = '/files/banners/'.$ban['id'].$ban['file'];			
			if(is_file($file2)){			
				$q->exec("update ".$prefix."banners_stat set views=views+1 where ban_id=".to_sql($ban['id'])." and datas=CURDATE()");
				echo '<center>
				<script type="text/javascript">	
				var flashvars = {};
				var params = {wmode:"opaque"};			
				swfobject.embedSWF("'.$file.'", "banid'.$ban['id'].'", "'.$ban['width'].'", "'.$ban['height'].'", "9.0.0", "'.$file.'",flashvars, params);				
				</script>
				<div style="'.$ban['style'].'">
				<div id="banid'.$ban['id'].'" >
				</div></div></center>';
			}//if(is_file($file2))
			
			
			
	}
}
function PasswordCrypt( $password ){
	return base64_encode( $password );
}

// Purpose	decrypts customer ( and admin ) password field ( see ORDERS_TABLE in database_structure.xml )
function PasswordDeCrypt( $cifer ){
	return base64_decode( $cifer );
}

?>
<? endif; ?>