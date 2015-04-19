<?php
if (file_exists("../private/chat"))
	$chat_archive = unserialize(file_get_contents("../private/chat"));
else
	exit;
?>
<style>
#chat_accordion {
	font: 1.1em "Trebuchet MS", sans-serif;
}
#chat_accordion strong{
	font-size: 1.2em;
	color: #add8e6;
}
#chat_accordion div {
	word-wrap: break-word;
}
</style>
<div id="chat_accordion">
<?php
foreach (array_reverse(array_slice($chat_archive, -20)) as $msg) {
	$msg_login = $msg['login'];
	if (date('Y') - date('Y', $msg['time'])) {
			$msg_time =  date('M jS, Y h:i:s', $msg['time']); }
	else {  $msg_time =  date('M jS, h:i:s',   $msg['time']); }
	$msg_message = $msg['message'];
	$msg_message_short = substr($msg_message, 0, 77);
	if ($msg_message_short !== $msg_message) { $msg_message_short =  $msg_message_short . "...";}
	$msg_message = preg_replace("/\r\n|\r|\n/", '<br/>', $msg_message);
	echo <<<EOT
	<h3>[$msg_time] <strong>$msg_login</strong> : $msg_message_short</h3>
	<div>$msg_message</div>
EOT;
}
?>
</div>
<script type="text/javascript">
$( "#chat_accordion" ).accordion({
  collapsible: true,
  heightStyle: "content"
});
</script>