<? if (!defined("_pages_inc")) : ?>
<? define("_pages_inc",1); ?>
<?php

class pages{
	var $table;
	var $main_page;
	var $main_page_title;
	function pages($table = '',$main_page='Главная', $main_page_title = 'index.php'){
		global $prefix;		
		if(empty($table)) $table = $prefix.'pages';
		$this->table = $table;
		$this->main_page = $main_page;
		$this->main_page_title = $main_page_title;
		$query = "select id from ".$table." where parent=0";
		$q = new query($query);
		$temp = $q->select1();
		if (!$temp) {
			$query = "insert into ".$table." (name, parent,dir_name) values (".to_sql($main_page).", 0,'index')";
			$id = $q->insert($query);      
		}   
	}
	function has_children($parentid=0){//возвращает не 0, если есть потомки
		if(empty($parentid)) $parentid = 0;
		$query = "select id from ".$this->table." where parent = ".to_sql($parentid)." LIMIT 0,1";
		$q = new query($query);
		return ($q->select1());    
	}
	function level($ctgr=0){
		if($ctgr == $this->root()) return 1;
		$query = "select parent from ".$this->table." where id = ".to_sql($ctgr);
		$q = new query($query);
		$temp = $q->select1();
		return 1+$this->level($temp['parent']);
	}

	function root(){
		$query = "select id from ".$this->table." where parent = 0";
		$q = new query($query);
		$temp = $q->select1();
		if($temp) return $temp['id'];
			else return 0;
	}

	function children($parentid=0){
		if($parentid < 0 || $parentid == '') return array();
		$rank_order = " order by rank desc";
		$query = "select id, name, dir_name,template,rank,parent,block   from ".$this->table." where parent=".to_sql($parentid).$rank_order;
		$q = new query($query);
		$temp = $q->select();
		return $temp;
	}

	function parent($id=0){
		$query = "select parent from ".$this->table." where id=".to_sql($id);
		$q = new query($query);
		$temp = $q->select1();
		$parent = $temp ? $temp['parent'] : 0;
		return $parent;
	}
	
	function insert($name, $name_dir, $ctgr, $type_in){
		$name_dir = translit($name_dir);
		if(empty($name_dir)) $name_dir = CleanFileName(translit($name));
		if(empty($name)|| empty($name_dir)){
			echo '<font style="color:red">Заполнены не все поля</font>';
			return -1;
		}
		
		if($ctgr){
			$q = new query();
			$rank_name = ", rank";
			switch($type_in){
				case 'in' :
				  $rank_value = ", '".$this->next_rank($ctgr)."'";
				  $id = $q->insert("insert into ".$this->table." (name, dir_name, parent".$rank_name.") values (".to_sql($name).",".to_sql($name_dir).", ".to_sql($ctgr)." ".$rank_value.")");
				  if($id){
					  return $id;
				  }
				  break;
				case 'after' :
					$parent = $q->select1("select parent from ".$this->table." where id=".to_sql($ctgr));
					if($parent['parent'] == 0){
						echo '<font style="color:red">Не правильно выбрана структура, нельзя добавить страницу ПОСЛЕ главной.</font>';
						return -1;
					}
					$sql = "select id from ".$this->table." where parent=".to_sql($parent['parent']);
					$sql .= " order by rank";
					$OrdList = $q->select($sql);		
					$n=1;
					foreach ($OrdList as $row){
						if($row['id'] == $ctgr){
							$rank_value = ", '".$n."'";
							$n++;
						}
						$sql = "update ".$this->table." set rank = ".$n." where id =  ".to_sql($row['id']);
						$q->exec($sql);		
						$n++;			
					}
				  $id = $q->insert("insert into ".$this->table." (name, dir_name, parent".$rank_name.") values (".to_sql($name).",".to_sql($name_dir).", ".to_sql($parent['parent'])." ".$rank_value.")");
				  if($id){
					  return $id;
				  }
				  break;
			case 'before' :
					$parent = $q->select1("select parent from ".$this->table." where id=".to_sql($ctgr));
					if($parent['parent'] == 0){
						echo '<font style="color:red">Не правильно выбрана структура, нельзя добавить страницу ДО главной.</font>';
						return -1;
					}
					$sql = "select id from ".$this->table." where parent=".to_sql($parent['parent']);
					$sql .= " order by rank";
					$OrdList = $q->select($sql);		
					$n=1;
					foreach ($OrdList as $row){
						
						$sql = "update ".$this->table." set rank = ".$n." where id =  ".to_sql($row['id']);
						$q->exec($sql);		
						if($row['id'] == $ctgr){
							$n++;
							$rank_value = ", '".$n."'";
						}
						$n++;			
					}
				  $id = $q->insert("insert into ".$this->table." (name, dir_name, parent".$rank_name.") values (".to_sql($name).",".to_sql($name_dir).", ".to_sql($parent['parent'])." ".$rank_value.")");
				  if($id){
					  return $id;
				  }
				  break;
		}
			
		}
		
		return 0;
	}

	function delete($ctgr=0){
		if($ctgr && !$this->has_children($ctgr)){
			$q = new query();
			$q->exec("delete from ".$this->table." where id=".to_sql($ctgr));
		}
	}

  function rename($name, $ctgr=0){
    $q = new query("update ".$this->table." set name=".to_sql($name).", created=created where id=".to_sql($ctgr));
    $temp = $q->update();
  }

  function all_id(){
    $q = new query("select id from ".$this->table);
    $temp = $q->select();
    if(!$temp) return array();
    return $temp;
  }

  function get_page_with_children(){//категории, имеющие потомков
    $q = new query("select distinct parent from ".$this->table);
    $temp = $q->select();
    if(!$temp) return array();
    return $temp;
  }

  function get_page_name($id){//имя категории
    $q = new query("select name from ".$this->table." where id=".to_sql($id));
    $temp = $q->select1();
    $name = $temp ? $temp['name'] : 0;
    return $name;
  }
  function get_page_dir($id){//имя категории
    $q = new query("select dir_name from ".$this->table." where id=".to_sql($id));
    $temp = $q->select1();
    $name = $temp ? $temp['dir_name'] : 0;
    return $name;
  }
   function get_page_text($id){//имя категории
    $q = new query("select text from ".$this->table." where id=".to_sql($id));
    $temp = $q->select1();
    $name = $temp ? $temp['text'] : '';
    return $name;
  }

  
   function get_page_title($id){//имя категории
    $q = new query("select title from ".$this->table." where id=".to_sql($id));
    $temp = $q->select1();
    $name = $temp ? $temp['title'] : '';
    return $name;
  }
   function get_page_description($id){//имя категории
    $q = new query("select description from ".$this->table." where id=".to_sql($id));
    $temp = $q->select1();
    $name = $temp ? $temp['description'] : '';
    return $name;
  }
   function get_page_keywords($id){//имя категории
    $q = new query("select keywords from ".$this->table." where id=".to_sql($id));
    $temp = $q->select1();
    $name = $temp ? $temp['keywords'] : '';
    return $name;
  }
  
  
  
  
  function clear(){
    $q = new query();
    $q->exec("delete from ".$this->table);
  }

  function next_rank($ctgr){
  //вычисляем ранг для вставляемой категории
    //получаем максимальный ранг среди потомков категории $ctgr
    $query = "select rank from ".$this->table." where parent=".to_sql($ctgr)." order by rank desc limit 1";
    $q = new query($query);
    $data = $q->select1();
    return $data['rank']+1;
  }

	function get_rank($id){
		if(!$this->is_rank) return 0;
		$q = new query("select rank from ".$this->table." where id=".to_sql($id));
		$data = $q->select1();
		return $data['rank'];
	}
	function path_to($ctgr=0, $in_path=1 , $delimiter = ''){
		return '<ol class="breadcrumb"><li><a href="./pages.php">Все страницы</a></li>'.$this->path($this->parent($ctgr), $in_path, $delimiter).'<li>'.$this->get_page_name($ctgr).'</li></ol>';
	}

  function path($ctgr=0, $in_path=1 , $delimiter = ''){
	if(!$ctgr) return;
	if($ctgr == $this->root()) 
		if($in_path == 1 )
			return '<li><a href="?page_id='.$ctgr.'">'.$this->get_page_name($ctgr).'</a></li>'. $delimiter;
		else
			return '<li class="active">'.$this->get_page_name($ctgr).'</li>';
    $query = "select parent,name from ".$this->table." where id = ".to_sql($ctgr);
    $q = new query($query);
    $temp = $q->select1();
	if($in_path == 1 )
		return $this->path($temp['parent'], 1, $delimiter).'<li><a href="?page_id='.$ctgr.'">'.$this->get_page_name($ctgr).'</a></li>'. $delimiter;
	else
		return '<li class="active">'.$this->path($temp['parent'], 1, $delimiter).$this->get_page_name($ctgr).'</li>';
  }
  function min_rank($ctgr=0){
	if(!$ctgr) return 0;
	$q = new query();
	$min = $q->select1("select min(rank) as min from ".$this->table." where parent = ".to_sql($ctgr));
	if($min != 0) return $min['min'];
	else return 0;
  }
  function max_rank($ctgr=0){
	if(!$ctgr) return 0;
	$q = new query();
	$max = $q->select1("select max(rank) as max from ".$this->table." where parent = ".to_sql($ctgr));
	if($max != 0) return $max['max'];
	else return 0;
  }
  function reorder($ctgr=0){
		if(!$ctgr) return 0;
		$q=new query();	
		$sql = "select id from ".$this->table." where parent=".to_sql($ctgr);
		$sql .= " order by rank";
		$OrdList = $q->select($sql);		
		$n=1;
			foreach ($OrdList as $row){
			$sql = "update ".$this->table." set rank = ".$n." where id =  ".to_sql($row['id']);
			$q->exec($sql);		
			$n++;			
		}
	}
	function cat_up($ctgr=0){
		if(!$ctgr) return 0;
		if($ctgr == $this->root()) return 0;
		$q = new query();
		$this_rank = $q->select1("select rank from ".$this->table." where id = ".to_sql($ctgr));
		$new_rank = $this->get_previous_page($ctgr);		
		if($new_rank != 0){
					$q->exec("update ".$this->table." set rank = ".$new_rank['rank']." where id = ".to_sql($ctgr));
					$q->exec("update ".$this->table." set rank = ".$this_rank['rank']." where id =".to_sql($new_rank['id']));
		}
		
	}
	function cat_down($ctgr=0){
		if(!$ctgr) return 0;
		if($ctgr == $this->root()) return 0;
		
		$q = new query();
		$this_rank = $q->select1("select rank from ".$this->table." where id = ".to_sql($ctgr));
		$new_rank = $this->get_next_page($ctgr);		
		if($new_rank != 0){
					$q->exec("update ".$this->table." set rank = ".$new_rank['rank']." where id = ".to_sql($ctgr));
					$q->exec("update ".$this->table." set rank = ".$this_rank['rank']." where id =".to_sql($new_rank['id']));
		}
		
	}

 function get_previous_page($id){
  //вычисляем ранг категории, предшествующей данной
  //при условии, что обе принадлежат одной надкатегории
//первая часть находим предка и ранг категории $id
//вторая часть - поиск "братьев" категории $id с рангом, большим, чем у нее
//order by c2.rank limit 1 - выбирает из найденных с наименьшим рангом
    $query = "select id, rank, parent  from ".$this->table." where id=".to_sql($id);
    $q = new query($query);
    $first = $q->select1();
	$query = "select id, rank  from ".$this->table." where parent=".to_sql($first['parent'])." and rank > ".$first['rank']." order by rank" ;
	$data = $q->select1($query);
    return $data;
  }

  function get_next_page($id){
  //вычисляем ранг категории, следующей за данной
  //при условии, что обе принадлежат одной надкатегории
	//первая часть находим предка и ранг категории $id
//вторая часть - поиск "братьев" категории $id с рангом, меньше, чем у нее
//order by c2.rank desc limit 1 - выбирает из найденных с наибольшем рангом
    $query = "select id, rank, parent  from ".$this->table." where id=".to_sql($id);
    $q = new query($query);
    $first = $q->select1();
	$query = "select id, rank  from ".$this->table." where parent=".to_sql($first['parent'])." and rank < ".$first['rank']." order by rank desc limit 1" ;
	$data = $q->select1();
    $data = $q->select1($query);
    return $data;
  }

	function cat_hide($ctgr=0){
		if(!$ctgr) return 0;
		//if($ctgr == $this->root()) return 0;
		$q = new query();
		$q->exec("update ".$this->table." set status = 0 where id = ".to_sql($ctgr));
	}
	function cat_show($ctgr=0){
		if(!$ctgr) return 0;
		//if($ctgr == $this->root()) return 0;
		$q = new query();
		$q->exec("update ".$this->table." set status = 1 where id = ".to_sql($ctgr));
	}
	function status($ctgr=0){
		if(!$ctgr) return 0;
		$q = new query();
		$temp = $q->select1("select status from  ".$this->table." where id = ".to_sql($ctgr));
		return $temp['status'];
	}
	function get_page_template($ctgr = 0){
		global $prefix;
		if(!$ctgr) return '';
		$q = new query();
		$temp = $q->select1("select template from ".$this->table." where id=".to_sql($ctgr));
		if($temp['template'] == 0 || $temp ==0) return '';
		$tpl = $q->select1("select id,file_ext from ".$prefix."templates where id=".to_sql($temp['template']));
		if($tpl == 0) return '';
		return 'templates/'.$tpl['id'].$tpl['file_ext'];
	}
	function get_page_block($ctgr = 0){
		global $prefix;
		if(!$ctgr) return '';
		$q = new query();
		$temp = $q->select1("select B.id, P.block,P.block_place, B.folder from ".$this->table." as P join ".$prefix."blocks as B on P.block = B.id where P.id=".to_sql($ctgr));	
		return $temp;
	}
	function get_page_link($ctgr, $is_main = 0){
		global $from_root;
		if($ctgr == $this->root() || $ctgr == 0){
			if(!empty($from_root)){
				if($is_main == 0) return $from_root;
				else 	return $from_root;
			}else{
				if($is_main == 0) return '/';
				else 	return '/';							
			}
		}else{
			return $this->get_page_link($this->parent($ctgr),1).$this->get_page_dir($ctgr).'/';
		}
	}
	function admin_get_page_link($ctgr, $is_main = 0){
		global $from_root;
		if($ctgr == $this->root() || $ctgr == 0){			
				if($is_main == 0) return '/';
				else 	return '/site/';							
		}else{
			return $this->admin_get_page_link($this->parent($ctgr),1).$this->get_page_dir($ctgr).'/';
		}
	}	//сгенирировать ветвь
	function generate_site_branch($ctgr=0){
		if(!$ctgr) return;
		if($ctgr == $this->root()){
			$this->generate_site();
		}else{
			$parent = $this->parent($ctgr);
			if($parent == $this->root())
				$this->generate_site($ctgr, -1, '../site/');
			else
				$this->generate_site($ctgr, -1, '..'.$this->admin_get_page_link($this->parent($ctgr)));
		}
		
	}
	//сгенирировать сайт
	function generate_site($ctgr=0, $lev = -1, $path='../'){
		global $from_root;
		$path = realpath($path).'/';
		$q = new query();
		if($ctgr == 0){
			$ctgr = $this->root();
			if($lev == -1) $lev = $this->level($ctgr);
			$dir_path = $path.'site/';
			deleteDirectory($dir_path);
			if(!file_exists($dir_path)){
				//@unlink($filename);
				make_dir($dir_path);
				//chmod($dir_path, DIR_RIGHTS);
			}			
			$filename = $path.'index.php';
			if (!$handle = fopen($filename, 'w+')) {
				 echo "Ошибка создания файла (".$filename.")";
				 return -1;
			}		
			$file_tmp = $path.'admin/'.$this->get_page_template($ctgr);
			if(!empty($file_tmp)){
				if(is_file($file_tmp)){
					$f = fopen($file_tmp ,'r');
					$data = fread($f, filesize($file_tmp));
				}else{
					$data = '{TEXT}';
				}
			}else{				
				$data = '{TEXT}';
			}
			$header = '<? $inc_path = "admin/";	$root_path = ""; include($inc_path."class/header.php");	';
			$header .= '$this_page_id = '.$ctgr.';	';
			$header .= '$q = new query();
			$site_pages = new pages($table=$prefix.\'pages\',$main_page=\'Главная\', $main_page_title = \'index.php\');';
			$header .= '?>';
			//////вставка блока, если есть
			$block = $this->get_page_block($ctgr);
			if($block['block'] != 0 && !empty($block['block'])){
			   $header .= '<? $this_block_id = '.$block['id'].';?>';
				$block_path = 'protoblocks/'.$block['folder'].'/';
				$this->generate_block_file($path,$header,$data,$block['id'],
				$this->get_page_title($ctgr),
				$this->get_page_description($ctgr),
				$this->get_page_keywords($ctgr)
				);
				if(is_file($block_path.'index.php')){
					$f = fopen($block_path.'index.php' ,'r');
					$data_file = fread($f, filesize($block_path.'index.php'));			
					switch($block['block_place']){
						case '0'://заменить текст
							$data = str_replace('{TEXT}', $data_file , $data);
							break;
						case '1'://после текста
						   $data = str_replace('{TEXT}', '{TEXT}'.$data_file , $data);
							break;
						case '2'://до текста
							$data = str_replace('{TEXT}', $data_file.'{TEXT}' , $data);
							break;
					
					}
				}
			
			}
			////////////block_end/////////
			$data = str_replace('{HEADER}', $header , $data);
			$data = str_replace('{TEXT}', $this->get_page_text($ctgr) , $data);
			
			$data = str_replace('{TITLE}', $this->get_page_title($ctgr) , $data);
			$data = str_replace('{DESCRIPTION}', $this->get_page_description($ctgr) , $data);
			$data = str_replace('{KEYWORDS}', $this->get_page_keywords($ctgr) , $data);
			$data = str_replace('{ROOT_DIR}', $from_root , $data);
			
			if (fwrite($handle, $data) === FALSE) {
				echo "Cannot write to file (".$filename.")";
				return -2;
			}
			chmod($filename, FILE_RIGHTS);
			$data = '';
			$header = '';

		}else{
			if($lev == -1) $lev = $this->level($ctgr);
			$dir_path = $path.$this->get_page_dir($ctgr).'/';
			if(!file_exists($dir_path)){
				//@unlink($filename);
				make_dir($dir_path);
				//chmod($dir_path, DIR_RIGHTS);
			}
			$filename = $dir_path.'index.php';
			if (!$handle = fopen($filename, 'w+')) {
				 echo "Ошибка создания файла (".$filename.")";
				 return -1;
			}		
			$file_tmp = $this->get_page_template($ctgr);
			if(!empty($file_tmp)){
				if(is_file($file_tmp)){
					$f = fopen($file_tmp ,'r');
					$data = fread($f, filesize($file_tmp));
				}else{
					$data = '{TEXT}';
				}

			}else{				
				$data = '{TEXT}';
			}


			$to_adm = '';
			for($i=1;$i<=$lev;$i++){
				$to_adm .= '../';
			}
			$header = '<?$inc_path = "'.$to_adm.'admin/"; $root_path="'.$to_adm.'" ;include($inc_path."class/header.php");';
			$header .= '$this_page_id = '.$ctgr.';	';
			$header .= '$q = new query();
			$site_pages = new pages($prefix.\'pages\',$main_page=\'Главная\', $main_page_title = \'index.php\');';
			$header .= '?>';
			//////вставка блока, если есть
			$block = $this->get_page_block($ctgr);
			if($block['block'] != 0 && !empty($block['block'])){
			   $header .= '<? $this_block_id = '.$block['id'].';?>';
				$block_path = 'protoblocks/'.$block['folder'].'/';
				$this->generate_block_file($dir_path,$header,$data,$block['id'],
				$this->get_page_title($ctgr),
				$this->get_page_description($ctgr),
				$this->get_page_keywords($ctgr));
				if(is_file($block_path.'index.php')){
					$data_file = '';
				        if(filesize($block_path.'index.php') > 0){
						$f = fopen($block_path.'index.php' ,'r');
						$data_file = fread($f, filesize($block_path.'index.php'));			
					}
					switch($block['block_place']){
						case '0'://заменить текст
							$data = str_replace('{TEXT}', $data_file , $data);
							break;
						case '1'://после текста
						   $data = str_replace('{TEXT}', '{TEXT}'.$data_file , $data);
							break;
						case '2'://до текста
							$data = str_replace('{TEXT}', $data_file.'{TEXT}' , $data);
							break;
					
					}
				}
			
			}
			////////////block_end/////////
			
			$data = str_replace('{HEADER}', $header , $data);

			$data = str_replace('{TEXT}', $this->get_page_text($ctgr) , $data);
			$data = str_replace('{TITLE}', $this->get_page_title($ctgr) , $data);
			$data = str_replace('{DESCRIPTION}', $this->get_page_description($ctgr) , $data);
			$data = str_replace('{KEYWORDS}', $this->get_page_keywords($ctgr) , $data);
			$data = str_replace('{ROOT_DIR}', $from_root , $data);
			if (fwrite($handle, $data) === FALSE) {
				echo "Cannot write to file (".$filename.")";
				return -2;
			}
			chmod($filename, FILE_RIGHTS);
			$data = '';
			$header = '';
		}		
		$pages = $q->select("select id from  ".$this->table." where parent = ".to_sql($ctgr));
		foreach($pages as $row){
			$this->generate_site($row['id'], $lev+1,$dir_path);
		}
		
	}
	

//сгенирировать страницу
	function generate_page($ctgr=0){
		global $from_root;
		if(!$ctgr) return 0;
		$dir_path = '..'.$this->admin_get_page_link($this->parent($ctgr));
		
		if(!file_exists($dir_path)){
			echo '<font style="color:red">Сгенерируйте страницы более высокого уровня</font>';
		}

			$dir_path = '..'.$this->admin_get_page_link($ctgr);
			if($ctgr != $this->root()){
						if(!file_exists($dir_path)){
							make_dir($dir_path);
							//chmod($dir_path, DIR_RIGHTS);
						}
			}

			$filename = $dir_path.'index.php';
			if (!$handle = fopen($filename, 'w+')) {
				 echo "Ошибка создания файла (".$filename.")";
				 return -1;
			}		
			$file_tmp = $this->get_page_template($ctgr);
			if(!empty($file_tmp)){
				if(is_file($file_tmp)){
					$f = fopen($file_tmp ,'r');
					$data = fread($f, filesize($file_tmp));
				}else{
					$data = '{TEXT}';
				}
			}else{				
				$data = '{TEXT}';
			}
			
			$to_adm = '';
			if($ctgr != $this->root()){
				$lev = $this->level($ctgr);
				for($i=1;$i<=$lev;$i++){
					$to_adm .= '../';
				}
			}
			$header = '<?$inc_path = "'.$to_adm.'admin/"; $root_path="'.$to_adm.'" ; include($inc_path."class/header.php");';
			$header .= '$this_page_id = '.$ctgr.';	';
			$header .= '$q = new query();
			$site_pages = new pages($prefix.\'pages\',$main_page=\'Главная\', $main_page_title = \'index.php\');';
			$header .= '?>';
			
			//////вставка блока, если есть
			$block = $this->get_page_block($ctgr);
			if($block['block'] != 0 && !empty($block['block'])){
			   $header .= '<? $this_block_id = '.$block['id'].';?>';
				$block_path = 'protoblocks/'.$block['folder'].'/';
				$this->generate_block_file($dir_path,$header,$data,$block['id'],
				$this->get_page_title($ctgr),
				$this->get_page_description($ctgr),
				$this->get_page_keywords($ctgr)
				);                                                               

				if(is_file($block_path.'index.php')){
					$f = fopen($block_path.'index.php' ,'r');
					if(filesize($block_path.'index.php') > 0)
						$data_file = fread($f, filesize($block_path.'index.php'));			
					else
						$data_file = '';
					switch($block['block_place']){
						case '0'://заменить текст
							$data = str_replace('{TEXT}', $data_file , $data);
							break;
						case '1'://после текста
						   $data = str_replace('{TEXT}', '{TEXT}'.$data_file , $data);
							break;
						case '2'://до текста
							$data = str_replace('{TEXT}', $data_file.'{TEXT}' , $data);
							break;
					
					}
				}
			
			}
			////////////block_end/////////
			
			$data = str_replace('{HEADER}', $header , $data);
			$data = str_replace('{TEXT}', $this->get_page_text($ctgr) , $data);
			$data = str_replace('{TITLE}', $this->get_page_title($ctgr) , $data);
			$data = str_replace('{DESCRIPTION}', $this->get_page_description($ctgr) , $data);
			$data = str_replace('{KEYWORDS}', $this->get_page_keywords($ctgr) , $data);
			$data = str_replace('{ROOT_DIR}', $from_root , $data);
			if (fwrite($handle, $data) === FALSE) {
				echo "Cannot write to file (".$filename.")";
				return -2;
			}
	}
	
	function generate_block_file($path,$header,$tmpl,$block_id,$title,$descr,$keys){
		global $from_root,$prefix;
		$q = new query();
		$block = $q->select1("select * from ".$prefix."blocks where id=".to_sql($block_id));
		$block_path = 'protoblocks/'.$block['folder'].'/';
		$proto_files = $q->select("select * from ".$prefix."block_files where block_id=".to_sql($block['id'])." and file_name not like '%index.php%'");
		foreach($proto_files as $row){
			$filename = $path.$row['file_name'];
			if (!$handle = fopen($filename, 'w+')) {
				 echo "Ошибка создания файла (".$filename.")";
				 return -1;
			}		
			$file_tmp = $block_path.$row['file_name'];
			if(is_file($file_tmp)){
				$f = fopen($file_tmp ,'r');
				if(filesize($file_tmp) > 0)	$data_file = fread($f, filesize($file_tmp));
			}else{
				continue;
			}
			if($row['gen_page'] == 1){
				$data = str_replace('{HEADER}', $header , $tmpl);				
			}else{
				$data = str_replace('{HEADER}', $header , '{HEADER}{TEXT}');				
			}
			$data = str_replace('{TEXT}', $data_file  , $data);
			$data = str_replace('{TITLE}', $title , $data);
			$data = str_replace('{DESCRIPTION}', $descr , $data);
			$data = str_replace('{KEYWORDS}', $keys , $data);
			$data = str_replace('{ROOT_DIR}', $from_root , $data);
			if (fwrite($handle, $data) === FALSE) {
				echo "Cannot write to file (".$filename.")";
				return -2;
			}			
		}
	
	}
	function draw_pages($ctgr = 0){
		global $inc_path,$prefix;
		$q = new query();
		if($ctgr == 0) $ctgr = $this->root();
		if($ctgr == $this->root()){
			if($this->has_children($ctgr)){
				echo '<div class="tr_div">';
				echo '<div style="float:left;">';
				echo '<i class="fa fa-minus" name="img_page'.$ctgr.'" id="img_page'.$ctgr.'" onclick="showhidepage('.$ctgr.')" style="margin-top:3px;margin-left:-16px;margin-right:5px;"></i>&nbsp;<a href="edit.php?page_id='.$ctgr.'">';
				
				echo $this->get_page_name($ctgr).'</a>';
				echo '</div>';
				echo '<div class="add">';
				if($this->status($ctgr) == 1) 
					echo '<a href="?pmode=cat_hide&hide_id='.$ctgr.'" class="btn_look dd"><i class="fa fa-eye"></i></a>';				
				else
					echo '<a href="?pmode=cat_show&show_id='.$ctgr.'" class="btn_lookno dd"><i class="fa fa-eye-slash"></i></a>';	

				echo '</div>';//<div class="add">
				echo '</div>';//<div class="tr_div">';
			

				echo '				
				<div id="page_div'.$ctgr.'" name="page_div'.$ctgr.'" style="display:block;padding-left:10px;" >
				';
			}else{
				echo '<div class="tr_div">';
				echo '<div style="float:left;">';	
				echo '<a href="edit.php?page_id='.$ctgr.'">';
				echo $this->get_page_name($ctgr).'</a></td>';
				echo '</div>';
				echo '<div class="add">';
				if($this->status($ctgr) == 1) 
					echo '<a href="?pmode=cat_hide&hide_id='.$ctgr.'" class="btn_look dd"><i class="fa fa-eye"></i></a>';				
				else
					echo '<a href="?pmode=cat_show&show_id='.$ctgr.'" class="btn_lookno dd"><i class="fa fa-eye-slash"></i></a>';
					
					
				echo '</div>';//<div class="add">
				echo '</div>';//<div class="tr_div">';
				
			}			
		}
		if($this->has_children($ctgr)){
			$childs = $this->children($ctgr);
			foreach($childs as $row){
				$min_r = $this->min_rank($row['parent']);
				$max_r = $this->max_rank($row['parent']);
				$this->reorder($row['id']);
				echo '
				<!--------------------------->
				<div class="tr_div">';
				echo '<div style="float:left">';
				if($this->has_children($row['id'])){
					echo '<i class="fa fa-plus" name="img_page'.$row['id'].'" id="img_page'.$row['id'].'" onclick="showhidepage('.$row['id'].')" style="margin-top:3px;margin-left:-16px;margin-right:5px;"></i> ';				
				}
								
				if($row['block'] > 0){
					$block = $q->select1("select folder, status from ".$prefix."blocks where id=".to_sql($row['block']));
					if($block['status'] == 1){
						echo '<a href="protoblocks/'.$block['folder'].'/admin/">';
						echo $this->get_page_name($row['id']).'</a>';
						echo ' <a href="edit.php?page_id='.$row['id'].'">[свойства]<a>';	
					}else{
						echo '<a href="edit.php?page_id='.$row['id'].'">';
						echo $this->get_page_name($row['id']).'</a>';						
					}
				}else{
					echo '<a href="edit.php?page_id='.$row['id'].'">';
					echo $this->get_page_name($row['id']).'</a>';					
				}
					
					
				
				echo '</div>';
				//delete
				echo '<div class="add">';
				if(!$this->has_children($row['id'])) 
					echo '<a href="javascript:confirm_page_del(\'?pmode=page_del&del_page_id='.$row['id'].'\');" class="btn_del dd"><i class="fa fa-trash"></i></a>';
				else echo '<div class="dd">&nbsp;</div>';
				
				
				//down
				if($min_r != $row['rank']) 
					echo '<a href="?pmode=page_down&cur_page='.$row['id'].'&move_id='.$row['id'].'" class="btn_bottom dd"><i class="fa fa-arrow-down"></i></a>';
				else echo '<div class="dd">&nbsp;</div>';	
			
					
				//up	
				if($max_r != $row['rank']) 
					echo '<a href="?pmode=page_up&cur_page='.$row['id'].'&move_id='.$row['id'].'" class="btn_top dd"><i class="fa fa-arrow-up"></i></a>';
				else echo '<div class="dd">&nbsp;</div>';
				
				echo '';
				//status	
				if($this->status($row['id']) == 1) 
					echo '<a href="?pmode=cat_hide&cur_page='.$row['id'].'&hide_id='.$row['id'].'" class="btn_look dd"><i class="fa fa-eye"></i></a>';	
				else
					echo '<a href="?pmode=cat_show&cur_page='.$row['id'].'&show_id='.$row['id'].'" class="btn_lookno dd"><i class="fa fa-eye-slash"></i></a>';	
						
						
						
				echo '
				
				</div>';//<div class="add">		
					
				echo '</div>';// '<div class="tr_div">';
				
				if($this->has_children($row['id'])){
					echo '<div id="page_div'.$row['id'].'" name="page_div'.$row['id'].'" style="display:none;padding-left:10px;">';
					$this->draw_pages($row['id']);
					echo '					
					</div>';
				}
				

			}
		}
		if($ctgr == $this->root()){
			if($this->has_children($row['id'])) echo '</div>';
			echo '</div>';
		}
	}
	function draw_list($parent, $lev){
		$childs = $this->children($parent);
		foreach($childs as $row){
			echo '<option value="'.$row['id'].'">';
			for($i=0; $i<=$lev; $i++){
				echo '&nbsp;&nbsp;&nbsp;';
			}
			echo $row['name'];
			if($this->has_children($row['id'])){
				$this->draw_list($row['id'], $lev+1);
			}
		}
	}

	function show_cur_page($page_id){
		if($page_id == $this->root()) return;
		$par = $this->parent($page_id);
		if($par != 0){
			echo 'showpage('.$par.');';
			$this->show_cur_page($par);
		}


	}

	function draw(){
		$pmode = get_param('pmode');		

		if($pmode == "cat_hide" ){//скрыть  категорию
			$this->cat_hide(get_param('hide_id'));
			$pmode = '';
		}
		if($pmode == "cat_show" ){//показать категорию
			$this->cat_show(get_param('show_id'));
			$pmode = '';
		}
		if($pmode == "del_cat" ){//удалить категорию
			$this->delete(get_param('del_id'));
		}
		if($pmode == "page_up" ){//поднять выше категорию
			$this->cat_up(get_param('move_id'));
			$pmode = '';
		}
		if($pmode == "page_down" ){//ниже категорию
			$this->cat_down(get_param('move_id'));
			$pmode = '';
		}
		if($pmode == "page_del" ){//ниже категорию
			$this->delete(get_param('del_page_id'));
			$pmode = '';
		}
		
		if($pmode=='add_page'){
//			echo 'page_name='.$_POST['page_name'].'page_dir'.$_POST['page_dir'].'page_id'.$_POST['page_id'].'type_in'.$_POST['type_in'];
			$res = $this->insert(get_param('page_name'),get_param('page_dir') , get_param('page_id'),get_param('type_in'));
	
			if($res == -1)$pmode = 'badd_page';
			else{
				$q = new query();
				$url = $this->get_page_link($res);
				$q->exec("update ".$this->table." set 
				uri=".to_sql($url)."
				where id=".$res);
				
				$pmode = '';
				header('location: pages.php');
			}
		}
		
		if($pmode=='badd_page'){
			echo '
			<script>
			function check_form(form){
				if(form.page_name.value == \'\'){
					form.page_name.focus();
					alert(\'Не заполено поле "Название страницы"\');
					return false;
				}
				if(form.page_id.value == \'\'){
					form.page_id.focus();
					alert(\'Не выбрано место для вставки "Название страницы"\');
					return false;
				}
				return true;			
			}
			</script>';
			echo '<form id="add_form" method="POST" onsubmit="return check_form(this);" >';
			echo '<input type="hidden" name="pmode" value="add_page">';
			
			echo '<div class="form-group">
						<label for="">Название страницы:</label><input type="text" name="page_name" class="form-control" required>
			</div>';
			
			echo '<div class="form-group"><label for="">Название страницы латинскими буквами:</label>
			<input type="text" name="page_dir" class="form-control">
			
			</div>';
			echo '<div class="form-group"><label for="">вставить:</label>
			<div > 
			<label><input type="radio" name="type_in" value="in" checked>в</label>&nbsp;&nbsp;
			<label><input type="radio" name="type_in" value="before">до</label>&nbsp;&nbsp;
			<label><input type="radio" name="type_in" value="after">после</label> 
			</div>
			</div>';
			
			
			echo '<div class="form-group">
				<select  class="form-control" size="15" name="page_id">';
			echo '<option style="padding-left: 10px;" value="'.$this->root().'" selected>'.$this->get_page_name($this->root());
			$this->draw_list($this->root(),0);
			echo '</select>
			</div>';
		
		
		
			echo '
			<input type="button" class="btn" value="отмена" onClick="document.location.href=\'pages.php\'">
			<input type="submit" value="Добавить" class="btn btn-success">
			';
			echo '</form>';
			
		}
		
		
		
		
		if($pmode=='generate'){
			$this->generate_site();
			$pmode = '';
		}

		if(empty($pmode)){
			echo '<table width="100%"><tr><td>';
			echo '
			<button type="Button" class="btn btn-success" onclick="document.location.href=\'?pmode=badd_page\'"><i class="fa fa- fa-plus"></i> Добавить страницу</button></td><td valign="top" align="right">';
			echo '
			<button class="btn btn-primary" onclick="document.location.href=\'?pmode=generate\'"><i class="fa fa-recycle"></i> Перегенерация страниц</button></td></tr></table><br>';
			$this->draw_pages($ctgr = 0);

			if(!empty($_GET['cur_page'])){
				echo '<script>';
				$this->show_cur_page(get_param('cur_page'));
				echo '</script>';

			}

			
		}

	}
 

}
?>
<? endif; ?>
