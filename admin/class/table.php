<? if (!defined("_table_inc")) : ?>
<? define("_table_inc",1); ?>
<?php
class cTable{
  var $caption;     //название таблицы
  var $table;         //имя таблицы в БД
  var $is_rank;     //1 - у каждого элемента таблицы есть ранг
  var $is_status;     //1 - у каждого элемента таблицы есть status
  var $is_add;      // 0 - запрет добавлений, 00 - неограничено, 1 и более - число максимально возможных рядов
  var $is_edit;      // 0 - запрет изменение,
  var $is_del;      // 0 - запрет удаление, 
  var $id_key;  // имя уникального id записи
  var $where = '';  // условие если нужно
  var $order = '';  // сортировка если нужно  
  var $draw_params;
  var $actions = Array();	
  var $cols = Array();
  var $params = Array();  
  var $after_add = Array();
  var $before_add = Array();
  var $after_update = Array();
  var $before_update = Array();
  var $after_delete = Array();
  var $before_delete = Array();
  var $columns;     //поля таблицы
  var $page_size;  //количество строк на странице
  var $select_sql_text; //sql для выбора значении если это не выборка не из 1 таблицы
	/* конструктор */
	function cTable($caption, $table="", $id_key='id' , $is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1){
		$this->caption = $caption;
		$this->table = $table;	
		$this->id_key=$id_key;
		$this->is_rank = $is_rank;
		$this->is_status = $is_status;
		$this->is_add = $is_add;
		$this->is_edit = $is_edit;
		$this->is_del = $is_del;
		$this->page_size = 100;
		$this->tmode = 'tmode'.$table;
		//$this->select_sql_text = '';
	}
	function settings($where){
		$this->where = $where;
	}
	
	function select_sql($sql){
		$this->select_sql_text = $sql;
	}
	
	function order($order){
		$this->order = $order;
	}
	function set_page_size($page_size){
		$this->page_size = $page_size;
	}
	function draw_params($param){
		$this->draw_params = $param;	
	}	
	function insertcol($column){
		$this->columns[sizeof($this->columns)+1] = $column;
	}

	function insertparam($param, $value){
		$n = sizeof($this->params)+1;
		$this->params[$n]['name'] = $param;
		$this->params[$n]['value'] = $value;
	}



	function insert_action($caption, $name, $id_name, $class_name = ''){
		$indx = sizeof($this->actions)+1;
		$this->actions[$indx]['caption'] = $caption;
		$this->actions[$indx]['name'] = $name;
		$this->actions[$indx]['id_name'] = $id_name;	
		$this->actions[$indx]['class_name'] = $class_name;	
		// caption - заголовок action (рус.)
		// name - имя файла(англ.)
		//	id - имя id в новом файле
	}

	function insert_after_add($function){//функция которая должна выполнится после добавления
		$indx = sizeof($this->after_add)+1;
		$this->after_add[$indx] = $function;
		//$function имя функции которая должна выполнится
	
	}
	function insert_after_update($function){//функция которая должна выполнится после обновления
		$indx = sizeof($this->after_update)+1;
		$this->after_update[$indx] = $function;
		//$function имя функции которая должна выполнится
	
	}
	function insert_after_delete($function){//функция которая должна выполнится после удаления
		$indx = sizeof($this->after_delete)+1;
		$this->after_delete[$indx] = $function;
		//$function имя функции которая должна выполнится
	
	}

	function before_after_add($function){//функция которая должна выполнится после добавления
		$indx = sizeof($this->before_add)+1;
		$this->before_add[$indx] = $function;
		//$function имя функции которая должна выполнится
	
	}
	function insert_before_update($function){//функция которая должна выполнится после обновления
		$indx = sizeof($this->before_update)+1;
		$this->before_update[$indx] = $function;
		//$function имя функции которая должна выполнится
	
	}
	function insert_before_delete($function){//функция которая должна выполнится после удаления
		$indx = sizeof($this->before_delete)+1;
		$this->before_delete[$indx] = $function;
		//$function имя функции которая должна выполнится
	
	}

	function reorder(){
		$q=new query();	
		$sql = 'select '.$this->id_key.' from '.$this->table;
		if($this->where) $sql .= ' where '.$this->where;
		$sql .= ' order by rank';
		$OrdList = $q->select($sql);		
		$n=1;
			foreach ($OrdList as $row){
			$sql = "update ".$this->table." set rank = ".to_sql($n)." where ".$this->id_key." =  ".to_sql($row[$this->id_key]);
			$q->exec($sql);		
			$n++;			
		}
	}
	
	function reorder2(){
		$q=new query();	
		$sql = 'select '.$this->id_key.' from '.$this->table;
		if($this->where) $sql .= ' where '.$this->where;
		$sql .= ' order by rank2';
		$OrdList = $q->select($sql);
		$n=10;
			foreach ($OrdList as $row){
			$sql = "update ".$this->table." set rank2 = ".to_sql($n)." where ".$this->id_key." =  ".to_sql($row[$this->id_key]);
			$q->exec($sql);		
			$n+=10;			
		}
	}



	function after_add($new_id){//подготовка параметров и выволнения функций после добавления записи	
		$indx = sizeof($this->after_add);
		if($indx > 0){
			foreach($this->columns as $obj){
				if($obj->edit==1){
					$param = $obj->after_add_value($new_id);
					if(empty($param)) $param = "''";
					foreach($this->after_add as $k2=>$v2){
						$this->after_add[$k2] = str_replace('{'.$obj->name.'}',$param,$v2);
					}
				}				
			}	
			foreach($this->after_add as $k2=>$v2){
				$this->after_add[$k2] = str_replace('{'.$this->id_key.'}',$new_id, $v2);
			}
			foreach($this->after_add as $k=>$v){
					eval($v);
			}
		}	
	}
	
	function after_update($id_row){//подготовка параметров и выволнения функций после изменения записи	
		$indx = sizeof($this->after_update);
		if($indx > 0){
			$new_after_update = $this->after_update;
			foreach($this->columns as $obj){
				if($obj->edit==1){
				
					$param = $obj->after_update_value($id_row);
					if(empty($param)) $param = "''";
					foreach($new_after_update as $k2=>$v2){
							$new_after_update[$k2] = str_replace('{'.$obj->name.'}',$param,$v2);
					}
				}//if($this->cols[$k]['edit']){
			}//foreach($this->cols as $k=>$v){
				
			foreach($new_after_update as $k2=>$v2){
				$new_after_update[$k2] = str_replace('{'.$this->id_key.'}',$id_row, $v2);
			}
			foreach($new_after_update as $k=>$v){
					eval($v);
			}	
		}	
	}


	function after_delete($id_row,$del_params=''){//подготовка параметров и выволнения функций после удаления записи	
		$indx = sizeof($this->after_delete);
		if($indx > 0){
			$new_after_delete = $this->after_delete;				
			foreach($new_after_delete as $k2=>$v2){
				$new_after_delete[$k2] = str_replace('{'.$this->id_key.'}',$id_row, $v2);
			}			
			
			foreach($new_after_delete as $k=>$v){
			//данные полей перед удалением
					foreach($del_params as $k2=>$v2){
						$v = str_replace('{'.$k2.'}',$v2, $v);
					}
			
					eval($v);
			}	
		}	
	}
	
	
	
	
	
	
	








	function draw(){
		global $inc_path,$PHP_SELF;
		$q=new query();	



		if(get_param('reorder') == "true" ){
			$this->reorder();
		}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// ADD DRAW //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "add" ){
			
			?>
			
            
                  <form name="table_form" id="add_product" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="<? echo $this->tmode;?>" value="add_row">
                  <input type="submit" class="btn btn-success" value="Добавить">
                  <input type="button" class="btn" value="Отмена" onclick="document.getElementById('cancel_form').submit();">
                  <div class="box box-success">
                      <div class="box-header with-border">
                      <h3 class="box-title">Новая запись</h3>
                    </div>
                      <div class="box-body">
                      <?
					foreach($this->columns as $obj){
						if($obj->edit == 2){
							//echo $obj->html;
							echo $obj->get_html(0);	
						}elseif($obj->edit == 1){
							echo '
							<div class="form-group">
							<label>'.$obj->caption.'</label>
							';
							echo $obj->draw_add();
							echo '</div>';
						}
					}
					?>
                    </div>
                    </div>                    
                  <input type="submit" class="btn btn-success" value="Добавить">
                  <input type="button" class="btn" value="Отмена" onclick="document.getElementById('cancel_form').submit();">
                </form>
                 <form method="POST" id="cancel_form" name="cancel_form">
                  <input type="hidden" name="<? echo $this->tmode;?>" value="">
                </form>
                          
            <?
		}
/////////////////////////////////////////////////////////end ADD DRAW//////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////ADD INSERT////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "add_row" ){
			$sql = "insert into ".$this->table." set ";

			foreach($this->params as $p){
				$sql .= " ".$p['name']." = ".to_sql($p['value']).",";
			}
			foreach($this->columns as $obj){
				if($obj->edit==1){
					$sql .= $obj->insert();
				}
			}
			if($this->is_rank){
				$q_max = "select max(rank)+1 as max from ".$this->table;
				if($this->where) $q_max .= " where ".$this->where;
				$max = $q->select1($q_max);
				$sql .= " rank = '".$max['max']."',";
			}

			//$sql=substr($sql, 0, strlen($sql)-1);
			$sql .= " ".$this->id_key."='' ";
			
			$new_id = $q->insert($sql);

			foreach($this->columns as $obj){
				if($obj->edit==1){
					$obj->after_insert($new_id);					
				}
			}
			$this->after_add($new_id);
			$_POST[$this->tmode] = '';
		}
/////////////////////////////////////////////////////////end ADD INSERT////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////EDIT DRAW//////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "edit" ){
			$id_to_edit = array();//массив id которые нужно отредактировать
			foreach($_POST as $k=>$v){
				if (substr($k,0,9) == 'ch_action'){
					$id_to_edit[count($id_to_edit)] = substr($k, 9);
				}
			}
			$temp = get_param($this->table.'_id');
			if(!empty($temp)) $id_to_edit[count($id_to_edit)] = $temp;
			$all_ids = implode("|", $id_to_edit);		
			
			
			/****убераем из адреса tmode=edit**/
			$u = $_SERVER['REQUEST_URI'];
			$u = str_replace($this->tmode.'=edit&','',$u);
			$temp = get_param($this->table.'_id');
			$u = str_replace($this->table.'_id='.$temp.'&','',$u);

			?>

			<form method="POST" id="add_product" name="table_form" enctype="multipart/form-data">
			<input type="submit" class="btn btn-primary" value="Изменить">
			<input type="button" class="btn" value="Отмена" onclick="document.location.href='<? echo $u;?>';">
			<input type="hidden" name="<? echo $this->tmode;?>" value="edit_rows">
			<input type="hidden" name="rows_to_edit" value="<? echo $all_ids;?>">
           
			<?
			foreach($id_to_edit as $id_row){
				$sql = "select ";
				foreach($this->columns as $obj){
					if($obj->edit == 1){
						$sql.= $obj->get_edit_sql();
					}
				}	
				?>
				<div class="box box-primary">
	            <div class="box-body">
				<?			
				$sql.= $this->id_key;
				$sql.= " from ".$this->table." where ". $this->id_key."=".to_sql($id_row)." ";
				$data = $q->select1($sql);
				foreach($this->columns as $obj){
					if($obj->edit == 2){
						echo $obj->get_html($id_row);							
					}elseif($obj->edit==1){
						echo '<div class="form-group">';
						echo '<label>'.$obj->caption.'</label>';
						echo $obj->draw_edit($id_row,isset($data[$obj->name])?$data[$obj->name]:'');
						echo '</div>';
					}
				}
				?>
				</div>
	            </div>
				<?
			}
            ?>
            
			<input type="submit" class="btn btn-primary" value="Изменить">
			<input type="button" class="btn" value="Отмена" onclick="document.location.href='<? echo $u;?>'">
			</form>
			
			<?
		}
/////////////////////////////////////////////////////////end EDIT DRAW//////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////UPDATE/////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "edit_rows" ){
			$id_to_edit = explode("|", get_param('rows_to_edit'));
			foreach($id_to_edit as $id_row){
				if(!$id_row) continue;
				$sql = "update  ".$this->table." set ";
				foreach($this->columns as $obj){
					if($obj->edit==1){
						$sql .= $obj->update($id_row,$this->table , $this->id_key);
						$obj->after_update($id_row);
					}
				}
				//$sql=substr($sql, 0, strlen($sql)-1);
				$sql .= " ".$this->id_key."= ".$this->id_key." ";
				$sql .= " where ". $this->id_key."=".to_sql($id_row)." ";
				$q->exec($sql);
				$this->after_update($id_row);				
			}
			$_POST[$this->tmode] = '';
			/****убераем из адреса tmode=edit**/
			$u = $_SERVER['REQUEST_URI'];
			$u = str_replace($this->tmode.'=edit&','',$u);
			$temp = get_param($this->table.'_id');
			$u = str_replace($this->table.'_id='.$temp.'&','',$u);
			header('location: '.$u.' ');
		}
/////////////////////////////////////////////////////////end UPDATE/////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////UP/////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "up_row" ){	
			if(get_param('id_table_row')){
				$old_rank = $q->select1("select rank from ".$this->table." where ".$this->id_key."=".(get_param('id_table_row'))." ");
				$sql = "select ".$this->id_key.", rank from ".$this->table;
				if($this->where) $sql .= " where ".$this->where." and rank > ".$old_rank['rank'];
				else $sql .= " where rank > ".$old_rank['rank']; 
				$sql .=" order by rank";
				$new_rank = $q->select1($sql);
				
				if($new_rank != 0){
					$q->exec("update ".$this->table." set rank = ".$new_rank['rank']." where ".$this->id_key."=".to_sql(get_param('id_table_row'))." ");
					$q->exec("update ".$this->table." set rank = ".$old_rank['rank']." where ".$this->id_key."=".to_sql($new_rank['id'])." ");
				}
			}
			$_POST[$this->tmode] = '';
		}
/////////////////////////////////////////////////////////end UP/////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////DOWN///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "down_row" ){		
			if(get_param('id_table_row')){
				$old_rank = $q->select1("select rank from ".$this->table." where ".$this->id_key."=".to_sql(get_param('id_table_row'))." ");
				//$new_rank = $q->select1("select ".$this->id_key.", rank from ".$this->table." where rank < ".$old_rank['rank']." order by rank desc");

				$sql = "select ".$this->id_key.", rank from ".$this->table;
				if($this->where) $sql .= " where ".$this->where." and rank < ".$old_rank['rank'];
				else $sql .= " where rank < ".$old_rank['rank']; 
				$sql .=" order by rank desc";
				$new_rank = $q->select1($sql);

				if($new_rank != 0){
					$q->exec("update ".$this->table." set rank = ".$new_rank['rank']." where ".$this->id_key."=".to_sql(get_param('id_table_row'))." ");
					$q->exec("update ".$this->table." set rank = ".$old_rank['rank']." where ".$this->id_key."=".to_sql($new_rank['id'])." ");
				}
			}

			$_POST[$this->tmode] = '';
		}
/////////////////////////////////////////////////////////end DOWN///////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////HIDE///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "hide_row" ){		
			if(get_param('id_table_row')){
				$q->exec("update ".$this->table." set status=0  where ".$this->id_key."=".to_sql(get_param('id_table_row')) );
			}
			$_POST[$this->tmode] = '';
		}
/////////////////////////////////////////////////////////end HIDE///////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////SHOW///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "show_row" ){		
			if(get_param('id_table_row')){
				$q->exec("update ".$this->table." set status=1  where ".$this->id_key."=".to_sql(get_param('id_table_row') ));
			}
			$_POST[$this->tmode] = '';
		}
/////////////////////////////////////////////////////////end SHOW///////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////DELETE/////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param($this->tmode) == "del" ){
			$id_to_edit = array();//массив id которые нужно Delete
			foreach($_POST as $k=>$v){
				if (substr($k,0,9) == 'ch_action'){
					$id_to_edit[count($id_to_edit)] = substr($k, 9);
				}

			}
			$temp = get_param($this->table.'_id');
			if(!empty($temp)) $id_to_edit[count($id_to_edit)] = $temp;
			
			foreach($id_to_edit as $id_row){
				if(!$id_row) continue;

				foreach($this->columns as $obj){
					if($obj->edit==1){
						$obj->delete($id_row, $this->table, $this->id_key);
					}
				}
				$sql = "delete from  ".$this->table."  ";
				$sql .= " where ". $this->id_key."=".to_sql($id_row)." ";
				
				//извлечь данные полей перед удалением
				$sqld = "select * from  ".$this->table."  ";
				$sqld .= " where ".$this->id_key."=".to_sql($id_row)." ";
				$del_params = $q->select1($sqld);
								
				$q->exec($sql);
				$this->after_delete($id_row, $del_params);
			}
			$_POST[$this->tmode] = '';
		}
/////////////////////////////////////////////////////////end DELETE/////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// DRAW//////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(get_param($this->tmode) != "edit" && get_param($this->tmode) != "delete" && get_param($this->tmode) != "add" && get_param($this->tmode) != "add_row"){
			echo '<h2>'.$this->caption.'</h2>';
			//if(empty($this->select_sql_text)){
				$sql = "select ";
				foreach($this->columns as $obj){
					if($obj->list){
						$sql.=$obj->get_sql();
					}
				}
				if($this->is_rank){
					$sql.= ' rank, ' ;
				}
				if($this->is_status){
					$sql.= ' status, ' ;
				}
				$sql.= $this->id_key;
	
				$page_size = $this->page_size;
				$page_name = 'page'.$this->table;
				$q_num = "select count(".$this->id_key.") as number from ".$this->table;
				if($this->where) $q_num .= " where ".$this->where;
				$num = $q->select1($q_num);			
	
				$total_number = $num['number'];
				$totalpages = (int)(($total_number -1)/$page_size);
				if( $totalpages < (int)get_param($page_name) ) 
					$_GET[$page_name] = $totalpages;
	
				$sql.= " from ".$this->table;
				if($this->where) $sql .= " where ".$this->where;
				if($this->is_rank){	
					$sql.= " order by rank desc ";
					if($this->order) $sql .= " , ".$this->order;
				}else{
					if($this->order) $sql .= " order by ".$this->order;
				}
			/*}else{
				$sql = $this->select_sql_text;
				
				$page_size = $this->page_size;
				$page_name = 'page';
				$q_num = "select count(".$this->id_key.") as number from ".$this->table;
				if($this->where) $q_num .= " where ".$this->where;
				$num = $q->select1($q_num);			
	
				$total_number = $num['number'];
				$totalpages = (int)(($total_number -1)/$page_size);
				if( $totalpages < (int)get_param($page_name) ) 
				$_GET[$page_name] = $totalpages;
				
			}	*/
			
			$sql.= " LIMIT ".((get_param($page_name,0))*$page_size).", ".$page_size;
			$data = $q->select($sql);
			draw_pages_adm($page_size , $total_number, $page_name, "" , $this->draw_params);
			//draw_pages($page_size , $total_number, $page_name, "" , '');



			echo '<form method="POST" name="main_form'.$this->table.'" id="main_form'.$this->table.'" >
			<input type="hidden" name="'.$this->tmode.'" id="'.$this->tmode.'" value="">
			<input type="hidden" name="id_table_row" id="id_table_row" value="">';


			echo '<table class="table_btn"><tr>';
			if($this->is_add){
				echo '<td>
				<input type="button" class="btn btn-block btn-success" value="Добавить" onclick="';
				echo 'ActionTable'.$this->table.'(\'add\')"></td>';
			}
			if($this->is_edit == 1){
				echo '<td>
				<input type="button" class="btn btn-block btn-primary" value="Изменить" onclick="do_edit'.$this->table.'();"></td>';
			}
			if($this->is_del){
				echo '<td>
				<input type="button" class="btn btn-block btn-danger" value="Удалить" onclick="do_del'.$this->table.'();"></td>';
			}
			echo '</tr></table>';

			echo '<div class="row"><div class="col-xs-12"><div class="box"><div class="box-body">';


			echo '<table class="table table-bordered table-hover table-striped"  id="sortable'.$this->table.'"><thead><tr>';
			
			if($this->is_rank){
				echo '<td>&nbsp;</td>';
			}
			
			if($this->is_edit == 1 || $this->is_del)
				echo '<th align="center" width="20" valign="middle"><div onclick="Invert'.$this->table.'()" style="cursor:pointer; font-size:30px;line-height:10px;"><b>*</b></div></th>';
			echo '<th>'.$this->id_key.'</th>';
			foreach($this->columns as $obj){
				if($obj->list){
					echo '<th>'.$obj->caption.'</th>';
				}
			}
			if($this->is_status || sizeof($this->actions)>0 	|| $this->is_edit == 1 || $this->is_del	){
				echo '<th>Действие</th>';
			}
			echo '</thead></tr><tbody>';
			$for_script = '';
			if($this->is_rank){
				$q_min = "select min(rank) as min from ".$this->table;
				if($this->where) $q_min .= " where ".$this->where;
				$min = $q->select1($q_min);
				$q_max = "select max(rank) as max from ".$this->table;
				if($this->where) $q_max .= " where ".$this->where;
				$max = $q->select1($q_max);
			}
			$nrow=1;
			foreach($data as $row){
				$for_script .= $row[$this->id_key].'|';
				
				
				if($this->is_rank){
					echo '<tr id="idnum_'.$row[$this->id_key].'_'.$row['rank'].'">';
					echo '<td class="move"><img src="'.$inc_path.'includes/css/img/drag.png" alt="Перетащить"></td>';
				}else{
					echo '<tr id="idnum_'.$row[$this->id_key].'">';	
				}
				
				if($this->is_edit == 1 || $this->is_del){
					echo '<td valign="top" ><input type="checkbox" class="minimal" name="ch_action'.$row[$this->id_key].'" id="ch_action'.$row[$this->id_key].'"></td>';
				}
				echo '<td valign="top" >'.$row[$this->id_key].'</td>';
				foreach($this->columns as $obj){
					if($obj->list){
						echo '<td valign="top" >'.$obj->draw($row[$obj->name],$row[$this->id_key]).'</td>';
					}
				}
				if($this->is_rank  || $this->is_status || sizeof($this->actions)>0 	|| $this->is_edit == 1 || $this->is_del	){
					echo '<td valign="top"  width="134">';
					//если можно изменять вывести картинку
					if($this->is_edit == 1){
						echo '<a href="?'.$this->tmode.'=edit&'.$this->table.'_id='.$row[$this->id_key].'&'.$this->draw_params.'&'.$page_name.'='.get_param($page_name,0).'" class="btn_edit dd"><i class="fa fa-edit"></i></a> ';
					}
					//если есть статус вывести картинку
					if($this->is_status){
						if($row['status'] == 1)
							echo '<span id="status'.$row[$this->id_key].'">
							<span onclick="status_off'.$this->table.'(\''.$row[$this->id_key].'\')" style="cursor:pointer;" 
							class="btn_look dd" title="скрыть"  ><i class="fa fa-eye"></i></span></span>';
						else
							echo '<span id="status'.$row[$this->id_key].'"><span onclick="status_on'.$this->table.'(\''.$row[$this->id_key].'\')" 						style="cursor:pointer;" class="btn_lookno dd" title="показать" ><i class="fa fa-eye-slash"></i></span></span>';
					}
					/*
					//если есть ранк вывести картинку
					if($this->is_rank){						
						if($max['max'] != $row['rank'])					
							echo '<span title="Вверх" class="btn_top dd"  style="cursor:pointer;" onclick="an_action'.$this->table.'(\''.$row[$this->id_key].'\', \'up_row\')" ></span>';
							
						if($min['min'] != $row['rank'])
							echo '<span onclick="an_action'.$this->table.'(\''.$row[$this->id_key].'\', \'down_row\')" style="cursor:pointer;" 
							class="btn_bottom dd" title="Вниз"  /></span>';
						
					}*/
					
					//если можно удалять вывести картинку
					if( $this->is_del){
						echo '<a href="?'.$this->tmode.'=del&'.$this->table.'_id='.$row[$this->id_key].'&'.$this->draw_params.'&'.$page_name.'='.get_param($page_name,0).'" onclick="if(!confirm(\'Удалить запись?\') ) return false;" class="btn_del dd"><i class="fa fa-trash"></i></a>';
					}
						
					$page_name_str = basename($PHP_SELF);

					foreach($this->actions as $act){
						echo '<br style="clear:both"><a href="'.$act['name'].'.php?page_back='.$page_name_str.'&this_block_id='.get_param('this_block_id').'&'.$act['id_name'].'='.$row[$this->id_key].'" class="a_action';
						if(!empty($act['class_name'])) echo ' '.$act['class_name'];
						echo '">'.$act['caption'].'</a>';
					}

					echo '</td>';
				}
				echo '</tr>';
			}
			echo '</tbody></table>';

echo '</div></div></div></div>';//echo '<div class="row"><div class="col-xs-12"><div class="box"><div class="box-body">';

			echo '<table class="table_btn"><tr>';
			if($this->is_add){
				echo '<td>
				<input type="button" class="btn btn-block btn-success" value="Добавить" onclick="';
				echo 'ActionTable'.$this->table.'(\'add\')"></td>';
			}
			if($this->is_edit == 1){
				echo '<td>
				<input type="button" class="btn btn-block btn-primary" value="Изменить" onclick="do_edit'.$this->table.'();"></td>';
			}
			if($this->is_del){
				echo '<td>
				<input type="button" class="btn btn-block btn-danger" value="Удалить" onclick="do_del'.$this->table.'();"></td>';
			}
			echo '</tr></table></form>';


			draw_pages_adm($page_size , $total_number, $page_name, "" , $this->draw_params);


			echo "
			<script>
function Invert".$this->table."(){
	$('#main_form".$this->table." input[type=\"checkbox\"].minimal').iCheck('toggle'); 
}";

if($this->is_edit == 1){
	echo 'function do_edit'.$this->table.'(){
	var main_form;
    main_form=document.getElementById(\'main_form'.$this->table.'\');
	';
	echo 'flag = 0;';			
	$data= explode('|' ,$for_script);
	foreach($data as $row){
		if($row){
			echo 'if(main_form.ch_action'.$row.'.checked) flag=1;';
		}
	}
	echo 'if(flag==0){
		WarningWin(\'Не выбраны строки\');				
		return;
	}';
	echo 'ActionTable'.$this->table.'(\'edit\'); }';
}
if($this->is_del){
	echo 'function do_del'.$this->table.'(){
		var main_form;
		main_form=document.getElementById(\'main_form'.$this->table.'\');
	';
	echo 'flag = 0;';			
	$data= explode('|' ,$for_script);
	foreach($data as $row){
		if($row){
			echo 'if(main_form.ch_action'.$row.'.checked) flag=1;';
		}
	}
	echo 'if(flag==0){
		WarningWin(\'Не выбраны строки\');				
		return;
	}';
	echo 'ActionTable'.$this->table.'(\'del\'); }';
}


echo "


    $(function() {
        $( \"#sortable".$this->table." tbody\" ).sortable({
			cursorAt: { left: 75 },
			axis: 'y',
			handle: '.move',
			cursor: 'move',
			update: function(){
				var tA = $( \"#sortable".$this->table." tbody\" ).sortable(\"toArray\");
				$('#sortable".$this->table." tbody').sortable('disable');
				start_sort".$this->table."(tA);
         		}
		 });
    });
	
	function start_sort".$this->table."(s){	
		$.post('".$inc_path."ajax/tosort.php', {s: s, table: '".$this->table."'}, onstart_sortSuccess".$this->table." );
	}
	function onstart_sortSuccess".$this->table."(data){
		$('#sortable".$this->table." tbody').sortable('enable');
	}


function status_on".$this->table."(id){
	$.post('".$inc_path."ajax/status.php', {id_name: '".$this->id_key."', table: '".$this->table."',action: 'show',id: id}, onstatus_Success".$this->table." );	
	
}
function status_off".$this->table."(id){
	$.post('".$inc_path."ajax/status.php', {id_name: '".$this->id_key."', table: '".$this->table."',action: 'hide',id: id}, onstatus_Success".$this->table." );	
	
}
function onstatus_Success".$this->table."(data){
	text = $('#text', data);
	id = $('#id', data);
	$('#status'+id.html()).html(text.html());
}
function change_text_field(id,field_name,value){
	$.post('".$inc_path."ajax/change_text_field.php', {id_name: '".$this->id_key."',id: id, table: '".$this->table."', value: value, field_name: field_name}, onchange_text_field_Success );	
	
}
function onchange_text_field_Success(){
	//alert('ok');	
}

function update_param(name,id,val){
	$.post('".$inc_path."ajax/update_param.php', {id_name: '".$this->id_key."', table: '".$this->table."', id: id, val: val, name: name}, on_update_param_Success );	
	
}
function on_update_param_Success(data){
	text = $('#text', data);
	id = $('#id', data);
}

function an_action".$this->table."(id,act){
	var main_form;
    main_form=document.getElementById('main_form".$this->table."');
	main_form.id_table_row.value = id;
	main_form.".$this->tmode.".value = act;
	main_form.submit();
}
function ActionTable".$this->table."(act){	
	var ".$this->table.";
    main_form=document.getElementById('main_form".$this->table."');
	if(act == 'del'){
		if(!confirm('Удалить выбранные строки?') ) return;
	}
	main_form.".$this->tmode.".value = act;
	main_form.submit();
}
</script>";
		}
	}
	/////////////////////////////////////////////////////////end  DRAW///////////////////////////////////////////////////////////////////


}


?>
<? endif; ?>
