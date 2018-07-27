<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::admin(); ?>" method="post">
		<p>
			Nickname<br />
			<input type="text" name="nickname" value="<?php echo isset($user['nickname']) ? $user['nickname'] : ''; ?>" placeholder="Nickname" />
		</p>
		<p>
			Email<br />
			<input type="tel" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" placeholder="Email" />
		</p>
		<p>
			Username<br />
			<input type="text" name="username" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" placeholder="User name" readonly="readonly" />
		</p>
		<p>
			Password<br />
			<input type="password" name="password" placeholder="Password" />
		</p>
		<p>
			Role<br />
			<?php $checked = isset($user['role']) ? $user['role'] : 'member'; ?>
			<select name="role">
				<option value="member"<?php echo $checked == 'member' ? ' selected="selected"' : ''; ?>>Member</option>
				<option value="account"<?php echo $checked == 'account' ? ' selected="selected"' : ''; ?>>Account</option>
				<option value="admin"<?php echo $checked == 'admin' ? ' selected="selected"' : ''; ?>>Admin</option>
				<option value="administrator"<?php echo $checked == 'administrator' ? ' selected="selected"' : ''; ?>>Supper Admin</option>
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
