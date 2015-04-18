<?php

function urlHome() {
	return sprintf(
		"%s://%s:8080",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME']
	);
}
function urlCurrent() {
	return sprintf(
		"%s://%s%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		$_SERVER['REQUEST_URI']
	);
}
function urlPath($path) {
	return sprintf(
		"%s/%s",
		urlHome(),
		$path
	);
}

?>