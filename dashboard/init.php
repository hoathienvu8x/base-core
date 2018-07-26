<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit();
}
error_reporting(7);
ob_start();
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once SITE_ROOT . '/config.php';
require_once SITE_ROOT . '/include/bootstrap.php';

define('SITE_URL',Option::get('site_url','http://localhost/dashboard/'));

define('TEMPLATEPATH', SITE_ROOT . '/template/');
define('TEMPLATEURL', SITE_URL . 'template/');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('AJAX_DOING', true);
}

doAddslashes();
