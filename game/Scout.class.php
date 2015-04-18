<?php
require_once('Battleship.class.php');

class Scout extends Battleship {

	public function __construct($x, $y) {
		parent::__construct( array ( 'x' => $x
									, 'y' => $y
									, 'height' => 1
									, 'width' => 3
									, 'maxHealth' => 10
									, 'sprite' => 'battleship.jpg' ) );
	}
/*		'hull'		=> 4,
		'engine'	=> 10,
		'speed'		=> 18,
		'agility'	=> 3,
		'shield'	=> 0,
		'weapons'	=> array()); }*/
}

?>