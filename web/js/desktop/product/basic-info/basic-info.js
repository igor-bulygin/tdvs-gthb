(function () {
	"use strict";

	function controller(productDataService, toastr, Upload, $scope, UtilService, $uibModal, $rootScope, 
		productEvents, $timeout, dragndropService) {
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
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.moved = moved;
		vm.canceled = canceled;
		
		function init(){
			//init values or functions
		}

		init();

		//categories
		function addCategory() {
			vm.categories_helper.push({
				categories_selected: [null],
				categories: [vm.rootCategories]
			})
			vm.product['categories'].push(null);
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
				$rootScope.$broadcast(productEvents.setVariations, {categories: vm.product.categories});
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

		//photos
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
					$timeout(function () {
						delete file.progress;
					}, 1000);
				}, function (err) {
					//errors
				}, function (evt) {
					file.progress = parseInt(100.0 * evt.loaded / evt.total);
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
						//set image filename in helper
						unSetMainPhoto();
						vm.images[index].url = currentHost() + '/' + dataUpload.data.url;
						//set image filename in model cropped name
						vm.product.media.photos[index]['name_cropped'] = angular.copy(dataUpload.data.filename);
						vm.product.media.photos[index]['main_product_photo'] = true;
						parseImages();
					}, function(err) {
						UtilService.onError(err);
					}, function(evt) {
						//evt when marking as main photo
					})
				}
			}, function(err) {
				UtilService.onError(err);
			})
		}

		function deleteImage(index) {
			if(index > -1) {
				vm.images.splice(index, 1);
				vm.product.media.photos.splice(index, 1);
			}
		}

		function unSetMainPhoto() {
			var main_photo = vm.product.media.photos.find((photo) => {
				return photo.main_product_photo;
			});
			delete main_photo.main_product_photo;
			if(main_photo.name_cropped)
				delete main_photo.name_cropped;
		}

		/* drag and drop functions */
		function dragStart(index) {
			dragndropService.dragStart(index, vm.images);
		}

		function dragOver(index) {
			vm.images = dragndropService.dragOver(index, vm.images);
			return true;
		}

		function moved(index) {
			vm.images = dragndropService.moved(vm.images);
			vm.product.media.photos = vm.images.map(e => Object.assign({}, e.filename));
		}

		function canceled(){
			vm.images = dragndropService.canceled();
		}

		function parseImages() {
			vm.images = UtilService.parseImagesUrl(vm.product.media.photos, '/uploads/product/' + vm.product.id + '/');
		}

		//watches
		$scope.$watch('productBasicInfoCtrl.product', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				//watch product
			}
		});

		$scope.$watch('productBasicInfoCtrl.product.categories', function(newValue, oldValue) {
			if(angular.isArray(oldValue) && oldValue[0]===null && angular.isArray(newValue) && newValue.length > 0 && vm.product.id) {
				for(var i = 0; i < newValue.length; i++) {
					var path = UtilService.returnPathFromCategory(vm.categories, newValue[i]);
					var path_array = path.split('/');
					path_array.splice(0, 1)
					path_array.splice(path_array.length-1,1);
					path_array.push(newValue[i]);
					for(var j = 0; j < path_array.length; j++) {
						categorySelected(path_array[j], i, j);
					}
					if(i < newValue.length - 1) {
						vm.categories_helper.push({
							categories_selected: [null],
							categories: [vm.rootCategories]
						})
					}
				}
			}
		}, true);

		//when get categories, set first
		$scope.$watch('productBasicInfoCtrl.categories', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				vm.rootCategories = filterCategory(newValue, '');
				addCategory();
			}
		});

		$scope.$watch('productBasicInfoCtrl.product.id', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				if(angular.isArray(vm.product.media.photos) && vm.product.media.photos.length > 0)
					parseImages();
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
			if(angular.isArray(newValue) && newValue.length > 0 && vm.photosRequired) {
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
			if(angular.isArray(newValue) && newValue.indexOf(null) === -1) {
				vm.form.$submitted = false;
			}
		}, true);

		//delete files array when done uploading
		// $scope.$watch('productBasicInfoCtrl.files', function(newValue, oldValue) {
		// 	console.log(newValue);
		// 	// if(angular.isArray(newValue) && newValue.length === 1 && angular.isObject(newValue[0]) && UtilService.isEmpty(newValue[0]))
		// 	// 	delete vm.files;
		// }, true);

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
		}
	}

	angular
		.module('todevise')
		.component('productBasicInfo', component);

}());