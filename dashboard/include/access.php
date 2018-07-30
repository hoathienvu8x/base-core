<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Access {
	public static function auth() {
		if (!Auth::isLogged()) {
			access_response(array(
				'status' => 'error',
				'direct' => Url::home(),
				'msg' => Auth::message()
			));
		}
		$mode = isset($_GET['mode']) && !empty($_GET['mode']) ? trim($_GET['mode']) : '';
		
	}
}
?>
