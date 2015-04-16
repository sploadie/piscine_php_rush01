<?php

abstract class Player {
	private $_name;
	private $_fleet;

	public function __construct($name, array $fleet) {
		$this->_name = (string) $name;
		foreach ($fleet as $ship) {
			# code...
		}
		echo "Player __constructed." . PHP_EOL;
	}

	public function getName() { return $this->_name; }
}

?>