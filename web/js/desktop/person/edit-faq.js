(function () {
	"use strict";

	function controller(personDataService, toastr, UtilService, languageDataService, $window, dragndropService) {
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

		function init() {
			getPerson();
		}

		init();

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
				languageSelected: 'en-US'
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

	angular.module('todevise')
		.controller('editFaqCtrl', controller);

}());