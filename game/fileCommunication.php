<?php

@session_start();

# $_SESSION['game'] = unserialize(file);
function setSessionToUnserialized() {
	$game_list = unserialize(file_get_contents("../private/lobby"));
	unset($_SESSION['game_host']);
	if (isset($game_list[$_SESSION['current_user']])) {
		if (is_string($game_list[$_SESSION['current_user']])) {
			$host = $game_list[$_SESSION['current_user']];
			if (isset($game_list[$host]) && !is_string($game_list[$host])) {
				$_SESSION['game_host'] = $host;
			} else {
				unset($game_list[$host]);
				file_put_contents("../private/lobby", serialize($game_list));
				exit_to("/");
			}
		} else {
			$_SESSION['game_host'] = $_SESSION['current_user'];
		}
	} else {
		exit_to("/");
	}
	$game = $game_list[$_SESSION['game_host']]['game'];
	error_log("game object as " . $_SESSION['current_user'] . ": " . $game);
	$_SESSION['game'] = $game;
}

function serializeGameInSession() {
	$game_list = unserialize(file_get_contents("../private/lobby"));
	$game_list[$_SESSION['game_host']]['game'] = $_SESSION['game'];

	file_put_contents("../private/lobby", serialize($game_list));
}

?>