(function () {
	"use strict";

	var settingsModule=angular.module('settings', ['api', 'util', 'header', 'ui.bootstrap', 'nya.bootstrap.select',
	 'textAngular','pascalprecht.translate']);

	settingsModule.config(['$provide','$translatePartialLoaderProvider', function ($provide,$translatePartialLoaderProvider) {
		$provide.value('settingsEvents', {
			saveChanges: 'save-changes',
			changesSaved: 'changes-saved',
			invalidForm: 'invalid-form'
		});
		$translatePartialLoaderProvider.addPart('settings');
}]);
}());