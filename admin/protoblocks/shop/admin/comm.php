<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?

$table = new cTable('Отзывы',$prefix.'goods_comm' ,'id',$is_rank=0, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->order(' id desc');

$table->insertcol(new c_text('Имя','name',1,1,$size='100',$max=''));
$table->insertcol(new c_text('id товара','gid',1,1,$size='100',$max=''));
$table->insertcol(new c_text('Email','email',1,1,$size='100',$max=''));
$table->insertcol(new c_textarea('Отзыв','text',1,1,60,5));


//$table->insert_action2('Товар', '/site/catalog/goods.php', 'id','gid');   

$table->draw();

?>