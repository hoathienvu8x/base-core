<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

class MySqlii {
	private $queryCount = 0;	
	private $conn = null;	
	private $result = null;	
	private $log_file = 'sql_log.sql';	
	private static $instance = null;	
	private function __construct() {
		if (!class_exists('mysqli')) {
			app_exit(array(
				'status' => 'error',
				'data' => null,
				'msg' => 'PHP does not support MySQLi server'
			));
		}
		if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASSWD')) {
			app_exit(array(
				'status' => 'error',
				'data' => null,
				'msg' => 'Undefined configuration !'
			));
		}
		@$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
		if ($this->conn->connect_error) {
			app_exit(array(
				'status' => 'error',
				'data' => null,
				'msg' => '#'.$this->conn->connect_errno.' ['.$this->conn->connect_error.']'
			));
		}
		$this->conn->set_charset('utf8');
	}	
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new MySqlii();
		}
		return self::$instance;
	}
	function close() {
		return $this->conn->close();
	}	
	private function sqllog($sql, $error = ''){
		$fp = fopen(SITE_ROOT . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $this->log_file, 'a');
		fwrite($fp, "[".date('Y-m-d H:i:s')."]: ".$sql."\n".(!empty($error) ? $error ."\n" : ""));
		fclose($fp);
	}	
	function query($sql) {
		$this->result = $this->conn->query($sql);
		if (defined('DEBUG_SQL')) {
			$this->sqllog($sql."\n");
		}
		$this->queryCount++;
		if (!$this->result) {
			$this->sqllog($sql, $this->geterror());
			app_exit(array(
				'status' => 'error',
				'data' => null,
				'msg' => 'Query error : '.$sql.'<br />'.$this->geterror()
			));
		}
		return $this->result;
	}	
	function fetch_array(mysqli_result $query , $type = MYSQLI_ASSOC) {
		return $query->fetch_array($type);
	}
	function once_fetch_array($sql) {
		$this->result = $this->query($sql);
		return $this->fetch_array($this->result);
	}	
	function fetch_row(mysqli_result $query) {
		return $query->fetch_row();
	}	
	function num_rows(mysqli_result $query) {
		return $query->num_rows;
	}	
	function num_fields(mysqli_result $query) {
		return $query->field_count;
	}	
	function insert_id() {
		return $this->conn->insert_id;
	}	
	function geterror() {
		return $this->conn->error;
	}	
	function geterrno() {
		return $this->conn->errno;
	}	
	function affected_rows() {
		return $this->conn->affected_rows;
	}	
	function getMysqlVersion() {
		return $this->conn->server_info;
	}	
	function getQueryCount() {
		return $this->queryCount;
	}	
	function escape_string($sql) {
		return $this->conn->real_escape_string($sql);
	}
}