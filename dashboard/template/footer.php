<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
?>
<div class="admin-footer<?php echo is_user_logged_in() ? ' is-logged' : '';?>">&copy; 2018<?php echo date('Y') > 2018 ? '-'.date('y') : '';?> AnhChangThoDe22145 All Rights Reserved</div>
<?php if( is_user_logged_in() ) : ?>
</div>
<script src="<?php echo TEMPLATEURL;?>js/script.js"></script>
<?php endif; ?>
</body></html>
