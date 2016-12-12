(function () {
	"use strict";

	function UtilService($location) {
		this.isObject = isObject;
		this.isEmpty = isEmpty;
		this.diff = diff;
		this.returnDeviserIdFromUrl = returnDeviserIdFromUrl;
		this.returnProductIdFromUrl = returnProductIdFromUrl;
		this.emptyArrayToObject = emptyArrayToObject;
		this.parseMultiLanguageEmptyFields = parseMultiLanguageEmptyFields;
		this.has_error = has_error;
		this.parseImagesUrl = parseImagesUrl;
		this.isZeroOrLess = isZeroOrLess;
		this.returnPathFromCategory = returnPathFromCategory;
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

		function isEmpty(object) {
			var hasOwnProperty = Object.prototype.hasOwnProperty;
			if (object == null) return true;
			if (object.length > 0) return false;
			if (object.length === 0) return true;
			if (typeof object !== "object") return true;
			for (var key in object) {
				if (hasOwnProperty.call(object, key)) return false;
			}
			return true;
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
				var name;
				if(images[i].name && images[i].name !== null && images[i].name !== undefined) {
					name = images[i].name;
				} else {
					name = images[i];
				}
				parsed_images[i] = {
					pos: i,
					url: currentHost() + url + name,
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

		function parseMultiLanguageEmptyFields(obj) {
			for(var key in obj) {
				if(obj[key] && obj[key].length === 0) {
					delete obj[key];
				}
			}
		}

		function isZeroOrLess(value) {
			if(value === undefined || value === null) return false;
			return value <= 0 ? true : false;
		}

		function returnPathFromCategory(categories, id) {
			for(var i = 0; i < categories.length; i++) {
				if(categories[i].id === id)
					return categories[i].path;
			}
		}
	}

	angular.module('util', ['util.formMessages'])
		.service('UtilService', UtilService);

}());