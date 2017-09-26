(function () {
	"use strict";

	function controller(productDataService, toastr, Upload, uploadDataService, $scope, UtilService, $uibModal, $rootScope, 
		productEvents, $timeout, dragndropService,$translate) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.name_language = vm.tags_language = vm.description_language = 'es-ES';
		vm.categories_helper = [];
		vm.images = [];
		vm.addCategory = addCategory;
		vm.categorySelected = categorySelected;
		vm.deleteCategory = deleteCategory;
		vm.openCropModal = openCropModal;
		vm.uploadPhoto = uploadPhoto;
		vm.deleteImage = deleteImage;
		vm.tempFiles=[];
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.tags = {};
		vm.addTag = addTag;
		vm.removeTag = removeTag;
		vm.firstCategorySelection=true;
		vm.mandatory_langs_names="";
		//we need this counter to know how many categories we have in order to broadcast right vars in setVariations event
		vm.category_counter = 0;

		init();
		
		function init(){
			setMandatoryLanguagesNames();
		}

		//	TODO unify this (repeated function on variations.js) as a component field from creation/edition when files free
		function setMandatoryLanguagesNames() {
			angular.forEach(Object.keys(_langs_required), function (lang) {
				var translationLang="product.".concat(_langs_required[lang].toUpperCase());
				$translate(translationLang).then(function (tr) {
					if (vm.mandatory_langs_names.length>0) {
						vm.mandatory_langs_names=vm.mandatory_langs_names.concat(', ');
					}
					vm.mandatory_langs_names=vm.mandatory_langs_names.concat(tr);
				});
			});
		}

		//categories
		function addCategory() {
			vm.categories_helper.push({
				categories_selected: [null],
				categories: [vm.rootCategories]
			})
			vm.product['categories'].push(null);
			vm.product.emptyCategory=true;
		}

		function categorySelected(category, index_helper, index) {
			vm.product.emptyCategory=true;
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
				$rootScope.$broadcast(productEvents.setVariations, {categories: vm.product.categories, isFirstSelection:vm.firstCategorySelection});
				vm.category_counter += 1;
				vm.product.emptyCategory=false;
				if(vm.category_counter === vm.product.categories.length)
					vm.firstCategorySelection=false;
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
			function onUploadPhotoSuccess(data, file) {
				$timeout(function() {
					delete file.progress;
				}, 1000);
				//parse images
				vm.images.push({
					url: currentHost() + '/' + data.data.url
				});
				vm.product.media.photos.push({
					name: data.data.filename
				});
				var index=-1;
				angular.forEach(vm.tempFiles, function(uploadingFile) {
					if (uploadingFile.$$hashKey==file.$$hashKey) {
						index=-vm.tempFiles.indexOf(uploadingFile);
					}
				});
				if (index != -1)
				{
					vm.tempFiles.splice(index,1);
				}
			}

			function onWhileUploadingPhoto(evt, file) {
				angular.forEach(vm.tempFiles, function(uploadingFile) {
					if (uploadingFile.$$hashKey==file.$$hashKey) {
						uploadingFile.progress = parseInt(100.0 * evt.loaded / evt.total);
					}
				});
			}

			
			angular.forEach(images, function(image) {
				vm.tempFiles.push(image);
			});
			vm.files=images
			vm.errFiles = errImages;

			//upload photos
			angular.forEach(vm.files, function(file) {
				var data = {
					deviser_id: person.short_id,
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

				uploadDataService.UploadFile(data,
					function(data) {
						return onUploadPhotoSuccess(data, file);
					}, UtilService.onError,
					function(evt) {
						return onWhileUploadingPhoto(evt, file);
					});
			});
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
					},
					person: function() {
						return person;
					},
					product_id: function() {
						if(vm.product.id)
							return vm.product.id;
						else {
							return null;
						}
					}
				}
			});

			modalInstance.result.then(function(data) {
				if(angular.isObject(data)) {
					unSetMainPhoto();
					//set image filename in helper
					vm.images[index].url = currentHost() + '/' + data.data.url;
					//set image filename in model cropped name
					vm.product.media.photos[index]['name_cropped'] = angular.copy(data.data.filename);
					vm.product.media.photos[index]['main_product_photo'] = true;
					parseImages();
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
			if(angular.isArray(vm.product.media.photos) && vm.product.media.photos.length > 0) {
				var main_photo = vm.product.media.photos.find((photo) => {
					return photo.main_product_photo;
				});
				if(main_photo) {
					if(main_photo.main_product_photo)
						delete main_photo.main_product_photo;
					if(main_photo.name_cropped)
						delete main_photo.name_cropped;
				}
			}
		}

		function parseImages() {
			var url = vm.product.id ? vm.product.id : 'temp';
			vm.images = UtilService.parseImagesUrl(vm.product.media.photos, '/uploads/product/' + url + '/');
		}

		function removeTag(tag) {
			var pos = vm.product.tags[vm.tags_language].indexOf(tag.text);
			if(pos > -1)
				vm.product.tags[vm.tags_language].splice(pos, 1);
			if(vm.product.tags[vm.tags_language].length === 0)
				delete vm.product.tags[vm.tags_language];
		}

		function addTag(tag, language) {
			if(!vm.product.tags[language])
				vm.product.tags[language]=[tag.text];
			else {
				if(vm.product.tags[language].indexOf(tag.text) === -1)
					vm.product.tags[language].push(tag.text)
			}
		}

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
			if(angular.isArray(newValue) && newValue.indexOf(null) === -1) {
				vm.form.$submitted = false;
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
			vm.nameRequired = false;
			angular.forEach(vm.mandatory_langs, function (lang) {
				if (angular.isUndefined(newValue) || angular.isUndefined(newValue[lang]) || newValue[lang].length<1) {
					vm.nameRequired = true;
				}
			});
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

		

		$scope.$watch('productBasicInfoCtrl.product.description', function(newValue, oldValue) {
			vm.descriptionRequired = false;
		}, true);

		$scope.$watch('productBasicInfoCtrl.product.tags', function(newValue, oldValue) {
			if(angular.isObject(oldValue) && UtilService.isEmpty(oldValue) && angular.isObject(newValue) && !UtilService.isEmpty(newValue)) {
				for(var key in newValue) {
					vm.tags[key] = [];
					newValue[key].forEach(function(element) {
						vm.tags[key].push({text: element});
					});
				}
			}
		}, true);

		//events
		$scope.$on(productEvents.requiredErrors, function(event, args){
			vm.warrantyRequired = false;
			vm.warrantyNumberRequired = false;
			vm.returnsNumberRequired = false;
			vm.returnsRequired = false;
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
			//set description error
			if(args.required.indexOf('description') > -1) {
				vm.descriptionRequired = true;
			}
			vm.categorySelectionRequired = false;
			if(args.required.indexOf('emptyCategory') > -1) {
				vm.categorySelectionRequired = true;
			}
			//set warranty errors
			if(args.required.indexOf('warranty') > -1) {
				vm.warrantyRequired = true;
			}
			else if(args.required.indexOf('warrantyNotNumber') > -1) {
				vm.warrantyNumberRequired = true;
			}
			//set returns errors
			if(args.required.indexOf('returns') > -1) {
				vm.returnsRequired = true;
			}
			else if(args.required.indexOf('returnsNotNumber') > -1) {
				vm.returnsNumberRequired = true;
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
			languages: '<'
		}
	}

	angular
		.module('product')
		.component('productBasicInfo', component);

}());