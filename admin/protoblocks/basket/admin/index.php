<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
<?



$select = Array();
//$select[6]='Все';
$select[0]='На Согласовании';
$select[1]='К отгрузке';


$ostatus = get_param('ostatus',0);
echo '<div class="sub_menu">';
	if($ostatus == 6)
		echo '<a href="?ostatus=6"><b>Все</b></a> | ';
	else
		echo '<a href="?ostatus=6">Все</a> | ';


foreach($select as $k=>$v){
	if($k == $ostatus)
		echo '<a href="?ostatus='.$k.'"><b>'.$v.'</b></a> | ';
	else
		echo '<a href="?ostatus='.$k.'">'.$v.'</a> | ';

}
echo '</div>';


$table = new cTable('Заказы',$prefix.'orders' ,'id',$is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(10);
if($ostatus != 6 )$table->settings(' order_status='.to_sql($ostatus));
$table->order(' id desc');
$table->draw_params('ostatus='.$ostatus);
$table->insertcol(new c_data('Дата','date_add',1,0));

$table->insertcol(new c_text('Изменен','changed',1,0));
$table->insertcol(new c_select('Статус','order_status',1,1,$select));
$table->insertcol(new c_text('Оплачен','payed',1,0));
		
$table->insertcol(new c_text('ФИО','fio',1,1,$size='100',$max=''));
$table->insertcol(new c_textarea('Адрес доставки','adres',0,1,60,5));
$table->insertcol(new c_text('телефон','phone',1,1,$size='100',$max=''));
$table->insertcol(new c_text('email','email',0,1,$size='100',$max=''));
$table->insertcol(new c_textarea('комментарии','comm',0,1,60,5));
$table->insertcol(new c_textarea('Доставка','delivery',1,1,60,5));
    $table->insertcol(new c_textarea('Доставка стоимость','delivery_price',1,1,60,5));


$table->insertcol(new c_text('Купон','coupon',1,0,$size='100',$max=''));

$table->insertcol(new c_fck('заказ','order_text',1,0,$height='400'));
$table->insert_action('распечатать','print','no_menu=1&good');

$table->insert_action('Ссылка на оплату','pay','no_menu=1&good');

$table->draw();
include($inc_path."class/bottom_adm.php");
?>