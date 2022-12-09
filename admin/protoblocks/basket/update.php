<?
if($action == 'clear_basket' ){
	$_SESSION['basket_catalog'] = array();
}

if($action == 'del'){
	unset($_SESSION['basket_catalog'][$cart_id]);
	$cart_b = $_SESSION['basket_catalog'];
	$i=1;
}

if($action == 'update'){
	$gq = get_param('gq');
	$cart_b = $_SESSION['basket_catalog'];
	if(sizeof($_SESSION['basket_catalog']) > 0){
		foreach($cart_b as $k=>$v){
			foreach($gq as $k2=>$v2){
				if($k2 == $k){	$cart_b[$k]['q'] = $v2; if($v2 == 0) unset($cart_b[$k]); }
			}
		}
		$_SESSION['basket_catalog'] = $cart_b;
	}
}
if($action == 'del' || $action == 'update' || $action == 'clear_basket'){
		echo '<script>document.addEventListener(\'DOMContentLoaded\', function () {refreshbasket();});</script>';
}
?>