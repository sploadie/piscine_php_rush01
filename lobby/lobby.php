<?php
require_once('url.php');
@session_start();
if (file_exists("private/chat")) { $chat_archive = unserialize(file_get_contents("private/chat")); }
else { $chat_archive = array(); }
$current_user = $_SESSION['current_user'];
?>
<style type="text/css">
	#lobby_div {
	}
	#lobby_div .lobby_section {
		padding: 5px;
		margin-bottom: 5px;
	}
	#lobby_div .lobby_section h1 {
		text-align: center;
		margin: 5px 0px 0px 0px;
	}
	#lobby_div #speak {
		margin-top: 10px;
		font-size: 2em;
		display: flex;
		justify-content: space-around;
		align-items: center;
	}
	#lobby_div #speak #speak_text {
		width: 80%;
		font-size: 0.8em;
	}
</style>
<script src="<?php echo urlPath("jquery/external/jquery/jquery.js"); ?>"></script>
<script type="text/javascript">
function sendMessage() {
	$.ajax({
        type: 'POST',
        url: 'chat/speak.php',
        data: $('#speak').serialize(), 
        success: function(msg) {
        	$('#speak_text').val("");
        	if (msg !== "") {
        		alert(msg);
        	};
        	$('#chat').load('chat/chat.php');
        }
    });
}
function updateChat() {
	$.ajax({
        type: 'POST',
        url: 'chat/check_chat.php',
        success: function(msg) { if (msg === "update") { $('#chat').load('chat/chat.php'); }; }
    });
}
$(document).ready(function() {
	$(function() {
		$('#game_list').load('lobby/game_list.php');
		$('#chat').load('chat/chat.php');
	});
	setInterval("updateChat()", 5000);
});
</script>
<div id="lobby_div">
	<div id="game_list" class="lobby_section ui-widget ui-widget-content ui-corner-all">
	</div>
	<div id="global_chat" class="lobby_section ui-widget ui-widget-content ui-corner-all">
		<h1>Global Chat</h1>
		<form id="speak" method="post" action="" onsubmit="sendMessage(); return false;">
			<input type="hidden" name="login" value="<?php echo $current_user; ?>"/>Message:
			<textarea id="speak_text" type="text" name="message"></textarea>
			<input type="submit" value="Post">
		</form>
		<div id="chat">
		</div>
	</div>
</div>
