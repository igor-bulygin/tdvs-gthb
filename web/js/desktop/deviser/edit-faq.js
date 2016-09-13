(function () {
	"use strict";

	function controller(deviserDataService, toastr, UtilService, languageDataService, $scope) {
		var vm = this;
		vm.addQuestion = addQuestion;
		vm.deleteQuestion = deleteQuestion;
		vm.update = update;
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;

		function getDeviser() {
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				//we need languages before parse questions
				languageDataService.Languages.get().$promise.then(function (dataLanguages) {
					vm.languages = dataLanguages.items;
					if (!vm.deviser.faq)
						vm.deviser.faq = [];
					else {
						vm.deviser.faq.forEach(function (element) {
							parseQuestion(element);
						})
					}
				}, function (err) {
					toastr.error(err);
				});

			}, function (err) {
				toastr.error(err);
			})
		}

		function init() {
			getDeviser();
		}

		init();

		function update(index) {
			if (index >= 0) {
				vm.deviser.faq.splice(index, 1)
			}
			var patch = new deviserDataService.Profile;
			patch.scenario = "deviser-faq-update";
			patch.faq = [];
			vm.deviser.faq.forEach(function (element, index) {
				parseQuestion(element);
				patch.faq.push({
					question: element.question,
					answer: element.answer
				})
			});
			patch.deviser_id = vm.deviser.id;
			patch.$update().then(function (dataFaq) {
				toastr.success("Updated!");
			}, function (err) {
				toastr.error(err);
			})
		}

		function addQuestion() {
			vm.deviser.faq.unshift({
				question: {},
				answer: {},
				completedLanguages: []
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


	}

	angular.module('todevise', ['api', 'toastr', 'util', 'ui.bootstrap', 'dndLists'])
		.controller('editFaqCtrl', controller);

}());