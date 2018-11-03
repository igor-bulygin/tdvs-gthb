(function () {
	"use strict";

	function controller(UtilService, productDataService, boxDataService, personDataService) {
		var vm = this;
		vm.searchTypes = UtilService.getSearchTypes(); // source of searchTypes is moved to UtilService because it's needed also in header to make select
        // vm.searchTypes = [{name:'discover.PRODUCTS', id:1}, {name:'discover.BOXES_NAME', id:2}, {name:'discover.DEVISERS', id:3, type: 2}, {name:'discover.INFLUENCERS', id:4, type:3} ];
		vm.selectSearchType = selectSearchType;
		vm.searchTypeClass = searchTypeClass;
		vm.setCurrentSearchType = setCurrentSearchType;
		vm.countItems = countItems;
		vm.hideHeader = true;
		vm.searchParam = searchParam;
        /**
         * @type {number}
         * taken from global @var _searchTypeId defined in /components/views/PublicHeader2/PublicHeader2.php  (from GET request)
         * if 100 - search for ALL categories
         */
        vm.searchTypeId = (parseInt(_searchTypeId) > 0) ? parseInt(_searchTypeId) : 100; //
        vm.currentSearchType = vm.setCurrentSearchType(vm.searchTypeId); // get searchType according to
        vm.searchdata = {
            key: vm.searchParam,
            hideHeader: vm.hideHeader,
            personType : vm.currentSearchType.type
        };
        /**
         * hide categories menu until number of items in each category be counted
         * @type {boolean}
         */
        vm.counted = false;

        init();

		function init() {
			// console.log('vm.currentSearchType', vm.currentSearchType.id);
            // console.log(vm.results);
            /**
             * We count items only if searchTypeId == 100, search ALL categories
             */
            if (vm.searchTypeId === 100) {
                vm.countItems();
            }
            else {
                vm.counted = true; // we don't need to count number of items in every category so we can show categories header
            }
		}

		function selectSearchType(searchType) {
			vm.currentSearchType = searchType;
			if (searchType.type) {
				vm.searchdata.personType = searchType.type;
			}
		}

		function searchTypeClass(searchTypeId) {
			if ((vm.currentSearchType.id === searchTypeId) || (searchTypeId === 1 && vm.currentSearchType.id === 100)) {
				return 'tracking-link strong';
			}
			return '';
		}

        /**
         *
         * @param typeId
         * @returns {*}
         *
         */
		function setCurrentSearchType (typeId) {
            var searchTypeId = typeId || 100;
            return vm.searchTypes.find(function (item) {
                return item.id === searchTypeId;
            });
        }

        /**
         * Function counts items in every category based on request params
         */
        function countItems() {
            if (!vm.counted) {

                var params = {};

                if (!angular.isUndefined(vm.searchParam) && vm.searchParam.length > 0) {
                    params.q = vm.searchParam;
                }
                /**
                 * get promises from Data Services
                 */
                var productCount = productDataService.getProductsCount(params);
                var boxesCount = boxDataService.getBoxesCount(params);
                var devisersCount = personDataService.getPersonsCount(Object.assign({}, params, {type: 2}));
                var influencersCount = personDataService.getPersonsCount(Object.assign({}, params, {type: 3}));
                Promise.all([productCount, boxesCount, devisersCount, influencersCount]).then(function (values) {
                    values.forEach(function (item, i) {
                        var index = vm.searchTypes.findIndex(function(type) {
                           return type.id == (i+1);
                        });
                        vm.searchTypes[index].num = item.count;
                    });
                    vm.counted = true;
                });
            }
        }

	}

	angular
		.module('discover')
		.controller('mainSearcherCtrl', controller);
}());