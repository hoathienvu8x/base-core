<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Xtemplate {
	public static function get($template, $ext = '.php') {
		if (!is_dir(TEMPLATEPATH)) {
			app_exit(array(
				'status' => 'error',
				'data' => null,
				'msg' => 'Không tìm thấy giao diện !'
			));
		}
		if ( !file_exists(TEMPLATEPATH . DIRECTORY_SEPARATOR . $template . $ext)) {
			app_exit(array(
				'status' => 'error',
				'data' => null,
				'msg' => 'Không tìm thấy giao diện &quot;'.$template.'&quot; yêu cầu !'
			));
		}
		return TEMPLATEPATH . DIRECTORY_SEPARATOR . $template . $ext;
	}
	public static function stylesheet($file = 'style', $inline = false) {
		$css_code = $inline ? '<style>{CSS_CODE}</style>' : '<link rel="stylesheet" type="text/css" media="all" href="{CSS_CODE}" />';
		if (!is_dir(TEMPLATEPATH)) {
			return '';
		}
		if (!file_exists(TEMPLATEPATH . DIRECTORY_SEPARATOR . $file.'.css')) {
			return '';
		}
		if ($inline) {
			$css = file_get_contents(TEMPLATEPATH . DIRECTORY_SEPARATOR . $file.'.css');
			$css = preg_replace('/[\r\n\t]*/','',$css);
			$css = preg_replace('/ {2,}/',' ',$css);
			return str_replace('{CSS_CODE}',trim($css),$css_code);
		}
		return str_replace('{CSS_CODE}',TEMPLATEURL . $file.'.css',$css_code);
	}
	public static function exists($template, $ext = '.php') {
		if (!is_dir(TEMPLATEPATH)) {
			return false;
		}
		if ( !file_exists(TEMPLATEPATH . DIRECTORY_SEPARATOR . $template . $ext)) {
			return false;
		}
		return true;
	}
	public static function favicon($ext = 'png') {
		return SITE_URL . 'favicon.'.$ext;
	}
	public static function attach($attach = '') {
		if (!empty($attach)) {
			return TEMPLATEURL . $attach;
		}
		return '';
	}
	public static function output() {
		$content = ob_get_clean();
		if (function_exists('ob_gzhandler')) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}
		echo $content;
		ob_end_flush();
		exit;
	}
}