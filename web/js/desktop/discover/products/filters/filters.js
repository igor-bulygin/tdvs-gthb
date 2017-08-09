(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_categories = 10;

		init();

		function init() {
			getCategories();
		}

		function seeMore(value) {
			switch (value) {
				case 'categories':
				if (vm.show_categories < vm.categories.meta.total_count)
					vm.show_categories += 10;
				break;
				default:
				break;
			}
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.categories = angular.copy(data);
			}
			productDataService.getCategories({}, onGetCategoriesSuccess, UtilService.onError);
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/products/filters/filters.html',
		controller: controller,
		controllerAs: 'exploreProductsFiltersCtrl',
		bindings: {
			searching:'<',
			filters: '<'
		}
	}

	angular
	.module('discover')
	.component('exploreProductsFilters', component);

}());