(function () {

	'use strict';

	function controller($scope, $timeout, $deviser, $deviser_util, toastr, $uibModal, Upload, $http, $product, $product_util) {
		var vm = this;

		vm.deviser = _deviser;
		vm.headerphoto = null;
		vm.profilephoto = null;

		vm.crop_header = crop_header;
		vm.crop_profile = crop_profile;
		vm.new_product = new_product;
		vm.save = save;

		$scope.$watch("deviserCtrl.headerphoto", function (n, o) {
			if (vm.pause_watch_headerphoto === true) return;
			if (n === null && o === null) return;
			//Restore old profile picture if select dialog is canceled
			if (n === null) {
				vm.headerphoto = o;
			}

			//Show the crop dialog after an image is selected
			// if (n && n.name !== undefined) {
			// 	vm.crop_header();
			// }
		});

		$scope.$watch("profilephoto", function (n, o) {
			if (vm.pause_watch_profilephoto === true) return;
			if (n === null && o === null) return;

			//Restore old profile picture if select dialog is canceled
			if (n === null) {
				vm.profilephoto = o;
			}

			//Show the crop dialog after an image is selected
			// if (n && n.name !== undefined) {
			// 	vm.crop_profile();
			// }
		});

		function crop_header() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/deviser/crop_rectangle.html',
				controller: 'cropCtrl',
				backdrop: 'static',
				resolve: {
					data: function () {
						return {
							'photo': vm.headerphoto
						}
					}
				}
			});

			modalInstance.result.then(function (data) {
				vm.pause_watch_headerphoto = true;
				vm.headerphoto = data.croppedphoto;

				//start watching again in the next digest
				$timeout(function () {
					vm.pause_watch_headerphoto = false;
				});
			}, function () {
				//Cancel
			});
		};

		function crop_profile() {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/deviser/crop_circle.html',
				controller: 'cropCtrl',
				backdrop: 'static',
				resolve: {
					data: function () {
						return {
							'photo': vm.profilephoto
						}
					}
				}
			});

			modalInstance.result.then(function (data) {
				vm.pause_watch_profilephoto = true;
				vm.profilephoto = data.croppedphoto;

				//start watching again in the next digest
				$timeout(function () {
					vm.pause_watch_profilephoto = false;
				});
			}, function () {
				//Cancel
			});
		};

		function new_product() {
			var _product = $product_util.newProduct(vm.deviser.short_id);
			$product.modify("POST", _product).then(function (data) {
				window.location.href = window.location.origin + "/" + vm.deviser.slug + "/edit-work/" + data.short_id + "/";
			}, function (err) {
				toastr.error("Couldn't create product!", err);
			});
		}

		function save() {
			$deviser.modify("POST", vm.deviser).then(function () {
				toastr.success("Deviser saved successfully!");
			}, function (err) {
				toastr.error("Failed saving deviser!", err);
			});

			if (vm.headerphoto) {
				Upload.upload({
					headers: {
						'X-CSRF-TOKEN': yii.getCsrfToken()
					},
					url: _upload_header_photo_url,
					data: {
						'file': vm.headerphoto
					}
				}).then(function (resp) {
					//console.log('file ' + resp.config.file.name + 'uploaded. Response: ' + resp.data);
					vm.deviser = resp.data;
					toastr.success("Uploaded successfully headerphoto photo", resp.config.data.file.name);
				}, function (err) {
					toastr.error("Error while uploading headerphoto photo", err)
				}, function (e) {
					//var progressPercentage = parseInt(100.0 * e.loaded / e.total);
					//console.log('progress: ' + progressPercentage + '% ' + e.config.file.name);
				});
			}

			if (vm.profilephoto) {
				Upload.upload({
					headers: {
						'X-CSRF-TOKEN': yii.getCsrfToken()
					},
					url: _upload_profile_photo_url,
					data: {
						'file': vm.profilephoto
					}
				}).then(function (resp) {
					//console.log('file ' + config.file.name + 'uploaded. Response: ' + data);
					vm.deviser = resp.data;
					toastr.success("Uploaded successfully profile photo", resp.config.data.file.name);
				}, function (err) {
					toastr.error("Error while uploading profile photo", err)
				}, function (e) {
					//var progressPercentage = parseInt(100.0 * e.loaded / e.total);
					//console.log('progress: ' + progressPercentage + '% ' + e.config.file.name);
				});
			}
		}

	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'angular-img-dl', 'global-deviser', 'global-desktop', 'api', 'ngFileUpload', 'ngImgCrop'])
		.controller('deviserCtrl', controller)

}());