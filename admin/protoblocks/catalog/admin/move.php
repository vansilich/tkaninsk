<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");



	$good_id = get_param('good_id');
	$new_cat = get_param('new_cat');
	$cur_cat = get_param('cur_cat');
	$catalog = new c_catalog('Каталог',$prefix.'catalog' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);

	//if(!empty($new_cat)){
	if(isset($_POST['new_cat'])){
		$q->exec("update ".$prefix."goods set catalog=".to_sql($new_cat)." where id=".to_sql($good_id));
		$catalog->re_calc();
		header('location: index.php?cur_cat='.$cur_cat);	
	}



	function draw_list2($parent, $lev){
			global $catalog, $prefix, $cat_id;
			$q =  new query();
			$childs = $q->select("select id,name from ".$prefix."catalog where parent =".$parent." order by rank desc");
			foreach($childs as $row){
				//if($row['id'] == $cat_id) continue;
				echo '<option value="'.$row['id'].'">';
				for($i=0; $i<=$lev; $i++){
					echo ' &mdash;';
				}
				echo $row['name'];
				if($catalog->has_children($row['id'])){
					draw_list2($row['id'], $lev+1);
				}
			}
	}
	echo '
	<form method="post">
	<select SIZE="20"  name="new_cat" id="new_cat">';
	//echo '<option style="padding-left: 10px;" value="0">Корень</option>';
	draw_list2(0,0);
	echo '</select><br>';
	echo '<input type="submit" value="перенести">';
	echo '</form>';



?>
