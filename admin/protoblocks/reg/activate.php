<?
$c = get_param('c');
if(empty($c)){
	echo 'Не указан код активации';
}else{
	$check = $q->select1("select * from ".$prefix."members where code=".to_sql($c));
	if(empty($check)){
		echo 'Указан неправильный код активации';
	}else{
		$q->exec("update ".$prefix."members set active=1 where code=".to_sql($c));
		echo 'Ваш аккаунт активирован.';
	}
}
?>