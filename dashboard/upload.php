<?php
define ( 'INAPP', true );
require_once dirname ( __FILE__ ) . '/init.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: x-requested-with, x-index, x-total, Content-Type, origin,accept");

header('Content-Type:application/json;charset=utf-8');

if (!defined('AJAX_DOING')) {
	access_response(array(
		'status' => 'error',
		'url' => SITE_URL,
		'msg' => 'Tải tập tin chỉ yêu cầu phương thức POST và gửi dữ liệu dưới dạng AJAX'
	));
	exit;
}

if (!isset($_POST['action']) && !preg_match('/^(avatar|file)$/i',strtolower($_POST['action']))) {
	access_response(array(
		'status' => 'error',
		'url' => SITE_URL,
		'msg' => 'Ứng dụng chỉ cho phép tải tập tin hoặc avatar vui lòng chọn vào kiểu mà bạn muốn tải lên !'
	));
	exit;
}

$action = strtolower(trim($_POST['action']));
if ($action == 'avatar') {
	FileIO::getInstance('avatars');
} else {
	FileIO::getInstance('files');
}

FileIO::handle();

access_response(array(
	'status' => 'error',
	'url' => SITE_URL,
	'msg' => 'Yêu cầu không hợp lệ'
));
