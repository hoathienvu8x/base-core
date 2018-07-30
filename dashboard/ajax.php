<?php
define ( 'INAPP', true );
require_once dirname ( __FILE__ ) . '/init.php';


header('Content-Type:application/json;charset=utf-8');

Access::auth(); // Restrict process acction

access_response(array(
	'status' => 'error',
	'url' => SITE_URL,
	'msg' => 'Yêu cầu không hợp lệ'
));
