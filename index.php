<!doctype html>
<html>
	<head>
		<title>Nymph Sudoku</title>
		<script type="text/javascript">
			(function(){
				var s = document.createElement("script"); s.setAttribute("src", "https://www.promisejs.org/polyfills/promise-5.0.0.min.js");
				(typeof Promise !== "undefined" && typeof Promise.all === "function") || document.getElementsByTagName('head')[0].appendChild(s);
			})();
			NymphOptions = {
				restURL: 'rest.php'
			};
		</script>
		<script src="bower_components/nymph/src/Nymph.js"></script>
		<script src="bower_components/nymph/src/Entity.js"></script>
		<script src="Game.js"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js"></script>
		<script src="sudoku.js"></script>
		<link rel="stylesheet" href="sudoku.css">

		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body ng-app="sudokuApp">
		<div class="page-header">
			<h1>Nymph Sudoku <small>by Hunter Perrin</small></h1>
		</div>
		<div ng-controller="SudokuController">
			<div ng-if="!curGame && !uiState.loading" class="game-selector">
				<form class="column" ng-submit="startNewGame($scope)">
					<div>
						<h3>Start a New Game</h3>
						<div class="form-controls cf">
							<label>
								<span>Who's playing?</span>
								<input class="form-control" type="text" ng-model="uiState.player" size="30" placeholder="player name" />
							</label>
						</div>
						<div class="form-controls cf">
							<span>How tough are you?</span>
							<div class="form-control">
								<label>
									<input type="radio" name="difficulty" ng-model="uiState.difficulty" ng-value="1" /> Practically a baby.
								</label><br>
								<label>
									<input type="radio" name="difficulty" ng-model="uiState.difficulty" ng-value="2" /> I've got some hair on my chest.
								</label><br>
								<label>
									<input type="radio" name="difficulty" ng-model="uiState.difficulty" ng-value="3" /> I eat cactus with the spines.
								</label>
							</div>
						</div>
						<div class="form-controls cf">
							<div ng-show="uiState.player">{{uiState.player}} wants {{[0, 'an easy', 'a fair', 'a hard'][uiState.difficulty]}} game.</div>
							<button type="submit">Bring it On!</button>
						</div>
					</div>
				</form>
				<div class="column" ng-if="uiState.games.length">
					<div>
						<h3>Saved Games</h3>
						<div ng-show="uiState.games.length > 1">
							Sort: <br>
							<label style="font-weight: normal;">
								<input type="radio" ng-model="uiState.sort" ng-change="sortGames()" name="sort" value="cdate"> Started</label>
							&nbsp;&nbsp;&nbsp;
							<label style="font-weight: normal;">
								<input type="radio" ng-model="uiState.sort" ng-change="sortGames()" name="sort" value="name"> Alpha</label>
						</div>
						<div ng-repeat="game in uiState.games" class="game game-{{game.data.done ? 'complete' : 'ongoing'}} cf">
							<span>{{game.data.name}}</span>
							<span>{{game.data.time}}</span>
							<span>({{[0, 'Easy', 'Medium', 'Hard'][game.data.difficulty]}})</span>
							<div class="actions">
								<button ng-if="!game.data.done" type="button" ng-click="loadGame(game)">Continue</button>
								&nbsp;&nbsp;&nbsp;
								<button type="button" ng-click="deleteGame(game)">Delete</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div ng-if="uiState.loading" class="loading">
				<i class="fa fa-spin fa-spinner"></i><br>
				{{uiState.loading}}
			</div>
			<div ng-if="curGame" class="game-play">
				<div ng-show="saving" class="saving">
					<i class="fa fa-spin fa-spinner"></i> Saving
				</div>
				<h3>Current Game: {{curGame.data.name}}</h3>
				<div class="column">
					<div class="game-board">
						<div class="row" ng-repeat="(y, row) in curGame.data.board track by $index">
							<div class="square square-{{curGame.data.playBoard[y][x] ? 'preset' : 'open'}}" ng-repeat="(x, square) in row track by $index">
								<div ng-if="curGame.data.playBoard[y][x]">
									{{square}}
								</div>
								<input ng-if="!curGame.data.playBoard[y][x]" ng-model="curGame.data.board[y][x]" ng-pattern="/^[1-9]$/" ng-change="saveState()" />
							</div>
						</div>
					</div>
				</div>
				<div class="game-options column">
					<div class="form-controls cf">
						<button type="button" ng-click="curGame.data.board = curGame.data.playBoard">Gah! Let me start over.</button>
						<button type="button" ng-click="curGame.hint()">I could really use a hint here.</button>
					</div>
					<div class="form-controls cf">
						<span>You want some help?</span>
						<div class="form-control">
							<label>
								<input type="radio" name="help" ng-model="curGame.help" ng-value="1" /> No way, I totally got this.
							</label><br>
							<label>
								<input type="radio" name="help" ng-model="curGame.help" ng-value="2" /> Just tell me if I make an obvious mistake.
							</label><br>
							<label>
								<input type="radio" name="help" ng-model="curGame.help" ng-value="3" /> Tell me if I play something that's wrong.
							</label>
						</div>
					</div>
					<div class="form-controls cf">
						<button type="button" ng-click="clearGame()">{{curGame.data.done ? 'Who\'s The Man' : 'I need a break.'}}</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
