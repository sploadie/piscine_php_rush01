<?php

trait urlTools {
	private function _urlHome() {
		return sprintf(
			"%s://%s:8080",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME']
		);
	}
	private function _urlCurrent() {
		return sprintf(
			"%s://%s%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME'],
			$_SERVER['REQUEST_URI']
		);
	}
	private function _url($path) {
		return sprintf(
			"%s/%s",
			$this->_urlHome(),
			$path
		);
	}
}


?>