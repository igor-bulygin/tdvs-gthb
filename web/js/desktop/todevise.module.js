

(function () {
var moduleTodevise = angular.module('todevise', ['header', 'api', 'util', 'toastr', 'box', 'person', 'product',
	'discover', 'settings', 'cart','pascalprecht.translate']);

moduleTodevise.config(['$translateProvider','$translatePartialLoaderProvider', function ($translateProvider,$translatePartialLoaderProvider) {
	$translatePartialLoaderProvider.addPart('todevise');
	$translateProvider.useLoader('$translatePartialLoader', {
		urlTemplate: '/translations/modules/{part}/{lang}.pod'
	});	
	$translateProvider.preferredLanguage('es');
	$translateProvider.useSanitizeValueStrategy('sanitize');
	$translateProvider.useLoaderCache(true);
}]);

}());
