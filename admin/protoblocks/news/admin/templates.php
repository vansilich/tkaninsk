<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
?>
    <script src="<? echo $inc_path;?>class/codemirror/js/codemirror.js" type="text/javascript"></script>
    <style type="text/css">
      .CodeMirror-line-numbers {
        width: 2.2em;
        color: #aaa;
        background-color: #eee;
        text-align: right;
        padding-right: .3em;
        font-size: 10pt;
        font-family: monospace;
        padding-top: .4em;
      }
      .CodeMirror {
        border: 1px solid #eee;
        height: auto;
      }
      .CodeMirror-scroll {
        overflow-y: hidden;
        overflow-x: auto;
      }


	  .border{
	  	border: solid 1px #000000;
	  }
    </style>

<?
$cur_file = 'templates.php';
$i=1;
$mas[$i]['name'] = 'Список новостей';
$mas[$i]['filename']= '../templates/news.php';
$mas[$i]['type'] = 2;

$i++;
$mas[$i]['name'] = 'Новость';
$mas[$i]['filename']= '../templates/news_full.php';
$mas[$i]['type'] = 1;

echo '<div class="sub_menu">
<a href="./">Назад</a>
';
foreach($mas as $k=>$v){
	echo ' <a href="'.$cur_file.'?action=edit&id='.$k.'">'.$v['name'].'</a>';
}
echo '</div>';
$id = get_param('id');
$action = get_param('action');
/************ EDIT *****************/
if($action == 'edit' && !empty($id)){
	$tmpl = file_get_contents($mas[$id]['filename']);
	echo '<h1>'.$mas[$id]['name'].'</h1>';
	echo '<form action="'.$cur_file.'" method="post">
	<input type="hidden" name="id" value="'.$id.'">
	<input type="hidden" name="action" value="save">';

echo '<br><input type="submit" value="Сохранить" class="btn"> <input type="button" value="Отмена" onclick="document.location.href=\''.$cur_file.'\';" class="btn"><br><br>';

	if($mas[$id]['type'] == 1){
		echo '<div class="border"><textarea style="width:800px;height:500px;" name="text" id="text">'.$tmpl.'</textarea>
</div>
<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(\'text\', {
    height: "500px",
    parserfile: "parsexml.js",
    stylesheet: "'.$inc_path.'class/codemirror/css/xmlcolors.css",
    path: "'.$inc_path.'class/codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true
  });
</script>';

	}elseif($mas[$id]['type'] == 2){
		preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
		preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
		preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
		preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
		echo 'Начало<br>
		<div class="border"><textarea style="width:800px;height:100px;" name="tbegin" id="tbegin">'.$tmp_begin[1].'</textarea></div><br>';
		echo 'Один товар<br><div class="border"><textarea style="width:800px;height:500px;" name="trow" id="trow">'.$tmp_row[1].'</textarea></div><br>';
		echo 'Разелитель<br><div class="border"><textarea style="width:800px;height:100px;" name="tdelim" id="tdelim">'.$tmp_delim[1].'</textarea></div><br>';
		echo 'Конец<br><div class="border"><textarea style="width:800px;height:100px;" name="tend" id="tend">'.$tmp_end[1].'</textarea></div><br>
		
		
		<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(\'tbegin\', {
    height: "100px",
    parserfile: "parsexml.js",
    stylesheet: "'.$inc_path.'class/codemirror/css/xmlcolors.css",
    path: "'.$inc_path.'class/codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true
  });
  var editor2 = CodeMirror.fromTextArea(\'trow\', {
    height: "500px",
    parserfile: "parsexml.js",
    stylesheet: "'.$inc_path.'class/codemirror/css/xmlcolors.css",
    path: "'.$inc_path.'class/codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true
  });
    var editor3 = CodeMirror.fromTextArea(\'tdelim\', {
    height: "100px",
    parserfile: "parsexml.js",
    stylesheet: "'.$inc_path.'class/codemirror/css/xmlcolors.css",
    path: "'.$inc_path.'class/codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true
  });
    var editor4 = CodeMirror.fromTextArea(\'tend\', {
    height: "100px",
    parserfile: "parsexml.js",
    stylesheet: "'.$inc_path.'class/codemirror/css/xmlcolors.css",
    path: "'.$inc_path.'class/codemirror/js/",
    continuousScanning: 500,
    lineNumbers: true
  });
</script>
		';
	}
	echo '<br><input type="submit" value="Сохранить" class="btn"> <input type="button" value="Отмена" onclick="document.location.href=\''.$cur_file.'\';" class="btn">';
	echo '</form>';
}
/************ END EDIT *****************/

/************ SAVE *****************/
if($action == 'save' && !empty($id)){


	if($mas[$id]['type'] == 1){
		$text = get_param('text');		
	}elseif($mas[$id]['type'] == 2){
		$tbegin = "<!--begin-->\n".get_param('tbegin')."<!--end_begin-->\n";
		$trow = "<!--row-->\n".get_param('trow')."<!--end_row-->\n";
		$tdelim = "<!--delim-->\n".get_param('tdelim')."<!--end_delim-->\n";
		$tend = "<!--end-->\n".get_param('tend')."<!--end_end-->\n";
		$text = $tbegin.$trow.$tdelim.$tend;
	}

	if (!$handle = fopen($mas[$id]['filename'], 'w+')) {
		echo "Ошибка создания файла (".$mas[$id]['filename'].")";
		return -1;
	}
	if (fwrite($handle, stripslashes($text)) === FALSE) {
		echo "Cannot write to file (".$mas[$id]['filename'].")";
		return -2;
	}
	fclose($handle);
	header('location: templates.php?action=success');
}
if($action == "success"){
	echo '<div class="msg">Изменения сохранены.</div>';
}
/************ END SAVE *****************/

?>






<?
include($inc_path."class/bottom_adm.php");
?>