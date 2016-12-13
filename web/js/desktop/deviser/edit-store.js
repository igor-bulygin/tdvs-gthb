(function () {
	"use strict";

	function controller(productDataService, deviserDataService, UtilService, toastr, $location) {
		var vm = this;
		vm.update = update;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		vm.language = 'en-US';

		function init() {
			parseCategories();
			getDeviser();
		}

		init();

		function getProducts() {
			var data = {
				"deviser": UtilService.returnDeviserIdFromUrl(),
				"limit": 1000
			}
			if(vm.subcategory || vm.category) {
				data["categories[]"] = [];
				if(vm.subcategory)
					data["categories[]"].push(vm.subcategory);
				if(vm.category)
					data["categories[]"].push(vm.category);
			}
			productDataService.Product.get(data).$promise.then(function (dataProducts) {
				vm.products = dataProducts.items;
				vm.products.forEach(function(element) {
					setMinimumPrice(element);
					element.edit_link = currentHost() + '/deviser/' + vm.deviser.slug + '/' + vm.deviser.id + '/works/' + element.id + '/edit';
				})
				parseMainPhoto(vm.products);
			});
		}

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
				vm.deviser = dataDeviser;
				getProducts();
			}, function(err) {
				//errors
			});
		}

		function parseCategories() {
			var url = $location.absUrl();
			if(url.split("?").length > 1) {
				var queries = url.split("?")[1];
				var categories = queries.split('=');
				if (categories.length > 2) {
					vm.category = categories[1].split('&')[0];
					vm.subcategory = categories[2];
				} else {
					vm.category = categories[1];
				}
			}
		}

		function update(index, product) {
			if (index >= 0) {
				vm.products.splice(index, 1);
			}
			var patch = new productDataService.ProductPriv;
			var pos = -1;
			for (var i = 0; i < vm.products.length; i++) {
				if (product.id === vm.products[i].id)
					pos = i;
			}
			if (pos > -1) {
				patch.position = (pos + 1);
				patch.$update({
					idProduct: product.id
				}).then(function (dataProduct) {
					getProducts();
				}, function (err) {
					toastr.error(err);
				});
			} else {
				toastr.error("Cannot be updated!");
			}
		}

		function setMinimumPrice(product) {
			var min_price;
			if(angular.isArray(product.price_stock) &&
				product.price_stock.length > 0) {
				min_price = product.price_stock[0].price;
				for(var i = 0; i < product.price_stock.length; i++) {
					if(product.price_stock[i].price < min_price) {
						min_price = product.price_stock[i].price;
					}
				}
				product.min_price = min_price;
			}else{
				product.min_price = '-';
			}
		}

		function parseMainPhoto(products) {
			products.forEach(function (element) {
					for (var i = 0; i < element.media.photos.length; i++) {
						if (element.media.photos[i].main_product_photo)
							element.main_photo = currentHost() + element.url_images + element.media.photos[i].name;
					}
				});
		}

	}

	function draftProduct() {
		return function(input){
			var draft=[];
			angular.forEach(input,function(product){
				if(product.product_state === 'product_state_draft')
					draft.push(product)
				})
			return draft;
		}
	}

	function publishedProduct(){
		return function(input){
			var draft=[];
			angular.forEach(input,function(product){
				if(product.product_state === 'product_state_active' || product.product_state === null)
					draft.push(product)
			});
			return draft;
		}
	}

	angular
		.module('todevise')
		.controller('editStoreCtrl', controller)
		.filter('draftProduct',draftProduct)
		.filter('publishedProduct', publishedProduct);

}());