(function () {
	"use strict";

	function controller($translate, bannerDataService, UtilService, languageDataService, uploadDataService, $timeout, $scope, productDataService, dragndropService) {
		var vm = this;
		vm.bannerOptions = [{name:"Home", value:-1},{name:"Categories", value:2}];
		vm.selectedBannerOption= vm.bannerOptions[0];
		vm.bannerTypes = [{name:"Carousel", value:'carousel'},{name:"Banner", value:'home_banner'}];
		vm.selectedType=vm.bannerTypes[0].value;
		vm.selectBannerOption = selectBannerOption;
		vm.banners = [];
		vm.uploadPhoto=uploadPhoto;
		vm.tempFiles=[];
		vm.showNewBanner = showNewBanner;
		vm.saveBanner = saveBanner;
		vm.mandatory_langs=Object.keys(_langs_required);
		vm.lang = _lang;
		vm.baseUrl = currentHost() + '/';
		vm.categories_helper = [];
		vm.selectedCategories =  [];
		vm.categorySelected = categorySelected;
		vm.selectedCategory= null;
		vm.getBanners = getBanners;
		vm.editBanner = editBanner;
		vm.cancelEdition = cancelEdition;
		vm.deleteBanner = deleteBanner;

		function init() {
			getLanguages();
			setMandatoryLanguagesNames();
		}

		init();

		function setMandatoryLanguagesNames() {
			angular.forEach(Object.keys(_langs_required), function (lang) {
				var translationLang="ADMIN.".concat(_langs_required[lang].toUpperCase());
				$translate(translationLang).then(function (tr) {
					if (vm.mandatory_langs_names.length>0) {
						vm.mandatory_langs_names=vm.mandatory_langs_names.concat(', ');
					}
					vm.mandatory_langs_names=vm.mandatory_langs_names.concat(tr);
				});
			});
		}



		function getLanguages() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
				if (vm.languages && vm.languages.length>0) {
					vm.name_language = vm.languages[0];
				}
			}
			languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
		}

		function selectBannerOption() {
			vm.showCategorySelection=false;
			vm.showHomeSelection = false;
			vm.selectedCategory= null;
			vm.selectedType=vm.bannerTypes[0].value;
			switch (vm.selectedBannerOption) {
				case -1:
				vm.showHomeSelection = true;
				vm.categories_helper = [];
				vm.selectedCategories =  [];
				getBanners();
				break;
				default:
				vm.banners = [];
				getCategories();
				vm.showCategorySelection=true;
				break;
			}
		}

		function editBanner(banner) {
			vm.isEdition = true;
			vm.newBanner = banner;
			vm.newImage = vm.newBanner.image_link;
			vm.viewNewBanner=true;
		}

		function showNewBanner(isEdition) {
			vm.showMaxNumberReached=false;
			if (vm.selectedType === 'home_banner' && vm.banners.length>2) {
				vm.showMaxNumberReached=true;
			}
			else {
				var position=1;
				if (vm.banners.length>0) {
					position = vm.banners.length +1;
				}
				vm.newBanner = { category_id: vm.selectedCategory, position: position};
				vm.newImage = {}; 
				vm.viewNewBanner=true;
			}
		}

		function getBanners() {
			vm.banners = [];
			vm.loading=true;
			function onGetBannersSuccess(data) {
				if (data && data.items && data.items.length>0) {
					vm.banners = angular.copy(data.items); 
				}
				vm.loading=false;
			}
			bannerDataService.getBanners({category_id: vm.selectedCategory, type:vm.selectedType }, onGetBannersSuccess, UtilService.onError);
		}

		function uploadPhoto(images, errImages) {
			function onUploadPhotoSuccess(data, file) {
				$timeout(function() {
					delete file.progress;
				}, 1000);
				if (!vm.newBanner.image) {
					vm.newBanner.image= {};
				}
				vm.newBanner.image[vm.name_language]= data.data.filename;
				vm.newImage[vm.name_language]= data.data.url;
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
					type: 'banner-image',
					file: file
				};
				uploadDataService.UploadFile(data,
					function(data) {
						return onUploadPhotoSuccess(data, file);
					}, UtilService.onError,
					function(evt) {
						return onWhileUploadingPhoto(evt, file);
					});
			});
		}

		function cancelEdition() {
			vm.isEdition = false;
			vm.viewNewBanner=false;
			vm.newBanner = {};
			vm.newImage = {};
		}

		function saveBanner() {
			vm.textRequired=false;
			vm.imageRequired = false;
			angular.forEach(vm.mandatory_langs, function (lang) {
				if (angular.isUndefined(vm.newBanner.alt_text) || angular.isUndefined(vm.newBanner.alt_text[lang]) || vm.newBanner.alt_text[lang].length<1) {
					vm.textRequired = true;
				}
				if (angular.isUndefined(vm.newBanner.image) || angular.isUndefined(vm.newBanner.image[lang])) {
					vm.imageRequired = true;
				}
			});
			if (!vm.textRequired && !vm.imageRequired) {
				function onSaveBannersSuccess(data) {
					getBanners();
					vm.isEdition = false;
					vm.viewNewBanner=false;
				}
				if (vm.isEdition) {
					bannerDataService.updateBanner(vm.newBanner, {id:vm.newBanner.id}, onSaveBannersSuccess, UtilService.onError);
				}
				else {
					vm.newBanner.category_id= vm.selectedCategory;
					vm.newBanner.type= vm.selectedType;
					bannerDataService.createBanner(vm.newBanner, onSaveBannersSuccess, UtilService.onError);
				}
			}
		}

		function deleteBanner(banner) {
			vm.loading = true;
			function onDeleteBannerSuccess(data) {
				vm.showMaxNumberReached=false;
				getBanners();
			}
			bannerDataService.deleteBanner({id:banner.id }, onDeleteBannerSuccess, UtilService.onError);
		}

		function getCategories() {
			vm.loading = true;
			function onGetCategoriesSuccess(data) {
				vm.categories = data.items;
				vm.loading = false;
			}

			productDataService.getCategories({ scope: 'all' }, onGetCategoriesSuccess, UtilService.onError);
		}

		function addCategory() {
			vm.categories_helper.push({
				categories_selected: [null],
				categories: [vm.rootCategories]
			})
			vm.categories.push(null);
			vm.emptyCategory=true;
		}

		function categorySelected(category, index_helper, index) {
			vm.selectedCategory= category;
			vm.emptyCategory=true;
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
				vm.selectedCategories[index_helper] = category;
				vm.emptyCategory=false;
			}
			getBanners();
		}

		function filterCategory(categories, id) {
			var sub_categories = [];
			for(var i = 0; i < categories.length; i++) {
				if(categories[i]) {
					var split_fathers = categories[i].path.split('/');

					for(var j = 0; j < split_fathers.length; j++) {
						if(split_fathers[j] === "")
							split_fathers.splice(j, 1);
					}
					var father = split_fathers[split_fathers.length-1];
					if(father === id && categories[i])
						sub_categories.push(categories[i]);
				}
			}
			return sub_categories;
		}

		function deleteCategory(index) {
			if(index >= 0) {
				vm.selectedCategory= null;
				vm.selectedCategories.splice(index, 1);
				if (vm.selectedCategories.length>0) {
					vm.selectedCategory= vm.selectedCategories[vm.selectedCategories.length -1];
				}
				vm.categories_helper.splice(index, 1);
			}
		}


		vm.sortableOptions = {
			stop: function(e, ui) { 
				updateOrderBanners();
			}
		};

		function updateOrderBanners() {
			function onUpdateBannersSuccess(data) {
			}
			var i=1;
			angular.forEach(vm.banners, function(banner) {
				banner.position = i;
				i= i+1;
				bannerDataService.updateBanner(banner, {id:banner.id}, onUpdateBannersSuccess, UtilService.onError);
			});
		}

		$scope.$watch('bannerCtrl.selectedCategories', function(newValue, oldValue) {
			if(angular.isArray(oldValue) && oldValue[0]===null && angular.isArray(newValue) && newValue.length > 0) {
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
		$scope.$watch('bannerCtrl.categories', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				vm.rootCategories = filterCategory(newValue, '');
				addCategory();
			}
		});

	}

	angular.module('todevise', ['global-admin','pascalprecht.translate','api','nya.bootstrap.select', 'ui.sortable'])
	.controller('bannerCtrl', controller);

}());