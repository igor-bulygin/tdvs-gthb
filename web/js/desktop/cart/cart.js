(function () {
	"use strict";

	function config(localStorageServiceProvider, $provide, $translatePartialLoaderProvider) {
		//events
		$provide.value("cartEvents", {
			cartUpdated: 'cart-updated'
		});

		localStorageServiceProvider
			.setPrefix('todevise-');

		$translatePartialLoaderProvider.addPart('cart');
	}

	angular
		.module('cart', ['api', 'util', 'header', 'nya.bootstrap.select', 'pascalprecht.translate'])
		.config(config);

}());