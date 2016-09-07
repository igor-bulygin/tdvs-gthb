(function () {
	"use strict";

	function controller(productDataService, $location, toastr) {
		var vm = this;
		vm.quantity = 1;
		vm.optionsSelected = {};
		vm.getReferencesFromOptions = getReferencesFromOptions;
		vm.selectComparator = selectComparator;
		var url = $location.absUrl().split("/");
		var product_id = url[url.length - 1];
		var select_order = ['size', 'color', 'select']

		function getProduct() {
			productDataService.Product.get({
				idProduct: product_id
			}).$promise.then(function (dataProduct) {
				vm.product = dataProduct;
				vm.minimum_price = getMinimumPrice(vm.product.references);
				vm.price = vm.minimum_price;
				getReferencesFromOptions();
			}, function (err) {
				toastr.error(err);
			})
		}

		function init() {
			getProduct();
		}

		function getMinimumPrice(references) {
			//gets the minimum price in an array of references
			if (!references.isArray && references.length > 0) {
				var price = references[0].price;
				for (var i = 0; i < references.length; i++) {
					if (references[i].price < price)
						price = references[i].price;
				}
			} else {
				return null;
			}
			return price;
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

	angular.module('todevise', ['api', 'toastr'])
		.controller('detailProductCtrl', controller)

}());