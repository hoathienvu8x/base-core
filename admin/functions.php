<?php
define('SITE_URL', './');
function is_user_loggin() {
	return false;
}
function get_user_data($key, $default) {
	return 'Trần Ngọc Nhật';
}
function body_class() {
	if (!is_user_loggin()) {
		echo ' class="login"';
	} else {
		$action = isset($_GET['action']) ? trim($_GET['action']) : 'home';
		if (empty($action)) {
			$action = 'home';
		}
		echo ' class="'.$action.'"';
	}
}
class Url {
	public static function query($args = array()) {
		$query = array();
		foreach($args as $key => $val) {
			$query[] = $key.'='.urlencode($val);
		}
		return $query;
	}
	public static function home($args = array()) {
		$query_string = self::query($args);
		return SITE_URL . (!empty($query_string) ? '?'.implode('&', $query_string) : '');
	}
	public static function action($action, $args = array()){
		$query_string = self::query($args);
		return SITE_URL . '?action=' . $action . (!empty($query_string) ? '&'.implode('&',$query_string) : '');
	}
	public static function admin($args = array()) {
		return self::action('admin', $args);
	}
	public static function forgot($args = array()) {
		return self::action('forgot', $args);
	}
	public static function profile($args = array()) {
		return self::action('profile', $args);
	}
	public static function option($args) {
		return self::action('option', $args);
	}
	public static function logout() {
		return SITE_URL . '?lg=true';
	}
}
