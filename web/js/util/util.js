(function () {
	"use strict";

	function UtilService() {
		this.isObject = isObject;
		this.diff = diff;

		function isObject(object) {
			return (object !== null && typeof object === "object");
		}

		function diff(obj1, obj2) {
			if (!isObject(obj1) || !isObject(obj2))
				return null;
			else {
				var newObject = {};
				for (var key in obj1) {
					if (!obj2[key]) {
						newObject[key] = obj1[key];
					} else {
						if (isObject(obj1[key])) {
							if (!angular.equals(obj1[key], obj2[key])) {
								newObject[key] = obj1[key];
							}
						} else {
							if (obj1[key] !== obj2[key]) {
								newObject[key] = obj1[key];
							}
						}
					}
				}
				return newObject;
			}
		}
	}

	angular.module('util', [])
		.service('UtilService', UtilService);

}());