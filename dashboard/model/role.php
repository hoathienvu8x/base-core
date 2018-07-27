<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Role {
	const ERROR_INVALID = 1;
	const ERROR_SAVE = 2;
	const ERROR_SAVED = 3;
	const ERROR_UPDATE = 4;
	const ERROR_UPDATED = 5;
	const ERROR_DELETE = 6;
	const ERROR_DELETED = 7;
	const ERROR_NAME = 8;
	const ERROR_EXISTS = 9;
	const ERROR_NOTEXISTS = 10;

	private static $db = null;
	public function __construct() {
		self::$db = Database::getInstance();
	}
	public static function message($code) {
		$msg = 'Hệ thống không hiểu lỗi này !';
		switch ($code) {
			case self::ERROR_NAME:
				$msg = 'Vui lòng nhập vào đúng tên quyền !';
				break;
			case self::ERROR_EXISTS:
				$msg = 'Quyền này đã tồn tại trên hệ thống !';
				break;
			case self::ERROR_NOTEXISTS:
				$msg = 'Không tìm thấy quyền này trên hệt thống !';
				break;
			case self::ERROR_INVALID :
				$msg = 'Vui lòng nhập vào thông tin !';
				break;
			case self::ERROR_SAVE:
                                $msg = 'Lưu thông tin thất bại !';
                                break;
                        case self::ERROR_SAVED:
                                $msg = 'Lưu thông tin thành công !';
                                break;
                        case self::ERROR_UPDATE:
                                $msg = 'Cập nhật thông tin thất bại !';
                                break;
                        case self::ERROR_UPDATED:
                                $msg = 'Cập nhật thông tin thành công !';
                                break;
                        case self::ERROR_DELETE:
                                $msg = 'Xoá thất bại !';
                                break;
                        case self::ERROR_DELETED:
                                $msg = 'Xoá thành công !';
                                break;
		}
		return $msg;
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
