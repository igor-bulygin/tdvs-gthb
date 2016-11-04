(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide, localStorageServiceProvider) {
		nyaBsConfigProvider.setLocalizedText('en-us', {
			defaultNoneSelection: 'Choose an option'
		});

		//events
		$provide.value("deviserEvents", {
			deviser_changed: 'deviser-changed',
			deviser_updated: 'deviser-updated',
			make_profile_public_errors: 'make-profile-public-errors'
		});

		localStorageServiceProvider
			.setPrefix('todevise-');
	}

	angular
		.module('todevise', ['api', 'util', 'toastr', 'nya.bootstrap.select', 'textAngular', 'ngFileUpload', 'dndLists', 'ui.bootstrap', 'ngImgCrop', 'ngTagsInput', 'LocalStorageModule'])
		.config(config)
}());