$(document).ready(function() {
	$(".c_iframe").colorbox({width:"905px",height:"100%", iframe:true,overlayClose:false});
	$(".c_popup").colorbox({width:"805px",height:"100%", iframe:false,overlayClose:false});
});

function confirm_page_del(cUrl){
    if (window.confirm('Удалить страницу?')) window.location = cUrl;
}
   
function showhidepage(parid){
     var x,y;
     x=document.getElementById('page_div'+parid).style;
     y=document.getElementById('img_page'+parid);
     if (x.display=='none'){
          x.display='block'; 
          y.src = '/admin/img/minus.gif'; 
     }
     else {
     	x.display='none'; 
        y.src = '/admin/img/plus.gif'; 
     }

}       
function showpage(parid){
     var x,y;
     x=document.getElementById('page_div'+parid).style;
     y=document.getElementById('img_page'+parid);
          x.display='block'; 
          y.src = '/admin/img/minus.gif'; 
}
function hide(parid){
     var x,y;
     x=document.getElementById(parid).style;
     x.display='none'; 
}
function show(parid){
     var x,y;
     x=document.getElementById(parid).style;
     x.display='block';     
}
function showhide(parid){
     var x,y;
     x=document.getElementById(parid).style;
     if (x.display=='none')  {x.display='block'; }
     else {x.display='none'; }
}

function replacetextarea(base, fckname, height)
{
	var sBasePath = base;
	var oFCKeditor = new FCKeditor(fckname) ;
	oFCKeditor.BasePath	= sBasePath ;
	
	oFCKeditor.Width = '100%' ;
	oFCKeditor.Height = height ;
	
	oFCKeditor.ReplaceTextarea() ;
	x=document.getElementById('but'+fckname).style;
        x.display='none'; 

}




