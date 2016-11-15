(function () {
	"use strict";

	function controller(productDataService, toastr, Upload, $scope, UtilService, $uibModal, $rootScope, productEvents) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.name_language = 'en-US';
		vm.categories_helper = [];
		vm.images = [];
		vm.addCategory = addCategory;
		vm.categorySelected = categorySelected;
		vm.deleteCategory = deleteCategory;
		vm.openCropModal = openCropModal;
		vm.uploadPhoto = uploadPhoto;
		vm.deleteImage = deleteImage;
		
		function init(){
			//init values or functions
		}

		init();

		function addCategory() {
			vm.categories_helper.push({
				categories_selected: [null],
				categories: [vm.rootCategories]
			})
			vm.product.categories.push(null);
		}

		function categorySelected(category, index_helper, index) {
			vm.categories_helper[index_helper].categories_selected[index] = category;
			//if we change an option with "child" selects
			if(index < vm.categories_helper[index_helper].categories_selected.length-1) {
				while(vm.categories_helper[index_helper].categories_selected.length-1 > index) {
					vm.categories_helper[index_helper].categories_selected.splice(vm.categories_helper[index_helper].categories_selected.length-1, 1);
					vm.categories_helper[index_helper].categories.splice(vm.categories_helper[index_helper].categories.length-1, 1);
					}
			}
			//if there are child categories
			if(filterCategory(vm.categories,category).length > 0) {
				vm.categories_helper[index_helper].categories[index+1] = filterCategory(vm.categories, category);
				vm.categories_helper[index_helper].categories_selected[index+1] = null;
			} else {
				//if not
				vm.product.categories[index_helper] = category;
				//send event to get tags by category
				$rootScope.$broadcast(productEvents.setTagsFromCategory, {categories: vm.product.categories});
			}
		}

		function filterCategory(categories, id) {
			var sub_categories = [];
			for(var i = 0; i < categories.length; i++) {
				var split_fathers = categories[i].path.split('/');
				for(var j = 0; j < split_fathers.length; j++) {
					if(split_fathers[j] === "")
						split_fathers.splice(j, 1);
				}
				var father = split_fathers[split_fathers.length-1];
				if(father === id)
					sub_categories.push(categories[i]);
			}
			return sub_categories;
		}

		function deleteCategory(index) {
			if(index >= 0) {
				vm.product.categories.splice(index, 1);
				vm.categories_helper.splice(index, 1);
				$rootScope.$broadcast(productEvents.setTagsFromCategory, {categories: vm.product.categories});
			}
		}

		function uploadPhoto(images, errImages) {
			vm.files = images;
			vm.errFiles = errImages;

			//upload photos
			angular.forEach(vm.files, function(file) {
				var data = {
					deviser_id: UtilService.returnDeviserIdFromUrl(),
					file: file
				};
				var type;
				if(vm.product.id){
						data['type'] = 'known-product-photo';
						data['product_id'] = vm.product.id;
					}
				else {
					data['type'] = 'unknown-product-photo';
				}
				Upload.upload({
					url: productDataService.Uploads,
					data: data
				}).then(function(dataUpload) {
					//parse images
					vm.images.unshift({
						url: currentHost() + '/' + dataUpload.data.url
					})
					vm.product.media.photos.unshift({
						name: dataUpload.data.filename
					});
				})
			})
		}

		function openCropModal(photo, index) {
			var modalInstance = $uibModal.open({
				component: 'modalCrop',
				resolve: {
					photo: function() {
						return photo;
					},
					type: function () {
						return 'work_photo';
					}
				}
			});

			modalInstance.result.then(function(imageCropped) {
				if(imageCropped) {
					//upload image
					var data = {
						deviser_id: UtilService.returnDeviserIdFromUrl(),
						file: Upload.dataUrltoBlob(imageCropped, "temp.png")
					}
					var type;
					if(vm.product.id) {
							type = 'known-product-photo';
							data['product_id'] = vm.product.id;
						}
					else {
						type = 'unknown-product-photo';
					}
					data['type'] = type;
					Upload.upload({
						url: productDataService.Uploads,
						data: data
					}).then(function(dataUpload) {
						//set image filename in vm.images[index].url
						vm.images[index].url = currentHost() + '/' + dataUpload.data.url;
						//set image filename in vm.product.media.photos[index].filename
						vm.product.media.photos[index].name = dataUpload.data.filename;
						unSetMainPhoto();
						vm.product.media.photos[index]['main_product_photo'] = true;
					})
				}
			}, function(err) {
				//errors
			})
		}

		function deleteImage(index) {
			if(index > -1) {
				vm.images.splice(index, 1);
				vm.product.media.photos.splice(index, 1);
			}
		}

		function unSetMainPhoto() {
			for(var i = 0; i < vm.product.media.photos.length; i++) {
				if(vm.product.media.photos[i].main_product_photo)
					delete vm.product.media.photos[i].main_product_photo;
			}
		}

		//watches
		$scope.$watch('productBasicInfoCtrl.product', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				//watch product
			}
		});

		//when get categories, set first
		$scope.$watch('productBasicInfoCtrl.categories', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				vm.rootCategories = filterCategory(newValue, '');
				addCategory();
			}
		});

		//watch name error
		$scope.$watch('productBasicInfoCtrl.product.name', function(newValue, oldValue) {
			if(newValue && newValue['en-US'] && newValue['en-US'].length > 0) {
				vm.nameRequired = false;
			} else if(oldValue && oldValue['en-US'] && newValue['en-US'].length === 0) {
				vm.nameRequired = true;
			}
		}, true);

		//watch photos and main photo errors
		$scope.$watch('productBasicInfoCtrl.product.media.photos', function (newValue, oldValue) {
			if(newValue.length > 0 && vm.photosRequired) {
				vm.photosRequired = false;
			}
			if(vm.mainPhotoRequired) {
				newValue.forEach(function (element) {
					if(element.main_product_photo)
						vm.mainPhotoRequired = false;
				});
			}
		}, true);

		//watch categories errors
		$scope.$watch('productBasicInfoCtrl.product.categories', function(newValue, oldValue) {
			if(newValue.indexOf(null) === -1) {
				vm.form.$submitted = false;
			}
		}, true);

		//events
		$scope.$on(productEvents.requiredErrors, function(event, args){
			//set name error
			if(args.required.indexOf('name') > -1) {
				vm.nameRequired = true;
			}
			//set photos error
			if(args.required.indexOf('photos') > -1) {
				vm.photosRequired = true;
			}
			//set main photo error
			if(args.required.indexOf('main_photo') > -1) {
				vm.mainPhotoRequired = true;
			}
			//set categories error
			if(args.required.indexOf('categories') > -1) {
				vm.form.$setSubmitted();
			}
		})
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/basic-info/basic-info.html',
		controller: controller,
		controllerAs: 'productBasicInfoCtrl',
		bindings: {
			product: '=',
			categories: '<',
			languages: '<',
			images: '<'
		}
	}

	angular
		.module('todevise')
		.component('productBasicInfo', component);

}());