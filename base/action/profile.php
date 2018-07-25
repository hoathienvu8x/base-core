<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Sửa thông tin cá nhân';
$UModel = new User();
if (isset($_POST['saved'])) {
	$u_data = array(
		'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
		'nickname' => isset($_POST['nickname']) ? trim($_POST['nickname']) : ''
	);
	$hasError = false;
	if (!is_mail($u_data['email'])) {
		$_GET['msg'] = 1;
		$hasError = $hasError || true;
	}
	if (empty($u_data['nickname'])) {
		$_GET['msg'] = 2;
		$hasError = $hasError || true;
	}
	if ($UModel->is_email_exists($u_data['email'], Auth::get('id', -1))) {
		$_GET['msg'] = 3;
		$hasError = $hasError || true;
	}
	if (isset($_POST['password']) && !empty($_POST['password'])) {
		$pwd = trim($_POST['password']);
		if (strlen($pwd) < 5) {
			$_GET['msg'] = 4;
			$hasError = $hasError || true;
		} else {
			$PHPASS = new PasswordHash(8, true);
			$u_data['password'] = $PHPASS->HashPassword($pwd);
		}
	}
	if ($hasError == false) {
		$id = $UModel->update($u_data, intval(Auth::get('id',-1)));
		if ($id > 0) {
			mDirect(Url::profile(array('msg' => 5)));
			exit;
		} else {
			$_GET['msg'] = 6;
		}
	}
}
require_once TEMPLATEPATH . 'profile.php';
exit;
?>