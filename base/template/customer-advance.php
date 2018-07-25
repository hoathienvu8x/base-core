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
			Họ &amp; tên<br />
			<input type="text" name="fullname" value="" placeholder="Họ &amp; tên" />
		</p>
		<p>
			CMND<br />
			<input type="number" name="cmnd" value="" placeholder="CMND" />
		</p>
		<p>
			Điện thoại<br />
			<input type="tel" name="telephone" value="" placeholder="Số điện thoại" />
		</p>
		<p>
			Địa chỉ<br />
			<textarea name="address" placeholder="Địa chỉ khách hàng"></textarea>
		</p>
		<p>
			Loại khách hàng<br />
			<select name="is_guest">
				<option value="y">Khách vãng lai</option>
				<option value="n">Khách hàng</option>
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