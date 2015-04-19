<?php
require_once("php_assets/exit_to.php");
require_once('url.php');
@session_start();
$current_user = $_SESSION['current_user'];
?>
<style type="text/css">
	#user_div {
	}
</style>
<div id="user_div">
</div>
