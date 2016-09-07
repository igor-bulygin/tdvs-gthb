(function () {
	"use strict";

	function controller() {
		var vm = this;

		function init() {
			vm.option.values.forEach(function (element) {
				element.color_style = {
					'background-color': element.colors[0]
				}
			});
		}

		init();
	}

	function directive() {
		return {
			templateUrl: currentHost() + '/js/desktop/product/color-selector/color-selector.html',
			controller: controller,
			controllerAs: 'colorSelectorCtrl',
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
		.directive('tdvColorSelector', directive);

}());