/*
 * This manages the become todeviser form
 */

(function () {
	"use strict";

	function becomeCtrl($cacheFactory) {
		var vm = this;

		function newItem() {
			return {
				link: ''
			};
		}

		function init() {
			vm.portfolio_links = [{
				link: ''
			}];

			vm.video_links = [{
				link: ''
			}]
		}

		init();

		vm.newVideoLink = function () {
			vm.video_links.push(newItem());
		}

		vm.newPortFolioLink = function () {
			vm.portfolio_links.push(newItem());
		}
	}

	angular.module('todevise', [])
		.controller('becomeCtrl', becomeCtrl);

}());