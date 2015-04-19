<?php

require_once('classIncludes.php');

class Game {
	private $_arena;
	private $_players;			# array of usernames
	private $_currentPlayer;	# index of current player in _players
	private $_ships;			# $this->_ships['tfleming'] ==> ships belonging to tfleming

	private $_currentSettings = array(
		'buttons' => array(
			array("minusthick" => 'asdf',		"triangle-1-n" => 'moveUp',		"plusthick" => 'asdf',			"hidden" => 'asdf',	"check" => 'asdf'),
			array("triangle-1-w" => 'moveLeft',	"triangle-1-s" => 'moveDown',	"triangle-1-e" => 'moveRight',	"hidden" => 'asdf',	"comment" => 'asdf')),
		'message' => "Hey you, Player.<br />I got the Hocus Focus.",
		'content' => ""
	);

	public function __construct( array $kwargs ) {
		$this->_arena = new Arena();

		foreach ($kwargs as $user => $userStuff) {
			$this->_players[] = $user;
			$this->_ships[$user] = $userStuff['ships'];
		}
		$this->_currentPlayer = 0;
	}

	public function moveShip($username, $shipId, $deltaX, $deltaY) {
		if ( isset( $_ships[$username][$shipId] ) ) {
			# I NEED TO WRITE THIS
		} else {
			error_log('cannot move that ship: it does not exist');
		}
		
	}

	public function getCurrentPlayer() {	return $this->_players[$this->_currentPlayer];	}

	/* EVERYTHING ABOUT STYLE ===========================================>>> */

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
			foreach ($button_row as $buttonName => $whatItDoes) {
				$html = $html . $this->_uiButton($buttonName, $whatItDoes);
			}
			$html = $html . '</ul>' . PHP_EOL;
		}
		return $html;
	}

	# <li class="ui-state-default ui-corner-all [game-button"|" style=visibility:hidden;]">
	# <span class="ui-icon (ui-icon-TYPE)"></span>
	# </li>
	private function _uiButton($buttonName, $whatItDoes) {
		$firstBit = '<li class="ui-state-default ui-corner-all';
		if ($buttonName === 'hidden') {
			$secondBit = '" style="visibility: hidden;">';
			$span = '<span class="ui-icon"></span>';
		} else {
			$secondBit = ' game-button" title="action.php?action=buttonClicked&button=' . $whatItDoes . '">';
			$span = '<span class="ui-icon ui-icon-' . $buttonName . '"></span>';
		}
		return $firstBit . $secondBit . $span . '</li>' . PHP_EOL;
	}

	public function shipsToHTML() {
		$tileSize = $this->_arena->getTileSize();

		foreach ($this->_ships as $username => $ships) {
			foreach ($ships as $key => $ship) {
				$img_x_pos = ($tileSize * $ship['x']);
				$img_y_pos = ($tileSize * $ship['y']);
				$img_width = $ship['width'] * $tileSize;
				$img_height = $ship['height'] * $tileSize;
				$img_url = urlPath('img/' . $ship['sprite']);
				#$transform = ($ship['flipped'] ? "transform:scale(-1,1);" : "");
				$srcWidthHeight = "src=\"$img_url\" width=\"$img_width\" height=\"$img_height\"";
				$heightWidth = "width: $img_width; height: $img_height;";
				$imageStuff = "left: $img_x_pos; top: $img_y_pos; position: absolute;";#" $transform";
				$title = "title='action.php?action=shipClicked&username=$username&shipId=$key'";
				$background = "box-shadow: 3px 3px 5px #FFFFFF;";
				echo <<<EOT
				<img class="battleship game-button" $title $srcWidthHeight style="$heightWidth $imageStuff $background" />
EOT;
			}
		}
	}

	public function arenaToHTML() {		$this->_arena->toHTML();				}
	public function bodyStyle() {		return $this->_arena->bodyStyle();		}

	/* <<<=========================================== EVERYTHING ABOUT STYLE */
}

?>