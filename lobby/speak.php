<?php
@session_start();
if (file_exists("../private/chat")) { $chat_archive = unserialize(file_get_contents("../private/chat")); }
else { $chat_archive = array(); }
if ($_SESSION['current_user'] !== $_POST['login']) {
	echo "Dont hack bro.";
	exit;
}
if ($_POST['message'] === "") {
	echo "Messages are appreciated for their meaningful content.";
	exit;
}
$chat_archive[] = array('time' => time(), 'login' => $_POST['login'], 'message' => $_POST['message']);
if (!file_exists("../private"))
	mkdir("../private");
file_put_contents("../private/chat", serialize($chat_archive));
?>
