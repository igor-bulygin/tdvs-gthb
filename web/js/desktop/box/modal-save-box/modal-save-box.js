(function () {
	"use strict";

	function controller(productDataService, boxDataService, UtilService) {
		var vm = this;
		vm.showCreateBox = showCreateBox;
		vm.createBox = createBox;
		vm.addProductToBox = addProductToBox;

		init();

		function init(){
			vm.creatingBox = false;
			vm.master_create_box_form = angular.copy(vm.create_form);
			getProduct(vm.resolve.productId);
		}

		function getProduct(id) {
			function onGetProductSuccess(data) {
				vm.product = angular.copy(data);
				var product_main_photo = vm.product.media.photos.find((photo) => {return photo.main_product_photo});
				vm.product_main_photo = currentHost() + vm.product.url_images + product_main_photo.name;
			}

			productDataService.getProductPub({
				idProduct: id
			}, onGetProductSuccess, UtilService.onError)
		}

		function getBoxes() {
			function onGetBoxSuccess(data) {
				vm.resolve.boxes = angular.copy(data);
			}
			boxDataService.getBoxPriv(null, onGetBoxSuccess, UtilService.onError)
		}

		function showCreateBox() {
			vm.creatingBox = true;
		}

		function createBox(form) {
			function onCreateBoxSuccess(data) {
				resetCreateForm();
				vm.new_box_name = null;
				getBoxes();
				vm.creatingBox = false;
			}

			form.$setSubmitted();
			if(form.$valid) {
				boxDataService.createBox({
					name: vm.new_box_name,
				}, onCreateBoxSuccess, UtilService.onError);
			} else {
				console.log("error creating box");
			}
		}

		function resetCreateForm() {
			vm.create_form = angular.copy(vm.master_create_box_form);
		}

		function addProductToBox(product_id, idBox) {
			var data = {product_id: product_id};
			var params = {idBox: idBox};

			function onAddProductSuccess(returnData) {
				vm.close();
			}

			boxDataService.addProduct(data, params, onAddProductSuccess, UtilService.onError);
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