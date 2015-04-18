<?php
include("../php_assets/return_to.php");
set_return_to("/?tab=3");
// Removes a login entirely
// Admin only
if (isset($_POST['login'])  &&
	$_POST['login'] !== ""  &&
	$_POST['submit'] === "Delete")
{
	@session_start();
	if (!file_exists("../private/passwd")) {
		return_to("No users?");
	}
	$passwd_file = file_get_contents("../private/passwd");
	$passwd_database = unserialize($passwd_file);
	if (isset($_SESSION['current_user'])                              &&
		isset($passwd_database[$_SESSION['current_user']])            &&
		$passwd_database[$_SESSION['current_user']]['admin'] === true) {
		if (isset($passwd_database[$_POST['login']])) {
			unset($passwd_database[$_POST['login']]);
			$passwd_file = serialize($passwd_database);
			file_put_contents("../private/passwd", $passwd_file);
			return_to('User ' . $_POST['login'] . ' deleted.');
		} else { return_to("User does not exist"); }
	} else { return_to("You are not admin"); }
}
return_to("Login not received");
?>
