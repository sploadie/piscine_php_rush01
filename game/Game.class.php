<?php

require_once('classIncludes.php');

class Game {

	private $_arena;
	private $_players;			# array of usernames
	private $_currentPlayer;	# index of current player in _players
	private $_selectedShipId;	# currently selected ship
	private $_ships;			# $this->_ships['tfleming'] ==> ships belonging to tfleming
	private $_colors;			# color strings associated with each player

	public function __construct( array $kwargs ) {
		$this->_arena = new Arena();

		foreach ($kwargs as $user => $userStuff) {
			$this->_players[] = $user;
			$this->_ships[$user] = $userStuff['ships'];
			$this->_colors[$user] = $userStuff['color'];
		}
		$this->_currentPlayer = 0;
		$this->_selectedShipId = -1;

		$this->_ships['obstacles'] = array ( new Obstacle(20, 20, 10, 10)
											, new Obstacle(119, 69, 10, 10)
											, new Obstacle(119, 20, 10, 10)
											, new Obstacle(20, 69, 10, 10)
											, new Obstacle(60, 35, 30, 30) );
	}

	public function moveShip($username, $shipId, $deltaX, $deltaY) {
		if ( isset( $_ships[$username][$shipId] ) ) {
			# I NEED TO WRITE THIS
		} else {
			error_log('cannot move that ship: it does not exist');
		}
	}

	# assumes there is at least one player
	public function nextPlayer() {
		$this->_currentPlayer++;
		if ( $this->_currentPlayer > count($this->_players) - 1 )
			$this->_currentPlayer = 0;
		$this->_selectedShipId = -1;
		error_log('changing players... new player: ' . $this->getCurrentPlayer());
	}

	public function printUserInterface($currentUsername) {
		$currentSettings = array(
			'buttons' => array(
				array("minusthick" => 'asdf',		"triangle-1-n" => 'moveUp',		"plusthick" => 'asdf',			"hidden" => 'asdf',	"check" => 'nextPlayer'),
				array("triangle-1-w" => 'moveLeft',	"triangle-1-s" => 'moveDown',	"triangle-1-e" => 'moveRight',	"hidden" => 'asdf',	"comment" => 'asdf')),
			'message' => "Hey you, Player.<br />I got the Hocus Focus.",
			'content' => ""
		);
		$this->userInterfaceToHTML($currentSettings);
	}

	public function shipsToHTML($currentUsername) {
		var_dump($this->_arena);
		var_dump(get_class_methods($this->_arena));
/*		$tileSize = $this->_arena->bodyStyle();
*/		$tileSize = 10;#$this->_arena->getTileSize();

		foreach ($this->_ships as $username => $ships) {
			foreach ($ships as $key => $ship) {
				$img_width = $ship['width'] * $tileSize;
				$img_height = $ship['height'] * $tileSize;
				$img_url = urlPath('img/' . $ship['sprite']);
				#$transform = ($ship['flipped'] ? "transform:scale(-1,1);" : "");
				$srcWidthHeight = "src=\"$img_url\" width=\"$img_width\" height=\"$img_height\"";
				$heightWidth = "width: $img_width; height: $img_height;";

				$img_x_pos = ($tileSize * $ship['x']);
				$img_y_pos = ($tileSize * $ship['y']);
				$imageStuff = "left: $img_x_pos; top: $img_y_pos; position: absolute;";#" $transform";

				$title = "title='action.php?action=shipClicked&username=$username&shipId=$key'";

				if ( $currentUsername === $this->getCurrentPlayer()
						&& $username === $currentUsername
						&& $this->_selectedShipId == $key ) {
					$shadowColor = '#FFFFFF';
				} else if ($username === 'obstacles') {
					$shadowColor = 'rgba(0, 0, 0, 0)';
				} else {
					$shadowColor = $this->_colors[$username];
				}
				
				$background = "box-shadow: 0px 0px 10px 5px $shadowColor;";
				echo <<<EOT
				<img class="battleship game-button" $title $srcWidthHeight style="$heightWidth $imageStuff $background" />
EOT;
			}
		}
	}

	// SIMPLE FUNCTIONS ====================================================>>>

	public function arenaToHTML() {		$this->_arena->toHTML();				}
	public function bodyStyle() {		return $this->_arena->bodyStyle();		}

	public function setSelectedShipId($id) {
		$this->_selectedShipId = $id;
	}

	public function getCurrentPlayer() {	return $this->_players[$this->_currentPlayer];	}

	// STATIC FUNCTIONS ====================================================>>>

	private static function userInterfaceToHTML($settings) {

		# <li class="ui-state-default ui-corner-all [game-button"|" style=visibility:hidden;]">
		# <span class="ui-icon (ui-icon-TYPE)"></span>
		# </li>
		function uiButton($buttonName, $whatItDoes) {
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

		function uiInterface(array $buttons) {
			$html = "";
			foreach ($buttons as $button_row) {
				$html = $html . '<ul id="icons" class="ui-widget ui-helper-clearfix">' . PHP_EOL;
				foreach ($button_row as $buttonName => $whatItDoes) {
					$html = $html . uiButton($buttonName, $whatItDoes);
				}
				$html = $html . '</ul>' . PHP_EOL;
			}
			return $html;
		}

		$content = $settings['content'];
		$message = $settings['message'];
		$interface = uiInterface($settings['buttons']);
		echo <<<EOT
			$content
			<div class="ui-dialog-content ui-widget-content" style="background: none; border: 0;">
				<p>$message</p>
			</div>
			$interface
EOT;
	}

}

?>