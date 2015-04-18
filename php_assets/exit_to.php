<?php
// Redirect to given path
// If message is provided, it will be sent with GET
// Message is converted to url safe
// Warning, this also ends the php code where from it was called
function exit_to($location, $message = "") {
	if ($message === "")
		header("Location: $location");
	else if (strpos($location, "?"))
		header("Location: $location&message=".urlencode($message));
	else
		header("Location: $location?message=".urlencode($message));
	exit ;
}
?>
