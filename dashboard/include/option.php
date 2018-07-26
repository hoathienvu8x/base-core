<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Option {
	const CORE_VERSION = '1.0.0';
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
			case 'app_agent':
				$row['option_value'] = str_replace('{VERSION}',self::CORE_VERSION, $row['option_value']);
				break;
			case 'app_ids': case 'roles':
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
