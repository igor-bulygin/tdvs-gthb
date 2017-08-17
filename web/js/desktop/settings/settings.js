(function () {
/*	"use strict";

	function config($provide) {

		//events
		$provide.value('settingsEvents', {
			saveChanges: 'save-changes',
			changesSaved: 'changes-saved',
			invalidForm: 'invalid-form'
		});


	}

*/
	var settingsModule=angular.module('settings', ['api', 'util', 'header', 'ui.bootstrap', 'nya.bootstrap.select',
	 'textAngular','pascalprecht.translate']);

	settingsModule.config(['$provide','$translateProvider', function ($provide, $translateProvider) {
		$provide.value('settingsEvents', {
			saveChanges: 'save-changes',
			changesSaved: 'changes-saved',
			invalidForm: 'invalid-form'
		});

		var fileNameConvention = {
			prefix: '/translations/locale-',
			suffix: '.pod'
		};  
		$translateProvider.useStaticFilesLoader(fileNameConvention);
		$translateProvider.preferredLanguage('es');
		$translateProvider.useSanitizeValueStrategy('sanitize');
}]);
}());