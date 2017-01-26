(function () {
	"use strict";

	function controller(productDataService, tagDataService, cartDataService, $location, toastr, UtilService, $window) {
		var vm = this;
		vm.quantity = 1;
		vm.option_selected = {};
		vm.has_error = UtilService.has_error;
		vm.parseOptions = parseOptions;
		vm.changeQuantity = changeQuantity;
		vm.changeOriginalArtwork = changeOriginalArtwork;
		vm.getReferencesFromOptions = getReferencesFromOptions;
		vm.selectComparator = selectComparator;
		vm.addToCart = addToCart;
		var select_order = ['size', 'color', 'select']

		function init() {
			getProductId();
			getProduct();
			getTags();

		}

		init();

		function getProductId() {
			var url = $location.absUrl().split("#")[0].split("/");
			vm.product_id = url[url.length - 1];
		}

		function getProduct() {
			productDataService.Product.get({
				idProduct: vm.product_id
			}).$promise.then(function (dataProduct) {
				vm.product = dataProduct;
				//checks
				setOriginalArtwork(vm.product);
				vm.minimum_price = getMinimumPrice(vm.product.price_stock);
				vm.total_stock = getTotalStock(vm.product.price_stock);
				vm.stock = vm.total_stock;
				vm.price = vm.minimum_price;
				//getReferencesFromOptions();
				//parse options with only one value
				vm.product.options.forEach(function(option){
					if(option.values.length === 1) {
						vm.option_selected[option.id] = option.values[0].value;
						parseOptions(option.id, option.values[0].value);
					}
				})
			}, function (err) {
				toastr.error(err);
			})
		}

		function getTags() {
			tagDataService.Tags.get()
				.$promise.then(function(dataTags) {
					vm.tags = dataTags.items;
				}, function(err) {
					//error
				});
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
				if(references[i].available && !references[i].original_artwork)
					stock += references[i].stock;
			}
			return stock;
		}

		function parseOptions(option_id, value) {
			if(option_id === 'size')
				value = getSizeText(value);
			vm.reference_id = getReferenceId(vm.option_selected);
			resetOptions();
			vm.product.price_stock.forEach(function(element) {
				if(element.stock === 0 && ((angular.isArray(element.options[option_id]) && element.options[option_id].indexOf(value) > -1) || 
					(option_id === 'size' && angular.equals(element.options[option_id],value)) ) ) {
						for(var key in element.options) {
							if(key !== option_id) {
								vm.product.options.forEach(function (option) {
									if(key === option.id) {
										option.values.forEach(function(unit) {
												if(key == 'size') {
													if(unit.text == element.options[key]) {
														unit.disabled=true;
													}
												} else {
													if(unit.value == element.options[key][0]) {
														unit.disabled=true;
													}
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

		function isOptionRequired(key) {
			var isRequired;
			vm.tags.forEach(function(element){
				if(element.id === key) {
					isRequired = element.required;
				}
			});
			return isRequired;
		}

		function getReferenceId(options_selected) {
			var options = angular.copy(options_selected);
			var reference;
			if(options['size']) {
				options['size'] = getSizeText(options['size']);
			}
			for (var key in options) {
				var optionRequired = isOptionRequired(key);
				if(key !== 'size' && optionRequired !== undefined && !optionRequired) {
					delete options[key];
				}
			}
			vm.product.price_stock.forEach(function (element) {
				var isReference = true;
				for(var key in options) {
					var valueToCompare;
					if(key === 'size')
						valueToCompare = options[key];
					else {
						valueToCompare = [options[key]];
					}
					if(!angular.equals(valueToCompare, element.options[key]))
						isReference = false;
				}
				if(isReference) {
					reference = element.short_id;
				}
			});
			return reference;
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

		function changeQuantity(value){
			if(vm.quantity <= vm.stock) {
				if(value < 0) {
					if(vm.quantity > 1)
						vm.quantity += value;
				}
				else if(vm.quantity < vm.stock) {
					vm.quantity += value;
				}
			}
		}

		function setOriginalArtwork(product) {
			for(var i = 0; i < product.price_stock.length; i++) {
				if(product.price_stock[i].original_artwork && product.price_stock[i].available) {
					vm.original_pos = i;
					vm.original_artwork = true;
				}
			}
		}

		function changeOriginalArtwork(value) {
			if(value == true) {
				vm.stock = vm.product.price_stock[vm.original_pos].stock;
				vm.price = vm.product.price_stock[vm.original_pos].price;
			} else {
				vm.stock = vm.total_stock;
				vm.price = vm.minimum_price;
			}
		}

		function selectComparator(option) {
			return select_order.indexOf(option.widget_type)
		}

		function saveProduct(cart_id) {
			var cartProduct = new cartDataService.CartProduct;
			cartProduct.product_id = angular.copy(vm.product.id);
			cartProduct.price_stock_id = angular.copy(vm.reference_id);
			cartProduct.quantity = angular.copy(vm.quantity);
			cartProduct.$save({
				id: cart_id
			}).then(function(savedData) {
				$window.location.href = currentHost() + "/cart";
			}, function (err) {
				if(err.status === 404) {
					cartDataService.Cart.save()
						.$promise.then(function(cartData) {
							cart_id = angular.copy(cartData.id);
							UtilService.setLocalStorage('cart_id', cart_id);
							saveProduct(cart_id);
						});
				}
			});
		}

		function addToCart(form) {
			form.$setSubmitted();
			if(form.$valid && vm.reference_id) {
				var cart_id = UtilService.getLocalStorage('cart_id');
				if(cart_id) {
					//POST to product
					saveProduct(cart_id);
				} else {
					//create cart
					cartDataService.Cart.save()
						.$promise.then(function (cartData) {
							cart_id = angular.copy(cartData.id);
							UtilService.setLocalStorage('cart_id', cart_id);
							saveProduct(cart_id);
						}, function(err) {
							//err
						})
				}
			}
		}
	}

	angular.module('todevise')
		.controller('detailProductCtrl', controller)

}());