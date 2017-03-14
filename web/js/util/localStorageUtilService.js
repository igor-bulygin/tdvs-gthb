(function () {
	"use strict";

	function localStorageUtilService(localStorageService) {
		this.setLocalStorage = setLocalStorage;
		this.getLocalStorage = getLocalStorage;
		this.removeLocalStorage = removeLocalStorage;

		function setLocalStorage(key, value) {
			return localStorageService.set(key, value);
		}

		function getLocalStorage(key) {
			return localStorageService.get(key);
		}

		function removeLocalStorage(key) {
			return localStorageService.remove(key);
		}
	}

	angular
		.module('util')
		.service('localStorageUtilService', localStorageUtilService);

}());