<?php
require_once('classIncludes.php');

require_once('fileCommunication.php');

@session_start();

function shipClicked() {
	if ( isset( $_GET['username'] ) && isset( $_GET['shipId'] ) ) {
		if ( $_SESSION['game']->getCurrentPlayer() === $_GET['username'] ) {
			$_SESSION['game']->setSelectedShipId($_GET['shipId']);
			error_log('selected ship: ' . $_GET['shipId']);
		}
		else {
			$_SESSION['game']->setSelectedShipId(-1);
			error_log('wrong player selected a ship: ' . $_GET['username']
						. ' (current = ' . $_SESSION['game']->getCurrentPlayer() . ")");
		}
	} else {
		error_log('shipClicked action but incorrect other parameters');
	}
}

function orderPhase($actionString) {
	$selectedShip = $_SESSION['game']->getSelectedShip($_SESSION['current_user']);
	if ($selectedShip['power'] > 0) {
		switch ( $actionString ) {
			case 'increaseHealth':
				if ($selectedShip['power'] > 2) {
					$selectedShip->changeHealth(1);
					$selectedShip->changePower(-3);
				}
				break;
			case 'increaseShield':
				$selectedShip->changeShield(1);
				$selectedShip->changePower(-1);
				break;
			case 'increaseSpeed':
				$selectedShip->changeSpeed(1);
				$selectedShip->changePower(-1);
				break;
			case 'increaseAmmo':
				$selectedShip->changeAmmo(1);
				$selectedShip->changePower(-1);
				break;
			default:
				error_log('undefined action for phase: ' . $actionString);
				break;
		}
	}
}

function movingPhase($actionString) {
	switch ( $actionString ) {
		case 'moveUp':
			$_SESSION['game']->moveShip($_SESSION['current_user'], 0, -1);
			break;
		case 'moveDown':
			$_SESSION['game']->moveShip($_SESSION['current_user'], 0, 1);
			break;
		case 'moveRight':
			$_SESSION['game']->moveShip($_SESSION['current_user'], 1, 0);
			break;
		case 'moveLeft':
			$_SESSION['game']->moveShip($_SESSION['current_user'], -1, 0);
			break;
		default:
			error_log('undefined action for phase: ' . $actionString);
			break;
	}
}

function firingPhase($actionString) {
	switch ( $actionString ) {
		case 'shootUp':
			$_SESSION['game']->shootFromShip($_SESSION['current_user'], 0, -1);
			break;
		case 'shootDown':
			$_SESSION['game']->shootFromShip($_SESSION['current_user'], 0, 1);
			break;
		case 'shootRight':
			$_SESSION['game']->shootFromShip($_SESSION['current_user'], 1, 0);
			break;
		case 'shootLeft':
			$_SESSION['game']->shootFromShip($_SESSION['current_user'], -1, 0);
			break;
		default:
			error_log('undefined action for phase: ' . $actionString);
			break;
	}
}

function uiButtonClicked() {
	if ( isset( $_GET['button'] ) ) {
		$actionString = $_GET['button'];

		if ( $actionString === 'nextPlayer' ) {
			$_SESSION['game']->nextPlayer();
		} else if ( $actionString === 'nextPhase' ) {
			$_SESSION['game']->nextPhase();
		} else if ( $actionString === 'refresh' ) {
			setSessionToUnserialized();
			error_log('refresh by ' . $_SESSION['current_user']);/*
			error_log('TAKE OUT ');
			if ($_SESSION['current_user'] === 'tfleming')
				$_SESSION['current_user'] = 'sploadie';
			else
				$_SESSION['current_user'] = 'tfleming';*/
			return;
		}
		else 
		{
			switch ( $_SESSION['game']->getPhase() ) {
				case 0:
					orderPhase($actionString);
					break;
				case 1:
					movingPhase($actionString);
					break;
				case 2:
					firingPhase($actionString);
					break;
				default:
					trigger_error ( "Undefined phase.", E_USER_ERROR );
					break;
			}
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