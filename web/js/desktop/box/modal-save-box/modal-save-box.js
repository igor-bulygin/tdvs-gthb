(function () {
	"use strict";

	function controller(productDataService, boxDataService, UtilService) {
		var vm = this;

		init();

		function init(){
			function onGetProductSuccess(data) {
				vm.product = angular.copy(data);
				var product_main_photo = vm.product.media.photos.find((photo) => {return photo.main_product_photo});
				vm.product_main_photo = currentHost() + vm.product.url_images + product_main_photo.name;
			}

			productDataService.getProductPub({
				idProduct: vm.resolve.productId
			}, onGetProductSuccess, UtilService.onError)
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/box/modal-save-box/modal-save-box.html',
		controller: controller,
		controllerAs: 'modalSaveBoxCtrl',
		bindings: {
			resolve: '<',
			dismiss: '&',
			close: '&'
		}
	}

	angular
		.module('box')
		.component('modalSaveBox', component);

}());