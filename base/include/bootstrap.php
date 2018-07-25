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
		access_response(array(
			'status' => 'error',
			'url' => SITE_URL,
			'msg' => 'Class '.$class.' not found!'
		));
	}
	if (!class_exists($class)) {
		access_response(array(
			'status' => 'error',
			'url' => SITE_URL,
			'msg' => 'Class '.$class.' not found!'
		));
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
	$json = json_encode($data);
	preg_match_all("/\\u([a-f0-9]{4})/i", $json, $all);
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
function getRandStr($length = 12, $special_chars = true) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	if ($special_chars) {
		$chars .= '!@#$%^&*()';
	}
	$randStr = '';
	for ($i = 0; $i < $length; $i++) {
		$randStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $randStr;
}
function is_mail($email) {
	if (preg_match("/^[\w\.\-]+@\w+([\.\-]\w+)*\.\w+$/", $email) && strlen($email) <= 60) {
		return true;
	}
	return false;
}
function getSiteUrl() {
	$phpself = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
	if (preg_match("/^.*\//", $phpself, $matches)) {
		return 'http://' . $_SERVER['HTTP_HOST'] . $matches[0];
	} else {
		return SITE_URL;
	}
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
function is_telephone($tel) {
	return preg_match('/^[0-9]{10}[0-9]*$/',$tel);
}
function access_response($data) {
	$def = array('status' => 'warning', 'url' => SITE_URL, 'msg' => 'Yêu cầu không hợp lệ');
	if (!$data || !is_array($def)) {
		$data = $def;
	}
	if (defined('AJAX_DOING')) {
		header('Content-Type:application/json;charset=utf-8');
		echo json_uencode($data);
		exit;
	}
	require_once TEMPLATEPATH . '/header.php';
	?>
	<div id="dialog-error" class="dialog-<?php echo $data['status']; ?>">
		<p><strong>Lỗi</strong></p>
		<p><?php echo $data['msg']; ?>
		<?php if (!empty($data['url'])) : ?>
		<a href="<?php echo $data['url']; ?>">Quay lại</a>
		<?php endif; ?></p>
	</div>
	<?php
	require_once TEMPLATEPATH . '/footer.php';
	exit;
}