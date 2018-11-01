(function () {
	"use strict";

	function controller(UtilService) {
		var vm = this;
		vm.searchTypes = UtilService.getSearchTypes(); // source of searchTypes is moved to UtilService because it's needed also in header to make select
        // vm.searchTypes = [{name:'discover.PRODUCTS', id:1}, {name:'discover.BOXES_NAME', id:2}, {name:'discover.DEVISERS', id:3, type: 2}, {name:'discover.INFLUENCERS', id:4, type:3} ];
		vm.selectSearchType = selectSearchType;
		vm.searchTypeClass = searchTypeClass;
		vm.setCurrentSearchType = setCurrentSearchType;
		vm.hideHeader = true;
		vm.searchParam = searchParam;
        vm.searchTypeId = _searchTypeId; // taken from global @var _searchTypeId defined in /components/views/PublicHeader2/PublicHeader2.php  (from GET request)
        vm.currentSearchType = vm.setCurrentSearchType(vm.searchTypeId);
        // vm.currentSearchType = vm.searchTypes[0];
        vm.searchdata = {
            key: vm.searchParam,
            hideHeader: vm.hideHeader,
            personType : vm.currentSearchType.type
        };
		init();

		function init() {
			// console.log('vm.currentSearchType', vm.currentSearchType);
            // console.log(vm.searchTypes);
		}

		function selectSearchType(searchType) {
			vm.currentSearchType = searchType;
			if (searchType.type) {
				vm.searchdata.personType = searchType.type;
			}
		}

		function searchTypeClass(searchTypeId) {
			if (vm.currentSearchType.id === searchTypeId) {
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
            var searchTypeId = typeId || 1;
            return vm.searchTypes.find(function (item) {
                return item.id == searchTypeId;
            });
        }

	}

	angular
		.module('discover')
		.controller('mainSearcherCtrl', controller);
}());