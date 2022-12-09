<? if (!defined("_proto_inc")) : ?>
<? define("_proto_inc",1); ?>
<?php

class cProtoblock{
  var $caption;     //название таблицы
  var $table;         //имя таблицы в БД
  var $is_add;      // 0 - запрет добавлений, 00 - неограничено, 1 и более - число максимально возможных рядов
  var $is_edit;      // 0 - запрет изменение,
  var $is_del;      // 0 - запрет удаление, 
  var $id_key;  // имя уникального id записи
  var $where = '';  // условие если нужно
  var $page_size;  //количество строк на странице
  var $actions = Array();
  var $cols = Array();

	/* конструктор */
	function cProtoblock($caption='Протоблоки', $table="", $id_key='id' , $is_add=1,$is_edit=1,$is_del=1){
		global $prefix;		
		if(empty($table)) $table = $prefix.'protoblocks';
		$this->caption = $caption;
		$this->table = $table;	
		$this->id_key=$id_key;		
		$this->is_add = $is_add;
		$this->is_edit = $is_edit;
		$this->is_del = $is_del;
		$this->page_size = 100;
		if(!is_dir('protoblocks'))
			mkdir('protoblocks');
	}
	function settings($where){
		$this->where = $where;
	}
	function set_page_size($page_size){
		$this->page_size = $page_size;
	}
	function insertcol($caption, $name, $list, $edit, $coltype, $param1 = '',$param2 = '',$param3 = ''){
		$indx = sizeof($this->cols)+1;
		$this->cols[$indx]['caption'] = $caption;
		$this->cols[$indx]['name'] = $name;
		$this->cols[$indx]['list'] = $list;
		$this->cols[$indx]['edit'] = $edit;
		$this->cols[$indx]['coltype'] = $coltype;
		$this->cols[$indx]['param1'] = $param1;
		$this->cols[$indx]['param2'] = $param2;
		$this->cols[$indx]['param3'] = $param3;
		// caption - заголовок таблицы (рус.)
		// name - имя столбца (англ.)
		// list - если true, поле отображается в общем списке
		// edit - если true, поле редактируется
		// coltype - тип столбца
		//  $param1 = '',$param2 = '',$param3  - параметры столбца
		// colparams - параметры столбца
		//$this->cols[$indx]['colparams'] = $colparams;
	}
	function insert_action($caption, $name, $id_name){
		$indx = sizeof($this->actions)+1;
		$this->actions[$indx]['caption'] = $caption;
		$this->actions[$indx]['name'] = $name;
		$this->actions[$indx]['id_name'] = $id_name;	
		// caption - заголовок action (рус.)
		// name - имя файла(англ.)
		//	id - имя id в новом файле
	}
	function insertparam($param, $value){
		$this->insertcol('', $param, 0, 0, 'parametr' , $value ,$param2 = '',$param3 = '');
	}

/*	function reorder(){
		$q=new query();	
		$sql = 'select '.$this->id_key.' from '.$this->table;
		if($this->where) $sql .= ' where '.$this->where;
		$sql .= ' order by rank';
		$OrdList = $q->select($sql);		
		$n=1;
			foreach ($OrdList as $row){
			$sql = 'update '.$this->table.' set rank = '.$n.' where '.$this->id_key.' =  '.$row[$this->id_key];
			$q->exec($sql);		
			$n++;			
		}
	}
*/
	function draw(){
		global $inc_path,$PHP_SELF;
		$q=new query();	


		$reorder = get_param('reorder');
		if($reorder == "true" ){
			$this->reorder();
		}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// ADD ///////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$tmode = get_param('tmode');
		if($tmode == "add" ){
			
			echo '<form method="POST" enctype="multipart/form-data"><input type="hidden" name="tmode" value="add_row">
			<input type="submit" value="Добавить"   class="btn" ><table border="1">';
			foreach($this->cols as $k=>$v){
				if($this->cols[$k]['edit']){
					switch($this->cols[$k]['coltype']){

						case 'text' : 
							echo '<tr><td valign="top" >'.$this->cols[$k]['caption'].'</td><td valign="top" ><input type="text" name="new_'.$this->cols[$k]['name'].'" size="'.$this->cols[$k]['param1'].'" maxlength="'.$this->cols[$k]['param2'].'"></td></tr>';
							break;
						case 'textarea' : 
							echo '<tr><td valign="top" >'.$this->cols[$k]['caption'].'</td><td valign="top" ><textarea name="new_'.$this->cols[$k]['name'].'"  cols="'.$this->cols[$k]['param1'].'" rows="'.$this->cols[$k]['param2'].'"></textarea></td></tr>';
							break;	
				


					}
				}
			}
			echo '</table>';

			echo '<input type="submit" value="Добавить"   class="btn" ></form>';
			
				echo '<form method="POST">
				<input type="hidden" name="tmode" value="">
				<input type="submit" value="Отмена"  class="btn" ></form>';




		}
/////////////////////////////////////////////////////////end ADD//////////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////ADD INSERT///////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($tmode == "add_row" ){
			$sql = "insert into ".$this->table." set ";
			foreach($this->cols as $k=>$v){
				if($this->cols[$k]['coltype'] == 'parametr'){
					$sql .= " ".$this->cols[$k]['name']." = ".to_sql($this->cols[$k]['param1']).",";
				}

				if($this->cols[$k]['edit']){
					switch($this->cols[$k]['coltype']){
						case 'text' : 
									$sql .= " ".$this->cols[$k]['name']." = ".to_sql(get_param('new_'.$this->cols[$k]['name'])).",";
									break;
						case 'textarea' : 
									$sql .= " ".$this->cols[$k]['name']." = ".to_sql(get_param('new_'.$this->cols[$k]['name'])).",";
									break;	
					}
				}
			}
			$sql=substr($sql, 0, strlen($sql)-1);
			$new_id = $q->insert($sql);	
			mkdir('protoblocks/'.$new_id);
			$tmode = 'draw';
		}
/////////////////////////////////////////////////////////end ADD INSERT///////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////EDIT/////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($tmode == "edit" ){
			$id_to_edit = array();//массив id которые нужно отредактировать
			foreach($_POST as $k=>$v){
				if (substr($k,0,9) == 'ch_action'){
					$id_to_edit[count($id_to_edit)] = substr($k, 9);
				}

			}
			$all_ids = implode("|", $id_to_edit);		
			echo '<form method="POST" >
			<input type="hidden" name="tmode" value="">
			<input type="submit" value="Отмена"  class="btn" ></form>';

			echo '<form method="POST" enctype="multipart/form-data">
			<input type="submit" value="Изменить"  class="btn" >
			<table border="0"  width="100%">
			<input type="hidden" name="tmode" value="edit_rows">
			<input type="hidden" name="rows_to_edit" value="'.$all_ids.'">';
			

		

			foreach($id_to_edit as $id_row){
				echo '<tr><td  width="100%">';

				$sql = "select ";
				foreach($this->cols as $k=>$v){
					if($this->cols[$k]['edit']){
						$sql.=$this->cols[$k]['name'];
						$sql.=', ';
					}
				}
				$sql.= $this->id_key;
				$sql.= " from ".$this->table." where ". $this->id_key."=".to_sql($id_row)." ";
				$data = $q->select1($sql);

				echo '<table border="1"  width="100%">';
				foreach($this->cols as $k=>$v){
					if($this->cols[$k]['edit']){
						switch($this->cols[$k]['coltype']){
							case 'text' : 
									echo '<tr><td valign="top" >'.$this->cols[$k]['caption'].'</td><td valign="top" ><input type="text" name="edit_'.$id_row.'_'.$this->cols[$k]['name'].'" value="'.my_htmlspecialchars($data[$this->cols[$k]['name']]).'" size="'.$this->cols[$k]['param1'].'" maxlength="'.$this->cols[$k]['param2'].'"></td></tr>';
										break;
							case 'textarea' : 
									echo '<tr><td valign="top" >'.$this->cols[$k]['caption'].'</td><td valign="top" ><textarea name="edit_'.$id_row.'_'.$this->cols[$k]['name'].'"   cols="'.$this->cols[$k]['param1'].'" rows="'.$this->cols[$k]['param2'].'">'.$data[$this->cols[$k]['name']].'</textarea></td></tr>';
										break;			
							

						}
					}
				}
				echo '</table>';

				echo '</td></tr>';				
				echo '<tr><td><hr></td></tr>';

			}
			echo '</table>';
			echo '<input type="submit" value="Изменить"  class="btn" ></form>';

			echo '<form method="POST">
			<input type="hidden" name="tmode" value="">
			<input type="submit" value="Отмена"  class="btn" ></form>';




		}
/////////////////////////////////////////////////////////end EDIT///////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////UPDATE//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($tmode == "edit_rows" ){
			$rows_to_edit = get_param('rows_to_edit');
			$id_to_edit = explode("|", $rows_to_edit);
			foreach($id_to_edit as $id_row){
				if(!$id_row) continue;
				$sql = "update  ".$this->table." set ";

				foreach($this->cols as $k=>$v){
					if($this->cols[$k]['edit']){
						switch($this->cols[$k]['coltype']){
							case 'text' : $sql .= " ".$this->cols[$k]['name']." = ".to_sql(get_param('edit_'.$id_row.'_'.$this->cols[$k]['name'])).",";
											break;
							
							case 'textarea' : $sql .= " ".$this->cols[$k]['name']." = ".to_sql(get_param('edit_'.$id_row.'_'.$this->cols[$k]['name'])).",";
											break;	
						}
					}
				}
				$sql=substr($sql, 0, strlen($sql)-1);
				$sql .= " where ". $this->id_key."=".to_sql($id_row)." ";
				$q->exec($sql);
			}
			$tmode = 'draw';
		}
/////////////////////////////////////////////////////////end UPDATE///////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////DELETE//////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($tmode == "del" ){
			$id_to_edit = array();//массив id которые нужно удалить
			foreach($_POST as $k=>$v){
				if (substr($k,0,9) == 'ch_action'){
					$id_to_edit[count($id_to_edit)] = substr($k, 9);
				}

			}
			foreach($id_to_edit as $id_row){
				if(!$id_row) continue;				

				$sql = "delete from  ".$this->table."  ";
				$sql .= " where ". $this->id_key."=".to_sql($id_row)." ";
				$q->exec($sql);
			}
			$tmode = 'draw';
		}
/////////////////////////////////////////////////////////end DELETE///////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// DRAW////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if($tmode != "edit" && $tmode != "delete" && $tmode != "add" && $tmode != "add_row"){
			echo '<h2>'.$this->caption.'</h2>';
			$sql = "select ";
			foreach($this->cols as $k=>$v){
				if($this->cols[$k]['list']){
					$sql.=$this->cols[$k]['name'];
					$sql.=', ';
				}
			}
			
			$sql.= $this->id_key;

			$page_size = $this->page_size;
			$page_name = 'page';
			$q_num = "select count(".$this->id_key.") as number from ".$this->table;
			if($this->where) $q_num .= " where ".$this->where;
			$num = $q->select1($q_num);			

			$total_number = $num['number'];
			$totalpages = (int)(($total_number -1)/$page_size);
			$par_page_name = get_param($page_name);
			if( $totalpages < (int)$par_page_name ) 
				$par_page_name = $totalpages;

			$sql.= " from ".$this->table;
			if($this->where) $sql .= " where ".$this->where;
			
			$sql.= " LIMIT ".(($par_page_name)*$page_size).", ".$page_size;
			$data = $q->select($sql);
			draw_pages($page_size , $total_number, $page_name, "index.php" , '');

			echo '<form method="POST" name="main_form" id="main_form" >
			<input type="hidden" name="tmode" id="tmode" value="">
			<input type="hidden" name="id_table_row" id="id_table_row" value="">';

			echo '<table><tr>';
			if($this->is_add){
				echo '<td>
				<input type="button"  class="btn" value="Добавить" onclick="';
				echo 'ActionTable(\'add\')"></td>';
			}
			if($this->is_edit){
				echo '<td>
				<input type="button" class="btn" value="Изменить" onclick="do_edit();"></td>';
			}
			if($this->is_del){
				echo '<td>
				<input type="button" class="btn" value="Удалить" onclick="do_del();"></td>';
			}
			echo '</tr></table>';

			echo '<table class="simptable"><tr class="head_tr">';
			if($this->is_edit || $this->is_del)
				echo '<td align="center"><div onclick="Invert()" style="cursor:hand"><b>*</b></div></td>';
			echo '<td>'.$this->id_key.'</td>';
			foreach($this->cols as $k=>$v){
				if($this->cols[$k]['list']){
					echo '<td>'.$this->cols[$k]['caption'].'</td>';
				}
			}
			if(sizeof($this->actions)>0 ){
				echo '<td>Действие</td>';
			}
			echo '</tr>';
			$for_script = '';
			
			$nrow=1;
			foreach($data as $row){
				$for_script .= $row[$this->id_key].'|';
				echo '<tr ';
				if($nrow==1){
					$nrow =2;
					echo 'class="row1"';
				}else{
					$nrow =1;
					echo 'class="row2"';
				}
				echo ' >';
				echo '<td valign="top" ><input type="checkbox" name="ch_action'.$row[$this->id_key].'" id="ch_action'.$row[$this->id_key].'"></td>';
				echo '<td valign="top" >'.$row[$this->id_key].'</td>';
				foreach($this->cols as $k=>$v){
					if($this->cols[$k]['list']){
						switch($this->cols[$k]['coltype']){
							case 'text' : echo '<td valign="top" >'.$row[$this->cols[$k]['name']];
										if(empty($row[$this->cols[$k]['name']]))
											echo '&nbsp;';
										echo '</td>';
										break;
							case 'textarea' : echo '<td valign="top" >'.$row[$this->cols[$k]['name']];
										if(empty($row[$this->cols[$k]['name']]))
											echo '&nbsp;';
										echo '</td>';
										break;							

						}
					}
				}
				if(sizeof($this->actions) > 0){
					echo '<td valign="top" >';
					
					$page_name_str = basename($PHP_SELF);
					foreach($this->actions as $act){
						echo '<a href="'.$act['name'].'.php?page_back='.$page_name_str.'&'.$act['id_name'].'='.$row[$this->id_key].'">'.$act['caption'].'</a><br>';
					}

					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</table>';

			
			echo '<table><tr>';
			if($this->is_add){
				echo '<td>
				<input type="button"  class="btn" value="Добавить" onclick="';
				echo 'ActionTable(\'add\')"></td>';
			}
			if($this->is_edit){
				echo '<td>
				<input type="button" class="btn" value="Изменить" onclick="do_edit();"></td>';
			}
			if($this->is_del){
				echo '<td>
				<input type="button" class="btn" value="Удалить" onclick="do_del();"></td>';
			}
			echo '</tr></table></form>';




			echo "
			<script>
function Invert(){
";

$data= explode('|' ,$for_script);
foreach($data as $row){
	if($row){
		echo 'if(main_form.ch_action'.$row.'.checked) main_form.ch_action'.$row.'.checked= false;
				else main_form.ch_action'.$row.'.checked = true;';
	}
}
echo '}';

if($this->is_edit){
	echo 'function do_edit(){';
	echo 'flag = 0;';			
	$data= explode('|' ,$for_script);
	foreach($data as $row){
		if($row){
			echo 'if(main_form.ch_action'.$row.'.checked) flag=1;';
		}
	}
	echo 'if(flag==0){
		alert(\'Не выбраны записи\');				
		return;
	}';
	echo 'ActionTable(\'edit\'); }';
}
if($this->is_del){
	echo 'function do_del(){';
	echo 'flag = 0;';			
	$data= explode('|' ,$for_script);
	foreach($data as $row){
		if($row){
			echo 'if(main_form.ch_action'.$row.'.checked) flag=1;';
		}
	}
	echo 'if(flag==0){
		alert(\'Не выбраны записи\');				
		return;
	}';
	echo 'ActionTable(\'del\'); }';
}












echo "			
function an_action(id,act){
	main_form.id_table_row.value = id;
	main_form.tmode.value = act;
	main_form.submit();
}
function ActionTable(act){	
	if(act == 'del'){
		if(!confirm('удалить выбранные записи?') ) return;
	}
	main_form.tmode.value = act;
	main_form.submit();
}
</script>
			";
		}
	}
	/////////////////////////////////////////////////////////end  DRAW///////////////////////////////////////////////////////////////////


}


?>
<? endif; ?>