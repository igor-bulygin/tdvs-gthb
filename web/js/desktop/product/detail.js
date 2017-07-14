(function () {
	"use strict";

	function controller(productDataService, tagDataService, cartDataService, lovedDataService, boxDataService, 
		$location, toastr, UtilService, $window, $uibModal, localStorageUtilService) {
		var vm = this;
		vm.quantity = 1;
		vm.option_selected = {};
		vm.has_error = UtilService.has_error;
		vm.isString = angular.isString;
		vm.optionsChanged = optionsChanged;
		vm.changeQuantity = changeQuantity;
		vm.changeOriginalArtwork = changeOriginalArtwork;
		vm.selectComparator = selectComparator;
		vm.addToCart = addToCart;
		vm.setLoved = setLoved;
		vm.setBox = setBox;
		var select_order = ['size', 'color', 'select']

		function init() {
			getProductId();
			getTags();
		}

		init();

		function getProductId() {
			var url = $location.absUrl().split("#")[0].split("/");
			vm.product_id = url[url.length - 1];
		}

		function getProduct() {
			function onGetProductSuccess(data) {
				vm.product = angular.copy(data);
				//checks
				setOriginalArtwork(vm.product);
				vm.minimum_price = getMinimumPrice(vm.product.price_stock);
				vm.total_stock = getTotalStock(vm.product.price_stock);
				vm.stock = vm.total_stock;
				vm.price = vm.minimum_price;
				vm.product.options.forEach(function(option){
					//parse options with only one value
					if(option.values.length === 1) {
						vm.option_selected[option.id] = option.values[0].value;
						parseOptions(option.id, option.values[0].value);
					}
				});
			}

			productDataService.getProductPub({
				idProduct: vm.product_id
			}, onGetProductSuccess, UtilService.onError);
		}

		function getTags() {
			function onGetTagsSuccess(data) {
				vm.tags = angular.copy(data.items);
				getProduct();
			}
			tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
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

		function optionsChanged(option_id, value) {
			resetOptions();
			parseOptions(option_id, value);
		}

		function parseOptions(option_id, value) {
			if(option_id === 'size')
				value = getSizeText(value);
			vm.reference_id = getReferenceId(vm.option_selected);
			vm.product.price_stock.forEach(function(element) {
				if(element.stock === 0 && ((angular.isArray(element.options[option_id]) && element.options[option_id].indexOf(value) > -1) || 
					(angular.isArray(element.options[option_id]) && angular.isArray(value) && angular.equals(element.options[option_id], value)) || 
					(option_id === 'size' && angular.equals(element.options[option_id], value)) ) ) {
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
					isRequired = element.required && element.stock_and_price;
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
					if(key === 'size' || !angular.isString(options[key]))
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
			function onSaveProductSuccess(data) {
				$window.location.href = currentHost() + '/cart';
			}
			function onSaveProductError(err) {
				if(err.status === 404) {
					cartDataService.createCart(onCreateCartSuccess, onCreateCartError);
				}
			}
			cartDataService.addProduct({
				product_id: vm.product.id,
				price_stock_id: vm.reference_id,
				quantity: vm.quantity
			}, {
				id: cart_id
			}, onSaveProductSuccess, onSaveProductError);
		}

		function onCreateCartSuccess(data) {
			var cart_id = angular.copy(data.id);
			localStorageUtilService.setLocalStorage('cart_id', cart_id);
			saveProduct(cart_id);
		}

		function onCreateCartError(err) {
			console.log(err);
		}

		function addToCart(form) {
			form.$setSubmitted();
			if(form.$valid && vm.reference_id) {
				var cart_id = localStorageUtilService.getLocalStorage('cart_id');
				if(cart_id) {
					saveProduct(cart_id);
				} else {
					cartDataService.createCart(onCreateCartSuccess, onCreateCartError);
				}
			}
		}

		function setLoved() {
			function setLovedSuccess(data) {
				getProduct();
			}
			function setLovedError(err) {
				if(err.status === 401) openSignUpModal('loved');
			}

			//if is not loved
			if(!vm.product.isLoved) {
				lovedDataService.setLoved({
					product_id: vm.product_id
				}, setLovedSuccess, setLovedError);
				
			}
			//(if it's loved, delete it)
			if(vm.product.isLoved) {
				lovedDataService.deleteLoved({
					productId: vm.product_id
				}, setLovedSuccess, setLovedError)
			}
		}

		function setBox() {
			function onGetBoxSuccess(data) {
				var modalInstance = $uibModal.open({
					component: 'modalSaveBox',
					size: 'sm',
					resolve: {
						productId: function() {
							return vm.product_id;
						},
						boxes: function() {
							return data;
						}
					}
				});

				modalInstance.result.then(function () {
					console.log("Here!");
					getProduct();
				});
			}

			function onGetBoxError(err) {
				if(err.status === 401 || err.status === 404)
					openSignUpModal('boxes')
			}

			boxDataService.getBoxPriv(null, onGetBoxSuccess, onGetBoxError);
		}

		function openSignUpModal(component){
			var modalInstance = $uibModal.open({
				component: 'modalSignUpLoved',
				size: 'sm',
				resolve: {
					icon: function() {
						return component;
					}
				}
			});
		}
	}

	angular.module('product')
		.controller('detailProductCtrl', controller)

}());