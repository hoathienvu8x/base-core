<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
?>
<div id="aside">
	<div id="aside-inner">
		<div id="logo">
			<a href="<?php echo SITE_URL;?>"><img src="<?php echo SITE_URL;?>app-logo-icon.png" /></a>
		</div>
		<ul>
			<li>
				<h3>Hóa đơn</h3>
				<ul>
					<li><a href="<?php echo Url::action('report'); ?>">Tất cả</a></li>
					<li><a href="<?php echo Url::action('inout'); ?>">Thu/Chi</a></li>
					<li><a href="<?php echo Url::action('report',array('mode' => 'add')); ?>">Thêm mới</a></li>
				</ul>
			</li>
			<li>
				<h3>Khách hàng</h3>
				<ul>
					<li><a href="<?php echo Url::action('customer'); ?>">Tất cả</a></li>
					<li><a href="<?php echo Url::action('guest'); ?>">Vãng lai</a></li>
					<li><a href="<?php echo Url::action('customer',array('mode' => 'add')); ?>">Thêm mới</a></li>
				</ul>
			</li>
			<li>
				<h3>Tài sản</h3>
				<ul>
					<li><a href="<?php echo Url::action('access'); ?>">Tất cả</a></li>
					<li><a href="<?php echo Url::action('access',array('mode' => 'add')); ?>">Thêm mới</a></li>
				</ul>
			</li>
			<li>
				<h3>Dịch vụ</h3>
				<ul>
					<li><a href="<?php echo Url::action('service'); ?>">Tất cả</a></li>
					<li><a href="<?php echo Url::action('service',array('mode' => 'add')); ?>">Thêm mới</a></li>
				</ul>
			</li>
			<li>
				<h3>Tùy chọn</h3>
				<ul>
					<li><a href="<?php echo Url::action('option'); ?>">Tất cả</a></li>
					<li><a href="<?php echo Url::action('option',array('mode' => 'add')); ?>">Thêm mới</a></li>
				</ul>
			</li>
			<li>
				<h3>Thành viên</h3>
				<ul>
					<li><a href="<?php echo Url::action('admin'); ?>">Tất cả</a></li>
					<li><a href="<?php echo Url::action('admin',array('mode' => 'add')); ?>">Thêm mới</a></li>
				</ul>
			</li>
			<li>
				<h3>Tài khoản</h3>
				<ul>
					<li><a href="<?php echo Url::action('profile'); ?>">Sửa thông tin</a></li>
					<li><a href="<?php echo Url::action('logout'); ?>">Thoát ra</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
