<?php require_once('url.php'); ?>
<?php
require_once("../php_assets/exit_to.php");
require_once("../php_assets/return_to.php");
set_return_to((isset($_GET['return_to']) ? $_GET['return_to'] : ""));
@session_start();

if (!isset($_GET["action"])) { $_GET["action"] = "login"; }// Default action
if (!isset($_SESSION['current_user'])) { $_SESSION['current_user'] = ""; }// NULL -> ""

switch ($_GET["action"]) {
	case "login":
		//If the user is logged in but gets here, get him out
		if ($_SESSION['current_user'] !== "")
			exit_to("/?tab=3", (isset($_GET['message']) ? $_GET['message'] : 'Already logged in!'));
		$title = "Login";
		$form = <<<EOT
<form method="post" action="login.php">
	Username: <input type="text" name="login" value=""/>
	<br />
	Password: <input type="password" name="passwd" value=""/>
	<br />
	<input type="submit" name="submit" value="OK" />
</form>
<a href="/login/index.php?action=create"><button>Create an account</button></a>
EOT;
		break;
	case "logout":
		//Should log out and send back to homepage
		exit_to("/login/logout.php");
		break;
	case "create":
		//If the user is logged in but gets here, get him out
		$title = "Create Account";
		$form = <<<EOT
<form method="post" action="create.php">
	Username: <input type="text" name="login" value=""/>
	<br />
	Password: <input type="password" name="passwd" value=""/>
	<br />
	<input type="submit" name="submit" value="OK" />
</form>
<a href="/login/index.php?action=login"><button>Login</button></a>
EOT;
		break;
	case "modif":
		//If the user is NOT logged in but gets here, get him out
		if ($_SESSION['current_user'] === "")
			exit_to("/");
		$title = "Change Password";
		$current_user = $_SESSION['current_user'];
		$form = <<<EOT
<form method="post" action="modif.php">
	Username: <input type="text" name="login" value="$current_user"/>
	<br />
	Old Password: <input type="password" name="oldpw" value=""/>
	<br />
	New Password: <input type="password" name="newpw" value=""/>
	<br />
	<input type="submit" name="submit" value="OK" />
</form>
EOT;
		break;
}
?>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title."\n"; ?></title>
	<link href="<?php echo urlPath("jquery/jquery-ui.css"); ?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="login.css">
	<style>
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		background-color: black;
		margin: auto;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	#logo {
		margin: 0px auto;
	}
	#main {
		padding: 10px;
		margin: 0px auto;
	}
	#alert p {
		text-align: center;
	}
	.ui-icon-alert {
		position: relative;
		top: -2px;
	}
	#form_div {
		margin-left: auto;
		margin-right: auto;
	}
	h1 {
		margin: 7px 0px;
	}
	form {
		line-height: 23px;
	}
	</style>
</head>
<body>
<img id="logo" width="441" height="100" src="<?php echo urlPath("img/warhammer_logo.png"); ?>" alt="WARHAMMER40K">
<div id="main" class="ui-widget ui-widget-content ui-corner-all">
	<?php if (isset($_GET['message'])) { ?>
		<div id="alert" class="ui-widget">
			<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
				<strong>Alert:</strong> <?php echo urldecode($_GET['message']); ?>
				<span class="ui-icon ui-icon-alert" style="float: right; margin-left: .3em;"></span></p>
			</div>
		</div>
	<?php } ?>
	<div -d="form_div">
		<h1><?php echo $title; ?></h1>
		<?php echo $form."\n"; ?>
	</div>
</div>
</body>
</html>
