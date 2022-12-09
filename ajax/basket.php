<? 
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../admin/";	$root_path = "../";
include($inc_path."class/header.php");
$q = new query();
$gid = get_param('gid');
$basket_id = get_param('basket_id',0);
$type_id = get_param('type_id',0);
$type = get_param('type','good');//color,size,
$col = get_param('col',1);//кол-во товара
$props = get_param('props');
$action = get_param('action');
$upd = 1;
if($action == 'add_to_basket' && !empty($gid)){
	$n_basket = sizeof($_SESSION['basket_catalog']);
	$ncol = 0;
	$cart_b = $_SESSION['basket_catalog'];
	$isnew=1;
	if(is_array($cart_b)){
		foreach($cart_b as $k=>$v){
			if($v['good']==$gid && $v['type']==$type && $v['type_id']==$type_id ){
				$cart_b[$k]['q']=$col;
				$cart_b[$k]['props']=$props;
				if($col == 0)unset($cart_b[$k]);
				$isnew=0;
			}
		}
	}else{
		$cart_b = array();	
	}
	if($isnew==1){
		$cart_row['good'] = $gid;
		$cart_row['type'] = $type;
		$cart_row['type_id'] = $type_id;
		$cart_row['props'] = $props;
		$cart_row['q']=$col;
		$cart_b[]=$cart_row;
	}
	$_SESSION['basket_catalog']=$cart_b;
}
if($action == 'del_basket' && !empty($gid) && empty($basket_id)){
	$cart_b = $_SESSION['basket_catalog'];
	$i=1;
	if(sizeof($_SESSION['basket_catalog']) > 0){
		foreach($cart_b as $k=>$v){
			if($v['good']==$gid && $v['type']==$type && $v['type_id']==$type_id ){
				unset($cart_b[$k]);	
			}
		}
		$_SESSION['basket_catalog'] = $cart_b;
	}
}
if($action == 'del_basket' && empty($gid) && !empty($basket_id)){
	$cart_b = $_SESSION['basket_catalog'];
	unset($cart_b[$basket_id]);	
	$_SESSION['basket_catalog'] = $cart_b;
}
if($action == 'clear_basket' ){
	$_SESSION['basket_catalog'] = array();
}

if($action == 'update_basket' ){
	$cart_b = $_SESSION['basket_catalog'];
	$upd = 0;
	$col = get_param('col');
	if(sizeof($_SESSION['basket_catalog']) > 0){
		foreach($cart_b as $k=>$v){
			if($v['good']==$gid && $v['type']==$type && $v['type_id']==$type_id ){
				$cart_b[$k]['q'] = $col;
				$cart_b[$k]['props'] = $v['props'];
				if($col == 0)unset($cart_b[$k]);
			}
		}
		$_SESSION['basket_catalog'] = $cart_b;
	}
}

?>
<div>
<div id="basket">
<?
$n_basket = 0;
$sum = 0;
$col = 0;
if(isset($_SESSION['basket_catalog']))	$n_basket = sizeof($_SESSION['basket_catalog']);
if($n_basket>0){
	$cart_b = $_SESSION['basket_catalog'];
	$z = 0;
	$sum=0;
	$col=0;
	$big_basket = '';
	foreach($cart_b as $k=>$v){
		$good = $q->select1("select * from ".$prefix."goods as G
		where G.id=".to_sql($v['good']));
		$price = $good['price'];
		
		$img = get_image_cpu($good,'files/goods/1/','img1',1);
		if(!empty($img)){
			$img =  '<img src="'.$img.'" border="0" alt="'.my_htmlspecialchars($good['name']).'">';
		}
		
		if($v['type']=='color'){
			 $color = $q->select1("select * from ".$prefix."goods_price where id=".to_sql($v['type_id']));
			 $img = get_image_folder($color,'files/color/1/','img1',1);
			if(!empty($img)){
				$img =  '<img src="'.$img.'" border="0" alt="'.my_htmlspecialchars($color['name']).'">';
			}
		}

		$sum += $v['q']*$price;
		$col += $v['q'];


		$big_basket .= '<tr valign="middle"><td>'.$img.'</td><td>'.$good['title'].'</td><td>&times; '.$v['q'].'</td><td class="name">'.$price.'</td></tr>';

	}
	$big_basket .= '</table>';

	$sum = number_format($sum, 0, '', ' ');
	$_SESSION['big_basket'] = $big_basket;




}
?>
</div>
<div id="big"><? echo $_SESSION['big_basket'];?></div>
<div id="basket"><? echo $_SESSION['small_basket'];?></div>
<div id="sum"><? echo $sum ?></div>
<div id="col"><? echo $col; ?></div>
<div id="basket_col"><? echo sizeof($_SESSION['basket_catalog']); ?></div>
<div id="action"><? echo $action; ?></div>
<div id="upd"><? echo $upd; ?></div>
</div>