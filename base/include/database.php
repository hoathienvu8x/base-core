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
			exit('Application needs database store extentions !');
		}
		return $DB;
	}
}
?>