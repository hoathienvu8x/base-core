<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<div class="form-elements">
	<form action="<?php echo Url::action('customer'); ?>" method="post">
		<p>
			Tài sản<br />
			<input type="text" name="taisan" value="" placeholder="Tài sản" />
		</p>
		<p>
			Giá trị thực<br />
			<input type="text" name="realVal" value="" placeholder="Giá trị thực" />
		</p>
		<p>
			Giá trị trên giấy tờ<br />
			<input type="text" name="paperVal" value="" placeholder="Giá trị trên giấy tờ" />
		</p>
		<p>
			Loại tài sản<br />
			<select name="accessType">
				<option value="">[&darr;] Chọn môt</option>
			</select>
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