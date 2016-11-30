(function () {
	"use strict";

	function controller($scope, productEvents, UtilService) {
		var vm = this;

		function parseTitles() {
			vm.titles = [];
			vm.product.price_stock.forEach(function (element) {
				var title = [];
				//order matters
				if(element.options['size']){
					if(angular.isObject(element.options['size'])) {
						if(element.options.size.width && element.options.size.length && element.options.size.metric_unit)
							title.push(element.options.size.width + ' x ' + element.options.size.length + element.options.size.metric_unit);
					} else {
						title.push(element.options.size);
					}
				}
				if(element.options['type']) {
					for(var i = 0; i < vm.papertypes.length; i++) {
						if(element.options['type'] == vm.papertypes[i].type) {
							title.push(vm.papertypes[i].name);
						}
					}
				}
				for(var key in element.options) {
					for(var i = 0; i < vm.tags.length; i++) {
						if(key === vm.tags[i].id && vm.tags[i].stock_and_price === true) {
							//look for tag.options.values and compare them to each element.options[key]
							vm.tags[i].options.forEach(function (option) {
								if(element.options[key].indexOf(option.value) > -1) {
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
			if(!UtilService.isEmpty(vm.product.options)) {
				for (var key in vm.product.options) {
					object[key] = vm.product.options[key];
				}
				if(angular.isObject(vm.product.prints) && !UtilService.isEmpty(vm.product.prints)) {
					object['type'] = vm.product.prints.type;
					object['size'] = vm.product.prints.sizes;
				}
				if(angular.isObject(vm.product.sizechart) && !UtilService.isEmpty(vm.product.sizechart)) {
					object['size'] = [];
					vm.product.sizechart.values.forEach(function (element) {
						object['size'].push(element[0]);
					});
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

		//events
		$scope.$on('productEvents.setVariations', function(args, event) {
			delete vm.product.price_stock;
		})

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/price-stock/price-stock.html',
		controller: controller,
		controllerAs: 'productPriceStockCtrl',
		bindings: {
			product: '<',
			tags: '<',
			papertypes: '<'
		}
	}

	angular
		.module('todevise')
		.component('productPriceStock', component);
}());