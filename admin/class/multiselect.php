<?
header('Content-Type: text/html; charset=utf-8');
$inc_path = '../';
include($inc_path."class/config.php");
include($inc_path."class/query.php");
include($inc_path."class/utils.php");
include($inc_path."class/catalog.php");
$q = new query();
$table=get_param('table');
$f1=get_param('f1');
$f2=get_param('f2');
$f3=get_param('f3');
$val=get_param('val');
$val = explode(',',$val);
?><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache">	
<title>Vakas CMS</title>
<style>
input[type="checkbox"] {
	width:16px;
	height:16px;
	padding:0px;
	color:#F60;
	margin:0px;
	border:1px solid #E5E5E5;
	background:#FFF;
	box-shadow:0 0 2px #999;
	-moz-box-shadow:0 0 2px #999;
	-webkit-box-shadow:0 0 2px #999;
}

</style>
</head>
<?
$catalog = new c_catalog('Каталог',$table ,$f1,$is_rank=1, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$mscript = '';
function draw_catalog($parent){
	global $catalog,$table,$val,$mscript;
	$q = new query();
	
	if($catalog->has_children($parent)){
	
		$cat = $q->select1("select id,name from ".$table." where id=".$parent);
		
		if($parent !=0){ 
			echo '<div onclick="showhide(\'cid'.$cat['id'].'\')" style="cursor:pointer;">+'.$cat['name'].'</div>';
			echo '<div style="padding-left:10px;" id="cid'.$cat['id'].'">';
		}else{
			echo '<div>';
		}
		$cats = $catalog->children($parent);
		foreach($cats as $row){
			draw_catalog($row['id']);
		}
		echo '</div>';
		
	
	}else{
	
		$cat = $q->select1("select id,name from ".$table." where id=".$parent);
		if($cat !=0){
			echo '<input type="checkbox" name="checkbox[]" value="'.$cat['id'].'"';
			if( in_array($cat['id'],$val)) echo ' checked';
			echo '>'.$cat['name'].'<br>';
			$mscript .= 'mmas['.$cat['id'].']="'.$cat['name'].'";
			';
		}	
	}

}
echo '<form name="form1" method="post" action="" style="margin:10px;">';
draw_catalog(0);
?>

<br />
<script>
mmas = Array();
<? echo $mscript; ?>
function AllDone(oForm, cbName)
{
	var str = '', nsrt = '';
	var f = 1;
	for (var i=0; i < oForm[cbName].length; i++){
		if(oForm[cbName][i].checked){
			if(f==1){
				f=0;
			}else{
				nsrt = nsrt + ", ";
				str = str+",";
			}
			str	= str+oForm[cbName][i].value;
			nsrt = nsrt+mmas[oForm[cbName][i].value];
		}
	}
	var y;
	y=document.getElementById('<? echo $f3;?>');
	y.value = str;
	$('#<? echo $f2;?>').html(nsrt);
	$.fn.colorbox.close()
}

</script>

<input type="button" value="Выбрать" class="btn_yellow" onclick="AllDone(this.form,'checkbox[]');">
<input type="button" id="Login" value="Отмена" class="btn_blue" onclick="$.fn.colorbox.close()">
</form>

