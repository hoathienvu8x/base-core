<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
?>
<div class="admin-footer<?php echo Auth::isLogged() ? ' is-logged' : '';?>">&copy; 2018<?php echo date('Y') > 2018 ? '-'.date('y') : '';?> AnhChangThoDe22145 All Rights Reserved</div>
<?php if( Auth::isLogged() ) : ?>
</div>
<script>
(function($) {
	$('#nav-wrap').on('touchstart', function(e) {
		$('body').removeClass('fixed');
		$('#aside').removeClass('show');
		$('#nav-wrap').removeClass('nav-wrap-show');
	});
	$('#nav-wrap').on('touchmove', function(e) {
		e.preventDefault();		
	});
	$(document).on('click', '#open-nav', function(e) {
		$('#nav-wrap').addClass('nav-wrap-show');
		$('body').addClass('fixed');
		$('#aside').addClass('show');
	});
	$('input[name="keyword"]').on('focus', function() {
		$(this).addClass('expandW');
	}).on("blur", function() {
		$(this).removeClass('expandW');
	});
})(jQuery);
</script>
<?php endif; ?>
</body></html>