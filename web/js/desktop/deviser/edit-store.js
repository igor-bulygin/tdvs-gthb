(function () {
	"use strict";

	function controller(productDataService, UtilService) {
		var vm = this;

		function getProducts() {
			productDataService.Product.get({
				deviser: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataProducts) {
				vm.products = dataProducts.items;
				console.log(vm.products);
			});
		}


		function init() {
			getProducts();
		}

		init();

	}


	angular
		.module('todevise')
		.controller('editStoreCtrl', controller);

}());