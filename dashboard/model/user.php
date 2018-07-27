<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class User {
	const ERROR_NAME_NOTEXIST = 1;
	const ERROR_EMAIL_NOEXISTS = 2;
	const ERROR_LOCKED = 3;
	const ERROR_INVALID = 4;
	const ERROR_NAME_EXISTS = 5;
	const ERROR_NAME_INVALID = 6;
	const ERROR_EMAIL_EXISTS = 7;
	const ERROR_EMAIL_INVALID = 8;
	const ERROR_SAVED = 9;
	const ERROR_SAVE = 10;
	const ERROR_UPDATE = 11;
	const ERROR_UPDATED = 12;
	const ERROR_DELETE = 13;
	const ERROR_DELETED = 14;
	const ERROR_LOCKED = 15;
	const ERROR_LOCK = 16;
	const ERROR_FORGOTED = 17;
	const ERROR_FORGOT = 18;
	const ERROR_FULLNAME_NONE = 19;
	const ERROR_PASSWORD_INVALID = 20;
	const ERROR_STAYLOGIN = 21;
	const ERROR_NOTEXISTS = 22;
	const ERROR_CHANGED = 23;

	private static $db = null;
	public function __construct() {
		self::$db = Database::getInstance();
	}
	public static function message($code) {
		$msg = 'Hệ thống không hiểu lỗi này !';
		switch ($code) {
			case self::ERROR_CHANGED:
				$msg = 'Chuyển quyền thành công !';
				break;
			case self::ERROR_NOTEXISTS:
				$msg = 'Tài khoản này không tồn tại trên hệ thông !';
				break;
			case self::ERROR_STAYLOGIN:
				$msg = 'Bạn đang đăng nhập vui lòng vô thông tin tài khoản để cập nhật';
				break;
			case self::ERROR_NAME_NOTEXIST:
				$msg = 'Tên đăng nhập này không tồn tại !';
				break;
			case self::ERROR_NAME_EXISTS:
				$msg = 'Tên đăng nhập này đã tồn tại !';
				break;
			case self::ERROR_NAME_VALID:
				$msg = 'Tên đăng nhập không hợp lệ !';
				break;
			case self::ERROR_EMAIL_NOTEXIST:
				$msg = 'Email không tồn tại !';
				break;
			case self::ERROR_EMAIL_EXISTS:
				$msg = 'Email này đã tồn tại !';
				break;
			case self::ERROR_EMAIL_INVALID:
				$msg = 'Email không hợp lệ !';
				break;
			case self::ERROR_LOCKED:
				$msg = 'Tài khoản này đang tạm khoá';
				break;
			case self::ERROR_INVALID :
				$msg = 'Vui lòng nhập vào thông tin !';
				break;
			case self::ERROR_SAVED:
				$msg = 'Lưu thông tin thành công !';
				break;
			case self::ERROR_SAVE:
				$msg = 'Lưu thông tin thất bại !';
				break;
			case self::ERROR_FORGOTED:
				$msg = 'Hệ thống đã gửi thông tin đăng nhập vào thư điện tử mà bạn đã đăng ký';
				break;
			case self::ERROR_FORGOT:
				$msg = 'Hệ thống không thể khôi phục thông tin đăng nhập giúp bạn được';
				break;
			case self::ERROR_PASSWORD_INVALID:
				$msg = 'Mật khẩu không hợp lệ !';
				break;
			case self::ERROR_FULLNAME_NONE:
				$msg = 'Vui lòng nhập vào họ và tên !';
				break;
		}
		return $msg;
	}
	public function get($options = array(), $page = 1, $limit = 15) {
		$conds = $orders = array();
		$sql = "select * from " . DB_PREFIX . "users";
		if (isset($options['keyword'])) {
			$conds[] = "(username like '%".self::$db->escape_string($options['keyword'])."%' or email like '%".self::$db->escape_string($options['keyword'])."%' or nickname like '%".self::$db->escape_string($options['keyword'])."%')";
		}
		if (isset($options['role'])) {
			$conds[] = "role = '".$options['role']."'";
		}
		$conds[] = "id <> ".Auth::get('id',-1);
		if (!empty($conds)) {
			$sql .= " where " . implode(' and ', $conds);
		}
		if (isset($options['name'])) {
			$orders[] = "username ".$options['name'];
		}
		if (isset($options['nickname'])) {
			$orders[] = "nickname ".$options['name'];
		}
		if (empty($orders)) {
			$orders[] = "substring_index(nickname,' ',-1) asc";
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
		$sql = "select * from " . DB_PREFIX . "users where id = '$id'";
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
		self::$db->query("insert into " . DB_PREFIX . "users ( " . implode(',',$keys) . " ) values (".implode(',',$values).")");
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
		self::$db->query("update " . DB_PREFIX . "users set ".implode(',',$items)." where id = '$id'");
		if (self::$db->affected_rows() > 0) {
			return $id;
		}
		return -1;
	}
	public function delete($id = null) {
		self::$db->query("delete from " . DB_PREFIX . "users where id = '$id'");
		if (self::$db->affected_rows() > 0) {
			return $id;
		}
		return -1;
	}
	public function is_uname_exists($uname, $uid = -1) {
		$row = self::$db->once_fetch_array("select count(id) as total from ".DB_PREFIX."users where username = '".self::$db->escape_string($uname)."'".($uid > 0 ? " and id <> $uid" : ""));
		if ($row && intval($row['total']) > 0) {
			return true;
		}
		return false;
	}
	public function is_email_exists($email, $uid = -1) {
		$row = self::$db->once_fetch_array("select count(id) as total from ".DB_PREFIX."users where email = '".self::$db->escape_string($email)."'".($uid > 0 ? " and id <> $uid" : ""));
		if ($row && intval($row['total']) > 0) {
			return true;
		}
		return false;
	}
	public static function getFullName($id) {
		$db = Database::getInstance();
		$row = $db->once_fetch_array("select nickname from " . DB_PREFIX . "users where id = '$id'");
		if (!$row) {
			return 'N/A';
		}
		return trim($row['nickname']);
	}
}
?>
