(function () {
	"use strict";

	function faqDataService($resource, apiConfig) {
		this.faq = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'faqs/')
		this.adminFaq = $resource(apiConfig.baseUrl + 'admin/' + apiConfig.version + 'faqs/');
	}

	angular.module('api')
		.service('faqDataService', faqDataService);

}());