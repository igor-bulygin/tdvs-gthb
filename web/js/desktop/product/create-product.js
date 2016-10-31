(function () {

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService) {
		var vm = this;
		vm.product = {
			categories: [],
			media: {
				photos: []
			},
			faq: [],
			tags: []
		};

		function init() {
			getLanguages();
			getCategories();
			getDeviser();
		}

		init();

		function getCategories() {
			productDataService.Categories.get({scope: 'all'})
				.$promise.then(function(dataCategories) {
					vm.allCategories = dataCategories.items;
				}, function(err) {
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

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.profile = currentHost()+vm.deviser.url_images+vm.deviser.media.profile_cropped;
			});
		}

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());