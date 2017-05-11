(function () {
	"use strict";

	function controller(Upload, uploadDataService, UtilService, dragndropService) {
		var vm = this;
		vm.uploadPhotos = uploadPhotos;

		function uploadPhotos(images, errImages) {
			if(!angular.isArray(vm.component.items))
				vm.component.items = [];
			
			function onUploadPhotoSuccess(data) {
				vm.component.items.push({
					position: vm.component.items.length,
					photo: data.data.filename,
					url: data.data.url
				})
			}

			images.forEach(function(image) {
				var data = {
					type: 'story-photos',
					person_id: person.short_id,
					file: image
				}
				uploadDataService.UploadFile(data, 
					function(data) {
						return onUploadPhotoSuccess(data)
					},
					UtilService.onError,
					function(evt) {
						return console.log(evt);
					});
			});
		}
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/photo-component/photo-component.html",
		controller: controller,
		controllerAs: "storyPhotoComponentCtrl",
		bindings: {
			component: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyPhotoComponent', component);

}());