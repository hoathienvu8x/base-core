<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Role {
	private static $db = null;
	public function __construct() {
		self::$db = Database::getInstance();
	}
	public function get($options = array(), $page = 1, $limit = 15) {
		$conds = $orders = array();
		$sql = "select * from " . DB_PREFIX . "roles";
		if (isset($options['keyword'])) {
			$conds[] = "(role_name like '%".self::$db->escape_string($options['keyword'])."%' or role_alias like '%".self::$db->escape_string($options['keyword'])."%' or nickname like '%".self::$db->escape_string($options['keyword'])."%')";
		}
		if (isset($options['role'])) {
			$conds[] = "role_alias = '".$options['role']."'";
		}
		if (!empty($conds)) {
			$sql .= " where " . implode(' and ', $conds);
		}
		if (isset($options['name'])) {
			$orders[] = "role_name ".$options['name'];
		}
		if (empty($orders)) {
			$orders[] = "role_name asc";
		}
		$sql .= " order by " . implode(',',$orders);
		$sql .= " limit " . ( ( $page - 1) * $limit ) . ", " . $limit;
		$query = self::$db->query($sql);
		$rows = array();
		while ($row = self::$db->fetch_array( $query )) {
			$rows[] = $row;
		}
		return $rows;
	}
	public function getOne($id) {
		$sql = "select * from " . DB_PREFIX . "roles where id = '$id'";
		$row = self::$db->once_fetch_array($sql);
		if (!$row) {
			return false;
		}
		return $row;
	}
	public function add($data = array(), $isSyntax = false) {
		if (empty($data)) {
			return -1;
		}
		$keys = $values = array();
		foreach($data as $key => $value) {
			$keys[] = $key;
			$values[] = $isSyntax ? $value : "'".self::$db->escape_string($value)."'";
		}
		self::$db->query("insert into " . DB_PREFIX . "roles ( " . implode(',',$keys) . " ) values (".implode(',',$values).")");
		if (self::$db->affected_rows() > 0) {
			return self::$db->insert_id();
		}
		return -1;
	}
	public function update($data = array(), $id = null, $isSyntax = false) {
		if (empty($data)) {
			return -1;
		}
		$items = array();
		unset($data['id']);
		foreach($data as $key => $value) {
			$items[] = $isSyntax ? "$key = $value" : "$key = '".self::$db->escape_string($value)."'";
		}
		self::$db->query("update " . DB_PREFIX . "roles set ".implode(',',$items)." where id = '$id'");
		if (self::$db->affected_rows() > 0) {
			return $id;
		}
		return -1;
	}
	public function delete($id = null) {
		self::$db->query("delete from " . DB_PREFIX . "roles where id = '$id'");
		if (self::$db->affected_rows() > 0) {
			return $id;
		}
		return -1;
	}
	public function is_role_exists($role, $id = -1) {
		$row = self::$db->once_fetch_array("select count(id) as total from ".DB_PREFIX."roles where role_alias = '".self::$db->escape_string($role)."'".($id > 0 ? " and id <> $id" : ""));
		if ($row && intval($row['total']) > 0) {
			return true;
		}
		return false;
	}
	public static function getRoleName($id) {
		$db = Database::getInstance();
		$row = $db->once_fetch_array("select role_name from " . DB_PREFIX . "roles where id = '$id'");
		if (!$row) {
			return 'N/A';
		}
		return trim($row['role_name']);
	}
}
?>
