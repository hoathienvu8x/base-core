<?php require_once 'header.php'; ?>
<div class="data-table">
	<table class="list-table">
		<thead>
			<tr>
				<th class="checkbox"><input type="checkbox" name="chkAll" value="1" /></th>
				<th>Nickname</th>
				<th>Username</th>
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
<?php require_once 'footer.php'; ?>
