(function () {
	"use strict";

	function controller($scope, deviserDataService, Upload, toastr, UtilService) {
		var vm = this;
		vm.upload = upload;
		vm.update = update;

		function parsePress(press, url) {
			var images = [];
			for (var i = 0; i < press.length; i++) {
				images[i] = {
					pos: i,
					url: currentHost() + url + press[i],
					filename: press[i]
				};
			}
			return images;
		}

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				if (!vm.deviser.press)
					vm.deviser.press = [];
				vm.images = parsePress(vm.deviser.press, vm.deviser.url_images);
			}, function (err) {
				toastr.error(err);
			});
		}

		function init() {
			getDeviser();
		}

		$scope.$watch('editPressCtrl.image', function (newValue, oldValue) {
			if (newValue)
				upload(newValue);
		});

		function update(index) {
			if (index >= 0) {
				vm.images.splice(index, 1);
			}
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-press-update";
			patch.press = [];
			patch.deviser_id = vm.deviser.id;
			vm.images.forEach(function (element) {
				patch.press.push(element.filename);
			});
			patch.$update().then(function (dataPress) {
				//console.log("dataPress", dataPress);
			}, function (err) {
				toastr.error(err);
			});
		}

		function upload(image) {
			var data = {
				type: "deviser-press",
				deviser_id: vm.deviser.id,
				file: image
			};
			Upload.upload({
				url: deviserDataService.Uploads,
				data: data
			}).then(function (dataUpload) {
				toastr.success("Photo uploaded!");
				vm.deviser.press.unshift(dataUpload.data.filename);
				vm.images = parsePress(vm.deviser.press, vm.deviser.url_images);
				update();
			}, function (err) {
				toastr.error(err);
			}, function (evt) {
				var progress = parseInt(100.0 * evt.loaded / evt.total);
				console.log('progress: ' + progress + '% ' + evt.config.data.file.name);
			});
		}

		init();

	}

	angular.module('todevise', ['api', 'ngFileUpload', 'dndLists', 'toastr', 'util'])
		.controller('editPressCtrl', controller);

}());