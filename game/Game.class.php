<?php
require_once('Arena.class.php');
require_once('Battleship.class.php');
require_once('Scout.class.php');
require_once('Player.class.php');

class Game {
	private $_arena;
	private $_player1;
	private $_player2;
	private $_currentSettings = array(
		'buttons' => array(
			array("minusthick",		"triangle-1-n",	"plusthick",	"hidden",	"check"),
			array("triangle-1-w",	"triangle-1-s",	"triangle-1-e",	"hidden",	"comment")),
		'message' => "Hey you, Player.<br />I got the Hocus Focus.",
		'content' => ""
	);

	public function __construct() {
		$this->_arena = new Arena();
		// echo "Game __constructed." . PHP_EOL;
	}

	public function bodyStyle() {
		$body_width = $this->_arena->getWidth() * $this->_arena->getTileSize() + 200;
		$body_height = $this->_arena->getHeight() * $this->_arena->getTileSize() + 200;
		return "padding: 100px 0px 0px 100px; width: " . $body_width . "px; height: " . $body_height . "px;";
	}

	public function __invoke() {
		$this->_player1 = new Player("Sploadie", array(
			new Scout("Joey", array('x' => 1, 'y' => 1), $this->_arena, false),
			new Scout("Jess", array('x' => 2, 'y' => 3), $this->_arena, false),
			new Scout("Jiff", array('x' => 3, 'y' => 5), $this->_arena, false),
			new Scout("Jill", array('x' => 4, 'y' => 7), $this->_arena, false)
		));
		$this->_player2 = new Player("Chaos", array(
			new Scout("Mack", array('x' => 11, 'y' => 1), $this->_arena, true),
			new Scout("Mill", array('x' => 12, 'y' => 3), $this->_arena, true),
			new Scout("Mint", array('x' => 13, 'y' => 5), $this->_arena, true),
			new Scout("Mort", array('x' => 14, 'y' => 7), $this->_arena, true)
		));

		$this->_arena->addShips($this->_player1->getFleet());
		$this->_arena->addShips($this->_player2->getFleet());
	}

	public function arenaToHTML() { $this->_arena->toHTML(); }
	public function shipsToHTML() { $this->_arena->shipsToHTML(); }
	public function lowerShips() { $this->_player1->lowerShips(); }

	public function uiHTML() {
		$content = $this->_currentSettings['content'];
		$message = $this->_currentSettings['message'];
		$interface = $this->_uiInterface($this->_currentSettings['buttons']);
		echo <<<EOT
			$content
			<div class="ui-dialog-content ui-widget-content" style="background: none; border: 0;">
				<p>$message</p>
			</div>
			$interface
EOT;
	}

	private function _uiInterface(array $buttons) {
		$html = "";
		foreach ($buttons as $button_row) {
			$html = $html . '<ul id="icons" class="ui-widget ui-helper-clearfix">' . PHP_EOL;
			foreach ($button_row as $button) { $html = $html . $this->_uiButton($button); }
			$html = $html . '</ul>' . PHP_EOL;
		}
		return $html;
	}

	private function _uiButton($type) {
		if ($type === 'hidden')	{ return '<li class="ui-state-default ui-corner-all" style="visibility: hidden;"><span class="ui-icon"></span></li>' . PHP_EOL; }
		else					{ return '<li class="ui-state-default ui-corner-all game-button" title="action.php?button=' . $type . '"><span class="ui-icon ui-icon-' . $type . '"></span></li>' . PHP_EOL; }
	}
}

?>