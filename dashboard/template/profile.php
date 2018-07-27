<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
if (isset($_GET['msg']) && is_numeric($_GET['msg'])) {
	$msg_errno = intval($_GET['msg']);
	$msg_status = '';
	$msg = User::message($msg_errno);
	if ($msg_errno == User::ERROR_UPDATED) {
		$msg_status = ' success';
	}
	echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}
?>
<div class="form-elements">
	<form action="<?php echo Url::profile();?>" method="post">
		<p>
			Họ và tên:<br />
			<input type="text" name="nickname" value="<?php echo isset($u_data['nickname']) ? $u_data['nickname'] : get_user_data('nickname',''); ?>" /><br />
			Tên đăng nhập:<br />
			<input type="text" name="username" readonly="readonly" value="<?php echo get_user_data('username',''); ?>" /><br />
			Mật khẩu:<br />
			<input type="password" name="password" value="" /><br />
			Thư điện tử:<br />
			<input type="text" name="email" value="<?php echo isset($u_data['email']) ? $u_data['email'] : get_user_data('email',''); ?>" /><br />
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
