(function () {
	"use strict";

	function config(localStorageServiceProvider, $provide) {
		//events
		$provide.value("cartEvents", {
			cartUpdated: 'cart-updated'
		});

		localStorageServiceProvider
			.setPrefix('todevise-');
	}

	angular
		.module('todevise', ['api', 'util', 'header', 'nya.bootstrap.select'])
		.config(config);

}());