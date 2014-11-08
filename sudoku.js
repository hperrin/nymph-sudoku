angular.module('sudokuApp', []).controller('SudokuController', ['$scope', function($scope) {
	$scope.games = [];
	$scope.sort = 'name';
	$scope.newGame = {
		player: '',
		difficulty: 1,
		loading: false
	};
	$scope.curGame = null;

	Nymph.getEntities({"class": 'Game'}, {"type": '&', "tag": 'game', "!tag": 'archived'}).then(function(games){
		if (games && games.length) {
			Nymph.sort(games, $scope.sort);
			$scope.games = games;
			$scope.$apply();
		}
	});

	$scope.startNewGame = function() {
		console.log("Player: ", $scope.newGame.player);
		if (typeof $scope.newGame.player === 'undefined' || $scope.newGame.player === '')
			return;
		console.log("Difficulty: ", $scope.newGame.difficulty);
		if ([1, 2, 3].indexOf($scope.newGame.difficulty) === -1)
			return;
		var game = new Game();
		game.set({
			'name': $scope.newGame.player + ' at ' + (new Date()).toLocaleString(),
			'difficulty': $scope.newGame.difficulty
		});
		$scope.newGame.loading = "Generating a new game board...";
		game.generateBoard().then(function(){
			$scope.newGame.loading = "Applying the difficulty level...";
			game.makeItFun().then(function(){
				$scope.newGame.loading = "Loading the new game...";
				game.save().then(function(game){
					$scope.games.push(game);
					$scope.newGame.player = '';
					$scope.newGame.difficulty = 1;
					$scope.games = Nymph.sort($scope.games, $scope.sort);
					$scope.curGame = game;
					$scope.newGame.loading = false;
					$scope.$apply();
				}, function(errObj){
					$scope.newGame.loading = false;
					$scope.$apply();
					alert("Error: "+errObj.textStatus);
				});
			}, function(errObj){
				$scope.newGame.loading = false;
				$scope.$apply();
				alert("Error: "+errObj.textStatus);
			});
		}, function(errObj){
			$scope.newGame.loading = false;
			$scope.$apply();
			alert("Error: "+errObj.textStatus);
		});
	};

	$scope.sortGames = function() {
		$scope.games = Nymph.sort($scope.games, $scope.sort);
		$scope.$apply();
	};

	$scope.save = function(game) {
		game.save().then(null, function(errObj){
			alert('Error: '+errObj.textStatus);
		});
	};

	$scope.loadGame = function(game) {
		$scope.curGame = game;
	};

	$scope.deleteGame = function(game) {
		var key = game.arraySearch($scope.games);
		game.delete().then(function(){
			if (key !== false)
				$scope.games.splice(key, 1);
			$scope.$apply();
		}, function(errObj){
			alert('Error: '+errObj.textStatus);
		});
	};

	$scope.archive = function() {
		var oldGames = $scope.games;
		$scope.games = [];
		angular.forEach(oldGames, function(game) {
			if (game.get('done')) {
				game.archive().then(function(success){
					if (!success)
						alert("Couldn't save changes to "+game.get('name'));
				}, function(errObj){
					alert("Error: "+errObj.textStatus+"\nCouldn't archive "+game.get('name'));
				});
			} else {
				$scope.games.push(game);
			}
		});
	};
}]);
