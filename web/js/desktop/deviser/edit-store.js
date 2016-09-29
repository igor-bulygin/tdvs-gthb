(function () {
	"use strict";

	function controller(productDataService, UtilService, toastr, $location) {
		var vm = this;
		vm.update = update;

		function parseCategories() {
			var url = $location.absUrl();
			var queries = url.split("?")[1];
			var categories = queries.split('=');
			if (categories.length > 2) {
				vm.category = categories[1].split('&')[0];
				vm.subcategory = categories[2];
			} else {
				vm.category = categories[1];
			}
		}

		function getProducts() {
			productDataService.Product.get({
				deviser: UtilService.returnDeviserIdFromUrl(),
				limit: 9999,
				categories: vm.subcategory || vm.category
			}).$promise.then(function (dataProducts) {
				vm.products = dataProducts.items;
				vm.products.forEach(function (element) {
					for (var i = 0; i < element.media.photos.length; i++) {
						if (element.media.photos[i].main_product_photo)
							element.main_photo = currentHost() + element.url_images + element.media.photos[i].name;
					}
				})
			});
		}

		function init() {
			parseCategories();
			getProducts();
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

		init();

	}


	angular
		.module('todevise')
		.controller('editStoreCtrl', controller);

}());