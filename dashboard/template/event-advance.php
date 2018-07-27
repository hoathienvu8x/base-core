<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::event(); ?>" method="post">
		<p>
			Event<br />
			<input type="text" name="event_name" value="<?php echo isset($event['event_name']) ? $event['event_name'] : ''; ?>" placeholder="Event name" />
		</p>
		<?php if (Auth::is_supper()) : ?>
                <p>
                        Event alias<br />
                        <input type="text" name="event_alias" value="<?php echo isset($event['event_alias']) ? $event['event_alias'] : ''; ?>" placeholder="Event alias" />
                </p>
                <?php endif; ?>
		<p>
			Description<br />
			<textarea name="event_desc" placeholder="Description"><?php echo isset($event['event_desc']) ? $event['event_desc'] : ''; ?></textarea>
		</p>
		<p class="bottom">
			<?php if (isset($event['id']) && intval($event['id'])) : ?>
			<input type="hidden" name="id" value="<?php echo $event['id']; ?>" />
			<?php endif; ?>
			<input type="submit" name="saved" value="Lưu thông tin" />
		</p>
	</form>
</div>
<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
