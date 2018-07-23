<?php
function a($a) {
	if (!is_dir($a)) {
		@mkdir($a);
	}
}
function b($a) {
	$c = "";
	if (preg_match('/\.php$/i',basename($a))) {
		$c = "<?php\n";
	}
	file_put_contents($a, $c);
}
function c($a, $b) {
	if (is_array($b)) {
		a($a);
		foreach($b as $c => $d) {
			if (is_array($d)) {
				c($a . DIR_SEP . $c, $d);
			} else {
				b($a . DIR_SEP . $d);
			}
		}
	}
}
function r($length = 12, $special_chars = true) {
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