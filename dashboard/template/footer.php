<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
?>
<div class="admin-footer<?php echo Auth::isLogged() ? ' is-logged' : '';?>">&copy; 2018<?php echo date('Y') > 2018 ? '-'.date('y') : '';?> AnhChangThoDe22145 All Rights Reserved</div>
<?php if( Auth::isLogged() ) : ?>
</div>
<script src="<?php echo TEMPLATEURL;?>js/script.js"></script>
<?php endif; ?>
</body></html>
