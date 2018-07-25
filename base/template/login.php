<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div id="login">
	<form action="<?php echo Url::home();?>" method="post" id="frmlogin">
		<p>
			Tên đăng nhập:<br />
			<input type="text" name="username" id="username" value="" autocapitalize="off" autocomplete="off" /><br />
			Mật khẩu:<br />
			<input type="password" name="password" id="password" value="" autocapitalize="off" autocomplete="off" />
		</p>
		<p id="pbottom">
			<a href="<?php echo Url::action('forgot'); ?>">Quên mật khẩu ư ?</a>
			<input type="submit" name="login" id="login-btn" value="Đăng nhập"/>
		</p>
	</form>
</div>
<?php
if (isset($_GET['msg'])) {
	$msg = intval($_GET['msg']);
	?><div id="login-msg"><?php
	switch ($msg) {
		case 1:
			echo 'Tên đăng nhập này không tồn tại !';
			break;
		case 2:
			echo 'Sai mật khẩu đăng nhập !';
			break;
		case 3:
			echo 'Sai mã xác nhận !';
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
if (isset($_GET['lg']) && $_GET['lg'] == 'true') {
	?><div id="logout-msg">Đăng xuất thành công !</div><?php
}
require_once TEMPLATEPATH . 'footer.php';
exit;
?>