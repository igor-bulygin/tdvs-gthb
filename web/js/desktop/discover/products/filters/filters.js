(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService, $location, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_categories = 10;
		vm.orderTypes=[{value: "new", name: "discover.NEW"},
					{value: "old", name: "discover.OLD"},
					{value: "cheapest", name: "discover.PRICE_LOW_TO_HIGH"}, 
					{value: "expensive", name:"discover.PRICE_HIGH_TO_LOW"}];
		vm.orderFilter={value:"", name: "discover.ORDER_BY"};
		vm.filters = {};
		vm.search=search;
		vm.page=1;

		init();

		function init() {
			if (!angular.isUndefined(searchParam) &&searchParam.length>0) {
				vm.searchParam = angular.copy(searchParam);
			}
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

		function search(resetPage) {
			if (!vm.searching) {
				vm.searching = true;
				if (vm.search_key != vm.key || resetPage) {
					vm.results={items:[], counter:0};
					vm.page=1;
					$scope.$emit("resetPage"); 
				}
				var params = {
					limit: vm.limit,
					page: vm.page
				}
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
					vm.search_key = angular.copy(vm.key);
					vm.results.items = vm.results.items.concat(angular.copy(data.items));
					vm.results.counter=angular.copy(data.meta.total_count);
					vm.searching = false;
				}

				function onGetProductsError(err) {
					UtilService.onError(err);
					vm.searching = false;
				}
				productDataService.getProducts(params, onGetProductsSuccess, onGetProductsError);
			}
		}

		$scope.$on("changePage", function(evt,data){ 
				vm.page=data;
				search(false);
		}, true);
	}

	

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/products/filters/filters.html',
		controller: controller,
		controllerAs: 'exploreProductsFiltersCtrl',
		bindings: {
			searching:'=',
			results: '=',
			limit:'<'
		}
	}

	angular
	.module('discover')
	.component('exploreProductsFilters', component);

}());