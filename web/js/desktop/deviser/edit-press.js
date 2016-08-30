(function () {
	"use strict";

	function controller(deviserDataService, Upload) {
		var vm = this;
		vm.upload = upload;

		function getDeviser() {
			deviserDataService.Profile.get().$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
			});
		}

		function init() {
			getDeviser();
		}

		function upload(form) {
			if (form.$valid) {
				var data = {
					type: "press"
				};
				data['file'] = vm.image;
				Upload.upload({
					url: deviserDataService.Uploads,
					data: data
				}).then(function (dataUpload) {
					console.log(dataUpload);
				}, function (err) {
					console.log(err);
				})
			}
		}

		init();


	}

	angular.module('todevise', ['api', 'ngFileUpload'])
		.controller('editPressCtrl', controller);

}());