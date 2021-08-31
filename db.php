<?php

/**
 * Programnas.com
 * db.php
 * 2021-04-27 05:02:08
 * Created by: Mohammed D Mirzada
 */

class db{

	const SERVER_NAME = "localhost";
	const USERNAME = "root";
	const PASSWORD = "";
	const DATABASE_NAME = "db";

	public $_connect, $_inserted_last_id, $_results;
	public $_mysqli_logs = array();

	//CONNECTING TO THE DATABASE
	public function __construct(){
		$mysqli = new mysqli(
			self::SERVER_NAME,
			self::USERNAME,
			self::PASSWORD,
			self::DATABASE_NAME
		);
		//DETECT IF THERE WAS A FAILURE CONNECTION
		if ($mysqli->connect_error) {
			die("Connection failed: " . $mysqli->connect_error);
		}else{
			$this->_connect = $mysqli;
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Database Connected Successfully");
		}
	}

	//INSERT DATA INTO DATABASE
	public function Insert($table_name,$array){
		$row_names = "(";
		$column_values = "VALUES (";
		$i = 0;
		$len = count($array);
		foreach ($array as $key => $value) {
		    $row_names .= $this->PreventXSSandSQLinjection($key);
		    $column_values .= "'".$this->PreventXSSandSQLinjection($value)."'";

		    if ($len != 1 && $i != $len - 1) {
		    	$row_names .= ", ";
		    	$column_values .= ", ";
		    }
		    $i++;
		}
		$row_names .= ") ";
		$column_values .= ")";
		//QUERY
		$sql = "INSERT INTO ".$table_name." ".$row_names.$column_values."";

		if ($this->_connect->query($sql) === TRUE) {
			//log
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Inserted Successfully");
			//inserted last id
			$this->_inserted_last_id = $this->_connect->insert_id;
			//true means inserted
			return true;
		}else{
			//log
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Error: " . $this->_connect->error);
			//false means there is an insertion error
			return false;
		}
		//RETURN BOOLEAN
	}

	//UPDATE DATABASE TABLE
	public function Update($table_name,$array,$id){
		$data = "";
		$i = 0;
		$len = count($array);
		foreach ($array as $key => $value) {
			$data .= $this->PreventXSSandSQLinjection($key)."="."'".$this->PreventXSSandSQLinjection($value)."'"."";
		    if ($len != 1 && $i != $len - 1) {
		    	$data .= ", ";
		    }
		    $i++;
		}

		//QUERY
		$sql = "UPDATE ".$table_name." SET ".$data." WHERE id = ".$id."";

		if ($this->_connect->query($sql) === TRUE) {
			//log
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Updated Successfully");
			//true means updated
			return true;
		}else{
			//log
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Error: " . $this->_connect->error);
			//false means there is an updating error
			return false;
		}
		//RETURN BOOLEAN
	}

	//DELETE DATA FROM DATABASE TABLE
	public function Delete($table,$id){
		//QUERY
		$sql = "DELETE FROM ".$table." WHERE id=".$id."";

		if ($this->_connect->query($sql) === TRUE) {
			//log
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Deleted Successfully");
			//true means deleted
			return true;
		} else {
			//log
			array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Error: " . $this->_connect->error);
			//false means there is an deleting error
			return false;
		}
		//RETURN BOOLEAN
	}

	//SELECT DATA FROM DATABASE TABLE
	public function Get($table, $where=null, $limit=null, $tb_order='DESC'){
		//QUERY
		if ($where == null) {
			if ($limit == null) {
				$sql = "SELECT * FROM ".$table." ORDER BY id ".$tb_order."";
			}else{
				$sql = "SELECT * FROM ".$table." ORDER BY id ".$tb_order." LIMIT ".$limit."";
			}
		}else{
			if ($limit == null) {
				$sql = "SELECT * FROM ".$table." WHERE ".$where." ORDER BY id ".$tb_order." ";
			}else{
				$sql = "SELECT * FROM ".$table." WHERE ".$where." ORDER BY id ".$tb_order." LIMIT ".$limit."";
			}
		}

		//log
		array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Selected Successfully");

		$result = $this->_connect->query($sql);
		$this->_results = $result->fetch_all(MYSQLI_ASSOC);
		return $this;
	}

	//COUNT DATA FROM DATABASE TABLE
	public function Count($table, $where=null, $limit=null){
		//QUERY
		if ($where == null) {
			if ($limit == null) {
				$sql = "SELECT * FROM ".$table."";
			}else{
				$sql = "SELECT * FROM ".$table." LIMIT ".$limit."";
			}
		}else{
			if ($limit == null) {
				$sql = "SELECT * FROM ".$table." WHERE ".$where." ";
			}else{
				$sql = "SELECT * FROM ".$table." WHERE ".$where." LIMIT ".$limit."";
			}
		}

		//log
		array_push($this->_mysqli_logs, "(" . date("Y-m-d H:i:s") . ") Selected Successfully");
		$result = $this->_connect->query($sql);
		return $result->num_rows;

		//RETURN INT
	}

	//GET RESULTS
	public function results(){
		return $this->_results;
		//RETURN ARRAY
	}

	//INSERTED LAST ID
	public function last_inserted_id(){
		return $this->_inserted_last_id;
		//RETURN ID
	}

	//MYSQLI ERROR LOGS
	public function error_logs($last_log=false){
		$all_logs = '';
		foreach ($this->_mysqli_logs as $log) {
			$all_logs .= $log . '<br>';
		}
		return ($last_log) ? end($this->_mysqli_logs) : $all_logs ;
		//RETURN STRING
	}

	public function PreventXSSandSQLinjection($str){
		return strip_tags(
			filter_var(htmlspecialchars(
				mysqli_real_escape_string($this->_connect, $str), ENT_QUOTES, 'UTF-8'
			), FILTER_SANITIZE_STRING)
		);
	}

	//CLOSE MYSQLI
	public function __destruct() {
		mysqli_close($this->_connect);
	}

}


?>