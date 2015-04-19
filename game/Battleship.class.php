<?php

require_once('classIncludes.php');

abstract class Battleship implements ArrayAccess {

	protected $data;

	public static function doc()
	{
	    return file_get_contents('./Battleship.doc.txt') . PHP_EOL;
	}

	public function __construct( array $kwargs ) {
		# todo: have some of these have actual default values
		if (isset($kwargs['x'])
			&& isset($kwargs['y'])
			&& isset($kwargs['height'])
			&& isset($kwargs['width'])
			&& isset($kwargs['maxHealth'])
			&& isset($kwargs['defaultSpeed'])
			&& isset($kwargs['defaultShield'])
			&& isset($kwargs['damage'])
			&& isset($kwargs['defaultAmmo'])
			&& isset($kwargs['defaultPower'])
			&& isset($kwargs['range'])
			&& isset($kwargs['sprite']) ) {
			$this->data = $kwargs;

			$this->data['health'] = $this->data['maxHealth'];
			$this->resetBeforeMove();
		} else {
			trigger_error ( "Incompatible kwargs passed to " . get_class($this)
								, E_USER_ERROR );
		}
	}

	public function resetBeforeMove() {
		$this->data['speed'] = $this->data['defaultSpeed'];
		$this->data['shield'] = $this->data['defaultShield'];
		$this->data['ammo'] = $this->data['defaultAmmo'];
		$this->data['power'] = $this->data['defaultPower'];
	}

	public function move($deltaX, $deltaY) {
		$this->data['x'] += $deltaX;
		$this->data['y'] += $deltaY;
		$this->changeShipCoordinates();
	}

	# resets cached ship coordinates
	private function changeShipCoordinates() {
		$coordinates = array();

		$row = 0;
		while ($row < $this['height']) {
			$column = 0;
			while ($column < $this['width']) {
				array_push($coordinates, array ( 'x' => $this['x'] + $column
												, 'y' => $this['y'] + $row ) );
				$column++;
			}
			$row++;
		}
		$this->data['coordinates'] = $coordinates;
	}

	# returns an array of all the locations the ship takes up
	public function getShipCoordinates() {
		if ( isset( $this['coordinates'] ) ) {
			return $this['coordinates'];
		}
		$this->changeShipCoordinates();
		return $this['coordinates'];
	}

	public function isDead() {
		return ($this->data['health'] <= 0);
	}

	public function changeHealth($deltaHealth) {
		$this->data['health'] += $deltaHealth;
	}

	public function changePower($deltaPower) {
		$this->data['power'] += $deltaPower;
	}

	public function changeShield($deltaPower) {
		$this->data['shield'] += $deltaPower;
	}

	public function changeSpeed($deltaSpeed) {
		$this->data['speed'] += $deltaSpeed;
	}

	public function changeAmmo($deltaAmmo) {
		$this->data['ammo'] += $deltaAmmo;
	}

	// ARRAY ACCESS ==================================>
	public function offsetSet($offset, $value) {
		trigger_error ( "You can't set the stuff in " . get_class($this)
						. ", silly goose! ", E_USER_ERROR );
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset) {
		trigger_error ( get_class($this)
						. "'s coordinates cannot be unset.", E_USER_ERROR );
	}

	public function offsetGet($offset) {
		return $this->data[$offset];
	}
	// <================================== ARRAY ACCESS
}

?>