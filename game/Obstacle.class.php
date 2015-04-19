<?php
require_once('Battleship.class.php');

class Obstacle extends Battleship {

	public function __construct($x, $y, $height, $width) {
		parent::__construct( array ( 'x' => $x
									, 'y' => $y
									, 'height' => $height
									, 'width' => $width
									, 'maxHealth' => 10000
									, 'sprite' => 'obstacle.gif' ) );
	}
}

?>