<?php require_once 'header.php'; ?>
<div class="data-form">
	<table class="form-elements">
		<tr>
			<td>Thông tin liên hệ</td>
			<td>
				<p>
					<input type="text" name="nickname" value="" placeholder="Họ và tên" />
				</p>
				<p>
					<input type="email" name="email" value="" placeholder="Email" />
				</p>
			</td>
		</tr>
		<tr>
			<td>Thông tin đăng nhập</td>
			<td>
				<p>
					<input type="text" name="username" value="" placeholder="Tên đăng nhập" />
				</p>
				<p>
					<input type="password" name="password" value="" placeholder="Mật khẩu" />
				</p>
			</td>
		</tr>
		<tr>
			<td>Hình ảnh đại diện</td>
			<td>
				<p>
				
				</p>
				<p>
					<input type="file" name="photo" />
				</p>
			</td>
		</tr>
	</table>
</div>
<?php require_once 'footer.php'; ?>
