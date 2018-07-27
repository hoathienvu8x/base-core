<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::role(); ?>" method="post">
		<p>
			Role name<br />
			<input type="text" name="role_name" value="<?php echo isset($role['role_name']) ? $role['role_name'] : ''; ?>" placeholder="Role name" />
		</p>
		<?php if (Auth::is_supper()) : ?>
		<p>
			Role alias<br />
			<input type="text" name="role_alias" value="<?php echo isset($role['role_alias']) ? $role['role_alias'] : ''; ?>" placeholder="Role alias" />
		</p>
		<?php endif; ?>
		<p>
			Description<br />
			<textarea name="role_desc" placeholder="Description"><?php echo isset($role['role_desc']) ? $role['role_desc'] : ''; ?></textarea>
		</p>
		<p class="bottom">
			<?php if (isset($role['id']) && intval($role['id']) > 0) : ?>
			<input type="hidden" name="id" value="<?php echo $role['id']; ?>" />
			<?php endif; ?>
			<input type="submit" name="saved" value="Lưu thông tin" />
		</p>
	</form>
</div>
<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
