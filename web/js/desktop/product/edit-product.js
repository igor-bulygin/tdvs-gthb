(function() {

	"use strict";

	function controller(deviserDataService, productDataService, languageDataService, toastr, UtilService, tagDataService, $scope, $rootScope, productEvents, $location) {
		var vm = this;
		
				
		vm.deviser_id = UtilService.returnDeviserIdFromUrl();
		vm.product_id = UtilService.returnProductIdFromUrl();
		vm.categories_helper = [];
		vm.image_helper = [];
		vm.images = [];
		
		
		function init(){
			getLanguages();
			getCategories();
			getDeviser();
			getProduct();
		}

		init();

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function(dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function(err) {
					toastr.erro(err);
				});

		}

		function getCategories() {
			productDataService.Categories.get({scope: 'all'})
				.$promise.then(function (dataCategories) {
					vm.allCategories = dataCategories.items;
				}, function(err) {

				});
		}
		//The getDeviser probably can be deleted.
		function getDeviser(){
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function(dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
			}, function(err) {

			});

		}
		
		function getProduct() {
			productDataService.ProductPriv.get({
				idProduct: UtilService.returnProductIdFromUrl()
			}).$promise.then(function(dataProduct) {
				vm.product = dataProduct;
				vm.product_original = angular.copy(dataProduct);

				angular.forEach(dataProduct.categories, function(value, key){

					angular.forEach(vm.allCategories, function(values,keys){
						
						if(value == values.id){
							var catHelp =[]
							catHelp = values.path.split("/");
							catHelp.shift();
							catHelp.pop();
							catHelp.push(value);
							vm.categories_helper[key]=catHelp;
						}
					});
				});
				
				for (var i = 0; i < vm.product.media.photos.length; i++) {
				vm.images[i] = {
					pos: i,
					url: currentHost() + vm.product.url_images + vm.product.media.photos[i].name,
					filename: vm.product.media.photos[i].name
					};
				}	
				
				

			}, function (err) {
				toastr.error(err);
			});
		}
		
		
	//}
		
}

	angular
		.module('todevise')
		.controller('editProductCtrl',controller);

}());