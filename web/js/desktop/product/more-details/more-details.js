(function () {
	"use strict";

	function controller($scope, $timeout, $uibModal, Upload, uploadDataService, productDataService, 
		UtilService, productEvents, dragndropService){
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
		vm.openCropModal = openCropModal;
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
		
		function openCropModal(photo) {
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
							}
						},
						size: 'lg'
					});

					modalInstance.result.then(function (imageData) {
						function onUploadPhotoSuccess(data, imageData) {
							$timeout(function() {
								delete vm.file;
							}, 1000);
							//save photo
							vm.images.unshift({
								url: currentHost() + data.data.url
							})
							vm.product.media.description_photos.unshift({
								name: data.data.filename,
								title: imageData.title,
								description: imageData.description
							});
						}

						function onWhileUploadingPhoto(evt) {
							vm.file.progress = parseInt(100.0 * evt.loaded/evt.total);
						}

						if(angular.isObject(imageData) && (imageData.photoCropped || imageData.title || imageData.description)) {
								//upload cropped photo
								vm.file = angular.copy(Upload.dataUrltoBlob(imageData.photoCropped, "temp.png"));
								var data = {
									deviser_id: person.short_id,
									file: Upload.dataUrltoBlob(imageData.photoCropped, "temp.png")
								};
								if(vm.product.id) {
									data['type'] = "known-product-photo";
									data['product_id'] = vm.product.id;
								} else {
									data['type'] = "unknown-product-photo";
								}

								uploadDataService.UploadFile(data, 
									function(data) {
										return onUploadPhotoSuccess(data, imageData);
									}, UtilService.onError, function(evt) {
										return onWhileUploadingPhoto(evt);
									});
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
			console.log(vm.images);
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