<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}

require_once Xtemplate::get( 'header' );
if (isset($_GET['msg'])) {
        $msg = Event::message(intval($_GET['msg']));
        $msg_status = in_array(intval($_GET['msg']),array(Event::ERROR_SAVED, Event::ERROR_UPDATED, Event::ERROR_DELETED)) ? ' success' : '';
        echo '<div class="error_notify'.$msg_status.'">'.$msg.'</div>';
}

?>

<?php foreach($events as $row) : ?>

<?php endforeach; ?>

<?php
require_once Xtemplate::get( 'footer' );
exit;
?>
