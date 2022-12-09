<?

include("GetNewFile.php");
//$db = mysqli_connect("localhost","root","","china");
 /*
//Это для тестов, чтобы получить $products
$res = $db->query("SELECT id,title,min_count,product_weight,product_price_yu FROM product LIMIT 15");
$arr = array();
while ($row = $res->fetch_array())
{
	array_push($arr,$row);
} */
$arr = array();

$mas['id']=1;
$mas['articul']=22;
$mas['title']=33;
$mas['edizm']='пог.м.';
$mas['min_count']=55.4;
$mas['price']=66;
for($z = 1;$z<=16;$z++){$mas['articul']=$z;array_push($arr,$mas);}

$products = json_encode($arr);

//Переменные, которые надо получить через $_POST, тут они для примера
$num = "28832";
$date = date("d.m.Y",time());
$target = "Оплата интернета3333";
$buyer = "Иванов Пётр Сидорович4444";
$product_json = json_decode($products);

GetNewFile($num,$date,$target,$buyer,$product_json);
echo 'done';
?>