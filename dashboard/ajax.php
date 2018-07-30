<?php
define ( 'INAPP', true );
require_once dirname ( __FILE__ ) . '/init.php';


header('Content-Type:application/json;charset=utf-8');

Access::auth(); // Restrict process acction

# Load dashboard data

# Load notification

access_response(array(
	'status' => 'error',
	'url' => '',
	'msg' => 'Yêu cầu không hợp lệ'
));
