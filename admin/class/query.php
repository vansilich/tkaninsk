<? if (!defined("_query_inc")) : ?>
<? define("_query_inc",1); ?>
<?

include "dbconn.php";

class query{
    var $query;

    function query($query=''){
        $this->query = $query;
    }
    function select($query=''){
        global $db;
        if ($query) $this->query = $query;

mysql_query("set names utf8", $db);

        $result = mysql_query($this->query, $db);
        if (!$result){
            $this->error(mysql_error($db));
            return array();
        }
        $rows = array();
        while ($row = mysql_fetch_array($result)) $rows[sizeof($rows)+1] = $row;

        return $rows;
    }
    function select1($query=''){
        $result = $this->select($query);
        if (sizeof($result)==0) return 0;
        return $result[1];
    }
    function insert($query=''){
        global $db;
        if ($query) $this->query = $query;

mysql_query("set names utf8", $db);

        $result = mysql_query($this->query, $db);
        if (!$result){
            $this->error(mysql_error($db));
            return 0;
        }
        return mysql_insert_id($db);
    }
    function exec($query=''){
        global $db;
        if ($query) $this->query = $query;

mysql_query("set names utf8", $db);

        $result = mysql_query($this->query, $db);
        if (!$result){
            $this->error(mysql_error($db));
            return 0;
        }
        return $result;
    }
    function execute($query=''){
      $this->exec($query);
    }
    function update($query=''){
        return $this->exec($query);
    }
    function delete($table, $id){
        $query = "delete from $table where id=$id";
        return $this->exec($query);
    }
    function clearstatus($table, $id=-1, $status=0){
        if ($id == -1){
            $query = "update $table set status=$status";
            return $this->exec($query);
        }else return $this->setstatus($table, $id, 0);
    }
    function setstatus($table, $id, $status=1){
        $query = "update $table set status=$status, created=created";
        $query .= " where id=$id";
        return $this->exec($query);
    }
    function setstatus1($table, $id, $status=1, $created=''){
        $query = "update $table set status=$status";
        if ($created) $query .= ", created=created";
        $query .= " where id=$id";
        return $this->exec($query);
    }
    function error($message){
	global $inc_path;
      if(DEBUG_MODE){

	$filename = $inc_path.'log/mysql.txt';
	$somecontent = date('d-m-y H:i').'
	'.$this->query.'
	'.$message.'
	
	';
	
	
	// Let's make sure the file exists and is writable first.
	if (is_writable($filename)) {		
		if (!$handle = fopen($filename, 'a')) {
			 exit;
		}
		// Write $somecontent to our opened file.
		if (fwrite($handle, $somecontent) === FALSE) {
			exit;
		}
		fclose($handle);
	}
		

        echo '<span style="font-weight: bold; color: red; font-family: Verdana;">MySQL: '.$this->query.'</span><br>';
        echo '<span style="font-weight: bold; color: red; font-family: Verdana;">MySQL: '.$message.'</span><br>';
      }
    }

	//выдает в одномерном массиве все названия полей таблицы $table 
	function list_fields($table){
		global $db;
		global $cms_settings;

		$fields = mysql_list_fields($cms_settings[db_name], $table, $db);
		if(!$fields){
     		$this->error(mysql_error($db));
            return array();
		}

		$columns = mysql_num_fields($fields);
		if(!$columns){
			$this->error(mysql_error($db));
            return array();
		}

		for ($i = 0; $i < $columns; $i++) {
		    $data[$i] = mysql_field_name($fields, $i);
		}
		return $data;
	}
}

?>
<? endif; ?>