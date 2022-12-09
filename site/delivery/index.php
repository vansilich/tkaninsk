<?$inc_path = "../../admin/"; $root_path="../../" ;include($inc_path."class/header.php");$this_page_id = 3;	$q = new query();
			$site_pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');?>
<?
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $redirect);
		exit();
	}

$this_page = $q->select1("select * from ".$prefix."pages where id=".to_sql($this_page_id));
$title  = $this_page['title'];
$descr = $this_page['description'];
$keys = $this_page['keywords'];
//$cat_id= get_param('cat_id',0);
$this_block = $q->select1("select folder from ".$prefix."blocks where id=".to_sql($this_page['block']));
if(!empty($this_block['folder'])){
  if(is_file($inc_path.'protoblocks/'.$this_block['folder'].'/_settings.php')){
    include($inc_path.'protoblocks/'.$this_block['folder'].'/_settings.php');  
  }
}

$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
$title = $title;
$descr = htmlspecialchars($descr);
$keys = htmlspecialchars($keys);

?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><? echo  $title;?></title>
<meta name="description" content="<? echo  $descr;?>">
<meta name="Keywords" content="<? echo  $keys;?>">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css" />
<link rel="stylesheet" type="text/css" href="/css/justified-nav.css" />
<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?=rand(1,9999);?>" />
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
<link href="/favicon2.ico" rel="shortcut icon" type="image/x-icon" />
<!-- JavaScript includes -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-NQ85BQ07JF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-NQ85BQ07JF');
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2925159064474256');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2925159064474256&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?168",t.onload=function(){VK.Retargeting.Init("VK-RTRG-777495-duTrp"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-777495-duTrp" style="position:fixed; left:-999px;" alt="/></noscript>
</head>


<body>

<?
include($inc_path.'templates/top.php');
?>
<img src="/img/about.jpg" width="100%"  alt=""/>
<div class="container"><h1>Доставка&nbsp;</h1>

<p>&nbsp;</p>

<p><span style="font-size:18px"><u><span style="font-family:Lucida Sans Unicode,Lucida Grande,sans-serif"><strong>Отправка товара осуществляется только после 100% предоплаты!</strong></span></u></span></p>

<p>&nbsp;</p>

<p><em>Доставка по г. Новосибирску:</em></p>

<p>По городу Новосибирску&nbsp;заказы передаем через РЦР - пункты выдачи интернет-заказов. Адреса и контактную информацию можно посмотреть на сайте&nbsp;гдерцр.рф</p>

<p>Мы сдаем заказы в РЦР Телецентр (ул. Костычева, 5) каждый будний день.&nbsp;<br />
При заказе указывайте наиболее удобный для Вас пункт выдачи, имя (или ник), пароль (по желанию), если ник и пароль не указаны, мы по&nbsp;умолчанию&nbsp;отправляем на имя, указанное в заказе и устанавливаем свой пароль и сообщаем его Вам&nbsp;<br />
<br />
<em>Доставка по России:&nbsp;</em><br />
- Почта РФ.<br />
Осуществляем отправку&nbsp;почтой России по всей территории РФ. При оформлении заказа расчет стоимости пересылки рассчитывается автоматически (достаточно лишь указать Ваш индекс)&nbsp;в соответствии с тарифами почты РФ и включается в счет.&nbsp;Отправка почтой происходит 1 раз в неделю по средам.<br />
<br />
- Транспортные компании.<br />
Осуществляем доставку следующими транспортными компаниями: Энергия, Кит, СДЭК, Озон. Отправляем 1 раз в неделю транспортными компания Кит и Энергия. Отправка СДЭК - 2 раза в неделю, вторник и пятница. Услуги транспортной компании оплачиваются покупателем при получении. Транспортной компанией ОЗОН отправляем 3-4 раза в неделю,&nbsp;стоимость&nbsp;пересылки в соответствии с тарифами ОЗОН оплачивается нашему интернет-магазину.</p>

<p><br />
&nbsp;</p>

<h1>Оплата</h1>

<p>&nbsp;</p>

<p><span style="font-size:18px"><strong>ОПЛАТА С ПОМОЩЬЮ БАНКОВСКОЙ КАРТЫ</strong></span></p>

<p><span style="font-size:18px">&nbsp;Для выбора оплаты товара с помощью банковской карты на соответствующей странице сайта необходимо нажать кнопку <strong>&laquo;Оплата банковской картой&raquo;</strong>.</span></p>

<p><span style="font-size:11px">* Все операции с&nbsp;банковскими картами проводятся в&nbsp;соответствии с&nbsp;требованиями VISA International и&nbsp;MasterCard Worldwide. Безопасность платежей гарантируется использованием протокола SSL для&nbsp;передачи конфиденциальной информации.</span></p>

<p><span style="font-size:11px">Для оплаты (ввода реквизитов Вашей карты) Вы будете перенаправлены на&nbsp;платежный шлюз ПАО СБЕРБАНК. Соединение с&nbsp;платежным шлюзом и&nbsp;передача информации осуществляется в&nbsp;защищенном режиме с&nbsp;использованием протокола шифрования SSL. В случае, если Ваш банк поддерживает технологию безопасного проведения интернет-платежей Verified&nbsp;By Visa или MasterCard SecureCode, для&nbsp;проведения платежа также может потребоваться ввод специального пароля. Настоящий сайт поддерживает 256-битное шифрование. Конфиденциальность сообщаемой персональной информации обеспечивается ПАО СБЕРБАНК. Введенная информация не&nbsp;будет предоставлена третьим лицам за&nbsp;исключением случаев, предусмотренных законодательством РФ. Проведение платежей по&nbsp;банковским картам осуществляется в&nbsp;строгом соответствии с&nbsp;требованиями платежных систем МИР, Visa&nbsp;Int. и&nbsp;MasterCard Europe Sprl.</span></p>

<p><strong>Оплата по банковским картам VISA</strong></p>

<p>К оплате принимаются все виды платежных карточек VISA, за исключением Visa Electron. В большинстве случаев карта Visa Electron не применима для оплаты через интернет, за исключением карт, выпущенных отдельными банками. О возможность оплаты картой Visa Electron вам нужно выяснять у банка-эмитента вашей карты.</p>

<p><strong>Оплата по кредитным картам MasterCard</strong></p>

<p>На сайте к оплате принимаются все виды MasterCard.</p>

<p><strong>Что нужно знать:</strong></p>

<p><strong>1</strong>. номер вашей кредитной карты;<br />
<strong>2</strong>. cрок окончания действия вашей кредитной карты, месяц/год;<br />
<strong>3</strong>. CVV код для карт Visa / CVC код для Master Card: 3 последние цифры на полосе для подписи на обороте карты.</p>

<p><span style="font-size:11px">Если на вашей карте код CVC / CVV отсутствует, то, возможно, карта не пригодна для CNP транзакций (т.е. таких транзакций, при которых сама карта не присутствует, а используются её реквизиты), и вам следует обратиться в банк для получения подробной информации.</span></p>

<p>&nbsp;</p>

<p><span style="font-size:18px"><strong>ОПЛАТА НА РАСЧЕТНЫЙ СЧЕТ</strong></span></p>

<p><span style="font-size:18px">Для выбора оплаты товара на расчетный счет&nbsp;на соответствующей странице сайта необходимо нажать кнопку <strong>&laquo;По счету&raquo;</strong>.</span></p>

<p>Ваш заказ будет направлен менеджеру.</p>

<p>В ближайшее время на электронную почту менеджер отправит счет, по которому Вы сможете произвести оплату.</p>

<p>Просьба, для сокращения времени выполнения заказа, выслать чек ответным письмом с указанием номера Вашего заказа.</p>

<p>&nbsp;</p>

<p>&nbsp;</p>
</div>



<?
include($inc_path.'templates/bottom.php');
?>