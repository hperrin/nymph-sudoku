<?php
/**
 * @property string $name The game's text.
 * @property int $difficulty Game's difficulty from 1-4, 1 being easiest.
 * @property array $board The game board.
 * @property int $time The time the user has spent playing, in seconds.
 * @property bool $done Whether it's done.
 */
class Game extends Entity {
	const etype = 'game';
	public $clientEnabledMethods = array('generateBoard');

	public function __construct($id = 0) {
		$this->addTag('game');
		$this->difficulty = 1;
		// In the board, if a value is an integer, that means it was preset by
		// the game. If it's a string, that means it was provided by the user.
		$this->board = array(
			0 => array(),
			1 => array(),
			2 => array(),
			3 => array(),
			4 => array(),
			5 => array(),
			6 => array(),
			7 => array(),
			8 => array(),
			9 => array(),
		);
		$this->done = false;
		parent::__construct($id);
	}

	public function info($type) {
		if ($type == 'name' && isset($this->name))
			return $this->name;
		elseif ($type == 'type')
			return 'game';
		elseif ($type == 'types')
			return 'games';
		return null;
	}

	public function generateBoard() {
		// Since we know there's nothing on the board, we can at least fill in
		// one row randomly.
		$firstRow = range(1, 9);
		shuffle($firstRow);
		for ($y = 0; $y <= 8; $y++) {
			$this->board[0][$y] = $firstRow[$y];
		}

		// Oh there has to be a better way to do this, but in the interest of
		// time, I'm basically going to brute force a board together.
		for ($x = 1; $x <= 8; $x++) {
			for ($y = 0; $y <= 8; $y++) {
				$options = $this->optionsLeft($x, $y);
				if (!$options) {
					$this->board[$x] = array();
					$y = -1;
					continue;
				}
				$this->board[$x][$y] = $options[array_rand($options)];
			}
		}
	}

	private function optionsLeft($x, $y) {
		$taken = array_merge($this->neighborsX($x, $y), $this->neighborsY($x, $y), $this->neighborsSquare($x, $y));
		$notTaken = array_diff(array(1, 2, 3, 4, 5, 6, 7, 8, 9), $taken); // Not calling range() because functions are costly in PHP.
		return $notTaken;
	}

	private function neighborsY($x, $y) {
		$results = array();
		for ($x2 = 0; $x2 <= 8; $x2++) {
			if ($x === $x2)
				continue;
			if (isset($this->board[$x2][$y]))
				$results[] = $this->board[$x2][$y];
		}
		return $results;
	}
	private function neighborsX($x, $y) {
		$results = array();
		for ($y2 = 0; $y2 <= 8; $y2++) {
			if ($y === $y2)
				continue;
			if (isset($this->board[$x][$y2]))
				$results[] = $this->board[$x][$y2];
		}
		return $results;
	}
	private function neighborsSquare($x, $y) {
		$results = array();
		$minX = $x - ($x % 3);
		$minY = $y - ($y % 3);
		for ($x2 = $minX; $x2 <= $minX+2; $x2++) {
			for ($y2 = $minY; $y2 <= $minY+2; $y2++) {
				if ($x2 === $x && $y2 === $y)
					continue;
				if (isset($this->board[$x2][$y2]))
					$results[] = $this->board[$x2][$y2];
			}
		}
		return $results;
	}
}
