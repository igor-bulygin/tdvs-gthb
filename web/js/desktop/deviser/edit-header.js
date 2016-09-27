(function () {
	"use strict";

	function controller(deviserDataService, languageDataService, UtilService, Upload, $uibModal, toastr, $scope) {
		var vm = this;
		vm.description_language = "en-US";
		vm.openCropModal = openCropModal;
		vm.update = update;
		vm.limit_text_biography = 140;

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				//set name
				if (!vm.deviser.personal_info.brand_name)
					vm.deviser.personal_info.brand_name = angular.copy(vm.deviser.personal_info.name);
				//set images
				if (vm.deviser.media.header_cropped)
					vm.header = currentHost() + vm.deviser.url_images + vm.deviser.media.header_cropped;
				if (vm.deviser.media.profile_cropped)
					vm.profile = currentHost() + vm.deviser.url_images + vm.deviser.media.profile_cropped;
				if (vm.deviser.media.header)
					vm.header_original = currentHost() + vm.deviser.url_images + vm.deviser.media.header;
				if (vm.deviser.media.profile)
					vm.profile_original = currentHost() + vm.deviser.url_images + vm.deviser.media.profile;
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
			});
		}

		function upload(image, type) {
			var data = {
				deviser_id: vm.deviser.id,
			}
			switch (type) {
			case "header":
				data.type = 'deviser-media-header-original';
				data.file = image;
				break;
			case "profile":
				data.type = 'deviser-media-profile-original';
				data.file = image;
				break;
			case "header_cropped":
				data.type = 'deviser-media-header-cropped';
				data.file = Upload.dataUrltoBlob(image, "temp.png")
				break;
			case "profile_cropped":
				data.type = 'deviser-media-profile-cropped';
				data.file = Upload.dataUrltoBlob(image, "temp.png")
				break;
			}
			Upload.upload({
				url: deviserDataService.Uploads,
				data: data
			}).then(function (dataUpload) {
				toastr.success("Photo uploaded!");
				vm.deviser.media[type] = dataUpload.data.filename;
				update('media', vm.deviser.media);
			});
		}

		function openCropModal(photo, type) {
			var modalInstance = $uibModal.open({
				component: 'modalCrop',
				resolve: {
					photo: function () {
						return photo;
					},
					type: function () {
						return type;
					}
				}
			})

			modalInstance.result.then(function (imageCropped) {
				switch (type) {
				case "header_cropped":
					vm.header = imageCropped;
					upload(imageCropped, type);
					break;
				case "profile_cropped":
					vm.profile = imageCropped;
					upload(imageCropped, type);
					break;
				}
			}, function () {
				console.log("dismissed");
			});
		}

		$scope.$watch('editHeaderCtrl.new_header', function (newValue, oldValue) {
			if (newValue) {
				//upload original
				upload(newValue, "header");
				//crop
				openCropModal(vm.new_header, 'header_cropped');
			}
		})

		$scope.$watch('editHeaderCtrl.new_profile', function (newValue, oldValue) {
			if (newValue) {
				//upload original
				upload(newValue, "profile");
				//crop
				openCropModal(vm.new_profile, 'profile_cropped');
			}
		})

		$scope.$watch('editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]', function (newValue, oldValue) {
			if (newValue && newValue.length > vm.limit_text_biography)
				vm.deviser.text_short_description[vm.description_language] = oldValue;
		});

	}

	angular
		.module('todevise')
		.controller('editHeaderCtrl', controller);
}());