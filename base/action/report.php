<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý hóa đơn';

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm hóa đơn';
	require_once TEMPLATEPATH . 'report-advance.php';
	exit;
}

require_once TEMPLATEPATH . 'report.php';
exit;
?>