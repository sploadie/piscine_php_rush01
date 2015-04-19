<?php
require_once('classIncludes.php');

@session_start();

function shipClicked() {
	if ( isset( $_GET['username'] ) && isset( $_GET['shipId'] ) ) {
		if ( $_SESSION['game']->getCurrentPlayer() === $_GET['username'] ) {
			$_SESSION['game']->setSelectedShipId($_GET['shipId']);
			error_log('selected ship: ' . $_GET['shipId']);
		}
		else
			error_log('wrong player selected a ship: ' . $_GET['username'] . ' (current = ' . $_SESSION['game']->getCurrentPlayer() . ")");
	} else {
		error_log('shipClicked action but incorrect other parameters');
	}
}

function uiButtonClicked() {
	if ( isset( $_GET['button'] ) ) {
		switch ( $_GET['button'] ) {
			case 'nextPlayer':
				$_SESSION['game']->nextPlayer();
				break;
			default:
				error_log('button click undefined: ' . $_GET['button']);
				break;
		}
	} else {
		error_log('button clicked but incorrect other parameters');
	}
}

if ($_GET['action'] === 'shipClicked') {
	# they clicked on a ship
	shipClicked();
} else if ($_GET['action'] === 'buttonClicked') {
	# they clicked a button on the bottom right
	uiButtonClicked();
} else {
	error_log('action.php called with incorrect parameters');
}

?>