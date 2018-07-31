<?php require_once 'header.php'; ?>
<table class="list-table">
	<thead>
		<tr>
			<th><input type="checkbox" name="chkAll" value="1" /></th>
			<th>Nickname</th>
			<th>Username</th>
			<th>Role</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><input type="checkbox" name="checked[]" value="1" /></td>
			<td><a href="<?php echo Url::admin(array('edit' => 1)); ?>">Trần Ngọc Nhât</a><br /><em>hoathienvu8x@gmail.com</em></td>
			<td>mrnhat</td>
			<td>Quản trị viên<br />Active</td>
			<td>
				<a href="<?php echo Url::admin(array('edit' => 1)); ?>">Edit</a>
				<a href="<?php echo Url::admin(array('del' => 1)); ?>">Delete</a>
			</td>
		</tr>
	</tbody>
</table>
<?php require_once 'footer.php'; ?>
