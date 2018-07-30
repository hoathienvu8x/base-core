<?php
define ( 'INAPP', true );
require_once dirname ( __FILE__ ) . '/init.php';


header('Content-Type:application/json;charset=utf-8');

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
