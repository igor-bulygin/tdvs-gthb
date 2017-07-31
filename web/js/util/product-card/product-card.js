(function () {
	"use strict";

	function controller() {
		var vm = this;

		if (angular.isUndefined(vm.classes)) {
			vm.classes={column1Class: 'col-md-2',column2Class: 'col-md-2'};
		}
		else {
			if (angular.isUndefined(vm.classescolumn1Class)) {
				vm.classes.column1Class= 'col-md-2';
			}
			if (angular.isUndefined(vm.classescolumn2Class)) {
				vm.classes.column2Class= 'col-md-2';
			}
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/util/product-card/product-card.html',
		controller: controller,
		controllerAs: 'productCardCtrl',
		bindings: {
			product: '<',
			classes: '<?'
		}

	}

	angular.module('util')
		.component('productCard', component)

}());