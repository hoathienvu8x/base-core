<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Users';

$Model = new User();
$args = array();
if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
        $args['edit'] = intval($_GET['edit']);
} else {
        $args['mode'] = 'add';
}
if (isset($_POST['saved'])) {
	$data = array(
		'nickname' => '',
		'username' => '',
		'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
		'role' => isset($_POST['role']) ? trim($_POST['role']) : 'member',
		'state' => isset($_POST['state']) ? trim($_POST['state']) : 'register'
	);
	if (!isset($_POST['nickname']) || empty($_POST['nickname'])) {
		$args['msg'] = User::ERROR_FULLNAME_NONE;
		access_response(array(
			'status' => 'error',
			'direct' => Url::admin($args),
			'msg' => User::message(User::ERROR_FULLNAME_NONE)
		));
		exit;
	}
	$data['nickname'] = trim($_POST['nickname']);
	if (!isset($_POST['username']) || empty($_POST['username'])) {
		$args['msg'] = User::ERROR_NAME_INVALID;
		access_response(array(
			'status' => 'error',
			'direct' => Url::admin($args),
			'msg' => User::message(User::ERROR_NAME_INVALID)
		));
		exit;
	}
	$data['username'] = trim($_POST['username']);
	if (isset($_POST['password']) && strlen($_POST['password']) > 4) {
		$PHPASS = new PasswordHash(8, true);
		$data['password'] = $PHPASS->HashPassword($_POST['password']);
	}
	if (isset($_POST['id']) && intval($_POST['id']) > 0) {
		if ($Model->is_uname_exists($data['username'], intval($_POST['id']))) {
			$args['msg'] = User::ERROR_NAME_EXISTS;
                        access_response(array(
                                'status' => 'error',
                                'direct' => Url::admin($args),
                                'msg' => User::message(User::ERROR_NAME_EXISTS)
                        ));
                        exit;
                }
                if (is_mail($data['email']) && $Model->is_email_exists($data['email'], intval($_POST['id']))) {
			$args['msg'] = User::ERROR_EMAIL_EXISTS;
                        access_response(array(
                                'status' => 'error',
                                'direct' => Url::admin($args),
                                'msg' => User::message(User::ERROR_EMAIL_EXISTS)
                        ));
                        exit;
                }
		if (!is_mail($data['email'])) {
			unset($data['email']);
		}
		unset($data['username']);
		if (isset($data['password']) && empty($data['password'])) {
			unset($data['password']);
		}
		$user_id = $Model->update($data, intval($_POST['id']));
		if ($user_id > 0) {
			$args['msg'] = User::ERROR_UPDATED;
			access_response(array(
				'status' => 'success',
				'direct' => Url::admin($args),
				'msg' => User::message(User::ERROR_UPDATED)
			));
		} else {
			$args['msg'] = User::ERROR_UPDATE;
			access_response(array(
				'status' => 'error',
				'direct' => Url::admin($args),
				'msg' => User::message(User::ERROR_UPDATE)
			));
		}
	} else {
		if (empty($data['username']) || strlen($data['username']) < 5) {
			$args['msg'] = User::ERROR_NAME_INVALID;
			access_response(array(
				'status' => 'error',
				'direct' => Url::admin($args),
				'msg' => User::message(User::ERROR_NAME_INVALID)
			));
			exit;
		}
		if (!is_mail($data['email'])) {
			$args['msg'] = User::ERROR_EMAIL_INVALID;
			access_response(array(
                                'status' => 'error',
                                'direct' => Url::admin($args),
                                'msg' => User::message(User::ERROR_EMAIL_INVALID)
                        ));
                        exit;
		}
		if (!isset($data['password'])) {
			$args['msg'] = User::ERROR_PASSWORD_INVALID;
			access_response(array(
                                'status' => 'error',
                                'direct' => Url::admin($args),
                                'msg' => User::message(User::ERROR_PASSWORD_INVALID)
                        ));
                        exit;
		}
		if ($Model->is_uname_exists($data['username'])) {
			$args['msg'] = User::ERROR_NAME_EXISTS;
			access_response(array(
				'status' => 'error',
				'direct' => Url::admin($args),
				'msg' => User::message(User::ERROR_NAME_EXISTS)
			));
			exit;
		}
		if ($Model->is_email_exists($data['email'])) {
			$args['msg'] = User::ERROR_EMAIL_EXISTS;
			access_response(array(
                                'status' => 'error',
                                'direct' => Url::admin($args),
                                'msg' => User::message(User::ERROR_EMAIL_EXISTS)
                        ));
                        exit;
		}
		$user_id = $Model->add($data);
		if ($user_id > 0) {
			$args['msg'] = User::ERROR_SAVED;
			access_response(array(
				'status' => 'success',
				'direct' => Url::admin($args),
				'msg' => User::message(User::ERROR_SAVED)
			));
		} else {
			$args['msg'] = User::ERROR_SAVE;
			access_response(array(
				'status' => 'error',
				'direct' => Url::admin($args),
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
			'direct' => Url::admin(array('msg' => User::ERROR_INVALID)),
			'msg' => User::message(User::ERROR_INVALID)
		));
	}
	$user_id = $Model->delete($user);
	if ($user_id > 0) {
		access_response(array(
			'status' => 'success',
			'direct' => Url::admin(array('msg' => User::ERROR_DELETED)),
			'msg' => User::message(User::ERROR_DELETED)
		));
	} else {
		access_response(array(
			'status' => 'error',
			'url' => Url::admin(array('msg' => User::ERROR_DELETE)),
			'msg' => User::message(User::ERROR_DELETE)
		));
	}
}

if (isset($_GET['change'], $_GET['to'])) {
	$user = intval($_GET['change']);
	if ($user <= 0) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::admin(array('msg' => User::ERROR_INVALID)),
			'msg' => User::message(User::ERROR_INVALID)
		));
	}
	$to = preg_match('/^(y|n)$/i',trim($_GET['to'])) ? strtolower(trim($_GET['to'])) : 'y';
	$user_id = $Model->update(array('role' => $to), $user);
	if ($customer_id > 0) {
		access_response(array(
			'status' => 'success',
			'direct' => Url::admin(array('msg' => User::ERROR_CHANGED)),
			'msg' => User::message(User::ERROR_CHANGED)
		));
	} else {
		access_response(array(
			'status' => 'error',
			'direct' => Url::admin(array('msg' => User::ERROR_CHANGE)),
			'msg' => User::message(User::ERROR_CHANGE)
		));
	}
}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: New User';
	require_once Xtemplate::get('admin-advance');
	exit;
}

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$pageTitle = 'ACP :: Update user';
	$user = $Model->getOne(intval($_GET['edit']));
	if (!$user) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::admin(array('msg' => User::ERROR_NOTEXISTS)),
			'msg' => User::message(User::ERROR_NOTEXISTS)
		));
		exit;
	}
	require_once Xtemplate::get('admin-advance');
	exit;
}

$page = isset($_GET['page']) && intval($_GET['page']) > 1 ? intval($_GET['page']) : 1;

$options = array();

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
	$options['keyword'] = trim($_GET['keyword']);
}
$orderBy = isset($_GET['orderby']) && !empty($_GET['orderby']) ? strtolower(trim($_GET['orderby'])) : 'name';
$order = isset($_GET['order']) && !empty($_GET['order']) ? strtolower(trim($_GET['order'])) : 'asc';
$options['orderby'] = $orderBy;
$options['order'] = $order;

$users = $Model->get($options, $page, Option::get('row_per_page', 20));

require_once Xtemplate::get('admin');
exit;
?>
