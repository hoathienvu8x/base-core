<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
?>

<?php foreach($options as $row) : ?>

<?php endforeach; ?>

<?php
require_once Xtemplate::get( 'footer' );
exit;
?>
