<?php
require_once 'functions.php';
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900" />
	<link rel="stylesheet" type="text/css"  href="css/style.css" />
	<title>Administrator Control Panel</title>
	<link rel="stylesheet" type="text/css" href="css/responsive.css" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body<?php body_class(); ?>>
<?php if (is_user_loggin()) : ?>
<?php require_once 'navi.php'; ?>
<div id="content-wrapper">
	<div id="header-wrap">
		<div id="header-inner"></div>
	</div>
	<div id="content">
<?php endif; ?>
