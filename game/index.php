<?php

session_start();

require_once('Game.class.php');
require_once('../url.php');

if (isset($_SESSION['game'])) {
	$game = new Game();
	$game();
	$_SESSION['game'] = $game;
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="<?php echo urlPath("jquery/jquery-ui.css"); ?>" rel="stylesheet">
	<script type="text/javascript" src="<?= urlPath("jquery/external/jquery/jquery.js"); ?>"></script>
	<script type="text/javascript" src="<?= urlPath("game/gameAction.js"); ?>"></script>
</head>
<body style="<?php echo $_SESSION['game']->bodyStyle(); ?>">
	<div id="space">
		<?php $_SESSION['game']->arenaToHTML(); ?>
		<div id="ships">
			<?php $_SESSION['game']->shipsToHTML(); ?>
		</div>
	</div>
	<div class="ui-overlay">
		<div id="overlay-thingy" class="ui-widget-shadow ui-corner-all"></div>
	</div>
	<div id="gui" class="ui-widget ui-widget-content ui-corner-all">
		<?php $_SESSION['game']->uiHTML(); ?>
	</div>
</body>
</html>
