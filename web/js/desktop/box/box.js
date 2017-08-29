(function () {
	"use strict";

	function config($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('box');
	}

	angular
		.module('box', ['api', 'pascalprecht.translate'])
		.config(config);

}());