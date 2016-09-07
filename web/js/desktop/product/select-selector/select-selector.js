(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	function directive() {
		return {
			templateUrl: currentHost() + '/js/desktop/product/select-selector/select-selector.html',
			controller: controller,
			controllerAs: 'selectSelectorCtrl',
			bindToController: true,
			require: '^form',
			scope: {
				option: '=',
				optionsSelected: '=',
				getReferences: '&'
			}
		}
	}

	angular.module('todevise')
		.directive('tdvSelectSelector', directive);
}());