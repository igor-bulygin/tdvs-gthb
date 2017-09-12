(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide, $translatePartialLoaderProvider) {
		nyaBsConfigProvider.setLocalizedText('en-US', {
			defaultNoneSelection: 'global.SELECT_OPTION'
		});
		$provide.value('settingsEvents', {
			saveChanges: 'save-changes',
			changesSaved: 'changes-saved',
			invalidForm: 'invalid-form'
		});
		$translatePartialLoaderProvider.addPart('settings');
	}

	angular
		.module('settings', ['api', 'util', 'header', 'ui.bootstrap', 'nya.bootstrap.select', 'textAngular','pascalprecht.translate'])
		.config(config);

}());