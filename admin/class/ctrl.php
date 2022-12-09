<? if (!defined("_ctrl_inc")) : ?>
<? define("_ctrl_inc",1); ?>
<?php

class obj_html{
	var $caption;
	var $name;
	var $list=0;
	var $edit=2;
	var $html;

	function obj_html($html){
		$this->html = $html;
	}
	
	function get_html($id){
		return $this->html;
	}	
}
class obj_iframe{
	var $caption;
	var $name;
	var $list=0;
	var $edit=2;
	var $file;

	function obj_iframe($file){
		$this->file = $file;
	}
	
	function get_html($id){
		if($id == 0){
			return '<div style="color:red">Доступно только при редактировании (после добавления)</div>';
		}else{
			return '<iframe style="width:100%; height:700px" src="'.str_replace('{id}',$id,$this->file).'"></iframe>';
		}
	}	
}


class ctrl{
	var $caption;
	var $name;
	var $list;
	var $edit;
	var $class_name;
	/*  пока хз */
	var $value; // 
	
	/*  енд пока хз */

	function ctrl($caption, $name, $params=array()){
		$this->caption = $caption;
		$this->name = $name;
	}	
	function get_sql(){
		return $this->name.', ';
	}
	/* insert */
	function draw_add(){	
	}
	function insert(){	
		return $this->name." = ".to_sql(get_param('new_'.$this->name)).",";
	}
	function after_insert($id){
		return;
	}
	
	function after_add_value($new_id){
		return to_sql(get_param('new_'.$this->name));
	}
	
	/* edit */
	function get_edit_sql(){
		return $this->name.', ';
	}
	function draw_edit($id_row,$value){
		return;
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		return " ".$this->name." = ".to_sql(get_param('edit_'.$id_row.'_'.$this->name)).",";
	}

	function after_update($id_row){
		return;
	}
	function after_update_value($id_row){
		return to_sql(get_param('edit_'.$id_row.'_'.$this->name));
	}
	/* delete */
	function delete($id_row, $table_name = '', $table_id_key = ''){
		return;
	}

	/* draw */
	function draw($value,$id =''){	
		$value = trim($value);
		if(empty($value)) return $value.'&nbsp;';
		else return $value;
	}
	
}

class c_text extends ctrl{
	var $size;
	var $max;
	function c_text($caption, $name, $list, $edit, $size = '',$max = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->size = $size; // размер input
		$this->max = $max; //макс. кол-во символов
		 
	}	
	/* insert */
	function draw_add(){	
		return '<input type="text" name="new_'.$this->name.'" class="form-control"  size="'.$this->size.'" maxlength="'.$this->max.'">';
	}	
	/* edit */
	/*function draw_edit($id_row,$value){
		return '<input type="text" name="edit_'.$id_row.'_'.$this->name.'"  class="form-control" value="'.my_htmlspecialchars($value).'" size="'.$this->size.'" maxlength="'.$this->max.'">';	
	}	
	*/
	function draw_edit($id_row,$value){
		return '<input type="text" name="edit_'.$id_row.'_'.$this->name.'"  class="form-control" value="'.my_htmlspecialchars($value).'" size="'.$this->size.'" maxlength="'.$this->max.'">';
	}
	
}
class c_ajax_text extends ctrl{
	var $size;
	var $max;
	function c_ajax_text($caption, $name, $list, $edit, $size = '',$max = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->size = $size; // размер input
		$this->max = $max; //макс. кол-во символов
	}	
	/* insert */
	function draw_add(){	
		return '<input type="text"  class="form-control" name="new_'.$this->name.'" size="'.$this->size.'" maxlength="'.$this->max.'">';
	}	
	/* edit */
	function draw_edit($id_row,$value){
		return '<input type="text"  class="form-control" name="edit_'.$id_row.'_'.$this->name.'" value="'.htmlspecialchars($value).'" size="'.$this->size.'" maxlength="'.$this->max.'">';	
	}	
	
	function draw($value,$id =''){	
		$q = new query();
		if(empty($value)) return '<input type="text" name="edit_'.$id.'_'.$this->name.'" id="edit_'.$id.'_'.$this->name.'" style="width:70px" value="0" onchange="change_text_field('.$id.',\''.$this->name.'\',this.value)">';
		
		else return '<input type="text" name="edit_'.$id.'_'.$this->name.'" id="edit_'.$id.'_'.$this->name.'" style="width:70px" value="'.htmlspecialchars($value).'" onchange="change_text_field('.$id.',\''.$this->name.'\', this.value)">';
		
		
		
	}
	
}


class c_text_link extends c_text{
	var $size;
	var $max;
	var $link;
	function c_text_link($caption, $name, $list, $edit, $size = '',$max = '',$link='',$class = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->size = $size; // размер input
		$this->max = $max; //макс. кол-во символов
		$this->link = $link; //ссылка со значнием в параметре
		 
	}
	function draw($value,$id =''){	
		$value = trim($value);
		if(empty($value)) return $value.'&nbsp;';
		else return str_replace('{par}',$value,$this->link);
	}	
}


class c_text_user extends c_text{
	function insert(){	
		return $this->name." = ".clear_text(get_param('new_'.$this->name)).",";
	}	
	function update($id_row, $table_name = '', $table_id_key = ''){
		return " ".$this->name." = ".clear_text(get_param('edit_'.$id_row.'_'.$this->name)).",";
	}
}


class c_multiselect extends ctrl{
	var $table,$field_id,$field_name;
	var $con_table, $con_field_id, $con_field_id2,$beg_value;
	function c_multiselect($caption, $name, $table, $field_id, $field_name,$con_table, $con_field_id, $con_field_id2,$beg_value=''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = 0;
		$this->edit = 1;		
		$this->table = $table; // имя таблицы
		$this->field_id = $field_id; //название поля где хранится ид
		$this->field_name = $field_name; //название поля где хранится название		
		$this->con_table = $con_table; // имя таблицы связи
		$this->con_field_id = $con_field_id; //название поля где хранится ид1
		$this->con_field_id2 = $con_field_id2; //название поля где хранится ид2
		$this->beg_value = $beg_value; // начальное значение
	}	
	function get_sql(){
		return '';
	}
	
	
	
	
	function insert(){	
		return '';
	}
	function after_insert($id){
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('new_'.$this->name);
		$vals = explode(',',$val);
		if(sizeof($vals) > 0){
			$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id);
			foreach($vals as $row){
				if(empty($row)) continue;
				$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id.", ".$this->con_field_id2."=".$row);
			}
		}
		return;
	}
	/* edit */
	function get_edit_sql(){
		return '';
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		return "";
	}
	function after_update($id_row){		
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('edit_'.$id_row.'_'.$this->name);
		$vals = explode(',',$val);
		if(sizeof($vals) > 0){
			$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row);
			foreach($vals as $row){
				if(empty($row)) continue;
				$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id_row.", ".$this->con_field_id2."=".$row);
			}
		}
		return;
	}
	
	
	/* insert */
	function draw_add(){
		global $inc_path;	
		$q = new query();	
		if(!empty($this->beg_value)){
			$temp = $q->select("select name from ".$this->table." where id in (".$this->beg_value.") order by rank desc");
			$f = 1;
			$temp_val = '';
			foreach($temp as $v){
				if($f==1) $f=0;
				else{
					$temp_val .= ", ";
				}
				$temp_val .= $v['name'];
			}
		}else $temp_val = '';
		
		
		return '
		<div id="name_new_'.$this->name.'">'.$temp_val.'</div>
		<input type="text"  class="form-control" name="new_'.$this->name.'" id="new_'.$this->name.'" style="width:200px;" value="'.$this->beg_value.'" >		
			
		<input type="button" value="Выбрать" 
		onclick="$.fn.colorbox({width:\'805px\',height:\'600\',href:\''.$inc_path.'class/multiselect.php?height=505&width=650&modal=true&table='.$this->table.'&f1='.$this->field_id.'&f2=name_new_'.$this->name.'&f3=new_'.$this->name.'&val=\'+document.getElementById(\'new_'.$this->name.'\').value});"> <br>
		
			';
	}	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;
		$q = new query();
		$cats = $q->select("select ".$this->con_field_id2." from ".$this->con_table." where ".$this->con_field_id."=".$id_row);
		$f = 1;
		$str = '';
		foreach($cats as $row){
			if($f == 1)	$f = 0;
			else $str .= ",";
			$str = $str.$row[$this->con_field_id2];
		}
		
		if(!empty($str)){
			$temp = $q->select("select name from ".$this->table." where id in (".$str.") order by rank desc");
			$f = 1;
			$temp_val = '';
			foreach($temp as $v){
				if($f==1) $f=0;
				else{
					$temp_val .= ", ";
				}
				$temp_val .= $v['name'];
			}
		}else $temp_val = '';
		
	
		return '
		<div id="name_edit_'.$id_row.'_'.$this->name.'">'.$temp_val.'</div>
		<input type="text"  class="form-control" name="edit_'.$id_row.'_'.$this->name.'" id="edit_'.$id_row.'_'.$this->name.'" value="'.my_htmlspecialchars($str).'" style="width:200px;">
		
		
		
		
			
		<input type="button" value="Выбрать" 
		onclick="$.fn.colorbox({width:\'805px\',height:\'600\',href:\''.$inc_path.'class/multiselect.php?height=505&width=650&modal=true&table='.$this->table.'&f1='.$this->field_id.'&f2=name_edit_'.$id_row.'_'.$this->name.'&f3=edit_'.$id_row.'_'.$this->name.'&val=\'+document.getElementById(\'edit_'.$id_row.'_'.$this->name.'\').value});"> <br>
		
		';	
		
		
	}
	/* delete */
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row);
		return;
	}	
}



class c_checkbox extends ctrl{
	var $def_val;
	function c_checkbox($caption, $name, $list, $edit, $def_val=0){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;
		$this->def_val = $def_val;//значение по умолчанию
		 
	}	
	
	
	function insert(){	
		$temp = get_param('new_'.$this->name);
		if(!empty($temp)) $temp = 1;
		else $temp = 0;
		return $this->name." = ".to_sql($temp).",";
	}

	function update($id_row, $table_name = '', $table_id_key = ''){
		$temp = get_param('edit_'.$id_row.'_'.$this->name);
		if(!empty($temp)) $temp = 1;
		else $temp = 0;
		return " ".$this->name." = ".to_sql($temp).",";
	}
	
	/* insert */
	function draw_add(){	
		return '<input type="checkbox" class="form-control minimal" name="new_'.$this->name.'"'.($this->def_val == 1 ? ' checked' : '').'>';
	}	

	/* edit */
	function draw_edit($id_row,$value){
		if($value == 1)
			return '<input type="checkbox" class="form-control minimal" name="edit_'.$id_row.'_'.$this->name.'" value="1" checked>';	
		else
			return '<input type="checkbox" class="form-control minimal" name="edit_'.$id_row.'_'.$this->name.'" value="1" >';	
	}	
	function draw($value,$id =''){	
		$value = trim($value);
		if($value ==1) return 'да';
		else return 'нет';
	}
}

class c_ajax_checkbox extends c_checkbox{
	var $def_val;
	function c_ajax_checkbox($caption, $name, $list, $edit, $def_val=0){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;
		$this->def_val = $def_val;//значение по умолчанию
		 
	}	
		
	
	/* insert */
	function draw_add(){	
		return '<input type="checkbox"  class="form-control minimal" name="new_'.$this->name.'"'.($this->def_val == 1 ? ' checked' : '').'>';
	}	

	/* edit */
	function draw_edit($id_row,$value){
		if($value == 1)
			return '<input type="checkbox"  class="form-control minimal" name="edit_'.$id_row.'_'.$this->name.'" value="1" checked>';	
		else
			return '<input type="checkbox"  class="form-control minimal" name="edit_'.$id_row.'_'.$this->name.'" value="1" >';	
	}	
	function draw($value,$id =''){	
		$value = trim($value);
	/*	if($value ==1) return 'да';
		else return 'нет';
		*/
		if($value == 1)
			return '<input type="checkbox" name="upd_'.$id.'_'.$this->name.'" value="1" checked 
			onclick="
			var val=0;
			if($(this).attr(\'checked\')) val=0;
			else val = 1;
			update_param(\''.$this->name.'\',\''.$id.'\',val);"
			>';	
		else
			return '<input type="checkbox"  name="upd_'.$id.'_'.$this->name.'" value="1" 
			onclick="
			var val=0;
			if($(this).attr(\'checked\')) val=0;
			else val = 1;
			update_param(\''.$this->name.'\',\''.$id.'\',val);"
			>';	
		
	}
}


class c_textarea extends ctrl{
	var $cols;
	var $rows;
	function c_textarea($caption, $name, $list, $edit, $cols = '',$rows = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;
		$this->cols = $cols;
		$this->rows = $rows;
		 
	}	
	/* insert */
	function draw_add(){	
		return '<textarea  class="form-control" name="new_'.$this->name.'"  cols="'.$this->cols.'" rows="'.$this->rows.'"></textarea>';
	}	
	/* edit */
	function draw_edit($id_row,$value){
		return '<textarea class="form-control" name="edit_'.$id_row.'_'.$this->name.'"  cols="'.$this->cols.'" rows="'.$this->rows.'">'.my_htmlspecialchars($value).'</textarea>';	
	}
}

class c_textarea_user extends c_textarea{
	function insert(){	
		return $this->name." = ".clear_text(get_param('new_'.$this->name)).",";
	}	
	function update($id_row, $table_name = '', $table_id_key = ''){
		return " ".$this->name." = ".clear_text(get_param('edit_'.$id_row.'_'.$this->name)).",";
	}
}

class c_fck extends ctrl{
	var $height;
	var $oFCKeditor;
	function c_fck($caption, $name, $list, $edit, $height = '400', $class=''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;
		$this->height = $height;
		 
	}	
	/* insert */
	function draw_add(){	
		global $inc_path;			
		return '<textarea class="ckeditor" id="new_'.$this->name.'" name="new_'.$this->name.'" rows="10" style="width:98%;height:200px;"></textarea>';
		
	}	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;			
		return '<textarea class="ckeditor" id="edit_'.$id_row.'_'.$this->name.'" name="edit_'.$id_row.'_'.$this->name.'" rows="10" style="width:98%;height:200px;">'.$value.'</textarea>';
	}
}

class c_select extends ctrl{
	var $values;//массив значений
	function c_select($caption, $name, $list, $edit, $values){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->values = $values; 
	}	
	/* insert */
	function draw_add(){	
		$text = '<select  class="form-control select2" name="new_'.$this->name.'" >';
		foreach($this->values as $k1=>$v1){
			$text .= '<option value="'.$k1.'">'.$v1;
		}
		$text .= '</select>';
		return $text;
	}	
	/* edit */
	function draw_edit($id_row,$value){
		$text = '
		<select  class="form-control select2" name="edit_'.$id_row.'_'.$this->name.'" >';
		foreach($this->values as $k1=>$v1){
			$text .= '<option value="'.$k1.'" ';
			if ($value == $k1) $text .= ' selected ';
			$text .= '>'.$v1;
		}
		$text .= '</select>';		
		return $text;
	}	
	/* draw */
	function draw($value,$id =''){	
		$value = trim($this->values[$value]);
		if(empty($value)) return '&nbsp;';
		else return $value;
	}	
}


class c_multi_checkbox extends ctrl{
	var $values;//массив значений
	function c_multi_checkbox($caption, $name, $list, $edit, $values){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->values = $values; 
	}	
	/* insert */
	
	
	
	function insert(){	
		$val = get_param('new_'.$this->name);
		$vals = '||'.implode('||',$val).'||';
		return $this->name." = ".to_sql($vals).",";
	}
	
	function update($id_row, $table_name = '', $table_id_key = ''){
		$val = get_param('edit_'.$id_row.'_'.$this->name);
		$vals = '||'.implode('||',$val).'||';
		return " ".$this->name." = ".to_sql($vals).",";
	}
	
	
	function after_add_value($new_id){
		return get_param('new_'.$this->name);
	}
	
	function after_update_value($id_row){
		return get_param('edit_'.$id_row.'_'.$this->name);
	}
	

	
	function draw_add(){
		$text = '';	
		foreach($this->values as $k1=>$v1){
			$text .= '<div><label><input type="checkbox" value="'.$k1.'"  name="new_'.$this->name.'[]" >'.$v1.'</label></div>';
		}
		return $text;
	}	
	/* edit */
	function draw_edit($id_row,$value){
		$text = '';
		$values = explode('||',$value);
		foreach($this->values as $k1=>$v1){
			$text .= '<div><label><input type="checkbox"  name="edit_'.$id_row.'_'.$this->name.'[]" value="'.$k1.'" ';
			if (in_array($k1,$values)) $text .= ' checked ';
			$text .= '>'.$v1.'</label></div>';
		}
		$text .= '';		
		return $text;
	}	
	/* draw */
	function draw($value,$id =''){	
		$values = explode('||',$value);
		$temp = array();
		foreach($this->values as $k1=>$v1){
			if (in_array($k1,$values)) $temp[] = $v1;
		}
		$value = implode(',',$temp);
		if(empty($value)) return '&nbsp;';
		else return $value;
	}	
}

class c_data extends ctrl{
	var $size;
	var $max;
	function c_data($caption, $name, $list, $edit, $size = '',$max = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->size = $size; // размер input
		$this->max = $max; //макс. кол-во символов
		 
	}	
	/* insert */
	function draw_add(){
		global $inc_path;	
		return '<div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div><input  class="form-control datepicker" type="text" name="new_'.$this->name.'" value=""></div>';		
	}
	function insert(){	
		$temp = get_param('new_'.$this->name);
		$str_data = substr($temp,6,4).'-'.substr($temp,3,2).'-'.substr($temp,0,2);
		return " ".$this->name." = '".$str_data."',";
	}
	function after_add_value($new_id){
		$temp = get_param('new_'.$this->name);
		$str_data = substr($temp,6,4).substr($temp,3,2).substr($temp,0,2);
		return to_sql($str_data);
	}
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;	
		$temp = to_phpdate($value);
		if($temp != 1)	$value = date('d.m.Y',$temp);
		else $value = '';
//		$value = date('d.m.Y',to_phpdate($value));
		return '<div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div><input type="text" class="form-control datepicker" name="edit_'.$id_row.'_'.$this->name.'" value="'.$value.'"></div>';	
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		$temp = get_param('edit_'.$id_row.'_'.$this->name);
		$str_data = substr($temp,6,4).'-'.substr($temp,3,2).'-'.substr($temp,0,2);
		return " ".$this->name." = '".$str_data."',";	
	}
	function after_update_value($id_row){
		$temp = get_param('edit_'.$id_row.'_'.$this->name);
		$str_data = substr($temp,6,4).substr($temp,3,2).substr($temp,0,2);
		return to_sql($str_data);
	}
	/* draw */
	function draw($value,$id =''){
		$temp = to_phpdate($value);
		if($temp != 1)	$value = date('d.m.Y H:i',$temp);
		if(empty($value)) return '&nbsp;';
		else return $value;
	}	
}




class c_image extends ctrl{
	var $folder;
	var $sizes;
	function c_image($caption, $name, $list, $edit, $folder ,$sizes = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->folder = $folder; // путь к папке с картинками
		$this->sizes = $sizes; //размеры превьюшек
	}	
	/* insert */
	function draw_add(){
		global $inc_path;	
		return '<input class="form-control" type="file" name="new_'.$this->name.'" >';		
	}
	//получение размеров превью картинок
	function get_pict_sizes($param,$w,$h){
		$picts = explode(';',$param);
		$i=0;
		foreach($picts as $row){
			if(!empty($row)){
				$one_pict = explode('x',$row);
				//  1    ,    2     
				if(empty($one_pict[1])){
					if($w>$h){

						if($one_pict[0] < $w){
							$w2 = $one_pict[0];
							$h2 = round(($h/($w/$w2)), 0);
						}else{
                                                	$w2 = $w;
							$h2 = $h;
						}

					}else{
						if($one_pict[0] < $h){
							$h2 = $one_pict[0];
							$w2 = round(($w/($h/$h2)), 0);
						}else{
                                                	$w2 = $w;
							$h2 = $h;
						}
					}			
				}else{
					//    ()      
					if(!empty($one_pict[0]) && $one_pict[1] == 'auto'){
							if($one_pict[0] < $w){
								$w2 = $one_pict[0];
								$h2 = round(($h/($w/$w2)), 0);						
							}else{
                                                        	$w2 = $w;
								$h2 = $h;
							}
					}else{
						//    ()      
						if(!empty($one_pict[1]) && $one_pict[0] == 'auto'){
							if($one_pict[1] < $h){
								$h2 = $one_pict[1];
								$w2 = round(($w/($h/$h2)), 0);					
							}else{
                                                        	$w2 = $w;
								$h2 = $h;
							}  
						}else{
							if(!empty($one_pict[0]) && !empty($one_pict[1])){
									$w2 = $one_pict[0];	
									$h2 = $one_pict[1];
									$w2 = $one_pict[0];					
							}
						}
					}
				}
				$res[$i]['w'] = $w2;
				$res[$i]['h'] = $h2;
				$i++;		
			}	
		}
		return $res;
	}
	function get_num_pict($param){
		$picts = explode(';',$param);
		return count($picts);
	}
	function insert(){	
		$pict_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}								
		}
		return " ".$this->name." = ".to_sql($pict_ext).",";		
	}
	function after_insert($new_id){
		$pict_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}								
		}
		if(!empty($pict_ext)){
			$path = $this->folder;
			$pre_param = $this->sizes;					
			$filename = $path.$new_id.$pict_ext;
			make_dir($path);
			copy($_FILES['new_'.$this->name]['tmp_name'], $filename);	
			chmod($filename, FILE_RIGHTS);
			list($w, $h) = getimagesize($filename);
			if(!empty($pre_param)){
				$pre_size = $this->get_pict_sizes($pre_param,$w,$h);
				$i = 1;
				foreach($pre_size as $ps){
					resize_then_crop($filename,$path.'pre'.$i.'_'.$new_id.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
					chmod($path.'pre'.$i.'_'.$new_id.$pict_ext, FILE_RIGHTS);
					$i++;
				}									
			}			
			$h = round(($h/($w/50)), 0);
			$w=50;			
			resize_then_crop($filename,$path.'pre'.$new_id.$pict_ext,$w,$h,255,255,255); 
			chmod($path.'pre'.$new_id.$pict_ext, FILE_RIGHTS); 
		}	
		return;
	}
	
	function after_add_value($new_id){
		$pict_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}								
		}
		return to_sql($pict_ext);
	}
	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;	
		$text = '';
		$path = $this->folder;					
		$filename = $path.'pre'.$id_row.$value;												
		if(file_exists($filename)){
			$text .= '<img src="'.$filename.'" align="left">';
		}
		$text .=  '<input  class="form-control" type="file" name="edit_'.$id_row.'_'.$this->name.'" onchange="editrad_'.$id_row.'_'.$this->name.'[1].checked=true;"><br>';
		$text .=  '<label><input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" checked value="no">не изменять</label> 
			<label><input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="yes">Изменить</label> 
			<label><input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="del">Удалить</label> ';
		return $text;
	}

	function update($id_row,$table_name='',$table_id_key=''){
		$q = new query();
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){									
				$pict_ext = '';
				$ext = array(".gif", ".jpg", ".jpeg", ".png");
				for($i=0;$i<sizeof($ext);$i++){
					if($data = explode($ext[$i], strtolower($_FILES['edit_'.$id_row.'_'.$this->name]['name']))){										
						if(count($data) == 2){
							$pict_ext = $ext[$i];
							break;
						}
					}
				}	
			}
			if(!empty($pict_ext )){													
				$path = $this->folder;
				$pre_param = $this->sizes;
				$filename = $path.$id_row.$pict_ext;
	
				$sql_del = "select ".$this->name." from  ".$table_name."  ";
				$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
				$image_ext = $q->select1($sql_del);
				$pict_ext_old = $image_ext[$this->name];
				@unlink($path.$id_row.$pict_ext_old);
				@unlink($path.'pre'.$id_row.$pict_ext_old);
				
				if(!empty($pre_param)){
					$col_pict = $this->get_num_pict($pre_param);
					$i = 1;
					for($i = 1;$i<= $col_pict;$i++){
						if(is_file($path.'pre'.$i.'_'.$id_row.$pict_ext_old))
							@unlink($path.'pre'.$i.'_'.$id_row.$pict_ext_old);
					}									
				}													
				copy($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'], $filename);	
				chmod($filename, FILE_RIGHTS);
				list($w, $h) = getimagesize($filename);
				if(!empty($pre_param)){
					$pre_size = $this->get_pict_sizes($pre_param,$w,$h);
					$i = 1;
					foreach($pre_size as $ps){
						resize_then_crop($filename,$path.'pre'.$i.'_'.$id_row.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
						chmod($path.'pre'.$i.'_'.$id_row.$pict_ext, FILE_RIGHTS);
						$i++;
					}									
				}
				$h = round(($h/($w/50)), 0);
				$w=50;			
				resize_then_crop($filename,$path.'pre'.$id_row.$pict_ext,$w,$h,255,255,255); 
				chmod($path.'pre'.$id_row.$pict_ext, FILE_RIGHTS);
			}
		}else{//if(get_param('editrad_'.$id_row.'_'.$this->cols[$k]['name']) == 'yes'){
				if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
					$sql_del = "select ".$this->name." from  ".$table_name."  ";
					$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
					$image_ext = $q->select1($sql_del);
					$pict_ext = $image_ext[$this->name];
					$path = $this->folder;	
					$pre_param = $this->sizes;	
					$filename = $path.$id_row.$pict_ext;
					if(file_exists($filename)){										
						@unlink($filename);
						@unlink($path.'pre'.$id_row.$pict_ext);
						if(!empty($pre_param)){
							$col = $this->get_num_pict($pre_param);
							$i = 1;
							for($i = 1;$i <= $col;$i++){
								if(is_file($path.'pre'.$i.'_'.$id_row.$pict_ext))
									@unlink($path.'pre'.$i.'_'.$id_row.$pict_ext);
							}									
						}																
					}
				}
		}//else if(get_param('editrad_'.$id_row.'_'.$this->cols[$k]['name']) == 'yes'){
	

		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			return " ".$this->name." = ".to_sql($pict_ext).",";
		}else{
			if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
					return " ".$this->name." = '',";
			}
		}
	}
	
	function after_update_value($id_row){
		$pict_ext = '';
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){									
				$ext = array(".gif", ".jpg", ".jpeg", ".png");
				for($i=0;$i<sizeof($ext);$i++){
					if($data = explode($ext[$i], strtolower($_FILES['edit_'.$id_row.'_'.$this->name]['name']))){										
						if(count($data) == 2){
							$pict_ext = $ext[$i];
							break;
						}
					}
				}	
			}
			if(!empty($pict_ext )){	
				return to_sql($pict_ext);
			}
		}
		return '';
	}

	
	
	
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$sql = "select ".$this->name." from  ".$table_name."  ";
		$sql .= " where ".$table_id_key."=".to_sql($id_row)." ";
		$image_ext = $q->select1($sql);
		$pict_ext = $image_ext[$this->name];
		$path = $this->folder;	
		$pre_param = $this->sizes;	
		$filename = $path.$id_row.$pict_ext;
		if(file_exists($filename)){										
			@unlink($filename);
			@unlink($path.'pre'.$id_row.$pict_ext);
			if(!empty($pre_param)){
				$col = $this->get_num_pict($pre_param);
				for($i = 1;$i <= $col;$i++){
					if(is_file($path.'pre'.$i.'_'.$id_row.$pict_ext))
						@unlink($path.'pre'.$i.'_'.$id_row.$pict_ext);
				}									
			}									
			
		}
	}	
	
	/* draw */
	function draw($value,$id =''){	
		$path = $this->folder;					
		$filename = $path.'pre'.$id.$value;										
		if(file_exists($filename)){
			$filename2 = $path.$id.$value;	
			list($w, $h) = getimagesize($filename2);
			if($w < 50)	$filename = $filename2;	
			return '<img src="'.$filename.'">';
		}else{
			return 'нет';
		}		
	}	
}




class c_fileid extends ctrl{
	var $folder;
	function c_fileid($caption, $name, $list, $edit, $folder){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->folder = $folder; // папка для файлов
	}	
	/* insert */
	function draw_add(){	
		return '<input  class="form-control" type="file" name="new_'.$this->name.'" >';
	}	
	function insert(){	
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){		
			$str_temp = $_FILES['new_'.$this->name]['name'];
			for($i=strlen($str_temp)-1;$i>=0;$i--){
				if($str_temp[$i] == '.') break;
			}								
			$file_ext = substr($str_temp,$i);
			return " ".$this->name." = ".to_sql($file_ext).",";
		}
		return '';
	}
	function after_insert($new_id){
		$file_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){		
			$str_temp = $_FILES['new_'.$this->name]['name'];
			for($i=strlen($str_temp)-1;$i>=0;$i--){
				if($str_temp[$i] == '.') break;
			}								
			$file_ext = substr($str_temp,$i);									
		}		
		if(!empty($file_ext )){
			$path = $this->folder;					
			$filename = $path.$new_id.$file_ext;
			make_dir($path);
			copy($_FILES['new_'.$this->name]['tmp_name'], $filename);	
			chmod($filename, FILE_RIGHTS);						
		}
		return;
	}
	
	function after_add_value($new_id){
		$file_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){		
			$str_temp = $_FILES['new_'.$this->name]['name'];
			for($i=strlen($str_temp)-1;$i>=0;$i--){
				if($str_temp[$i] == '.') break;
			}								
			$file_ext = substr($str_temp,$i);
			return to_sql($file_ext);
		}
		return '';
	}
	
	/* edit */
	function draw_edit($id_row,$value){
		$path = $this->folder;	
		$file_name = $id_row.$value;
		$file_path = $path.$file_name;
		$text = '';
		if(file_exists($file_path)){
			$text .= $value.' ';
		}		
		$text .= '<input  class="form-control" type="file" name="edit_'.$id_row.'_'.$this->name.'" onchange="editrad_'.$id_row.'_'.$this->name.'[1].checked=true;" ><br>';
		$text .= '<input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" checked value="no">Не изменять 
		<input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="yes">Изменить 
		<input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="del">Удалить';
		return $text;
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			$file_ext='';
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){	
				$str_temp = $_FILES['edit_'.$id_row.'_'.$this->name]['name'];
				for($i=strlen($str_temp)-1;$i>0;$i--){
					if($str_temp[$i] == '.') break;
				}								
				$file_ext = substr($str_temp,$i);									
			}		
			if(!empty($file_ext)){													
				$path = $this->folder;					
				$filename = $path.$id_row.$file_ext;
				$sql_del = "select ".$this->name." from  ".$table_name."  ";
				$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
				$temp = $q->select1($sql_del);
				$file_ext_old = $temp[$this->name];
				@unlink($path.$id_row.$file_ext_old);
				copy($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'], $filename);	
				chmod($filename, FILE_RIGHTS);												
			}
		}else{//if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
				$sql_del = "select ".$this->name." from  ".$table_name."  ";
				$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
				$temp = $q->select1($sql_del);
				$file_ext = $temp[$this->name];
				$path = $this->folder;					
				$filename = $path.$id_row.$file_ext;
				if(file_exists($filename)){										
					@unlink($filename);
				}
			}//if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
		}
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			return " ".$this->name." = ".to_sql($file_ext).",";
		}else{
				if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
						return " ".$this->name." = '',";
				}
		}
	}//function update
	
	
	function after_update_value($id_row){
		$file_ext = '';
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			$file_ext='';
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){	
				$str_temp = $_FILES['edit_'.$id_row.'_'.$this->name]['name'];
				for($i=strlen($str_temp)-1;$i>0;$i--){
					if($str_temp[$i] == '.') break;
				}								
				$file_ext = substr($str_temp,$i);									
			}		
			if(!empty($file_ext)){													
//				$path = $this->folder;
//				$filename = $path.$id_row.$file_ext;
				return to_sql($file_ext);
														
			}
		}	
		return '';
	}
	
	
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();	
		$sql = "select ".$this->name." from  ".$table_name."  ";
		$sql .= " where ". $table_id_key."=".to_sql($id_row)." ";
		$temp = $q->select1($sql);
		$file_ext = $temp[$this->name];
		$path = $this->folder;					
		$filename = $path.$id_row.$file_ext;
		if(file_exists($filename)){										
			@unlink($filename);
			//@unlink($path.'pre'.$id_row.$pict_ext);
		}
		return;
	}
	
	function draw($value,$id =''){
		$path = $this->folder;					
		$filename = $path.$id.$value;										
		if(file_exists($filename)){
			return 'есть('.$value.')';
		}else{
			return 'нет';
		}
	}
	
}


class c_anyfile extends ctrl{
	var $folder;
	function c_anyfile($caption, $name, $list, $edit, $folder){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->folder = $folder; // папка для файлов
	}	
	/* insert */
	function draw_add(){	
		return '<input class="form-control" type="file" name="new_'.$this->name.'" >';
	}	
	function insert(){	
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){		
			$file_name = translit($_FILES['new_'.$this->name]['name']);
			$path = $this->folder;
			make_dir($path);				
			$filename = $path.$file_name;
			copy($_FILES['new_'.$this->name]['tmp_name'], $filename);	
			chmod($filename, FILE_RIGHTS);	
			return " ".$this->name." = ".to_sql($file_name).",";
		}
		return '';
	}	
	function after_add_value($new_id){
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){		
			$file_name = translit($_FILES['new_'.$this->name]['name']);
			return to_sql($file_name);
		}	
		return '';
	}
	/* edit */
	function draw_edit($id_row,$value){	
		$path = $this->folder;	
		$file_name = $value;
		$file_path = $path.$file_name;
		$text = '';
		if(file_exists($file_path)){
			$text .= $value.' ';
		}										
		$text .= '<input class="form-control" type="file" name="edit_'.$id_row.'_'.$this->name.'" onchange="editrad_'.$id_row.'_'.$this->name.'[1].checked=true;" ><br>';
		$text .= '<input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" checked value="no">Не изменять 
			<input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="yes">Изменить 
			<input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="del">Удалить';
		return $text;
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){		
				$file_name = translit($_FILES['edit_'.$id_row.'_'.$this->name]['name']);
				$path = $this->folder;					
				$filename = $path.$file_name;
				$sql_del = "select ".$this->name." from  ".$table_name."  ";
				$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
				$temp = $q->select1($sql_del);
				$file_old = $temp[$this->name];
				@unlink($path.$file_old);
				copy($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'], $filename);	
				chmod($filename, FILE_RIGHTS);												
			}
		}else{
			if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
				$sql_del = "select ".$this->name." from  ".$table_name."  ";
				$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
				$temp = $q->select1($sql_del);
				$file_name = $temp[$this->name];
				$path = $this->folder;					
				$filename = $path.$file_name;
				if(file_exists($filename)){										
					@unlink($filename);
				}
			}
		}	
		
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			return " ".$this->name." = '".$file_name."',";
		}else{
			if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
				return " ".$this->name." = '',";
			}
		}
	}//function update
	
	function after_update_value($id_row){
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){		
				$file_name = translit($_FILES['edit_'.$id_row.'_'.$this->name]['name']);
				return to_sql($file_name);
			}
		}
		return '';
	}
	
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();	
		$sql = "select ".$this->name." from  ".$table_name."  ";
		$sql .= " where ". $table_id_key."=".to_sql($id_row)." ";
		$temp = $q->select1($sql);
		$file_ext = $temp[$this->name];
		$path = $this->folder;					
		$filename = $path.$file_ext;
		if(file_exists($filename)){										
			@unlink($filename);
			//@unlink($path.'pre'.$id_row.$pict_ext);
		}
		return;
	}
	
	function draw($value,$id =''){	
	
		$path = $this->folder;					
		$filename = $path.$value;	
		if(file_exists($filename) && !empty($value)){
			return $value;
		}else{
			return 'нет файла';
		}
		
	}
	
}

class c_cpu_image extends ctrl{
	var $folder;
	var $sizes;
	function c_cpu_image($caption, $name, $list, $edit, $folder ,$sizes = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->folder = $folder; // путь к папке с картинками
		$this->sizes = $sizes; //размеры превьюшек
	}	
	/* insert */
	function draw_add(){
		global $inc_path;	
		return '<input class="form-control" type="file" name="new_'.$this->name.'" >';		
	}
	//получение размеров превью картинок
	function get_pict_sizes($param,$w,$h){
		$picts = explode(';',$param);
		$i=0;
		foreach($picts as $row){
			if(!empty($row)){
				$one_pict = explode('x',$row);
				//  1    ,    2     
				if(empty($one_pict[1])){
					if($w>$h){

						if($one_pict[0] < $w){
							$w2 = $one_pict[0];
							$h2 = round(($h/($w/$w2)), 0);
						}else{
                                                	$w2 = $w;
							$h2 = $h;
						}

					}else{
						if($one_pict[0] < $h){
							$h2 = $one_pict[0];
							$w2 = round(($w/($h/$h2)), 0);
						}else{
                                                	$w2 = $w;
							$h2 = $h;
						}
					}			
				}else{
					//    ()      
					if(!empty($one_pict[0]) && $one_pict[1] == 'auto'){
							if($one_pict[0] < $w){
								$w2 = $one_pict[0];
								$h2 = round(($h/($w/$w2)), 0);						
							}else{
                                                        	$w2 = $w;
								$h2 = $h;
							}
					}else{
						//    ()      
						if(!empty($one_pict[1]) && $one_pict[0] == 'auto'){
							if($one_pict[1] < $h){
								$h2 = $one_pict[1];
								$w2 = round(($w/($h/$h2)), 0);					
							}else{
                                                        	$w2 = $w;
								$h2 = $h;
							}  
						}else{
							if(!empty($one_pict[0]) && !empty($one_pict[1])){
									$w2 = $one_pict[0];	
									$h2 = $one_pict[1];
									$w2 = $one_pict[0];					
							}
						}
					}
				}
				$res[$i]['w'] = $w2;
				$res[$i]['h'] = $h2;
				$i++;		
			}	
		}
		return $res;
	}
	function get_num_pict($param){
		$picts = explode(';',$param);
		return count($picts);
	}
	function insert(){	
		$pict_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}								
		}
		return " ".$this->name." = ".to_sql($pict_ext).",";		
	}
	function after_insert($new_id){
		global $prefix;
		$q=new query();
		$temp = $q->select1("select name from ".$prefix."goods where id=".to_sql($new_id));
		$fol = (int)($new_id / 1000);
		
		$cpu = CleanFileName(translit($temp['name'])).'_';
		$pict_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}								
		}
		if(!empty($pict_ext)){
			$path = $this->folder.$fol.'/';
			$pre_param = $this->sizes;					
			$filename = $path.$cpu.$new_id.$pict_ext;
			make_dir($path);
			copy($_FILES['new_'.$this->name]['tmp_name'], $filename);	
			chmod($filename, FILE_RIGHTS);
			list($w, $h) = getimagesize($filename);
			if(!empty($pre_param)){
				$pre_size = $this->get_pict_sizes($pre_param,$w,$h);
				$i = 1;
				foreach($pre_size as $ps){
					resize_then_crop($filename,$path.'pre'.$i.'_'.$cpu.$new_id.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
					chmod($path.'pre'.$i.'_'.$cpu.$new_id.$pict_ext, FILE_RIGHTS);
					$i++;
				}									
			}			
			$h = round(($h/($w/50)), 0);
			$w=50;			
			resize_then_crop($filename,$path.'pre'.$cpu.$new_id.$pict_ext,$w,$h,255,255,255); 
			chmod($path.'pre'.$cpu.$new_id.$pict_ext, FILE_RIGHTS); 
			
			$sql = "update ".$prefix."goods   ";
			$sql .= "set ".$this->name."=".to_sql($cpu.$new_id.$pict_ext)."  where id=".to_sql($new_id)." ";
			$q->exec($sql);
			
		}	
		return;
	}
	
	function after_add_value($new_id){
		global $prefix;
		$q=new query();
		
		$pict_ext = $filename = '';
		$temp = $q->select1("select name from ".$prefix."goods where id=".to_sql($new_id));
		
		$fol = (int)($new_id / 1000);
		$cpu = CleanFileName(translit($temp['name'])).'_';
		
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}
			$filename = $cpu.'_'.$new_id.$pict_ext;
		}
		
		return to_sql($filename);
	}
	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;	
		global $prefix;
		$q=new query();
		
		
		$text = '';
		$fol = (int)($id_row / 1000);

		$path = $this->folder.$fol.'/';					
		
		$filename = $path.'pre'.$value;
		if(file_exists($filename)){
			$text .= '<img src="'.$filename.'" align="left">';
		}
		$text .=  '<input  class="form-control" type="file" name="edit_'.$id_row.'_'.$this->name.'" onchange="editrad_'.$id_row.'_'.$this->name.'[1].checked=true;"><br>';
		$text .=  '<label> <input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" checked value="no">не изменять</label> 
			<label> <input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="yes">Изменить</label> 
			<label> <input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="del">Удалить</label> ';
		return $text;
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		
		global $prefix;
		$q=new query();
		$fol = (int)($id_row / 1000);
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){									
				$pict_ext = '';
				$ext = array(".gif", ".jpg", ".jpeg", ".png");
				for($i=0;$i<sizeof($ext);$i++){
					if($data = explode($ext[$i], strtolower($_FILES['edit_'.$id_row.'_'.$this->name]['name']))){										
						if(count($data) == 2){
							$pict_ext = $ext[$i];
							break;
						}
					}
				}	
			}
			if(!empty($pict_ext )){	
			/* удаляем сначала старый файл*/
					$sql_del = "select ".$this->name." from  ".$table_name."  ";
					$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
					$image_ext = $q->select1($sql_del);
					$dpict_ext = $image_ext[$this->name];
					$path = $this->folder.$fol.'/';	
					make_dir($path);
					$pre_param = $this->sizes;	
					$filename = $path.$dpict_ext;
					if(file_exists($filename)){										
						@unlink($filename);
						@unlink($path.'pre'.$dpict_ext);
						if(!empty($pre_param)){
							$col = $this->get_num_pict($pre_param);
							$i = 1;
							for($i = 1;$i <= $col;$i++){
								if(is_file($path.'pre'.$i.'_'.$dpict_ext))
									@unlink($path.'pre'.$i.'_'.$dpict_ext);
							}									
						}																
					}
			/* end удаляем сначала старый файл*/
				
				$temp = $q->select1("select name from ".$prefix."goods where id=".to_sql($id_row));
				
				
				$cpu = CleanFileName(translit($temp['name'])).'_';
															
				$path = $this->folder.$fol.'/';
				$pre_param = $this->sizes;
				$filename = $path.$cpu.$id_row.$pict_ext;
	
																	
				copy($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'], $filename);	
				chmod($filename, FILE_RIGHTS);
				list($w, $h) = getimagesize($filename);
				if(!empty($pre_param)){
					$pre_size = $this->get_pict_sizes($pre_param,$w,$h);
					$i = 1;
					foreach($pre_size as $ps){
						resize_then_crop($filename,$path.'pre'.$i.'_'.$cpu.$id_row.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
						chmod($path.'pre'.$i.'_'.$cpu.$id_row.$pict_ext, FILE_RIGHTS);
						$i++;
					}									
				}
				$h = round(($h/($w/50)), 0);
				$w=50;			
				resize_then_crop($filename,$path.'pre'.$cpu.$id_row.$pict_ext,$w,$h,255,255,255); 
				chmod($path.'pre'.$cpu.$id_row.$pict_ext, FILE_RIGHTS);
			}
		}else{//if(get_param('editrad_'.$id_row.'_'.$this->cols[$k]['name']) == 'yes'){
				if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
					$sql_del = "select ".$this->name." from  ".$table_name."  ";
					$sql_del .= " where ".$table_id_key."=".to_sql($id_row)." ";
					$image_ext = $q->select1($sql_del);
					$pict_ext = $image_ext[$this->name];
					$path = $this->folder.$fol.'/';	
					make_dir($path);
					$pre_param = $this->sizes;	
					$filename = $path.$pict_ext;
					if(file_exists($filename)){										
						@unlink($filename);
						@unlink($path.'pre'.$pict_ext);
						if(!empty($pre_param)){
							$col = $this->get_num_pict($pre_param);
							$i = 1;
							for($i = 1;$i <= $col;$i++){
								if(is_file($path.'pre'.$i.'_'.$pict_ext))
									@unlink($path.'pre'.$i.'_'.$pict_ext);
							}									
						}																
					}
				}
		}//else if(get_param('editrad_'.$id_row.'_'.$this->cols[$k]['name']) == 'yes'){
	

		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes' && !empty($pict_ext)){
			return " ".$this->name." = ".to_sql($cpu.$id_row.$pict_ext).",";
		}else{
			if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
					return " ".$this->name." = '',";
			}
		}
	}
	
	function after_update_value($id_row){
		global $prefix;
		$q=new query();
		$pict_ext = '';
		$fol = (int)($id_row / 1000);
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){									
				$ext = array(".gif", ".jpg", ".jpeg", ".png");
				for($i=0;$i<sizeof($ext);$i++){
					if($data = explode($ext[$i], strtolower($_FILES['edit_'.$id_row.'_'.$this->name]['name']))){										
						if(count($data) == 2){
							$pict_ext = $ext[$i];
							break;
						}
					}
				}	
			}
			if(!empty($pict_ext )){	
				$temp = $q->select1("select name from ".$prefix."goods where id=".to_sql($id_row));
				
				$fol = (int)($id_row / 1000);
				$cpu = CleanFileName(translit($temp['name'])).'_';
				return to_sql($cpu.$id_row.$pict_ext);
			}
		}
		return '';
	}

	
	
	
	function delete($id_row, $table_name = '', $table_id_key = ''){
		global $prefix;
		$q=new query();
		$fol = (int)($id_row / 1000);
		$sql = "select ".$this->name." from  ".$table_name."  ";
		$sql .= " where ".$table_id_key."=".to_sql($id_row)." ";
		$image_ext = $q->select1($sql);
		$pict_ext = $image_ext[$this->name];
		$path = $this->folder.$fol.'/';	
		$pre_param = $this->sizes;	
		$filename = $path.$pict_ext;
		if(file_exists($filename)){										
			@unlink($filename);
			@unlink($path.'pre'.$pict_ext);
			if(!empty($pre_param)){
				$col = $this->get_num_pict($pre_param);
				for($i = 1;$i <= $col;$i++){
					if(is_file($path.'pre'.$i.'_'.$pict_ext))
						@unlink($path.'pre'.$i.'_'.$pict_ext);
				}									
			}									
			
		}
	}	
	/* draw */
	function draw($value,$id =''){	
		global $prefix;
		$q=new query();
		$fol = (int)($id / 1000);
		$path = $this->folder.$fol.'/';					
		$filename = $path.'pre'.$value;										
		if(file_exists($filename)&& !empty($value)){
			$filename2 = $path.$value;	
			list($w, $h) = getimagesize($filename2);
			if($w < 50)	$filename = $filename2;	
			return '<img src="'.$filename.'">';
		}else{
			return 'нет';
		}		
	}	
}

class c_image_folder extends c_image{
	var $folder;
	var $sizes;
	function c_image_folder($caption, $name, $list, $edit, $folder ,$sizes = ''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = $list;
		$this->edit = $edit;		
		$this->folder = $folder; // путь к папке с картинками
		$this->sizes = $sizes; //размеры превьюшек
	}	
	/* insert */

	function after_insert($new_id){
		$pict_ext = '';
		if(is_file($_FILES['new_'.$this->name]['tmp_name'])){									
			$ext = array(".gif", ".jpg", ".jpeg", ".png");
			for($i=0;$i<sizeof($ext);$i++){
				if($data = explode($ext[$i], strtolower($_FILES['new_'.$this->name]['name']))){										
					if(count($data) == 2){
						$pict_ext = $ext[$i];
						break;
					}
				}
			}								
		}
		if(!empty($pict_ext)){
			$path = $this->folder;
			$pre_param = $this->sizes;		
			$fol = (int)($new_id/1000);
			$path .= $fol.'/';
						
			$filename = $path.$new_id.$pict_ext;
			make_dir($path);
			copy($_FILES['new_'.$this->name]['tmp_name'], $filename);	
			chmod($filename, FILE_RIGHTS);
			list($w, $h) = getimagesize($filename);
			if(!empty($pre_param)){
				$pre_size = $this->get_pict_sizes($pre_param,$w,$h);
				$i = 1;
				foreach($pre_size as $ps){
					resize_then_crop($filename,$path.'pre'.$i.'_'.$new_id.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
					chmod($path.'pre'.$i.'_'.$new_id.$pict_ext, FILE_RIGHTS);
					$i++;
				}									
			}			
			$h = round(($h/($w/50)), 0);
			$w=50;			
			resize_then_crop($filename,$path.'pre'.$new_id.$pict_ext,$w,$h,255,255,255); 
			chmod($path.'pre'.$new_id.$pict_ext, FILE_RIGHTS); 
		}	
		return;
	}
	
		
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;	
		$text = '';
		$path = $this->folder;		
		$fol = (int)($id_row/1000);
		$path .= $fol.'/';			
		
		$filename = $path.'pre'.$id_row.$value;												
		if(file_exists($filename)){
			$text .= '<img src="'.$filename.'" align="left">';
		}
		$text .=  '<input class="form-control" type="file" name="edit_'.$id_row.'_'.$this->name.'" onchange="editrad_'.$id_row.'_'.$this->name.'[1].checked=true;"><br>';
		$text .=  '<label> <input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" checked value="no">не изменять</label>
			<label> <input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="yes">Изменить</label> 
			<label> <input type="radio" name="editrad_'.$id_row.'_'.$this->name.'" value="del">Удалить</label> ';
		return $text;
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			if(is_file($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'])){									
				$pict_ext = '';
				$ext = array(".gif", ".jpg", ".jpeg", ".png");
				for($i=0;$i<sizeof($ext);$i++){
					if($data = explode($ext[$i], strtolower($_FILES['edit_'.$id_row.'_'.$this->name]['name']))){										
						if(count($data) == 2){
							$pict_ext = $ext[$i];
							break;
						}
					}
				}	
			}
			if(!empty($pict_ext )){													
				$path = $this->folder;
				$pre_param = $this->sizes;
				
				$fol = (int)($id_row/1000);
				$path .= $fol.'/';
				make_dir($path);
				$filename = $path.$id_row.$pict_ext;
	
				$sql_del = "select ".$this->name." from  ".$table_name."  ";
				$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
				$image_ext = $q->select1($sql_del);
				$pict_ext_old = $image_ext[$this->name];
				@unlink($path.$id_row.$pict_ext_old);
				@unlink($path.'pre'.$id_row.$pict_ext_old);
				
				if(!empty($pre_param)){
					$col_pict = $this->get_num_pict($pre_param);
					$i = 1;
					for($i = 1;$i<= $col_pict;$i++){
						if(is_file($path.'pre'.$i.'_'.$id_row.$pict_ext_old))
							@unlink($path.'pre'.$i.'_'.$id_row.$pict_ext_old);
					}									
				}													
				copy($_FILES['edit_'.$id_row.'_'.$this->name]['tmp_name'], $filename);	
				chmod($filename, FILE_RIGHTS);
				list($w, $h) = getimagesize($filename);
				if(!empty($pre_param)){
					$pre_size = $this->get_pict_sizes($pre_param,$w,$h);
					$i = 1;
					foreach($pre_size as $ps){
						resize_then_crop($filename,$path.'pre'.$i.'_'.$id_row.$pict_ext,$ps['w'],$ps['h'],255,255,255); 
						chmod($path.'pre'.$i.'_'.$id_row.$pict_ext, FILE_RIGHTS);
						$i++;
					}									
				}
				$h = round(($h/($w/50)), 0);
				$w=50;			
				resize_then_crop($filename,$path.'pre'.$id_row.$pict_ext,$w,$h,255,255,255); 
				chmod($path.'pre'.$id_row.$pict_ext, FILE_RIGHTS);
			}
		}else{//if(get_param('editrad_'.$id_row.'_'.$this->cols[$k]['name']) == 'yes'){
				if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
					$sql_del = "select ".$this->name." from  ".$table_name."  ";
					$sql_del .= " where ". $table_id_key."=".to_sql($id_row)." ";
					$image_ext = $q->select1($sql_del);
					$pict_ext = $image_ext[$this->name];
					$path = $this->folder;	
					$pre_param = $this->sizes;	
					
					$fol = (int)($id_row/1000);
					$path .= $fol.'/';
					
					$filename = $path.$id_row.$pict_ext;
					if(file_exists($filename)){										
						@unlink($filename);
						@unlink($path.'pre'.$id_row.$pict_ext);
						if(!empty($pre_param)){
							$col = $this->get_num_pict($pre_param);
							$i = 1;
							for($i = 1;$i <= $col;$i++){
								if(is_file($path.'pre'.$i.'_'.$id_row.$pict_ext))
									@unlink($path.'pre'.$i.'_'.$id_row.$pict_ext);
							}									
						}																
					}
				}
		}//else if(get_param('editrad_'.$id_row.'_'.$this->cols[$k]['name']) == 'yes'){
	

		if(get_param('editrad_'.$id_row.'_'.$this->name) == 'yes'){
			return " ".$this->name." = ".to_sql($pict_ext).",";
		}else{
			if(get_param('editrad_'.$id_row.'_'.$this->name) == 'del'){
					return " ".$this->name." = '',";
			}
		}
	}
	
	
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$sql = "select ".$this->name." from  ".$table_name."  ";
		$sql .= " where ".$table_id_key."=".to_sql($id_row)." ";
		$image_ext = $q->select1($sql);
		$pict_ext = $image_ext[$this->name];
		$path = $this->folder;	
		
		$fol = (int)($id_row/1000);
		$path .= $fol.'/';
		
		$pre_param = $this->sizes;	
		$filename = $path.$id_row.$pict_ext;
		if(file_exists($filename)){										
			@unlink($filename);
			@unlink($path.'pre'.$id_row.$pict_ext);
			if(!empty($pre_param)){
				$col = $this->get_num_pict($pre_param);
				for($i = 1;$i <= $col;$i++){
					if(is_file($path.'pre'.$i.'_'.$id_row.$pict_ext))
						@unlink($path.'pre'.$i.'_'.$id_row.$pict_ext);
				}									
			}									
			
		}
	}	
	/* draw */
	function draw($value,$id =''){
		$path = $this->folder;					

		$fol = (int)($id/1000);
		$path .= $fol.'/';
		
		$filename = $path.'pre'.$id.$value;
		if(file_exists($filename)){
			return '<img src="'.$filename.'?z='.rand(1,9999).'">';
		}else{
			return 'нет';
		}		
	}	
}

class c_outselect extends ctrl{
	var $table,$field_id,$field_name;
	var $con_table, $con_field_id, $con_field_id2,$beg_value;


	function c_outselect($caption, $name, $values,$con_table, $con_field_id, $con_field_out_id,$con_field_out_id_value,$con_val_name,$beg_value=''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = 0;
		$this->edit = 1;		
		$this->values = $values; 
		$this->con_table = $con_table; // имя таблицы связи
		$this->con_field_id = $con_field_id; //название поля где хранится ид1 
		$this->con_field_out_id = $con_field_out_id; //название поля где хранится ид2 во внешней таблице
		$this->con_field_out_id_value = $con_field_out_id_value; //значение поля где хранится ид2
		$this->con_val_name = $con_val_name; //название поля где хранится значение в присоедененной таблице
		$this->beg_value = $beg_value; // начальное значение
	}	
	function get_sql(){
		return '';
	}
	
	function insert(){	
		return '';
	}
	function after_insert($id){
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('new_'.$this->name);
		
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		if(!empty($val)){
			$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id.",
			".$this->con_field_out_id."=".$this->con_field_out_id_value.",
			".$this->con_val_name."=".to_sql($val));
		}
		
		return;
	}
	/* edit */
	function get_edit_sql(){
		return '';
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		return "";
	}
	function after_update($id_row){		
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('edit_'.$id_row.'_'.$this->name);
	
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		if(!empty($val)){
			$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id_row.",
			".$this->con_field_out_id."=".$this->con_field_out_id_value.",
			".$this->con_val_name."=".to_sql($val));
		}
		
		return;
	}
	
	
	/* insert */
	function draw_add(){
		global $inc_path;	
		
		$text = '<select name="new_'.$this->name.'" >';
		foreach($this->values as $k1=>$v1){
			$text .= '<option value="'.$k1.'">'.$v1;
		}
		$text .= '</select>';
		return $text;		
	}	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;
		$q = new query();
		$cats = $q->select1("select ".$this->con_val_name." from ".$this->con_table." where ".$this->con_field_id."=".$id_row."  and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		$str = $cats[$this->con_val_name];
		
		$text = '
		<select name="edit_'.$id_row.'_'.$this->name.'" >';
		foreach($this->values as $k1=>$v1){
			$text .= '<option value="'.$k1.'" ';
			if ($str == $k1) $text .= ' selected ';
			$text .= '>'.$v1;
		}
		$text .= '</select>';		
		return $text;
	}
	/* delete */
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		return;
	}	
	
	
}

class c_outmultiselect extends ctrl{
	var $table,$field_id,$field_name;
	var $con_table, $con_field_id, $con_field_id2,$beg_value;


	function c_outmultiselect($caption, $name, $values,$con_table, $con_field_id, $con_field_out_id,$con_field_out_id_value,$con_val_name,$beg_value=''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = 0;
		$this->edit = 1;		
		$this->values = $values; 
		$this->con_table = $con_table; // имя таблицы связи
		$this->con_field_id = $con_field_id; //название поля где хранится ид1 
		$this->con_field_out_id = $con_field_out_id; //название поля где хранится ид2 во внешней таблице
		$this->con_field_out_id_value = $con_field_out_id_value; //значение поля где хранится ид2
		$this->con_val_name = $con_val_name; //название поля где хранится значение в присоедененной таблице
		$this->beg_value = $beg_value; // начальное значение
	}	
	function get_sql(){
		return '';
	}
	
	function insert(){	
		return '';
	}
	function after_insert($id){
		/*вставить в таблицу записи*/
		$q = new query();
		
		$vals = get_param('new_'.$this->name);
		
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		foreach($vals as $val){
			if(!empty($val)){
				$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id.",
				".$this->con_field_out_id."=".$this->con_field_out_id_value.",
				".$this->con_val_name."=".to_sql($val));
			}
		}
		
		return;
	}
	/* edit */
	function get_edit_sql(){
		return '';
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		return "";
	}
	function after_update($id_row){		
		/*вставить в таблицу записи*/
		$q = new query();
		$vals = get_param('edit_'.$id_row.'_'.$this->name);
	
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		foreach($vals as $val){
			if(!empty($val)){
				$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id_row.",
				".$this->con_field_out_id."=".$this->con_field_out_id_value.",
				".$this->con_val_name."=".to_sql($val));
			}
		}
		return;
	}
	
	
	/* insert */
	function draw_add(){
		global $inc_path;	
		

		$text = '';	
		foreach($this->values as $k1=>$v1){
			$text .= '<div><label><input type="checkbox" value="'.$k1.'"  name="new_'.$this->name.'[]" >'.$v1.'</label></div>';
		}
		
		return $text;		
	}	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;
		$text='';
		$q = new query();
		$cats = $q->select("select ".$this->con_val_name." from ".$this->con_table." where ".$this->con_field_id."=".$id_row."  and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		$vals = array();
		foreach($cats as $v){
			$vals[] = $v[$this->con_val_name];	
		}
		
		foreach($this->values as $k1=>$v1){
			$text .= '<div><label><input type="checkbox"  name="edit_'.$id_row.'_'.$this->name.'[]" value="'.$k1.'" ';
			if (in_array($k1,$vals)) $text .= ' checked ';
			$text .= '>'.$v1.'</label></div>';
		}
		return $text;
	}
	/* delete */
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		return;
	}	
	
	
}



class c_outfield extends ctrl{
	var $table,$field_id,$field_name;
	var $con_table, $con_field_id, $con_field_id2,$beg_value;


	function c_outfield($caption, $name, $con_table, $con_field_id, $con_field_out_id,$con_field_out_id_value,$con_val_name,$beg_value=''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = 0;
		$this->edit = 1;		

		$this->con_table = $con_table; // имя таблицы связи
		$this->con_field_id = $con_field_id; //название поля где хранится ид1 
		$this->con_field_out_id = $con_field_out_id; //название поля где хранится ид2 во внешней таблице
		$this->con_field_out_id_value = $con_field_out_id_value; //значение поля где хранится ид2
		$this->con_val_name = $con_val_name; //название поля где хранится значение в присоедененной таблице
		$this->beg_value = $beg_value; // начальное значение
	}	
	function get_sql(){
		return '';
	}
	
	function insert(){	
		return '';
	}
	function after_insert($id){
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('new_'.$this->name);
		
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		//if(!empty($val)){
			$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id.",
			".$this->con_field_out_id."=".$this->con_field_out_id_value.",
			".$this->con_val_name."=".to_sql($val));
		//}
		
		return;
	}
	/* edit */
	function get_edit_sql(){
		return '';
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		return "";
	}
	function after_update($id_row){		
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('edit_'.$id_row.'_'.$this->name);
	
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		//if(!empty($val)){
			$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id_row.",
			".$this->con_field_out_id."=".$this->con_field_out_id_value.",
			".$this->con_val_name."=".to_sql($val));
		//}
		
		return;
	}
	
	
	/* insert */
	function draw_add(){
		global $inc_path;		
		return '<input type="text" name="new_'.$this->name.'" id="new_'.$this->name.'" style="width:500px;" value="'.$this->beg_value.'" >';
		
	}	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;
		$q = new query();
		$cats = $q->select1("select ".$this->con_val_name." from ".$this->con_table." where ".$this->con_field_id."=".$id_row."  and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		$str = $cats[$this->con_val_name];
	
		return '<input type="text" name="edit_'.$id_row.'_'.$this->name.'" id="edit_'.$id_row.'_'.$this->name.'" value="'.htmlspecialchars($str).'" style="width:500px;">';
			
		
		
	}
	/* delete */
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		return;
	}	
}

class c_outcheckbox extends ctrl{
	var $table,$field_id,$field_name;
	var $con_table, $con_field_id, $con_field_id2,$beg_value;


	function c_outcheckbox($caption, $name, $con_table, $con_field_id, $con_field_out_id,$con_field_out_id_value,$con_val_name,$beg_value=''){
		$this->caption = $caption;
		$this->name = $name;
		$this->list = 0;
		$this->edit = 1;		

		$this->con_table = $con_table; // имя таблицы связи
		$this->con_field_id = $con_field_id; //название поля где хранится ид1 
		$this->con_field_out_id = $con_field_out_id; //название поля где хранится ид2 во внешней таблице
		$this->con_field_out_id_value = $con_field_out_id_value; //значение поля где хранится ид2
		$this->con_val_name = $con_val_name; //название поля где хранится значение в присоедененной таблице
		$this->beg_value = $beg_value; // начальное значение
	}	
	function get_sql(){
		return '';
	}
	
	function insert(){	
		return '';
	}
	function after_insert($id){
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('new_'.$this->name);
		if(!empty($val)) $val = 1;
		else $val = 0;
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		//if(!empty($val)){
			$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id.",
			".$this->con_field_out_id."=".$this->con_field_out_id_value.",
			".$this->con_val_name."=".to_sql($val));
		//}
		
		return;
	}
	/* edit */
	function get_edit_sql(){
		return '';
	}
	function update($id_row, $table_name = '', $table_id_key = ''){
		return "";
	}
	function after_update($id_row){		
		/*вставить в таблицу записи*/
		$q = new query();
		$val = get_param('edit_'.$id_row.'_'.$this->name);
		if(!empty($val)) $val = 1;
		else $val = 0;
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		//if(!empty($val)){
			$q->insert("insert into ".$this->con_table." set ".$this->con_field_id."=".$id_row.",
			".$this->con_field_out_id."=".$this->con_field_out_id_value.",
			".$this->con_val_name."=".to_sql($val));
		//}
		
		return;
	}
	
	
	/* insert */
	function draw_add(){
		global $inc_path;		
		return '<input type="checkbox" class="minimal" name="new_'.$this->name.'" id="new_'.$this->name.'" 
		'.($this->beg_value == 1 ? ' checked' : '').' >';
		
	}	
	/* edit */
	function draw_edit($id_row,$value){
		global $inc_path;
		$q = new query();
		$cats = $q->select1("select ".$this->con_val_name." from ".$this->con_table." where ".$this->con_field_id."=".$id_row."  and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		$str = $cats[$this->con_val_name];
		if($str == 1){
			return '<input type="checkbox" class="minimal" name="edit_'.$id_row.'_'.$this->name.'" id="edit_'.$id_row.'_'.$this->name.'" checked>';
		}else{
			return '<input type="checkbox" class="minimal" name="edit_'.$id_row.'_'.$this->name.'" id="edit_'.$id_row.'_'.$this->name.'" >';
		}
			
		
		
	}
	/* delete */
	function delete($id_row, $table_name = '', $table_id_key = ''){
		$q = new query();
		$q->exec("delete from ".$this->con_table." where ".$this->con_field_id."=".$id_row." and ".$this->con_field_out_id."=".$this->con_field_out_id_value."");
		return;
	}	
}


?>
<? endif; ?>