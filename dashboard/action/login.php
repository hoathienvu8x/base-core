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
		access_response(array(
			'status' => 'error',
			'direct' => Url::home(array('msg' => Auth::ERROR_VALID)),
			'msg' => Auth::message(Auth::ERROR_VALID)
		));
		exit;
	}
	if (empty($password)) {
		access_response(array(
                        'status' => 'error',
                        'direct' => Url::home(array('msg' => Auth::ERROR_VALID)),
                        'msg' => Auth::message(Auth::ERROR_VALID)
                ));
		exit;
	}
	$result = Auth::checkLogin($username, $password,$img_code,'n');
	if ($result != Auth::ERROR_NONE) {
		access_response(array(
                        'status' => 'error',
                        'direct' => Url::home(array('msg' => $result)),
                        'msg' => Auth::message($result)
                ));
		exit;
	}
	Auth::setAuthCookie($username, $ispersis);
	access_response(array(
                'status' => 'success',
                'direct' => Url::home(),
        	'msg' => Auth::message(Auth::ERROR_SUCCESS)
        ));
	exit;
}
require_once Xtemplate::get( 'login' );
exit;
?>
