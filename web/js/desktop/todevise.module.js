

(function () {
var moduleTodevise = angular.module('todevise', ['header', 'api', 'util', 'toastr', 'box', 'person', 'product',
	'discover', 'settings', 'cart','pascalprecht.translate']);

moduleTodevise.config(['$translateProvider', function ($translateProvider) {
	var fileNameConvention = {
		prefix: '/translations/locale-',
		suffix: '.pod'
	};  
	$translateProvider.useStaticFilesLoader(fileNameConvention);
	$translateProvider.preferredLanguage('es');
	$translateProvider.useSanitizeValueStrategy('textAngular-sanitize');
}]);

}());
