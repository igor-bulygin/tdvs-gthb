(function () {
	"use strict";

	function controller(personDataService, toastr, UtilService, languageDataService, $window, dragndropService,$translate) {
		var vm = this;
		vm.stripHTMLTags = UtilService.stripHTMLTags;
		vm.addQuestion = addQuestion;
		vm.deleteQuestion = deleteQuestion;
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;
		vm.dragOver = dragOver;
		vm.dragStart = dragStart;
		vm.moved = moved;
		vm.canceled = canceled;
		vm.done = done;
		vm.selected_language=_lang;
		vm.mandatory_langs=Object.keys(_langs_required);
		vm.mandatory_langs_names="";
		vm.saving=true;

		function init() {
			getPerson();
			setMandatoryLanguagesNames();
		}

		init();

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

		function getPerson() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
				if (!vm.person.faq)
					vm.person.faq = [];
				else {
					vm.person.faq.forEach(function (element) {
						parseQuestion(element);
					})
				}
				vm.saving=false;
			}

			function onGetProfileSuccess(data) {
				vm.person = angular.copy(data);
				vm.person_original = angular.copy(data);
				//we need languages before parse questions
				languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
			}

			personDataService.getProfile({
				personId: person.short_id
			}, onGetProfileSuccess, UtilService.onError);
		}



		function addQuestion() {
			vm.person.faq.unshift({
				question: {},
				answer: {},
				completedLanguages: [],
				languageSelected: vm.selected_language
			});
		}

		function deleteQuestion(index) {
			vm.person.faq.splice(index, 1);
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

		function done() {
			update();
		}

		function update() {
			vm.saving=true;
			var hasError=false;
			angular.forEach(vm.person.faq, function (faq) {
				faq.required_question=false;
				faq.required_answer=false;
				angular.forEach(vm.mandatory_langs, function (lang) {
					if (angular.isUndefined(faq.question[lang]) || faq.question[lang].length<1) {
						faq.required_question=true;
						hasError=true;
					}
					if (angular.isUndefined(faq.answer[lang]) || faq.answer[lang].length<1) {
						faq.required_answer=true;
						hasError=true;
					}
				});
			});
			if (hasError) {
				vm.saving=false;
				return;
			}
			var data = {
				scenario: 'deviser-update-profile',
				faq: [],
			};

			function onUpdateProfileSuccess(data) {
				$window.location.href = vm.person.faq_link;
			}

			function parseFaqs(element) {
				parseQuestion(element);
				for(var key in element.answer) {
					element.answer[key] = element.answer[key].replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
				}
				data.faq.push({
					question: angular.copy(element.question),
					answer: angular.copy(element.answer)
				});
			}

			vm.person.faq.map(parseFaqs);

			personDataService.updateProfile(data, {
				personId: person.short_id
			}, onUpdateProfileSuccess, UtilService.onError);
		}

		function dragStart(index) {
			dragndropService.dragStart(index, vm.person.faq);
		}

		function dragOver(index) {
			vm.person.faq = dragndropService.dragOver(index, vm.person.faq);
			return true;
		}

		function moved(index) {
			vm.person.faq = dragndropService.moved(index);
		}

		function canceled() {
			vm.person.faq = dragndropService.canceled();
		}

	}

	angular.module('person')
		.controller('editFaqCtrl', controller);

}());