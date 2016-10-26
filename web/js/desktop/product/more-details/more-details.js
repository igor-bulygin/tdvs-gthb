(function () {
	"use strict";

	function controller(){
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


	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/more-details/more-details.html',
		controller: controller,
		controllerAs: 'productMoreDetailsCtrl',
		bindings: {
			product: '<',
			languages: '='
		}
	}

	angular
		.module('todevise')
		.component('productMoreDetails', component);

}());