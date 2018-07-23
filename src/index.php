<?php
define ( 'INAPP', true );

require_once dirname ( __FILE__ ) . '/init.php';

$action = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : '';

if ($action == 'forgot') {
	require_once ACTION_DIR . DIRECTORY_SEPARATOR . 'forgot.php';
	Xtemplate::output();
	exit;
}

if (is_loggin() == false && $action == 'signup') {
	require_once ACTION_DIR . DIRECTORY_SEPARATOR . 'signup.php';
	Xtemplate::output();
	exit;
}

if (!is_loggin()) {
	require_once ACTION_DIR . DIRECTORY_SEPARATOR . 'login.php';
	Xtemplate::output();
	exit;
}

if (empty($action)) {
	$action = 'home';
}

$file = $action . '.php';

if ( file_exists(ACTION_DIR . DIRECTORY_SEPARATOR . $file)) {
	require_once ACTION_DIR . DIRECTORY_SEPARATOR . $file;
	Xtemplate::output();
	exit;
}

app_exit(array(
	'status' => 'error',
	'data' => null,
	'msg' => 'Yêu cầu không tồn tại. Vui lòng thử lại sau'
));
Xtemplate::output();
exit;
