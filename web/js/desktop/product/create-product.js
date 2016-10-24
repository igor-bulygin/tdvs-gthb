(function () {

	function controller(productDataService, languageDataService, toastr) {
		var vm = this;
		vm.product = {
			categories: [],
			media: {
				photos: []
			}
		};

		function init() {
			getLanguages();
			getCategories();
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

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());