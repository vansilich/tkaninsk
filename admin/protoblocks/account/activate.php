<?
$c = get_param('c');
if(empty($c)){
	echo '�� ������ ��� ���������';
}else{
	$check = $q->select1("select * from ".$prefix."members where code=".to_sql($c));
	if(empty($check)){
		echo '������ ������������ ��� ���������';
	}else{
		$q->exec("update ".$prefix."members set active=1 where code=".to_sql($c));
		$temp = $q->select1("select * from ".$prefix."tmpl where id=3");
		echo '<div class="msg">'.$temp['text'].'</div>';	
	}
}
?>