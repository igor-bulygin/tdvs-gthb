(function () {
	"use strict";

	function controller(UtilService, productDataService) {
		var vm = this;

		init();

		function init(){
			getCategories();
		}

		function getCategories(){
			function onGetCategoriesSuccess(data) {
				vm.categories = angular.copy(data.items);
			}

			productDataService.getCategories({scope: 'roots'}, onGetCategoriesSuccess, UtilService.onError);
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/filters/filters.html',
		controller: controller,
		controllerAs: 'discoverFiltersCtrl',
		bindings: {
			filters : '<'
		}
	}

	angular
		.module('todevise')
		.component('discoverFilters', component);

}());