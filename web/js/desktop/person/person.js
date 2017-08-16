(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide, localStorageServiceProvider) {
		nyaBsConfigProvider.setLocalizedText('en-us', {
			defaultNoneSelection: 'Choose an option'
		});

		//events
		$provide.value("deviserEvents", {
			make_profile_public_errors: 'make-profile-public-errors',
			updated_deviser: 'updated_deviser'
		});

		localStorageServiceProvider
			.setPrefix('todevise-');
	}

	angular
		.module('person', ['api', 'util', 'header', 'toastr', 'nya.bootstrap.select',
			'textAngular', 'ngFileUpload', 'dndLists', 'ui.bootstrap', 
			'ngYoutubeEmbed', 'uiCropper', 'LocalStorageModule', 'ngTagsInput', 'ui.sortable'])
		.config(config)
}());