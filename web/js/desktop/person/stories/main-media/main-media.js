(function () {
	"use strict";

	function controller(UtilService, Upload, uploadDataService) {
		var vm = this;
		vm.upload = upload;

		function upload(file) {
			function onUploadFileSuccess(data) {
				vm.image = angular.copy(data.data.url);
				vm.story['main_media'] = {
					type: 1,
					photo: data.data.filename
				};
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
		.module('todevise')
		.component('storyMainMedia', component);

}());