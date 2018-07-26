<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quên mật khẩu';

if (Auth::isLogged()) {
	mDirect(Url::action('profile'));
	exit;
}

if (isset($_POST['field_chk'])) {
	$field_chk = trim($_POST['field_chk']);
	if (empty($field_chk)) {
		mDirect(Url::action('forgot',array('msg' => 5)));
		exit;
	}
	if (!is_mail($field_chk)) {
		$user_data = Auth::getUserDataByUsername($field_chk);
		if ($user_data === false) {
			mDirect(Url::action('forgot',array('msg' => 1)));
			exit;
		}
		// Send Mail $user_data['email']
		
		mDirect(Url::home(array('msg' => 6)));
		exit;
	} else {
		$user_data = Auth::getUserDataByEmail($field_chk);
		if ($user_data === false) {
			mDirect(Url::action('forgot',array('msg' => 2)));
			exit;
		}
		// Send Mail  $user_data['email']
		exit;
		mDirect(Url::home(array('msg' => 6)));
		exit;
	}
}

require_once TEMPLATEPATH . 'forgot.php';
?>