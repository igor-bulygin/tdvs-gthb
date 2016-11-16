(function () {
	"use strict";

	function controller(productDataService, $location, toastr) {
		var vm = this;
		vm.quantity = 1;
		vm.optionsSelected = {};
		vm.parseOptions = parseOptions;
		vm.addQuantity = addQuantity;
		vm.subQuantity = subQuantity;
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

		init();

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

		function parseOptions(option_id, value) {
			if(option_id === 'size')
				value = getSizeText(value);
			resetOptions();
			vm.product.price_stock.forEach(function(element) {
				if(element.stock === 0 && ((angular.isArray(element.options[option_id]) && element.options[option_id].indexOf(value) > -1) || 
					(option_id === 'size' && element.options[option_id] === value))) {
						for(var key in element.options) {
							if(key !== option_id) {
								vm.product.options.forEach(function (option) {
									if(key === option.id) {
										option.values.forEach(function(unit) {
												if(key == 'size') {
													if(unit.text == element.options[key])
														unit.disabled=true;
												} else {
													if(unit.value == element.options[key][0])
														unit.disabled=true;
												}
										});
									}
								});
							}
						}
				}
			});
		}

		function getSizeText(value) {
			var text = null;
			vm.product.options.forEach(function(option) {
				if(option.id == "size") {
					option.values.forEach(function (element) {
						if(value == element.value) {
							text = element.text;
						}
					})
				}
			})
			return text;
		}

		function resetOptions() {
			vm.product.options.forEach(function(element) {
				element.values.forEach(function(value) {
					value['disabled'] = false;
				})
			})
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

		function addQuantity(){
			vm.quantity += 1;
		}

		function subQuantity(){
			if(vm.quantity >1){
				vm.quantity -= 1;
			}
		}

		function selectComparator(option) {
			return select_order.indexOf(option.widget_type)
		}
	}

	angular.module('todevise')
		.controller('detailProductCtrl', controller)

}());