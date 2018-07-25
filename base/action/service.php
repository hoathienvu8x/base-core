<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý dịch vụ';


if (isset($_GET['del'])) {}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm mới dịch vụ';
	require_once TEMPLATEPATH . 'service-advance.php';
	exit;
}

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$pageTitle = 'ACP :: Cập nhật dịch vụ';
	require_once TEMPLATEPATH . 'service-advance.php';
	exit;
}
require_once TEMPLATEPATH . 'service.php';
exit;
?>