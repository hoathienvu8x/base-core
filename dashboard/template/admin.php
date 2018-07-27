<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once TEMPLATEPATH . 'header.php';
?>

<?php foreach($users as $row) : ?>

<?php endforeach; ?>

<?php
require_once TEMPLATEPATH . 'footer.php';
exit;
?>
