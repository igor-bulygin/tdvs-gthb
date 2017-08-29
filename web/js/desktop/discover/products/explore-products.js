(function() {
	"use strict";

	function controller(UtilService, productDataService,$scope) {
		var vm = this;
		vm.results=[];
		vm.limit=100;

		$scope.$on("changeNewPage", function(evt,data){ 
		  $scope.$broadcast("changePage",true); 
		});
}

angular
.module('discover')
.controller('exploreProductsCtrl', controller);

}())