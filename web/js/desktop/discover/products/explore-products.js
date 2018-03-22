(function() {
	"use strict";

	function controller(UtilService, productDataService,$scope) {
		var vm = this;
		vm.results={items:[]};
		vm.limit=99;
		vm.searchMore=searchMore;
		vm.page=1;
		vm.isMobile = true;

		function searchMore() {
			vm.page=vm.page + 1;
			$scope.$broadcast("changePage",vm.page); 
		}

		$scope.$watch(angular.element("#filters-xs-container").is(':visible'), function(newValue, oldValue) {
			if (angular.element("#filters-xs-container").is(':visible')) {
				vm.isMobile=true;
			}
			else {
				vm.isMobile=false;
			}
		}, true);



		$scope.$on("resetPage", function(evt){ 
				vm.page=1;
		}, true);
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/discover/products/explore-products.html',
		controller: controller,
		controllerAs: 'exploreProductsCtrl',
		bindings: {
			personType: '<'
		}
	}

	angular
		.module('discover')
		.component('exploreProducts', component);


}())