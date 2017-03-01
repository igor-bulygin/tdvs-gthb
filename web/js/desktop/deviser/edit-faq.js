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

		function getDeviser() {
			function onGetLanguagesSuccess(data) {
				vm.languages = data.items;
				if (!vm.deviser.faq)
					vm.deviser.faq = [];
				else {
					vm.deviser.faq.forEach(function (element) {
						parseQuestion(element);
					})
				}
			}

			function onGetProfileSuccess(data) {
				vm.deviser = angular.copy(data);
				vm.deviser_original = angular.copy(data);
				//we need languages before parse questions
				languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
			}

			personDataService.getProfile({
				person_id: deviser.short_id
			}, onGetProfileSuccess, UtilService.onError);
		}

		function init() {
			getDeviser();
		}

		init();

		function addQuestion() {
			vm.deviser.faq.unshift({
				question: {},
				answer: {},
				completedLanguages: [],
				languageSelected: 'en-US'
			});
		}

		function deleteQuestion(index) {
			vm.deviser.faq.splice(index, 1);
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
				person_id: deviser.short_id
			};

			function onUpdateProfileSuccess(data) {
				$window.location.href = '/deviser/' + data.slug + '/' + data.id + '/faq';
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

			vm.deviser.faq.map(parseFaqs);

			personDataService.updateProfile(data, null, onUpdateProfileSuccess, UtilService.onError);

/*			var patch = new deviserDataService.Profile;
			patch.scenario = 'deviser-update-profile';
			patch.faq = [];
			patch.deviser_id = vm.deviser.id;
			vm.deviser.faq.forEach(function (element) {
				parseQuestion(element);
				for(var key in element.answer) {
					element.answer[key] = element.answer[key].replace(/<[^\/>][^>]*><\/[^>]+>/gim, "");
				}
				patch.faq.push({
					question: angular.copy(element.question),
					answer: angular.copy(element.answer)
				});
			});
			patch.$update().then(function(dataFaq) {
				$window.location.href = '/deviser/' + dataFaq.slug + '/' + dataFaq.id + '/faq';
			}, function (err) {
				toastr.error(err);
			});*/
		}

		function dragStart(index) {
			dragndropService.dragStart(index, vm.deviser.faq);
		}

		function dragOver(index) {
			vm.deviser.faq = dragndropService.dragOver(index, vm.deviser.faq);
			return true;
		}

		function moved(index) {
			vm.deviser.faq = dragndropService.moved(index);
		}

		function canceled() {
			vm.deviser.faq = dragndropService.canceled();
		}

	}

	angular.module('todevise')
		.controller('editFaqCtrl', controller);

}());