<?php
// Used by login.php
function is_admin($login)
{
	if (isset($login)    &&
		$login  !== ""   &&
		file_exists("../private/passwd"))
	{
		$passwd_file = file_get_contents("../private/passwd");
		$passwd_database = unserialize($passwd_file);
		if (isset($passwd_database[$_POST['login']]))
		{
			return $passwd_database[$login]['admin'];
		}
	}
	return false;
}
?>
