(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	angular.module('todevise', ['global-deviser', 'global-desktop'])
		.controller('editPressCtrl', controller);

}());