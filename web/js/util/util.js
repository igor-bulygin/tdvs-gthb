(function () {
	"use strict";

	function UtilService($location, localStorageService, $window) {
		this.isObject = isObject;
		this.isEmpty = isEmpty;
		this.diff = diff;
		this.arrayDiff = arrayDiff;
		this.emptyArrayToObject = emptyArrayToObject;
		this.parseMultiLanguageEmptyFields = parseMultiLanguageEmptyFields;
		this.has_error = has_error;
		this.parseImagesUrl = parseImagesUrl;
		this.isZeroOrLess = isZeroOrLess;
		this.isStringNotEmpty = isStringNotEmpty;
		this.returnPathFromCategory = returnPathFromCategory;
		this.stripHTMLTags = stripHTMLTags;
		this.onError = onError;
		this.setLeavingModal = setLeavingModal;
		this.isDeviser = isDeviser;
		this.isInfluencer = isInfluencer;
		this.isClient = isClient;
		this.isPublic = isPublic;
		this.isDraft = isDraft;
		this.setElementPosition = setElementPosition;
		this.parseDate=parseDate;
		this.truncateString = truncateString;
		this.isConnectedUser = isConnectedUser;
		this.getConnectedUser = getConnectedUser;

		//regex from: https://gist.github.com/dperini/729294
		//added "?" after (?:(?:https?|ftp):\/\/) for urls like www.google.es
		this.urlRegEx = /^(?:(?:https?|ftp):\/\/)?(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$/i;
		//regex from: http://stackoverflow.com/questions/2964678/jquery-youtube-url-validation-with-regex
		this.youTubeRegEx = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;


		function isConnectedUser(person_id) {
			var session_id = localStorageService.get('sesion_id');
			if(person_id === session_id){
				return true;
			}
			return false;
		}

		function getConnectedUser() {
			return localStorageService.get('sesion_id');
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

		function arrayDiff(array1, array2) {
			var newArray = [];
			if(angular.isArray(array1) && angular.isArray(array2)) {
				array1.forEach(function(first_element) {
					if(!array2.find(function(second_element) {
						return first_element == second_element;
					})) {
						newArray.push(first_element)
					}
				})
				array2.forEach(function(first_element) {
					if(!array1.find(function(second_element) {
						return first_element == second_element;
					})) {
						newArray.push(first_element)
					}
				})
			}
			return newArray;
		}

		function has_error(form, field) {
			if (field) {
				return (form.$submitted || field.$touched) && field.$invalid;
			}
		}

		function parseImagesUrl(images, url) {
			var parsed_images = [];
			images.map(function (element, index) {
				var name;
				if (element.main_product_photo && element.name_cropped && element.name_cropped !== null && element.name_cropped !== undefined) {
					name = element.name_cropped;
				}
				else if (element.name && element.name !== null && element.name !== undefined) {
					name = element.name
				}
				else {
					name = element;
				}
				parsed_images.push({
					pos: index,
					url: currentHost() + url + name,
					filename: element
				})
			})
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
				if(angular.isString(obj[key]) && obj[key].length === 0) {
					delete obj[key];
				}
			}
		}

		function isZeroOrLess(value) {
			if(value === undefined || value === null) return true;
			if(typeof value !== 'number') return false;
			return value <= 0 ? true : false;
		}

		function isStringNotEmpty(string) {
			return (typeof string == 'string' && string != '') ? true : false;
		}

		function returnPathFromCategory(categories, id) {
			var category = categories.find(function(element) {
				return element.id === id
			});
			return category.path;
		}

		function stripHTMLTags(value) {
			return $($.parseHTML((value))).text();
		}

		function onError(err){
			console.log(err);
		}

		function setLeavingModal(value) {
			$window.onbeforeunload = function(e) {
				if(value)
					return "If you leave without saving, you will lose the latest changes you made.";
				else {
					return null;
				}
			}
		}

		function isDeviser(person){
			return person.type.indexOf(2) >= 0 ? true : false;
		}

		function isInfluencer(person){
			return person.type.indexOf(3) >= 0 ? true : false;
		}

		function isClient(person){
			return person.type.indexOf(1) >= 0 ? true: false;
		}

		function isPublic(person) {
			return person.account_state === 'active' ? true : false;
		}

		function isDraft(person) {
			return person.account_state === 'draft' ? true : false;
		}

		function setElementPosition(array) {
			return array.map(function(element, index) {
				element.position = index+1;
				return element;
			})
		}

	}

	function config(localStorageServiceProvider,$translatePartialLoaderProvider) {
		localStorageServiceProvider
			.setPrefix('todevise-')
			.setStorageType('localStorage');
		$translatePartialLoaderProvider.addPart('util');
	}

	function capitalize() {
		return function(input) {
			return(!!input ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '');
		}
	}

	function parseDate(date) {
		return new Date(date);
	}

	function truncateString(string, pos, addendum) {
		if(angular.isString(string) && angular.isString(addendum) && pos >= 0){
			if(string.length < pos)
				return string;
			else {
				var new_string = string.slice(0, pos);
				new_string = new_string.concat(addendum);
				return new_string;
			}
		}
		return null;
	}

	

	

	angular.module('util', ['util.formMessages', 'LocalStorageModule', 'ui.bootstrap', 'infinite-scroll', 'uiCropper','pascalprecht.translate'])
		.service('UtilService', UtilService)
		.filter('capitalize', capitalize)
		.config(config);

}());