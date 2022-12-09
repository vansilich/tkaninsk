<div class="order"><?
    $done = get_param('done');
    $action = get_param('action');
    $gid = get_param('gid');
    $cart_id = get_param('cart_id');
    $coupon = get_param('coupon');
    if (isset($_POST['coupon'])) {
        $_SESSION['basket_coupon'] = $coupon;
    }

    include($inc_path . 'protoblocks/basket/update.php');


    $done = get_param('done');
    $refresh = get_param('refresh');
    if ($refresh == 'true') {
        echo '<script>document.addEventListener(\'DOMContentLoaded\', function () {refreshbasket();});</script>';

    }
    if ($done == 'yes') {
        $oid = get_param('oid');
        $send = 1;
        echo '<div class="complete"><span>
	
	<h1>Спасибо за то, что выбрали наш интернет-магазин.</h1>
	Ваш заказ <b>№' . $oid . '</b> поступил в отдел продаж.<br>
   
	
	

	</div>';
        /*
        Ваш заказ<b> №'.$oid.'</b> поступил в отдел продаж.<br>
    В самое ближайшее время, с вами свяжется наш менеджер , для подтверждения вашего заказа . </span><br>
    <p>Вы можете посмотреть наш <a href="/catalog/">каталог товаров</a>, пока ожидаете обработки Вашего заказа.
    </p>
        */


        $ord = $q->select1("select * from " . $prefix . "orders where id=" . to_sql($_SESSION['old_basket_id']));
        $good = $ord['id'];
        if ($ord['pay_type'] == 'card') {
            /*echo '<div style="color:red; font-size:16px;">На сайте ведутся технические работы, оплатить "Картой Visa, Mastercard, Maestro", пока не возможно.</div>';	*/
            echo '<div>Информация об отправке будет направлена на Вашу электронную почту в течение двух рабочих дней.</div>';

            if (!empty($ord)) {
                ?>

                <div class="page_in" id="action pay">
                    <form method="post" action="/pay/?good=<?= $ord['id']; ?>&p=<?= $ord['phone']; ?>"
                          id="form_pay">
                        <input type="hidden" name="cmd" value="pay">
                        <input type="hidden" name="good" value="<?= $ord['id']; ?>">
                        <?


                        echo '
		<h2>Оплатить заказ</h2>
		<h3>Ваши данные</h3>
		<div class="table-responsive">
		<table class="table table-striped table-condensed">';
                        echo '<tr><th>ФИО:</th><td>' . $ord['fio'] . '</td></tr>';
                        echo '<tr><th>Адрес доставки:</th><td>' . $ord['adres'] . '</td></tr>';
                        echo '<tr><th>Телефон:</th><td>' . $ord['phone'] . '</td></tr>';
                        echo '<tr><th>Email:</th><td>' . $ord['email'] . '</td></tr>';
                        echo '<tr><th>Комментарии:</th><td>' . $ord['comm'] . '</td></tr>';
                        echo '</table></div>';
                        echo '<h3>Заказ</h3><br>
		<div class="table-responsive">
		' . str_replace('<table', '<table class="table table-striped"', $ord['order_text']);
                        echo '</div>';
                        echo 'Доставка: <b>' . $ord['delivery_price'] . '</b> руб.';

                        $full = $q->select("select * from " . $prefix . "orders_full where order_id=" . to_sql($good));
                        $sum = 0;
                        foreach ($full as $v) {
                            $good = $q->select1("select * from " . $prefix . "goods as G
				where G.id=" . to_sql($v['good_id']));
                            /*
                                            if($v['col'] >=$col_opt && $good['price_opt']>0){
                                                $price = $good['price_opt'];
                                            }else{
                                                $price = $good['price'];
                                            }*/
                            $price = $v['price'];
                            $sum += $v['col'] * $price;
                        }
                        //echo 'Сумма:'.$sum;

                        $sum += $ord['delivery_price'];
                        echo '<div style="font-size:20px; padding:20px;">Итоговая сумма: <b>' . $sum . '</b> рублей</div>';
                        ?>


                        <div class="clearfix button_pay">
                            <a href="javascript:" onClick="$('#form_pay').submit();return false;" class="btn left"
                               style="width:135px;">Оплатить</a> <img src="/img/sberbank.jpg" alt="" class="left"/>
                        </div>
                    </form>

                </div>
                <?
            }//if(!empty($ord)){
        } else {//if($ord['pay_type'] == 'card'){
            echo '<div>Ваш заказ будет направлен менеджеру. В ближайшее время на электронную почту менеджер отправит счет, по которому Вы сможете призвести оплату. Просьба, для сокращения времени выполнения заказа выслать чек ответным письмом с указанием номера Вашего заказа.</div>';
        }


        echo '<script>document.addEventListener(\'DOMContentLoaded\', function () {refreshbasket();});</script>';
    } else {//if($done == 'yes'){

        $n_basket = sizeof($_SESSION['basket_catalog']);
        if ($n_basket > 0) {

            /**********send**********/
            $send = 0;
            $cmd = get_param('cmd');


            if ($cmd == 'send') {
                include($inc_path . 'protoblocks/basket/send.php');
            }
            /**********end send**********/

            if ($send != 1) {
                ?>
                <div id="table">

                    <h2>ваш заказ</h2>

                    <form method="post" id="cbform" name="cbform">
                        <input type="hidden" name="action" value="update">
                        <?
                        $cart_b = $_SESSION['basket_catalog'];

                        if (!empty($_SESSION['basket_coupon'])) {
                            $sum_before = 0;
                            foreach ($cart_b as $k => $v) {
                                $good = $q->select1("select G.*,G.edizm from " . $prefix . "goods as G
					join " . $prefix . "catalog as C on C.id=G.catalog
					where G.id=" . to_sql($v['good']));
                                if ($v['q'] >= 5 && $good['price_opt'] > 0) {
                                    $price = $good['price_opt'];
                                } else {
                                    $price = $good['price'];
                                }
                                $sum_before += $v['q'] * $price;


                            }
                            $check = $q->select1("select * from " . $prefix . "kupon where status=1 and date_end>NOW() and code= " . to_sql($_SESSION['basket_coupon']));

                            if (!empty($check)) {
                                if ($sum_before < $check['min_sum']) {
                                    $coupon_msg = '<div class="err_msg">Сумма заказа должна быть больше ' . $check['min_sum'] . '</div>';
                                } else {
                                    if ($check['sale_proc'] > 0) {
                                        $coupon_sale_proc = $check['sale_proc'];
                                        $coupon_msg = '<div class="good_msg">Скидка ' . $check['sale_proc'] . '%</div>';
                                    } elseif ($check['sale_sum'] > 0) {
                                        $coupon_sale_sum = $check['sale_proc'];
                                        $coupon_msg = '<div class="good_msg">Скидка ' . $check['sale_sum'] . 'руб </div>';
                                    }
                                }
                            } else {
                                $coupon_msg = '<div class="err_msg">Не правильный код или закончилась акция</div>';
                            }
                        }


                        $sum = 0;
                        $col = 0;
                        $ves = 0;
                        $tmpl = file_get_contents($inc_path . 'parts/basket.php');
                        preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl, $tmp_begin);
                        preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl, $tmp_end);
                        preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl, $tmp_row);
                        preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl, $tmp_delim);


                        echo $tmp_begin[1];
                        $test = get_param('test');

                        $f = 1;
                        foreach ($cart_b as $k => $v) {
                            $good = $q->select1("select G.*,G.edizm from " . $prefix . "goods as G
				join " . $prefix . "catalog as C on C.id=G.catalog
				where G.id=" . to_sql($v['good']));

                            $img = get_image_cpu($good, 'files/goods/1/', 'img1', 1);
                            if (!empty($img)) {
                                $img = '<img src="' . $img . '" border="0" alt="' . my_htmlspecialchars($good['title']) . '" class="img">';
                            }
                            $link = '/goods/' . $good['cpu'] . '/';
                            if ($v['type'] == 'color' && $v['type_id'] > 0) {
                                /*$color = $q->select1("select G.*,C.code,C.name as cname from ".$prefix."goods_price as G
                                left join ".$prefix."color as C on C.id = G.color
                                where G.id=".to_sql($v['type_id']));*/
                                $color = $q->select1("select G.*,C.name as cname from " . $prefix . "goods_price as G 
					left join " . $prefix . "adv_params_value as C on C.id = G.color
					where G.id=" . to_sql($v['type_id']));

                                $good['articul'] = $color['articul'];
                                $good['kol'] = $color['kol'];
                                if (!empty($color['cname'])) $good['name'] = $good['name'] . ' [' . $color['cname'] . ']';
                                $img = get_image_folder($color, 'files/color/1/', 'img1', 1);
                                if (!empty($img)) {
                                    $img = '<img src="' . $img . '" border="0" alt="' . my_htmlspecialchars($color['name']) . '" class="img">';
                                }
                                $link = '/goods/' . $good['cpu'] . '/?color=' . $color['id'];
                            } else {
                                //$main_color = $q->select1("select C.code,C.name as cname from ".$prefix."color as C where id=".to_sql($good['color']));
                                $main_color = $q->select1("select C.name as cname from " . $prefix . "adv_params_value as C where id=" . to_sql($good['color']));
                                if (!empty($main_color['cname'])) $good['name'] = $good['name'] . ' [' . $main_color['cname'] . ']';
                            }


                            $price_tit = '';
                            if ($v['q'] >= 5 && $good['price_opt'] > 0) {
                                $price = $good['price_opt'];
                                $price_tit = 'опт. ';
                            } else {
                                $price = $good['price'];
                            }
                            if ($coupon_sale_proc > 0 && $good['price_old'] == 0) {
                                $price = (int)round($price * ((100 - $coupon_sale_proc) / 100), 0);
                            }

                            $sum += $v['q'] * $price;
                            $ves += $v['q'] * $good['ves'];
                            $col += $v['q'];

                            $news_row = $tmp_row[1];

                            $news_row = str_replace('{NEWS_MAXKOL}', $good['kol'], $news_row);
                            if ($f == 1) {
                                $news_row = str_replace('{ROW_CLASS}', 'td1', $news_row);
                                $f = 2;
                            } else {
                                $news_row = str_replace('{ROW_CLASS}', 'td2', $news_row);
                                $f = 1;
                            }


                            $news_row = str_replace('{GOOD_IMG}', $img, $news_row);
                            if ($test == 1) {
                                $news_row = str_replace('{GOOD_TITLE}', $good['name'] . ' вес:' . $good['ves'], $news_row);
                            } else {
                                $news_row = str_replace('{GOOD_TITLE}', $good['name'], $news_row);
                            }
                            $news_row = str_replace('{articul}', $good['articul'], $news_row);
                            $news_row = str_replace('{edizm}', $good['edizm'], $news_row);

                            if ($good['edizm'] == 'шт') {
                                $news_row = str_replace('{b_type_col}', '_cel', $news_row);
                            } else {
                                $news_row = str_replace('{b_type_col}', '', $news_row);
                            }

                            $news_row = str_replace('{GOOD_TITLE2}', $good['title2'], $news_row);
                            $news_row = str_replace('{GOOD_PRICE}', $price_tit . $price, $news_row);
                            $news_row = str_replace('{GOOD_COL}', $v['q'], $news_row);
                            $news_row = str_replace('{VAR}', $v['var'], $news_row);
                            $news_row = str_replace('{GOOD_PRICE_ALL}', $price * $v['q'], $news_row);
                            $news_row = str_replace('{GOOD_ID}', $v['good'], $news_row);
                            $news_row = str_replace('{CART_ID}', $k, $news_row);
                            $news_row = str_replace('{GOOD_LINK}', $link, $news_row);
                            echo $news_row;
                        }

                        $end = $tmp_end[1];
                        if ($f == 1) {
                            $end = str_replace('{ROW_CLASS}', 'td1', $end);
                        } else {
                            $end = str_replace('{ROW_CLASS}', 'td2', $end);
                        }


                        $end = str_replace('{coupon_msg}', $coupon_msg, $end);
                        $end = str_replace('{coupon}', $_SESSION['basket_coupon'], $end);


                        $end = str_replace('{GOOD_PRICE_SUM}', $sum, $end);
                        $end = str_replace('{GOOD_COL}', $col, $end);
                        echo $end;

                        $all_sum = $sum;


                        ?>
                    </form>


                    <div class="row">


                        <a name="oform"></a>
                        <form action="/basket/#oform" name="send_form" id="send_form" method="post"
                              onSubmit="return check_basket();" style="margin:0;">
                            <input type="hidden" name="cmd" value="send">
                            <input type="hidden" id="basket_ves" name="ves" value="<?= $ves; ?>">
                            <input type="hidden" id="basket_sum" name="sum" value="<?= $sum; ?>">
                            <div class="col-md-3">
                                <div class="h3">оформить заказ</div>
                                <ul class="ul ul2">
                                    <li><label><input type="radio" class="" name="ftype" value="1" checked> Физ.
                                            лицо</label></li>
                                    <li><label><input type="radio" class="" name="ftype" value="2"> Юр. лицо</label>
                                    </li>
                                </ul>
                                <input placeholder="Фамилия" id="b_fio" name="fio" class="input"
                                       value="<? echo get_user_info('surname'); ?>"/>
                                <input placeholder="Имя" id="b_name" name="name" class="input"
                                       value="<? echo get_user_info('name'); ?>"/>
                                <input placeholder="Отчество" id="b_otch" name="otch" class="input"
                                       value="<? echo get_user_info('otch'); ?>"/>
                                <input placeholder="Телефон" name="phone" id="basket_phone"
                                       value="<? echo get_user_info('phone'); ?>" class="input" required/>
                                <input placeholder="Email" name="email" id="basket_email"
                                       value="<? echo get_user_info('order_email'); ?>" class="input" required/>
                                <input placeholder="Почтовый индекс" name="postindex"
                                       value="<? echo get_user_info('postindex'); ?>" id="postindex" class="input"/>

                                <input placeholder="Город" id="b_city" name="city" class="input"
                                       value="<? echo get_user_info('city'); ?>"/>

                                <textarea class="textarea" id="b_adres" name="adres"
                                          placeholder="Адрес доставки[улица,дом,кв]"><? echo get_user_info('adres'); ?></textarea>
                                <textarea class="textarea" name="comm" placeholder="Комментарий"></textarea>

                            </div>

                            <div class="col-md-3 top">
                                <div class="name">Способ доставки:</div>
                                <ul class="ul ul2">
                                    <li><label><input type="radio" class="delivery_check" name="delivery"
                                                      value="sam" id="delivery_rc" checked> РЦР по
                                            Новосибирску</label>
                                        <div class="dop_adres_tk" id="select_rc">
                                            <select class="input" name="select_rc" id="select_rc_value">
                                                <option value="">Выберите РЦР</option>
                                                <optgroup label="Новосибирск">
                                                    <option>Р Нива
                                                    </option>
                                                    <option>
                                                        РЦР Новосибирск Главный
                                                    </option>
                                                    <option>
                                                        РЦР Заельцовский
                                                    </option>
                                                    <option>
                                                        РЦР Калинина
                                                    </option>
                                                    <option>
                                                        РЦР КСМ
                                                    </option>
                                                    <option>
                                                        РЦР Родники
                                                    </option>
                                                    <option>
                                                        РЦР Учительская
                                                    </option>
                                                    <option>
                                                        РЦР Затулинка
                                                    </option>
                                                    <option>
                                                        РЦР Чемской
                                                    </option>
                                                    <option>
                                                        РЦР Горский
                                                    </option>
                                                    <option>
                                                        РЦР Западный
                                                    </option>
                                                    <option>
                                                        РЦР Маркса
                                                    </option>


                                                    <option>
                                                        РЦР Телецентр
                                                    </option>
                                                    <option>
                                                        РЦР Добрый
                                                    </option>
                                                    <option>
                                                        РЦР Меркурий
                                                    </option>
                                                    <option>
                                                        РЦР МЖК
                                                    </option>
                                                    <option>
                                                        РЦР Экватор
                                                    </option>
                                                    <option>
                                                        РЦР Первомайка
                                                    </option>
                                                    <option>
                                                        РЦР Академ
                                                    </option>
                                                    <option>
                                                        РЦР Волна-ОбьГЭС
                                                    </option>
                                                    <option>
                                                        РЦР Щ
                                                    </option>
                                                    <option>
                                                        РЦР Ника-logistics
                                                    </option>
                                                    <option>
                                                        РЦР Площадь Ленина
                                                    </option>
                                                    <option>
                                                        РЦР Краснообск
                                                    </option>
                                                    <option>
                                                        РЦР КОльцОвО
                                                    </option>
                                                    <option>
                                                        РЦР Бердск

                                                    </option>

                                                    <option>
                                                        РЦР Пашино
                                                    </option>
                                                    <option>
                                                        РЦР Слобода
                                                    </option>
                                                    <option>
                                                        РЦР Берёзовая роща
                                                    </option>

                                                </optgroup>


                                                <optgroup label="БАРНАУЛ">
                                                    <option>
                                                        РЦР Докучаево
                                                    </option>
                                                    <option>
                                                        РЦР Караван
                                                    </option>
                                                    <option>
                                                        РЦР Балтийский
                                                    </option>
                                                    <option>
                                                        РЦР Праздник
                                                    </option>
                                                    <option>
                                                        РЦР Флагман
                                                    </option>
                                                    <option>
                                                        РЦР Уютный
                                                    </option>
                                                    <option>
                                                        РЦР Центр
                                                    </option>
                                                    <option>
                                                        РЦР Ультра

                                                    </option>
                                                </optgroup>

                                            </select>
                                        </div>
                                    </li>
                                    <li><label><input type="radio" class="delivery_check" name="delivery"
                                                      value="post" id="delivery_post"> Почтой России</label>

                                        <div id="delivery_res" class="filters-res none"></div>
                                    </li>
                                    <li><label><input type="radio" class="delivery_check" name="delivery"
                                                      value="transport"> Транспортной компанией</label>
                                        <div class="dop_adres_tk" id="select_tk" style="display:none;">
                                            <select name="tr_firm" class="input">
                                                <option value="">Выберите ТК</option>
                                                <option value="Энергия">Энергия</option>
                                                <option value="Кит">Кит</option>
                                                <? /* <option value="ПЭК">ПЭК</option>
     <option value="Деловые Линии">Деловые Линии</option>*/
                                                ?>

                                            </select>
                                        </div>
                                        <div id="delivery_res2" class="filters-res none"><?
                                            /* <option value="СДЭК">СДЭК</option> if($sum > 7000){
                                                echo 'Доставка до ТК <span class="s_res">бесплатно</span>';
                                             }else{	*/
                                            //echo 'Доставка до ТК - <span class="s_res">100руб</span>';
                                            //}
                                            ?></div>

                                    </li>
                                    <li><label><input type="radio" class="delivery_check" name="delivery"
                                                      value="cdek"> Курьерская служба СДЭК</label>

                                        <div id="delivery_res3" class="filters-res none">
                                            Доставка до СДЭК
                                            <br/>
                                            <label><input type="radio" name="cdek_type" value="2" checked
                                                          onclick="$('#cdek2').show();$('#cdek1').hide();"/>до
                                                пункта выдачи СДЭК(склад)</label>
                                            <div id="cdek2">
                                                Адрес склада<br/>
                                                <input type="text" name="cdek_pvz"/>
                                                <a href="https://www.cdek.ru/contacts/city-list.html"
                                                   target="_blank" class="small">Посмотреть адреса</a>
                                            </div>


                                            <label><input type="radio" name="cdek_type" value="1"
                                                          onclick="$('#cdek1').show();$('#cdek2').hide();"/>до двери
                                            </label>
                                            <div id="cdek1" class="sgrey"
                                                 style="color:#666; font-size:14px; display:none"><-Укажите адрес
                                                доставки
                                            </div>


                                        </div>

                                    </li>
                                    <li>
                                        <label>
                                            <input type="radio" class="delivery_check" name="delivery" value="ozon">
                                            OZON
                                        </label>


                                        <!--OZON_AREA-->
                                        <div class="ozon-area hidden">
                                            <!--<input type="text" class="input" name="ozon-cities" placeholder="Выберите город" list="ozon-cities" />-->
                                            <select class="js-example-basic-single" name="ozon-cities" id="ozon-cities"
                                                    style="width: 100%;"></select>
                                            <p class="hidden delivery-loading"
                                               style="text-align: center; margin: 10px 0;">
                                                <i class="fa fa-spinner fa-pulse"></i>
                                            </p>
                                            <div id="deliveries-select-container" class="hidden"
                                                 style="padding: 10px 0;">
                                                <select class="js-example-basic-single" name="delivery-delivery"
                                                        style="width: 100%;"
                                                        id="ozon-delivery">
                                                </select>
                                            </div>
                                            <input type="hidden" name="ozon-point" id="ozon-point"/>
                                            <p class="hidden calculating" style="text-align: center; margin: 10px 0;">
                                                <i class="fa fa-spinner fa-pulse"></i>
                                            </p>
                                            <p id="amount"></p>
                                            <input type="hidden" name="ozon-delivery-cost"/>
                                        </div>

                                    </li>
                                    <!--li><label><input type="radio" class="delivery_check" name="delivery"
                                                      value="pickpoint"> Самовывоз
Титова 11/1 офис 1</label-->

                                </ul>


                                <div class="name">Способ оплаты:</div>
                                <ul class="ul ul2">
                                    <li><label><input type="radio" name="pay" value="card" checked> Картой Visa,
                                            Mastercard, Maestro</label></li>
                                    <li><label><input type="radio" name="pay" value="schet"> По счету</label></li>

                                </ul>
                                <div class="dop_text">Услуги транспортной компании и РЦР
                                    оплачиваются покупателем при получении.
                                </div>
                                <a href="javascript:" onclick="$('#send_form').submit();" class="btn btn-click">отправить
                                    заказ</a>
                            </div>
                        </form>

                        <!--OZON API-->
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

                        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
                              rel="stylesheet"/>
                        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                        <script>

                            //$(document).ready(function () {
                                $('.js-example-basic-single').select2();
                           // });

                            const citiesSelect = document.querySelector('#ozon-cities');
                            //const citiesSelectInput = document.querySelector('input[name="ozon-cities"]');
                            const deliverySelect = document.querySelector('#ozon-delivery');
                            const deliverySelectContainer = document.querySelector('#deliveries-select-container');
                            const ozonArea = document.querySelector('.ozon-area');

                            //выгружаем города из Ozon
                            fetch('/basket/ozon.php', {
                                method: 'POST',
                                body: JSON.stringify({method: 'cities'})
                            })
                                .then(response => response.json())
                                .then(response => {
                                    //console.log(response);

                                    if (typeof response !== "object") {
                                        return 0;
                                    }

                                    citiesSelect.innerHTML = '<option value="" selected disabled>Выберите город</option>';

                                    response.map(city => {
                                        const option = document.createElement('option');
                                        option.innerText = city.replace(/\"{2,}/gi, '"');
                                        citiesSelect.appendChild(option);
                                    });
                                });


                            //Показываем / скрываем ozon-area
                            document.querySelectorAll('input[name="delivery"]').forEach(input => {
                                input.onchange = () => input.value !== 'ozon' ?
                                    ozonArea.classList.add('hidden') : ozonArea.classList.remove('hidden');
                            });

                            //Выбор города
                            citiesSelect.onchange = () => {

                                const city = citiesSelect.value;

                                if (!city.trim())
                                    return 0;

                                const spinner = ozonArea.querySelector('.delivery-loading');
                                spinner.classList.remove('hidden');
                                deliverySelectContainer.classList.add('hidden');
                                document.getElementById('amount').classList.add('hidden');

                                fetch('/basket/ozon.php', {
                                    method: 'POST',
                                    body: JSON.stringify({method: 'delivery', city: city.trim()})
                                })
                                    .then(response => response.json())
                                    .then(response => {
                                        console.log(response);
                                        deliverySelectContainer.classList.remove('hidden');
                                        deliverySelect.innerHTML = '<option value="" selected disabled>Выберите пункт выдачи</option>';
                                        spinner.classList.add('hidden');
                                        response.map(deliveryItem => {
                                            const option = document.createElement('option');
                                            option.innerText = deliveryItem.name;
                                            option.value = deliveryItem.id;
                                            deliverySelect.appendChild(option);
                                        });
                                    });
                            };

                            //Выбор пункта самовывоза, рассчет стоимости доставки
                            deliverySelect.onchange = () => {

                                const spinner = ozonArea.querySelector('.calculating');
                                spinner.classList.remove('hidden');
                                const amountP = document.getElementById('amount');
                                amountP.classList.add('hidden');
                                const basket_ves = document.querySelector('input[name="ves"]').value;
                                const weight = parseInt(basket_ves) > 100000 ? parseInt(basket_ves) / 1000 : parseInt(basket_ves);

                                fetch('/basket/ozon.php', {
                                    method: 'POST',
                                    body: JSON.stringify({
                                        method: 'calculate',
                                        city: citiesSelect.value,
                                        deliveryVariantId: deliverySelect.value,
                                        weight: weight
                                    })
                                })
                                    .then(response => response.json())
                                    .then(response => {
                                        console.log(response);
                                        spinner.classList.add('hidden');
                                        if (response.amount) {
                                            //стоимость_доставки = (сумма_заказа * 0.0036) + стоимость_доставки + 50
                                            const order_sum = <?= $sum ?>;
                                            const insurance_rate = 0.0036;
                                            const amount = Math.ceil(order_sum * insurance_rate) + parseFloat(response.amount) + 50;

                                            const priceSpan = document.querySelector('.price-big');
                                            const deliverySpan = priceSpan.querySelector('.delivery-cost');
                                            if (deliverySpan !== null)
                                                deliverySpan.remove();
                                            priceSpan.innerHTML = priceSpan.innerHTML.replace('р', '').trim() + ' <span class="delivery-cost"> + ' + amount + '</span> р';
                                            amountP.classList.remove('hidden');
                                            amountP.innerHTML = '<b>Стоимость доставки:</b> &#8381;' + amount;
                                            document.querySelector('input[name="ozon-delivery-cost"]').value = amount;

											var selind = document.getElementById("ozon-delivery").options.selectedIndex;
											var txt = document.getElementById("ozon-delivery").options[selind].text;
											var val = document.getElementById("ozon-delivery").options[selind].value;
											document.querySelector('input[name="ozon-point"]').value = txt;
                                        }

                                    });
                            };
                        </script>
                        <!--***OZON API***-->


                        <? /*
                            <div class="col-md-3">

                                <form action="/reg/" method="POST" name="registerForm" id="registerForm"
                                      enctype="multipart/form-data" onsubmit="return check_reg(this);">
                                    <input type="hidden" name="action" value="add"/>
                                    <div class="h3">Регистрация</div>


                                    <input placeholder="Фамилия" class="input" id="fam" name="fam"/>
                                    <input placeholder="Имя" class="input" id="name" name="name"/>
                                    <input placeholder="Отчество" class="input" id="otch" name="otch"/>
                                    <input placeholder="Телефон" class="input" name="phone" id="phone"/>
                                    <input placeholder="Email" name="mail" id="mail" class="input"/>

                                    <input placeholder="Пароль" type="password" name="pass" class="input input2"/>
                                    <input placeholder="Повторите пароль" type="password" id="pass2" name="pass2"
                                           class="input input2"/>

                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><label class="label2 label3" for="code">Введите цифры <input
                                                            class="input input2" style="width:107px" name="code"
                                                            maxlength="8" id="code" autocomplete="off"
                                                            type="text"></label></td>

                                            <td class="captcha">
                                                <img id="captcha" src="/img.php" alt="Цифры с картинки"
                                                     title="Цифры с картинки"></td>
                                            <td><a class="dot_a a_in" id="captcha_refresh" href="#"
                                                   onclick="$('#captcha').attr('src', '/img.php?z='+(new Date()).valueOf()); return false;">Обновить
                                                    цифры </a></td>

                                        </tr>

                                    </table>


                                    <div class="label2"><a href="javascript:"
                                                           onclick="$('#registerForm').submit();return false;"
                                                           class="btn btn-click">Зарегистрироваться</a></div>

                                </form>


                                <form id="login-form" action="/reg/" method="post" class="none">
                                    <div class="h3">Авторизация</div>
                                    <input type="hidden" name="cmd" value="login"/>

                                    <input class="input input2" name="login" placeholder="Логин"/>

                                    <input class="input input2" type="password" name="pass" placeholder="Пароль"/>


                                    <div class="left">
                                        <a href="javascript:" onclick="$('#login-form').submit();return false;"
                                           class="btn btn-click">Войти</a>
                                    </div>
                                    <div class="left"><a href="/reg/forget.php">Забыли пароль?</a></div>
                                    <br class="clear"/>


                                </form>


                            </div>


                            <div class="col-md-3 text-small">
                                <div>
                                    <a href="/auth/" class="already"
                                       onclick="$('#login-form').show();$('#registerForm').hide();return false;">Авторизация</a>
                                    <a href="/reg/" class="already"
                                       onclick="$('#login-form').hide();$('#registerForm').show();return false;">Регистрация</a>
                                </div>

                                Доступ в личный кабинет дает вам доступ к оптовым ценам, сохраняет историю ваших
                                заказов. Информацию об акциях вы получаете первым.
                            </div>

                            */ ?>

                    </div>
                </div>

                <?
            }//if($send !=1)

        } else {
            echo '<h1 style="margin-top:30px">Ваша корзина</h1>
		<p class="alertM">В корзине пока нет товаров.</p>
		
		<br class="clearfix"/>';
        }
    }//esle{//if($done == 'yes'){
    ?>
</div>