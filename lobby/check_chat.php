<?php
@session_start();
if (!isset($_SESSION['chat_last_update']))
	$_SESSION['chat_last_update'] = 0;
if (filemtime('../private/chat') > $_SESSION['chat_last_update']) {
	$_SESSION['chat_last_update'] = filemtime('../private/chat');
	echo "update";
}
?>