<?php
include("../php_assets/return_to.php");
@session_start();
// Modify login password
// Can only be done by actual user (not admin)
// Does not allow change of admin settings (see: admin_update)
if (isset($_POST['login'])   &&
	$_POST['login'] !== ""   &&
	isset($_POST['oldpw'])   &&
	$_POST['oldpw'] !== ""   &&
	isset($_POST['newpw'])   &&
	$_POST['newpw'] !== ""   &&
	$_POST['submit'] === "OK")
{
	if (!file_exists("../private/passwd")) {
		return_to("NO USERS");
	}
	$passwd_file = file_get_contents("../private/passwd");
	$passwd_database = unserialize($passwd_file);
	if (!isset($passwd_database[$_POST['login']])) {
		exit_to("/login?action=modif", "Password change failed. (Unknown Username)");
		error_log("Modif failed. (Unknown Username)");
	}
	if ($passwd_database[$_POST['login']]['passwd'] !== hash("whirlpool", $_POST['oldpw'])) {
		exit_to("/login?action=modif", "Password change failed. (Incorrect Password)");
		error_log("Modif failed. (Incorrect Password)");
	}
	$passwd_database[$_POST['login']]['passwd'] = hash("whirlpool", $_POST['newpw']);
	$passwd_file = serialize($passwd_database);
	file_put_contents("../private/passwd", $passwd_file);
	return_to("OK");
}
else {
	exit_to("/login?action=modif", "Password change failed. (Empty field)");
	error_log("Password change failed. (Empty field)");
}
?>
