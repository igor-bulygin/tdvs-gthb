(function () {
	"use strict";

	function controller(Upload, $uibModal, productDataService, UtilService){
		var vm = this;
		vm.description_language = 'en-US';
		vm.tags_language = 'en-US';
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
			vm.faq_helper.unshift({
				completedLanguages: [],
				languageSelected: 'en-US'
			});
			vm.product.faq.unshift({
				question: {},
				answer: {},
				//completedLanguages: [],
				//languageSelected: 'en-US'
			});
		}

		function deleteQuestion(index) {
			vm.product.faq.splice(index, 1);
			vm.faq_selected.splice(index, 1);
			if(vm.product.faq.length===0)
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

		function addTag(tag) {
			if(!vm.product.tags[vm.tags_language])
				vm.product.tags[vm.tags_language]=[tag.text];
			else {
				if(vm.product.tags[vm.tags_language].indexOf(tag.text) === -1)
					vm.product.tags[vm.tags_language].push(tag.text)
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
										url: currentHost() + '/' + dataUpload.data.url
									})
									vm.product.media.description_photos.unshift({
										name: dataUpload.data.filename,
										title: imageData.title,
										description: imageData.description
									});
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

		//events
		//TO DO: set description required if it is empty in english (vm.descriptionRequired = true)
		//TO DO: set faq required if it is not empty and english is not filled

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