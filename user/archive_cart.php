<?php
include("../php_assets/return_to.php");
session_start();
if (!isset($_SESSION['cart']) ||
		$_SESSION['cart'] === array()) {
	return_to("NO CART");
}
if (!isset($_SESSION['current_user']) ||
	$_SESSION['current_user'] === "") {
	return_to("NOT LOGGED IN");
}
$user_name = $_SESSION['current_user'];

if (!file_exists("../private"))
	mkdir("../private");
if (!file_exists("../private/archive"))
	file_put_contents("../private/archive", "");
$archive_file = file_get_contents("../private/archive");
$archive_database = unserialize($archive_file);
if (!isset($archive_database[$user_name])) {
	$archive_database[$user_name] = array();
}

# I guess we should also save the $ amount and total

$archive_database[$user_name][] = array('time' => time(), 'products' => $_SESSION['cart']);
$archive_file = serialize($archive_database);
file_put_contents("../private/archive", $archive_file);
$_SESSION['cart'] = array();
return_to("ARCHIVED");
?>
