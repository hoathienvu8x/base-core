<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý quyền cho người dùng';

$args = array();

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
	$args['edit'] = trim($_GET['edit']);
}

if (isset($_POST['saved'])) {
	$data = array(
		'option_name' => '',
		'option_value' => '',
		'option_desc' => '',
		'autoload' => isset($_POST['autoload']) ? trim($_POST['autoload']) : 'n'
	);
	if (!isset($_POST['option_name']) || empty($_POST['option_name'])) {
		$args[] = Option::ERROR_NAME;
		access_response(array(
			'status' => 'error',
			'direct' => Url::option($args),
			'msg' => Option::message(Option::ERROR_NAME)
		));
		exit;
	}
	$data['option_name'] = trim($_POST['option_name']);
	if (isset($_POST['option_value'])) {
		$data['option_value'] = trim($_POST['option_value']);
	}
	if (isset($_POST['option_desc'])) {
		$data['option_desc'] = trim($_POST['option_desc']);
	}
	if (isset($_POST['edit']) && !empty($_POST['edit'])) {
		$option_name = trim($_POST['edit']);
		if ($option_name == $data['option_name']) {
			$retVal = Option::update($option_name, $data['option_value'], $data['option_desc'], $data['autoload']);
			if ($retVal == true) {
				$args['msg'] = Option::ERROR_UPDATED;
				access_response(array(
					'status' => 'success',
					'direct' => Url::option($args),
					'msg' => Option::message(Option::ERROR_UPDATED)
				));
				exit;
			} else {
				$args['msg'] = Option::ERROR_UPDATE;
				access_response(array(
					'status' => 'error',
					'direct' => Url::option($args),
					'msg' => Option::message(Option::ERROR_UPDATE)
				));
				exit;
			}
		} else {
			$args['msg'] = Option::ERROR_UPDATE;
			access_response(array(
				'status' => 'error',
				'direct' => Url::option($args),
				'msg' => Option::message(Option::ERROR_UPDATE)
			));
			exit;
		}
	} else {
		$option = Option::getOne($data['option_name']);
		if (!$option) {
			$args['msg'] = Option::ERROR_NOTEXISTS;
			access_response(array(
				'status' => 'error',
				'direct' => Url::option($args),
				'msg' => Option::message(Option::ERROR_NOTEXISTS)
			));
			exit;
		} else {
			$retVal = Option::add($data['option_name'], $data['option_value'], $data['option_desc'], $data['autoload']);
			if ($retVal == true) {
				$args['msg'] = Option::ERROR_SAVED;
				access_response(array(
					'status' => 'success',
					'direct' => Url::option($args),
					'msg' => Option::message(Option::ERROR_SAVED)
				));
				exit;
			} else {
				$args['msg'] = Option::ERROR_SAVE;
				access_response(array(
					'status' => 'error',
					'direct' => Url::option($args),
					'msg' => Option::message(Option::ERROR_SAVE)
				));
				exit;
			}
		}
	}
}

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
	$option = Option::getOne(trim($_GET['edit']));
	if (!$option) {
		$args['msg'] = Option::ERROR_NOTEXISTS;
		access_response(array(
			'status' => 'error',
			'direct' => Url::option($args),
			'msg' => Option::message(Option::ERROR_NOTEXISTS)
		));
		exit;
	}
	$pageTitle = 'ACP :: Cập nhật tuỳ chọn';
	require_once TEMPLATEPATH . 'option-advance.php';
	exit;
}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm tuỳ chọn';
	require_once TEMPLATEPATH . 'option-advance.php';
	exit;
}

if (isset($_GET['del']) && intval($_GET['del']) > 0) {
	$retVal = Option::delete(trim($_GET['del']));
	if ($retVal == true) {
		$args['msg'] = Option::ERROR_DELETED;
		access_response(array(
			'status' => 'success',
			'dicrect' => Url::option($args),
			'msg' => Option::message(Option::ERROR_DELETED)
		));
		exit;
	} else {
		$args['msg'] = Option::ERROR_DELETE;
		access_response(array(
			'status' => 'error',
			'direct' => Url::option($args),
			'msg' => Option::message(Option::ERROR_DELETE)
		));
		exit;
	}
}

$page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
$options = array();

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
	$options['keyword'] = trim($_GET['keyword']);
}

if (isset($_GET['byname']) && preg_match('/^(asc|desc)$/i', trim($_GET['byname']))) {
	$options['name'] = strtolower(trim($_GET['byname']));
}

$roles = Option::getAll($options, $page, Option::get('row_per_page', 15));

require_once TEMPLATEPATH . 'option.php';
exit;
?>
