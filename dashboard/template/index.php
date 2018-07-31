<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
?>

<?php
require_once Xtemplate::get( 'footer' );
exit;
?>
