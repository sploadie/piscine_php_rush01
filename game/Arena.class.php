<?php

require_once("../url.php");

final class Arena {
	private $_width  = 150;
	private $_height = 100;
	private $_tile_size = 10;
	private $_arena = array();
	private $_ships = array();

	public function __construct() {
		$this->_cleanArena();
		// echo "Arena __constructed." . PHP_EOL;
	}

	private function _cleanArena() {
		$i = 0;
		while ($i < $this->_width) {
			$this->_arena[$i] = array();
			$j = 0;
			while ($j < $this->_height) {
				$this->_arena[$i][$j] = null;
				$j++;
			}
			$i++;
		}
	}

	public function addShips(array $ships) {
		foreach ($ships as $ship) {
			if (!is_subclass_of($ship, 'Battleship'))
				trigger_error ( "Ship is not a Battleship", E_USER_ERROR );
			$this->_ships[] = $ship;
		}
		$this->update();
	}

	public function at($x, $y) {
		if (!is_int($x) || !is_int($y))
			trigger_error ( "Incorrect Coordinates", E_USER_ERROR );
		if ($x < 0 || $x >= $this->_width || $y < 0 || $y >= $this->_height)
			trigger_error ( "Coordinates out of bounds", E_USER_ERROR );
		return $this->_arena[$x][$y];
	}

	public function update() {
		$this->_cleanArena();
		foreach ($this->_ships as $ship) {
			$i = 0;
			while ($i < $ship['width']) {
				$j = 0;
				while ($j < $ship['height']) {
					//ADD COLLISION HANDLING
					//ADD OUT OF BOUNDS HANDLING
					$this->_arena[$ship['x'] + $i][$ship['y'] + $j] = $ship;
					$j++;
				}
				$i++;
			}
		}
	}

	public function toHTML() {
		$div_size = $this->_tile_size - 1;
		echo '<table id="tiles" style="left: 0px; top: 0px; position: absolute; margin: 0px; padding: 0px; border-collapse: collapse;">' . PHP_EOL;
		$j = 0;
		while ($j < $this->_height) {
			echo '<tr >' . PHP_EOL;
			$i = 0;
			while ($i < $this->_width) {
				echo <<<EOT
				<td class="tile" style="border: 1px solid #aaaaaa; margin: 0px; padding: 0px; border-collapse: collapse;">
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
		foreach ($this->_ships as $ship) {
			$img_x_pos = ($this->_tile_size * $ship['x']);
			$img_y_pos = ($this->_tile_size * $ship['y']);
			$img_width = $ship['width'] * $this->_tile_size;
			$img_height = $ship['height'] * $this->_tile_size;
			$img_url = urlPath('img/' . $ship->getDefault('sprite'));
			$transform = ($ship['flipped'] ? "transform:scale(-1,1);" : "");
			echo <<<EOT
			<img class="battleship" src="$img_url" width="$img_width" height="$img_height" style="width: $img_width; height: $img_height; left: $img_x_pos; top: $img_y_pos; position: absolute; $transform" />
EOT;
		}
	}

	public function getWidth()		{ return $this->_width;		}
	public function getHeight()		{ return $this->_height;	}
	public function getTileSize()	{ return $this->_tile_size;	}
}

?>