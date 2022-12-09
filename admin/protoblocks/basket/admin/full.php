<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?
$good = get_param('good');

$ord = $q->select1("select * from ".$prefix."orders where id=".to_sql($good));
$goods = $q->select("select * from ".$prefix."goods where id in (".$ord['full_order'].")");
foreach($goods as $row){
$img = $link_img = '';
			if(is_file('../../../../files/catalog/pre'.$row['id'].$row['img'])){
				$img = '<img src="/files/catalog/pre2_'.$row['id'].$row['img'].'" alt="'.$row['title'].'" class="img" style="margin-right:10px;" border="0">';					
			}
//<a href="#" onclick="$(\'#g'.$row['id'].'\').hide();return false">скрыть этот товар</a><br>			
	echo '<div id="g'.$row['id'].'">
	
	<a href="/admin/protoblocks/2/admin/index.php?tmode=edit&gift_goods_id='.$row['id'].'&cur_cat=75&page=0" target="_blank">Изменить данный товар</a> | 
	<a href="/goods/'.$row['cpu'].'/" target="_blank">Посмотреть на сайте '.$row['articul']." - ".$row['title'].'</a><br>'.$img.'<br><br>
	<hr>
	</div>';

}

include($inc_path."class/bottom_adm.php");


?>