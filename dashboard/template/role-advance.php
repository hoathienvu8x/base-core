<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::action('role'); ?>" method="post">
		<p>
			Role name<br />
			<input type="text" name="role_name" value="" placeholder="Role name" />
		</p>
		<?php if (Auth::is_supper()) : ?>
		<p>
			Role alias<br />
			<input type="text" name="role_alias" value="" placeholder="Role alias" />
		</p>
		<?php endif; ?>
		<p>
			Description<br />
			<textarea name="role_desc" placeholder="Description" />
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
