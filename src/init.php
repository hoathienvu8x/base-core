<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
error_reporting(7);
ob_start();
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Ho_Chi_Minh');
define('SITE_ROOT', dirname(__FILE__));
require_once SITE_ROOT . '/config.php';
require_once SITE_ROOT . '/include/bootstrap.php';
define('SITE_URL', Option::get('site_url','URL_DEFAULT'));
define('OAUTH_URL', Option::get('oauthurl','URL_DEFAULT/service.php'));
// Template handle
define('TEMPLATEPATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'template');
define('TEMPLATEURL', SITE_URL . 'template/');
// Action handle
define('ACTION_DIR', SITE_ROOT . DIRECTORY_SEPARATOR . 'action');
// Uploads handle
define('UPLOAD_DIR', SITE_ROOT . DIRECTORY_SEPARATOR . 'uploads');
define('UPLOAD_URL', SITE_URL . 'uploads');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('AJAX_DOING', true);
}

doAddslashes();
