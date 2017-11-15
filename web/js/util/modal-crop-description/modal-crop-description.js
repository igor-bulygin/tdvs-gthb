(function () {
	"use strict";

	function controller(Upload, uploadDataService, UtilService) {
		var vm = this;
		vm.ok = ok;
		vm.dismissModal = dismissModal;
		vm.selected_language=_lang;
		vm.title_language = vm.selected_language;
		vm.description_language = vm.selected_language;
		vm.onChangeCrop = onChangeCrop;
		vm.modified = false;

		function ok() {

			function onUploadPhotoSuccess(data) {
				vm.resolve.imageData.url = currentHost() + data.data.url;
				vm.resolve.imageData.name = data.data.filename;
				closeModal();
			}

			function onWhileUploadingPhoto(evt) {
				//vm.file.progress = parseInt(100.0 * evt.loaded/evt.total)
			}

			//if is new crop or it has been modified, upload the foto
			if(vm.resolve.index === -1 || vm.modified) {
				if(angular.isObject(vm.resolve.imageData) && (vm.resolve.imageData.photoCropped || vm.resolve.imageData.title || vm.resolve.imageData.description)) {
					vm.file = Upload.dataUrltoBlob(vm.resolve.imageData.photoCropped, 'temp.png');
					vm.file.photoCropped = undefined;
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
						});
				}
			} else {
				closeModal();
			}

		}

		function closeModal() {
			vm.close({
				$value: vm.resolve.imageData
			})
		}

		function onChangeCrop(data) {
			if(data !== null) {
				if(!vm.dataURI)
					vm.dataURI = angular.copy(data);
				if(!angular.equals(data, vm.dataURI))
					vm.modified = true;
			}
		}

		function dismissModal() {
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