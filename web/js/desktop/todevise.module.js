

(function () {
var moduleTodevise = angular.module('todevise', ['header', 'api', 'util', 'toastr', 'box', 'person', 'product',
	'discover', 'settings', 'cart','pascalprecht.translate']);

moduleTodevise.config(['$translateProvider','$translatePartialLoaderProvider', function ($translateProvider,$translatePartialLoaderProvider) {
	$translatePartialLoaderProvider.addPart('global');
	$translatePartialLoaderProvider.addPart('todevise');
	$translateProvider.useLoader('$translatePartialLoader', {
		urlTemplate: '/translations/modules/{part}/{lang}.pod'
	});
	var language= _lang.split('-')[0].trim();
	$translateProvider.preferredLanguage(language);
	$translateProvider.useSanitizeValueStrategy('sanitize');
	$translateProvider.useLoaderCache(true);
}]);

}());
