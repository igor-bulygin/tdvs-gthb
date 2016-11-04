(function () {
	"use strict";

	function controller(productDataService, UtilService, toastr, $location, localStorageService) {
		var vm = this;
		vm.update = update;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		vm.language = 'en-US';

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

		function getProducts() {
			var data = {
				"deviser": UtilService.returnDeviserIdFromUrl(),
				"limit": 9999
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
				parseMainPhoto(vm.products);
			});
		}

		function getUnpublishedProducts() {
			vm.allUnpublishedProducts = localStorageService.get('draftProducts');
			if(vm.allUnpublishedProducts !== undefined && vm.allUnpublishedProducts !== null) {
				vm.unpublishedProducts = [];
				for(var i = 0; i < vm.allUnpublishedProducts.length; i++) {
					if(vm.allUnpublishedProducts[i].deviser_id === vm.deviser_id) {
						vm.allUnpublishedProducts[i].url_images = '/uploads/product/temp/';
						vm.unpublishedProducts.push(vm.allUnpublishedProducts[i]);
					}
				}
				if(vm.unpublishedProducts.length > 0) {
					parseMainPhoto(vm.unpublishedProducts);
				}
				
			}
		}

		function init() {
			parseCategories();
			getProducts();
			getUnpublishedProducts();
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

		function parseMainPhoto(products) {
			products.forEach(function (element) {
					for (var i = 0; i < element.media.photos.length; i++) {
						if (element.media.photos[i].main_product_photo)
							element.main_photo = currentHost() + element.url_images + element.media.photos[i].name;
					}
				});
		}

		init();

	}


	angular
		.module('todevise')
		.controller('editStoreCtrl', controller);

}());