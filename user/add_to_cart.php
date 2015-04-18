<?php
include("../php_assets/return_to.php");
session_start();
if ($_POST['submit'] === "Add To Cart" &&
	isset($_POST['product'])    &&
	$_POST['product']  !== "")
{
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	if (!isset($_SESSION['cart'][$_POST['product']])) {
		$_SESSION['cart'][$_POST['product']] = 1;
	} else {
		$_SESSION['cart'][$_POST['product']]++;
	}
	$_SESSION['return_to'] = "../products";
	return_to("OK");
}
else
	return_to("ERROR");
?>
