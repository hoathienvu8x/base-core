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
			<a href="<?php echo Url::forgot(); ?>">Quên mật khẩu ư ?</a>
			<input type="submit" name="login" id="login-btn" value="Đăng nhập"/>
		</p>
	</form>
</div>
<?php
if (isset($_GET['msg'])) {
	$msg = intval($_GET['msg']);
	?><div id="login-msg"><?php echo Auth::message($msg); ?></div><?php
}
if (isset($_GET['lg']) && $_GET['lg'] == 'true') {
	?><div id="logout-msg"><?php echo Auth::message(trim($_GET['lg'])); ?></div><?php
}
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
