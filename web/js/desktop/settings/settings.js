(function () {
	"use strict";

	function config($provide) {

		//events
		$provide.value('settingsEvents', {
			saveChanges: 'save-changes'
		});
	}

	angular
		.module('todevise', ['api', 'util', 'header', 'nya.bootstrap.select'])
		.config(config);
}());