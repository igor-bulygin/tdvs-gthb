(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService, $location, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_categories = 10;
        vm.show_sizes = 50;
		vm.orderTypes=[
            {value: "relevant", name: 'discover.RELEVANT'},
			{value: "new", name: 'discover.NEW'},
            // {value: "old", name: 'discover.OLD'},
            {value: "cheapest", name: 'discover.PRICE_LOW_TO_HIGH'},
            {value: "expensive", name: 'discover.PRICE_HIGH_TO_LOW'}
        ];
		vm.orderFilter={value: "", name: 'discover.ORDER_BY'};
		vm.filters = {};
		vm.search = search;
		vm.clearAllFilters = clearAllFilters
        vm.getProductSizesFilters = getProductSizesFilters;
		vm.clearFilter = clearFilter;
		vm.page = 1;

		init();

		function init() {
			if (!angular.isUndefined(searchParam) && searchParam.length > 0) {
				vm.searchParam = angular.copy(searchParam);
			}
			getCategories();
		}

		function seeMore(value) {
			switch (value) {
				case 'categories':
					if (vm.show_categories < vm.categories.meta.total_count) {
                        vm.show_categories += 10;
                    }
					break;
				default:
					break;
			}
		}

        /**
         * clears all filters and start search again
         */
		function clearAllFilters() {
			vm.filters = {};
			search(true, false);
		}

        /**
         * clears certain type of filters and start search again
         */
        function clearFilter(type) {
            if (type === 'categories') {
                vm.filters.categories = {};
			}
			else if (type === 'sizes') {
                vm.filters.sizes = {};
			}
            search(true, false);
        }


		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.categories = angular.copy(data);
			}
			productDataService.getCategories({}, onGetCategoriesSuccess, UtilService.onError);
		}

		function search(resetPage, resetFilters) {
			if (!vm.searching) {
				vm.searching = true;
				if (vm.search_key != vm.key || resetPage) {
					vm.results={items:[], counter:0};
					vm.page = 1;
					$scope.$emit('resetPage');
				}
				var params = {
					limit: vm.limit,
					page: vm.page
				};

				if (!angular.isUndefined(vm.orderFilter) && !angular.isUndefined(vm.orderFilter.value)) {
					params = Object.assign(params, {order_type: vm.orderFilter.value});
				}
				if (!angular.isUndefined(vm.searchParam) && vm.searchParam.length>0) {
					params.q = vm.searchParam;
				}
                // console.log('filters', vm.filters);
				Object.keys(vm.filters).map(function(filter_type) {
					var new_filter = [];
					Object.keys(vm.filters[filter_type]).map(function(filter) {
						if (vm.filters[filter_type][filter]) {
                            new_filter.push(filter);
                        }
					});
					if (new_filter.length > 0) {
                        params[filter_type + '[]'] = new_filter;
                    }
				});

				var onGetProductsSuccess = function(data) {
                    vm.search_key = angular.copy(vm.key);
                    vm.results.items = vm.results.items.concat(angular.copy(data.items));
                    vm.results.counter = angular.copy(data.meta.total_count);
                    if (resetFilters) {
                        vm.getProductSizesFilters();
                	}
					vm.searching = false;
				};

				var onGetProductsError = function(err) {
					UtilService.onError(err);
					vm.searching = false;
				};
				productDataService.getProducts(params, onGetProductsSuccess, onGetProductsError);
			}
		}

		function getProductSizesFilters() {
		    vm.sizes = [];
            vm.results.items.forEach(function(product) {
                if(typeof product.sizechart.values === 'object') {
                    product.sizechart.values.forEach(function (size) {
                        if (vm.sizes.indexOf(size[0]) == -1) {
                            vm.sizes.push(size[0]);
                        }
                    });
                }
            });
            vm.sizes.sort(sortAlphaNum);
        }


		$scope.$on("changePage", function(evt,data){ 
				vm.page=data;
				search(false, true);
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

var reA = /[a-zA-Z)(]/g;
var reN = /[0-9]/g;
function sortAlphaNum(a,b) {
    var AInt = parseInt(a, 10);
    var BInt = parseInt(b, 10);

    if(isNaN(AInt) && isNaN(BInt)){
        var aA = a.replace(reA, '');
        var bA = b.replace(reA, '');
        if (aA.length > 0 && bA.length > 0) {
            if (aA === bA) {
                var aN = parseInt(a.replace(reN, ""), 10);
                var bN = parseInt(b.replace(reN, ""), 10);
                return aN === bN ? 0 : aN > bN ? 1 : -1;
            } else {
                return aA > bA ? 1 : -1;
            }
        }
        else {
            return a > b ? 1 : -1;
        }
    }
    else if (isNaN(AInt)){//A is not an Int
        return 1;//to make alphanumeric sort first return -1 here
    }
    else if (isNaN(BInt)){//B is not an Int
        return -1;//to make alphanumeric sort first return 1 here
    }
    else {
        if ((reA.test(a) && reA.test(b)) || (!reA.test(a) && !reA.test(b))) {
            return a > b;
        }
        else if (reA.test(a) && !reA.test(b)) {
            return -1;
        }
        else {
            return 1;
        }
    }
}