<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Option {
	const CORE_VERSION = '1.0.0';
	const ERROR_SAVED = 1;
	const ERROR_SAVE = 2;
	const ERROR_UPDATED = 3;
	const ERROR_UPDATE = 4;
	const ERROR_DELETED = 5;
	const ERROR_DELETE = 6;
	const ERROR_LOADED = 7;
	const ERROR_LOAD = 8;
	const ERROR_NAME = 9;
	const ERROR_INVALID = 10;
	const ERROR_VALUE = 11;
	const ERROR_SYSTEM = 12;
	const ERROR_NOTEXISTS = 13;
	const ERROR_EXISTS = 14;
	private static $systems = array('login_code','row_per_page','balance','roles','site_url');
	public static function message($code) {
		$msg = 'Hệ thống không hiểu lỗi này !';
		switch ($code) {
			case self::ERROR_SAVED : $msg = 'Lưu tuỳ chọn thành công !'; break;
			case self::ERROR_SAVE : $msg = 'Lưu tuỳ chọn thất bại !'; break;
			case self::ERROR_UPDATED : $msg = 'Cập nhật tuỳ chọn thành công !'; break;
			case self::ERROR_UPDATE : $msg = 'Cập nhật tuỳ chọn thất bại !'; break;
			case self::ERROR_DELETED : $msg = 'Xoá tuỳ chọn thành công !'; break;
			case self::ERROR_DELETE : $msg = 'Xoá tuỳ chọn thất bại !'; break;
			case self::ERROR_LOADED : $msg = 'Đưa và danh sách tự động tải thành công !'; break;
			case self::ERROR_LOAD : $msg = 'Không thể đưa tuỳ chọn vào tự động tải !'; break;
			case self::ERROR_NAME : $msg = 'Tên tuỳ chọn không hợp lệ'; break;
			case self::ERROR_INVALID : $msg = 'Vui lòng nhập vào thông tin !'; break;
			case self::ERROR_VALUE : $msg = 'Giá trị tuỳ chọn không hợp lệ !'; break;
			case self::ERROR_NOTEXISTS : $msg = 'Tuỳ chọn này không tồn tại trên hệ thống !'; break;
			case self::ERROR_SYSTEM : $msg = 'Tuỳ chọn này là hệ thống không xoá được !'; break;
			case self::ERROR_EXISTS : $msg = 'Tuỳ chọn này đã tồn tại trên hệ thống !'; break;
		}
		return $msg;
	}
	public static function is_system($option) {
		return in_array($option, self::$systems);
	}
	public static function option_value($row) {
		$value = '[Object Object]';
		switch($row['option_name']) {
			case 'login_code' : $value = $row['option_value'] == 'y' ? 'Có' : 'Không'; break;
			case 'roles' : $value = '[Object Array]'; break;
			case 'balance' : case 'row_per_page': $value = intval($row['option_value']); break;
			case 'site_url' : $value = trim($row['option_value']); break;
			default: 
				if (preg_match('/a\:[0-9]+\{/i',$row['option_value'])) {
					$value = '[Object Array]';
				} else {
					$value = empty($row['option_value']) ? 'Chưa đặt giá trị' : '[Object String]'; 
				}
				break;
		}
		return $value;
	}
	public static function get($option, $default = false) {
		if (empty($option)) {
			return $default;
		}
		$row = self::getOne($option);
		if (!$row) {
			return $default;
		}
		switch ($option) {
			case 'login_code':
				$row['option_value'] = !in_array($row['option_value'], array('y','n')) ? 'n' : trim($row['option_value']);
				break;
			case 'balance':
				$row['option_value'] = floatval($row['option_value']);
				break;
			case 'row_per_page':
				$row['option_value'] = intval($row['option_value']);
				break;
			case 'roles':
				$row['option_value'] = @unserialize(stripslashes($row['option_value']));
				if (!$row['option_value']) {
					$row['option_value'] = array();
				}
				break;
		}
		return $row['option_value'];
	}
	public static function getOne($option) {
		$DB = Database::getInstance();
		$row = $DB->once_fetch_array("select * from ".DB_PREFIX."options where option_name = '".$DB->escape_string($option)."'");
		if (!$row) {
			return null;
		}
		return $row;
	}
	public static function getAll($options = array(), $page = 1, $limit = 15) {
		$DB = Database::getInstance();
		$sql = "select * from ".DB_PREFIX."options";
		if (isset($options['keyword'])) {
			$sql .= " where option_desc like '%".$DB->escape_string($options['keyword'])."%'";
		}
		if (isset($options['name'])) {
			$sql .= " order by option_desc ". $options['name'];
		} else {
			$sql .= " order by option_desc asc";
		}
		$sql .= " limit ".(($page - 1) * $limit).", ".$limit;
		$rows = array();
		$query = $DB->query($sql);
		while ($row = $DB->fetch_array($query)) {
			$rows[] = $row;
		}
		return $rows;
	}
	public static function add($option, $value, $desc = '', $autoload = 'n') {
		$row = self::getOne($option);
		if (!$row) {
			$DB = Database::getInstance();
			if (is_array($value)) {
				$value = serialize($value);
			}

			$keys = $values = array();
			$keys[] = "option_name";
			$values[] = "'".$DB->escape_string($option)."'";
			$keys[] = "option_value";
			$values[] = "'".$DB->escape_string($value)."'";
			if (!empty($desc)) {
				$keys[] = "option_desc";
				$values[] = "'".$DB->escape_string($desc)."'";
			}
			$keys[] = "autoload";
			$values[] = "'".$DB->escape_string($autoload)."'";
			$DB->query("insert into ".DB_PREFIX."options (".implode(',',$keys).") values (".implode(',',$values).")");
			if ($DB->affected_rows() > 0) {
				return true;
			}
			return false;
		}
		return true;
	}
	public static function delete($option) {
		$DB = Database::getInstance();
		$DB->query("delete from ".DB_PREFIX."options where option_name = '".$DB->escape_string($option)."'");
		if ($DB->affected_rows() > 0) {
			return true;
		}
		return false;
	}
	public static function update($option, $value, $desc = null, $autoload = null) {
		$row = self::getOne($option);
		if (!$row) {
			return false;
		}
		$DB = Database::getInstance();
		if (is_array($value)) {
			$value = serialize($value);
		}
		$items = array("option_value = '".$DB->escape_string($value)."'");
		if ($desc) {
			$items[] = "option_desc = '".$DB->escape_string($desc)."'";
		}
		if ($autoload) {
			$items[] = "autoload = '".$DB->escape_string($autoload)."'";
		}
		if (empty($items)) {
			return false;
		}
		$DB->query("update ".DB_PREFIX."options set ".implode(',',$items)." where option_name = '".$DB->escape_string($option)."'");
		if ($DB->affected_rows() >= 0) {
			return true;
		}
		return false;
	}
}
?>
