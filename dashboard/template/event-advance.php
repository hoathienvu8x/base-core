<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::action('event'); ?>" method="post">
		<p>
			Event<br />
			<input type="text" name="event_name" value="" placeholder="Event name" />
		</p>
		<?php if (Auth::is_supper()) : ?>
                <p>
                        Event alias<br />
                        <input type="text" name="event_alias" value="" placeholder="Event alias" />
                </p>
                <?php endif; ?>
		<p>
			Description<br />
			<textarea name="event_desc" placeholder="Description" />
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
