<? 
header('Content-Type: text/html; charset=utf-8');
$inc_path = "../admin/";	$root_path = "../";
include($inc_path."class/header.php");
$gid = get_param('gid');
$col = get_param('col',1);//кол-во товара
$type = get_param('type','good');
$basket_id = get_param('basket_id',0);
$type_id = get_param('type_id',0);

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
	
include($inc_path.'protoblocks/basket/send.php');	
?>