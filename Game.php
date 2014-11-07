<?php
/**
 * @property string $name The game's text.
 * @property int $difficulty Game's difficulty from 1-4, 1 being easiest.
 * @property array $board The game board.
 * @property array $solvedBoard The complete game board.
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
		for ($x = 0; $x <= 8; $x++) {
			$this->board[0][$x] = $firstRow[$x];
		}
		$firstBlockAffinity = array($this->board[0][6], $this->board[0][7], $this->board[0][8]);
		$secondBlockAffinity = array($this->board[0][0], $this->board[0][1], $this->board[0][2]);
		$thirdBlockAffinity = array($this->board[0][3], $this->board[0][4], $this->board[0][5]);

		// Oh there has to be a better way to do this, but in the interest of
		// time, I'm basically going to brute force a board together.
		for ($y = 1; $y <= 8; $y++) {
			$rowAttemts = 0;
			for ($x = 0; $x <= 8; $x++) {
				$options = $this->optionsLeft($x, $y);
				// Let's find our affinity.
				switch ($x) {
					case 0:
					case 1:
					case 2:
						$affinity = $firstBlockAffinity;
						break;
					case 3:
					case 4:
					case 5:
						$affinity = $secondBlockAffinity;
						break;
					case 6:
					case 7:
					case 8:
						$affinity = $thirdBlockAffinity;
						break;
				}
				$affinityOptions = array_intersect($affinity, $options);
				// If we can use a value from our affinity values, let's use it.
				if ($affinityOptions)
					$options = $affinityOptions;

				// Do we have options?
				if (!$options) {
					$rowAttemts++;
					// If we've been going at it for a while, just give up and
					// try again.
					if ($rowAttemts > 15) {
						$this->board = array(0 => array(),1 => array(),2 => array(),3 => array(),4 => array(),5 => array(),6 => array(),7 => array(),8 => array(),9 => array());
						return $this->generateBoard();
					}
					$this->board[$y] = array();
					$x = -1;
					continue;
				}

				$this->board[$y][$x] = $options[array_rand($options)];
			}
			$firstBlockAffinity = array($this->board[$y][6], $this->board[$y][7], $this->board[$y][8]);
			$secondBlockAffinity = array($this->board[$y][0], $this->board[$y][1], $this->board[$y][2]);
			$thirdBlockAffinity = array($this->board[$y][3], $this->board[$y][4], $this->board[$y][5]);
		}

		// Cool, our board is done. Now let's keep the solved board.
		$this->solvedBoard = $this->board;
	}

	private function optionsLeft($x, $y) {
		$taken = array_merge($this->neighborsX($x, $y), $this->neighborsY($x, $y), $this->neighborsSquare($x, $y));
		$notTaken = array_diff(array(1, 2, 3, 4, 5, 6, 7, 8, 9), $taken); // Not calling range() because functions are costly in PHP.
		return $notTaken;
	}

	private function neighborsY($x, $y) {
		$results = array();
		for ($y2 = 0; $y2 <= 8; $y2++) {
			if ($y === $y2)
				continue;
			if (isset($this->board[$y2][$x]))
				$results[] = $this->board[$y2][$x];
		}
		return $results;
	}
	private function neighborsX($x, $y) {
		$results = array();
		for ($x2 = 0; $x2 <= 8; $x2++) {
			if ($x === $x2)
				continue;
			if (isset($this->board[$y][$x2]))
				$results[] = $this->board[$y][$x2];
		}
		return $results;
	}
	private function neighborsSquare($x, $y) {
		$results = array();
		$minX = $y - ($y % 3);
		$minY = $x - ($x % 3);
		for ($y2 = $minX; $y2 <= $minX+2; $y2++) {
			for ($x2 = $minY; $x2 <= $minY+2; $x2++) {
				if ($y2 === $y && $x2 === $x)
					continue;
				if (isset($this->board[$y2][$x2]))
					$results[] = $this->board[$y2][$x2];
			}
		}
		return $results;
	}
}
