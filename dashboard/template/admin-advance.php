<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
if (isset($_GET['msg'])) {
	$msg = User::message(intval($_GET['msg']));
	$msg_status = in_array(intval($_GET['msg']),array(User::ERROR_SAVED, User::ERROR_UPDATED, User::ERROR_DELETED, User::ERROR_CHANGED)) ? ' success' : '';
	echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}
?>
<div class="form-elements">
	<form action="<?php echo Url::admin($args); ?>" method="post" enctype="multipart/form-data">
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
			<input type="text" name="username" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>"<?php echo isset($args['edit']) ? ' readonly="readonly"' : ''; ?> placeholder="User name" />
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
			<?php $checked = isset($user['state']) ? $user['state'] : 'register'; ?>
			<select name="state">
				<option value="register"<?php echo $checked == 'register' ? ' selected="selected"' : ''; ?>>Register</option>
				<option value="checked"<?php echo $checked == 'checked' ? ' selected="selected"' : ''; ?>>Checked</option>
				<option value="blocked"<?php echo $checked == 'blocked' ? ' selected="selected"' : ''; ?>>Blocked</option>
			</select>
		</p>
		<p>
			Photo<br />
			<?php if (isset($user['photo']) && !empty($user['photo'])) : ?>
			<span id="user-photo"><img src="<?php echo UPLOADURL; ?>avatars/<?php echo $user['photo']; ?>" /></span><br />
			<?php endif; ?>
			<input type="file" name="photo" placeholder="Photo" />
		</p>
		<p class="bottom">
			<?php if (isset($user['id']) && intval($user['id']) > 0) : ?>
			<input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
			<?php endif; ?>
			<input type="submit" name="saved" value="Lưu thông tin" />
		</p>
	</form>
</div>
<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
