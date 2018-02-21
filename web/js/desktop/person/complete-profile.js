(function() {
	"use strict";

	function controller(personDataService, UtilService, locationDataService, productDataService, languageDataService, Upload, 
		uploadDataService, $scope, $window) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.person = person;
		vm.selected_language=_lang;
		vm.description_language = vm.biography_language = vm.selected_language;
		vm.limit_text_short_description = 140;
		vm.searchPlace = searchPlace;
		vm.selectCity = selectCity;
		vm.save = save;
		vm.setPrefix=setPrefix;
		vm.sendingForm = true;
		vm.crop_options = {
			profile: {
				area_type: 'circle',
				size: {
					w: 340,
					h: 340
				},
				aspect_ratio: 1
			},
			header: {
				area_type: 'rectangle',
				size: {
					w: 1280,
					h: 450
				},
				aspect_ratio: "2.8"
			}
		}

		init();

		function init() {
			getCategories();
			getLanguages();

			//These two functions work only when there is some malfunction in the database and some
			//essential data get accidentally deleted.
			setCity();
			setImages();

			setPrefix();
			vm.sendingForm = false;
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				if(data.items && angular.isArray(data.items) && data.items.length > 0)
					vm.categories = data.items;
			}

			productDataService.getCategories({scope: 'roots'}, onGetCategoriesSuccess, UtilService.onError);
		}

		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				if(data.items && angular.isArray(data.items) && data.items.length > 0) {
					vm.languages = data.items;
				}
			}

			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function setCity() {
			if(angular.isObject(vm.person.personal_info)) {
				if(UtilService.isStringNotEmpty(vm.person.personal_info.city) && UtilService.isStringNotEmpty(vm.person.personal_info.country)) {
					selectCity({
						city: vm.person.personal_info.city,
						country_code: vm.person.personal_info.country
					})
				}
			} 
		}

		function setPrefix() {
			if (vm.person.personal_info.phone_number_prefix==null || angular.isUndefined(vm.person.personal_info.phone_number_prefix) || vm.person.personal_info.phone_number_prefix.length<1) {
				vm.person.personal_info.phone_number_prefix="+";
			}
			if (vm.person.personal_info.phone_number_prefix.indexOf('+') == -1) {
				vm.person.personal_info.phone_number_prefix='+' + vm.person.personal_info.phone_number_prefix;
				validatePrefix();
			}
		}

		function validatePrefix() {
			if (vm.person.personal_info.phone_number_prefix.length<2) {
				vm.validPrefix = true;
			} else {
				vm.validPrefix = false;
			}
			return vm.validPrefix;
		}

		function setImages() {
			if(angular.isObject(vm.person.media)) {
				if(UtilService.isStringNotEmpty(vm.person.media.header)) {
					vm.header_crop = vm.header = currentHost() + person.url_images + vm.person.media.header;
				}
				if(UtilService.isStringNotEmpty(vm.person.media.profile)) {
					vm.profile_crop = vm.profile = currentHost() + person.url_images + vm.person.media.profile;
				}
			}
		}

		function searchPlace(place) {
			function onGetLocationSuccess(data) {
				if(data.items && angular.isArray(data.items)) {
					vm.showCities = data.items.length > 0 ? true : false;
					if(data.items.length > 0)
						vm.cities = angular.copy(data.items);
				}
			}

			if(place)
				locationDataService.getLocation({q: place}, onGetLocationSuccess, UtilService.onError);
			else {
				selectCity({
					city: null,
					country_code: null
				});
			}
		}

		function selectCity(city) {
			vm.person.personal_info.city = city.city;
			vm.person.personal_info.country = city.country_code;
			if(city.city && city.country_code)
				vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
			vm.showCities = false;
		}

		function save(form) {

			vm.sendingForm = true;

			function onError(error) {
				console.log(error);
				vm.sendingForm = false;
			}

			function onUploadProfileCroppedSuccess(data) {
				vm.person.media.profile_cropped = data.data.filename;
				var fileData = {
					person_id: vm.person.short_id,
					type: 'deviser-media-header-cropped',
					file: Upload.dataUrltoBlob(vm.header_cropped, "temp.png")
				}

				uploadDataService.UploadFile(fileData, onUploadHeaderCroppedSuccess, onError, console.log);
			}
			
			function onUploadHeaderCroppedSuccess(data) {
				vm.person.media.header_cropped = data.data.filename;
				personDataService.updateProfile(vm.person, {
					personId: vm.person.short_id,
				}, onUpdateProfileSuccess, onError)
			}

			function onUpdateProfileSuccess(data) {
				if(data.main_link)
					$window.location.href = data.main_link;
			}

			form.$setSubmitted();

			if(form.$valid && (!form.phone_prefix || validatePrefix())) {
				//callback hell
				var fileData = {
					person_id: vm.person.short_id,
					type: 'deviser-media-profile-cropped',
					file: Upload.dataUrltoBlob(vm.profile_cropped, "temp.png")
				}
				
				uploadDataService.UploadFile(fileData, onUploadProfileCroppedSuccess, onError, console.log);
			} 
			else {
				vm.sendingForm = false;
			}
		}

		//watches
		$scope.$watch('completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language]', function(newValue, oldValue) {
			if(newValue && newValue.length > vm.limit_text_short_description)
				vm.person.text_short_description[vm.description_language] = oldValue;
		});

		$scope.$watch('completeProfileCtrl.profile', function(newValue, oldValue) {
			function onUploadProfileSuccess(data) {
				vm.person.media.profile = angular.copy(data.data.filename);
				vm.profile_crop = newValue;
			}
			if(angular.isObject(newValue)) {
				//upload original
				var data = {
					person_id: vm.person.short_id,
					type: 'deviser-media-profile-original',
					file: newValue
				}
				uploadDataService.UploadFile(data, onUploadProfileSuccess, UtilService.onError, console.log);
			}
		});

		$scope.$watch('completeProfileCtrl.header', function(newValue, oldValue) {
			function onUploadHeaderSuccess(data) {
				vm.person.media.header = angular.copy(data.data.filename);
				vm.header_crop = newValue;
			}
			if(angular.isObject(newValue)) {
				//upload original
				var data = {
					person_id: vm.person.short_id,
					type: 'deviser-media-header-original',
					file: newValue
				}
				uploadDataService.UploadFile(data, onUploadHeaderSuccess, UtilService.onError, console.log);
			}
		})

	}

	angular
		.module('person')
		.controller('completeProfileCtrl', controller);

}());