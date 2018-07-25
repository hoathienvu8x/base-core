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
			Tên dịch vụ<br />
			<input type="text" name="service_name" value="" placeholder="Tên dịch vụ" />
		</p>
		<p>
			Mô tả dịch vụ<br />
			<textarea name="description" placeholder="Mô tả dịch vụ"></textarea>
		</p>
		<p>
			Loại dịch vụ<br />
			<select name="service_type">
				<option value="">[&darr;] Chọn một</option>
			</select>
		</p>
		<p>
			Phí<br />
			<input type="number" name="service_fee" value="" placeholder="Phí dịch vụ" />
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