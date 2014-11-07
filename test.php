<?php

require 'bower_components/requirephp/require.php';

require 'bower_components/nymph/src/Nymph.php';
RPHP::_('NymphConfig', array(), function(){
	return include 'config.php';
});

$NymphREST = RPHP::_('NymphREST');

require 'Game.php';

$newGame = new Game();
$time = microtime(true);
$newGame->generateBoard();
$timeTaken = microtime(true) - $time;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Nymph Sudoku Test</title>
</head>
<body>
	<h1>Nymph Sudoku Test</h1>
	<p>It took the Game class <?php echo $timeTaken; ?> seconds to generate this board:</p>
	<div>
		<table border="2">
			<tbody>
				<?php foreach ($newGame->board as $y => $curRow): ?>
				<tr>
					<?php foreach ($curRow as $x => $curVal): ?>
					<td style="width: 65px; height: 65px; text-align: center;"><?php echo "<small>($x, $y) =></small><br><b>$curVal</b>"; ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</body>
</html>