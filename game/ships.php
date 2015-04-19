<?php
require_once('classIncludes.php');
@session_start();

$_SESSION['game']->shipsToHTML($_SESSION['current_user']);
?>