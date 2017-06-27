(function () {
	"use strict;"

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/person/card/card.html',
		controller: controller,
		controllerAs: 'discoverCardCtrl',
		bindings: {
			person: '<'
		}
	}

	angular
		.module('discover')
		.component('discoverCard', component);
}());