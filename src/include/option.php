<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Option {
	const CORE_VERSION = '1.0.0';
	public static function get($option, $default = false) {
		$DB = Database::getInstance();
		if (empty($option)) {
			return $default;
		}
		$row = $DB->once_fetch_array("select option_value from ".DB_PREFIX."options where option_name = '".$DB->escape_string($option)."'");
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
	public static function is_exists($option) {
		$DB = Database::getInstance();
		if (!empty($option)) {
			$row = $DB->once_fetch_array("select count(option_name) as total from ".DB_PREFIX."options where option_name = '".$DB->escape_string($option)."'");
			if ($row && intval($row['total']) > 0) {
				return true;
			}
		}
		return false;
	}
	public static function systems() {
		$system = array('login_code','app_ids','roles','site_url','app_agent','row_per_page');
		return $system;
	}
	public static function is_system($option) {
		$system = self::systems();
		return in_array($option,$system);
	}
	public static function getAll($options = array(), $page = 1, $limit = 15) {
		$DB = Database::getInstance();
		$rows = array();
		$sql = "select * from ".DB_PREFIX."options";
		$conds = array();
		if (isset($options['keyword'])) {
			$conds[] = "(option_name like '%".$DB->escape_string($options['keyword'])."%' or option_value like '%".$DB->escape_string($options['keyword'])."%' or option_desc like '%".$DB->escape_string($options['keyword'])."%')";
		}
		if (isset($options['not_in']) && !empty($options['not_in'])) {
			$conds[] = "option_name not in ('".implode("','",$options['not_in'])."')";
		}
		$orders = array();
		if (isset($options['name'])) {
			$orders[] = "option_name ".$options['name'];
		}
		if (isset($options['desc'])) {
			$orders[] = "option_desc ".$options['desc'];
			$orders[] = "option_value ".$options['value'];
		}
		if (empty($orders)) {
			$orders[] = "option_desc asc";
		}
		if (!empty($conds)) {
			$sql .= " where ".implode(' and ', $conds);
		}
		$sql .= " order by ".implode(',',$orders);
		$sql .= " limit ".(($page - 1) * $limit).", $limit";
		$query = $DB->query($sql);
		while ($row = $DB->fetch_array($query)) {
			$rows[] = $row;
		}
		return $rows;
	}
	public static function add($option, $value, $desc = '', $isSyntax = false) {
		$DB = Database::getInstance();
		$value = $isSyntax ? $value : "'".$DB->escape_string($value)."'";
		$DB->query("insert into ".DB_PREFIX."options (option_name, option_value, option_desc) values ('".$DB->escape_string($option)."', $value, '".$DB->escape_string($desc)."')");
		if ($DB->affected_rows() > 0) {
			return $option;
		}
		return false;
	}
	public static function getOne($option) {
		$DB = Database::getInstance();
		if (!empty($option)) {
			$row = $DB->once_fetch_array("select * from ".DB_PREFIX."options where option_name = '".$DB->escape_string($option)."'");
			if (!$row) {
				return false;
			}
			return $row;
		}
		return false;
	}
	public static function update($option, $value, $desc = null, $isSyntax = false) {
		$DB = Database::getInstance();
		$value = $isSyntax ? $value : "'".$DB->escape_string($value)."'";
		if ($desc) {
			$desc = ", option_desc = '".$DB->escape_string($desc)."'";
		} else {
			$desc = '';
		}
		$DB->query("update ".DB_PREFIX."options set option_value = $value $desc where option_name = '".$DB->escape_string($option)."'");
		if ($DB->affected_rows() >= 0) {
			return $option;
		}
		return false;
	}
	public static function delete($option) {
		$DB = Database::getInstance();
		$DB->query("delete from ".DB_PREFIX."options where option_name = '".$DB->escape_string($option)."'");
		if ($DB->affected_rows() > 0) {
			return $option;
		}
		return false;
	}
}
?>