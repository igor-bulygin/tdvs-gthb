(function () {
	"use strict";

	function config(localStorageServiceProvider) {

		localStorageServiceProvider
			.setPrefix('todevise-');
	}

	angular
		.module('todevise', ['api', 'util', 'header', 'nya.bootstrap.select'])

}());