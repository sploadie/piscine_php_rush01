<?php

class Player {
	private $_name;
	private $_fleet = array();

	public function __construct($name, array $fleet) {
		$this->_name = (string) $name;
		foreach ($fleet as $ship) {
			if (!is_subclass_of($ship, 'Battleship'))
				trigger_error ( "Ship is not a Battleship", E_USER_ERROR );
			$this->_fleet[] = $ship;
		}
		// echo "Player __constructed." . PHP_EOL;
	}

	public function getName() { return $this->_name; }
	public function getFleet() { return $this->_fleet; }

	public function shipDestroyed($ship) {
		if (($key = array_search($ship, $this->_fleet))) {
			unset($this->_fleet[$key]);
			$this->_fleet = array_values($this->_fleet);
		}
	}

	public function lowerShips() {
		foreach ($this->_fleet as $ship) {
			$ship['y'] = $ship['y'] + 1;
		}
	}
}

?>