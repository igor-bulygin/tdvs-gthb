(function () {
	"use strict";

	function UtilService($location) {
		this.isObject = isObject;
		this.diff = diff;
		this.returnDeviserIdFromUrl = returnDeviserIdFromUrl;
		this.has_error = has_error;
		this.parseImagesUrl = parseImagesUrl;

		function returnDeviserIdFromUrl() {
			var url = $location.absUrl();
			return url.split('/')[5];
		}

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

		function has_error(form, field) {
			if (field) {
				return (form.$submitted || field.$touched) && field.$invalid;
			}
		}

		function parseImagesUrl(images, url) {
			var parsed_images = [];
			for (var i = 0; i < images.length; i++) {
				parsed_images[i] = {
					pos: i,
					url: currentHost() + url + images[i],
					filename: images[i]
				};
			}
			return parsed_images;
		}

	}

	angular.module('util', ['util.formMessages'])
		.service('UtilService', UtilService);

}());