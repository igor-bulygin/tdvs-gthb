(function () {
	"use strict";

	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService) {
		var vm = this;
		vm.update = update;
		vm.biography_language = "en-US";

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
			}, function (err) {
				toastr.error(err);
			});
		}


		function getCategories() {
			productDataService.Categories.get()
				.$promise.then(function (dataCategories) {
					vm.categories = dataCategories.items;
				}, function (err) {
					toastr.error(err);
				});
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
			getCategories();
		}

		init();

		function update() {
			//update model
		}

	}


	angular
		.module('todevise', ['api', 'util', 'toastr', 'nya.bootstrap.select', 'textAngular'])
		.controller('editAboutCtrl', controller);
}());