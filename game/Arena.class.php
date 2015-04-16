<?php
require_once('url.php');

final class Arena {
	private $_width  = 150;
	private $_height = 100;
	private $_tile_size = 20;
	private $_arena = array();
	private $_ships = array();

	use urlTools;

	public function __construct() {
		$this->_cleanArena();
		echo "Arena __constructed." . PHP_EOL;
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
		// var_dump($this->_ships);
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
		$div_size = $this->_tile_size - 2;
		//OPEN SPACE
		echo <<<EOT
<div id="space" style="position: relative;">
EOT;
		//DRAW BATTLESHIPS
		foreach ($this->_ships as $ship) {
			$img_x_pos = ($this->_tile_size * $ship['x']);
			$img_y_pos = ($this->_tile_size * $ship['y']);
			$img_width = $ship['width'] * $this->_tile_size;
			$img_height = $ship['height'] * $this->_tile_size;
			$img_url = $this->_url('img/' . $ship->getDefault('sprite'));
			echo <<<EOT
	<img class="battleship" src="$img_url" width="$img_width" height="$img_height" style="width: $img_width; height: $img_height; left: $img_x_pos; top: $img_y_pos; position: absolute;"></img>
EOT;
		}
		//DRAW EMPTIES
		foreach ($this->_arena as $x => $column) {
			foreach ($column as $y => $tile) {
				if (!is_null($tile))
					continue;
				$div_x_pos = ($this->_tile_size * $x) + 1;
				$div_y_pos = ($this->_tile_size * $y) + 1;
				echo <<<EOT
	<div class="empty_tile" style="width: $div_size; height: $div_size; left: $div_x_pos; top: $div_y_pos; position: absolute; background-color: black;"></div>
EOT;
			}
		}
		//CLOSE SPACE
		echo <<<EOT
</div>
EOT;
	}
}

?>