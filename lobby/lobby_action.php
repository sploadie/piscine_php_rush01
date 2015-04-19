<?php
require_once('../php_assets/exit_to.php');
@session_start();
if (file_exists("../private/lobby"))
	$game_list = unserialize(file_get_contents("../private/lobby"));
else
	$game_list = array();
if (!isset($_POST['action'])) { echo "Action not set."; exit; }
switch ($_POST['action']) {
	case 'Leave Game':
		$host = $_SESSION['game_host'];
		if ($host === $_SESSION['current_user']) {
			unset($game_list[$host]);
		}
		else {
			unset($game_list[$host]['players'][array_search($_SESSION['current_user'], $game_list[$host]['players'])]);
		}
		unset($game_list[$_SESSION['current_user']]);
		break;
	case 'Start Game':
		$host = $_SESSION['game_host'];
		if ($host === $_SESSION['current_user']) {
			$game_list[$host]['started'] = true;
			file_put_contents("../private/lobby", serialize($game_list));
			exit_to("/game");
		}
		else if ($game_list[$host]['started'] == true) {
			exit_to("/game");
		}
		echo "Game isn't ready."; exit;
		break;
	case 'Create Game':
		if (!isset($_POST['name']) || $_POST['name'] === "") { echo "Name not set."; exit; }
		$_SESSION['game_host'] = $_SESSION['current_user'];
		$game_list[$_SESSION['current_user']] = array('players' => array($_SESSION['current_user']), 'name' => $_POST['name'], 'started' => false);
		break;
	case 'Join Game':
		if (!isset($_POST['host']) || !isset($game_list[$_POST['host']]) || is_string($game_list[$_POST['host']])) { echo "Game does not exist."; exit; }
		if (!in_array($_SESSION['current_user'], $game_list[$_POST['host']]['players'])) {
			if ($game_list[$_POST['host']]['started'] == true) {
				echo "Player list already locked and loaded.";
				exit;
			}
			$game_list[$_POST['host']]['players'][] = $_SESSION['current_user'];
		}
		$game_list['current_user'] = $_POST['host'];
		break;
	default:
		echo "Action [" . $_POST['action'] . "] unknown.";
		exit;
}
file_put_contents("../private/lobby", serialize($game_list));
?>