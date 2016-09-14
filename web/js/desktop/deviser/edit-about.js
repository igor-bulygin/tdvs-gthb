(function () {
	"use strict";
	
	function controller(deviserDataService, UtilService, languageDataService, toastr, productDataService) {
		var vm = this;
		
		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
			}, function (err) {
				toastr.error(err);
			});
		}
		
		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function(dataLanguages) {
					vm.languages = dataLanguages;
			}, function(err) {
				toastr.error(err);
			})
		}
		
		function getCategories() {
			productDataService.Categories.get()
				.$promise.then(function(dataCategories) {
					vm.categories = dataCategories.items;
			})
		}
		
		function init() {
			getDeviser();
			getLanguages();
			getCategories();
		}
		
		init();
		
	}
	
	
	angular
		.module('todevise', ['api', 'util', 'toastr'])
		.controller('editAboutCtrl', controller);
}());