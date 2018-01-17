(function () {
	'use strict';
	
	function config($translateProvider, $translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('global');
		$translatePartialLoaderProvider.addPart('todevise');
		$translatePartialLoaderProvider.addPart('admin');
		$translateProvider.useLoader('$translatePartialLoader', {
			urlTemplate: '/translations/modules/{part}/{lang}.pod'
		});
		var language= _lang.split('-')[0].trim();
		$translateProvider.preferredLanguage(language);
		$translateProvider.useSanitizeValueStrategy('sanitize');
		$translateProvider.useLoaderCache(true);
	}

angular
	.module('todevise', ['header', 'footer', 'api', 'util', 'toastr', 'box', 'person', 'product', 'discover', 'settings', 'cart','pascalprecht.translate'])
	.config(config);

}());
