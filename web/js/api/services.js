(function () {
	"use strict";

	function $services_util($http, $q) {
		var api_helpers = {};

		api_helpers._handleSuccess = _handleSuccess;
		api_helpers._handleError = _handleError;
		api_helpers._get = _get;
		api_helpers._modify = _modify;

		function _handleSuccess(response) {
			return response.data;
		}

		function _handleError(response) {
			if (!angular.isObject(response.data) || !response.data.message) {
				return $q.reject("An unknown error ocurred.")
			}
			return $q.reject(response.data.message);
		}

		function _get(url, filters) {
			filters = filters !== undefined ? "?filters=" + aus._objToStr(filters) : "";

			return $http({
				method: "get",
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				url: url + filters
			});
		};

		function _modify(url, method, data) {
			var csrf = yii.getCsrfToken();
			data["_csrf"] = csrf;
			return $http({
				method: method,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'X-CSRF-Token': csrf
				},
				url: url,
				data: data
			});
		};

		return api_helpers;
	}

	function $admin($services_util) {
		var api_point = currentHost() + "/api/admins/";

		var adminObject = {};

		adminObject.get = _get;
		adminObject.modify = _modify;
		adminObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, admin) {
			console.log(admin);
			var req = $services_util._modify(api_point, method, {
				person: admin
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(admin) {
			return this.modify("DELETE", admin);
		}

		return adminObject;
	}

	function $deviser($services_util) {
		var api_point = currentHost() + "/api/devisers/";

		var deviserObject = {};

		deviserObject.get = _get;
		deviserObject.modify = _modify;
		deviserObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, deviser) {
			var req = $services_util._modify(api_point, method, {
				person: deviser
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(deviser) {
			return this.modify("DELETE", deviser);
		}

		return deviserObject;
	}

	function $person($services_util) {
		var api_point = currentHost() + "/api/persons/";

		var personObject = {};

		personObject.get = _get;
		personObject.modify = _modify;
		personObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, person) {
			var req = $services_util._modify(api_point, method, {
				person: person
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(person) {
			return this.modify("DELETE", person);
		}

		return personObject;
	}

	function $product($services_util) {
		var api_point = currentHost() + "/api/products/";

		var productObject = {};

		productObject.get = _get;
		productObject.modify = _modify;
		productObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, product) {
			var req = $services_util._modify(api_point, method, {
				product: product
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(product) {
			return this.modify("DELETE", product);
		}

		return productObject;

	}

	function $tag($services_util) {
		var api_point = currentHost() + "/api/tags/";

		var tagObject = {};

		tagObject.get = _get;
		tagObject.modify = _modify;
		tagObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, tag) {
			var req = $services_util._modify(api_point, method, {
				tag: tag
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(tag) {
			return this.modify("DELETE", tag);
		}

		return tagObject;

	}

	function $sizechart($services_util) {
		var api_point = currentHost() + "/api/size-charts/";

		var sizechartsObject = {};

		sizechartsObject.get = _get;
		sizechartsObject.modify = _modify;
		sizechartsObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, sizechart) {
			var req = $services_util._modify(api_point, method, {
				sizechart: sizechart
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(sizechart) {
			return this.modify("DELETE", sizechart);
		}

		return sizechartsObject;
	}

	function $category($services_util) {
		var api_point = currentHost() + "/api/categories/";

		var categoryObject = {};

		categoryObject.get = _get;
		categoryObject.modify = _modify;
		categoryObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, node) {
			var req = $services_util._modify(api_point, method, {
				category: node
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(node) {
			return this.modify("DELETE", node);
		}

		return categoryObject;
	}

	function $faqs($services_util) {
		var api_point = currentHost() + "/api/faqs/";

		var faqsObject = {};

		faqsObject.get = _get;
		faqsObject.modify = _modify;
		faqsObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, node) {
			var req = $services_util._modify(api_point, method, {
				category: node
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(node) {
			return this.modify("DELETE", node);
		}

		return faqsObject;
	}

	function $terms($services_util) {
		var api_point = currentHost() + "/api/terms/";

		var termsObject = {};

		termsObject.get = _get;
		termsObject.modify = _modify;
		termsObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, node) {
			var req = $services_util._modify(api_point, method, {
				category: node
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(node) {
			return this.modify("DELETE", node);
		}

		return termsObject;
	}

	function $term($services_util) {
		var api_point = currentHost() + "/api/term/";

		var termObject = {};

		termObject.get = _get;
		termObject.modify = _modify;
		termObject.delete = _delete;

		function _get(filters) {
			var req = $services_util._get(api_point, filters);

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _modify(method, node) {
			var req = $services_util._modify(api_point, method, {
				category: node
			});

			return req.then($services_util._handleSuccess, $services_util._handleError);
		}

		function _delete(node) {
			return this.modify("DELETE", node);
		}

		return termObject;
	}

	angular.module('api')
		.factory("$services_util", $services_util)
		.service("$admin", $admin)
		.service("$deviser", $deviser)
		.service("$person", $person)
		.service("$product", $product)
		.service("$tag", $tag)
		.service("$sizechart", $sizechart)
		.service("$category", $category)
		.service("$faqs", $faqs)
		.service("$terms", $terms)
		.service("$term", $term);
}());