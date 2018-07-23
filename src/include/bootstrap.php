<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
function __autoload($class) {
	$file = strtolower($class).'.php';
	if (file_exists(SITE_ROOT . '/model/'.$file)) {
		include_once SITE_ROOT . '/model/'.$file;
	} else if (file_exists(SITE_ROOT . '/include/'.$file)) {
		include_once SITE_ROOT . '/include/'.$file;
	} else {
		app_exit('Class '.$class.' not found!');
	}
	if (!class_exists($class)) {
		app_exit('Class '.$class.' not found!');
	}
}
function doAddslashes() {
	if (!get_magic_quotes_gpc()) {
		$_GET = addslashesDeep($_GET);
		$_POST = addslashesDeep($_POST);
		$_COOKIE = addslashesDeep($_COOKIE);
	}
}
function doStripslashes() {
	$_GET = stripslashesDeep($_GET);
	$_POST = stripslashesDeep($_POST);
	$_COOKIE = stripslashesDeep($_COOKIE);
}
function addslashesDeep($value) {
	$value = is_array($value) ? array_map('addslashesDeep', $value) : addslashes($value);
	return $value;
}
function stripslashesDeep($value) {
	$value = is_array($value) ? array_map('stripslashesDeep', $value) : stripslashes($value);
	return $value;
}
function json_uencode($data) {
	if (defined('JSON_UNESCAPED_UNICODE')) {
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	$json = json_encode($data);
	preg_match_all("/\\\\u([a-f0-9]{4})/i", $json, $all);
	$val = isset($all[1]) ? $all[1] : array();
	foreach($val as $i => $v) {
		$v = iconv('UCS-4LE','UTF-8',pack('V', hexdec('U'.$v)));
		$json = str_replace($all[0][$i], $v, $json);
	}
	return $json;
}
function mDirect($url) {
	header('Location: '.$url);
	exit();
}
function app_exit($data, $type = 'html') {
	if (!$data) {
		$data = array(
			'status' => 'error',
			'data' => null,
			'msg' => ''
		);
	}
	if (is_string($data) || is_int($data) || is_float($data)) {
		$data = array(
			'status' => 'error',
			'data' => null,
			'msg' => $data
		);
	}
	if (is_bool($data)) {
		$data = array(
			'status' => 'error',
			'data' => null,
			'msg' => $data == true ? 'True' : 'False'
		);
	}
	$data = array(
		'status' => 'error',
		'data' => null,
		'msg' => ''
	) + $data;
	if (!$type) {
		$type = defined('AJAX_DOING') ? 'json' : 'html';
	}
	if ($type == 'text') {
		header('Content-Type:text/plain;charset=utf-8');
		exit(json_uencode($data));
	}
	if ($type == 'json' || defined('AJAX_DOING')) {
		header('Content-Type:application/json;charset=utf-8');
		echo json_uencode($data);
		exit;
	}
	ob_start();
	?>
	<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lỗi hệ thống</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" />
<style>
* {
    margin: 0;
    padding: 0;
}
body {
    background-color: #ffff;
    font-size: 13px;
    font-family: Arial, Helvetica, sans-serif;
}
.main-wrap {
    min-width: 300px;
    max-width: 450px;
    margin: 5% auto 0;
    background-color: #fff;
}
.main-wrap p{
    padding: 10px;
    transition: height 500ms;
    -webkit-transition: height 500ms;
	background-color:#f9dede;
}
.main-wrap h2 {
    padding: 10px;
	text-align:center;
}
@media only screen and (min-width :320px) and (max-width :780px) {
    body {
        font-size: 90%;
    }
    .main-wrap {
        max-width: 100%;
        border: none;
        box-shadow: none;
        width: 100%;
        margin: 0 auto;
        border-radius: 0;
    }
    .main-wrap h2 {
        font-size: 120%;
    }
	.main-wrap p {
		padding:5% 10px;
	}
}
</style>
</head>
<body>
<div class="main-wrap<?php echo $data['status']; ?>">
	<h2>Lỗi máy chủ</h2>
	<p><?php echo $data['msg']; ?></p>
</div>
</body>
</html>
<?php
$content = ob_get_contents();
ob_clean();
ob_end_clean();
ob_end_flush();
$content = preg_replace('/[\r\n\t]*/','',$content);
$content = preg_replace('/ {2,}/',' ',$content);
echo $content;
	exit;
}
if(!function_exists('hash_hmac')) {
	function hash_hmac($algo, $data, $key) {
		$packs = array('md5' => 'H32', 'sha1' => 'H40');
		if (!isset($packs[$algo])) {
			return false;
		}
		$pack = $packs[$algo];
		if (strlen($key) > 64) {
			$key = pack($pack, $algo($key));
		} elseif (strlen($key) < 64) {
			$key = str_pad($key, 64, chr(0));
		}
		$ipad = (substr($key, 0, 64) ^ str_repeat(chr(0x36), 64));
		$opad = (substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64));
		return $algo($opad . pack($pack, $algo($ipad . $data)));
	}
}
function is_mail($email) {
	if (preg_match("/^[\w\.\-]+@\w+([\.\-]\w+)*\.\w+$/", $email) && strlen($email) <= 60) {
		return true;
	} else {
		return false;
	}
}
function is_telephone($telephone) {
	$telephone = str_replace('+84','0',trim($telephone));
	if (!preg_match('/^0[0-9]{9,10}$/',$telephone)) {
		return false;
	}
	return true;
}
function get_user_data($key, $default = false) {
    return Auth::get($key, $default);
}
function get_user_meta($meta_key, $default = false) {
    return Auth::get_user_meta($meta_key, $default);
}
function is_loggin(){
    return Auth::isLogged();
}
function get_current_user_id() {
    return get_user_data('id', -1);
}