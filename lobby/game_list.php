<?php
@session_start();
if (file_exists("../private/lobby"))
	$game_list = unserialize(file_get_contents("../private/lobby"));
else
	$game_list = array();
unset($_SESSION['game_host']);
if (isset($game_list[$_SESSION['current_user']])) {
	if (is_string($game_list[$_SESSION['current_user']])) {
		$host = $game_list[$_SESSION['current_user']];
		if (isset($game_list[$host]) && !is_string($game_list[$host])) {
			$_SESSION['game_host'] = $host;
		} else {
			unset($game_list[$host]);
			file_put_contents("../private/lobby", serialize($game_list));
		}
	} else {
		$_SESSION['game_host'] = $_SESSION['current_user'];
	}
}
?>
<style>
#leave_game, #start_game, #create_game {
	text-align: center;
}
#game_list_accordion {
	font: 1.1em "Trebuchet MS", sans-serif;
}
#game_list_accordion strong{
	font-size: 1.2em;
	color: #add8e6;
}
#game_list_accordion div {
	word-wrap: break-word;
}
</style>
<script type="text/javascript">
function lobbyAction(form_id) {
	//alert($(form_id).serialize());
	$.ajax({
        type: 'POST',
        url: $(form_id).attr( 'action' ),
        data: $(form_id).serialize(), 
        success: function(msg) {
        	if (msg !== "") {
        		alert(msg);
        	};
        	$('#game_list').load('lobby/game_list.php');
        }
    });
}
</script>
<h1>Games</h1>
<?php if (isset($_SESSION['game_host'])) { ?>
<form id="leave_game" method="post" action="lobby/lobby_action.php" onsubmit="lobbyAction('#leave_game'); return false;">
	<input type="hidden" name="login" value="<? echo $_SESSION['current_user']; ?>"/>
	Waiting for <? echo $_SESSION['game_host']; ?> to start game...&nbsp;
	<input type="hidden" name="action" value="Leave Game"/>
	<input type="submit" value="Leave Game"/>
</form>
<?php } else { ?>
<form id="create_game" method="post" action="lobby/lobby_action.php" onsubmit="lobbyAction('#create_game'); return false;">
	Game name: <input type="text" name="name" value=""/>
	<input type="hidden" name="action" value="Create Game"/>
	<input type="submit" value="Create Game"/>
</form>
<?php } if (isset($_SESSION['game_host']) && ($_SESSION['game_host'] === $_SESSION['current_user'] || $game_list[$_SESSION['game_host']]['started'] == true) && count($game_list[$_SESSION['game_host']]['players']) > 1) { ?>
<form id="start_game" method="post" action="lobby/lobby_action.php">
	<input type="hidden" name="action" value="Start Game"/>
	<input type="submit" value="Start Game"/>
</form>
<?php } ?>
<div id="game_list_accordion">
<?php
if (isset($_SESSION['game_host'])) {
	$host = $_SESSION['game_host'];
	$current_game = $game_list[$host];
	echo "<h3>[ <strong>" . $current_game['name'] . "</strong> ] Host: $host</h3><div>";
	echo "Players: " . implode(", ", $current_game['players']);
	if ($current_game['started'] == true) { echo "<br />Game has started."; }
	echo "</div>";
}
foreach (array_reverse($game_list) as $host => $game_info) {
	if (is_string($game_info))
		continue;
	if (isset($_SESSION['game_host']) && $host === $_SESSION['game_host'])
		continue;
	$game_name = $game_info['name'];
	echo "<h3>[ <strong>$game_name</strong> ] Host: $host</h3><div>";
	echo "Players: " . implode(", ", $game_info['players']);
	if ($game_info['started'] == true) { echo "<br />Game has started."; }
	if (!isset($_SESSION['game_host'])) { ?>
	<form id="join_game" method="post" action="lobby/lobby_action.php" onsubmit="lobbyAction('#join_game'); return false;">
		<?php echo '<input type="hidden" name="host" value="' . $host . '"/>'; ?>
		<input type="hidden" name="action" value="Join Game"/>
		<input type="submit" value="Join Game"/>
	</form>
	<?php }
	echo "</div>";
}
?>
</div>
<script type="text/javascript">
$( "#game_list_accordion" ).accordion({
  collapsible: true,
  heightStyle: "content"
});
</script>