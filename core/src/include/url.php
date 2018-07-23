<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Url {
	const ACTION_PROFILE = 'profile';
	const ACTION_NOTIFY = 'notify';
	const ACTION_HISTORY = 'history';
	const ACTION_SETTING = 'setting';
	
	public static function label($action = '') {
		$title = ''; 
		switch ($action) {
			case self::ACTION_PROFILE :
				$title = 'Thông tin cá nhân';
				break;
			case self::ACTION_HISTORY :
				$title = 'Lịch sử';
				break;		
			case self::ACTION_SETTING :
				$title = 'Cài đặt';
				break;				
		}
		return $title;
	}
	
	public static function home($args = array()) {
		$query_string = array();
		foreach($args as $key => $val) {
			$query_string[] = "$key=$val";
		}
		if (!empty($query_string)) {
			$query_string = '?'.implode('&',$query_string);
		} else {
			$query_string = '';
		}
		return SITE_URL . $query_string;
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
	public static function is($action = '') {
		if (!isset($_GET['action'])) {
			return false;
		}
		$current = strtolower(trim($_GET['action']));
		return $current == $action;
	}
}