<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quên mật khẩu';

if (Auth::isLogged()) {
	access_response(array(
                'status' => 'warning',
                'direct' => Url::profile(array('msg' => User::ERROR_STAYLOGIN)),
        	'msg' => User::message(Auth::ERROR_STAYLOGIN)
        ));
	exit;
}

if (isset($_POST['field_chk'])) {
	$field_chk = trim($_POST['field_chk']);
	if (empty($field_chk)) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::action('forgot', array('msg' => User::ERROR_INVALID)),
			'msg' => User::message(User::ERROR_INVALID)
		));
		exit;
	}
	if (!is_mail($field_chk)) {
		$user_data = Auth::getUserDataByUsername($field_chk);
		if ($user_data === false) {
			access_response(array(
				'status' => 'error',
				'direct' => Url::action('forgot', array('msg' => User::ERROR_NAME_NOTEXISTS)),
				'msg' => User::message(User::ERROR_NAME_NOTEXISTS)
			));
			exit;
		}
		// Send Mail $user_data['email']

		access_response(array(
			'status' => 'success',
			'direct' => Url::action('forgot', array('msg' => User::ERROR_FORGOTED)),
			'msg' => User::message(User::ERROR_FORGOTED)
		));
		exit;
	} else {
		$user_data = Auth::getUserDataByEmail($field_chk);
		if ($user_data === false) {
			access_response(array(
				'status' => 'error',
				'direct' => Url::action('forgot', array('msg' => User::ERROR_EMAIL_NOTEXISTS)),
				'msg' => User::message(User::ERROR_EMAIL_NOTEXISTS)
			));
			exit;
		}
		// Send Mail  $user_data['email']

		access_response(array(
                        'status' => 'success',
                        'direct' => Url::action('forgot', array('msg' => User::ERROR_FORGOTED)),
                        'msg' => User::message(User::ERROR_FORGOTED)
                ));
		exit;
	}
}

require_once Xtemplate::get( 'forgot' );
?>
