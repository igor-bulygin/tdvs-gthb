(function () {
	"use strict";

	function controller($scope, productEvents, UtilService, productService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.isZeroOrLess = isZeroOrLess;
		var set_original_artwork = false;

		function parseTitles() {
			vm.titles = [];
			vm.product.price_stock.forEach(function (element) {
				var title = [];
				//order matters
				if(element.original_artwork) {
					title.push('Original artwork');
				}
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
					if(vm.product.options[key][0].length > 0)
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
				if(!UtilService.isEmpty(cartesian[0])) {
					for (var i = 0; i < cartesian.length; i++) {
						vm.product.price_stock.push({
							options: cartesian[i],
							price: 0,
							stock: 0,
							weight: 0,
							width: 0,
							height: 0,
							length: 0
						});
					}
				}
				if(set_original_artwork) {
					vm.product.price_stock.unshift({
						options: [],
						original_artwork: true,
						price: 0,
						stock: 0,
						weight: 0,
						width: 0,
						height: 0,
						length: 0
					})
				}
				parseTitles();
			}
		}

		function isZeroOrLess(value) {
			return value <= 0 ? true : false;
		}

		//watches
		$scope.$watch('productPriceStockCtrl.product.prints', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				createTable();
			}
		}, true);

		$scope.$watch('productPriceStockCtrl.product.options', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				createTable();
			}
		}, true);

		$scope.$watch('productPriceStockCtrl.product.sizechart', function(newValue, oldValue) {
			if(angular.isObject(newValue)) {
				createTable();
			}
		}, true);

		//events
		$scope.$on(productEvents.setVariations, function(event, args) {
			args.categories.forEach(function(element) {
				var values = productService.searchPrintSizechartsOnCategory(vm.categories, element);
				//if we do have prints, set original artwork to true
				if(values[0])
					set_original_artwork = true;
				else {
					set_original_artwork = false;
				}
			})
			delete vm.product.price_stock;
		});

		$scope.$on(productEvents.requiredErrors, function (event, args) {
			vm.form.$setSubmitted();
		})

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/price-stock/price-stock.html',
		controller: controller,
		controllerAs: 'productPriceStockCtrl',
		bindings: {
			product: '<',
			tags: '<',
			papertypes: '<',
			metric: '<',
			categories: '<'
		}
	}

	angular
		.module('todevise')
		.component('productPriceStock', component);
}());