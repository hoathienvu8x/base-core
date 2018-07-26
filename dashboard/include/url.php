<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Url {
	public static function home() {
		return SITE_URL;
	}
	public static function action($action, $args = array()) {
		$url = SITE_URL;
		$query_string = array();
		foreach($args as $key => $val) {
			$query_string[] = "$key=$val";
		}
		if (!empty($query_string)) {
			$query_string = '&'.implode('&',$query_string);
		} else {
			$query_string = '';
		}
		if(empty($action)) {
			$query_string = trim($query_string,'&');
		}
		return $url . (!empty($action) ? '?action='.$action : '') . $query_string;
	}
	public static function profile($args = array()) {
		return self::action('profile',$args);
	}
	public static function login($args = array()) {
		return self::action('login', $args);
	}
	public static function forgot($args = array()) {
		return self::action('forgot', $args);
	}
	public static function admin($args = array()) {
		return self::action('admin', $args);
	}
	public static function event($args = array()) {
		return self::action('event', $args);
	}
	public static function role($args = array()) {
		return self::action('role', $args);
	}
	public static function option($args = array()) {
		return self::action('option', $args);
	}
	public static function logout() {
		return SITE_URL . '?logout=true';
	}
	public static function is_current($action = '') {
		if (!isset($_GET['action'])) {
			return false;
		}
		$current = strtolower(trim($_GET['action']));
		return $current == $action;
	}
}
?>
