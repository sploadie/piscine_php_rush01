<?php
@session_start();
if (file_exists("../private/lobby"))
	$game_list = unserialize(file_get_contents("../private/lobby"));
else
	$game_list = array();
if (!isset($_POST['action'])) { echo "Action not set."; exit; }
switch ($_POST['action']) {
	case 'Leave Game':
		$host = $_SESSION['waiting_for_host'];
		if ($host === $_SESSION['current_user'])
			unset($game_list[$host]);
		else
			unset($game_list[$host]['players'][array_search($_SESSION['current_user'], $game_list[$host]['players'])]);
		unset($_SESSION['waiting_for_host']);
		break;
	case 'Start Game':
		echo "Not a thing yet."; exit;
		break;
	case 'Create Game':
		if (!isset($_POST['name']) || $_POST['name'] === "") { echo "Name not set."; exit; }
		$_SESSION['waiting_for_host'] = $_SESSION['current_user'];
		$game_list[$_SESSION['current_user']] = array('players' => array($_SESSION['current_user']), 'name' => $_POST['name']);
		break;
	case 'Join Game':
		if (!isset($_POST['host']) || !isset($game_list[$_POST['host']])) { echo "Game does not exist."; exit; }
		$game_list[$_POST['host']]['players'][] = $_SESSION['current_user'];
		$_SESSION['waiting_for_host'] = $_POST['host'];
		break;
	default:
		echo "Action [" . $_POST['action'] . "] unknown.";
		exit;
}
file_put_contents("../private/lobby", serialize($game_list));
?>