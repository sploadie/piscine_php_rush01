<?php
include("../php_assets/return_to.php");
// Kicks user, logged in or otherwise
@session_start();
$_SESSION['current_user'] = "";
session_unset();
return_to("OK");
?>
