(function () {
	"use strict";

	function controller($scope) {
		var vm = this;

		function parseTitles() {
			vm.titles = [];
			vm.product.price_stock.forEach(function (element) {
				var title = [];
				for(var key in element.options) {
					for(var i = 0; i < vm.tags.length; i++) {
						if(key === vm.tags[i].id) {
							//look for tag.options.values and compare them to each element.options[key]
							vm.tags[i].options.forEach(function (option) {
								if(element.options[key].indexOf(option.value) > -1) {
									console.log(option.text['en-US']);
									title.push(option.text['en-US']);
								}
							})
						}
					}
				}
				vm.titles.push(title.join(", "));
			})
		}

		function product(args) {
			if(!args.length)
				return [[]];
			var prod = product(args.slice(1)), r = [];
			args[0].forEach(function(x) {
				prod.forEach(function(p) {
					r.push([x].concat(p));
				});
			});
			return r;
		}

		function objectProduct(obj) {
			var keys = Object.keys(obj),
				values = keys.map(function(x) { return obj[x] });

			return product(values).map(function(p) {
				var e = {};
				keys.forEach(function (k, n) { e[k] = p[n] });
				return e;
			});
		}

		function createTable() {
			vm.product.price_stock = [];
			var object = {};
			for (var key in vm.product.options) {
				object[key] = vm.product.options[key];
			}
			if(angular.isObject(vm.product.prints)) {
				object['type'] = vm.product.prints.type;
				object['size'] = vm.product.prints.sizes;
			}
			var cartesian = objectProduct(object);
			for (var i = 0; i < cartesian.length; i++) {
				vm.product.price_stock.push({
					options: cartesian[i],
					price: 0,
					stock: 0
				});
			}
			parseTitles();
		}

		//watches
		$scope.$watch('productPriceStockCtrl.product.prints', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				createTable();
			}
		}, true)

		$scope.$watch('productPriceStockCtrl.product.options', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				createTable();
			}
		}, true)

		$scope.$watch('productPriceStockCtrl.product.sizechart', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				createTable();
			}
		}, true)

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/price-stock/price-stock.html',
		controller: controller,
		controllerAs: 'productPriceStockCtrl',
		bindings: {
			product: '<',
			tags: '<'
		}
	}

	angular
		.module('todevise')
		.component('productPriceStock', component);
}());