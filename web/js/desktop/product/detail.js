(function () {
	"use strict";

	function controller(productDataService, $location, toastr) {
		var vm = this;
		var url = $location.absUrl().split("/");
		var product_id = url[url.length - 1];		

		function getProduct() {
				productDataService.Product.get({
					idProduct: product_id
				}).$promise.then(function (dataProduct) {
					vm.product = dataProduct;
					vm.minimum_price = getMinimumPrice(vm.product.references);
					console.log(vm.product);
				}, function (err) {
					toastr.error(err);
				})
		}
		
		function init() {
			getProduct();
		}
		
		function getMinimumPrice(references) {
			if(!references.isArray && references.length > 0) {
				var price = references[0].price;
				for(var i = 0; i < references.length; i++) {
					if(references[i].price < price)
						price = references[i].price;
				}
			}
			else {
				return null;
			}
			return price;
		}
		
		init();
		
		
	}

	angular.module('todevise', ['api', 'toastr'])
		.controller('detailProductCtrl', controller)

}());