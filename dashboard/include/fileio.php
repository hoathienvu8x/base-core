<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class FileIO {
	public static function getInstance( $subdir = 'files' ) {
		$currentDir = UPLOADPATH . DIRECTORY_SEPARATOR . $subdir  . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, date('Y/m'));
		if (!is_dir($currentDir)) {
			@mkdir($currentDir, 0777, true);
		}
		if (is_dir($currentDir)) {
			define('CURRENT_DIR_UPLOAD', $currentDir);
		}
	}
	public static function upload($file) {
		
	}
	public static function chunk($file) {

	}
	public static function handle() {
		if (isset($_FILES['chunk'])) {
			$retVal = self::chunk($_FILES['chunk']);
			access_response($retVal);
			exit;
		}
		if (isset($_FILE['file'])) {
			$retVal = self::upload($_FILES['file']);
			access_response($retVal);
			exit;
		}
		access_response(array(
			'status' => 'error',
			'url' => SITE_URL,
			'msg' => 'Vui lòng chọn vào tập tin cần tải !'
		));
		exit;
	}
}
?>
