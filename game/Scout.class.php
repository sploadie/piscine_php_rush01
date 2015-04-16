<?php
require_once('Battleship.class.php');

class Scout extends Battleship {
	protected function _default() { return array(
		'name'		=> "Jiff",
		'width'		=> 3,
		'height'	=> 2,
		'sprite'	=> "battleship.jpg",
		'hull'		=> 0,
		'engine'	=> 0,
		'speed'		=> 0,
		'agility'	=> 0,
		'shield'	=> 0,
		'weapons'	=> array()); }
}

?>