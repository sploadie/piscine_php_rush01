<?php

require_once('classIncludes.php');
require_once("../url.php");

final class Arena {

	private $_width;
	private $_height;
	private $_tileSize;

	public static function doc()
	{
	    return file_get_contents('./Arena.doc.txt') . PHP_EOL;
	}

	public function __construct() {
		$this->_width = 150;
		$this->_height = 100;
		$this->_tileSize = 9;
	}

	public function toHTML() {
		$div_size = $this->_tileSize - 1;

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

	public function bodyStyle() {
		$bodyWidth = $this->_width * $this->_tileSize + 200;
		$bodyHeight = $this->_width * $this->_tileSize + 200;
		return "padding: 50px 50px 50px 50px; width: " . $bodyWidth . "px; height: " . $bodyHeight . "px;";
	}

	public function getWidth()		{ return $this->_width;		}
	public function getHeight()		{ return $this->_height;	}
	public function getTileSize()	{ return $this->_tileSize;	}
}

?>