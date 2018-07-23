<?php
define ( 'INAPP', true );

require_once dirname ( __FILE__ ) . '/init.php';

header('Content-Type:application/json;charset=utf-8');

app_exit(array(
	'status' => 'error',
	'data' => null,
	'msg' => 'Yêu cầu không hợp lệ'
),'json');