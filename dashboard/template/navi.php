<?php
if (!defined('INAPP')) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
?>
<div id="aside">
	<div id="aside-inner">
		<div id="logo">
			<a href="<?php echo SITE_URL;?>"><img src="<?php echo SITE_URL;?>app-logo-icon.png" /></a>
		</div>
		<ul>
			<li>
				<h3>Events</h3>
				<ul>
					<li><a href="<?php echo Url::event(); ?>">All</a></li>
					<li><a href="<?php echo Url::grant(); ?>">Grant</a></li>
					<li><a href="<?php echo Url::event(array('mode' => 'add')); ?>">New Event</a></li>
				</ul>
			</li>
			<li>
				<h3>Roles</h3>
				<ul>
					<li><a href="<?php echo Url::role(); ?>">All</a></li>
					<li><a href="<?php echo Url::role(array('mode' => 'add')); ?>">New Role</a></li>
				</ul>
			</li>
			<li>
				<h3>Options</h3>
				<ul>
					<li><a href="<?php echo Url::option(); ?>">All</a></li>
					<li><a href="<?php echo Url::option(array('mode' => 'add')); ?>">New Option</a></li>
				</ul>
			</li>
			<li>
				<h3>Users</h3>
				<ul>
					<li><a href="<?php echo Url::admin(); ?>">All</a></li>
					<li><a href="<?php echo Url::admin(array('mode' => 'add')); ?>">New User</a></li>
				</ul>
			</li>
			<li>
				<h3>Account</h3>
				<ul>
					<li><a href="<?php echo Url::profile(); ?>">Profile</a></li>
					<li><a href="<?php echo Url::logout(); ?>">Logout</a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
