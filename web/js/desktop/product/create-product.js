(function () {

	function controller(deviserDataService, sizechartDataService, productDataService, languageDataService, toastr, UtilService, localStorageService, tagDataService, productEvents, $rootScope) {
		var vm = this;
		vm.save = save;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		
		function init() {
			vm.product = new productDataService.ProductPriv();
			vm.product.slug = {};
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
			vm.product.tags = {};
			getLanguages();
			getCategories();
			getDeviser();
			getTags();
			getSizechart();

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

		function getSizechart() {
			sizechartDataService.Sizechart.get({scope: 'all'})
				.$promise.then(function (dataSizechart) {
					vm.sizechart = dataSizechart;
					console.log(vm.sizechart);
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

		function save(state) {
			var required = [];
			//set state of the product
			vm.product.product_state = angular.copy(state);

			//parse empty multilanguage fields
			UtilService.parseMultiLanguageEmptyFields(vm.product.name);
			UtilService.parseMultiLanguageEmptyFields(vm.product.description);

			//parse faq
			if(angular.isArray(vm.product.faq) && vm.product.faq.length > 0) {
				vm.product.faq.forEach(function(element) {
					UtilService.parseMultiLanguageEmptyFields(element.question);
					UtilService.parseMultiLanguageEmptyFields(element.answer)
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
						var options_to_convert = ['name', 'description', 'slug', 'sizechart', 'preorder', 'returns', 'warranty', 'tags'];
						for(var i=0; i < options_to_convert.length; i++) {
							vm.product[options_to_convert[i]] = UtilService.emptyArrayToObject(vm.product[options_to_convert[i]]);
						}
						toastr.success('Saved!');
					}, function (err) {
						console.log(err);
						if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
								$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required})
					});
				}
				else {
					vm.product.$save()
						.then(function (dataSaved) {
							toastr.success('Saved!');
							var options_to_convert = ['name', 'description', 'slug', 'sizechart', 'preorder', 'returns', 'warranty'];
							for(var i=0; i < options_to_convert.length; i++) {
								vm.product[options_to_convert[i]] = UtilService.emptyArrayToObject(vm.product[options_to_convert[i]]);
							}
						}, function(err) {
							console.log(err);
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