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
	public static function upload() {}
}
?>
