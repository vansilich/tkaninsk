<?
	$inc_path = '../../../';
	include($inc_path."class/header_adm.php");
	
	
$word = get_param('word');	
echo '<form>
<input type="text" name="word" value="'.$word.'">
<input type="submit" value="�����">
</form>';	


function make_date($id){
	global $prefix;
	$q = new query();
	$q->exec("update ".$prefix."maker set added=NOW() where id = ".to_sql($id));
}	
	
	$table = new cTable('����� �������������',$prefix.'maker' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
	$table->set_page_size(20);
//	$table->settings(' 1 order by name');
	$table->settings(" name like ".to_sql('%'.$word.'%')." order by name");
	$table->insertcol(new c_image('��������','img',1,1,$inc_path.'../files/maker/', '125;500'));
	$table->insertcol(new c_image('�������� �� ������� �.�','img2',1,1,$inc_path.'../files/smaker/', '125;500'));
	$table->insertcol(new c_image('�������� �� �������','img3',1,1,$inc_path.'../files/cmaker/', '125;500'));
	$table->insertcol(new c_textarea('������� ��������','anons',0,1,60,5));
	$table->insertcol(new c_fck('������ ��������','text',0,1,$height='400'));
	
	$table->insertcol(new c_textarea('������� ��������(http://tochkatepla.ru)','anons_tochka',0,1,60,5));
	$table->insertcol(new c_fck('������ ��������(http://tochkatepla.ru)','text_tochka',0,1,$height='400'));	
	
	
	$table->insertcol(new c_text('��������','name',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('������ � %','sales',1,1,$size='100',$max=''));
        $table->insertcol(new c_textarea('TITLE','k_title',0,1,60,5));
        $table->insertcol(new c_textarea('DESCRIPTION','k_description',0,1,60,5));
        $table->insertcol(new c_textarea('KEYWORDS','k_keywords',0,1,60,5));
        $table->insertcol(new c_text('HEADER','k_header',0,1,$size='100',$max=''));
	
	$table->insert_action('����� ��� tochkatepla.ru', 'maker_files', 'gid');
	$table->insert_after_add('make_date({id});');
	
	$table->draw();
	
?>