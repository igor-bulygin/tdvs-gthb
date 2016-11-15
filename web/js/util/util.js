(function () {
	"use strict";

	function UtilService($location) {
		this.isObject = isObject;
		this.diff = diff;
		this.returnDeviserIdFromUrl = returnDeviserIdFromUrl;
		this.returnProductIdFromUrl = returnProductIdFromUrl;
		this.emptyArrayToObject = emptyArrayToObject;
		this.has_error = has_error;
		this.parseImagesUrl = parseImagesUrl;
		//regex from: https://gist.github.com/dperini/729294
		//added "?" after (?:(?:https?|ftp):\/\/) for urls like www.google.es
		this.urlRegEx = /^(?:(?:https?|ftp):\/\/)?(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$/i;

		function returnDeviserIdFromUrl() {
			var url = $location.absUrl();
			return url.split('/')[5];
		}

		function returnProductIdFromUrl() {
			var url = $location.absUrl();
			return url.split('/')[7];
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

		function emptyArrayToObject(array) {
			if(angular.isArray(array) && array.length === 0) {
				return {};
			}
			else {
				return array;
			}
		}

	}

	angular.module('util', ['util.formMessages'])
		.service('UtilService', UtilService);

}());