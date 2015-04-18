<?php
require_once('classIncludes.php');

@session_start();
error_log("action.php is called!");

if (isset($_GET['username']) && isset($_GET['shipId'])) {
	if ($_SESSION['game']->getCurrentPlayer() === $_GET['username']) {
		$_SESSION['selectedShipId'] = $_GET['shipId'];
		var_dump('selected ship id: ' . $_SESSION['selectedShipId']);
	}
	else
		var_dump('wrong player selected a ship');
} else if (isset($_GET))


?>