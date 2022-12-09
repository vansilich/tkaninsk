  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();



$.fn.datepicker.dates['ru'] = {
	days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
	daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб", "Вск"],
	daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
	months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
	monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
	today: "Сегодня",
    clear: "Очистить",
    format: "dd.mm.yyyy",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
	autoclose:true,
    weekStart: 1
};
$(".datepicker").datepicker( { autoclose:true,language: "ru"});

   
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
  
function DangerWin(text){
	$("#danger_window_text").html(text);
	$("#danger_window").modal('show');	
}  
function WarningWin(text){
	$("#warning_window_text").html(text);	
	$("#warning_window").modal('show');	
} 
function SampleWin(text){
	$("#simple_window_text").html(text);
	$("#simple_window").modal('show');	
}  
  
function PrimaryWin(text){
	$("#primary_window_text").html(text);
	$("#primary_window").modal('show');	
} 
function InfoWin(text){
	$("#info_window_text").html(text);
	$("#info_window").modal('show');	
} 
function SuccessWin(text){
	$("#success_window_text").html(text);
	$("#success_window").modal('show');	
} 


var cboxOptions = {
  width: '95%',
  height: '95%',
  maxWidth: '960px',
  maxHeight: '960px',
  iframe: true,
  overlayClose: false
}

$(window).resize(function(){
    $.colorbox.resize({
      width: window.innerWidth > parseInt(cboxOptions.maxWidth) ? cboxOptions.maxWidth : cboxOptions.width,
      height: window.innerHeight > parseInt(cboxOptions.maxHeight) ? cboxOptions.maxHeight : cboxOptions.height
    });
});

$(document).ready(function(){
	$(".c_iframe").colorbox(cboxOptions );
	$(".c_popup").colorbox({width:"805px",height:"100%", iframe:false,overlayClose:false});
});

function confirm_page_del(cUrl){
    if (window.confirm('Удалить страницу?')) window.location = cUrl;
}
   
function showhidepage(parid){
     var x,y;
     x=$('#page_div'+parid);
     y=$('#img_page'+parid);
     if (x.is(':hidden')){
          x.show(); 
          y.removeClass('fa-plus').addClass('fa-minus');
     }
     else {
     	x.hide(); 
		y.removeClass('fa-minus').addClass('fa-plus');
     }

}       
function showpage(parid){
     var x,y;
     x=$('#page_div'+parid);
     y=$('#img_page'+parid);
	 x.show(); 
	 y.removeClass('fa-plus').addClass('fa-minus');
}
function hide(parid){
     $('#'+parid).hide();
}
function show(parid){
     x=$('#'+parid).show();
}
function showhide(parid){
     $('#'+parid).toggle();
}
