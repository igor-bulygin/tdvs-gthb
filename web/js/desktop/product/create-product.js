(function () {

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService) {
		var vm = this;
		vm.save = save;

		function init() {
			getLanguages();
			getCategories();
			getDeviser();
			vm.product = new productDataService.ProductPriv();
			vm.product.categories = [];
			vm.product.media = {
				photos: [],
				description_photos: []
			}
			vm.product.faq = [];
			vm.product.tags = [];
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
				vm.product.deviser_id = dataDeviser.id;
			});
		}

		function save() {
			console.log(vm.product);
			vm.product.$save()
				.then(function (dataSaved) {
					console.log(dataSaved);
				});
		}

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());