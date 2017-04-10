(function () {
	"use strict";

	function controller(UtilService, boxDataService) {
		var vm = this;

		init();

		function init() {
			search();
		}

		function search() {
			function onGetBoxesSuccess(data) {
				vm.results = angular.copy(data);
				
				// filter only boxes with > 0 products
				vm.boxes = vm.results.items.filter((box) => {
					return angular.isArray(box.products) && box.products.length > 0;
				});

				//parse main_product_photo
				vm.boxes = vm.boxes.map((box) => {
					box.products = box.products.map((product) => {
						product.main_photo = product.media.photos.find((photo) => {
							return photo.main_product_photo
						})
						return product;
					})
					return box;
				})
			}

			var params = {
				ignore_empty_boxes: true
			}

			boxDataService.getBoxPub(params, onGetBoxesSuccess, UtilService.onError);
		}

	}

	angular
		.module('todevise')
		.controller('exploreBoxesCtrl', controller);

}())