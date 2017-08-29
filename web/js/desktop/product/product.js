(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide, localStorageServiceProvider,$translatePartialLoaderProvider) {
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
			$translatePartialLoaderProvider.addPart('product');
	}

	angular
		.module('product', ['api', 'util', 'header', 'toastr', 'nya.bootstrap.select', 'textAngular', 'ngFileUpload',
			'dndLists', 'ui.bootstrap', 'uiCropper', 'ngTagsInput', 'ui.bootstrap.datetimepicker', 'LocalStorageModule',
			'xeditable', 'ui.sortable','pascalprecht.translate'])
		.config(config)
		.run(function(editableOptions) {
			editableOptions.theme = 'bs3';
			editableOptions.blurElem = 'submit';
		})
}());