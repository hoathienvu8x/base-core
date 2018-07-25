<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
if (isset($_POST['username'],$_POST['password'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$img_code = isset($_POST['imgcode']) ? strtoupper(trim($_POST['imgcode'])) : '';
	if (empty($username)) {
		mDirect('./?msg=5');
		exit();
	}
	if (empty($password)) {
		mDirect('./?msg=5');
		exit();
	}
	$result = Auth::checkLogin($username, $password,$img_code,'n');
	if ($result > 0) {
		mDirect('./?msg='.$result);
		exit();
	}
	Auth::setAuthCookie($username, $ispersis);
	mDirect('./');
	exit;
}
require_once TEMPLATEPATH . 'login.php';
exit;
?>