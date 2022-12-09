<?
$inc_path = '../../../';
include($inc_path."class/header.php");

header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=users.xls");
header("Content-Description: PHP Generated XLS Data");

$users = $q->select("select * from ".$prefix."members order by col_orders desc");
foreach($users as $row){
	$order = $q->select1("select count(*) as col from ".$prefix."orders where user_id=".to_sql($row['id']));	
	$q->exec("update ".$prefix."members set col_orders=".to_sql($order['col'])." where id=".to_sql($row['id']));	
}


$users = $q->select("select * from ".$prefix."members order by col_orders desc");

echo '<table border="1">';
echo '<tr>
	<td><b>email</b></td>
	<td><b>Имя</b></td>
	<td><b>Фамилия</b></td>
	<td><b>Количество заказов</b></td>
	</tr>';	
foreach($users as $row){
	echo '<tr>
	<td>'.$row['mail'].'</td>
	<td>'.$row['name'].'</td>
	<td>'.$row['surname'].'</td>
	<td>'.$row['col_orders'].'</td>
	</tr>';	
}
echo '</table>';




?>