<?
$err = 0;
$fam = $fio = trim(strip_tags(get_param('fio')));
$name = trim(strip_tags(get_param('name')));
$otch = trim(strip_tags(get_param('otch')));
$fio = $fam . ' ' . $name . ' ' . $otch;
$phone = trim(strip_tags(get_param('phone')));
$email = trim(strip_tags(get_param('email')));
$comm = trim(strip_tags(get_param('comm')));
$city = trim(strip_tags(get_param('city')));
$adres = $city . ' ' . trim(strip_tags(get_param('adres')));
$pay_type = $pay = trim(strip_tags(get_param('pay')));
$ftype = trim(strip_tags(get_param('ftype')));
$postindex = trim(strip_tags(get_param('postindex')));
$select_rc = trim(strip_tags(get_param('select_rc')));
$tr_firm = trim(strip_tags(get_param('tr_firm')));


$ozon_cities=get_param('ozon-cities');
$ozon_point=get_param('ozon-point');

if ($ftype == 1) {
    $ft = 'Физ. лицо';
}
if ($ftype == 2) {
    $ft = 'Юр. лицо';
}

$delivery_type = $delivery = trim(strip_tags(get_param('delivery')));
switch ($pay) {
    case 'nal':
        $pay = 'Наличными';
        break;
    case 'schet':
        $pay = 'По счету';
        break;
    case 'card':
        $pay = 'Картой Visa, Mastercard, Maestro';
        break;
}
switch ($delivery) {
    case 'sam':
        $delivery = 'РЦР по Новосибирску [' . $select_rc . ']';
        break;
    case 'post':
        $delivery = 'Почтой России - индекс:' . $postindex;
        break;
    case 'transport':
        $delivery = 'Транспортной компанией [' . $tr_firm . ']';
        break;
    case 'pickpoint':
        $delivery = 'самовывоз Титова 11/1 офис 1';
        break;
    case 'ozon':
        $delivery = 'OZON '.$ozon_cities.'['.$ozon_point.']';
        break;

    case 'cdek':
        $delivery = 'СДЭК';
        $cdek_type = strip_tags(get_param('cdek_type'));
        if ($cdek_type == 1) {
            $delivery .= ' [до двери]';
        }
        if ($cdek_type == 2) {
            $cdek_pvz = strip_tags(get_param('cdek_pvz'));
            $delivery .= ' [до пункта выдачи СДЭК="' . $cdek_pvz . '"]';
        }
        break;
}
if ($err == 0) {


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
                $coupon_msg = '<div style="color:red">Сумма заказа должна быть больше ' . $check['min_sum'] . '</div>';
            } else {
                if ($check['sale_proc'] > 0) {
                    $coupon_sale_proc = $check['sale_proc'];
                    $coupon_msg = '<div style="color:green">Скидка ' . $check['sale_proc'] . '%</div>';
                } elseif ($check['sale_sum'] > 0) {
                    $coupon_sale_sum = $check['sale_proc'];
                    $coupon_msg = '<div style="color:green">Скидка ' . $check['sale_sum'] . 'руб </div>';
                }
            }
        } else {
            $coupon_msg = '<div style="color:red">Не правильный код или закончилась акция</div>';
        }
    }


    $goods_order = '';
    $cart_b = $_SESSION['basket_catalog'];
    $sum = 0;
    $col = 0;
    $ves = 0;
    $msg = ' <table cellpadding="0" cellspacing="0" width="600">
			 		  <tr valign="top">
						
						<td style="padding:10px 20px 10px 40px;" colspan="2">' . $ft . '</td>
					  </tr>
					  <tr valign="top">
						<td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">Имя:</td>
						<td style="padding:10px 20px 10px 40px;">' . $fio . '</td>
					  </tr>
					   <tr valign="top">
						  <td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">Адрес доставки</td>
						  <td style="padding:10px 20px 10px 40px;">' . $adres . '</td>
						</tr>
					  <tr valign="top">
						<td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">E-mail:</td>
						<td style="padding:10px 20px 10px 40px;">' . $email . '</td>
					  </tr>
					  <tr valign="top">
						<td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">Телефон:</td>
						<td style="padding:10px 20px 10px 40px;">' . $phone . '</td>
					  </tr>
					  <tr valign="top">
						<td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">Комментарии к заказу:</td>
						<td style="padding:10px 20px 10px 40px;">' . $comm . '</td>
					  </tr>
					  <tr valign="top">
						<td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">Оплата:</td>
						<td style="padding:10px 20px 10px 40px;">' . $pay . '</td>
					  </tr>
					  <tr valign="top">
						<td style="border-right:1px solid #e2e2e2; padding:10px 20px; width:150px;">Доставка:</td>
						<td style="padding:10px 20px 10px 40px;">' . $delivery . '</td>
					  </tr>
			  		</table>';

    if (!empty($_SESSION['basket_coupon'])) {
        $msg .= '<div style="font-size:18px;"><b>Купон</b>: ' . $_SESSION['basket_coupon'] . '</div>';
        $msg .= $coupon_msg . '<br>';
    }

    $mail_sms = $fio . "|" . $phone . "|" . $adres . "|" . $email . "|" . $comm . "{";
    $fuulo = '10000';
    $sms_text = '';

    $client_order = '<div style="background:url(http://' . $_SERVER['HTTP_HOST'] . '/images/bg-line.jpg) no-repeat 50% 0 #E9E9E9; padding-top:50px; padding-bottom:50px;">
			<div style="width:600px; margin:0 auto; background:#FFF; border:1px solid #e5e5e5; padding:0 20px 20px;">
			<a href="http://www.' . $_SERVER['HTTP_HOST'] . '"><img src="http://' . $_SERVER['HTTP_HOST'] . '/img/logo.gif" style="float:right; margin:20px 0 40px 20px;" /></a>
			<br/>
			<h2>Здраствуйте, ' . $fio . '! Спасибо за заказ!</h2>
			<p>Благодарим, что выбрали наш интернет-магазин! <br>
			Заказ собираем и отправляем только после 100% предоплаты.<br>
Если у Вас возникли вопросы, Вы можете позвонить нам по телефону ' . $_settings['code'] . ' ' . $_settings['phone'] . '. Консультант работает с ПН по ПТ, с 10 до 16 (+4 МСК)</p>
			<p>Если с Вами не связались, Вы можете позвонить сами по телефону <b>' . $_settings['code'] . ' ' . $_settings['phone'] . '</b><br /><br /></p>
			<h2>Ваш заказ №{ORDERID}:</h2>
			<table border="0" cellpadding="0" cellspacing="0" width="90%" style="margin:20px auto">
			<tr><th width="160" align="left" style="padding:5px 10px;">Фото</th><th width="200" align="left" style="padding:5px 10px;">Название</th><th width="120" align="left" style="padding:5px 10px;">Цена</th><th width="120" align="left" style="padding:5px 10px;">Кол-во</th></tr>';


    $goods_order .= '
			<table border="0" cellpadding="0">
			<tr><th width="120" align="left" style="padding:5px 10px;">Фото</th><th width="4" align="left" style="padding:5px 10px;">id</th><th width="200" align="left" style="padding:5px 10px;">Название</th><th width="120" align="left" style="padding:5px 10px;">Цена</th><th width="120" align="left" style="padding:5px 10px;">Кол-во</th></tr>';

    foreach ($cart_b as $k => $v) {
        $good = $q->select1("select * from " . $prefix . "goods as G
				where G.id=" . to_sql($v['good']));

        $price_tit = '';
        if ($v['q'] >= $col_opt && $good['price_opt'] > 0) {
            $price = $good['price_opt'];
            $price_tit = 'опт. ';
        } else {
            $price = $good['price'];
        }

        if ($coupon_sale_proc > 0 && $good['price_old'] == 0) {
            $price = (int)round($price * ((100 - $coupon_sale_proc) / 100), 0);
        }


        $ves += $v['q'] * $good['ves'];
        $sum += $v['q'] * $price;
        $col += $v['q'];
        $sms_text .= $good['name'] . "| ";
        $mail_sms .= $good['name'] . "| ";
        $fuulo .= ',' . $v['good'];


        $img = get_image_cpu($good, 'files/goods/1/', 'img1', 1);
        if (!empty($img)) {
            $img = '<img src="http://' . $_SERVER['HTTP_HOST'] . '' . $img . '" border="0" width="50" alt="' . my_htmlspecialchars($good['title']) . '" class="img">';
        }


        if ($v['type'] == 'color' && $v['type_id'] > 0) {
            /*$color = $q->select1("select G.*,C.code,C.name as cname from ".$prefix."goods_price as G
            left join ".$prefix."color as C on C.id = G.color
            where G.id=".to_sql($v['type_id']));
            */
            $color = $q->select1("select G.*,C.name as cname from " . $prefix . "goods_price as G 
					left join " . $prefix . "adv_params_value as C on C.id = G.color
					where G.id=" . to_sql($v['type_id']));

            $good['articul'] = $color['articul'];
            if (!empty($color['cname'])) $good['name'] = $good['name'] . ' [' . $color['cname'] . ']';
            $img = get_image_folder($color, 'files/color/1/', 'img1', 1);
            if (!empty($img)) {
                $img = '<img src="http://' . $_SERVER['HTTP_HOST'] . '' . $img . '" border="0" width="50" alt="' . my_htmlspecialchars($color['name']) . '" class="img">';
            }
        } else {
            //$main_color = $q->select1("select C.code,C.name as cname from ".$prefix."color as C where id=".to_sql($good['color']));
            $main_color = $q->select1("select C.name as cname from " . $prefix . "adv_params_value as C where id=" . to_sql($good['color']));
            if (!empty($main_color['cname'])) $good['name'] = $good['name'] . ' [' . $main_color['cname'] . ']';
        }


        $client_order .= '<tr style="border-bottom:1px solid #ccc;">
				<td style="padding:5px 10px;">' . $img . '</td>
				<td style="padding:5px 10px;">' . $good['name'] . '</td>
				<td style="padding:5px 10px;">' . $price_tit . '<b>' . $price . '</b></td>
				<td style="padding:5px 10px;">' . $v['q'] . '</td>
				</tr>';


        $goods_order .= '<tr style="border-bottom:1px solid #ccc;">
				<td style="padding:5px 10px;">' . $img . '</td>
				<td style="padding:5px 10px;">' . $v['good'] . '</td>
				<td style="padding:5px 10px;">' . $good['name'] . '</td>
				<td style="padding:5px 10px;">' . $price_tit . '<b>' . $price . '</b></td>
				<td style="padding:5px 10px;">' . $v['q'] . '</td>
				</tr>';
    }
    $mail_sms .= "}";

    $goods_order .= '</table><p style="border-top:1px solid #ccc; padding:5px 10px;">Заказано ' . $col . ' товаров на сумму: <b>' . $sum . '</b> руб.</p>';


    $client_order .= '</table>';
    $client_order .= '<p>Вы заказали ' . $col . ' товаров на общую сумму: <b>' . $sum . '</b> руб.</p>';

    $send = 1;


    switch ($delivery_type) {
        case 'sam':
            $delivery_price = 0;
            break;
        case 'pickpoint':
            $delivery_price = 0;
            break;

        case 'post':
            $toindex = $postindex;
            $ves = $ves / 1000;
            //запрос расчета стоимости отправления из 101000 МОСКВА во ВЛАДИМИР 600000.
            $ret = russianpostcalc_api_calc("59138c11112d334a081e38a80b3ce5aa", "Nas654321", "630000", $toindex, $ves, $sum);

            if (isset($ret['msg']['type']) && $ret['msg']['type'] == "done") {
                foreach ($ret['calc'] as $v) {
                    if ($v['type'] == 'rp_main') {
                        $price = $v['cost'];
                    }
                }

                $price += 50;
                $dop_ves = $price;
                //До 2кг – 50руб,  2-3,7кг – 75руб, от 3,7-7кг – 100руб
                if ($ves > 3.7) $price += 100;
                elseif ($ves > 2) $price += 75;
                else $price += 50;
                $dop_ves .= '=' . $price;
            }
            $delivery_price = $price;
            break;
        case 'transport': //if($sum<7000) $delivery_price = 100; else $delivery_price = 0;
            //$delivery_price = 100;
            $delivery_price = 0;

            break;
        case 'cdek': //if($sum<7000) $delivery_price = 100; else $delivery_price = 0;
            $delivery_price = 0;
            break;

        case 'ozon':
            $delivery_price = get_param('ozon-delivery-cost');
            break;
    }

    $newoid = $q->insert("insert into " . $prefix . "orders set
					ftype = " . to_sql($ftype) . ",
					fio = " . to_sql($fio) . ",
					fam = " . to_sql($fam) . ",
					name = " . to_sql($name) . ",
					otch = " . to_sql($otch) . ",
					adres = " . to_sql($adres) . ",
					phone = " . to_sql($phone) . ",
					email = " . to_sql($email) . ",
					comm = " . to_sql($comm) . ",
					user_id = " . to_sql($_SESSION['user_info']['id']) . ",
					delivery = " . to_sql($delivery) . ",
					delivery_price = " . to_sql($delivery_price) . ",
					delivery_type = " . to_sql($delivery_type) . ",

ves = " . to_sql($ves) . ",
					pay = " . to_sql($pay) . ",
					pay_type = " . to_sql($pay_type) . ",
					full_text = " . to_sql($fuulo) . ",
					date_add = NOW(),
					order_status = 0,
					coupon=" . to_sql($_SESSION['basket_coupon']) . ",
					order_text = " . to_sql($goods_order) . "
					");


    foreach ($cart_b as $k => $v) {
        $good = $q->select1("select * from " . $prefix . "goods as G
				where G.id=" . to_sql($v['good']));

        if ($v['q'] >= 5 && $good['price_opt'] > 0) {
            $price = $good['price_opt'];
        } else {
            $price = $good['price'];
        }

        if ($coupon_sale_proc > 0 && $good['price_old'] == 0) {
            $price = (int)round($price * ((100 - $coupon_sale_proc) / 100), 0);
        }

        $q->insert("insert into " . $prefix . "orders_full set
					order_id = " . to_sql($newoid) . ",
					good_id = " . to_sql($v['good']) . ",
					price = " . to_sql($price) . ",
					col = " . to_sql($v['q']) . ",
					types = " . to_sql($v['type']) . ",
					param = " . to_sql($v['type_id']) . "
					");
    }


    $subject = 'Заказ с сайта ' . $_SERVER['HTTP_HOST'];
    $msg = '<h3>Детали заказа <b>№' . $newoid . '</b> от ' . date('d.m.Y H:i') . '</h3>' . $msg;
    $headers = "From: " . $_SERVER['HTTP_HOST'] . " <" . $_settings['email'] . ">\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    //mail($_settings['email'], $subject, $msg.'<br/><br/>'.$goods_order, $headers);

    $q->exec("update " . $prefix . "orders set letter_order=" . to_sql($msg . '<br/><br/>' . $goods_order) . " where id=" . to_sql($newoid));

    if (!empty($email)) {
        if (is_valid_email($email)) {
            $client_order .= '<div style="margin:40px 10px 20px; border-top:2px dashed #ccc; border-bottom:2px dashed #ccc; padding:20px 0;">' . $msg . '</div>
					<p>*Данное письмо является информативным о том, что Вы сделали заказ на сайте.</p>
					<br/>
					------<br/>
					С уважением, ' . $_SERVER['HTTP_HOST'] . '</div></div>';

            $client_order = str_replace('{ORDERID}', $newoid, $client_order);
            //mail($email, $subject, $client_order, $headers);
            $q->exec("update " . $prefix . "orders set letter_body=" . to_sql($client_order) . " where id=" . to_sql($newoid));
        }
    }


    echo '<div class="complete">Спасибо за то, что выбрали наш интернет-магазин ' . $_SERVER['HTTP_HOST'] . '!
			<br/> Ваш заказ №' . $newoid . ' поступил в отдел продаж.<br> <br>
			Заказ собираем и отправляем только после 100% предоплаты.<br>
            Если у Вас возникли вопросы, Вы можете позвонить нам по телефону 8-913-917-01-89. Консультант работает с ПН по ПТ, с 10 до 16 (+4 МСК)
			

			</div>';

    $_SESSION['old_basket_catalog'] = $_SESSION['basket_catalog'];
    $_SESSION['old_basket_id'] = $newoid;

    $_SESSION['basket_catalog'] = array();
    $_SESSION['mbasket'] = '<span class="b_sp">Ваша корзина:</span> <ul><li>в корзине сейчас пусто</li></ul>';


    header('location: /basket/?done=yes&oid=' . $newoid);
}


function _russianpostcalc_api_communicate($request)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://russianpostcalc.ru/api_beta_077.php");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    curl_close($curl);
    if ($data === false) {
        return "10000 server error";
    }

    $js = json_decode($data, $assoc = true);
    return $js;
}

function russianpostcalc_api_calc($apikey, $password, $from_index, $to_index, $weight, $ob_cennost_rub)
{
    $request = array("apikey" => $apikey,
        "method" => "calc",
        "from_index" => $from_index,
        "to_index" => $to_index,
        "weight" => $weight,
        "ob_cennost_rub" => $ob_cennost_rub
    );

    if ($password != "") {
        //если пароль указан, аутентификация по методу API ключ + API пароль.
        $all_to_md5 = $request;
        $all_to_md5[] = $password;
        $hash = md5(implode("|", $all_to_md5));
        $request["hash"] = $hash;
    }

    $ret = _russianpostcalc_api_communicate($request);

    return $ret;
}
?>