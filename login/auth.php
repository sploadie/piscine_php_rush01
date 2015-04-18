<?php
// Used by login.php
// Honestly idk why this is its own file anymore
// Using it for other operations limits redirect and message options
function auth($login, $passwd)
{
	if (isset($login)    &&
		$login  !== ""   &&
		isset($passwd)   &&
		$passwd !== ""   &&
		file_exists("../private/passwd"))
	{
		$passwd_file = file_get_contents("../private/passwd");
		$passwd_database = unserialize($passwd_file);
		if (isset($passwd_database[$_POST['login']]) &&
			$passwd_database[$_POST['login']]['passwd'] === hash("whirlpool", $_POST['passwd']))
		{
			return true;
		}
	}
	return false;
}
?>
