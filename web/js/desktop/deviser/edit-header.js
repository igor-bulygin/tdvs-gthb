(function () {
	"use strict";

	function controller(deviserDataService, languageDataService, UtilService, Upload, $uibModal) {
		var vm = this;
		vm.description_language = "en-US";
		vm.openCropModal = openCropModal;

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.header = currentHost() + vm.deviser.url_images + vm.deviser.media.header;
				vm.profile = currentHost() + vm.deviser.url_images + vm.deviser.media.profile;
			}, function (err) {
				toastr.error(err);
			});
		}

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function (dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function (err) {
					toastr.error(err);
				});
		}

		function init() {
			getDeviser();
			getLanguages();
		}

		init();

		function openCropModal(photo, photoCropped) {
			var modalInstance = $uibModal.open({
				component: 'modalCrop',
				resolve: {
					photo: function () {
						return photo;
					},
					photoCropped: function () {
						return photoCropped;
					}
				}
			})
			
			modalInstance.result.then(function (imageCropped) {
				vm.croppedHeader = imageCropped;
			}, function() {
				console.log("dismissed");
			});
		}

	}

	angular
		.module('todevise')
		.controller('editHeaderCtrl', controller);
}());