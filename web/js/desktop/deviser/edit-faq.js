(function () {
	"use strict";

	function controller(deviserDataService, toastr, UtilService, languageDataService, $scope, deviserEvents, $rootScope) {
		var vm = this;
		vm.addQuestion = addQuestion;
		vm.deleteQuestion = deleteQuestion;
		vm.parseQuestion = parseQuestion;
		vm.isLanguageOk = isLanguageOk;

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

		//watchs
		$scope.$watch('editFaqCtrl.deviser', function(newValue, oldValue){
			if(newValue) {
				var deviserCompare = angular.copy(newValue);
				deviserCompare.faq.forEach(function (element) {
					delete element.completedLanguages;
					delete element.languageSelected;
				});
				if(!angular.equals(deviserCompare, vm.deviser_original)) {
					$rootScope.$broadcast(deviserEvents.deviser_changed, {value: true, deviser: newValue});
				} else {
					$rootScope.$broadcast(deviserEvents.deviser_changed, {value: false});
				}
			}

		}, true);


	}

	angular.module('todevise')
		.controller('editFaqCtrl', controller);

}());