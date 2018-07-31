<?php
define('BYTES_PER_CHUNK', 1048576);
define('SITE_URL', 'http://kbms.vn/');
define('UPLOAD_URL', 'http://kbms.vn/upload/');
define('UPLOAD_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upload');
define('X_FIELD_NAME', '_path');/*_url, _filename | Truong du lieu hinh anh can lay luu vo CSDL*/
function __retVal($status, $data, $msg) {
	return array(
		'status' => $status,
		'data' => $data,
		'msg' => $msg
	);
}
function utf82ascii($str, $lowercase = false) {
	$str = preg_replace('/ {2,}/',' ', $str);
	$str = trim($str);
	// In thường
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);
	// In HOA
    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    $str = preg_replace("/( )/", '-', $str);
    if ($lowercase) {
        $str = strtolower($str);
    }
	$temp = explode('-',$str);
	$temp = array_map('ucfirst',$temp);
	$str = implode('-',$temp);
    return $str; // Trả về chuỗi đã chuyển
}
function __label($n) {
	$label = 'tập tin';
	switch(strtolower($n)) {
		case 'cmnd_mt' : $label = 'CMND mặt trước'; break;
		case 'cmnd_ms' : $label = 'CMND mặt sau'; break;
		case 'chandung' : $label = 'Chân dung'; break;
		case 'pyc' : $label = 'Phiếu yêu cầu'; break;
	}
	return $label;
}
function handle_upload($file, $post) {
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".",$file['name']);
	$extension = end($temp);
	/*
	if (!in_array($file["type"], array('image/gif','image/jpg','image/jpeg','image/pjpeg','image/x-png','image/png'))) {
		return __retVal('error',null,'Tập tin tải lên không đúng định dạng !');
	}
	*/
	if (!isset($post['image_name']) || empty($post['image_name'])) {
		return __retVal('error',null,'Hệ thống không hiểu tập tin');
	}
	$telephone = isset($post['telephone']) ? trim($post['telephone']) : null;
	$fullname = isset($post['fullname']) ? trim($post['fullname']) : null;
	$shopid = isset($post['shopid']) ? trim($post['shopid']) : null;
	if (!$telephone || empty($telephone)) {
		return __retVal('error',null,'Vui lòng nhập vào số điện thoại');
	}
	if (!$fullname || empty($fullname)) {
		return __retVal('error',null,'Vui lòng nhập vào tên khách hàng');
	}
	if (!$shopid || empty($shopid)) {
		return __retVal('error',null,'Vui lòng chọn cửa hàng');
	}
	$fullname = utf82ascii($fullname);
	$subdir = $shopid . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . $fullname;
	$upload_path = UPLOAD_PATH . DIRECTORY_SEPARATOR . $subdir;
	if (!is_dir($upload_path)) {
		@umask(0);
		$ret = @mkdir($upload_path, 0777, true);
		if ($ret === false) {
			return __retVal('error',null, 'Lỗi tạo thư mục chứa !');
		}
	}
	$dest_file = $upload_path . DIRECTORY_SEPARATOR . $post['image_name'] . '_' . $telephone . '.'.strtolower($extension);
	if (@move_uploaded_file($file['tmp_name'], $dest_file)) {
		$stat = stat ( dirname ( $dest_file ) );
		$perms = $stat ['mode'] & 0000666;
		@ chmod ( $dest_file, $perms );
		$filename = basename($dest_file);
		$url = UPLOAD_URL .$subdir. "/$filename";
        $filesize = filesize($dest_file);
        if ($filesize <= 0) {
            return __retVal('error', null, 'Lỗi tải '.__label($post['image_name']).' lên máy chủ !');
        }
		return __retVal('success',array('url' => $url, 'path' => str_replace( UPLOAD_PATH . DIRECTORY_SEPARATOR,'',$dest_file), 'filename' => $filename), 'Tải ' . __label($post['image_name']) . ' thành công !');
	}
	return __retVal('error',null, 'Lỗi tải '.__label($post['image_name']).' lên máy chủ !');
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: x-requested-with, x-file-name, x-index, x-total, x-name, Content-Type, origin, authorization, accept, client-security-token");
if (isset($_FILES['chunk']) && AJAX_DOING) {
	header('Content-Type:application/json;charset=utf-8');
	// Kiem tra hinh anh thuoc truong nao CMND mat truoc,CMND mat sau, Phieu yeu cau, Chan dung
	if (!isset($_SERVER['HTTP_X_NAME']) || empty($_SERVER['HTTP_X_NAME'])) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Vui lòng chọn hình ảnh"}';
		exit;
	}
	// Kiem tra bytes dang lay la bytes thu bao nhieu
	if (!isset($_SERVER['HTTP_X_INDEX'])) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Hệ thống không hiểu dữ liệu"}';
		exit;
	}
	// Kiem tra toan bo tap tin la bao nhieu bytes
	if (!isset($_SERVER['HTTP_X_TOTAL'])) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Hệ thống không hiểu kích thước tập tin"}';
		exit;
	}
	// Bat ket noi tiep tuc, an loi, tu dong ngat phien khi ma nguoi dung thoat
	header('Connection:continue');
	@ini_set ( 'display_errors', 0 );
	ignore_user_abort ( true );
	// Tien hanh kiem tra du lieu dau vao (So dien thoai, Ho va ten, Cua hang)
	$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : null;
	$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : null;
	$shopid = isset($_POST['shopid']) ? trim($_POST['shopid']) : null;
	$time_id = isset($_POST['time_id']) ? trim($_POST['time_id']) : null;
	if (!$telephone || empty($telephone)) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Vui lòng nhập vào số điện thoại"}';
		exit;
	}
	if (!$fullname || empty($fullname)) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Vui lòng nhập vào tên khách hàng"}';
		exit;
	}
	if (!$shopid || empty($shopid)) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Vui lòng chọn cửa hàng"}';
		exit;
	}
	if (!$time_id || empty($time_id)) {
		header('Connection:close');
		echo '{"status":"error","data":null,"msg":"Lỗi hệ thống vui lòng thử lại"}';
		exit;
	}
	// Ok du lieu da day du tien hanh upload tap tin
	$completedPath = sys_get_temp_dir() . '/' . $_SERVER['HTTP_X_TOTAL'].'-'.$time_id.'-'.$_FILES['chunk']['name'];
	if (isset($_SERVER['HTTP_X_INDEX']) && $_SERVER['HTTP_X_INDEX'] == BYTES_PER_CHUNK) {
		$completed = @fopen($completedPath, "wb"); // Neu nhu doc tu byte dau tien thi tao tapj tin
	} else {
		$completed = @fopen($completedPath, "ab"); // Nguoc lai thi ghi them du lieu vao
	}
	if ($completed) {
		$in = fopen($_FILES['chunk']['tmp_name'], "rb"); // Doc ty du lieu gui len
		if ( $in ) {
			while ( $buff = fread( $in, BYTES_PER_CHUNK ) ) {
				fwrite($completed, $buff);
			}
		}
		fclose($in);
		fclose($completed);
	}
	// Kiem tra tap tin da duoc tai len het
	if (intval($_SERVER['HTTP_X_TOTAL']) == intval($_SERVER['HTTP_X_INDEX'])) {
		$filesize = filesize($completedPath);
		if ($filesize != intval($_SERVER['HTTP_X_TOTAL'])) { // Truyen tap tin thieu bytes
			header('Connection:close');
			@unlink($completedPath);
			echo json_encode(__retVal('error',null, 'Quá trình tải lên bị mất mát dữ liệu !'));
			exit;
		}
		// Kiem tra tap tin co phai la hinh anh hay khong ?
		$filename = str_replace($_SERVER['HTTP_X_TOTAL'].'-'.$time_id.'-','',basename($completedPath));
		$orig_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowed_filetype = array('png','jpg','gif','jpeg');
		if (!in_array($orig_ext, $allowed_filetype)) {
			header('Connection:close');
			@unlink($completedPath);
			echo json_encode(__retVal('error',null, 'Tập tin tải lên không đúng định dạng !'));
			exit;
		}
		// OK tien hanh dua tap tin vo thu muc
		$fullname = utf82ascii($fullname);
		$subdir = $shopid . DIRECTORY_SEPARATOR . date('Y-m-d') . DIRECTORY_SEPARATOR . $fullname;
		$upload_path = UPLOAD_PATH . DIRECTORY_SEPARATOR . $subdir;
		if (!is_dir($upload_path)) {
			@umask(0);
			$ret = @mkdir($upload_path, 0777, true);
			if ($ret === false) {
				header('Connection:close');
				@unlink($completedPath);
				echo json_encode(__retVal('error',null, 'Lỗi tạo thư mục chứa !'));
				exit;
			}
		}
		$dest_file = $upload_path . DIRECTORY_SEPARATOR . $_SERVER['HTTP_X_NAME'] . '_' . $telephone . '.'.$orig_ext;
		if (!@copy($completedPath, $dest_file)) {
			header('Connection:close');
			@unlink($completedPath);
			echo json_encode(__retVal('error',null, 'Không thể tạo được tập tin vào thư mục chứa !'));
			exit;
		}
		@unlink($completedPath);
		$stat = stat ( dirname ( $dest_file ) );
		$perms = $stat ['mode'] & 0000666;
		@ chmod ( $dest_file, $perms );
		$filename = basename($dest_file);
		$url = UPLOAD_URL .$subdir. "/$filename";
        $filesize = filesize($dest_file);
        if ($filesize <= 0) {
            echo json_encode(__retVal('error',null, 'Tải '.__label($_SERVER['HTTP_X_NAME']).' thất bại !'));
            exit;
        }
		echo json_encode(__retVal('success',array('url' => $url, 'path' => str_replace( UPLOAD_PATH . DIRECTORY_SEPARATOR,'',$dest_file), 'filename' => $filename), 'Tải '.__label($_SERVER['HTTP_X_NAME']).' thành công !'));
		exit;
	}
	echo json_encode(__retVal('success',null, 'Tải lên '.intval($_SERVER['HTTP_X_INDEX']) .'/'. intval($_SERVER['HTTP_X_TOTAL']).' !'));
	exit;
}

if (isset($_FILES['image']) && isset($_POST['image_name'])) {
	$image_name = trim($_POST['image_name']);
	$post = array(
		'image_name' => $image_name
	);
	if (isset($_POST['telephone'])) {
		$post['telephone'] = $_POST['telephone'];
	}
	if (isset($_POST['fullname'])) {
		$post['fullname'] = $_POST['fullname'];
	}
	if (isset($_POST['shopid'])) {
		$post['shopid'] = $_POST['shopid'];
	}
	$retVal = handle_upload($_FILES['image'], $post);
	if (AJAX_DOING) {
		header('Content-Type:application/json;charset=utf-8');
		echo json_encode($retVal);
		exit;
	}
	$msg = $retVal['msg'];
	$_msg_sta = $retVal['status'];
}

if (isset($_POST['form']) && is_array($_POST['form'])) {
	$data = (array)$_POST['form'];
    $form = array();
    if (isset($data['time_id']) && !empty($data['time_id'])) {
        $form['time_id'] = trim($data['time_id']);
    }
    if (isset($data['shopid']) && !empty($data['shopid'])) {
        $form['cuahang'] = trim($data['shopid']);
    }
    if (isset($data['fullname']) && !empty($data['fullname'])) {
        $form['tenkhachhang'] = trim($data['fullname']);
    }
    
    if (!isset($data['time_id'])) {
        $retVal = __retVal('error',$form, 'Lỗi hệ thống vui lòng thử lại');
    } else if (!isset($data['shopid'])) {
        $retVal = __retVal('error',$form, 'Thiếu thông tin cửa hàng !');
    } else if (!isset($data['fullname'])) {
        $retVal = __retVal('error',$form, 'Thiếu thông tin họ tên người dùng !');
    } else {
        $images = array();
        $fields = array('cmnd_mt', 'cmnd_ms', 'chandung', 'pyc');
        foreach($fields as $field) {
            if (!isset($data[$field])) {
                $retVal = __retVal('error',$form, 'Thiếu thông tin '.__label($field).' !');
                break;
            } else {
                $tenfile = basename($data[$field]);
                $dir_server = str_replace(DIRECTORY_SEPARATOR . $tenfile, '', $data[$field]);
                $images[] = array(
                    'tenfile' => $tenfile,
                    'dir_server' => $dir_server
                );
            }
        }
        if (empty($images) || count($images) < 4) {
            $retVal = __retVal('error', $form, 'Thiếu thông tin hình ảnh !');
        }
        if (!isset($retVal)) {
            define('DB_NAME', 'thongtinthuebao');
            define('DB_USER', 'root');
            define('DB_PASSWORD', 'ddde310b09da243b1');
            define('DB_HOST', 'localhost');
            require_once dirname(__FILE__) . '/mysqlii.php';
            $DB = MySqlii::getInstance();
            $ids = array();
            foreach($images as $image) {
                $keys = $values = array();
                $raw = $form + $image;
                foreach($raw as $key => $value) {
                    $keys[] =  $key;
                    $values[] = "'".$DB->escape_string($value)."'";
                }
                $DB->query("insert into khachhang (".implode(',',$keys).") values (".implode(',',$values).")");
                if ($DB->affected_rows() > 0) {
                    $ids[] = $DB->insert_id();
                }
            }
            if (count($ids) != 4) {
                $retVal = __retVal('error', $ids, 'Cập nhật thông tin người dùng còn thiếu hình ảnh, vui lòng thử lại !');
                if (!empty($ids)) {
                    $DB->query("delete from khdemo where id in (".implode(',',$ids).")");
                }
            } else {
                $retVal = __retVal('success', array('ids' => $ids, 'time_id' => date('His') . round(microtime(true) * 1000)), 'Cập nhật thông tin người dùng thành công !');
            }
        }
    }
    if(isset($retVal)) {
        if (AJAX_DOING) {
            header('Content-Type:application/json;charset=utf-8');
            echo json_encode($retVal);
            exit;
        }
        $msg = $retVal['msg'];
        $_msg_sta = $retVal['status'];
    }
    
    //X_FIELD_NAME
    //`time_id`, `cuahang`, `tenkhachhang`, `tenfile`, `dir_server`
	/*
	define('DB_NAME', 'thongtinthuebao');
	define('DB_USER', 'root');
	define('DB_PASSWORD', 'ddde310b09da243b1');
	define('DB_HOST', 'localhost');
	require_once dirname(__FILE__) . '/mysqlii.php';
	$DB = MySqlii::getInstance();
	$keys = $values = array();
	foreach($form as $key => $value) {
		$keys[] =  $key;
		$values[] = "'".$DB->escape_string($value)."'";
	}
	$DB->query("insert into table_name (".implode(',',$keys).") values (".implode(',',$values).")");
	if ($DB->affected_rows() > 0) {
		$retVal = __retVal('success', '#'.$DB->insert_id(), 'Lưu thông tin khách hàng vào CSDL thành công !');
	} else {
		$retVal = __retVal('error', null, 'Lưu thông tin khách hàng vào CSDL thất bại !');
	}
    // $time_id, $cuahang, $tenkh, $file_name, $database_url
	
	$retVal = __retVal('success',$form, 'Lưu CSDL thành công');
	if (AJAX_DOING) {
		header('Content-Type:application/json;charset=utf-8');
		echo json_encode($retVal);
		exit;
	}
	$msg = $retVal['msg'];
	$_msg_sta = $retVal['status'];*/
}

$ds_cuahang = array(
    '' => 'Chọn cửa hàng',
    'xnkrga' => 'CH XNK Rạch giá',
    'xnkrs' => 'CH XNK Rạch Sỏi',
    '1400330004' => 'CHTT Lạc Hồng',
    '1400330001' => 'CHTT 33 NBK',
    '1400330005' => 'CHTT Rạch Sỏi',
    '1400330019' => 'CHTT Hòn Tre',
    '1400330010' => 'CHTT Châu Thành',
    '1400330011' => 'CHTT Giồng Riềng',
    '1400330012' => 'CHTT Gò Quao',
    '1400330009' => 'CHTT Tân Hiệp',
    '1400330013' => 'CHTT An Biên',
    '1400330014' => 'CHTT An Minh',
    '1400330015' => 'CHTT Vĩnh Thuận',
    '1400330017' => 'CHTT U Minh Thượng',
    '1400330008' => 'CHTT Hòn Đất',
    '1400330018' => 'CHTT Sóc Xoài',
    '1400330007' => 'CHTT Kiên Lương',
    '1400330006' => 'CHTT Hà Tiên',
    '1400330003' => 'CHTT Giang Thành',
    '1400330016' => 'CHTT Dương Đông',
    '1400330002' => 'CHTT An Thới',
);
?>
<!doctype html>
<html>
<head>
<link rel="shortcut icon" href="<?php echo SITE_URL; ?>favicon.png" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta charset="utf-8">
<style><?php ob_start(); ?>
* {
	margin: 0;
	padding: 0;
}

body {
	background-color: #f0f0f0;
	font-size: 13px;
	font-family: Arial, Helvetica, sans-serif;
}

.main-wrap {
	min-width: 300px;
	max-width: 450px;
	margin: 5% auto 0;
	background-color: #fff;
	border-radius: 5px;
	box-shadow: inset 0 1px 0 0 #fff;
	border: 1px solid #cdd3d7;
	-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.main-wrap p{
	padding: 5px 10px;
	transition: height 500ms;
	-webkit-transition: height 500ms;
}

.main-wrap h1 {
	padding: 10px;
	margin-bottom: 10px;
	/* border-bottom: 1px solid #ccc; */
}

.main-wrap input[type="text"],
.main-wrap input[type="number"],
.main-wrap input[type="submit"] {
	padding: 10px 0;
	width: 100%;
	border-radius: 3px;
	border: none;
	outline: none;
}
.main-wrap p.field {
	position:relative;	
}
.main-wrap p.field:after {
	content: "OK";
    display: block;
    padding: 10px;
    background-color: #ddd;
    position: absolute;
    top: 5px;
    right: 10px;
    border-radius: 0 3px 3px 0;
    font-weight: bold;
	z-index:1000;
}
.main-wrap select {
    padding:10px;
    width: 100%;
	border-radius: 3px;
	border: none;
	outline: none;
    background-color: #eee;
	outline:none;
}
.main-wrap input[type="text"],.main-wrap input[type="number"] {
	text-indent: 10px;
	color: #333;
	background-color: #eee;
	outline:none;
}

.main-wrap input[name="telephone"] {
	/*border: 1px solid red;*/
}

.main-wrap input[name="fullname"] {
	/*border: 1px solid red;*/
	margin-bottom:10px;
}

.main-wrap input[type="submit"] {
	background-color: #1e88e5;
	color: #fff;
	text-transform: uppercase;
	font-size: 15px;
	margin-bottom: 10px;
	outline:none;
}
p.hide_first {
	display:none;
}
p.bill {
	background-color: #f1f1f1;
	padding: 15px 10px;
	border: 2px dashed #ccc;
	width: 90%;
	margin: 0 auto 10px;
	text-align: center;
	overflow: hidden;
	position: relative;
	font-weight:bold;
}

p.bill span img {
	vertical-align:middle;
	margin-left:10px;
}

p.bill input[type="file"] {
	position: absolute;
	top: 0;
	left: 0;
	font-size: 45px;
	opacity: 0;
}

p.bill:before {
    color: #333;
    content: "[ + ]";
    font-size: 35px;
    position: absolute;
    top: 0px;
    right: 5px;
    z-index: 0;
    opacity: .3;
}

p.message {
	margin:-10px 0 10px;
	padding: 18px;
	background-color: green;
	color: #fff;
	line-height: 23px;
}
p.error {
	background-color: red;
	color: yellow;
}

a.preview {
    width: 45px;
    height: 45px;
    display: block;
    overflow: hidden;
    position: absolute;
    top: 0;
    left: 0;
}
    
a.preview img {
    display: block;
    width: 200%;
    height: auto;
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
		font-size: 92%;
	}
	.main-wrap h1 {
		font-size:120%;
	}
	.main-wrap p.field:after {
		padding:9px;
	}
}
<?php
$content = ob_get_contents();
ob_clean();
ob_end_clean();
ob_end_flush();
$content = preg_replace('/[\r\n\t]*/','',$content);
$content = preg_replace('/ {2,}/',' ',$content);
echo trim($content);
?>
</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<title>Cập nhật thông tin thuê bao</title>
</head>
<body>
<div class="main-wrap">
<h1>Cập nhật thông tin thuê bao</h1>
	<form action="<?php echo SITE_URL; ?>" id="frminfo" method="post" autocomplete="off">
	<p class="message<?php echo isset($_msg_sta) ? ' '.$_msg_sta : ''; ?>"><?php echo isset($msg) ? $msg : 'Vui lòng nhập vào đầy đủ thông tin'; ?></p>
    <p><select id="cuahang" name="shopid">
        <?php
		$curent_ch = isset($_COOKIE['shopid']) ? trim($_COOKIE['shopid']) : '';
        foreach ($ds_cuahang as $mach => $tench) {
            $selected = ($curent_ch == $mach) ? "selected='selected'" : "";
            echo "<option $selected value='$mach'>$tench</option>";
        }
        ?>
        </select></p>
    <p class="field telephone hide_first"><input type="number" name="telephone" value="" placeholder="Số điện thoại đăng ký" autocomplete="off" /></p>
    <p class="field fullname hide_first"><input type="text" name="fullname" value="" placeholder="Họ và tên khách hàng" autocomplete="off" /></p>
    <p class="bill chandung hide_first">Ảnh chân dung<span></span><input type="file" name="chandung" accept="image/x-png,image/gif,image/jpeg" /></p>
    <p class="bill cmnd_mt hide_first">CMND mặt trước<span></span><input type="file" name="cmnd_mt" accept="image/x-png,image/gif,image/jpeg" /> </p>
    <p class="bill cmnd_ms hide_first">CMND mặt sau<span></span><input type="file" name="cmnd_ms" accept="image/x-png,image/gif,image/jpeg" /></p>
    <p class="bill pyc hide_first">Phiếu yêu cầu<span></span><input type="file" name="pyc" accept="image/x-png,image/gif,image/jpeg" /></p>
    <input type="hidden" name="time_id" value="<?php echo date('His') . round(microtime(true) * 1000); ?>" />
    <p class="submit_btn hide_first"><input type="submit" name="submit" value="Lưu thông tin" /></p>
	</form>
</div>
<script>
<?php ob_start(); ?>
	var sendData = function(url, data, callback) {
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener("progress", function(e) {
			if(e.lengthComputable) {
				var percentComplete = Math.round(e.loaded * 100 / e.total);
			}
		}, false);
		xhr.addEventListener("load", function(e) {
			if (e.target.responseText.length > 0) {
				try {
					var obj = JSON.parse(e.target.responseText);
					callback(obj);
				} catch (ex) {
					callback({status: 'error',data:null, msg : 'Hệ thống đang bận vui lòng thử lại sau !'});
				}
			} else {
				callback({status: 'error',data:null, msg : 'Hệ thống đang bận vui lòng thử lại sau '});
			}
			xhr = null;
		}, false);
		xhr.addEventListener("error", function(e) {
			callback({status: 'error',data:null, msg : "Lỗi hệ thống vui lòng thử lại sau"});
		}, false);
		xhr.addEventListener("abort", function(e) {
			xhr.abort();
			xhr = null;
		}, false);
		xhr.open('POST',url, true);
		xhr.withCredentials = true;
		xhr.send(data);
	};
	(function($) {
        $('input[type="file"]').change( function() {
            var fdata = {
				telephone : '',
				fullname : '',
				shopid : '',
                time_id : ''
			};
			fdata.telephone = $.trim($('input[name="telephone"]').val());
			fdata.fullname = $.trim($('input[name="fullname"]').val());
			fdata.shopid = $.trim($('select[name="shopid"] option:selected').val());
			fdata.time_id = $.trim($('input[name="time_id"]').val());
            
			if (!fdata.shopid || fdata.shopid.length <= 0) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Vui lòng chọn chi nhánh !');
				return false;
			}
			if (!fdata.telephone || /^0[0-9]{9,10}$/.test(fdata.telephone) == false) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Vui lòng nhập vào số thuê bao !');
				return false;
			}
			if (!fdata.fullname || fdata.fullname.length <= 0) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Vui lòng nhập vào tên khách hàng !');
				return false;
			}
			if (!fdata.time_id || fdata.time_id.length <= 0) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Lỗi hệ thống vui lòng thử lại !');
				return false;
			}
			
			var file = this.files[0];
			if (file.size > 0 && ['image/png','image/jpg','image/gif','image/jpeg'].indexOf(file.type) != -1) {
				var _URL = window.URL || window.webkitURL;
				var blobImage = _URL.createObjectURL(file);
				var blobName = file.name;
				var input_name = $(this).attr('name');
				if (!input_name) {
					$('p.message').removeClass('error success').addClass('error');
					$('p.message').html('Lỗi hệ thống vui lòng thử lại !');
					return false;
				}
				$('input[name="'+input_name+'"]').parent().find('span').html('<img src="<?php echo SITE_URL; ?>imgs/loading.gif" />');
				fetch(blobImage).then(function(response) {
					if (response.status === 200 || response.status === 0) {
						return response.blob();
					}
					$('p.message').removeClass('error success').addClass('error');
					$('p.message').html('Kết nối mạng không ổn định vui lòng thử lại');
				}).then(function(blob) {
					if (blob.size > <?php echo BYTES_PER_CHUNK; ?>) {
						const BYTES_PER_CHUNK = <?php echo BYTES_PER_CHUNK;?>;
						const SIZE = blob.size;
						var start = 0;
						var end = BYTES_PER_CHUNK;
						var bytes = BYTES_PER_CHUNK;
						
						var part = function(blob, start, end, callback) {
							if ('mozSlice' in blob) {
								var chunk = blob.mozSlice(start, end);
							} else if ('webkitSlice' in blob) {
								var chunk = blob.webkitSlice(start, end);
							} else {
								var chunk = blob.slice(start, end);
							}
							var formData = new FormData();
							formData.append('chunk',chunk, (blobName || 'image.jpg'));
							for(var key in fdata) {
								formData.append(key, fdata[key]);
							}
							var xhr = new XMLHttpRequest();
							xhr.upload.addEventListener("progress", function(e) {
								if(e.lengthComputable) {
									var percentComplete = Math.round(e.loaded * 100 / e.total);
								}
							}, false);
							xhr.addEventListener("load", function(e) {
								if (e.target.responseText.length > 0) {
									try {
										var obj = JSON.parse(e.target.responseText);
										if (obj.status == 'success') {
											if (end < blob.size) {
												start = end;
												end = start + BYTES_PER_CHUNK;
												if (end > blob.size) {
													end = blob.size;
												}
												bytes = end;
												part(blob, start, end, callback);
											} else {
												return callback(obj);
											}
											xhr = null;
										} else {
											return callback(obj);
										}
									} catch (ex) {
										return callback({status:'error', data : null, msg : ex.message});
									}
								} else {
									xhr = null;
									return callback({status:'error',data:null, msg : 'Máy chủ đang bận vui lòng thử lại.'});
								}
							});
							xhr.addEventListener("error", function(e) {
								$('p.message').removeClass('error success').addClass('error');
								$('p.message').html("Lỗi tải tập tin vui lòng thử lại.");
							}, false);
							xhr.addEventListener("abort", function(e) {
								xhr.abort();
								xhr = null;
							}, false);
							xhr.open('POST','<?php echo SITE_URL; ?>', true);
							xhr.withCredentials = true;
							xhr.setRequestHeader("X-Index", bytes);
							xhr.setRequestHeader("X-Total", blob.size);
							xhr.setRequestHeader("X-Name", input_name);
							xhr.send(formData);
						};
						part(blob, start, end, function(obj) {
							$('p.message').removeClass('error success');
							$('p.message').addClass(obj.status);
							if (obj.data == null) {
								var msg = obj.msg || 'Hệ thống đang bận vui lòng thử lại sau';
								$('p.message').html(msg);
								$('input[name="'+input_name+'"]').parent().find('span').html('<img src="<?php echo SITE_URL; ?>imgs/err.gif" />')
							} else {
                                $('p.'+input_name+' a.preview').remove();
								$('input[name="'+input_name+'"]').before('<a class="preview" target="_preview" href="'+obj.data.url+'"><img src="'+obj.data.url+'" /><input type="hidden" name="'+input_name+'_path" value="'+obj.data.path+'" /><input type="hidden" name="'+input_name+'_url" value="'+obj.data.url+'" /><input type="hidden" name="'+input_name+'_filename" value="'+obj.data.filename+'" /></a>');
								$('p.message').html(obj.msg);
								$('input[name="'+input_name+'"]').parent().find('span').html('<img src="<?php echo SITE_URL; ?>imgs/ok.png" />');
							}
						});
					} else {
						var formData = new FormData();
						formData.append('image',blob, (blobName || 'image.jpg'));
						for(var key in fdata) {
							formData.append(key, fdata[key]);
						}
						formData.append('image_name', input_name);
						$('input[name="'+input_name+'"]').parent().find('span').html('<img src="<?php echo SITE_URL; ?>imgs/loading.gif" />');
						sendData('<?php echo SITE_URL; ?>',formData, function(obj) {
							$('p.message').removeClass('error success');
							$('p.message').addClass(obj.status);
							if (obj.data == null) {
								var msg = obj.msg || 'Hệ thống đang bận vui lòng thử lại sau';
								$('p.message').html(msg);
								$('input[name="'+input_name+'"]').parent().find('span').html('<img src="<?php echo SITE_URL; ?>imgs/err.gif" />')
							} else {
                                $('p.'+input_name+' a.preview').remove();
								$('input[name="'+input_name+'"]').before('<a class="preview" target="_preview" href="'+obj.data.url+'"><img src="'+obj.data.url+'" /><input type="hidden" name="'+input_name+'_path" value="'+obj.data.path+'" /><input type="hidden" name="'+input_name+'_url" value="'+obj.data.url+'" /><input type="hidden" name="'+input_name+'_filename" value="'+obj.data.filename+'" /></a>');
								$('p.message').html(obj.msg);
								$('input[name="'+input_name+'"]').parent().find('span').html('<img src="<?php echo SITE_URL; ?>imgs/ok.png" />');
							}
						});
					}
				});
			}
        });
		$('select[name="shopid"]').on('change', function() {
			var val = $('option:selected',this).val();
			if (val.length > 0) {
				$('p.telephone').removeClass('hide_first');
			} else {
				$('p.field, p.bill, p.submit_btn').addClass('hide_first');
			}
		});
		$('input[name="telephone"]').on("focus blur", function() {
			var telephone = $.trim($('input[name="telephone"]').val());
			if (!telephone || /^0[0-9]{9,10}$/.test(telephone) == false) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Vui lòng nhập vào số thuê bao !');
				return false;
			}
			$('p.fullname').removeClass('hide_first');
		});
		$('input[name="fullname"]').on("focus blur", function() {
			var telephone = $.trim($('input[name="telephone"]').val());
			var fullname = $.trim($('input[name="fullname"]').val());
			if (!telephone || /^0[0-9]{9,10}$/.test(telephone) == false) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Vui lòng nhập vào số thuê bao !');
				return false;
			}
			if (!fullname || fullname.length <= 0) {
				$('p.message').removeClass('error success').addClass('error');
				$('p.message').html('Vui lòng nhập vào tên khách hàng !');
				return false;
			}
			$('p.message').html('Vui lòng tải hình ảnh.');
			$('p.bill, p.submit_btn').removeClass('hide_first');
		});
		$('form[name="frminfo"]').on('submit', function(e) { e.preventDefault(); return false; });
		$('input[type="submit"]').on('click', function(e) {
			e.preventDefault();
			var fdata = {
				telephone : '',
				fullname : '',
				shopid : '',
                time_id : ''
			};
			fdata.telephone = $.trim($('input[name="telephone"]').val());
			fdata.fullname = $.trim($('input[name="fullname"]').val());
			fdata.shopid = $.trim($('select[name="shopid"] option:selected').val());
            fdata.time_id = $.trim($('input[name="time_id"]').val());
			
			var field = '<?php echo X_FIELD_NAME; ?>';
			
			var fields = ['cmnd_mt', 'cmnd_ms', 'chandung', 'pyc'];
			
			for(var f in fields) {
				if ($('input[name="'+fields[f] + field+'"]').length <= 0) {
					var msg = $('p.'+fields[f]).text() + ' chưa bổ sung';
					if (!msg) {
						msg = 'Kiểm tra hình ảnh đang còn thiếu';
					}
					$('p.message').html(msg);
					return false;
				} else {
					fdata[fields[f]] = $('input[name="'+fields[f] + field+'"]').val();
				}
			}
			
			if (!fdata.shopid || fdata.shopid.length <= 0) {
				$('p.message').html('Vui lòng chọn chi nhánh !');
				return false;
			}
			if (!fdata.telephone || /^0[0-9]{9,10}$/.test(fdata.telephone) == false) {
				$('p.message').html('Vui lòng nhập vào số thuê bao !');
				return false;
			}
			if (!fdata.fullname || fdata.fullname.length <= 0) {
				$('p.message').html('Vui lòng nhập vào tên khách hàng !');
				return false;
			}
            if (!fdata.time_id || fdata.time_id.length <= 0) {
				$('p.message').html('Lỗi hệ thống vui lòng thử lại !');
				return false;
			}
			$.ajax({
				url : '<?php echo SITE_URL; ?>',
				type : 'post',
				data : { form : fdata},
				async : true,
				dataType:'json',
				beforeSend: function() {
					$('p.message').html('Đang gửi dữ liệu <span><img src="<?php echo SITE_URL;?>imgs/loading.gif" /></span>');
				},
				success : function(obj) {
					$('p.message').removeClass('error success');
					$('p.message').addClass(obj.status);
					$('p.message').html(obj.msg);
					if (obj.status == 'success') {
						/*var fields = ['cmnd_mt', 'cmnd_ms', 'chandung', 'pyc'];
						for(var i in fields) {
							$('input[name="'+fields[i]+'"]').remove();
							$('input[name="'+fields[i]+'_url"]').remove();
							$('input[name="'+fields[i]+'_path"]').remove();
							$('input[name="'+fields[i]+'_filename"]').remove();
						}*/
                        $('input[name="time_id"]').val(obj.data.time_id);
                        $('a.preview').remove();
						$('input[name="telephone"]').val('');
						$('input[name="fullname"]').val('');
						/* $('select[name="shopid"]').val(''); */
						$('p.field, p.bill, p.submit_btn').each(function() {
							if ($(this).hasClass('telephone') == false) {
								$(this).addClass('hide_first');
							}
						});
					}
				}
			});
		});
    })(jQuery);<?php
$content = ob_get_contents();
ob_clean();
ob_end_clean();
ob_end_flush();
$content = preg_replace('/[\r\n\t]*/','',$content);
$content = preg_replace('/ {2,}/',' ',$content);
echo trim($content);
?>
</script>
</body>
</html>
