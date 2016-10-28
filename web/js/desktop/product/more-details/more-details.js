(function () {
	"use strict";

	function controller(Upload, $uibModal){
		var vm = this;
		vm.description_language = 'en-US';
		vm.tags_language = 'en-US';
		vm.faq_selected = false;
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

		function init(){
			//init functions
		}

		init();

		function showFaq() {
			if(vm.product.faq.length===0)
				addFaq();
		}

		function addFaq() {
			vm.product.faq.unshift({
				question: {},
				answer: {},
				completedLanguages: [],
				languageSelected: 'en-US'
			});
		}

		function deleteQuestion(index) {
			vm.product.faq.splice(index, 1);
			if(vm.product.faq.length===0)
				vm.faq_selected = false;
		}

		function parseQuestion(question) {
			question.completedLanguages = [];
			vm.languages.forEach(function (element) {
				if (question.question[element.code] && question.question[element.code] !== "" && question.answer[element.code] && question.answer[element.code] !== "") {
					question.completedLanguages.push(element.code)
				}
			})
		}

		function isLanguageOk(code, question) {
			return question.completedLanguages.indexOf(code) > -1 ? true : false;
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
				if(imageData.imageCropped || imageData.title || imageData.description) {
					//save
					console.log("saved");
				}
			}, function (err) {
				//errors
				console.log("dismissed!");
			});
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