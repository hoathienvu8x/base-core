<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>
<form action="" method="post" id="form-actions">
	<div class="aside">
		<div class="post-box">
			<h3 class="expand">Thông tin khách hàng</h3>
			<div class="post-inner">
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
			</div>
		</div>
		<div class="post-box post-optional">
			<h3>Thông tin doanh nghiệp</h3>
			<div class="post-inner">
				<p>
					Tên doanh nghiệp<br />
					<input type="text" name="company" value="" placeholder="Tên doanh nghiệp" />
				</p>
				<p>
					Điện thoại<br />
					<input type="tel" name="phonebox" value="" placeholder="Số điện thoại doanh nghiệp" />
				</p>
				<p>
					Địa chỉ<br />
					<textarea name="offical" placeholder="Địa chỉ doanh nghiệp"></textarea>
				</p>
			</div>
		</div>
	</div>
	<div class="main">
		<div class="post-box">
			<h3 class="expand">Dịch vụ</h3>
			<div class="post-inner">
				<div class="service-advance">
					<select id="service">
						<option value="">[&darr;] Chọn một</option>
						<option value="">Công chứng</option>
						<option value="">Làm sổ</option>
					</select>
					<input type="text" id="keyword" value="" placeholder="Nhập vào từ khoá..." />
					<input type="submit" value="Tìm kiếm" />
				</div>
			</div>
		</div>
	</div>
</form>
<script>
    (function($) {
        $(document).on('click', '.post-box h3', function(e) {
            $(this).next('.post-inner').toggle();
            if ($(this).hasClass('expand') == false) {
                $(this).addClass('expand');
            } else {
                $(this).removeClass('expand');
            }
            return false;
        });
        $(document).on('click', '.service-advance input[type="sbumit"]', function(e) {
        	e.preventDefault();	
        	
        	return false;
        });
    })(jQuery);
</script>
<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
