<?php
session_start();
require_once('Game.class.php');
require_once('url.php');
$game = new Game();
$game();
$_SESSION['game'] = $game;
?>
<html>
<head>
	<link href="<?php echo urlPath("jquery/jquery-ui.css"); ?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo urlPath("jquery/external/jquery/jquery.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo urlPath("game/gameAction.js"); ?>"></script>
	<style>
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}
	.demoHeaders {
		margin-top: 2em;
	}
	#dialog-link {
		padding: .4em 1em .4em 20px;
		text-decoration: none;
		position: relative;
	}
	#dialog-link span.ui-icon {
		margin: 0 5px 0 0;
		position: absolute;
		left: .2em;
		top: 50%;
		margin-top: -8px;
	}
	#icons {
		margin: 0;
		padding: 0;
	}
	#icons li {
		margin: 2px;
		position: relative;
		padding: 4px 0;
		cursor: pointer;
		float: left;
		list-style: none;
	}
	#icons span.ui-icon {
		float: left;
		margin: 0 4px;
	}
	.fakewindowcontain .ui-widget-overlay {
		position: absolute;
	}
	select {
		width: 200px;
	}
	</style>
</head>
<body style="background-color: black; margin: 0px; <?php echo $_SESSION['game']->bodyStyle(); ?>">
	<div id="space" style="position: relative; background-color: black; margin: 0px; padding: 0px; border-collapse: collapse;">
		<?php $_SESSION['game']->arenaToHTML(); ?>
		<div id="ships">
			<?php $_SESSION['game']->shipsToHTML(); ?>
		</div>
	</div>
	<div class="ui-overlay"><div class="ui-widget-shadow ui-corner-all" style="width: 185px; height: 205px; position: fixed; right: 2px; bottom: 5px;"></div></div>
	<div id="gui" class="ui-widget ui-widget-content ui-corner-all" style="position: fixed; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; width: 180px; height: 200px; bottom: 10px; right: 7px; padding: 3px;">
		<?php $_SESSION['game']->uiHTML(); ?>
	</div>
</body>
</html>
