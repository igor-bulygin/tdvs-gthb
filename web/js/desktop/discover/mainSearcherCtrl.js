(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.searchTypes = [{name:'discover.PRODUCTS', id:1}, {name:'discover.BOXES_NAME', id:2}, {name:'discover.DEVISERS', id:3, type: 2}, {name:'discover.INFLUENCERS', id:4, type:3} ]
		vm.currentSearchType = vm.searchTypes[0];
		vm.selectSearchType = selectSearchType;
		vm.searchTypeClass = searchTypeClass; 
		vm.hideHeader = true;
		vm.searchParam = searchParam;
		vm.searchdata = {key:vm.searchParam, hideHeader: vm.hideHeader, personType : vm.currentSearchType.type};
		init();

		function init() {
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

	}

	angular
		.module('discover')
		.controller('mainSearcherCtrl', controller);
}());