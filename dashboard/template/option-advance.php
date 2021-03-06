<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
if (isset($_GET['msg'])) {
	$code = intval($_GET['msg']);
	$msg = Option::message($code);
	$msg_status = in_array($code, array(Option::ERROR_SAVED, Option::ERROR_UPDATED, Option::ERROR_DELETED, Option::ERROR_LOADED)) ? ' success' : '';
	echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}
if (empty($args)) {
	$args['mode'] = 'add';
}
?>
<div class="form-elements">
	<form action="<?php echo Url::option($args); ?>" method="post">
		<?php if (!isset($args['edit']) && Auth::is_super()) : ?>
		<p>
			Option name<br />
			<input type="text" name="option_name"<?php echo isset($args['option_name']) ? ' readonly="readonly"' : ''; ?> value="<?php echo isset($option['option_name']) ? $option['option_name'] : ''; ?>" placeholder="Role name" />
		</p>
		<?php endif; ?>
		<p>
			Option value<br />
			<?php
			$opt_name = isset($option['option_name']) ? $option['option_name'] : '';
			$opt_value =isset($option['option_value']) ? $option['option_value'] : '';
			?>
			<?php echo Option::option_field($opt_name, $opt_value); ?>
		</p>
		<p>
			Description<br />
			<textarea name="option_desc" placeholder="Description"><?php echo isset($option['option_desc']) ? $option['option_desc'] : ''; ?></textarea>
		</p>
		<p class="bottom">
			<?php if (isset($args['edit']) && !empty($args['edit'])) : ?>
			<input type="hidden" name="edit" value="<?php echo $args['edit']; ?>" />
			<?php endif; ?>
			<input type="submit" name="saved" value="Lưu thông tin" />
		</p>
	</form>
</div>
<?php
require_once Xtemplate::get( 'footer' );
exit;
?>
