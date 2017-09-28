(function () {
	"use strict";

	function controller($scope, productEvents, UtilService, productService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.priceStockValuesValidation = priceStockValuesValidation;
		vm.setUnlimitedStock = setUnlimitedStock;
		vm.applyToAll = applyToAll;
		vm.selected_language=_lang;

		var set_original_artwork = false;

		function parseTitles() {
			vm.titles = [];
			vm.product.price_stock.forEach(function (element) {
				var title = [];
				//order matters
				if(element.original_artwork) {
					title.push('Original artwork');
				}
				if(UtilService.isObject(element.options) && element.options['size']){
					if(angular.isObject(element.options['size'])) {
						if(element.options.size.width && element.options.size.length && element.options.size.metric_unit)
							title.push(element.options.size.width + ' x ' + element.options.size.length + element.options.size.metric_unit);
					} else {
						title.push(element.options.size);
					}
				}
				if(UtilService.isObject(element.options) && element.options['type']) {
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
									title.push(option.text[vm.selected_language]);
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

		function getCartesian(options, prints, sizechart) {
			var object = {};
			for (var key in options) {
				if(productService.tagChangesStockAndPrice(vm.tags, key)){
					if(options[key].length > 0 && options[key][0].length > 0)
						object[key] = options[key];
				}
			}
			if(angular.isObject(prints) && !UtilService.isEmpty(prints)) {
				object['type'] = prints.type;
				object['size'] = prints.sizes;
			}
			if(angular.isObject(sizechart) && !UtilService.isEmpty(sizechart)) {
				if(angular.isArray(sizechart.values) && sizechart.values.length > 0) {
					object['size'] = [];
					sizechart.values.forEach(function (element) {
						object['size'].push(element[0]);
					});
				}
			}
			return objectProduct(object);
		}

		function createTable() {
			var old_price_stock = angular.copy(vm.product.price_stock);
			vm.product.price_stock = [];
			var object = {};
			if(!UtilService.isEmpty(vm.product.options)) {
				var cartesian = getCartesian(vm.product.options, vm.product.prints, vm.product.sizechart);
				if(set_original_artwork) {
					vm.product.price_stock.unshift({
						options: [],
						original_artwork: true,
						price: 0,
						stock: 0,
						weight: 0,
						width: 0,
						height: 0,
						length: 0,
						available: true
					});
				}
				if((set_original_artwork && !UtilService.isEmpty(vm.product.prints)) || (!set_original_artwork)) {
					if(!UtilService.isEmpty(cartesian[0])) {
						for (var i = 0; i < cartesian.length; i++) {
							addEmptyPriceStock(cartesian[i])
						}
					} else {
						addEmptyPriceStock([]);
					}
				}
			} else {
				addEmptyPriceStock([]);
			}
			productService.setOldPriceStockPrices(old_price_stock, vm.product.price_stock, cartesian, vm.old_cartesian);
			parseTitles();
		}

		function addEmptyPriceStock(options) {
			vm.product.price_stock.push({
				options: options,
				price: 0,
				stock: 0,
				weight: 0,
				width: 0,
				height: 0,
				length: 0,
				available: true
			})
		}

		function applyToAll(type, value) {
			switch(type) {
				case 'dimensions':
					vm.product.price_stock.map((element) => {
						Object.assign(element, value);
					});
					break;
				case 'stock':
					if(value === null) {
						vm.product.price_stock.map((element) => element.unlimited_stock = true);
					} else {
						vm.product.price_stock.map((element) => element.unlimited_stock = false);
					}
					vm.product.price_stock.map((element) => element[type] = value);
					break;
				default:
					vm.product.price_stock.map((element) => element[type] = value);
					break;
			}

		}

		function setUnlimitedStock(product) {
			product.stock = product.unlimited_stock ? null : 0;
		}

		//show validation error only if value <= 0, if product is available and the form has been submitted
		function priceStockValuesValidation(value, available) {
			return UtilService.isZeroOrLess(value) && available && vm.form_submitted ? true : false;
		}

		//watches
		$scope.$watch('productPriceStockCtrl.product.prints', function(newValue, oldValue) {
			if(!vm.fromedit) {
				if(angular.isObject(newValue) || (!newValue && angular.isObject(oldValue))) {
					vm.old_cartesian = getCartesian(vm.product.options, oldValue, vm.product.sizechart);
					createTable();
				}
			}
		}, true);

		$scope.$watch('productPriceStockCtrl.product.options', function(newValue, oldValue) {
			if(!vm.fromedit || (vm.fromedit && vm.product.price_stock.length === 0)) {
				if(angular.isObject(newValue) && !UtilService.isEmpty(newValue)) {
					vm.old_cartesian = getCartesian(oldValue, vm.product.prints, vm.product.sizechart);
					createTable();
				}
			}
		}, true);

		$scope.$watch('productPriceStockCtrl.product.sizechart', function(newValue, oldValue) {
			if(!vm.fromedit) {
				if(angular.isObject(newValue)) {
					vm.old_cartesian = getCartesian(vm.product.options, vm.product.prints, oldValue);
					createTable();
				}
			}
		}, true);

		$scope.$watch('productPriceStockCtrl.product.price_stock', function(newValue, oldValue) {
			parseTitles();
			if(newValue.length > 0)
				newValue.forEach(function(element) {
					element.unlimited_stock = element.stock === null ? true : false;
				});
			if(vm.fromedit) {
				delete vm.fromedit;
			} 
		}, true);

		//events
		$scope.$on(productEvents.setVariations, function(event, args) {
			args.categories.forEach(function(element) {
				var values = productService.searchPrintSizechartsOnCategory(vm.categories, element);
				if(!values[0] && !values[1]) {
					if(!vm.fromedit) {
						vm.old_cartesian = getCartesian(vm.product.options, vm.product.prints, vm.product.sizechart);
						createTable();
					}
				}
				//if we do have prints, set original artwork to true
				if(values[0])
					set_original_artwork = true;
				else {
					set_original_artwork = false;
				}
			})
			if(!vm.fromedit) {
				//reset options
				delete vm.product.price_stock;
				delete vm.product.prints;
				delete vm.product.sizechart;
				vm.old_cartesian = getCartesian(vm.product.options, vm.product.prints, vm.product.sizechart);
				createTable();
			}
		});

		$scope.$on(productEvents.requiredErrors, function (event, args) {
			vm.form.$setSubmitted();
			//vm.forms_submitted is true if we sent the form and we have to apply validations
			vm.form_submitted = true;
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
			categories: '<',
			fromedit: '<'
		}
	}

	angular
		.module('product')
		.component('productPriceStock', component);
}());