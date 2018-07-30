<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý sự kiện xử lý';

$EModel = new Event();

if (isset($_POST['saved'])) {
	$data = array(
		'event_alias' => '',
		'event_name' => '',
		'event_desc' => ''
	);
	if (!isset($_POST['event_name']) || empty($_POST['event_name'])) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::event(array('msg' => Event::ERROR_NAME)),
			'msg' => Event::message(Event::ERROR_NAME)
		));
		exit;
	}
	$data['event_name'] = trim($_POST['event_name']);
	if (isset($_POST['event_alias']) && !empty($_POST['event_alias'])) {
		$data['event_alias'] = trim($_POST['event_alias']);
	}
	if (isset($_POST['event_desc'])) {
		$data['event_desc'] = trim($_POST['event_desc']);
	}
	if (empty($data['event_alias'])) {
		$data['event_alias'] = make_alias_from_str($data['event_name']);
	}
	if (isset($_POST['id']) && intval($_POST['id']) > 0) {
		if ($EModel->is_event_exists($data['event_alias'], intval($_POST['id']))) {
			access_response(array(
				'status' => 'error',
				'direct' => Url::event(array('msg' => Event::ERROR_EXISTS)),
				'msg' => Event::message(Event::ERROR_EXISTS)
			));
			exit;
		}
		$event_id = $EModel->update($data, intval($_POST['id']));
		if ($event_id > 0) {
			access_response(array(
				'status' => 'sucess',
				'direct' => Url::event(array('msg' => Event::ERROR_UPDATED)),
				'msg' => Event::message(Event::ERROR_UPDATED)
			));
			exit;
		} else {
			access_response(array(
				'status' => 'error',
				'direct' => Url::event(array('msg' => Event::ERROR_UPDATE)),
				'msg' => Event::message(Event::ERROR_UPDATE)
			));
			exit;
		}
	} else {
		if ($EModel->is_event_exists($data['event_alias'], null)) {
			access_response(array(
                                'status' => 'error',
                                'direct' => Url::event(array('msg' => Event::ERROR_EXISTS)),
                                'msg' => Event::message(Event::ERROR_EXISTS)
                        ));
                        exit;
		}
		$event_id = $EModel->add($data);
		if ($event_id > 0) {
			access_response(array(
				'status' => 'success',
				'direct' => Url::event(array('msg' => Event::ERROR_SAVED)),
				'msg' => Event::message(Event::ERROR_SAVED)
			));
			exit;
		} else {
			access_response(array(
				'status' => 'error',
				'direct' => Url::event(array('msg' => Event::ERROR_SAVE)),
				'msg' => Event::message(Event::ERROR_SAVE)
			));
			exit;
		}
	}
}

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$event = $EModel->getOne(intval($_GET['edit']));
	if (!$event) {
		access_response(array(
			'status' => 'error',
			'direct' => Url::event(array('msg' => Event::ERROR_NOTEXISTS)),
			'msg' => Event::message(Event::ERROR_NOTEXISTS)
		));
		exit;
	}
	$pageTitle = 'ACP :: Cập nhật sự kiện';
	require_once TEMPLATEPATH . 'event-advance.php';
	exit;
}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm sự kiện';
	require_once TEMPLATEPATH . 'event-advance.php';
	exit;
}

if (isset($_GET['del']) && intval($_GET['del']) > 0) {
	$event_id = $EModel->delete(intval($_GET['del']));
	if ($event_id > 0) {
		access_response(array(
			'status' => 'success',
			'dicrect' => Url::event(array('msg' => Event::ERROR_DELETED)),
			'msg' => Event::message(Event::ERROR_DELETED)
		));
		exit;
	} else {
		access_response(array(
			'status' => 'error',
			'direct' => Url::event(array('msg' => Event::ERROR_DELETE)),
			'msg' => Event::message(Event::ERROR_DELETE)
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

$roles = $EModel->get($options, $page, Option::get('row_per_page', 15));

require_once TEMPLATEPATH . 'event.php';
exit;
?>
