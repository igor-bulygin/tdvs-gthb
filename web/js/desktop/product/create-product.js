(function () {

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService, localStorageService, tagDataService) {
		var vm = this;
		vm.save = save;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();

		function init() {
			vm.product = new productDataService.ProductPriv();
			vm.product = {
				categories: [],
				media: {
					photos: [],
					description_photos: []
				},
				faq: [],
				tags: [],
				madetoorder: {
					type: 0
				},
				preorder: {
					type: 0
				}
			}
			getLanguages();
			getCategories();
			getDeviser();
			getTags();
			//getStorage();
			
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
				vm.link_profile = '/deviser/' + dataDeviser.slug + '/' + dataDeviser.id + '/store/edit';
				vm.profile = currentHost()+vm.deviser.url_images+vm.deviser.media.profile_cropped;
				vm.product.deviser_id = dataDeviser.id;
			}, function(err) {
				//err
			});
		}

		function getTags() {
			tagDataService.Tags.get()
				.$promise.then(function (dataTags) {
					vm.tags = dataTags.items;
				}, function(err) {
					//err
				})
		}

		function getStorage() {
			// vm.products = localStorageService.get('draftProducts');
			// if(vm.products === undefined || vm.products === null) {
			// 	vm.products = [];
			// 	localStorageService.set('draftProducts', vm.products);
			// }
			vm.product = new productDataService.ProductPriv();
			vm.product.categories = [];
			vm.product.id = vm.products.length+1;
			vm.product.media = {
				photos: [],
				description_photos: []
			}
			vm.product.faq = [];
			vm.product.tags = [];
			vm.product.madetoorder = {
				type: 0
			}
		}

		function save() {
			//vm.products.push(vm.product);
			//localStorageService.set('draftProducts', vm.products);
			vm.product.$save()
				.then(function (dataSaved) {
					toastr.success('Saved!');
					console.log(dataSaved);
				});
		}

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());