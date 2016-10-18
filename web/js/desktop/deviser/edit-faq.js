(function () {
	"use strict";

	function controller(deviserDataService, toastr, UtilService, languageDataService, $scope, deviserEvents, $rootScope) {
		var vm = this;
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
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				vm.deviser_original = angular.copy(dataDeviser);
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

		function done() {
			update(true);
		}

		function update(done) {
			var patch = new deviserDataService.Profile;
			patch.scenario = 'deviser-update-profile';
			patch.faq = [];
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
			patch.deviser_id = vm.deviser.id;
			patch.$update().then(function(dataFaq) {
				if(done) {
					//go away
				}
			}, function (err) {
				toastr.error(err);
			});
		}

		function dragStart(event, index) {
			vm.original_index = index;
			vm.original_faq = angular.copy(vm.deviser.faq);
			vm.faq_being_moved = angular.copy(vm.deviser.faq[index]);
		}

		function dragOver(event, index) {
			console.log(vm.original_index);
			vm.deviser.faq = angular.copy(vm.original_faq);
			vm.previous_index = index;
			if(vm.previous_index > vm.original_index) {
				vm.deviser.faq.splice(vm.previous_index, 0, vm.faq_being_moved);
			} else {
				vm.deviser.faq[vm.original_index] = vm.original_faq[vm.original_index - 1];
				vm.deviser.faq.splice(vm.previous_index, 0, vm.faq_being_moved);
			}
			return true;
		}

		function moved(index) {
			vm.deviser.faq = angular.copy(vm.original_faq);
			if(vm.previous_index > vm.original_index) {
				vm.deviser.faq.splice(vm.previous_index, 0, vm.faq_being_moved);
				vm.deviser.faq.splice(vm.original_index, 1);
			} else {
				vm.deviser.faq.splice(vm.original_index, 1);
				vm.deviser.faq.splice(vm.previous_index, 0, vm.faq_being_moved);
			}
			delete vm.faq_being_moved;
			delete vm.previous_index;
		}

		function canceled(event, index) {
			vm.deviser.faq = angular.copy(vm.original_faq);
		}

	}

	angular.module('todevise')
		.controller('editFaqCtrl', controller);

}());