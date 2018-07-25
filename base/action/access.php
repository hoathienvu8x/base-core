<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý tài sản';

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm mới tài sản';
	require_once TEMPLATEPATH . 'access-advance.php';
	exit;
}

require_once TEMPLATEPATH . 'access.php';
exit;
?>