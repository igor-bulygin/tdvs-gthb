(function () {
	"use strict";

	function config($provide) {

		//events
		$provide.value('settingsEvents', {
			saveChanges: 'save-changes',
			changesSaved: 'changes-saved',
			invalidForm: 'invalid-form'
		});
	}

	angular
		.module('settings', ['api', 'util', 'header', 'ui.bootstrap', 'nya.bootstrap.select', 'textAngular'])
		.config(config);
}());