<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
if (isset($_GET['msg'])) {
        $msg = User::message(intval($_GET['msg']));
        $msg_status = in_array(intval($_GET['msg']),array(User::ERROR_SAVED, User::ERROR_UPDATED, User::ERROR_DELETED, User::ERROR_CHANGED)) ? ' success' : '';
        echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}
?>
<table>
<thead>
<tr>
	<th><input type="checkbox" name="chkAll" value="1" /></th>
	<th><a href="<?php echo Url::admin(array('orderby' => 'name', 'order' => ($order == 'asc' ? 'desc' : 'asc'))); ?>">NickName</a></th>
	<th><a href="<?php echo Url::admin(array('orderby' => 'username', 'order' => ($order == 'asc' ? 'desc' : 'asc'))); ?>">Username</a></th>
	<th>Email</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php foreach($users as $row) : ?>
<tr>
	<td><input type="checkbox" name="checked[]" value="<?php echo $row['id']; ?>" /></td>
	<td><strong><?php echo $row['nickname']; ?></strong></td>
	<td><?php echo $row['username'];?></td>
	<td><?php echo $row['email']; ?></td>
	<td>
		<a href="<?php echo Url::admin(array('edit' => $row['id'])); ?>" class="edit">Edit</a>
		<a href="<?php echo Url::admin(array('del' => $row['id'])); ?>" class="remove">Delete</a>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php
require_once Xtemplate::get( 'footer' );
exit;
?>
