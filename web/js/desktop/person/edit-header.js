(function () {
	"use strict";

	function controller(personDataService, deviserEvents, languageDataService, UtilService, Upload, 
		$uibModal, locationDataService, $scope, $location, $rootScope) {
		var vm = this;
		vm.showCities = false;
		vm.limit_text_biography = 140;
		vm.description_language = 'en-US';
		vm.required = {};
		vm.openCropModal = openCropModal;
		vm.selectCity = selectCity;
		vm.searchPlace = searchPlace;
		vm.editHeader = editHeader;
		vm.saveHeader = saveHeader;
		vm.cancelEdit = cancelEdit;
		vm.has_error = UtilService.has_error;

		init();

		function init() {
			vm.editingHeader = false;
			getPerson();
			getLanguages();
		}

		function getPerson() {
			function onGetProfileSuccess(data) {
				vm.person = angular.copy(data);
				vm.person_original = angular.copy(data);
				parsePersonInfo(vm.person);
			}

			personDataService.getProfile({
				personId: person.short_id,
			}, onGetProfileSuccess, UtilService.onError);
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function parsePersonInfo(person){
			function setHostImage(image){
				return currentHost() + person.url_images + image;
			}
			//set name
			if(!person.personal_info.brand_name)
				person.personal_info.brand_name = angular.copy(person.personal_info.name);
			//set status
			vm.isProfilePublic = (person.account_state === 'draft' ? false: true);
			person.text_short_description = UtilService.emptyArrayToObject(person.text_short_description);
			//set city
			if(person.personal_info.city && person.personal_info.country)
				vm.city = person.personal_info.city + ', ' + person.personal_info.country;
			//set images
			if(person.media.header_cropped)
				vm.header = setHostImage(person.media.header_cropped);
			if(person.media.profile_cropped)
				vm.profile = setHostImage(person.media.profile_cropped);
			if(person.media.header)
				vm.header_original = setHostImage(person.media.header);
			if(person.media.profile)
				vm.profile_original = setHostImage(person.media.profile);
		}

		function searchPlace(place) {
			function onGetLocationSuccess(data) {
				if(data.items.length === 0) {
					vm.showCities = false;
				}
				if(data.items.length > 0) {
					vm.showCities = true;
					vm.cities = angular.copy(data.items);
				}
			}

			if(place)
				locationDataService.getLocation({q: place}, onGetLocationSuccess, UtilService.onError);
			else {
				selectCity({
					city: null,
					country_code: null
				})
			}

		}

		function selectCity(city) {
			vm.person.personal_info.city = city.city;
			vm.person.personal_info.country = city.country_code;
			if(city.city && city.country_code)
				vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
			vm.showCities = false;
		}

		function upload(image, type) {
			var data = {
				person_id: vm.person.id,
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
				url: personDataService.Uploads,
				data: data
			}).then(function (dataUpload) {
				//when uploading original, wait for cropped to save data in the person model
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
					vm.person.media[type] = dataUpload.data.filename;
					vm.person.media[vm.media_upload_helper.type] = vm.media_upload_helper.filename;
					delete vm.media_upload_helper;
				}
			});
		}

		function editHeader() {
			vm.editingHeader = true;
		}

		function saveHeader() {
			function onSaveHeaderSuccess(data) {
				vm.person = angular.copy(data);
				vm.person_original = angular.copy(data);
				parsePersonInfo(vm.person);
				vm.editingHeader = false;
				var newObject = {
					media: {
						header: vm.person.media.header || null,
						header_cropped: vm.person.media.header_cropped || null,
						profile: vm.person.media.profile || null,
						profile_cropped: vm.person.media.profile_cropped || null
					},
					personal_info: vm.person.personal_info || {},
					text_short_description: vm.person.text_short_description || {}
				}
				$rootScope.$broadcast(deviserEvents.updated_deviser, newObject);
			}

			personDataService.updateProfile(vm.person, {
				personId: person.short_id
			}, onSaveHeaderSuccess, UtilService.onError);
		}

		function cancelEdit() {
			vm.editingHeader = false;
			parsePersonInfo(vm.person_original);
			vm.person = angular.copy(vm.person_original);
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

		function setErrors(error_array) {
			error_array.forEach(function (element) {
				vm.required[element] = true;
			})
		}

		//watches
		$scope.$watch('personHeaderCtrl.new_header', function (newValue, oldValue) {
			if (newValue) {
				//upload original
				upload(newValue, "header");
			}
		});

		$scope.$watch('personHeaderCtrl.new_profile', function (newValue, oldValue) {
			if (newValue) {
				//upload original
				upload(newValue, "profile");
			}
		});

		$scope.$watch('personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language]', function (newValue, oldValue) {
			if (newValue && newValue.length > vm.limit_text_biography)
				vm.person.text_short_description[vm.description_language] = oldValue;
		});

		$scope.$on(deviserEvents.updated_deviser, function(event, args) {
			vm.person = Object.assign(vm.person, args);
		})

		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			//set form as submitted
			if(vm.form)
				vm.form.$setSubmitted();
			//check for required fields and sections
			if(args.required_fields && args.required_fields.length > 0) {
				setErrors(args.required_fields);
			}
			if(args.required_sections && args.required_sections.length > 0) {
				setErrors(args.required_sections);
			}
		})

	}

	angular
		.module('todevise')
		.controller('personHeaderCtrl', controller)
}());