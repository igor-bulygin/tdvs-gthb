(function () {
	"use strict";

	function controller(deviserDataService, languageDataService, UtilService, Upload, $uibModal, toastr, $scope) {
		var vm = this;
		vm.description_language = "en-US";
		vm.openCropModal = openCropModal;
		vm.update = update;

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				//set name
				if (!vm.deviser.personal_info.brand_name)
					vm.brand_name = angular.copy(vm.deviser.personal_info.name);
				else {
					vm.brand_name = angular.copy(vm.deviser.personal_info.brand_name);
				}
				//set images
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

		function update(field, value) {
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-update-profile";
			patch[field] = angular.copy(value);
			patch.deviser_id = vm.deviser.id;
			patch.$update().then(function (updateData) {
				getDeviser();
			}, function (err) {
				toastr.error(err);
			})
		}

		function upload(image, type, name) {
			var data = {
				deviser_id: vm.deviser.id,
				file: Upload.dataUrltoBlob(image, name)
			}
			console.log(data);
			switch (type) {
			case "header":
				data.type = 'deviser-media-header';
				break;
			case "profile":
				data.type = 'deviser-media-profile';
			}
			Upload.upload({
				url: deviserDataService.Uploads,
				data: data
			}).then(function (dataUpload) {
				toastr.success("Photo uploaded!");
				vm.deviser.media[type] = dataUpload.data.filename;
				update(media, vm.deviser.media);
			})
		}

		function openCropModal(photo) {
			var modalInstance = $uibModal.open({
				component: 'modalCrop',
				resolve: {
					photo: function () {
						return photo;
					}
				}
			})

			modalInstance.result.then(function (imageCropped) {
				vm.header = imageCropped;
				upload(imageCropped, 'header', vm.new_header.name);

			}, function () {
				console.log("dismissed");
			});
		}

		$scope.$watch('editHeaderCtrl.new_header', function (newValue, oldValue) {
			if (newValue) {
				openCropModal(vm.new_header);
			}
		})

	}

	angular
		.module('todevise')
		.controller('editHeaderCtrl', controller);
}());