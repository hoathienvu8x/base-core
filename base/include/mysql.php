<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

class MySql {
	private $queryCount = 0;	
	private $conn = null;	
	private $result = null;	
	private $log_file = 'sql_log.sql';	
	private static $instance = null;	
	private function __construct() {
		if (!function_exists('mysql_connect')) {
			access_response(array(
				'status' => 'error',
				'url' => SITE_URL,
				'msg' => 'PHP does not support MySQL server'
			));
		}
		if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASSWD')) {
			access_response(array(
				'status' => 'error',
				'url' => SITE_URL,
				'msg' => 'Undefined configuration !'
			));
		}
		if (!$this->conn = @mysql_connect(DB_HOST, DB_USER, DB_PASSWD)) {
			access_response(array(
				'status' => 'error',
				'url' => SITE_URL,
				'msg' => '#'.$this->geterrno().' ['.$this->geterror().']'
			));
		}
		if ($this->getMysqlVersion() > '4.1') {
			mysql_query("set names 'utf8'");
		}
		@mysql_select_db(DB_NAME, $this->conn) or access_response(array(
			'status' => 'error',
			'url' => SITE_URL,
			'msg' => 'No database selected.'
		));
	}	
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new MySql();
		}
		return self::$instance;
	}
	function close() {
		return mysql_close($this->conn);
	}	
	private function sqllog($sql, $error = ''){
		$fp = fopen(SITE_ROOT . '/' . $this->log_file, 'a');
		fwrite($fp, "[".date('Y-m-d H:i:s')."]: ".$sql."\n".(!empty($error) ? $error ."\n" : ""));
		fclose($fp);
	}	
	function query($sql) {
		$this->result = @mysql_query($sql, $this->conn);
		if (defined('DEBUG_SQL')) {
			$this->sqllog($sql."\n");
		}
		$this->queryCount++;
		if (!$this->result) {
			$this->sqllog($sql, $this->geterror());
			access_response(array(
				'status' => 'error',
				'url' => SITE_URL,
				'msg' => 'Query error : '.$sql.'<br />'.$this->geterror()
			));
		}
		return $this->result;
	}	
	function fetch_array($query , $type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $type);
	}
	function once_fetch_array($sql) {
		$this->result = $this->query($sql);
		return $this->fetch_array($this->result);
	}	
	function fetch_row($query) {
		return mysql_fetch_row($query);
	}	
	function num_rows($query) {
		return mysql_num_rows($query);
	}	
	function num_fields($query) {
		return mysql_num_fields($query);
	}	
	function insert_id() {
		return mysql_insert_id($this->conn);
	}	
	function geterror() {
		return mysql_error();
	}	
	function geterrno() {
		return mysql_errno();
	}	
	function affected_rows() {
		return mysql_affected_rows();
	}	
	function getMysqlVersion() {
		return mysql_get_server_info();
	}	
	function getQueryCount() {
		return $this->queryCount;
	}	
	function escape_string($sql) {
		return mysql_real_escape_string($sql);
	}
}