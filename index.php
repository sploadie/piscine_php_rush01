<?PHP
require_once('url.php');
require_once("php_assets/exit_to.php");
require_once("php_assets/return_to.php");
set_return_to("/");
@session_start();
if (!file_exists("private/passwd")) { exit_to("/login", "NO USERS"); }
$passwd_database = unserialize(file_get_contents("private/passwd"));
if (!isset($_SESSION['current_user']) || !isset($passwd_database[$_SESSION['current_user']])) {	exit_to("/login", "Logged out"); }
$user_name = $_SESSION['current_user'];
?>
<?php
function login_link($action, $tooltip, $icon) {
	echo '<a href="/login?action=' . $action . '" title="' . $tooltip . '"><span class="ui-icon ui-icon-' . $icon . '"></span></a>';
}
?>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>MastBreaker</title>
	<link href="<?php echo urlPath("jquery/jquery-ui.css"); ?>" rel="stylesheet">
	<link href="main.css" rel="stylesheet">
	<style>
	body{
		background-color: black;
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 10px 50px;
		min-width: 700px;
	}
	#top {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		flex-wrap: nowrap;
		flex: none;
		margin: 10px 0px;
	}
	#logo {
		min-width: 441px;
		min-height: 100px;
		margin: 0px;
		position: relative;
		left: -30px;
	}
	#user_data {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		flex-wrap: nowrap;
		margin: 0px 5px;
	}
	#user_buttons {
		margin: 7px 3px;
	}
	#user_buttons span {
		cursor: pointer;
		margin: 3px 0px;
	}
	#profile_picture {
		border-radius: 10px;
		min-width: 100px;
		min-height: 100px;
	}
	</style>
</head>
<body>
<div id="top">
	<img id="logo" width="441" height="100" src="<?php echo urlPath("img/warhammer_logo.png"); ?>" alt="WARHAMMER40K">
	<div id="user_data">
		<div id="user_buttons">
			<?php login_link('logout', 'Logout', 'closethick'); ?>
			<?php login_link('modif', 'Change Password', 'pencil'); ?>
		</div>
		<img id="profile_picture" width="100" height="100" src="<?php echo urlPath("img/default_profile_picture.jpg"); ?>" alt="PROFILE PIC">
	</div>
</div>
<div id="tabs">
	<ul>
		<li><a href="#lobby">Lobby</a></li>
		<li><a href="#ranking">Ranking</a></li>
		<li><a href="#profile">Profile</a></li>
		<?php if ($_SESSION['admin']) { echo '<li><a href="#admin">Admin</a></li>';} ?>
	</ul>
	<div id="lobby"><?php include('lobby/lobby.php'); ?></div>
	<div id="ranking">Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.</div>
	<div id="profile"><?php include('user/user.php'); ?></div>
	<?php if ($_SESSION['admin']) { echo '<div id="admin">'; include('admin/admin.php'); echo '</div>';} ?>
</div>

<script src="<?php echo urlPath("jquery/external/jquery/jquery.js"); ?>"></script>
<script src="<?php echo urlPath("jquery/jquery-ui.js"); ?>"></script>
<script>
$( "#user_buttons" ).tooltip();
$( "#tabs" ).tabs();
$(document).ready(function(){
	<?php if (isset($_GET['tab'])) { echo '$( "#tabs" ).tabs("option", "active", ' . $_GET['tab'] . ');'; } ?>
});
</script>
<script>
// DEFAULT V ================

$( "#autocomplete" ).autocomplete({ source: ["ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme"] });

$( "#dialog" ).dialog({
	autoOpen: false,
	width: 400,
	buttons: [
		{
			text: "Ok",
			click: function() { $( this ).dialog( "close" ); }
		},
		{
			text: "Cancel",
			click: function() { $( this ).dialog( "close" ); }
		}
	]
});

// Link to open the dialog
$( "#dialog-link" ).click(function( event ) {
	$( "#dialog" ).dialog( "open" );
	event.preventDefault();
});

$( "#accordion" ).accordion();
$( "#button" ).button();
$( "#radioset" ).buttonset();
$( "#slider" ).slider({ range: true, values: [ 17, 67 ] });
$( "#datepicker" ).datepicker({ inline: true });
$( "#progressbar" ).progressbar({ value: 20 });
$( "#spinner" ).spinner();
$( "#menu" ).menu();
$( "#tooltip" ).tooltip();
$( "#selectmenu" ).selectmenu();

// Hover states on the static widgets
$( "#dialog-link, #icons li" ).hover(
	function() { $( this ).addClass( "ui-state-hover" ); },
	function() { $( this ).removeClass( "ui-state-hover" ); }
);
</script>
</body>
</html>
