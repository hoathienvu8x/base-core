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
}
?>
