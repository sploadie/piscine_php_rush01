<?php
require_once('Battleship.class.php');

class Scout extends Battleship {
	protected function _default() { return array(
		'name'		=> "Silvian Scout",
		'width'		=> 3,
		'height'	=> 1,
		'sprite'	=> "battleship.jpg",
		'hull'		=> 4,
		'engine'	=> 10,
		'speed'		=> 18,
		'agility'	=> 3,
		'shield'	=> 0,
		'weapons'	=> array()); }
}

?>