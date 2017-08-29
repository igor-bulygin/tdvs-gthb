(function () {
	"use strict";

function config($translatePartialLoaderProvider) {
		$translatePartialLoaderProvider.addPart('discover');
	}
	angular
		.module('discover', ['api', 'util','pascalprecht.translate']).config(config);
}());