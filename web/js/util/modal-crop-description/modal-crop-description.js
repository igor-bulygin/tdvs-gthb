(function () {
	"use strict";

	function controller(Upload, uploadDataService, UtilService) {
		var vm = this;
		vm.ok = ok;
		vm.closeModal = closeModal;
		vm.selected_language=_lang;
		vm.title_language = vm.selected_language;
		vm.description_language = vm.selected_language;
		vm.imageData = {};

		function ok() {

			function onUploadPhotoSuccess(data) {
				vm.imageData.url = currentHost() + data.data.url;
				vm.imageData.name = data.data.filename;
				vm.close({
					$value: vm.imageData
				});
			}

			function onWhileUploadingPhoto(evt) {
				//vm.file.progress = parseInt(100.0 * evt.loaded/evt.total)
			}

			if(angular.isObject(vm.imageData) && (vm.imageData.photoCropped || vm.imageData.title || vm.imageData.description)) {
				vm.file = Upload.dataUrltoBlob(vm.imageData.photoCropped, 'temp.png')
				var data = {
					deviser_id: person.short_id,
					file: vm.file
				};
				if(vm.resolve.product.id) {
					data['type'] = "known-product-photo";
					data['product_id'] = vm.resolve.product.id;
				} else {
					data['type'] = "unknown-product-photo";
				}

				uploadDataService.UploadFile(data,
					function(data) {
						return onUploadPhotoSuccess(data);
					}, UtilService.onError, function(evt) {
						return onWhileUploadingPhoto(evt);
					})
			}


		}

		function closeModal() {
			vm.dismiss();
		}



	}

	var component = {
		templateUrl: currentHost() + '/js/util/modal-crop-description/modal-crop-description.html',
		controller: controller,
		controllerAs: 'modalCropDescriptionCtrl',
		bindings: {
			resolve: '<',
			close: '&',
			dismiss: '&',
			modalInstance: '<'
		}
	}

	angular
		.module('util')
		.component('modalCropDescription', component)

}());