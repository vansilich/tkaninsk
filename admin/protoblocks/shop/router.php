<?
/*
Create by Sunny (e-boxes@list.ru, 288-681-633)
������� ������� (������� ��� ������� ����������� �����)
*/

// ���������� �������
include_once("../../admin/class/config.php");
include_once("../../admin/class/dbconn.php");

// ���������� �����������
if(isset($_GET['command']) && $_GET['command'] == "router_convert"){
	// �������� ������
	mysql_query("UPDATE `".$prefix."catalog` SET `alias` = NULL;", $db) or die(mysql_error());
	
	// �������� ������ �������
	$result = mysql_query("SELECT `id`,`name` FROM `".$prefix."catalog` ORDER BY `id`;", $db) or die(mysql_error());
	while($row = mysql_fetch_assoc($result)){
		// �������� ���������
		$name = rutranslit($row['name']);
		
		// ��������� ������������� ���������������� ��������
		$result2 = mysql_query("SELECT `id` FROM `".$prefix."catalog` WHERE `alias` = '".mysql_escape_string($name)."' LIMIT 1;", $db) or die(mysql_error());
		if(mysql_num_rows($result2) > 0){
			// ����������, ��������� �������
			$name .= "-".mt_rand(10, 90);
		}
		
		// ��������� � �������
		mysql_query("UPDATE `".$prefix."catalog` SET `alias` = '".mysql_escape_string($name)."' WHERE `id` = '".intval($row['id'])."' LIMIT 1;", $db) or die(mysql_error());
		printf("<pre>%s</pre>\n", $name);
	}
	exit;
}

// ��������� ������������� ����������
if(!isset($_GET['route'])){
	// ��� ����������, ������ ������ �� �������
	redirect("/");
}

// ��������� ������������� ���������������� ��������
$result = mysql_query("SELECT `id` FROM `".$prefix."catalog` WHERE `alias` = '".mysql_escape_string($_GET['route'])."' LIMIT 1;") or die(mysql_error());
if(mysql_num_rows($result) > 0){
	// �������� �������������
	$_GET['cat_id'] = mysql_result($result, 0, 0);
	include_once("index.php");
	exit;
}else{
	// �����-�� ��������
	redirect("/");
}

exit;
?>