<?php
require_once('Arena.class.php');
require_once('Battleship.class.php');

class Game {
	private $_arena;

	public function __construct() {
		$this->_arena = new Arena();
		echo "Game __constructed." . PHP_EOL;
	}

	public function __invoke() {
		echo "Game __invoked." . PHP_EOL;
		$this->_arena->addShips(array(
			new Scout("Jiff", array('x' => 0, 'y' => 0), $this->_arena),
		));
		$this->_arena->toHTML();
	}
}

?>