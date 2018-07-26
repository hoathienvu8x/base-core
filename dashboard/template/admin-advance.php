<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::action('admin'); ?>" method="post">
		<p>
			Nickname<br />
			<input type="text" name="nickname" value="" placeholder="Nickname" />
		</p>
		<p>
			Email<br />
			<input type="tel" name="email" value="" placeholder="Email" />
		</p>
		<p>
			Username<br />
			<input type="text" name="username" placeholder="User name"></textarea>
		</p>
		<p>
			Password<br />
			<input type="password" name="password" placeholder="Password" />
		</p>
		<p>
			Role<br />
			<select name="role">
				<option value="member">Member</option>
				<option value="account">Account</option>
				<option value="admin">Admin</option>
				<option value="administrator">Supper Admin</option>
			</select>
		</p>
		<p>
			State<br />
			<select name="state">
				<option value="register">Register</option>
				<option value="checked">Checked</option>
				<option value="blocked">Blocked</option>
			</select>
		</p>
		<p>
			Photo<br />
			<input type="file" name="photo" placeholder="Photo" />
		</p>
		<p class="bottom">
			<input type="submit" name="saved" value="Lưu thông tin" />
		</p>
	</form>
</div>
<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
