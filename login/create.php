<?php
include("../php_assets/exit_to.php");
include("../php_assets/return_to.php");
@session_start();
if ($_SESSION['admin'])
	@set_return_to('/?tab=3');
else
	@set_return_to('/login?action=create');
// Create new user
// $_POST['admin'] can be used to set admin permissions
// $_POST['admin'] is not sent except from /admin form
// Protected from repeat creation of a login
if ($_POST['submit'] === "OK" &&
	isset($_POST['login'])    &&
	$_POST['login']  !== ""   &&
	isset($_POST['passwd'])   &&
	$_POST['passwd'] !== "")
{
	if (!file_exists("../private"))
		mkdir("../private");
	if (!file_exists("../private/passwd"))
		file_put_contents("../private/passwd", "");
	$passwd_file = file_get_contents("../private/passwd");
	$passwd_database = unserialize($passwd_file);
	if (isset($passwd_database[$_POST['login']])) {
		return_to("User creation failed. (Username already taken.)");
		error_log("User creation failed. (Username already taken.)");
	}
	$passwd_database[$_POST['login']]['passwd'] = hash("whirlpool", $_POST['passwd']);
	$passwd_database[$_POST['login']]['admin'] = (isset($_POST['admin']) ? true : false);
	$passwd_file = serialize($passwd_database);
	file_put_contents("../private/passwd", $passwd_file);
	if (!isset($_SESSION['current_user'])) { $_SESSION['current_user'] = $_POST['login']; }
	return_to("User creation success.");
}
else {
	return_to("User creation failed. (Empty field)");
	error_log("User creation failed. (Empty field)");
}
?>
