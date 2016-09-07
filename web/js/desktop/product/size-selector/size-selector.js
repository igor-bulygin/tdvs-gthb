(function () {
	"use strict";

	function controller() {
		var vm = this;
	}

	function directive() {
		return {
			templateUrl: currentHost() + '/js/desktop/product/size-selector/size-selector.html',
			controller: controller,
			controllerAs: 'sizeSelectorCtrl',
			bindToController: true,
			require: '^form',
			scope: {
				option: '=',
				optionsSelected: '=',
				getReferences: '&'
			}
		}
	}

	angular
		.module('todevise')
		.directive('tdvSizeSelector', directive);

}());