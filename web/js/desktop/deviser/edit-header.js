(function () {
	"use strict";

	function controller(deviserDataService, languageDataService, UtilService, Upload, $uibModal, toastr, $scope, $rootScope, locationDataService, $location, deviserEvents) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.isProfilePublic = false;
		vm.description_language = "en-US";
		vm.openCropModal = openCropModal;
		vm.openConfirmationModal = openConfirmationModal;
		vm.updateAll = updateAll;
		vm.selectCity = selectCity;
		vm.searchPlace = searchPlace;
		vm.restoreDeviser = restoreDeviser;
		vm.limit_text_biography = 140;
		vm.showCities = false;

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
				parseDeviserInfo(vm.deviser);
			}, function (err) {
				toastr.error(err);
			});
		}

		function parseDeviserInfo(deviser){
			function setHostImage(image){
				return currentHost() + deviser.url_images + image;
			}
			//set name
			if(deviser.personal_info.brand_name)
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

		function searchPlace(place) {
			locationDataService.Location.get({
				q: place
			}).$promise.then(function(dataLocation) {
				if(dataLocation.items.length > 0){
					vm.showCities = true;
					vm.cities=dataLocation.items;
					}
				else if(dataLocation.items.length === 0) {
					vm.showCities = false;
				}
			});
		}

		function selectCity(city) {
			vm.deviser.personal_info.city = city.city;
			vm.deviser.personal_info.country = city.country_code;
			vm.city = vm.deviser.personal_info.city + ', ' + vm.deviser.personal_info.country;
			vm.showCities = false;
		}

		function updateAll() {
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-update-profile";
			patch.deviser_id = vm.deviser.id;
			for(var key in vm.deviser) {
				//delete unwanted tags on text_biography
				if(key === 'text_biography') {
					for(var language in vm.deviser[key]) {
						vm.deviser[key][language]=vm.deviser[key][language].replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
					}
				}
				if(key!=='account_state')
					patch[key] = angular.copy(vm.deviser[key]);
			}
			patch.$update().then(function(updateData) {
				$rootScope.$broadcast('deviser-updated');
				vm.deviser_changed = false;
			}, function(err) {
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
			});
		}

		function setDeviserChanged(value) {
			vm.deviser_changed = value;
		}

		function restoreDeviser(){
			parseDeviserInfo(vm.deviser_original);
			vm.deviser = angular.copy(vm.deviser_original);
		}

		//modals
		function openConfirmationModal(link) {
			var modalInstance = $uibModal.open({
				component: 'modalConfirmLeave',
				resolve: {
					link: function() {
						return link;
					}
				}
			});

			modalInstance.result.then(function(link) {
				if(link) {
					//save changes then go away
					updateAll();
					console.log("I have to go!");
				}
			}, function () {
				console.log("dismissed");
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

		//checks char limit on text_short_description
		$scope.$watch('editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]', function (newValue, oldValue) {
			if (newValue && newValue.length > vm.limit_text_biography)
				vm.deviser.text_short_description[vm.description_language] = oldValue;
		});

		//checks for new changes in deviser (sets deviser_changed to true or false)
		$scope.$watch('editHeaderCtrl.deviser', function (newValue, oldValue) {
			if(newValue) {
				if(!angular.equals(newValue, vm.deviser_original)) {
					setDeviserChanged(true);
					$rootScope.$broadcast(deviserEvents.deviser_changed, {value: true, deviser: newValue});
				} else {
					setDeviserChanged(false);
				}
			}
		}, true);

		//events
		$scope.$on(deviserEvents.deviser_changed, function(event, args) {
			setDeviserChanged(args.value);
			if(args.deviser)
				vm.deviser = angular.copy(args.deviser);
		});

		$scope.$on(deviserEvents.deviser_updated, function (event, args) {
			getDeviser();
		});

		$scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
			console.log("set required header", args);
			//set form as submitted
			vm.form.$setSubmitted();
			//check for both header and profile requireds
			for(var i=0; i<args.required_fields.length; i++) {
				if(args.required_fields[i]==='profile')
					//set profile required
					vm.profileRequired = true;
				if(args.required_fields[i]==='header')
					//set header required
					vm.headerRequired = true;
			}
		})

		$scope.$on('$locationChangeStart', function(ev, newUrl, oldUrl) {
			ev.preventDefault();
			if(vm.deviser_changed) {
				openConfirmationModal();
			}
		});

	}

	angular
		.module('todevise')
		.controller('editHeaderCtrl', controller);
}());