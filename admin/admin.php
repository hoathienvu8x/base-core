<?php require_once 'header.php'; ?>
<div class="navTools pageNavi">
	<p>
                <span class="total">1,123 mục</span>
                <a class="first-page" href="#">Đầu tiên</a>
                <span class="current"><input type="number" value="2" class="navi-page" name="page" />/120</span>
                <a class="next-page" href="#">Trang sau</a>
                <a class="last-page" href="#">Trang cuối</a>
        </p>
</div>
<div class="data-table">
	<table class="list-table">
		<thead>
			<tr>
				<th class="checkbox"><input type="checkbox" name="chkAll" value="1" /></th>
				<th><a href="<?php echo Url::admin(array('orderby' => 'name'));?>">Nickname</a></th>
				<th><a href="<?php echo Url::admin(array('orderby' => 'username')); ?>">Username</a></th>
				<th>Role</th>
				<th class="tools">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="checkbox"><input type="checkbox" name="checked[]" value="1" /></td>
				<td><a href="<?php echo Url::admin(array('edit' => 1)); ?>">Trần Ngọc Nhât</a><br /><em>hoathienvu8x@gmail.com</em></td>
				<td>mrnhat</td>
				<td>Quản trị viên<br />Active</td>
				<td class="tools">
					<a href="<?php echo Url::admin(array('edit' => 1)); ?>" class="tool edit">Edit</a>
					<a href="<?php echo Url::admin(array('del' => 1)); ?>" class="tool remove">Delete</a>
				</td>
			</tr>
		</tbody>
	</table>
</div> <!-- End of data table -->
<div class="navTools pageNavi">
	<p>
		<span class="total">1,123 mục</span>
		<a class="first-page" href="#">Đầu tiên</a>
		<span class="current"><input type="number" value="2" class="navi-page" name="page" />/120</span>
		<a class="next-page" href="#">Trang sau</a>
		<a class="last-page" href="#">Trang cuối</a>
	</p>
</div>
<?php require_once 'footer.php'; ?>
