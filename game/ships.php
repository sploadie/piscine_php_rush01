<?php
require_once('Game.class.php');
session_start();
$_SESSION['game']->shipsToHTML();
?>