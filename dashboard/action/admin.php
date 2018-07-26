<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Users';

$Model = User::getInstance();

if (isset($_POST['saved'])) {
	$data = array();
	
	if (isset($_POST['id']) && intval($_POST['id']) > 0) {
		$user_id = $Model->update($data, intval($_POST['id']));
		if ($user_id > 0) {
			access_response(array(
				'status' => 'success',
				'url' => Url::action('admin', array('msg' => User::ERROR_UPDATED)),
				'msg' => User::message(User::ERROR_UPDATED)
			));
		} else {
			access_response(array(
				'status' => 'error',
				'url' => Url::action('admin', array('msg' => User::ERROR_UPDATE)),
				'msg' => User::message(User::ERROR_UPDATE)
			));
		}
	} else {
		$user_id = $Model->add($data);
		if ($user_id > 0) {
			access_response(array(
				'status' => 'success',
				'url' => Url::action('admin', array('msg' => User::ERROR_SAVED)),
				'msg' => User::message(User::ERROR_SAVED)
			));
		} else {
			access_response(array(
				'status' => 'error',
				'url' => Url::action('admin', array('msg' => User::ERROR_SAVE)),
				'msg' => User::message(User::ERROR_SAVE)
			));
		}
	}
}

if (isset($_GET['del'])) {
	$user = intval($_GET['del']);
	if ($user <= 0) {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('admin', array('msg' => User::ERROR_INVALID)),
			'msg' => User::message(User::ERROR_INVALID)
		));
	}
	$user_id = $Model->delete($user);
	if ($user_id > 0) {
		access_response(array(
			'status' => 'success',
			'url' => Url::action('admin', array('msg' => User::ERROR_DELETED)),
			'msg' => User::message(User::ERROR_DELETED)
		));
	} else {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('admin', array('msg' => User::ERROR_DELETE)),
			'msg' => User::message(User::ERROR_DELETE)
		));
	}
}

if (isset($_GET['change'], $_GET['to'])) {
	$user = intval($_GET['change']);
	if ($user <= 0) {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('admin', array('msg' => User::ERROR_INVALID)),
			'msg' => User::message(User::ERROR_INVALID)
		));
	} 
	$to = preg_match('/^(y|n)$/i',trim($_GET['to'])) ? strtolower(trim($_GET['to'])) : 'y';
	$user_id = $Model->update(array('role' => $to), $user);
	if ($customer_id > 0) {
		access_response(array(
			'status' => 'success',
			'url' => Url::action('admin', array('msg' => User::ERROR_CHANGED)),
			'msg' => User::message(User::ERROR_CHANGED)
		));
	} else {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('admin', array('msg' => User::ERROR_CHANGE)),
			'msg' => User::message(User::ERROR_CHANGE)
		));
	}
}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: New User';
	require_once TEMPLATEPATH . 'admin-advance.php';
	exit;
}

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$pageTitle = 'ACP :: Update user';
	$user = $Model->getOne(intval($_GET['edit']));
	if (!$user) {
		$dialogTitle = 'Lỗi';
		$dialogMsg = 'Không tìm thấy khách hàng này';
		require_once TEMPLATEPATH . '/dialog.php';
		exit;
	}
	require_once TEMPLATEPATH . 'admin-advance.php';
	exit;
}

$page = isset($_GET['page']) && intval($_GET['page']) > 1 ? intval($_GET['page']) : 1;

$options = array();

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
	$options['keyword'] = trim($_GET['keyword']);
}
$orderBy = isset($_GET['orderby']) && !empty($_GET['orderby']) ? strtolower(trim($_GET['orderby'])) : 'name'; // name|phone|address|id
$order = isset($_GET['order']) && !empty($_GET['order']) ? strtolower(trim($_GET['order'])) : 'asc';
$options['orderby'] = $orderBy;
$options['order'] = $order;

$customers = $Model->getAll($options, $page, 20);

require_once TEMPLATEPATH . 'admin.php';
exit;
?>
