(function () {
	"use strict";

	function controller(productDataService, $location, toastr) {
		var vm = this;
		vm.quantity = 1;
		vm.optionsSelected = {};
		vm.getReferencesFromOptions = getReferencesFromOptions;
		vm.selectComparator = selectComparator;
		var select_order = ['size', 'color', 'select']

		function getProductId() {			
			var url = $location.absUrl().split("#")[0].split("/");
			vm.product_id = url[url.length - 1];
		}

		function getProduct() {
			productDataService.Product.get({
				idProduct: vm.product_id
			}).$promise.then(function (dataProduct) {
				vm.product = dataProduct;
				vm.minimum_price = getMinimumPrice(vm.product.price_stock);
				vm.price_showing = vm.minimum_price;
				vm.total_stock = getTotalStock(vm.product.price_stock);
				vm.stock = vm.total_stock;
				vm.price = vm.minimum_price;
				getReferencesFromOptions();
			}, function (err) {
				toastr.error(err);
			})
		}

		function init() {
			getProductId();
			getProduct();
		}

		function getMinimumPrice(references) {
			if(references.length > 0) {
				var price = references[0].price;
				for(var i = 0; i < references.length; i++) {
					if(references[i].price < price && references[i].price !== 0) {
						price = references[i].price
					}
				}
				return price;
			} else {
				return null;
			}
		}

		function getTotalStock(references) {
			var stock = 0;
			for(var i = 0; i < references.length; i++) {
				stock += references[i].stock;
			}
			return stock;
		}

		function getReferencesFromOptions(options) {
			//searchs all the references looking for matches with options
			var references_filtered = [];
			vm.product.references.forEach(function (element) {
				var flag = true;
				for (var key in options) {
					if (key in element.options && (element.options[key] == options[key])) {
						//ok
					} else {
						flag = false;
					}
				}
				if (flag) references_filtered.push(element);
			})
			getMinimumPrice(references_filtered);
			//return references_filtered;
		}

		init();

		function selectComparator(option) {
			return select_order.indexOf(option.widget_type)
		}
	}

	angular.module('todevise', ['api', 'toastr', 'nya.bootstrap.select'])
		.controller('detailProductCtrl', controller)

}());