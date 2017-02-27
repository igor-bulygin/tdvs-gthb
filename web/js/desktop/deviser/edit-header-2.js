(function () {
	"use strict";

	function controller(deviserDataService, deviserEvents, languageDataService, UtilService, Upload, $uibModal, locationDataService, $scope, $location) {
		var vm = this;
		vm.showCities = false;
		vm.limit_text_biography = 140;
		vm.description_language = 'en-US';
		vm.openCropModal = openCropModal;
		vm.selectCity = selectCity;
		vm.searchPlace = searchPlace;
		vm.editHeader = editHeader;
		vm.saveHeader = saveHeader;
		vm.cancelEdit = cancelEdit;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			vm.editingHeader = deviser.account_state === 'draft' ? true : false;
			getDeviser();
			getLanguages();
		}

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: deviser.short_id
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
				parseDeviserInfo(vm.deviser);
			}, function(err) {
				UtilService.onError(err);
			})
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function parseDeviserInfo(deviser){
			function setHostImage(image){
				return currentHost() + deviser.url_images + image;
			}
			//set name
			if(!deviser.personal_info.brand_name)
				deviser.personal_info.brand_name = angular.copy(deviser.personal_info.name);
			//set status
			vm.isProfilePublic = (deviser.account_state === 'draft' ? false: true);
			//set city
			if(deviser.personal_info.city && deviser.personal_info.country)
				vm.city = deviser.personal_info.city + ', ' + deviser.personal_info.country;
			//set images
			if(deviser.media.header_cropped)
				vm.header = setHostImage(deviser.media.header_cropped);
			if(deviser.media.profile_cropped)
				vm.profile = setHostImage(deviser.media.profile_cropped);
			if(deviser.media.header)
				vm.header_original = setHostImage(deviser.media.header);
			if(deviser.media.profile)
				vm.profile_original = setHostImage(deviser.media.profile);
		}

		function searchPlace(place) {
			locationDataService.Location.get({
				q: place
			}).$promise.then(function (dataLocation) {
				if(dataLocation.items.length === 0) {
					vm.showCities = false;
				}
				if(dataLocation.items.length > 0) {
					vm.showCities = true;
					vm.cities = dataLocation.items;
				}
			});
		}

		function selectCity(city) {
			vm.deviser.personal_info.city = city.city;
			vm.deviser.personal_info.country = city.country_code;
			vm.city = vm.deviser.personal_info.city + ', ' + vm.deviser.personal_info.country;
			vm.showCities = false;
		}

		function parseTags(value) {
			return value.replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
		}

		function upload(image, type) {
			var data = {
				deviser_id: vm.deviser.id,
			}
			var wait_for_cropped = false;
			switch (type) {
			case "header":
				data.type = 'deviser-media-header-original';
				data.file = image;
				wait_for_cropped = true;
				break;
			case "profile":
				data.type = 'deviser-media-profile-original';
				data.file = image;
				wait_for_cropped = true;
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
				//when uploading original, wait for cropped to save data in the deviser model
				if(wait_for_cropped) {
					vm.media_upload_helper = {
						type: type,
						filename: dataUpload.data.filename
					}
					//open modals once upload is complete
					if(type === 'header')
						openCropModal(vm.new_header, 'header_cropped');
					if(type === 'profile')
						openCropModal(vm.new_profile, 'profile_cropped');
				}
				else {
					vm.deviser.media[type] = dataUpload.data.filename;
					vm.deviser.media[vm.media_upload_helper.type] = vm.media_upload_helper.filename;
					delete vm.media_upload_helper;
				}
			});
		}

		function editHeader() {
			vm.editingHeader = true;
		}

		function saveHeader() {
			var patch = new deviserDataService.Profile;
			//patch.scenario = "deviser-update-profile";
			patch.deviser_id = vm.deviser.id;
			for(var key in vm.deviser) {
				//delete unwanted tags on text_biography
				if(key === 'text_biography') {
					for(var language in vm.deviser[key]) {
						vm.deviser[key][language]= parseTags(vm.deviser[key][language]);
					}
				}
				if(key!=='account_state')
					patch[key] = angular.copy(vm.deviser[key]);
				
			}
			patch.$update().then(function(updateData) {
				vm.deviser = angular.copy(updateData);
				vm.deviser_original = angular.copy(updateData)
				parseDeviserInfo(vm.deviser)
				vm.editingHeader = false;
			}, function(err) {
				UtilService.onError(err);
			});
		}

		function cancelEdit() {
			vm.editingHeader = false;
			parseDeviserInfo(vm.deviser_original);
			vm.deviser = angular.copy(vm.deviser_original);
		}

		//modals
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
				if(imageCropped) {
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
				}
			}, function () {
				console.log("dismissed");
			});
		}

		//watches
		$scope.$watch('deviserHeaderCtrl.new_header', function (newValue, oldValue) {
			if (newValue) {
				//upload original
				upload(newValue, "header");
			}
		});

		$scope.$watch('deviserHeaderCtrl.new_profile', function (newValue, oldValue) {
			if (newValue) {
				//upload original
				upload(newValue, "profile");
			}
		});

		$scope.$watch('deviserHeaderCtrl.deviser.text_short_description[deviserHeaderCtrl.description_language]', function (newValue, oldValue) {
			if (newValue && newValue.length > vm.limit_text_biography)
				vm.deviser.text_short_description[vm.description_language] = oldValue;
		});

		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			//set form as submitted
			vm.form.$setSubmitted();
			//check for both header and profile requireds
			if(args.required_fields && args.required_fields.length > 0) {
				args.required_fields.forEach(function(element){
					if(element === 'profile')
						vm.profileRequired = true;
					if(element === 'header')
						vm.headerRequired = true;
				})
			}
		})

	}

	angular
		.module('todevise')
		.controller('deviserHeaderCtrl', controller)
}());