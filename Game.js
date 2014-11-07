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
		this.difficulty = 1;
		this.board = [
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

		// === Class Methods ===

		archive: function(){
			return this.serverCall('archive', arguments);
		}
	};
	for (var p in thisClass) {
		Game.prototype[p] = thisClass[p];
	}

	return Game;
}));
