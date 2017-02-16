(function () {

	function controller(deviserDataService, metricDataService, sizechartDataService, 
		productDataService, languageDataService, toastr, UtilService, productService,
		localStorageService, tagDataService, productEvents, $rootScope, $window) {
		var vm = this;
		vm.save = save;
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		
		function init() {
			vm.product = {};
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
			vm.product.bespoke = {
				type: 0
			};
			vm.product.tags = {};
			vm.product.price_stock = [];

			getLanguages();
			getCategories();
			getDeviser();
			getTags();
			getMetric();
			getSizechart();
			getPaperType();
		}

		init();

		function onError(err) {
			console.log(err);
		}

		function getCategories() {
			function onGetCategoriesSuccess(data) {
				vm.allCategories = data.items;
			}

			productDataService.getCategories({scope: 'all'}, onGetCategoriesSuccess, onError);
		}

		function getMetric() {
			metricDataService.Metric.get()
				.$promise.then(function(dataMetric) {
					vm.metric = dataMetric;
				}, function(err) {
					toastr.error(err);
				});
		}


		function getSizechart() {
			function onGetSizechartSuccess(data) {
				vm.sizecharts = data.items;
			}
			sizechartDataService.getSizechart({
				scope: 'all'
			}, onGetSizechartSuccess, onError);
		}

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function (dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function (err) {
					toastr.error(err);
				});
		}

		function getTags() {
			tagDataService.Tags.get()
				.$promise.then(function (dataTags) {
					vm.tags = dataTags.items;
				}, function(err) {
					//err
				});
		}

		function getPaperType() {
			function onGetPaperTypeSuccess(data) {
				vm.papertypes = data.items;
			}

			productDataService.getPaperType(onGetPaperTypeSuccess, onError);
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

		function saved_draft(){
			vm.product = productService.parseProductFromService(vm.product);
			toastr.success('Saved!');
		}

		function product_published() {
			$window.location.href = currentHost() + vm.link_profile + '?published=true';
		}

		function save(state) {
			function onUpdateProductSuccess(data) {
				vm.disable_save_buttons = false;
				if(state === 'product_state_draft') {
					saved_draft();
				} else if(state === 'product_state_active') {
					product_published();
				}
			}

			function onUpdateProductError(err) {
				vm.disable_save_buttons = false;
				if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
					$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required})
			}
			
			function onSaveProductSuccess(data) {
				vm.disable_save_buttons = false;
				if(state==='product_state_draft') {
					saved_draft();
				} else if(state === 'product_state_active') {
					vm.disable_save_buttons = false;
					product_published();
				}
			}
			
			function onSaveProductError(err) {
				vm.disable_save_buttons = false;
				vm.errors = true;
				//send errors to components
				if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
					$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required})
			}

			vm.disable_save_buttons = true;
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

			if(vm.product.product_state !== 'product_state_draft') {
				required = productService.validate(vm.product);
			} else {
				//check for null categories
				while(vm.product.categories.indexOf(null) > -1){
					var pos = vm.product.categories.indexOf(null);
					vm.product.categories.splice(pos, 1);
				}
			}

			if(required.length === 0) {
				if(vm.product.id) {
					productDataService.updateProductPriv(vm.product, {
						idProduct: vm.product.id
					}, onUpdateProductSuccess, onUpdateProductError);
				}
				else {
					productDataService.postProductPriv(vm.product, onSaveProductSuccess, onSaveProductError);
				}
			} else {
				vm.disable_save_buttons = false;
				vm.errors = true;
				$rootScope.$broadcast(productEvents.requiredErrors, {required: required});
			}
		}

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());