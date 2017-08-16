(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService, $location) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_categories = 10;
		vm.orderTypes=[{value:"new", name : "New"},{value:"old", name :"Old"},{value:"chepeast", name : "Price low to high"}, {value:"expensive", name :"Price high to low"}];
		vm.orderFilter=vm.orderTypes[0];
		vm.filters = {};
		vm.search=search;
		init();

		function init() {
			getCategories();
			search();
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

		function search() {
			delete vm.results;
			vm.searching = true;
			var params={};
			if (!angular.isUndefined(vm.orderFilter) && !angular.isUndefined(vm.orderFilter.value)) {
				params = Object.assign(params, {order_type: vm.orderFilter.value});
			}
			if (!angular.isUndefined($location.search().q)) {
				params.q=$location.search().q;
			}
			Object.keys(vm.filters).map(function(filter_type) {
				var new_filter = []
				Object.keys(vm.filters[filter_type]).map(function(filter) {
					if (vm.filters[filter_type][filter])
						new_filter.push(filter);
				})
				if (new_filter.length > 0)
					params[filter_type + '[]'] = new_filter;
			});

			function onGetProductsSuccess(data) {
				vm.searching = false;
				vm.search_key = angular.copy(vm.key);
				vm.results = angular.copy(data);
			}

			function onGetProductsError(err) {
				UtilService.onError(err);
				vm.searching = false;
			}
			productDataService.getProducts(params, onGetProductsSuccess, onGetProductsError);
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/products/filters/filters.html',
		controller: controller,
		controllerAs: 'exploreProductsFiltersCtrl',
		bindings: {
			searching:'<',
			results: '='
		}
	}

	angular
	.module('discover')
	.component('exploreProductsFilters', component);

}());