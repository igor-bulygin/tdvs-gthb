(function () {

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService, localStorageService, tagDataService, productEvents, $rootScope) {
		var vm = this;
		vm.save = save;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();

		function init() {
			vm.product = new productDataService.ProductPriv();
			vm.product.categories = [];
			vm.product.media = {
				photos: [],
				description_photos: []
			};
			vm.product.faq = [];
			vm.product.options = {};
			vm.product.madetoorder = {
				type: 0
			};
			vm.product.preorder = {
				type: 0
			};
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

		function parseEmptyFields(obj) {
			for(var key in obj) {
				if(obj[key].length === 0)
					delete obj[key];
			}
		}

		function save(state) {
			var required = [];
			//set state of the product
			vm.product.product_state = angular.copy(state);

			//parse empty multilanguage fields
			parseEmptyFields(vm.product.name);
			parseEmptyFields(vm.product.description);

			//parse faq
			if(angular.isArray(vm.product.faq) && vm.product.faq.length > 0) {
				vm.product.faq.forEach(function(element) {
					parseEmptyFields(element.question);
					parseEmptyFields(element.answer)
				});
			}

			//check existing main photo
			var main_photo = false;
			if(angular.isArray(vm.product.media.photos) && vm.product.media.photos.length > 0) {
				vm.product.media.photos.forEach(function(element) {
					if(element.main_product_photo)
						main_photo=true;
				});
			}

			//validations
			if(vm.product.product_state !== 'product_state_draft') {
				//name
				if(!vm.product.name || !vm.product.name['en-US']) {
					required.push('name');
				}
				//categories
				if(angular.isArray(vm.product.categories) && vm.product.categories.length === 0) {
					required.push('categories');
				} else if(vm.product.categories.indexOf(null) > -1) {
					required.push('categories');
				}
				//photos
				if(angular.isArray(vm.product.media.photos) && vm.product.media.photos.length === 0) {
					required.push('photos');
				}

				if(angular.isArray(vm.product.media.photos) && vm.product.media.photos.length > 0 && !main_photo) {
					required.push('main_photo');
				}

				//description
				if(!vm.product.description || !vm.product.description['en-US']) {
					required.push('description');
				}
			} else {
				//check for null categories
				while(vm.product.categories.indexOf(null) > -1){
					var pos = vm.product.categories.indexOf(null);
					vm.product.categories.splice(pos, 1);
				}
			}

			if(required.length === 0) {
				if(vm.product.id) {
					vm.product.$update({
						idProduct: vm.product.id
					}).then(function(dataSaved) {
						console.log(dataSaved);
						vm.product = dataSaved;
						toastr.success('Saved!');
					});
				}
				else {
					vm.product.$save()
						.then(function (dataSaved) {
							console.log(dataSaved);
							vm.product = dataSaved;
							toastr.success('Saved!');
						}, function(err) {
							//send errors to components
							if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
								$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required})
						});
				}
			} else {
				$rootScope.$broadcast(productEvents.requiredErrors, {required: required});
			}

		}

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());