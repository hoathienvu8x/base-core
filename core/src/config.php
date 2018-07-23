<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
//mysql database address
define('DB_HOST','{DB_HOST}');
//mysql database user
define('DB_USER','{DB_USER}');
//database password
define('DB_PASSWD','{DB_PASS}');
//database name
define('DB_NAME','{DB_NAME}');
//database prefix
define('DB_PREFIX','{DB_PREFIX}');
//auth key
define('AUTH_KEY','{KEY_HASH}');
//cookie name
define('AUTH_COOKIE_NAME','{COOKIE_NAME}');
// using local script
define('IS_LOCAL', false);
