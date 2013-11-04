<?php
	// logout.php
	// Log a user out of the system utilizing the logout
	// method in UserTools.
	//
	require_once 'includes/global.inc.php';

	$userTools = new UserTools();
	$userTools->logout();

	header("Location: login.php");

?>