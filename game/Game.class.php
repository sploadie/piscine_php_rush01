<?php

require_once('classIncludes.php');

class Game {
	private $_arena;
	private $_currentPlayer;

	private $_currentSettings = array(
		'buttons' => array(
			array("minusthick",		"triangle-1-n",	"plusthick",	"hidden",	"check"),
			array("triangle-1-w",	"triangle-1-s",	"triangle-1-e",	"hidden",	"comment")),
		'message' => "Hey you, Player.<br />I got the Hocus Focus.",
		'content' => ""
	);

	public function __construct( array $userShips ) {
		$this->_arena = new Arena();

		$this->_currentPlayer = "tfleming";

		foreach ($userShips as $user => $ships) {
			$this->_arena->setUserShips($user, $ships);
		}
	}

	public function bodyStyle() {
		$body_width = $this->_arena->getWidth() * $this->_arena->getTileSize() + 200;
		$body_height = $this->_arena->getHeight() * $this->_arena->getTileSize() + 200;
		return "padding: 100px 0px 0px 100px; width: " . $body_width . "px; height: " . $body_height . "px;";
	}

	public function controlUiToHTML() {
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

	# <li class="ui-state-default ui-corner-all [game-button"|" style=visibility:hidden;]">
	# <span class="ui-icon (ui-icon-TYPE)"></span>
	# </li>
	private function _uiButton($type) {
		$firstBit = '<li class="ui-state-default ui-corner-all';
		if ($type === 'hidden') {
			$secondBit = '" style="visibility: hidden;">';
			$span = '<span class="ui-icon"></span>';
		} else {
			$secondBit = ' game-button" title="action.php?action=buttonClicked&button=' . $type . '">';
			$span = '<span class="ui-icon ui-icon-' . $type . '"></span>';
		}
		return $firstBit . $secondBit . $span . '</li>' . PHP_EOL;
	}

	public function arenaToHTML() {			$this->_arena->toHTML();		}
	public function shipsToHTML() {			$this->_arena->shipsToHTML();	}
	public function getCurrentPlayer() {	return $this->_currentPlayer;	}

	public function __call($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        error_log( "Calling object method '$name' "
             . implode(', ', $arguments). "\n");
    }
}

?>