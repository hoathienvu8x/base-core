<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class Auth {
	const ERROR_NONE = 0;
	const ERROR_USER = 1;
	const ERROR_PASSWD = 2;
	const ERROR_CODE = 3;
	const ERROR_BLOCKED = 4;
	const ERROR_VALID = 5;
	const ERROR_SUCCESS = 6;
	const ERROR_LOGGOUT = 'true';
	private static $userData = null;
	public static function isLogged() {
		if (!isset($_COOKIE[AUTH_COOKIE_NAME]) || empty($_COOKIE[AUTH_COOKIE_NAME])) {
			return false;
		}
		$auth_cookie = $_COOKIE[AUTH_COOKIE_NAME];
		if((self::$userData = self::validateAuthCookie($auth_cookie)) === false) {
		        return false;
        	}
        	return true;
	}
	public static function message($core) {
		$msg = '';
		switch($code) {
			case self::ERROR_LOGGOUT:
				$msg = 'Đăng xuất thành công !';
				break;
			case self::ERROR_SUCCESS:
				$msg = 'Đăng nhập thành công !';
				break;
			case self::ERROR_NONE:
				$msg = 'Tốt quá không có lỗi nào !';
				break;
			case self::ERROR_USER:
				$msg = 'Tên đăng nhập không đúng !';
				break;
			case self::ERROR_PASSWD:
				$msg = 'Mật khẩu không đúng !';
				break;
			case self::ERROR_CODE:
				$msg = 'Mã xác thực khi đăng nhập không khớp !';
				break;
			case self::ERROR_BLOCKED:
				$msg = 'Tài khoản này đang tạm khoá !';
				break;
			case self::ERROR_VALID:
				$msg = 'Vui lòng nhập vào thông tin';
				break;
		}
		return $msg;
	}
	public static function is_super() {
		if (self::isLogged() == false) {
			return false;
		}
		if (isset(self::$userData['role']) && self::$userData['role'] == 'administrator') {
			return true;
		}
		return false;
	}
	public static function isAdmin() {
		if (self::isLogged() == false) {
			return false;
		}
		if(isset(self::$userData['role']) && self::$userData['role'] != 'admin') {
			return false;
		}
		if(isset(self::$userData['state']) && self::$userData['state'] == 'locked') {
			return false;
		}
		return true;
	}
	public static function get($key, $default = false) {
		if (self::isLogged() == false) {
			return $default;
		}
		if (isset(self::$userData[$key])) {
			return self::$userData[$key];
		}
		return $default;
	}
	public static function checkLogin($username, $password, $imgcode, $logincode = 'n') {
		if (trim($username) == '' || trim($password) == '') {
            		return self::ERROR_VALID;
        	}
		if ($logincode == 'y') {
			$sessionCode = isset($_SESSION['code']) ? $_SESSION['code'] : '';
			if (empty($imgcode) || strtoupper($imgcode) != $sessionCode) {
				return self::ERROR_CODE;
			}
		}
		self::$userData = self::getUserDataByUsername($username);
		if (false === $userData) {
			return self::ERROR_USER;
		}
		$hash = self::$userData['password'];
		if (true !== self::checkPassword($password, $hash)){
			return self::ERROR_PASSWD;
		}
		if (self::$userData['locked'] == 'y') {
			return self::ERROR_BLOCKED;
		}
		return self::ERROR_NONE;
	}
	public static function getUserDataByUsername($userLogin) {
        $DB = Database::getInstance();
        if (empty($userLogin)) {
            return false;
        }
        self::$userData = false;
        if (!self::$userData = $DB->once_fetch_array("select * from ".DB_PREFIX."users where username = '".$DB->escape_string($userLogin)."'")) {
            return false;
        }
        self::$userData['nickname'] = htmlspecialchars(self::$userData['nickname']);
        self::$userData['username'] = htmlspecialchars(self::$userData['username']);
        return self::$userData;
    }
	public static function getUserDataByEmail($email) {
        $DB = Database::getInstance();
        if (!is_mail($email)) {
            return false;
        }
        self::$userData = false;
        if (!self::$userData = $DB->once_fetch_array("select * from ".DB_PREFIX."users where email = '".$DB->escape_string($email)."'")) {
            return false;
        }
        self::$userData['nickname'] = htmlspecialchars(self::$userData['nickname']);
        self::$userData['username'] = htmlspecialchars(self::$userData['username']);
        return self::$userData;
    }
	public static function checkPassword($password, $hash) {
        global $m_hasher;
        if (empty($m_hasher)) {
            $m_hasher = new PasswordHash(8, true);
        }
        $check = $m_hasher->CheckPassword($password, $hash);
        return $check;
    }
	public static function setAuthCookie($user_login, $ispersis = false) {
        if ($ispersis) {
            $expiration  = time() + 3600 * 24 * 30 * 12;
        } else {
            $expiration = null;
        }
        $auth_cookie_name = AUTH_COOKIE_NAME;
        $auth_cookie = self::generateAuthCookie($user_login, $expiration);
        setcookie($auth_cookie_name, $auth_cookie, $expiration,'/');
    }
	public static function destroyAuthCookie() {
		setcookie(AUTH_COOKIE_NAME, ' ', time() - 31536000, '/');
	}
	private static function generateAuthCookie($user_login, $expiration) {
        $key = self::hash_code($user_login . '|' . $expiration);
        $hash = hash_hmac('md5', $user_login . '|' . $expiration, $key);
        $cookie = $user_login . '|' . $expiration . '|' . $hash;
        return $cookie;
    }
	private static function hash_code($data) {
        $key = AUTH_KEY;
        return hash_hmac('md5', $data, $key);
    }
	private static function validateAuthCookie($cookie = '') {
		if (empty($cookie)) {
            return false;
        }
	$cookie_elements = explode('|', $cookie);
        if (count($cookie_elements) != 3) {
            return false;
        }
        list($username, $expiration, $hmac) = $cookie_elements;
        if (!empty($expiration) && $expiration < time()) {
            return false;
        }
		$key = self::hash_code($username . '|' . $expiration);
        $hash = hash_hmac('md5', $username . '|' . $expiration, $key);
        if ($hmac != $hash) {
            return false;
        }
        $user = self::getUserDataByUsername($username);
        if (!$user) {
            return false;
        }
        return $user;
	}
}
?>
