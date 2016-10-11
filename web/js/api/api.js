(function () {
	"use strict";

	angular.module('api', ['ngResource'])
		.constant('apiConfig', {
			baseUrl: currentHost() + '/api3/',
			version: 'v1/'
		});
}());