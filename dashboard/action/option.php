<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý quyền cho người dùng';

if (isset($_POST['saved'])) {
	$data = array(
		'option_name' => '',
		'option_value' => '',
		'option_desc' => ''
	);
	if (!isset($_POST['option_name']) || empty($_POST['option_name'])) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::option(array('msg' => Option::ERROR_NAME)),
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
	if (isset($_POST['id']) && intval($_POST['id']) > 0) {
		if ($RModel->is_role_exists($data['role_alias'], intval($_POST['id']))) {
			access_response(array(
				'status' => 'error',
				'direct' => Url::role(array('msg' => Role::ERROR_EXISTS)),
				'msg' => Role::message(Role::ERROR_EXISTS)
			));
			exit;
		}
		$role_id = $RModel->update($data, intval($_POST['id']));
		if ($role_id > 0) {
			access_response(array(
				'status' => 'sucess',
				'direct' => Url::role(array('msg' => Role::ERROR_UPDATED)),
				'msg' => Role::message(Role::ERROR_UPDATED)
			));
			exit;
		} else {
			access_response(array(
				'status' => 'error',
				'direct' => Url::role(array('msg' => Role::ERROR_UPDATE)),
				'msg' => Role::message(Role::ERROR_UPDATE)
			));
			exit;
		}
	} else {
		if ($RModel->is_role_exists($data['role_alias'], null)) {
			access_response(array(
                                'status' => 'error',
                                'direct' => Url::role(array('msg' => Role::ERROR_EXISTS)),
                                'msg' => Role::message(Role::ERROR_EXISTS)
                        ));
                        exit;
		}
		$role_id = $RModel->add($data);
		if ($role_id > 0) {
			access_response(array(
				'status' => 'success',
				'direct' => Url::role(array('msg' => Role::ERROR_SAVED)),
				'msg' => Role::message(Role::ERROR_SAVED)
			));
			exit;
		} else {
			access_response(array(
				'status' => 'error',
				'direct' => Url::role(array('msg' => Role::ERROR_SAVE)),
				'msg' => Role::message(Role::ERROR_SAVE)
			));
			exit;
		}
	}
}

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$role = $RModel->getOne(intval($_GET['edit']));
	if (!$role) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::role(array('msg' => Role::ERROR_NOTEXISTS)),
			'msg' => Role::message(Role::ERROR_NOTEXISTS)
		));
		exit;
	}
	$pageTitle = 'ACP :: Cập nhật quyền';
	require_once TEMPLATEPATH . 'role-advance.php';
	exit;
}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm quyền';
	require_once TEMPLATEPATH . 'role-advance.php';
	exit;
}

if (isset($_GET['del']) && intval($_GET['del']) > 0) {
	$role_id = $RModel->delete(intval($_GET['del']));
	if ($role_id > 0) {
		access_response(array(
			'status' => 'success',
			'dicrect' => Url::role(array('msg' => Role::ERROR_DELETED)),
			'msg' => Role::message(Role::ERROR_DELETED)
		));
		exit;
	} else {
		access_response(array(
			'status' => 'error',
			'direct' => Url::role(array('msg' => Role::ERROR_DELETE)),
			'msg' => Role::message(Role::ERROR_DELETE)
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

if (isset($_GET['role']) && !empty(trim($_GET['role']))) {
	$options['role'] = trim($_GET['role']);
}

$roles = $RModel->get($options, $page, Option::get('row_per_page', 15));

require_once TEMPLATEPATH . 'role.php';
exit;
?>
