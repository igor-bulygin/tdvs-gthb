(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService, $location) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_categories = 10;
		vm.orderTypes=[{value:"new", name : "NEW"},{value:"old", name :"OLD"},{value:"chepeast", name : "PRICE_LOW_TO_HIGH"}, {value:"expensive", name :"PRICE_HIGH_TO_LOW"}];
		vm.orderFilter={value:"", name : "ORDER_BY"};
		vm.filters = {};
		vm.search=search;
		vm.searchParam = angular.copy(searchParam);
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

		function search() {
			delete vm.results;
			vm.searching = true;
			var params={};
			if (!angular.isUndefined(vm.orderFilter) && !angular.isUndefined(vm.orderFilter.value)) {
				params = Object.assign(params, {order_type: vm.orderFilter.value});
			}
			if (!angular.isUndefined(vm.searchParam) && vm.searchParam.length>0) {
				params.q=vm.searchParam;
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
			results: '='
		}
	}

	angular
	.module('discover')
	.component('exploreProductsFilters', component);

}());