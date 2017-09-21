(function () {
	"use strict";

	function config($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('footer');
	}

	function controller(UtilService, newsletterDataService) {
		var vm = this;
		vm.hasError = UtilService.hasError;
		vm.sendNewsletter = sendNewsletter;

		function sendNewsletter(form) {
			function onCreateNewsletter(data) {
				vm.subscribed = true;
			}

			form.$setSubmitted();
			if(form.$valid) {
				newsletterDataService.createNewsletter({
					email:vm.newsletterEmail}, onCreateNewsletter, UtilService.onError);
			}
		}
	}

	angular.module('footer', ['api', 'util', 'pascalprecht.translate'])
		.config(config)
		.controller('footerCtrl', controller);

}());