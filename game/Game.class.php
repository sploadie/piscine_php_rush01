<?php

require_once('classIncludes.php');

class Game {

	private $_arena;
	private $_players;			# array of usernames
	private $_currentPlayer;	# index of current player in _players
	private $_selectedShipId;	# currently selected ship
	private $_ships;			# $this->_ships['tfleming'] ==> ships belonging to tfleming
	private $_colors;			# color strings associated with each player
	private $_phase;			# 0 = order; 1 = move; 2 = fire

	public function __construct( array $kwargs ) {
		$this->_arena = new Arena();

		foreach ($kwargs as $user => $userStuff) {
			$this->_players[] = $user;
			$this->_ships[$user] = $userStuff['ships'];
			$this->_colors[$user] = $userStuff['color'];
		}
		$this->_currentPlayer = 0;
		$this->_selectedShipId = -1;

		# note: 'obstacles' is not in $this->_players
		$this->_ships['obstacles'] = array ( new Obstacle(20, 20, 10, 10)
											, new Obstacle(119, 69, 10, 10)
											, new Obstacle(119, 20, 10, 10)
											, new Obstacle(20, 69, 10, 10)
											, new Obstacle(60, 35, 30, 30) );
	}

	private function killShip($currentUsername) {
		# to put back if we do caching of all ship coordinates
		# $this->_ships[$currentUsername][$this->_selectedShipId]->die();
		unset($this->_ships[$currentUsername][$this->_selectedShipId]);
		$this->_selectedShipId = -1;
	}

	public function moveShip($currentUsername, $deltaX, $deltaY) {

		function doCoordinatesIntersect($firstCoordinates, $secondCoordinates) {
			foreach ($firstCoordinates as $first) {
				foreach ($secondCoordinates as $second) {
					if ($first['x'] === $second['x']
						&& $first['y'] === $second['y']) {
						return true;
					}
				}
			}
			return false;
		}

		function didCollisionOccur($ship, $thisShips
									, $thisSelectedShipId, $currentUsername) {
			$shipCoordinates = $ship->getShipCoordinates();
			foreach ( $thisShips as $username => $ships ) {
				foreach ( $ships as $shipId => $otherShip ) {
					# skip the current ship
					if ( ! ($username === $currentUsername
							&& $shipId == $thisSelectedShipId ) ) {
						$otherShipCoordinates = $otherShip->getShipCoordinates();
						if (doCoordinatesIntersect($otherShipCoordinates
													, $shipCoordinates)) {
							return true;
						}
					}
				}
			}
			return false;
		}

		function isInBounds($ship, $arena) {
			$shipCoordinates = $ship->getShipCoordinates();
			$arenaWidth = $arena->getWidth();
			$arenaHeight = $arena->getHeight();
			foreach ( $shipCoordinates as $coordinate ) {
				if ( $coordinate['x'] >= $arenaWidth
					|| $coordinate['y'] >= $arenaHeight
					|| $coordinate['x'] < 0
					|| $coordinate['y'] < 0 ) {
					return false;
				}
			}
			return true;
		}

		if ( isset( $this->_ships[$currentUsername][$this->_selectedShipId] ) ) {
			$ship = $this->_ships[$currentUsername][$this->_selectedShipId];
			if ( $ship['speed'] > 0) {
				$ship->move($deltaX, $deltaY);
				if ( didCollisionOccur($ship, $this->_ships, $this->_selectedShipId
										, $currentUsername)
						|| !isInBounds($ship, $this->_arena) ) {
					$this->killShip($currentUsername);
					error_log('The ship that moved ran into something. How sad.');
				}
				$ship->changeSpeed(-1);
			} else {
				error_log('that ship cannot move anymore');
			}	
		} else {
			error_log('cannot move that ship: it does not exist');
		}
	}

	public function shootFromShip($currentUsername, $deltaX, $deltaY) {
		error_log('shooting from ship!');
		# only fire if ammo, decrease it
	}

	# assumes there is at least one player
	public function nextPlayer() {
		$this->_currentPlayer++;
		if ( $this->_currentPlayer > count($this->_players) - 1 )
			$this->_currentPlayer = 0;
		$this->_selectedShipId = -1;
		$this->_phase = 0;
		error_log('changing players... new player: ' . $this->getCurrentPlayer());
	}

	public function nextPhase() {
		$this->_phase++;
		if ($this->_phase > 2) {
			$this->nextPlayer();
		}
	}

	public function shipsToHTML($currentUsername) {
		$tileSize = $this->_arena->getTileSize();

		# I don't like how broken up this HTML got
		# , but it was really long and annoyed me a lot
		# /poetry

		foreach ( $this->_ships as $username => $ships ) {
			foreach ( $ships as $shipId => $ship ) {
				$img_width = $ship['width'] * $tileSize;
				$img_height = $ship['height'] * $tileSize;
				$img_url = urlPath('img/' . $ship['sprite']);
				#$transform = ($ship['flipped'] ? "transform:scale(-1,1);" : "");
				
				$title = "title='action.php?action=shipClicked&username=$username&shipId=$shipId'";
				$srcWidthHeight = "src=\"$img_url\" width=\"$img_width\" height=\"$img_height\"";

				if ( $currentUsername === $this->getCurrentPlayer()
						&& $username === $currentUsername
						&& $this->_selectedShipId == $shipId ) {
					$shadowColor = '#FFFFFF';
				} else if ($username === 'obstacles') {
					$shadowColor = 'rgba(0, 0, 0, 0)';
				} else {
					$shadowColor = $this->_colors[$username];
				}

				# style stuff
				$heightWidth = "width: $img_width; height: $img_height;";
				$img_x_pos = ($tileSize * $ship['x']);
				$img_y_pos = ($tileSize * $ship['y']);
				$imageStuff = "left: $img_x_pos; top: $img_y_pos; position: absolute;";#" $transform";
				$background = "box-shadow: 0px 0px 10px 5px $shadowColor;";
				$style = "$heightWidth $imageStuff $background";

				echo <<<EOT
				<img class="battleship game-button" $title $srcWidthHeight style="$style" />
EOT;
			}
		}
	}

	public function printUserInterface($currentUsername) {
		# note: arrays can only have one of each key, meaming you can't have two blanks on the same line

		$orderShips = array(
			'buttons' => array(
				array("heart" => 'increaseHealth',	"arrow-4-diag" => 'increaseSpeed',	"hidden2" => 'asdf',	"hidden3" => 'asdf',	"check" => 'nextPlayer'),
				array("gear" => 'increaseShield',	"cart" => 'increaseAmmo',			"hidden1" => 'asdf',	"hidden2" => 'asdf',	"arrowstop-1-e" => 'nextPhase')),
			'message' => "Distribute PP: ", # insert PP here
			'content' => "SHIP STATS INSERTED HERE"
		);

		$moveShips = array(
			'buttons' => array(
				array("hidden1" => 'asdf',			"triangle-1-n" => 'moveUp',		"hidden2" => 'asdf',			"hidden3" => 'asdf',	"check" => 'nextPlayer'),
				array("triangle-1-w" => 'moveLeft',	"triangle-1-s" => 'moveDown',	"triangle-1-e" => 'moveRight',	"hidden1" => 'asdf',	"arrowstop-1-e" => 'nextPhase')),
			'message' => "Move your ship!",
			'content' => "SHIP STATS INSERTED HERE"
		);

		$fireShips = array(
			'buttons' => array(
				array("hidden1" => 'asdf',				"triangle-1-n" => 'shootUp',	"hidden2" => 'asdf',			"hidden3" => 'asdf',	"check" => 'nextPlayer'),
				array("triangle-1-w" => 'shootLeft',	"triangle-1-s" => 'shootUp',	"triangle-1-e" => 'shootRight',	"hidden1" => 'asdf',	"arrowstop-1-e" => 'nextPhase')),
			'message' => "Fire!",
			'content' => "SHIP STATS INSERTED HERE"
		);

		$noShipSelected = array(
			'buttons' => array(
				array("hidden1" => 'asdf',	"hidden2" => 'asdf',	"hidden3" => 'asdf',	"hidden4" => 'asdf',	"check" => 'nextPlayer'),
				array("hidden1" => 'asdf',	"hidden2" => 'asdf',	"hidden3" => 'asdf',	"hidden4" => 'asdf',	"hidden5" => 'asdf')),
			'message' => "Select a ship",
			'content' => ""
		);

		$notYourTurn = array(
			'buttons' => array(),
			'message' => "It's not your turn.",
			'content' => ""
		);

		if ( $currentUsername === $this->getCurrentPlayer() ) {
			# it's your turn!
			if ($this->_selectedShipId >= 0) {
				$ship = $this->_ships[$currentUsername][$this->_selectedShipId];
				$shipStats = "Health: " . $ship['health'] . '<br />';
				$shipStats = $shipStats . "Speed: " . $ship['speed'] . '<br />';
				$shipStats = $shipStats . "Shield: " . $ship['shield'] . '<br />';
				$shipStats = $shipStats . "Ammo: " . $ship['ammo'] . '<br />';
				$shipStats = $shipStats . "Damage: " . $ship['damage'] . '<br />';
				switch ($this->_phase) {
					case 0:
						$orderShips['content'] = $shipStats;
						$orderShips['message'] = $orderShips['message'] . $ship['power'];
						$currentSettings = $orderShips;
						break;
					case 1:
						$moveShips['content'] = $shipStats;
						$currentSettings = $moveShips;
						break;
					case 2:
						$fireShips['content'] = $shipStats;
						$currentSettings = $fireShips;
						break;
					default:
						trigger_error ( "Invalid phase in game.", E_USER_ERROR );
						break;
				}
			} else {
				$currentSettings = $noShipSelected;
			}
		} else {
			# not your turn
			$currentSettings = $notYourTurn;
		}

		$this->userInterfaceToHTML($currentSettings);
	}

	// SIMPLE FUNCTIONS ====================================================>>>

	public function getSelectedShip($currentUsername) {
		return $this->_ships[$currentUsername][$this->_selectedShipId];
	}

	public function setSelectedShipId($id) {
		$this->_selectedShipId = $id;
	}

	public function arenaToHTML() {			$this->_arena->toHTML();										}
	public function bodyStyle() {			return $this->_arena->bodyStyle();								}
	public function getPhase() {			return $this->_phase;											}
	public function getCurrentPlayer() {	return $this->_players[$this->_currentPlayer];					}

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
					$realName = $buttonName;
					if ( substr($realName, 0, 5) === "blank" ) {
						$realName = "blank";
					} else if ( substr($realName, 0, 6) === "hidden" ) {
						$realName = "hidden";
					}
					$html = $html . uiButton($realName, $whatItDoes);
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