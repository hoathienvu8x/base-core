<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
?>
<table>
<thead>
	<tr>
		<th><input type="checkbox" name="chkAll" value="1" /></th>
		<th>Option</th>
		<th>Value</th>
		<th>&nbsp;</th>
	</tr>
</thead>
<tbody>
<?php foreach($options as $row) : ?>
<tr>
	<?php $is_system = Option::is_system($row['option_name']); ?>
	<td><input type="checkbox" name="checked[]"<?php echo $is_system ? ' disabled="disabled"' : ''; ?> value="<?php echo $row['option_name'];?>" /></td>
	<td><?php echo $row['option_desc']; ?></td>
	<td><?php echo Option::option_value($row); ?></td>
	<td>
		<a href="<?php echo Url::option(array('edit' => $row['option_name'])); ?>">Edit</a>
		<?php if ($is_system == false) : ?>
		<a href="<?php echo Url::option(array('del' => $row['option_name'])); ?>">Delete</a>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php
require_once Xtemplate::get( 'footer' );
exit;
?>
