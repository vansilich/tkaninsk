<? if (!defined("_catalog_inc")) : ?>
<? define("_catalog_inc",1); ?>
<?php
class c_catalog{
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
  var $params = Array();  
  var $cols = Array();
  var $after_add = Array();
  var $before_add = Array();
  var $after_update = Array();
  var $before_update = Array();
  var $after_delete = Array();
  var $before_delete = Array();
  var $columns = Array();     //поля таблицы
  var $page_size;  //количество строк на странице
	/* конструктор */
	function c_catalog($caption, $table="", $id_key='id' , $is_rank=0, $is_status=0, $is_add=1,$is_edit=1,$is_del=1){
		$this->caption = $caption;
		$this->table = $table;	
		$this->id_key=$id_key;
		$this->is_rank = $is_rank;
		$this->is_status = $is_status;
		$this->is_add = $is_add;
		$this->is_edit = $is_edit;
		$this->is_del = $is_del;
		$this->page_size = 100;
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

	function order($order){
		$this->order = $order;
	}

	function settings($where){
		$this->where = $where;
	}
	
	function select_sql($sql){
		$this->select_sql_text = $sql;
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

	function reorder($order_field = 'rank'){
		$cur_cat = (int)get_param('cur_cat',0);
		$q=new query();	
		$sql = 'select '.$this->id_key.' from '.$this->table;
		$sql .= " where parent=".to_sql($cur_cat);
		if($this->where) $sql .= ' and '.$this->where;
		$sql .= ' order by '.$order_field;
		$OrdList = $q->select($sql);		
		$n=1;
			foreach ($OrdList as $row){
			$sql = "update ".$this->table." set rank = ".to_sql($n)." where ".$this->id_key." =  ".to_sql($row[$this->id_key]);
			$q->exec($sql);		
			$n++;			
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
				$this->after_add[$k2] = str_replace('{name}',to_sql(get_param('new_name')), $this->after_add[$k2]);
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
			$new_after_update = str_replace('{name}',to_sql(get_param('edit_'.$id_row.'_name')),$new_after_update);
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


	function after_delete($id_row){//подготовка параметров и выволнения функций после удаления записи	
		$indx = sizeof($this->after_delete);
		if($indx > 0){
			$new_after_delete = $this->after_delete;				
			foreach($new_after_delete as $k2=>$v2){
				$new_after_delete[$k2] = str_replace('{'.$this->id_key.'}',$id_row, $v2);
			}
			foreach($new_after_delete as $k=>$v){
					eval($v);
			}	
		}	
	}
	
	
	
	
	function level($ctgr=0){
		if(empty($ctgr)) return -1;
		if($ctgr == $this->root()) return 1;
		$query = "select parent from ".$this->table." where  ".$this->id_key." = ".to_sql($ctgr);
		$q = new query($query);
		$temp = $q->select1();
		return 1+$this->level($temp['parent']);
	}

	function root(){
		return 0;
	}
	function has_children($parentid=0){//возвращает не 0, если есть потомки
		$query = "select id from ".$this->table." where parent = ".to_sql($parentid)." LIMIT 0,1";
		$q = new query($query);
		return ($q->select1());    
	}
	
	function children($parentid=0,$status=1){
		//if($parentid < 0 || $parentid == '') return array();
		if($this->is_rank){
		  $rank_order = " order by rank desc";
		}else{
		  $rank_order = " order by created desc";
		}
		if($this->is_status && $status==1){
			$status=" and status=1 ";
		}else{
			$status="";
		}
		$query = "select *  from ".$this->table." where parent=".to_sql($parentid).$status.$rank_order;
		$q = new query($query);
		$temp = $q->select();
		return $temp;
	}
	
	
	function parent($id=0){
		$query = "select parent from ".$this->table." where ".$this->id_key."=".to_sql($id);
		$q = new query($query);
		$temp = $q->select1();
		$parent = $temp ? $temp['parent'] : 0;
		return $parent;
	}
	
	function get_category_name($id){//имя категории
		if($id == 0) return $this->caption;
		$q = new query("select name from ".$this->table." where ".$this->id_key."=".to_sql($id));
		$temp = $q->select1();
		$name = $temp ? $temp['name'] : 0;
		return $name;
	}
	function path($ctgr=0, $in_path=0 , $delimiter = ' > '){
		if($ctgr == 0) 
			if($in_path == 1 )
				return '<li><a href="?cur_ctgr='.$ctgr.'&'.$this->draw_params.'">'.$this->get_category_name($ctgr).'</a></li>';
			else
				return '<li>'.$this->get_category_name($ctgr).'</li>';
		$query = "select parent,name from ".$this->table." where ".$this->id_key." = ".to_sql($ctgr);
		$q = new query($query);
		$temp = $q->select1();
		if($in_path == 1 )
			return $this->path($temp['parent'], 1, $delimiter).'<li><a href="?cur_cat='.$ctgr.'&'.$this->draw_params.'">'.$this->get_category_name($ctgr).'</a></li>';
		else
			return $this->path($temp['parent'], 1, $delimiter).'<li>'.$this->get_category_name($ctgr).'</li>';
	}
	
	function re_calc($cur_id=0, $lev=0, $allparent =''){	
		$q = new query();
		$q->exec("update ".$this->table." set level=".to_sql($lev+1).",all_parent=".to_sql($allparent)."  where parent=".to_sql($cur_id,'Number'));
		$cats = $q->select("select id,level,all_parent from ".$this->table." where parent=".to_sql($cur_id,'Number'));
		foreach($cats as $row){
			if(!empty($allparent)) $allp = $allparent.','.$row['id'];
			else $allp = $row['id'];
			$this->re_calc($row['id'], $row['level'], $allp);			
		}	
	}
	
	


	function draw_list2($parent, $lev, $cat_id){
			$q =  new query();
			$childs = $q->select("select id,name from ".$this->table." where parent =".$parent." order by rank desc");
			foreach($childs as $row){
				if($row['id'] == $cat_id) continue;
				echo '<option value="'.$row['id'].'">';
				for($i=0; $i<=$lev; $i++){
					echo ' &mdash;';
				}
				echo $row['name'];
				if($this->has_children($row['id'])){
					$this->draw_list2($row['id'], $lev+1, $cat_id);
				}
			}
	}





	function draw(){
		global $inc_path;
		$q=new query();
		$cur_cat = (int)get_param('cur_cat',0);
		if(!empty($cur_cat)){
			$cur_cat_parent = $this->parent($cur_cat);			
		}else{
			$cur_cat_parent = 0;
		}
		if(get_param('recalc') == 1) $this->re_calc();

		if(get_param('reorder') == "true" ){
			$this->reorder();
		}

		if(get_param('reorder') == "name" ){
			$this->reorder('name desc');
		}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// ADD DRAW //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "add" ){
			$u = $_SERVER['REQUEST_URI'];
			?>
			<form name="table_form" id="add_product" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="catmode" value="add_row">
			<input type="submit"  class="btn btn-success" value="Добавить раздел">
			<input type="button" class="btn" value="Отмена" onclick="document.location.href='<? echo $u;?>';">
			
            <div class="box box-success">
                <div class="box-header with-border">
                <h3 class="box-title">Новый раздел</h3>
              </div>
                <div class="box-body">
                <div class="form-group">
                    <label for="">Название</label>
                    <input type="text" name="new_name" class="form-control">
                  </div>
                <?
			foreach($this->columns as $obj){
				if($obj->edit == 2){
					//echo $obj->html;
					echo $obj->get_html(0);								
				}elseif($obj->edit==1){
					echo '
					<div class="form-group">
					<label for="">'.$obj->caption.'</label>
					';
					echo $obj->draw_add();
					echo '</div>';
				}
			}
			?>
              </div>
              </div> 
			<input type="submit"  class="btn btn-success" value="Добавить раздел">
			<input type="button" class="btn" value="Отмена" onclick="document.location.href='<? echo $u;?>';">
			</form>
            <?	
			
		}
/////////////////////////////////////////////////////////end ADD DRAW//////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////ADD INSERT////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "add_row" ){
		
			$temp = $q->select1("select all_parent,level from ".$this->table." where ".$this->id_key." = ".to_sql($cur_cat));
			if($temp == 0){
				$lev=1;
				$allparent=$cur_cat;
			}else{
				$lev = $temp['level']+1;
				if(!empty($temp['all_parent'])){
					$allparent=$temp['all_parent'].','.$cur_cat;
				}else{
					$allparent=$cur_cat;
				}
			}
		
			$sql = "insert into ".$this->table." set parent=".to_sql($cur_cat).",name = ".to_sql(get_param('new_name')).",
			level=".to_sql($lev).", all_parent=".to_sql($allparent).", ";

			foreach($this->params as $p){
				$sql .= " ".$p['name']." = ".to_sql($p['value']).",";
			}

			foreach($this->columns as $obj){
				if($obj->edit==1){
					$sql .= $obj->insert();
				}
			}
			if($this->is_rank){
				$q_max = "select max(rank)+1 as max from ".$this->table." where parent=".to_sql($cur_cat);
				if($this->where) $q_max .= " and ".$this->where;
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
			$_POST['catmode'] = '';
		}
/////////////////////////////////////////////////////////end ADD INSERT////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////EDIT DRAW//////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "edit" ){
			$id_to_edit = get_param('id_to_edit');//массив id которые нужно отредактировать
			
			/****убераем из адреса tmode=edit**/
			$u = $_SERVER['REQUEST_URI'];
			$u = str_replace('catmode=edit&','',$u);
			$temp = get_param('id_to_edit');
			$u = str_replace('id_to_edit='.$temp.'&','',$u);
			$u = rtrim($u,'&');
			$sql = "select name,";
				foreach($this->columns as $obj){
					if($obj->edit==1){
						$sql.= $obj->get_edit_sql();
					}
				}				
				$sql.= $this->id_key;
				$sql.= " from ".$this->table." where ". $this->id_key."=".to_sql($id_to_edit)." ";
				$data = $q->select1($sql);
			?>

			<form method="POST" id="add_product" name="table_form" enctype="multipart/form-data">
			<input type="submit" class="btn btn-primary" value="Изменить">
			<input type="button" value="Отмена" onclick="document.location.href='<? echo $u;?>';" class="btn">
			
			<input type="hidden" name="catmode" value="edit_rows">
			<input type="hidden" name="id_to_edit" value="<? echo $id_to_edit;?>">
			<div class="box box-primary">
	            <div class="box-body">
			
				<div class="form-group">
                    <label for="">Название</label>
                    <input type="text"  class="form-control" name="edit_<? echo $id_to_edit;?>_name" value="<? echo my_htmlspecialchars($data['name']);?>" size="100">
                  </div>
				<?
				foreach($this->columns as $obj){
					if($obj->edit == 2){
						//echo $obj->html;
						echo $obj->get_html($id_to_edit);								
					}elseif($obj->edit==1){
						echo '<div class="form-group">
						<label for="">'.$obj->caption.'</label>';
						echo $obj->draw_edit($id_to_edit,$data[$obj->name]);
						echo '</div>';
					}
				}
				?>
				</div></div>
			<input type="submit" class="btn btn-primary" value="Изменить">
			<input type="button" value="Отмена" onclick="document.location.href='<? echo $u;?>';" class="btn">
			</form>
			<?
		}
/////////////////////////////////////////////////////////end EDIT DRAW//////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////UPDATE/////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "edit_rows" ){
			$id_to_edit = get_param('id_to_edit');
			if(!empty($id_to_edit)){
				$sql = "update  ".$this->table." set name= ".to_sql(get_param('edit_'.$id_to_edit.'_name')).",";				
				
				foreach($this->columns as $obj){
					if($obj->edit==1){
						$sql .= $obj->update($id_to_edit,$this->table , $this->id_key);
						$obj->after_update($id_to_edit);
					}
				}
				//$sql=substr($sql, 0, strlen($sql)-1);
				$sql .= " ".$this->id_key."= ".$this->id_key." ";
				$sql .= " where ". $this->id_key."=".to_sql($id_to_edit)." ";
				$q->exec($sql);
				$this->after_update($id_to_edit);
			}
			$_POST['catmode'] = '';
			
			/****убераем из адреса catmode=edit**/
			$u = $_SERVER['REQUEST_URI'];
			$u = str_replace('catmode=edit&','',$u);
			$temp = get_param('id_to_edit');
			$u = str_replace('id_to_edit='.$temp.'&','',$u);
			$u = rtrim($u,'&');
			header('location: '.$u.' ');
			
		}
/////////////////////////////////////////////////////////end UPDATE/////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////UP/////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "up_row" ){	
			if(get_param('id_table_row')){
				$old_rank = $q->select1("select rank from ".$this->table." where ".$this->id_key."=".(get_param('id_table_row'))." ");
				$sql = "select ".$this->id_key.", rank from ".$this->table;
				$sql .= " where parent=".to_sql($cur_cat);
				if($this->where) $sql .= " and ".$this->where." and rank > ".$old_rank['rank'];
				else $sql .= " and rank > ".$old_rank['rank']; 
				$sql .=" order by rank";
				$new_rank = $q->select1($sql);
				
				if($new_rank != 0){
					$q->exec("update ".$this->table." set rank = ".$new_rank['rank']." where ".$this->id_key."=".to_sql(get_param('id_table_row'))." ");
					$q->exec("update ".$this->table." set rank = ".$old_rank['rank']." where ".$this->id_key."=".to_sql($new_rank['id'])." ");
				}
			}
			$_POST['catmode'] = '';
		}
/////////////////////////////////////////////////////////end UP/////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////DOWN///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "down_row" ){		
			if(get_param('id_table_row')){
				$old_rank = $q->select1("select rank from ".$this->table." where ".$this->id_key."=".to_sql(get_param('id_table_row'))." ");
				//$new_rank = $q->select1("select ".$this->id_key.", rank from ".$this->table." where rank < ".$old_rank['rank']." order by rank desc");

				$sql = "select ".$this->id_key.", rank from ".$this->table;
				$sql .= " where parent=".to_sql($cur_cat);
				if($this->where) $sql .= " and ".$this->where." and rank < ".$old_rank['rank'];
				else $sql .= " and rank < ".$old_rank['rank']; 
				$sql .=" order by rank desc";
				$new_rank = $q->select1($sql);

				if($new_rank != 0){
					$q->exec("update ".$this->table." set rank = ".$new_rank['rank']." where ".$this->id_key."=".to_sql(get_param('id_table_row'))." ");
					$q->exec("update ".$this->table." set rank = ".$old_rank['rank']." where ".$this->id_key."=".to_sql($new_rank['id'])." ");
				}
			}

			$_POST['catmode'] = '';
		}
/////////////////////////////////////////////////////////end DOWN///////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////HIDE///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "hide_row" ){		
			if(get_param('id_table_row')){
				$q->exec("update ".$this->table." set status=0  where ".$this->id_key."=".to_sql(get_param('id_table_row')) );
			}
			$_POST['catmode'] = '';
		}
/////////////////////////////////////////////////////////end HIDE///////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////SHOW///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "show_row" ){		
			if(get_param('id_table_row')){
				$q->exec("update ".$this->table." set status=1  where ".$this->id_key."=".to_sql(get_param('id_table_row') ));
			}
			$_POST['catmode'] = '';
		}
/////////////////////////////////////////////////////////end SHOW///////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////DELETE/////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "del" ){
			
			$id_table_row = get_param('id_table_row');
			
				if(!empty($id_table_row)){
					foreach($this->columns as $obj){
						if($obj->edit==1){
							$obj->delete($id_table_row, $this->table, $this->id_key);
						}
					}
					$sql = "delete from  ".$this->table."  ";
					$sql .= " where ". $this->id_key."=".to_sql($id_table_row)." ";
					$q->exec($sql);
					$this->after_delete($id_table_row);
				}
			
			$_POST['catmode'] = '';
		}
/////////////////////////////////////////////////////////end DELETE/////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// move //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "move" ){		
			$id_table_row = get_param('id_table_row');			
			echo '
			<form method="post">
			<input type="hidden" name="id_table_row" value="'.$id_table_row.'">
			<input type="hidden" name="catmode" value="move_cat">
			<select SIZE="20"  name="new_cat" id="new_cat">';
			echo '<option style="padding-left: 10px;" value="0">Корень</option>';
			$this->draw_list2(0,0,$id_table_row);
			echo '</select><br>';
			echo '<input type="submit" value="перенести" class="btn btn-primary">';
			echo '</form>';
			
			echo '<form method="POST">
			<input type="hidden" name="catmode" value="">
			<input type="submit" value="Отмена"  class="btn" ></form>';
		}
/////////////////////////////////////////////////////////end move//////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// move_cat //////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(get_param('catmode') == "move_cat" ){		
			$id_table_row = get_param('id_table_row');
			if(isset($_POST['new_cat'])){
				$new_cat = get_param('new_cat');
				$q->exec("update ".$this->table." set parent=".to_sql($new_cat)." where ". $this->id_key."=".to_sql($id_table_row));
				$this->re_calc();
			}
		}
/////////////////////////////////////////////////////////end move_cat//////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////// DRAW//////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$mas_action = array("edit", "delete", "add", "add_row", "move");
		$catmode = get_param('catmode'); // для класса каталога 
		$tmode = get_param('tmode'); // для класса таблицы
		
		if(!in_array($catmode,$mas_action) && !in_array($tmode,$mas_action)	){
			
			echo '<h2>'.$this->caption.'</h2>';
			$sql = "select name,";
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

			$sql.= " from ".$this->table;			
			$sql .= " where parent=".to_sql($cur_cat);
			if($this->where) $sql .= " and ".$this->where;
			if($this->is_rank){	
				$sql.= " order by rank desc ";
				if($this->order) $sql .= " , ".$this->order;
			}else{
				if($this->order) $sql .= " order by ".$this->order;
			}
			$data = $q->select($sql);
//			draw_pages($page_size , $total_number, $page_name, "" , $this->draw_params);

			echo '<form method="POST" name="catalog_main_form" id="catalog_main_form" >
			<input type="hidden" name="catmode" id="catmode" value="">
			<input type="hidden" name="id_table_row" id="id_table_row" value="">';

			echo '<table ><tr>';
			if($this->is_add){
				echo '<td>
				<input type="button"  class="btn btn-success" value="Добавить раздел" onclick="';
				echo 'ActionCat(\'add\')"></td>';
			}
			
			echo '</tr></table>';
			echo '<ol class="breadcrumb" style="background-color:transparent; margin:10px 0 0 0;">';
			//если не корень выйти на уровень выше			
			if(!empty($cur_cat)){
				echo '<li><a href="?cur_cat='.$cur_cat_parent.'&'.$this->draw_params.'" class="folder_up">
<i class="fa fa-level-up"></i></a></li>';
			}
			echo ''.$this->path($cur_cat).'</ol>';
			
			
echo '<div class="row"><div class="col-xs-12"><div class="box"><div class="box-body">';


			echo '<table class="table table-bordered table-hover table-striped" id="sortable'.$this->table.'"><thead><tr>';			
//			if($this->is_edit || $this->is_del)
				//echo '<td align="center"><div onclick="Invert()" style="cursor:pointer"><b>*</b></div></td>';
			echo '<th>&nbsp;</th><th>'.$this->id_key.'</th><th>Название</th>';
			foreach($this->columns as $obj){
				if($obj->list){
					echo '<th>'.$obj->caption.'</th>';
				}
			}
			if( $this->is_status || sizeof($this->actions)>0 	|| $this->is_edit == 1 || $this->is_del	){
				echo '<th colspan="4">Действие</th>';
			}
			echo '</thead></tr>';
			

			$for_script = '';
			if($this->is_rank){
				$q_min = "select min(rank) as min from ".$this->table." where parent=".to_sql($cur_cat);
				if($this->where) $q_min .= " and ".$this->where;				
				$min = $q->select1($q_min);
				
				$q_max = "select max(rank) as max from ".$this->table." where parent=".to_sql($cur_cat);
				if($this->where) $q_max .= " and ".$this->where;
				$max = $q->select1($q_max);
			}
			$nrow=1;
			foreach($data as $row){
				$for_script .= $row[$this->id_key].'|';
				echo '<tr id="idnum_'.$row[$this->id_key].'_'.$row['rank'].'" valign="top" >';
				echo '<td class="move"><img src="'.$inc_path.'includes/css/img/drag.png" alt="Перетащить"></td>';
				echo '<td  >'.$row[$this->id_key].'</td>';
				echo '<td ><a href="?cur_cat='.$row[$this->id_key].'&'.$this->draw_params.'"><i class="fa fa-folder cat_icon"></i> '.$row['name'].'</a></td>';
				foreach($this->columns as $obj){
					if($obj->list){
						echo '<td  >'.$obj->draw($row[$obj->name],$row[$this->id_key]).'</td>';
					}
				}
				/****действия***/
				echo '<td  >';				
				if($this->is_edit == 1){
					echo '<a href="?id_to_edit='.$row[$this->id_key].'&cur_cat='.$cur_cat.'&catmode=edit&'.$this->draw_params.'" class="btn_edit dd" title="Редактировать"><i class="fa fa-edit"></i></a>';
				
				}
				
				
				if($this->is_status){
					if($row['status'] == 1)
						echo '<span id="cat_status'.$row[$this->id_key].'">
						<span onclick="cat_status_off(\''.$row[$this->id_key].'\')" style="cursor:pointer;" 
						class="btn_look dd" title="скрыть"><i class="fa fa-eye"></i></span></span>';
					else
						echo '<span id="cat_status'.$row[$this->id_key].'"><span onclick="cat_status_on(\''.$row[$this->id_key].'\')" 						style="cursor:pointer;" class="btn_lookno dd" title="показать" ><i class="fa fa-eye-slash"></i></span></span>';
				}

				
				if($this->is_del){
					echo '<a onclick="an_cat_action('.$row[$this->id_key].',\'del\');" title="Удалить" class="btn_del dd"><i class="fa fa-trash"></i></a>';
				
				}
				echo '</td><td style="padding-top:10px;"><a href="#" onclick="an_cat_action('.$row[$this->id_key].',\'move\');return false;" class="a_action">Перенести в раздел</a>';
				
				
				if(sizeof($this->actions) > 0) echo '</td><td>';
				foreach($this->actions as $act){
					echo '<a href="'.$act['name'].'.php?'.$act['id_name'].'='.$row[$this->id_key].'&'.$this->draw_params.'"  class="a_action';
					if(!empty($act['class_name'])) echo ' '.$act['class_name'];
					echo '">'.$act['caption'].'</a><br style="clear:both">';
				}

				echo '</td>';
				/****end действия***/
				
				echo '</tr>';
			}
			echo '</table>';
			echo '</div></div></div></div>';//echo '<div class="row"><div class="col-xs-12"><div class="box"><div class="box-body">';
			
			echo '<table><tr>';
			if($this->is_add){
				echo '<td>
				<input type="button"  class="btn btn-success" value="Добавить раздел" onclick="';
				echo 'ActionCat(\'add\')"></td>';
			}
		
			echo '</tr></table></form>';



echo "	
<script>	
$(function() {
        $( \"#sortable".$this->table." tbody\" ).sortable({
			cursorAt: { left: 75 },
			axis: 'y',
			handle: '.move',
			cursor: 'move',
			update: function(){
				var tA = $( \"#sortable".$this->table." tbody\" ).sortable(\"toArray\");
				$('#sortable".$this->table." tbody').sortable('disable');
				start_cat_sort(tA);
         }});
    });
	
	function start_cat_sort(s){	
		$.post('".$inc_path."ajax/tosort.php', {s: s, table: '".$this->table."'}, onstart_cat_sortSuccess );
	}
	function onstart_cat_sortSuccess(data){
		$('#sortable".$this->table." tbody').sortable('enable');
	}

function cat_status_on(id){
	$.post('".$inc_path."ajax/status.php', {id_name: '".$this->id_key."', table: '".$this->table."',action: 'catshow',id: id}, cat_onstatus_Success );	
	
}
function cat_status_off(id){
	$.post('".$inc_path."ajax/status.php', {id_name: '".$this->id_key."', table: '".$this->table."',action: 'cathide',id: id}, cat_onstatus_Success );	
	
}
function cat_onstatus_Success(data){
	text = $('#text', data).html();
	id = $('#id', data).html();
	
	$('#cat_status'+id).html(text);
}	
function an_cat_action(id,act){
	if(act == 'del'){
		if(!confirm('Удалить выбранные строки?') ) return;
	}
	catalog_main_form.id_table_row.value = id;
	catalog_main_form.catmode.value = act;
	catalog_main_form.submit();
}
function ActionCat(act){	
	catalog_main_form.catmode.value = act;
	catalog_main_form.submit();
}
</script>";
		}
	}
	/////////////////////////////////////////////////////////end  DRAW///////////////////////////////////////////////////////////////////


}


?>
<? endif; ?>