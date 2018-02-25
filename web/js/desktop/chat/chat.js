(function () {
	"use strict";

	function config($provide, $translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('chat');
	}

	angular
		.module('chat', ['api', 'util', 'header', 'ui.bootstrap', 'pascalprecht.translate'])
		.config(config);

}());