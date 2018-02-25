(function () {
	"use strict";

	function controller() {
		var vm = this;
		vm.searchTypes = [{name:'PRODUCTS', id:1}, {name:'BOXES', id:2}, {name:'DEVISERS', id:3},{name:'INFLUENCERS', id:4} ]
		vm.currentSearchType = vm.searchTypes[0];
		vm.selectSearchType = selectSearchType;
		vm.searchTypeClass = searchTypeClass; 
		vm.hideHeader = true;
		init();

		function init() {
		}

		function selectSearchType(searchType) {
			vm.currentSearchType = searchType;
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