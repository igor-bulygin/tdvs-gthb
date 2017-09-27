(function() {
	"use strict";

	function controller(UtilService, productDataService,$scope) {
		var vm = this;
		vm.results={items:[]};
		vm.limit=100;
		vm.searchMore=searchMore;
		vm.page=1;

		function searchMore() {
			vm.page=vm.page + 1;
			$scope.$broadcast("changePage",vm.page); 
		}

		$scope.$on("resetPage", function(evt){ 
				vm.page=1;
		}, true);
}

angular
.module('discover')
.controller('exploreProductsCtrl', controller);

}())