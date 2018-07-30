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
		access_response(array(
			'status' => 'error',
			'direct' => Url::profile(array('msg' => User::ERROR_EMAIL_INVALID)),
			'msg' => User::message(User::ERROR_EMAIL_INVALID)
		));
		exit;
	}
	if (empty($u_data['nickname'])) {
		access_response(array(
                        'status' => 'error',
                        'direct' => Url::profile(array('msg' => User::ERROR_FULLNAME_NONE)),
                        'msg' => User::message(User::ERROR_FULLNAME_NONE)
                ));
                exit;
	}
	if ($UModel->is_email_exists($u_data['email'], get_current_user_id())) {
		access_response(array(
                        'status' => 'error',
                        'direct' => Url::profile(array('msg' => User::ERROR_EMAIL_EXISTS)),
                        'msg' => User::message(User::ERROR_EMAIL_EXISTS)
                ));
                exit;
	}
	if (isset($_POST['password']) && !empty($_POST['password'])) {
		$pwd = trim($_POST['password']);
		if (strlen($pwd) < 5) {
			access_response(array(
                        	'status' => 'error',
                        	'direct' => Url::profile(array('msg' => User::ERROR_PASSWORD_INVALID)),
                        	'msg' => User::message(User::ERROR_PASSWORD_INVALID)
                	));
                	exit;
		} else {
			$PHPASS = new PasswordHash(8, true);
			$u_data['password'] = $PHPASS->HashPassword($pwd);
		}
	}
	if ($hasError == false) {
		$id = $UModel->update($u_data, intval(get_current_user_id()));
		if ($id > 0) {
			access_response(array(
				'status' => 'success',
				'direct' => Url::action('profile', array('msg' => User::ERROR_UPDATED)),
				'msg' => User::message(User::ERROR_UPDATED)
			));
			exit;
		} else {
			access_response(array(
				'status' => 'error',
				'direct' => Url::profile(array('msg' => User::ERROR_UPDATE)),
				'msg' => User::message(User::ERROR_UPDATE)
			));
			exit;
		}
	}
}

require_once TEMPLATEPATH . 'profile.php';
exit;
?>
