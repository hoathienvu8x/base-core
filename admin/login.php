<?php require_once 'header.php'; ?>
<form action="" method="post">
	<div id="login-form">
		<p>
			Username:<br />
			<input type="text" name="username" value="" placeholder="" />
		</p>
		<p>
			Password<br />
			<input type="password" name="password" placeholder="" />
		</p>
		<p class="bottom">
			<a href="<?php echo Url::forgot(); ?>">Forgot password ?</a>
			<input type="submit" name="logon" value="Login" />
		</div>
	</div>
</form>
<?php require_once 'footer.php'; ?>
