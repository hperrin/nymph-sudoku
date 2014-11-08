angular.module('sudokuApp', []).controller('SudokuController', ['$scope', function($scope) {
	$scope.uiState = {
		player: '',
		difficulty: 1,
		loading: false,
		sort: 'cdate',
		games: []
	};
	$scope.curGame = null;

	Nymph.getEntities({"class": 'Game'}, {"type": '&', "tag": 'game', "!tag": 'archived'}).then(function(games){
		if (games && games.length) {
			Nymph.sort(games, $scope.uiState.sort);
			$scope.uiState.games = games;
			$scope.$apply();
		}
	});

	$scope.startNewGame = function() {
		console.log("Player: ", $scope.uiState.player);
		if (typeof $scope.uiState.player === 'undefined' || $scope.uiState.player === '')
			return;
		console.log("Difficulty: ", $scope.uiState.difficulty);
		if ([1, 2, 3].indexOf($scope.uiState.difficulty) === -1)
			return;
		var game = new Game();
		game.set({
			'name': $scope.uiState.player + ' at ' + (new Date()).toLocaleString(),
			'difficulty': $scope.uiState.difficulty
		});
		$scope.uiState.loading = "Generating a new game board...";
		game.generateBoard().then(function(){
			$scope.uiState.loading = "Applying the difficulty level...";
			$scope.$apply();
			game.makeItFun().then(function(){
				$scope.uiState.loading = "Loading the new game...";
				$scope.$apply();
				game.save().then(function(game){
					$scope.uiState.games.push(game);
					$scope.uiState.player = '';
					$scope.uiState.difficulty = 1;
					$scope.uiState.games = Nymph.sort($scope.uiState.games, $scope.uiState.sort);
					$scope.curGame = game;
					$scope.uiState.loading = false;
					$scope.$apply();
				}, function(errObj){
					$scope.uiState.loading = false;
					$scope.$apply();
					alert("Error: "+errObj.textStatus);
				});
			}, function(errObj){
				$scope.uiState.loading = false;
				$scope.$apply();
				alert("Error: "+errObj.textStatus);
			});
		}, function(errObj){
			$scope.uiState.loading = false;
			$scope.$apply();
			alert("Error: "+errObj.textStatus);
		});
	};

	$scope.sortGames = function() {
		$scope.uiState.games = Nymph.sort($scope.uiState.games, $scope.uiState.sort);
	};

	$scope.saveState = function() {
		$scope.saving = true;
		$scope.curGame.save().then(function(){
			$scope.saving = false;
			$scope.$apply();
		}, function(errObj){
			$scope.saving = false;
			$scope.$apply();
			alert('Error: '+errObj.textStatus);
		});
	};

	$scope.loadGame = function(game) {
		$scope.curGame = game;
	};

	$scope.clearGame = function(){
		$scope.saveState();
		$scope.curGame = null;
	};

	$scope.deleteGame = function(game) {
		if (!confirm('Are you sure?'))
			return;
		var key = game.arraySearch($scope.uiState.games);
		game.delete().then(function(){
			if (key !== false)
				$scope.uiState.games.splice(key, 1);
			$scope.$apply();
		}, function(errObj){
			alert('Error: '+errObj.textStatus);
		});
	};

	$scope.archive = function() {
		var oldGames = $scope.uiState.games;
		$scope.uiState.games = [];
		angular.forEach(oldGames, function(game) {
			if (game.get('done')) {
				game.archive().then(function(success){
					if (!success)
						alert("Couldn't save changes to "+game.get('name'));
				}, function(errObj){
					alert("Error: "+errObj.textStatus+"\nCouldn't archive "+game.get('name'));
				});
			} else {
				$scope.uiState.games.push(game);
			}
		});
	};
}]);
