(function() {
	"use strict";

	function controller(UtilService, locationDataService, productDataService, $location, $scope) {
		var vm = this;
		vm.seeMore = seeMore;
		vm.show_categories  = 10;
        vm.show_sizes       = 50;
        vm.show_colors      = 50;
        vm.show_materials   = 50;
        vm.show_occasions   = 50;
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
        vm.getProductFilters = getProductFilters;
		vm.clearFilter = clearFilter;
		// vm.getFilterCategories = getFilterCategories;
		vm.getFilterSizes = getFilterSizes;
        vm.getFilterCategories = getFilterCategories;
        vm.getFilterColors = getFilterColors;
        vm.getFilterMaterials = getFilterMaterials;
        vm.getFilterOccasions = getFilterOccasions;
        vm.getFilterSeasons = getFilterSeasons;
        vm.getFilterTechniques = getFilterTechniques;
		vm.page = 1;
		vm.colors = [];
		vm.materials = [];
		vm.occasions = [];
		vm.seasons = [];

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
            else if (type === 'colors') {
                vm.filters.colors = {};
            }
            else if (type === 'materials') {
                vm.filters.materials = {};
            }
            else if (type === 'occasions') {
                vm.filters.occasions = {};
            }
            else if (type === 'seasons') {
                vm.filters.seasons = {};
            }
            search(true, false);
        }


        /**
         * get All existing categories for retrieve information about product categories
         * (see {scope="all"} in service parameters)
         */
        function getCategories() {
			function onGetCategoriesSuccess(data) {
                vm.categories_all = angular.copy(data);
			}
			productDataService.getCategories({scope: 'all'}, onGetCategoriesSuccess, UtilService.onError);
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
                        vm.getProductFilters();
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

		function getProductFilters() {
            vm.getFilterCategories();
            vm.getFilterSizes();
            vm.getFilterColors();
            vm.getFilterMaterials();
            vm.getFilterOccasions();
        }

        function getFilterCategories() {
            vm.categories = {};
            vm.categories.items = [];
            var cats = [];

            /**
             * retrieve information about categories IDs in products found
             */
            vm.results.items.forEach(function(product) {
                cats = cats.concat(angular.copy(product.categories));
            });

            /**
             * retrirve FULL information about categories in products found (filter all categories according to earlier found categories IDs)
             */
            var exists = [];
            var product_categories = vm.categories_all.items.filter(function (item) {
                if (exists.indexOf(item.short_id) === -1 && cats.indexOf(item.short_id) > -1) {
                    exists.push(item.short_id);
                    return true;
                }
                return false;
            });


            /**
             * 1. Search for root categories (Art, Fashion, etc)
             * 2. If there's more than 1 root category - we return root categories for filters
             * 3. If there's only 1 root category - we return only products subcategories for filters
             * @param product_categories
             * @return {*}
             */
            exists = [];
            var root_categories = product_categories.reduce(function (prev, cur, i, arr) {
                /**
                 * Get root category ID using "path" property of category info
                 */
                var root_id = cur.path.split('/')[1];

                if(root_id === undefined || root_id.length === 0) {
                    return prev;
                }

                if (exists.indexOf(root_id) === -1) {
                    prev.push(
                        vm.categories_all.items.find(function(item) {
                            return (item !== undefined && item.short_id === root_id);
                        })
                    );
                    exists.push(root_id);
                }
                return prev;
            }, []);
            /**
             * get categories filters
             */
            if (root_categories.length > 1) {
                vm.categories.items = root_categories.filter (function(item) {
                    return item !== undefined;
                });
            }
            else {
                vm.categories.items = product_categories.filter (function(item) {
                    return item !== undefined;
                });

            }
        }

        function getFilterSizes() {
            vm.sizes = [];
            /**
             * retrieve information about all product sizes in products found
             */
            vm.results.items.forEach(function(product) {
                if(typeof product.sizechart.values === 'object') {
                    product.sizechart.values.forEach(function (size) {
                        if (vm.sizes.indexOf(size[0]) == -1) {
                            vm.sizes.push(size[0]);
                        }
                    });
                }
            });
            /**
             * Sort sizes. (see sort function in the bottom of file)
             * First: sizes with numbers and letters (like "38 (XS)")
             * Then: sizes with letters (like "M", "S" etc)
             * Then: numbers ("40", "12", etc.)
             */
            vm.sizes.sort(sortAlphaNum);
        }

        function getFilterColors() {
            var colors_exists = [];

            vm.results.items.forEach(function(product) {
                /**
                 * retrieve information about avalaible colors in products found
                 */
                product.options.forEach(function(item) {
                    if (item.name === 'Color') {
                        item.values.forEach(function(obj) {
                            if (obj.colors.length > 1) {
                                for (i = 0; i < obj.colors.length; i++) {
                                    if (colors_exists.indexOf(obj.value[i]) === -1) {
                                        vm.colors.push({
                                            color: obj.colors[i][0],
                                            name: obj.text[i],
                                            value: obj.value[i]
                                        });
                                        colors_exists.push(obj.value[i]);
                                    }
                                }
                            }
                            else if (obj.colors.length === 1) {
                                if (colors_exists.indexOf(obj.value) === -1) {
                                    // console.log('1', obj.value, obj.colors[0])
                                    vm.colors.push({
                                        color: obj.colors[0],
                                        name: obj.text,
                                        value: obj.value
                                    });
                                    colors_exists.push(obj.value);
                                }
                            }
                        });
                    }
                });
            });
        }

        function getFilterMaterials() {
            var exists = [];

            vm.results.items.forEach(function(product) {
                /**
                 * retrieve information about avalaible materials in products found
                 */
                product.options.forEach(function(item) {
                    if (item.name === 'Material') {
                        item.values.forEach(function(obj) {
                            if (isArray(obj.value)) {
                                for (i = 0; i < obj.value.length; i++) {
                                    // if (obj.value[i] == 'gold-18') console.log(product);
                                    if (exists.indexOf(obj.value[i]) === -1) {
                                        vm.materials.push({
                                            name: obj.text[i],
                                            value: obj.value[i]
                                        });
                                        exists.push(obj.value[i]);
                                    }
                                }
                            }
                            else  {
                                if (exists.indexOf(obj.value) === -1) {
                                    vm.materials.push({
                                        name: obj.text,
                                        value: obj.value
                                    });
                                    exists.push(obj.value);
                                }
                            }
                        });
                    }
                });
            });
        }

        function getFilterOccasions() {
            var exists = [];

            vm.results.items.forEach(function(product) {
                /**
                 * retrieve information about avalaible materials in products found
                 */
                product.options.forEach(function(item) {
                    if (item.name === 'Occasion') {
                        item.values.forEach(function(obj) {
                            if (isArray(obj.value)) {
                                for (i = 0; i < obj.value.length; i++) {
                                    // if (obj.value[i] == 'gold-18') console.log(product);
                                    if (exists.indexOf(obj.value[i]) === -1) {
                                        vm.occasions.push({
                                            name: obj.text[i],
                                            value: obj.value[i]
                                        });
                                        exists.push(obj.value[i]);
                                    }
                                }
                            }
                            else  {
                                if (exists.indexOf(obj.value) === -1) {
                                    vm.occasions.push({
                                        name: obj.text,
                                        value: obj.value
                                    });
                                    exists.push(obj.value);
                                }
                            }
                        });
                    }
                });
            });
        }
        function getFilterSeasons() {

        }

        function getFilterTechniques() {

        }

		$scope.$on("changePage", function(evt,data){ 
				vm.page = data;
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