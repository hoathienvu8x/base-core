<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
?>
<div id="forgot">
	<form action="<?php echo Url::forgot();?>" method="post" id="frmforgot">
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
	?><div id="forgot-msg"><?php echo User::message($msg); ?></div><?php
}
require_once Xtemplate::get( 'footer' );
?>
