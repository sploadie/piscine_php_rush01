<?php
// session_start();
// $_SESSION['ROOT_DIR'] = dirname(__FILE__);
// define('ROOT_DIR', dirname(__FILE__));
require_once('Game.class.php');
require_once('Scout.class.php');
$game = new Game();
?>
<html>
<head>
</head>
<body style="background-color: blue">
	<?php $game(); ?>
</body>
</html>
