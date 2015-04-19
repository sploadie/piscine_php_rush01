<?php

require_once('classIncludes.php');
require_once("../url.php");

final class Arena {

	private static $uniqueId;

	private $_width;
	private $_height;
	private $_tile_size;

	# referenced like this: $_ships['username'] ==> list of ships belonging to that player
	private $_ships = array();

	public function __construct() {
		$this->_width = 150;
		$this->_height = 100;
		$this->_tile_size = 10;
		$this->uniqueId = 0;
	}

	public function setUserShips($user, $ships) {
		$this->_ships[$user] = $ships;
	}

	public function toHTML() {
		$div_size = $this->_tile_size - 1;

		echo <<<EOT
		<style>
		.tile {
			border: 1px solid #aaaaaa;
			margin: 0px;
			padding: 0px;
			border-collapse: collapse;
		}
		#tiles {
			left: 0px;
			top: 0px;
			position: absolute;
			margin: 0px;
			padding: 0px;
			border-collapse: collapse;
		}
		</style>
		<table id="tiles">
EOT;
		$j = 0;
		while ($j < $this->_height) {
			echo '<tr >' . PHP_EOL;
			$i = 0;
			while ($i < $this->_width) {
				echo <<<EOT
				<td class="tile">
					<div style="width: $div_size; height: $div_size;"></div>
				</td>
EOT;
				$i++;
			}
			echo '</tr>' . PHP_EOL;
			$j++;
		}
		echo '</table>' . PHP_EOL;
	}

	public function shipsToHTML() {
		foreach ($this->_ships as $username => $ships) {
			foreach ($ships as $key => $ship) {
				$img_x_pos = ($this->_tile_size * $ship['x']);
				$img_y_pos = ($this->_tile_size * $ship['y']);
				$img_width = $ship['width'] * $this->_tile_size;
				$img_height = $ship['height'] * $this->_tile_size;
				$img_url = urlPath('img/' . $ship['sprite']);
				#$transform = ($ship['flipped'] ? "transform:scale(-1,1);" : "");
				$srcWidthHeight = "src=\"$img_url\" width=\"$img_width\" height=\"$img_height\"";
				$heightWidth = "width: $img_width; height: $img_height;";
				$imageStuff = "left: $img_x_pos; top: $img_y_pos; position: absolute;";#" $transform";
				$title = "title='action.php?action=shipClicked&username=$username&shipId=$key'";
				echo <<<EOT
				<img class="battleship game-button" $title $srcWidthHeight style="$heightWidth $imageStuff" />
EOT;
			}
		}
	}

	public function moveShip($username, $shipId, $deltaX, $deltaY) {
		if ( isset( $_ships[$username][$shipId] ) ) {
			
		} else {
			error_log('cannot move that ship: it does not exist');
		}
		
	}

	public function getWidth()		{ return $this->_width;		}
	public function getHeight()		{ return $this->_height;	}
	public function getTileSize()	{ return $this->_tile_size;	}
}

?>