<?php

abstract class Battleship implements ArrayAccess {
	protected function _default() { return (array(
		'name'		=> "Badass",
		'width'		=> 0,
		'height'	=> 0,
		'sprite'	=> "battleship.jpg",
		'hull'		=> 0,
		'engine'	=> 0,
		'speed'		=> 0,
		'agility'	=> 0,
		'shield'	=> 0,
		'weapons'	=> array())); }
	private $_data = array(
		'engine'	=> NULL,
		'speed'		=> NULL,
		'shield'	=> NULL);
	private $_arena;
	private $_name;
	private $_coordinates;
	private $_hull;
	private $_player = NULL;

	public function __construct($name, $coordinates, Arena $arena) {
		$this->_arena = $arena;
		$this->_name = (string) $name;
		$this->_hull = (int) $this->_default()['hull'];
		$this->resetData();
		if (count($coordinates) != 2 || !is_int($coordinates['x']) || !is_int($coordinates['y']))
				trigger_error ( "Incorrect Coordinates", E_USER_ERROR );
		$this->_coordinates['x'] = $coordinates['x'];
		$this->_coordinates['y'] = $coordinates['y'];
		$this->_coordinates['width']  = (int) $this->_default()['width'];
		$this->_coordinates['height'] = (int) $this->_default()['height'];
		echo "Battleship __constructed." . PHP_EOL;
	}

	public function getData($key) {
		if (!array_key_exists($key, $this->_data))
			trigger_error ( get_class($this) . " object does not have a data value for " . $key, E_USER_ERROR );
		return $this->_data[$key];
	}
	public function resetData() {
		foreach ($this->_data as $key => $value) {
			$this->_data[$key] = $this->_default()[$key];
		}
	}

	public function getName() { return $this->_name; }
	public function getHull() { return $this->_hull; }

	public function getDefault($key) {
		if (!isset($this->_default()[$key]))
			trigger_error ( get_class($this) . " does not have a default value for " . $key, E_USER_ERROR );
		return $this->_default()[$key];
	}

	public function setPlayer(Player $player) {
		$this->_player = $player;
	}

	public function destroy() {
		$this->_arena->removeShip($this);
		if (!is_null($this->_player))
			$this->_player->shipDestroyed($this);
		echo get_class($this) . " " . $this->_name . " was destroyed." . PHP_EOL;
	}

	public function __destruct() {
		echo get_class($this) . " " . $this->_name . " was __destructed." . PHP_EOL;
	}

	// ARRAY ACCESS ==================================>
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			trigger_error ( get_class($this) . "'s coordinates cannot have new keys.", E_USER_ERROR );
		} else if ($offset !== 'x' && $offset !== 'y') {
			trigger_error ( "Only " . get_class($this) . "'s coordinates can can be set.", E_USER_ERROR );
		} else {
			$this->_coordinates[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->_coordinates[$offset]);
	}

	public function offsetUnset($offset) {
		trigger_error ( get_class($this) . "'s coordinates cannot be unset.", E_USER_ERROR );
		//unset($this->_coordinates[$offset]);
	}

	public function offsetGet($offset) {
		return isset($this->_coordinates[$offset]) ? $this->_coordinates[$offset] : trigger_error ( get_class($this) . "'s coordinates only have x, y, width, and height keys.", E_USER_ERROR );;
	}
	// <================================== ARRAY ACCESS
}

?>