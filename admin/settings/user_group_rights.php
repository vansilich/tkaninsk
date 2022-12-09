<?
$inc_path = "../";
$_menu_type = 'settings';

include($inc_path."class/header_adm_pop.php");

$gid = get_param('gid');
if(!empty($gid)){





$action = get_param('action');
if($action == 'upd'){
	$blocks = $q->select("select * from ".$prefix."blocks as B
	left join ".$prefix."users_group_rights as R on R.block_id = B.id and R.group_id = ".to_sql($gid)."
	");
	
	
	foreach($blocks as $row){ 
	
		$show = get_param('show_'.$row['id'],0);
		$edit = get_param('edit_'.$row['id'],0);
		$del = get_param('del_'.$row['id'],0);
		$check = $q->select1("select * from ".$prefix."users_group_rights where block_id=".to_sql($row['id'])." and group_id=".to_sql($gid));
		if($check == 0){
			$q->insert("insert into ".$prefix."users_group_rights
			set 
			block_id=".to_sql($row['id']).",
			group_id=".to_sql($gid).",
			shows=".to_sql($show).",
			edits=".to_sql($edit).",
			dels=".to_sql($del)."
			");			
		}else{
			$q->insert("update ".$prefix."users_group_rights
			set 			
			shows=".to_sql($show).",
			edits=".to_sql($edit).",
			dels=".to_sql($del)."
			where block_id=".to_sql($row['id'])." and group_id=".to_sql($gid));
		}	 
	}
	
}



	$blocks = $q->select("select * from ".$prefix."blocks as B
	left join ".$prefix."users_group_rights as R on R.block_id = B.id and R.group_id = ".to_sql($gid)."
	where status=1
	");
	
		echo '
	<form method="post">
	<input type="hidden" name="action" value="upd">
	<table border="1"><tr><td>Модуль</td><td>Просмотр</td><td>Редактирование</td><td>Удаление</td></tr>';
	foreach($blocks as $row){
		echo '<tr><td>'.$row['name'].'</td>';
		
		echo '<td><input type="checkbox" name="show_'.$row['id'].'" value="1" ';
		if($row['shows'] == 1){
			echo ' checked';	
		}
		echo '></td>';
		
		echo '<td><input type="checkbox" name="edit_'.$row['id'].'" value="1" ';
		if($row['edits'] == 1){
			echo ' checked';	
		}
		echo '></td>';
		
		echo '<td><input type="checkbox" name="del_'.$row['id'].'" value="1" ';
		if($row['dels'] == 1){
			echo ' checked';	
		}
		echo '></td>';
		
		
		echo '</tr>';
		
	}
	echo '</table><br>
	<input type="submit" value="Сохранить">
	</form>
	';

}

include($inc_path."class/bottom_adm_pop.php");
?>
