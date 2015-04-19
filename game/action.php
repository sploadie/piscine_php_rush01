<?php
require_once('classIncludes.php');

@session_start();
error_log("action.php is called!");

if ($_GET['action'] === 'shipClicked') {
	if ( isset( $_GET['username'] ) && isset( $_GET['shipId'] ) ) {
		if ( $_SESSION['game']->getCurrentPlayer() === $_GET['username'] ) {
			$_SESSION['selectedShipId'] = $_GET['shipId'];
			error_log('selected ship id: ' . $_SESSION['selectedShipId']);
		}
		else
			error_log('wrong player selected a ship: ' . $_GET['username'] . ' (current = ' . $_SESSION['game']->getCurrentPlayer() . ")");
	} else {
		error_log('shipClicked action but incorrect other parameters');
	}
} else if ($_GET['action'] === 'buttonClicked') {
	if ( isset( $_GET['button'] ) ) {
		error_log('button clicked: ' . $_GET['button']);
	} else {
		error_log('button clicked but incorrect other parameters');
	}
} else {
	error_log('action.php called with incorrect parameters');
}

?>