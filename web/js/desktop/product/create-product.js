(function () {

	function controller(deviserDataService, metricDataService, sizechartDataService, 
		productDataService, languageDataService, toastr, UtilService, productService,
		localStorageService, tagDataService, productEvents, $rootScope, $location) {
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

		function getCategories() {
			productDataService.Categories.get({scope: 'all'})
				.$promise.then(function(dataCategories) {
					vm.allCategories = dataCategories.items;
				}, function(err) {
					toastr.error(err);
				});
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
			sizechartDataService.Sizechart.get({scope: 'all'})
				.$promise.then(function (dataSizechart) {
					vm.sizecharts = dataSizechart.items;
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

		function getTags() {
			tagDataService.Tags.get()
				.$promise.then(function (dataTags) {
					vm.tags = dataTags.items;
				}, function(err) {
					//err
				});
		}

		function getPaperType() {
			productDataService.PaperType.get()
				.$promise.then(function (dataPaperType) {
					vm.papertypes = dataPaperType.items;
				}, function (err){
					console.log(err);
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

		function saved_draft(){
			vm.product = productService.parseProductFromService(vm.product);
			toastr.success('Saved!');
		}

		function product_published() {
			$location.href = currentHost() + vm.link_profile + '?published=true';
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
					vm.product.$update({
						idProduct: vm.product.id
					}).then(function(dataSaved) {
						if(state === 'product_state_draft') {
							saved_draft();
						} else if(state === 'product_state_active') {
							product_published();
						}
					}, function (err) {
						if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
								$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required})
					});
				}
				else {
					vm.product.$save()
						.then(function (dataSaved) {
							if(state==='product_state_draft') {
								saved_draft();
							} else if (state === 'product_state_active') {
								product_published();
							}
						}, function(err) {
							vm.errors = true;
							//send errors to components
							if(err.data.errors && err.data.errors.required && angular.isArray(err.data.errors.required))
								$rootScope.$broadcast(productEvents.requiredErrors, {required: err.data.errors.required})
						});
				}
			} else {
				vm.errors = true;
				$rootScope.$broadcast(productEvents.requiredErrors, {required: required});
			}

		}

	}

	angular
		.module('todevise')
		.controller('createProductCtrl', controller);

}());