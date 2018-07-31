<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class FileIO {
	public static function getInstance( $subdir = 'files' ) {
		$currentDir = UPLOADPATH . DIRECTORY_SEPARATOR . $subdir  . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, date('Y/m'));
		if (!is_dir($currentDir)) {
			@mkdir($currentDir, 0777, true);
		}
		if (is_dir($currentDir)) {
			define('CURRENT_DIR_UPLOAD', $currentDir);
			$currentPath = str_replace(UPLOADPATH . DIRECTORY_SEPARATOR, '', $currentDir);
			define('CURRENT_DIR_URL', str_replace(DIRECTORY_SEPARATRO, '/', $currentPath));
		} else {
			access_response(array(
				'status' => 'error',
				'url' => '',
				'msg' => 'Lỗi tạo thư mục chứa tập tin tải lên'
			));
			exit;
		}
	}
	public static function upload($file) {
		if (!defined('CURRENT_DIR_UPLOAD')) {
			return array(
				'status' => 'error',
				'url' => '',
				'msg' => 'Lỗi tạo thư mục chứa !'
			);
		}
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".",$file['name']);
		$extension = end($temp);
		if (!in_array($file["type"], array('image/gif','image/jpg','image/jpeg','image/pjpeg','image/x-png','image/png'))) {
			return array(
				'status' => 'error',
				'url' => '',
				'msg' => ''Tập tin tải lên không đúng định dạng !
			);
		}
		$dest_file = CURRENT_DIR_UPLOAD . DIRECTORY_SEPARATOR . sha1(basename($file["name"])) . '_o.'.strtolower($extension);
		if (@move_uploaded_file($file['tmp_name'], $dest_file)) {
			$stat = stat ( dirname ( $dest_file ) );
			$perms = $stat ['mode'] & 0000666;
			@ chmod ( $dest_file, $perms );
			$filename = basename($dest_file);
			$url = UPLOADURL . CURRENT_DIR_URL . '/' . $filename;
			$filesize = filesize($dest_file);
			if ($filesize <= 0) {
				return array(
					'status' => 'error',
					'url' => '',
					'msg' => 'Lỗi tải tập tin lên máy chủ'
				);
			}
			return array(
				'status' => 'success',
				'url' => '',
				'msg' => 'Tải tập tin lên máy chủ thành công',
				'data' => array(
					'url' => $url,
					'name' => $filename,
					'size' => $filesize,
					'path' => str_replace(UPLOADPATH . DIRECTORY_SEPARATOR, '', $dest_file),
					'uri' => CURRENT_DIR_URL . '/' . $filename
				)
			);
		}
		return array(
			'status' => 'error',
			'url' => '',
			'msg' => 'Lỗi tải tập tin lên máy chủ !'
		);
	}
	public static function chunk($file) {
		if (!defined('CURRENT_DIR_UPLOAD')) {
                        return array(
                                'status' => 'error',
                                'url' => '',
                                'msg' => 'Lỗi tạo thư mục chứa !'
                        );
                }
		if (!isset($_SERVER['HTTP_X_NAME']) || empty($_SERVER['HTTP_X_NAME'])) {
			return array('status' => 'error', 'url' => '', 'msg' => 'Vui lòng chọn tập tin để tải !');
		}
		if (!isset($_SERVER['HTTP_X_INDEX'])) {
			return array('status' => 'error', 'url' => '', 'msg' => 'Hệ thống không hiểu dữ liệu');
		}
		if (!isset($_SERVER['HTTP_X_TOTAL'])) {
			return array('status' => 'error', 'url' => '', 'msg' => 'Hệ thống không hiểu kích thước tập tin');
		}
		@ini_set ( 'display_errors', 0 );
		ignore_user_abort ( true );
		$completedPath = sys_get_temp_dir() . '/' . $_SERVER['HTTP_X_TOTAL'].'-'.$file['name'];
		if (isset($_SERVER['HTTP_X_INDEX']) && $_SERVER['HTTP_X_INDEX'] == BYTES_PER_CHUNK) {
			$completed = @fopen($completedPath, "wb");
		} else {
			$completed = @fopen($completedPath, "ab");
		}
		if ($completed) {
			$in = fopen($file['tmp_name'], "rb");
			if ( $in ) {
				while ( $buff = fread( $in, BYTES_PER_CHUNK ) ) {
					fwrite($completed, $buff);
				}
			}
			fclose($in);
			fclose($completed);
		}
		if (intval($_SERVER['HTTP_X_TOTAL']) == intval($_SERVER['HTTP_X_INDEX'])) {
			$filesize = filesize($completedPath);
			if ($filesize != intval($_SERVER['HTTP_X_TOTAL'])) {
				@unlink($completedPath);
				return array('status' => 'error', 'url' => '', 'msg' => 'Quá trình tải lên bị mất mát dữ liệu !');
			}
			$filename = str_replace($_SERVER['HTTP_X_TOTAL'].'-','',basename($completedPath));
			$orig_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			$allowed_filetype = array('png','jpg','gif','jpeg');
			if (!in_array($orig_ext, $allowed_filetype)) {
				@unlink($completedPath);
				return array('status' => 'error', 'url' => '', 'msg' => 'Tập tin tải lên không đúng định dạng !');
			}
			$dest_file = CURRENT_DIR_UPLOAD . DIRECTORY_SEPARATOR . sha1(basename($_SERVER['HTTP_X_NAME'])) . '_o.'.strtolower($orig_ext);
			if (!@copy($completedPath, $dest_file)) {
				return array('status' => 'error', 'url' => '', 'msg' => 'Không thể tạo được tập tin vào thư mục chứa !');
			}
			@unlink($completedPath);
			$stat = stat ( dirname ( $dest_file ) );
			$perms = $stat ['mode'] & 0000666;
			@ chmod ( $dest_file, $perms );
			$filename = basename($dest_file);
			$url = UPLOADURL . CURRENT_DIR_URL . '/' . $filename;
                        $filesize = filesize($dest_file);
			if ($filesize <= 0) {
                                return array(
                                        'status' => 'error',
                                        'url' => '',
                                        'msg' => 'Lỗi tải tập tin lên máy chủ'
                                );
                        }
			return array(
                                'status' => 'success',
                                'url' => '',
                                'msg' => 'Tải tập tin lên máy chủ thành công',
                                'data' => array(
                                        'url' => $url,
                                        'name' => $filename,
                                        'size' => $filesize,
                                        'path' => str_replace(UPLOADPATH . DIRECTORY_SEPARATOR, '', $dest_file),
                                        'uri' => CURRENT_DIR_URL . '/' . $filename
                                )
                        );
		} else {
			return array('status' => 'success', 'url' => '', 'msg' => 'Tải lên '.intval($_SERVER['HTTP_X_INDEX']) .'/'. intval($_SERVER['HTTP_X_TOTAL']).' !');
		}
	}
	public static function handle() {
		if (isset($_FILES['chunk'])) {
			$retVal = self::chunk($_FILES['chunk']);
			access_response($retVal);
			exit;
		}
		if (isset($_FILE['file'])) {
			$retVal = self::upload($_FILES['file']);
			access_response($retVal);
			exit;
		}
		access_response(array(
			'status' => 'error',
			'url' => SITE_URL,
			'msg' => 'Vui lòng chọn vào tập tin cần tải !'
		));
		exit;
	}
}
?>
