<?php require_once 'header.php'; ?>

<?php
if (!is_user_loggin()) :
	require_once 'login.php';
else:
	$action = isset($_GET['action']) ? trim($_GET['action']) : '';
	if (!empty($action) && file_exists($action.'.php')) {
		require_once $action.'.php';
	}
endif;
?>

<?php require_once 'footer.php'; ?>
