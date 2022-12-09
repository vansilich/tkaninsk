<?
$inc_path = "../";
$_menu_type = 'admin';
$_menu_active = 'templates';

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
$cur_file = 'template_edit.php';
$i=1;

$tid = get_param('tid');
$template = $q->select1("select * from ".$prefix."templates where id=".to_sql($tid));


$mas['name'] = $template['name'];
$mas['filename']= $inc_path.'templates/'.$template['id'].$template['file_ext'];


echo '<div class="sub_menu">
<a href="./templates.php">Назад</a>
';

echo '</div>';

$action = get_param('action','edit');
/************ EDIT *****************/
if($action == 'edit' && !empty($template)){
	$tmpl = file_get_contents($mas['filename']);
	$tmpl = str_replace('</textarea>','&lt;/textarea>',$tmpl);
	echo '<h1>'.$mas['name'].'</h1>';
	echo '<form action="'.$cur_file.'" method="post">
	<input type="hidden" name="tid" value="'.$tid.'">
	<input type="hidden" name="action" value="save">';

echo '<br><input type="submit" value="Сохранить" class="btn"> <input type="button" value="Отмена" onclick="document.location.href=\''.$cur_file.'\';" class="btn"><br><br>';

		echo '<div class="border"><textarea style="width:100%;height:500px;" name="text" id="text">'.$tmpl.'</textarea>
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

	echo '<br><input type="submit" value="Сохранить" class="btn"> <input type="button" value="Отмена" onclick="document.location.href=\''.$cur_file.'\';" class="btn">';
	echo '</form>';
}
/************ END EDIT *****************/

/************ SAVE *****************/
if($action == 'save' && !empty($tid)){
	$tmpl = file_get_contents($mas['filename']);

	$text = get_param('text');		
	$text = str_replace('&lt;/textarea>','</textarea>',$text);
	$q->insert("insert into ".$prefix."history_edit set
	type='template',
	file_name = ".to_sql($mas['filename']).",
	date_edit=NOW(),
	user_id=".to_sql($_SESSION["admin_info"]['id']).",
	text=".to_sql($text).",
	text_before=".to_sql($tmpl)."
	");	
	
	if (!$handle = fopen($mas['filename'], 'w+')) {
		echo "Ошибка создания файла (".$mas['filename'].")";
		return -1;
	}
	if (fwrite($handle, stripslashes($text)) === FALSE) {
		echo "Cannot write to file (".$mas['filename'].")";
		return -2;
	}
	fclose($handle);
	header('location: template_edit.php?tid='.$tid.'&action=success');
}
if($action == "success"){
	echo '<div class="msg">Изменения сохранены.</div>';
}
/************ END SAVE *****************/



include($inc_path."class/bottom_adm.php");
?>
