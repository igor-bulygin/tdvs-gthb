(function () {
	"use strict";

	function UtilService() {
		this.HelloWorld = function () {
			console.log("Hello World!")
		}
	}

	angular.module('util', ['util.treeService'])
		.service('UtilService', UtilService);

}());