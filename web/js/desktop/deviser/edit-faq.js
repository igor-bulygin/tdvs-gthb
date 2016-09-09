(function () {
	"use strict";
	
	function controller(deviserDataService, toastr, UtilService, languageDataService, $scope) {
		var vm = this;
		vm.addQuestion = addQuestion;
		vm.deleteQuestion = deleteQuestion;
		vm.addLanguageToQuestion = addLanguageToQuestion;
		
		function getDeviser(){
			deviserDataService.Profile.get({
				deviser_id: UtilService.returnDeviserIdFromUrl()
			}).$promise.then(function (dataDeviser) {
				vm.deviser = dataDeviser;
				if(!vm.deviser.faq)
					vm.deviser.faq = [];
				else {
					vm.deviser.faq
				}
			}, function(err) {
				toastr.error(err);
			})
		}
		
		function getLanguages() {
			languageDataService.Languages.get().$promise.then(function (dataLanguages) {
				vm.languages = dataLanguages.items;
			}, function (err) {
				toastr.error(err);
			})
		}
		
		function init(){
			getDeviser();
			getLanguages();
		}
		
		init();
		
		function addQuestion() {
			vm.deviser.faq.unshift({
				languages: [{name:"English",code:"en-US"}],
				question:{'en-US': ""},
				answer: {'en-US': ""}
			});
		}
		
		function deleteQuestion(index) {
			vm.deviser.faq.splice(index, 1);
		}
		
		function addLanguageToQuestion(language, index) {
			vm.deviser.faq[index].languages.push(language);
			vm.deviser.faq[index].question[language.code] = "";
			vm.deviser.faq[index].answer[language.code] = "";
		}
		
	}
	
	angular.module('todevise', ['api', 'toastr','util', 'ui.bootstrap'])
		.controller('editFaqCtrl', controller);
	
}());