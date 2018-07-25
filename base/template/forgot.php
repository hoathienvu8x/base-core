<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div id="forgot">
	<form action="<?php echo Url::action('forgot');?>" method="post" id="frmforgot">
		<p>
			Tên đăng nhập/thư điện tử:<br />
			<input type="text" name="field_chk" id="field_chk" value="" autocapitalize="off" autocomplete="off" />
		</p>
		<p id="pbottom">
			<a href="<?php echo Url::home(); ?>">Đăng nhập</a>
			<input type="submit" name="login" id="login-btn" value="Lấy mật khẩu"/>
		</p>
	</form>
</div>
<?php
if (isset($_GET['msg'])) {
	$msg = intval($_GET['msg']);
	?><div id="forgot-msg"><?php
	switch ($msg) {
		case 1:
			echo 'Tên đăng nhập này không tồn tại !';
			break;
		case 2:
			echo 'Thư điện tử không tồn tại !';
			break;
		case 4:
			echo 'Tài khoản này đang tạm khóa !';
			break;
		case 5:
			echo 'Vui lòng nhập vào đầy đủ thông tin !';
			break;
		default:
			echo 'Hệ thống không hiểu lỗi này !';
			break;
	}
	?></div><?php
}
require_once TEMPLATEPATH . 'footer.php';
?>