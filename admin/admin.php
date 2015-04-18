<?php
require_once("php_assets/exit_to.php");
require_once('url.php');
@session_start();
//Make sure user is admin
if (file_exists("private/passwd")) { $passwd_database = unserialize(file_get_contents("private/passwd")); }
else { $passwd_database = array(); }
$current_user = $_SESSION['current_user'];
?>
<link rel="stylesheet" type="text/css" href="admin.css">
<style type="text/css">
	#admin_div li {
		line-height: 30px;
	}
	#admin_div form {
		display: inline;
	}
</style>
<div id="admin_div">
	<h1>Admin Page [<?php echo $current_user; ?>]</h1>
	<h2>Users:</h2>
	<ul>
		<?php
		foreach ($passwd_database as $login => $data) {
			if ($login === $current_user) { continue; }
			if ($data['admin'] == 0) { $is_admin  = ""; }
			else { $is_admin  = "checked"; }
			echo <<<EOT
		<li>
			<form method="post" action="login/admin_update.php">
				<input type="hidden" name="login" value="$login"/>[$login]:
				<input type="text" name="passwd" value=""/>
				Is Admin:<input type="checkbox" name="admin" value="true" $is_admin>
				<input type="submit" name="submit" value="Update" />
			</form>
			<form method="post" action="login/delete.php">
				<input type="hidden" name="login" value="$login"/>
				<input type="submit" name="submit" value="Delete" />
			</form>
		</li>
EOT;
		}
		?>
		<li>
			<form method="post" action="login/create.php">
				[<input type="text" name="login" value="Username"/>]:
				<input type="text" name="passwd" value="Password"/>
				Is Admin:<input type="checkbox" name="admin" value="true">
				<input type="hidden" name="submit" value="OK" />
				<input type="submit" name="create" value="Create" />
			</form>
		</li>
	</ul>
</div>
