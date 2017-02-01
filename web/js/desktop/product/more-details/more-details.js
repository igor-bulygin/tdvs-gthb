(function () {
	"use strict";

	function controller($scope, $timeout, $uibModal, Upload, productDataService, UtilService, productEvents){
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.description_language = 'en-US';
		vm.tags_language = 'en-US';
		vm.faq_selected = false;
		vm.faq_helper = [];
		vm.images = [];
		vm.tags = {};
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;
		vm.addFaq = addFaq;
		vm.showFaq = showFaq;
		vm.deleteQuestion = deleteQuestion;
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;
		vm.addTag = addTag;
		vm.removeTag = removeTag;
		vm.openCropModal = openCropModal;
		vm.deleteImage = deleteImage;

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
				languageSelected: 'en-US'
			});
			vm.product.faq.push({
				question: {},
				answer: {},
				//completedLanguages: [],
				//languageSelected: 'en-US'
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

		function addTag(tag, language) {
			if(!vm.product.tags[language])
				vm.product.tags[language]=[tag.text];
			else {
				if(vm.product.tags[language].indexOf(tag.text) === -1)
					vm.product.tags[language].push(tag.text)
			}
		}

		function removeTag(tag) {
			var pos = vm.product.tags[vm.tags_language].indexOf(tag.text);
			if(pos > -1)
				vm.product.tags[vm.tags_language].splice(pos, 1);
			if(vm.product.tags[vm.tags_language].length === 0)
				delete vm.product.tags[vm.tags_language];
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
						if(angular.isObject(imageData) && (imageData.photoCropped || imageData.title || imageData.description)) {
								//upload cropped photo
								vm.file = angular.copy(Upload.dataUrltoBlob(imageData.photoCropped, "temp.png"));
								var data = {
									deviser_id: UtilService.returnDeviserIdFromUrl(),
									file: Upload.dataUrltoBlob(imageData.photoCropped, "temp.png")
								};
								var type;
								if(vm.product.id) {
									data['type'] = "known-product-photo";
									data['product_id'] = vm.product.id;
								} else {
									data['type'] = "unknown-product-photo";
								}
								Upload.upload({
									url: productDataService.Uploads,
									data: data
								}).then(function (dataUpload) {
									//save photo
									vm.images.unshift({
										url: currentHost() + dataUpload.data.url
									})
									vm.product.media.description_photos.unshift({
										name: dataUpload.data.filename,
										title: imageData.title,
										description: imageData.description
									});
									$timeout(function () {
										delete vm.file;
									}, 1000)
								}, function (err) {
									//errors
								}, function (evt) {
									vm.file.progress = parseInt(100.0 * evt.loaded/evt.total);
								})
						}
					}, function (err) {
						//errors
						console.log("dismissed!");
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
		//watches
		$scope.$watch('productMoreDetailsCtrl.product.description', function(newValue, oldValue) {
			vm.descriptionRequired = false;
		}, true);
		$scope.$watch('productMoreDetailsCtrl.product.faq', function(newValue, oldValue) {
			if(angular.isArray(oldValue) && oldValue.length === 0 && angular.isArray(newValue) && newValue.length > 0) {
				vm.faq_selected = true;
				for(var i = 0; i < newValue.length; i++) {
					vm.faq_helper.unshift({
						completedLanguages: [],
						languageSelected: 'en-US'
					})
					parseQuestion(newValue[i], i);
				}
			}
			vm.faqRequired = false;
		}, true);

		$scope.$watch('productMoreDetailsCtrl.product.id', function(newValue, oldValue) {
			if(!oldValue && newValue) {
				if(angular.isArray(vm.product.media.description_photos) && vm.product.media.description_photos.length > 0)
					vm.images = UtilService.parseImagesUrl(vm.product.media.description_photos, '/uploads/product/' + newValue + '/');
			}
		});

		$scope.$watch('productMoreDetailsCtrl.product.tags', function(newValue, oldValue) {
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
		$scope.$on(productEvents.requiredErrors, function(event, args) {
			//set description error
			if(args.required.indexOf('description') > -1) {
				vm.descriptionRequired = true;
			}
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
		.module('todevise')
		.component('productMoreDetails', component);

}());