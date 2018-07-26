<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Database {
	public static function getInstance() {
		if (class_exists('mysqli')) {
			$DB = MySqlii::getInstance();
		} else {
			$DB = MySql::getInstance();
		}
		if (empty($DB)) {
			access_response(array(
				'status' => 'error',
				'url' => SITE_URL,
				'msg' => 'Application needs database store extentions !'
			));
		}
		return $DB;
	}
}
?>
