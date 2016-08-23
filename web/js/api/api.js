(function () {
	"use strict";

	angular.module('api', ['ngResource'])
		.constant('config', {
			baseUrl: currentHost() + '/api3/',
			version: 'v1/'
		});
}());