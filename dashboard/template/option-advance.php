<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
if (isset($_GET['msg'])) {
	$code = intval($_GET['msg']);
	$msg = Option::message(intval($code);
	$msg_status = in_array($code, array(Option::ERROR_SAVED, Option::ERROR_UPDATED, Option::ERROR_DELETED, Option::ERROR_LOADED)) ? ' success' : '';
	echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}
?>
<div class="form-elements">
	<form action="<?php echo Url::option($args); ?>" method="post">
		<p>
			Option name<br />
			<input type="text" name="option_name"<?php echo isset($args['option_name']) ? ' readonly="readonly"' : ''; ?> value="<?php echo isset($option['option_name']) ? $option['option_name'] : ''; ?>" placeholder="Role name" />
		</p>
		<p>
			Option value<br />
			<textarea name="option_value" placeholder="Option value"><?php echo isset($option['option_value']) ? $option['option_value'] : ''; ?></textarea>
		</p>
		<p>
			Description<br />
			<textarea name="option_desc" placeholder="Description"><?php echo isset($option['option_desc']) ? $option['option_desc'] : ''; ?></textarea>
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
