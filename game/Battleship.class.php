<?php

require_once('classIncludes.php');

abstract class Battleship implements ArrayAccess {
	protected $data;

	public function __construct( array $kwargs ) {
		if (isset($kwargs['x'])
			&& isset($kwargs['y'])
			&& isset($kwargs['height'])
			&& isset($kwargs['width'])
			&& isset($kwargs['maxHealth'])
			&& isset($kwargs['sprite'])) {
			$this->data = $kwargs;
		}
	}

	public function move($deltaX, $deltaY) {
		$this->data['x'] += $deltaX;
		$this->data['y'] += $deltaY;
	}

	// ARRAY ACCESS ==================================>
	public function offsetSet($offset, $value) {
		trigger_error ( "You can't set the stuff in BattleShip, silly goose! ", E_USER_ERROR );
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset) {
		trigger_error ( get_class($this) . "'s coordinates cannot be unset.", E_USER_ERROR );
		//unset($this->_coordinates[$offset]);
	}

	public function offsetGet($offset) {
		return $this->data[$offset];
	}
	// <================================== ARRAY ACCESS
}

?>