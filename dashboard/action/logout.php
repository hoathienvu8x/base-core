<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
if (Auth::isLogged()) {
	Auth::destroyAuthCookie();
}
access_response(array(
	'status' => 'success',
	'direct' => Url::home(array('lg' => 'true')),
	'msg' => Auth::message(Auth::ERROR_LOGGOUT)
));
exit();
?>
