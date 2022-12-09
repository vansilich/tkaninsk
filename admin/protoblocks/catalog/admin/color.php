<?
	$inc_path = '../../../';
	include($inc_path."class/header_adm.php");
	
	
$word = get_param('word');	
echo '<form>
<input type="text" name="word" value="'.$word.'">
<input type="submit" value="найти">
</form>';	
	
	
	$table = new cTable('Цвета',$prefix.'color' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
//	$table->settings(' 1 order by name');
	$table->settings(" name like ".to_sql('%'.$word.'%')." order by name");
	
	$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('Цвет1 css #','code',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('Цвет2 css #','code2',1,1,$size='100',$max=''));
	
	//$table->insertcol(new c_image('Картинка 30x30','img',1,1,$inc_path.'../files/color/'));
/*	$table->insertcol(new c_textarea('Краткое описание','anons',0,1,60,5));
	$table->insertcol(new c_fck('Полное описание','text',0,1,$height='400'));
*/	

	
	
	

	$table->draw();
include($inc_path."class/bottom_adm.php");	
?>