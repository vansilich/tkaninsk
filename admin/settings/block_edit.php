<?
$inc_path = "../";
$_menu_type = 'admin';
$_menu_active = 'blocks';

include($inc_path."class/header_adm.php");
?>
    <script src="<? echo $inc_path;?>class/codemirror/js/codemirror.js?v=3" type="text/javascript"></script>

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
echo '<div class="sub_menu">
<a href="./blocks.php">Назад</a>
</div>';

$cur_file = 'block_edit.php';
$i=1;

$tid = get_param('tid');
$id = get_param('id');
$block = $q->select1("select * from ".$prefix."blocks where id=".to_sql($tid));

$path = $inc_path.'protoblocks/'.$block['folder'];
$path_adm = $inc_path.'protoblocks/'.$block['folder'].'/admin';

/*
$mas['name'] = $block['name'];
$mas['filename']= $inc_path.'protoblocks/'.$block['folder'].'/index.php';
*/
$filelist = array();
if ($handle = opendir($path)) {
    while ($entry = readdir($handle)) {
        if (is_file($path.'/'.$entry)) {
            $filelist[] = $entry;
			$i++;
			$mas[$i]['name'] = $entry;
			$mas[$i]['filename']= $path.'/'.$entry;
        }
    }
    closedir($handle);
}

echo '<div class="sub_menu">';
foreach($mas as $k=>$v){
	echo ' <a href="'.$cur_file.'?action=edit&tid='.$tid.'&id='.$k.'">'.$v['name'].'</a>';
	$z = $k;
}
echo '</div>';

if ($handle = opendir($path_adm)) {
    while ($entry = readdir($handle)) {
        if (is_file($path_adm.'/'.$entry)) {
            $filelist[] = $entry;
			$i++;
			$mas[$i]['name'] = 'admin/'.$entry;
			$mas[$i]['filename']= $path_adm.'/'.$entry;
        }
    }
    closedir($handle);
}
echo '<h3>admin</h3>';
echo '<div class="sub_menu">';
foreach($mas as $k=>$v){
	if($k<=$z) continue;
	echo ' <a href="'.$cur_file.'?action=edit&tid='.$tid.'&id='.$k.'">'.$v['name'].'</a>';
}
echo '</div>';



$action = get_param('action');
/************ EDIT *****************/
if($action == 'edit' && !empty($block)){
	
	$tmpl = file_get_contents($mas[$id]['filename']);
	echo '<h1>Редактор - '.$mas[$id]['name'].'</h1>';
	$tmpl = str_replace('</textarea>','&lt;/textarea>',$tmpl);
	echo '<form action="'.$cur_file.'" method="post">
	<input type="hidden" name="id" value="'.$id.'">
	<input type="hidden" name="tid" value="'.$tid.'">
	<input type="hidden" name="action" value="save">';

echo '<br><input type="submit" value="Сохранить" class="btn btn-success"> 
<input type="button" value="Отмена" onclick="document.location.href=\''.$cur_file.'?tid='.$tid.'\';" class="btn">

<br><br>';

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

	echo '<br><input type="submit" value="Сохранить" class="btn btn-success">
	<input type="button" value="Отмена" onclick="document.location.href=\''.$cur_file.'?tid='.$tid.'\';" class="btn">';
	echo '</form>';
}
/************ END EDIT *****************/

/************ SAVE *****************/
if($action == 'save' && !empty($tid) && !empty($id)){
	$tmpl = file_get_contents($mas[$id]['filename']);

	$text = get_param('text');		
	$text = str_replace('&lt;/textarea>','</textarea>',$text);
	$q->insert("insert into ".$prefix."history_edit set
	type='block',
	file_name = ".to_sql($mas[$id]['filename']).",
	date_edit=NOW(),
	user_id=".to_sql($_SESSION["admin_info"]['id']).",
	text=".to_sql($text).",
	text_before=".to_sql($tmpl)."
	");	
	if (!$handle = fopen($mas[$id]['filename'], 'w+')) {
		echo "Ошибка создания файла (".$mas[$id]['filename'].")";
		return -1;
	}
	if (fwrite($handle, stripslashes($text)) === FALSE) {
		echo "Cannot write to file (".$mas[$id]['filename'].")";
		return -2;
	}
	fclose($handle);
	header('location: block_edit.php?tid='.$tid.'&action=success');
}
if($action == "success"){
	echo '<div class="msg">Изменения сохранены.</div>';
}
/************ END SAVE *****************/



include($inc_path."class/bottom_adm.php");
?>
