<?php require_once 'header.php'; ?>
<form action="" method="post">
	<div id="login-form">
		<div id="login-logo">
			<img src="images/logo.svg" />
		</div>
		<p>
			<input type="text" name="username" value="" placeholder="Username" autocapitalize="off" autocomplete="off"  />
		</p>
		<p>
			<input type="password" name="password" placeholder="Password" autocapitalize="off" autocomplete="off"  />
		</p>
		<p>
			<label for="remember_me"><input type="checkbox" name="remember" value="y" id="remember_me" /> Remember me ?</label>
		</p>
		<p class="bottom">
			<a href="<?php echo Url::forgot(); ?>">Forgot password ?</a>
			<input type="submit" name="logon" value="Login" />
		</div>
	</div>
	<div class="message error">
		<p>Vui lòng đăng nhập !</p>
	</div>
</form>
<?php require_once 'footer.php'; ?>
