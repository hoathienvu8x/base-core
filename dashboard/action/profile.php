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
		$_GET['msg'] = User::ERROR_EMAIL_INVALID;
		$hasError = $hasError || true;
	}
	if (empty($u_data['nickname'])) {
		$_GET['msg'] = User::ERROR_FULLNAME_NONE;
		$hasError = $hasError || true;
	}
	if ($UModel->is_email_exists($u_data['email'], Auth::get('id', -1))) {
		$_GET['msg'] = User::ERROR_EMAIL_EXISTS;
		$hasError = $hasError || true;
	}
	if (isset($_POST['password']) && !empty($_POST['password'])) {
		$pwd = trim($_POST['password']);
		if (strlen($pwd) < 5) {
			$_GET['msg'] = User::ERROR_PASSWORD_INVALID;
			$hasError = $hasError || true;
		} else {
			$PHPASS = new PasswordHash(8, true);
			$u_data['password'] = $PHPASS->HashPassword($pwd);
		}
	}
	if ($hasError == false) {
		$id = $UModel->update($u_data, intval(Auth::get('id',-1)));
		if ($id > 0) {
			access_response(array(
				'status' => 'success',
				'url' => Url::action('profile', array('msg' => User::ERROR_UPDATED)),
				'msg' => User::message(User::ERROR_SAVED)
			));
			exit;
		} else {
			$_GET['msg'] = User::ERROR_UPDATE;
		}
	}
}
require_once TEMPLATEPATH . 'profile.php';
exit;
?>
