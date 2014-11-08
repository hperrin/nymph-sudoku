// Uses AMD or browser globals.
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as a module.
        define('NymphGame', ['NymphEntity'], factory);
    } else {
        // Browser globals
        factory(Entity);
    }
}(function(Entity){
	Game = function(id){
		this.constructor.call(this, id);
		this.addTag('game');
		this.data.difficulty = 1;
		this.data.board = [
			[], [], [], [], [], [], [], [], [], []
		];
		this.data.done = false;
	};
	Game.prototype = new Entity();

	var thisClass = {
		// === The Name of the Class ===
		class: 'Game',

		// === Class Variables ===
		etype: "game",
		mistakes: [
			[], [], [], [], [], [], [], [], [], []
		],
		help: 1,

		// === Class Methods ===

		generateBoard: function(){
			return this.serverCall('generateBoard', arguments);
		},

		makeItFun: function(){
			return this.serverCall('makeItFun', arguments);
		},

		calculateErrors: function(){

		}
	};
	for (var p in thisClass) {
		Game.prototype[p] = thisClass[p];
	}

	return Game;
}));
