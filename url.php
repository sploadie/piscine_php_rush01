<?php

# root of site
function urlHome() {
	return sprintf(
		"%s://%s:8080",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME']
	);
}

# where you currently are (don't really use this one)
function urlCurrent() {
	return sprintf(
		"%s://%s:8080/%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		$_SERVER['REQUEST_URI']
	);
}

# gets any url from the root
function urlPath($path) {
	return sprintf(
		"%s/%s",
		urlHome(),
		$path
	);
}

?>