(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide, localStorageServiceProvider) {
		nyaBsConfigProvider.setLocalizedText('en-us', {
			defaultNoneSelection: 'Choose an option'
		});

		//events
		$provide.value("productEvents", {
			setVariations: 'set-variations',
			requiredErrors: 'set-required-errors'
		});

		localStorageServiceProvider
			.setPrefix('todevise-');
	}

	angular
		.module('todevise', ['api', 'util', 'toastr', 'nya.bootstrap.select', 'textAngular', 'ngFileUpload', 'dndLists', 'ui.bootstrap', 'ngImgCrop', 'ngTagsInput', 'ui.bootstrap.datetimepicker', 'LocalStorageModule'])
		.config(config)
}());