(function () {
	"use strict";

	function controller(UtilService, Upload, uploadDataService, $uibModal) {
		var vm = this;
		vm.upload = upload;
		vm.person = person;

		init();

		function init() {
			if(vm.story.main_photo_url)
				vm.image = angular.copy(vm.story.main_photo_url);
		}

		function upload(file) {
			function onUploadFileSuccess(data) {
				var modalInstance = $uibModal.open({
					component: 'modalCrop',
					resolve: {
						photo: function () {
							return data.data.url;
						},
						type: function() {
							return 'header_cropped';
						},
						person: function() {
							return vm.person;
						}
					}
				})

				modalInstance.result.then(function (croppedData) {
					vm.image = angular.copy(croppedData.data.url);
					vm.story['main_media'] = {
						type: 1,
						photo: croppedData.data.filename
					};
				}, function () {
					console.log("dismissed");
				})
			}

			var data = {
				type: 'story-photos',
				person_id: person.short_id,
				file: file
			}
			uploadDataService.UploadFile(data, 
				function(data) {
					return onUploadFileSuccess(data, file)
				},
				UtilService.onError,
				function(evt) {
					return console.log(evt);
				})
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/person/stories/main-media/main-media.html',
		controller: controller,
		controllerAs: 'storyMainMediaCtrl',
		bindings: {
			story: '<',
		}
	}

	angular
		.module('person')
		.component('storyMainMedia', component);

}());