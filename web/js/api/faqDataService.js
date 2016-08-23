(function () {
	"use strict";

	function faqDataService($resource) {
		this.faq = $resource(currentHost() + 'api3/pub/v1/faqs/')
		this.adminFaq = $resource(currentHost() + '/api3/admin/v1/faqs/');
	}

	angular.module('api')
		.service('faqDataService', faqDataService);

}());