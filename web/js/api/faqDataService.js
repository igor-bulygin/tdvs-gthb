(function () {
	"use strict";

	function faqDataService($resource, config) {
		this.faq = $resource(config.baseUrl + 'pub' + config.version + 'faqs/')
		this.adminFaq = $resource(config.baseUrl + 'admin/' + config.version + 'faqs/');
	}

	angular.module('api')
		.service('faqDataService', faqDataService);

}());