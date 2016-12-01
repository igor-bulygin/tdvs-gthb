(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide, localStorageServiceProvider) {
		nyaBsConfigProvider.setLocalizedText('en-us', {
			defaultNoneSelection: 'Select an option'
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
		.module('todevise', ['api', 'util', 'toastr', 'nya.bootstrap.select', 'textAngular', 'ngFileUpload',
			'dndLists', 'ui.bootstrap', 'ngImgCrop', 'ngTagsInput', 'ui.bootstrap.datetimepicker', 'LocalStorageModule',
			'xeditable'])
		.config(config)
		.run(function(editableOptions) {
			editableOptions.theme = 'bs3';
		})
}());