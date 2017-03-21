(function () {
	"use strict;"

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/card/card.html',
		controller: controller,
		controllerAs: 'discoverCardCtrl',
		bindings: {
			person: '<'
		}
	}

	angular
		.module('todevise')
		.component('discoverCard', component);
}());