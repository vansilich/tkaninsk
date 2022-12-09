jQuery.colorbox.settings.maxWidth  = '90%';
function setEqualHeight(columns)
{
	var tallestcolumn = 0;
	columns.each(
		function()
		{
			currentHeight = $(this).height();
				if(currentHeight > tallestcolumn)
				{
					tallestcolumn = currentHeight;
				}
		}
	);
	columns.height(tallestcolumn);
}
function reachGoal(goal){
	
}
jQuery(document).ready(function() {
	$(".colorbox").colorbox();
	$(".products-group").colorbox({rel:'foto'});
	
	$(".c_iframe").colorbox({fixed:true,width:"850px",height:"90%", iframe:true,overlayClose:true});
	$("a[rel='port']").colorbox({fixed:true,width:"850px",height:"90%", iframe:true,overlayClose:true});
	$(".c_popup").colorbox({width:"805px",height:"100%", iframe:false,overlayClose:false});



	owlm = $("#banner");
	owlm.owlCarousel({loop:true,items:1,nav:false,dots:false,smartSpeed:1000, autoplay:true});
	
	$('#mm1').click(function(){
		owlm.trigger('to.owl.carousel',[0, 500,true] ); return false;
	});
	$('#mm2').click(function(){
		owlm.trigger('to.owl.carousel',[1, 500,true] );return false;
	});
	$('#mm3').click(function(){
		owlm.trigger('to.owl.carousel',[2, 500,true] );return false;
	});
	$('#mm4').click(function(){
		owlm.trigger('to.owl.carousel',[3, 500,true] );return false;
	});


	$(".main_tabs_tabs .tabs-a").click(function(){
		if(!$(this).hasClass('tabs-a-ac')){
			$(".main_tabs_tabs .tabs-a").removeClass('tabs-a-ac');
			$(this).addClass('tabs-a-ac');
			$('.main_tabs>div').removeClass('active');
			$('#'+$(this).data('tab')).addClass('active');
		}
		return false;
	});


	$('.js_col').change(function(){
		$max = $(this).data('max');
		
		$(this).val($(this).val().replace(',', '.'));
		var col = $(this).val()/1;
		col = col.toFixed(1)/1;
		if(isNaN(col)) col=0;
        if(col < 0.5) col = 0.5;
		if(col > $max) col = $max;
		col = Math.round (col * 100) / 100;
		$(this).val(col);
		return false;
	});
	
	$('._js_minus').click(function(){
		$id = $(this).data('id');
		//$('#_col'+$id).val().replace(',', '.');
		var col = $('#_col'+$id).val()/1;
		col = col.toFixed(1)/1;
		if(isNaN(col)) col=0;
		col = col-0.1;
		if(col<0.5) col=0.5;
		col = Math.round (col * 100) / 100;
		$('#_col'+$id).val(col);
		return false;
	});
	
	$('._js_plus').click(function(){
		$id = $(this).data('id');
		$max = $(this).data('max');
		//$('#_col'+$id).val().replace(',', '.');
		var col = $('#_col'+$id).val()/1;
		col = col.toFixed(1)/1;
		if(isNaN(col)) col=0;
		col = (col+0.1);
		if(col > $max) col = $max;
		
		col = Math.round (col * 100) / 100;
		$('#_col'+$id).val(col);
		return false;
	});
	
	
	$('._js_minus_cel').click(function(){
		$id = $(this).data('id');
		var col = parseInt($('#_col'+$id).val());
		if(isNaN(col)) col=0;
		col = col-1;
		if(col<0) col=0;
		$('#_col'+$id).val(col);
		return false;
	});
	
	$('._js_plus_cel').click(function(){
		$id = $(this).data('id');
		$max = parseInt($(this).data('max'));
		var col = parseInt($('#_col'+$id).val());
		if(isNaN(col)) col=0;
		col = col+1;
		if(col > $max) col = $max;
		
		$('#_col'+$id).val(col);
		return false;
	});
	$('.js_basket').click(function(){
		$id = $(this).data('id');
		addtobasket($id);
		return false;
	});
	$('.js_basket_color').click(function(){
		$id = $(this).data('id');
		addtobasket_color($id);
		return false;
	});
	
	$('.js_change_url').change(function(){
  		url = $('.js_change_url').val();
		document.location.href=url;
	});
	
	$('.js-smfoto').mouseover(function(){
  		$id = $(this).data('id');
		$('.big_foto .products-group').hide();
		$('#'+$id).show();	
	});
	
	
	$('._js_cat_menu').mouseover(function(){
  		$id = $(this).data('id');
		$('.sub_cat').hide();
		$('#sub_cat'+$id).show();	
	});
	$('._js_cat_menu').click(function(){
  		$id = $(this).data('id');
		$('.sub_cat').hide();
		$('#sub_cat'+$id).show();
		return false;	
	});
	
	$('#search-param ._js_search_param').click(function(){
		if($(this).hasClass('ac')){
			$(this).removeClass('ac');
			$(this).next().removeClass('ac');
		}else{
			$(this).addClass('ac');
			$(this).next().addClass('ac');
		}
		return false;
	});
	$('#search-param input[type="checkbox"]').click(function(){
		onSchange();
	});
	
	
	
	
	$('#postindex').change(function(){
		
			$postindex = $('#postindex').val(); 
			if($postindex == ''){
				$('#delivery_res').show();
				$('#delivery_res').html('Укажите почтовый индекс');	
			}else{
				var msg = $("#send_form").serialize();
				$.ajax({
					type: "POST",
					url: "/ajax/rp_calc.php",
					data: msg,
					success: function(data){
						if($("#delivery_post").prop("checked")){
							$('#delivery_res').show();
						}
						$('#delivery_res').html(data);	
					},
					error:  function(xhr, str){
						alert("Возникла ошибка!");
					}
				});
			}
  		
	});
	
	$('.delivery_check').click(function(){
  		$val = $(this).val();
		
		if($val == 'transport'){
			$('#delivery_res2').show();	
			$('#delivery_res3').hide();	
			$('.dop_adres_tk').hide();	
			$('#select_tk').show();	
		}else{
			if($val == 'cdek'){
				$('#delivery_res2').hide();	
				$('#delivery_res3').show();	
				$('.dop_adres_tk').hide();	
					
			}else{
				$('.dop_adres_tk').hide();	
				$('#delivery_res2').hide();
				$('#delivery_res3').hide();
			}
		}
		if($val == 'sam'){
			$('.dop_adres_tk').hide();	
			$('#select_rc').show();	
		}
		
		if($val == 'post'){
			$postindex = $('#postindex').val(); 
			if($postindex == ''){
				$('#delivery_res').show();
				$('#delivery_res').html('Укажите почтовый индекс');	
			}else{
				var msg = $("#send_form").serialize();
				$.ajax({
					type: "POST",
					url: "/ajax/rp_calc.php",
					data: msg,
					success: function(data){
						$('#delivery_res').show();
						$('#delivery_res').html(data);	
					},
					error:  function(xhr, str){
						alert("Возникла ошибка!");
					}
				});
			}
			
		}else{
			
			$('#delivery_res').hide();
			$('#delivery_res').html('');
		}
		
	});
	

});


var position;
$(document).bind('cbox_open', function() {
  /*  position = $("#right").scrollTop();
	alert(position);*/
    $('body').css({
        overflow: 'hidden'
    });
}).bind('cbox_closed', function() {
    $('body').css({
        overflow: 'auto'
    });
	/*alert(position);
	$("#right").scrollTop(position);*/
    
});



function onSchange(){	
	$("#s_res").html('*');
	var queryString = $('#search_Form').serialize();
	$.post('/ajax/q_search.php', queryString, onSchangeSuccess); 
}
function onSchangeSuccess(data)
{
	$("#s_res").html(data);
}
function metrika_goal(goal){
	yaCounter50170459.reachGoal(goal);	/* добавление в корзину */	
}
function addtobasket_color(gid){
	var col = $('#_col'+gid).val()/1;
	col = col.toFixed(1)/1;
	
	if(isNaN(col)) col=0;
	col = Math.round (col * 100) / 100;
	var type_id = $('#_type'+gid).val();
	$.post(
	  '/ajax/basket.php',
	  {
		gid: gid,
		type: 'color',
		type_id: type_id,
		col: col,
		action: "add_to_basket"
	  },
	  onBasketSuccess
	);
	metrika_goal('basket');
	reachGoal('add2Basket');	
}
	
function addtobasket(gid){
	var col = $('#_col'+gid).val()/1;
	
	if(isNaN(col)) col=1;
	$.post(
	  '/ajax/basket.php',
	  {
		gid: gid,
		type: 'good',
		col: col,
		action: "add_to_basket"
	  },
	  onBasketSuccess
	);
	reachGoal('add2Basket');
	metrika_goal('basket');
}
function ajax_one(id) { //Ajax отправка формы
	
	tel = $('#tel-'+id).val();
	if(tel == ''){
		$("#tel-"+id).addClass('error');
		$("#tel-"+id).focus();
		setTimeout(function () {
			$("#tel-"+id).removeClass('error');
		}, 2600);
		return false;
	}

	var msg = $("#form-"+id).serialize();
	$.ajax({
		type: "POST",
		url: "/ajax/oneclick.php",
		data: msg,
		success: function(data){
			$('#w'+id).hide();
			$("#name-"+id).val('');
			$("#email-"+id).val('');
			$("#tel-"+id).val('');
			$('#res_dialog').css('top',$(window).scrollTop()+100);
	        $('#res_dialog').show();
			$('#shadow').show();
metrika_goal('oneclick');
/*
			setTimeout(function(){
				$('#res_dialog').hide();
				$('#shadow').hide();
			},3700);*/
		},
		error:  function(xhr, str){
			alert("Возникла ошибка!");
		}
	});
}


function refreshbasket(){	
	$.post(
	  '/ajax/basket.php',
	  {
		action: "refresh"
	  },
	  onBasketSuccess2
	);
}

function onBasketSuccess(data)
{
	text = $("#text", data).html();
	col = $("#col", data).html();
	basket_col = $("#basket_col", data).html();
	sum = $("#sum", data).html();
	basket = $("#basket", data).html();
	basket_col = $("#basket_col", data).html();
	upd = $("#upd", data).html();
	$("#cart_sum").html(sum);
	$("#basket_col").html(basket_col);
	$("#cart_text").html(basket);
	
	$('#res_basket').css('top',$(window).scrollTop()+100);
	$('#res_basket').show();
	$('#shadow').show();

}

function onBasketSuccess2(data)
{
	text = $("#text", data).html();
	col = $("#col", data).html();
	basket_col = $("#basket_col", data).html();
	sum = $("#sum", data).html();
	basket = $("#basket", data).html();
	basket_col = $("#basket_col", data).html();
	upd = $("#upd", data).html();
	$("#cart_sum").html(sum);
	$("#basket_col").html(basket_col);
	$("#cart_text").html(basket);
	

}

function updatebasket(gid,col){	
	$.post(
	  '/ajax/basket.php',
	  {
		gid: gid,
		col: col,
		action: "update_basket"
	  },
	  onBasketSuccess2
	);
}	

function clearbasket(gid){	
	$.post(
	  '/ajax/basket.php',
	  {
		action: "clear_basket"
	  },
	  onBasketSuccess2
	);
}	
		
function delbasket(gid){	
	$.post(
	  '/ajax/basket.php',
	  {
		gid: gid,
		action: "del_basket"
	  },
	  onBasketSuccess2
	);
}	

function search_res_show(){
	$('#search_word').click(function(event){
		event.stopPropagation();
	});
	$('#search_res').click(function(event){
		event.stopPropagation();
	});
	$('body').click(function(event){
		search_res_hide();
	});
	$('#search_res').show();	
}
function search_res_hide(){
	$('#search_res').hide();
	$('body').unbind('click');
}


function auto_search(val){	
	$.post(
	  '/ajax/search.php',
	  {
		val: val
	  },
	  onSearchSuccess
	);
}
function onSearchSuccess(data)
{
	text = $("#text", data).html();
	$('#search_res').html(text);
	search_res_show();
}
function check_basket(){
	
	var mas=['#b_fio','#b_name','#b_otch','#basket_phone','#b_city'];
	var index;
	var $tel;
	for (index = 0; index < mas.length; ++index) {
		console.log(mas[index]);
		$tel = $(mas[index]).val();
		console.log('v='+$tel);
		if($tel == ''){
			$(mas[index]).addClass('error');
			$(mas[index]).focus();
			setTimeout(function () {$(mas[index]).removeClass('error');	}, 2600);
			return false;	
		}
		console.log(mas[index]);
		
	}
	/*
	var $tel = $('#basket_phone').val();
	if($tel == ''){
		$('#basket_phone').addClass('error');
		$('#basket_phone').focus();
		return false;	
	}
	*/
	
	
	
	
	var $basket_email = $('#basket_email').val();
	if($basket_email == ''){
		$('#basket_email').addClass('error');
		$('#basket_email').focus();
		setTimeout(function () {$("#basket_email").removeClass('error');	}, 2600);
			
		return false;	
	}else{
		if(!validateEmail($basket_email)){
			$('#basket_email').addClass('error');
			$('#basket_email').focus();
			setTimeout(function () {$("#basket_email").removeClass('error');	}, 2600);
			
			alert('Введите Email правильно');
			return false;	
		}
	}
	
	if($("#delivery_rc").prop("checked")){
		var $index = $('#select_rc_value').val();
		if($index == ''){
			$('#select_rc_value').addClass('error');
			$('#select_rc_value').focus();
			setTimeout(function () {$("#select_rc_value").removeClass('error');	}, 2600);
			
			return false;	
		}
		
	}
	
	if($("#delivery_post").prop("checked")){
		
		var $index = $('#postindex').val();
		if($index == ''){
			$('#postindex').addClass('error');
			$('#postindex').focus();
			setTimeout(function () {$("#postindex").removeClass('error');	}, 2600);
			return false;	
		}
		
		var $index = $('#b_adres').val();
		if($index == ''){
			$('#b_adres').addClass('error');
			$('#b_adres').focus();
			setTimeout(function () {$("#b_adres").removeClass('error');	}, 2600);
			return false;	
		}
	
	}
	return true;
}

/*menu*/

var mobile_menu = function() {
  		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).next().removeClass('active');
		}else{
			$(this).addClass('active');
			$(this).next().addClass('active');
		}
	  return false;
};
var desktop_menu = function() {
		if($(this).hasClass('active')){
			
		}else{
			$('.one').removeClass('active');
			$('.second').removeClass('active');
			
			if($(this).hasClass('drop_down')){
				$(this).addClass('active');
				$(this).next().addClass('active');
			}
		}
	  return false;
};

function check_menu(){
		width=window.innerWidth;
		if(width<1000){
			$('#top_menu .drop_down').unbind('click'); // удалим обработчик
			$('#top_menu .drop_down').click(mobile_menu);
			$('.one').unbind('mouseover'); // удалим обработчик
		}else{
			$('.one').unbind('mouseover'); // удалим обработчик
			$('.one').mouseover(desktop_menu);	
			$('#top_menu .drop_down').unbind('click'); // удалим обработчик
		}	
}
jQuery(document).ready(function() {	 
	check_menu();
	$(window).resize(function(){
	  check_menu();
	});

/*desktop*/
	$('.catalog_button').click(function(){
		
		if($('#top_menu_container').hasClass('active')){
			$('#top_menu_container').removeClass('active');
			$('.one').removeClass('active');
			$('.second').removeClass('active');		
			$('body').unbind('click');
		}else{
			$('#top_menu_container').addClass('active');
			
			$('#top_menu').click(function(event){
			event.stopPropagation();
			});
		
			$('body').click(function(event){
				$('#top_menu_container').removeClass('active');
				$('.one').removeClass('active');
				$('.second').removeClass('active');
				$('body').unbind('click');
			});
			
			
		}
	  return false;
	});
	
	
	//$('.one').mouseover(desktop_menu);

	
	
	/*mobile*/
	$('#but_filtr').click(function(){
	  filtr_toggle();
	  return false;
	});	 
	$('.catalog_button').click(function(){
	  menu_toggle();
	  return false;
	});

	//$('#top_menu .drop_down').click(mobile_menu);
});


function filtr_toggle(){
	if($('#top_menu').hasClass('menu_show')){
		menu_toggle();
		return false;
	}
	if($('#menu').hasClass('menu_show')){
		$('#menu').removeClass('menu_show');
		$('#menu').animate({left: '-252'}, 500);
	}else{
		$('#menu').addClass('menu_show');
		$('#menu').animate({left: '0'}, 500);
		
		event.stopPropagation();
		$('#menu').click(function(event){
			event.stopPropagation();
		});
		$('body').click(function(event){
			HideFiltr();
		});
	}	
}
function HideFiltr(){
	$('#menu').removeClass('menu_show');
	//$('#menu').animate({left: '-252'}, 500);
	$('.page-content').removeClass('move_right');
	$('body').unbind('click');
}


function menu_toggle(){
	if($('#menu').hasClass('menu_show')){
		filtr_toggle();
		return false;
	}
	
	if($('#top_menu').hasClass('menu_show')){
		$('#top_menu').removeClass('menu_show');
		//$('#top_menu').animate({left: '-252'}, 500);
		//$('.page-content').animate({left: '0'}, 500);
		$('.page-content').removeClass('move_right');
		
	}else{
		$('#top_menu').addClass('menu_show');
		//$('#top_menu').animate({left: '0'}, 500);
		//$('.page-content').animate({left: '252'}, 500);
		$('.page-content').addClass('move_right');
		event.stopPropagation();
		$('#top_menu').click(function(event){
			//event.stopPropagation();
		});
		
		$('body').click(function(event){
			HideMenu();
		});
	}	
}
function HideMenu(){
	$('#top_menu').removeClass('menu_show');
	//$('#top_menu').animate({left: '-252'}, 500);
	$('.page-content').removeClass('move_right');
	$('body').unbind('click');
}


function ajax_f(id) { //Ajax отправка формы
	
	tel = $('#tel-'+id).val();
	if(tel == ''){
		$("#tel-"+id).addClass('error');
		$("#tel-"+id).focus();
		setTimeout(function () {
			$("#tel-"+id).removeClass('error');
		}, 2600);
		return false;
	}

	var msg = $("#form-"+id).serialize();
	$.ajax({
		type: "POST",
		url: "/ajax/callback.php",
		data: msg,
		success: function(data){
			$('#w'+id).hide();
			$("#name-"+id).val('');
			$("#email-"+id).val('');
			$("#tel-"+id).val('');
			$('#res_dialog').css('top',$(window).scrollTop()+100);
	        $('#res_dialog').show();
			$('#shadow').show();
metrika_goal('callback');
/*
			setTimeout(function(){
				$('#res_dialog').hide();
				$('#shadow').hide();
			},3700);*/
		},
		error:  function(xhr, str){
			alert("Возникла ошибка!");
		}
	});
}
function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
function ajax_e(id) { //Ajax отправка формы

	tel = $('#email-'+id).val();
	if(tel == ''){
		$("#email-"+id).addClass('error');
		$("#email-"+id).focus();
		setTimeout(function () {
			$("#email-"+id).removeClass('error');
		}, 2600);
		return false;
	}
	var msg = $("#form-"+id).serialize();
	//msg.Push({"name":"имя","value":"значение" });
	$.ajax({
		type: "POST",
		url: "/ajax/callback.php",
		data: msg,
		success: function(data){
			$('#w'+id).hide();
			$("#name-"+id).val('');
			$("#email-"+id).val('');
			$("#tel-"+id).val('');
			$('#res_dialog').css('top',$(window).scrollTop()+100);
	        $('#res_dialog').show();
			$('#shadow').show();

			/*setTimeout(function(){
				$('#res_dialog').hide();
				$('#shadow').hide();
			},3700);*/
		},
		error:  function(xhr, str){
			alert("Возникла ошибка!");
		}
	});
}
jQuery.fn.exists = function() {
   return $(this).length;
}


function ShowWin(id,tit){
		$('#w'+id).css('top',$(window).scrollTop()+100);
      	$('#type-'+id).val(tit);
        $('#w'+id).show().css('opacity', '0').animate({opacity: '1'}, 500);
        $('#shadow').show();

		event.stopPropagation();
		$('#form-'+id).click(function(event){
			event.stopPropagation();
		});
		$('body').click(function(event){
			HideWin(id);
		});

}
function HideWin(id){
	$('#w'+id).hide();
	$('#shadow').hide();
	$('#res_dialog').hide();
	$('body').unbind('click');
}
jQuery(document).ready(function() {
	
	$('.vakas-dialog').click(function(){
		$id = $(this).data('id');
		$type = $(this).data('type');
		ShowWin($id,$type);
		return false;
	});
	
});

function check_reg(form){	
		
		if(form.mail.value == ''){
			form.mail.focus();
			alert('Поле "E-mail" не заполенно');
			return false;
		}else{		
			if(!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(form.mail.value)){
				form.mail.focus();
				alert('E-mail введен неправильно');
				return false;
			}	
		}
		
		if(form.pass.value == ''){
			form.pass.focus();
			alert('Поле "Пароль" не заполенно');
			return false;
		}else{
			if(form.pass.value != form.pass2.value){
				form.pass.focus();
				alert('Пароли не совпадают');
				return false;
			}
			
			
		}
		if(form.code.value == ''){
			form.code.focus();
			alert('Не указан код с  картинки');
			return false;
		}
		
		
		
		return true;
	}
