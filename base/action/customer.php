<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
$pageTitle = 'ACP :: Quản lý khách hàng';

$Model = Customer::getInstance();

if (isset($_POST['saved'])) {
	$data = array();
	
	if (isset($_POST['id']) && intval($_POST['id']) > 0) {
		$customer_id = $Model->update($data, intval($_POST['id']));
		if ($customer_id > 0) {
			access_response(array(
				'status' => 'success',
				'url' => Url::action('customer', array('msg' => Customer::ERROR_UPDATED)),
				'msg' => Customer::message(Customer::ERROR_UPDATED)
			));
		} else {
			access_response(array(
				'status' => 'error',
				'url' => Url::action('customer', array('msg' => Customer::ERROR_UPDATE)),
				'msg' => Customer::message(Customer::ERROR_UPDATE)
			));
		}
	} else {
		$customer_id = $Model->add($data);
		if ($customer_id > 0) {
			access_response(array(
				'status' => 'success',
				'url' => Url::action('customer', array('msg' => Customer::ERROR_SAVED)),
				'msg' => Customer::message(Customer::ERROR_SAVED)
			));
		} else {
			access_response(array(
				'status' => 'error',
				'url' => Url::action('customer', array('msg' => Customer::ERROR_SAVE)),
				'msg' => Customer::message(Customer::ERROR_SAVE)
			));
		}
	}
}

if (isset($_GET['del'])) {
	$customer = intval($_GET['del']);
	if ($customer <= 0) {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('customer', array('msg' => Customer::ERROR_INVALID)),
			'msg' => Customer::message(Customer::ERROR_INVALID)
		));
	}
	$customer_id = $Model->delete($customer);
	if ($customer_id > 0) {
		access_response(array(
			'status' => 'success',
			'url' => Url::action('customer', array('msg' => Customer::ERROR_DELETED)),
			'msg' => Customer::message(Customer::ERROR_DELETED)
		));
	} else {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('customer', array('msg' => Customer::ERROR_DELETE)),
			'msg' => Customer::message(Customer::ERROR_DELETE)
		));
	}
}

if (isset($_GET['change'], $_GET['to'])) {
	$customer = intval($_GET['change']);
	if ($customer <= 0) {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('customer', array('msg' => Customer::ERROR_INVALID)),
			'msg' => Customer::message(Customer::ERROR_INVALID)
		));
	} 
	$to = preg_match('/^(y|n)$/i',trim($_GET['to'])) ? strtolower(trim($_GET['to'])) : 'y';
	$customer_id = $Model->update(array('is_guest' => $to), $customer);
	if ($customer_id > 0) {
		access_response(array(
			'status' => 'success',
			'url' => Url::action('customer', array('msg' => Customer::ERROR_CHANGED)),
			'msg' => Customer::message(Customer::ERROR_CHANGED)
		));
	} else {
		access_response(array(
			'status' => 'error',
			'url' => Url::action('customer', array('msg' => Customer::ERROR_CHANGE)),
			'msg' => Customer::message(Customer::ERROR_CHANGE)
		));
	}
}

if (isset($_GET['mode']) && strtolower($_GET['mode']) == 'add') {
	$pageTitle = 'ACP :: Thêm mới khách hàng';
	require_once TEMPLATEPATH . 'customer-advance.php';
	exit;
}

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$pageTitle = 'ACP :: Cập nhật khách hàng';
	$customer = $Model->getOne(intval($_GET['edit']));
	if (!$customer) {
		$dialogTitle = 'Lỗi';
		$dialogMsg = 'Không tìm thấy khách hàng này';
		require_once TEMPLATEPATH . '/dialog.php';
		exit;
	}
	require_once TEMPLATEPATH . 'customer-advance.php';
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

require_once TEMPLATEPATH . 'customer.php';
exit;
?>