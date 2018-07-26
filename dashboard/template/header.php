<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="theme-color" content="#3b5998">
	<link rel="shortcut icon" href="<?php echo SITE_URL;?>favicon.ico" />
	<link rel="apple-touch-icon image_src" href="<?php echo SITE_URL;?>app-logo-icon.png" />
	<title><?php echo isset($pageTitle) ? $pageTitle : 'ACP :: Dashboard'; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0, minimal-ui" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo TEMPLATEURL;?>css/style.css" />
</head>
<body<?php echo Auth::isLogged() ? ' class="logged'.(isset($_GET['action']) && !empty($_GET['action']) ? ' '.strtolower($_GET['action']) : '').'"' : '';?>>
<?php
if (Auth::isLogged()){
	?><div id="nav-wrap" class="nav-wrap"></div><?php
	require_once TEMPLATEPATH . 'navi.php';
	?>
	<div id="container">
	<div id="header">
		<button id="open-nav" class="open-nav">&#9776;</button>
		<form action="" method="get" id="top-search">
			<input type="text" name="keyword" value="" placeholder="Put the keywords" />
			<button type="submit">Search</button>
		</form>
		<h2><?php echo isset($pageTitle) ? trim(str_replace('ACP :: ','',$pageTitle)) : 'Dashboard'; ?></h2>
	</div>
	<?php
}
?>
