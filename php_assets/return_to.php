<?php
include("exit_to.php");
// Set the path to return to
function set_return_to($path) {
	@session_start();
	$_SESSION['return_to'] = $path;
}
// Return to set path with message
// If no path is set, redirect is to home
function return_to($message = "") {
	@session_start();
	if (!isset($_SESSION['return_to']) || $_SESSION['return_to'] === "")
		$_SESSION['return_to'] = "/";
	exit_to($_SESSION['return_to'], $message);
}
?>
