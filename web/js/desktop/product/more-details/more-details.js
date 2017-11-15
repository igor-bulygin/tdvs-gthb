(function () {
	"use strict";

	function controller($scope, $timeout, $uibModal, productDataService, UtilService, productEvents, dragndropService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.faq_selected = false;
		vm.faq_helper = [];
		vm.images = [];
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;
		vm.addFaq = addFaq;
		vm.showFaq = showFaq;
		vm.deleteQuestion = deleteQuestion;
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;
		vm.newCropModal = newCropModal;
		vm.editCropModal = editCropModal;
		vm.deleteImage = deleteImage;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.moved = moved;
		vm.canceled = canceled;
		vm.selected_language=_lang;

		function init(){
			//init functions
		}

		init();

		function showFaq() {
			if(vm.product.faq.length===0)
				addFaq();
		}

		function addFaq() {
			vm.faq_helper.push({
				completedLanguages: [],
				languageSelected: vm.selected_language
			});
			vm.product.faq.push({
				question: {},
				answer: {}
			});
		}

		function deleteQuestion(index) {
			vm.product.faq.splice(index, 1);
			vm.faq_helper.splice(index, 1);
			if(vm.product.faq.length === 0)
				vm.faq_selected = false;
		}

		function parseQuestion(question, index) {
			vm.languages.forEach(function(element) {
				if(question.question[element.code] && 
					question.question[element.code] !== "" && 
					question.answer[element.code] && 
					question.answer[element.code] !== "" && 
					vm.faq_helper[index].completedLanguages.indexOf(element.code) === -1) {
						vm.faq_helper[index].completedLanguages.push(element.code);
				}
			})
		}

		function isLanguageOk(code, index) {
			return vm.faq_helper[index].completedLanguages.indexOf(code) > -1 ? true : false;
		}

		function newCropModal(photo) {
			openCropModal(photo, {}, -1);
		}

		function editCropModal(index) {
			openCropModal(vm.images[index].url, vm.product.media.description_photos[index], index);
		}
		
		function openCropModal(photo, imageData, index) {
			if(vm.images.length < 4) {
				if(photo) {
					var modalInstance = $uibModal.open({
						component: 'modalCropDescription',
						resolve: {
							photo: function() {
								return photo;
							},
							languages: function() {
								return vm.languages;
							},
							product: function() {
								return vm.product;
							},
							imageData: function() {
								return imageData;
							},
							index: function() {
								return index;
							}
						},
						size: 'lg'
					});

					modalInstance.result.then(function (imageData) {
						var imageObject = {}
						var descriptionPhotoObject = {
							title: imageData.title,
							description: imageData.description
						};
						if(imageData.url)
							imageObject.url = imageData.url;
						if(imageData.name)
							descriptionPhotoObject.name = imageData.name;
						if(index <= -1) {
							vm.images.unshift(imageObject);
							vm.product.media.description_photos.unshift(descriptionPhotoObject);
						} else {
							vm.images[index] = Object.assign(vm.images[index], imageObject);
							vm.product.media.description_photos[index] = Object.assign(vm.product.media.description_photos[index], descriptionPhotoObject);
						}
					}, function (err) {
						UtilService.onError(err);
					});
				}
			} else {
				//max description photos reached
			}
		}

		function deleteImage(index) {
			if(index > -1) {
				vm.images.splice(index, 1);
				vm.product.media.description_photos.splice(index, 1);
			}
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
			vm.product.media.description_photos = vm.images.map(e => Object.assign({}, e.filename));
		}

		function canceled(){
			vm.images = dragndropService.canceled();
		}

		function parseImages() {
			var url = vm.product.id ? vm.product.id : 'temp';
			vm.images = UtilService.parseImagesUrl(vm.product.media.description_photos, '/uploads/product/' + url + '/');
		}

		//watches
		
		$scope.$watch('productMoreDetailsCtrl.product.faq', function(newValue, oldValue) {
			if(angular.isArray(oldValue) && oldValue.length === 0 && angular.isArray(newValue) && newValue.length > 0) {
				vm.faq_selected = true;
				for(var i = 0; i < newValue.length; i++) {
					vm.faq_helper.unshift({
						completedLanguages: [],
						languageSelected: vm.selected_language
					})
					parseQuestion(newValue[i], i);
				}
			}
			vm.faqRequired = false;
		}, true);

		$scope.$watch('productMoreDetailsCtrl.product.id', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				if(angular.isArray(vm.product.media.description_photos) && vm.product.media.description_photos.length > 0)
					parseImages();
			}
		});

		

		//events
		$scope.$on(productEvents.requiredErrors, function(event, args) {
			if(args.required.indexOf('faq') > -1) {
				vm.faqRequired = true;
			}
		})

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/more-details/more-details.html',
		controller: controller,
		controllerAs: 'productMoreDetailsCtrl',
		bindings: {
			product: '=',
			languages: '<'
		}
	}

	angular
		.module('product')
		.component('productMoreDetails', component);

}());