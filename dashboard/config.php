<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
//mysql database address
define('DB_HOST','localhost');
//mysql database user
define('DB_USER','root');
//database password
define('DB_PASSWD','none_if_empty');
//database name
define('DB_NAME','dashboard');
//database prefix
define('DB_PREFIX','');
//auth key
define('AUTH_KEY','y7toW8EMipu@qQ1YliPLcM^th(hj&F)I8251dfbdb0e58f8fd5431a914d112e32');
//cookie name
define('AUTH_COOKIE_NAME','hfHAGE0LqfJCnvZak44qHlfSuh8GzogH');
// using local script
define('IS_LOCAL', true);

