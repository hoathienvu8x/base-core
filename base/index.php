<?php
define('INAPP',true);
define('SITE_ROOT', dirname(__FILE__));

require_once SITE_ROOT . '/init.php';

$action = isset($_GET['action']) ? trim($_GET['action']) : '';

if ($action == 'forgot') {
	require_once SITE_ROOT . '/action/forgot.php';
	exit;
}

if (Auth::isLogged() == false) {
	require_once SITE_ROOT . '/action/login.php';
	exit;
}
if (isset($_GET['logout'])) {
	require_once SITE_ROOT . '/action/logout.php';
	exit;
}

$action_file = strtolower($action) . '.php';

if (file_exists(SITE_ROOT . '/action/'.$action_file)) {
	require_once SITE_ROOT . '/action/'.$action_file;
	exit;
} else {
	require_once SITE_ROOT . '/action/index.php';
}
exit;