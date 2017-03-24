(function () {
	"use strict;"

	function controller() {
		var vm = this;
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/results/results.html',
		controller: controller,
		controllerAs: 'discoverResultsCtrl',
		bindings: {
			results: '<'
		}
	}

	angular
		.module('todevise')
		.component('discoverResults', component);

}());