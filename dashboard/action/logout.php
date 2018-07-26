<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
if (Auth::isLogged()) {
	Auth::destroyAuthCookie();
}
mDirect('./?lg=true');
exit();
?>