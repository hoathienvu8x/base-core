<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
if (isset($_GET['msg']) && is_numeric($_GET['msg'])) {
	$msg_errno = intval($_GET['msg']);
	$msg_status = '';
	switch ($msg_errno) {
		case 1:
			$msg = 'Thư điện tử không hợp lệ !';
			break;
		case 2:
			$msg = 'Vui lòng nhập vào họ và tên đầy đủ !';
			break;
		case 3:
			$msg = 'Thư điện tử này đã tồn tại trên hệ thống !';
			break;
		case 4:
			$msg = 'Mật khẩu quá ngắn !';
			break;
		case 5:
			$msg = 'Cập nhật thông tin thành công !';
			$msg_status = ' success';
			break;
		case 6:
			$msg = 'Cập nhật thông tin thất bại !';
			break;
		default:
			$msg = 'Hệ thông không hiểu lỗi này !';
			$msg_status = ' warning';
			break;
	}
	echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}
?>
<div class="form-elements">
	<form action="<?php echo Url::profile();?>" method="post">
		<p>
			Họ và tên:<br />
			<input type="text" name="nickname" value="<?php echo isset($u_data['nickname']) ? $u_data['nickname'] : Auth::get('nickname',''); ?>" /><br />
			Tên đăng nhập:<br />
			<input type="text" name="uname" readonly="readonly" value="<?php echo Auth::get('uname',''); ?>" /><br />
			Mật khẩu:<br />
			<input type="password" name="password" value="" /><br />
			Thư điện tử:<br />
			<input type="text" name="email" value="<?php echo isset($u_data['email']) ? $u_data['email'] : Auth::get('email',''); ?>" /><br />
		</p>
		<p class="bottom">
			<input type="submit" name="saved" value="Cập nhật" />
		</p>
	</form>
</div>
<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>