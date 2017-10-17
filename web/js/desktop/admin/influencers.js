(function () {
	"use strict";

	function influencersCtrl($scope, $timeout, $person, toastr, $uibModal, $compile, $http) {
		var vm = this;

		vm.renderPartial = function () {
			$http.get(aus.syncToURL()).success(function (data, status) {
				angular.element('.body-content').html($compile(data)($scope));
			}).error(function (data, status) {
				toastr.error("Failed to refresh content!");
			});
		};

		vm.delete = function (influencer_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to delete a influencer! All items related to this user (loved, boxes, settings, media....) will be deleted. This action can not be undone"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: influencer_id
				}).then(function (influencers) {
					if (influencers.length !== 1) return;
					var influencer = influencers[0];

					$person.delete(influencer).then(function (data) {
						toastr.success("Influencer deleted!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't delete influencer!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find influencer!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.block = function (influencer_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to block a influencer! The influencer will no longer have access to the web"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: influencer_id
				}).then(function (influencers) {
					if (influencers.length !== 1) return;
					var influencer = influencers[0];
					influencer.account_state = 'blocked';

					$person.modify('POST', influencer).then(function (data) {
						toastr.success("influencer blocked!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't block influencer!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find influencer!", err);
				});
			}, function () {
				//Cancel
			});
		};

		vm.draft = function (influencer_id) {
			var modalInstance = $uibModal.open({
				templateUrl: 'template/modal/confirm.html',
				controller: 'confirmCtrl',
				resolve: {
					data: function () {
						return {
							title: "Are you sure?",
							text: "You are about to set a influencer as draft! The influencer will have access to the web, but his profile will be marked as draft"
						};
					}
				}
			});

			modalInstance.result.then(function () {
				$person.get({
					short_id: influencer_id
				}).then(function (influencers) {
					if (influencers.length !== 1) return;
					var influencer = influencers[0];
					influencer.account_state = 'draft';

					$person.modify('POST', influencer).then(function (data) {
						toastr.success("influencer actived as draft!");
						vm.renderPartial();
					}, function (err) {
						toastr.error("Couldn't activate influencer!", err);
					});
				}, function (err) {
					toastr.error("Couldn't find influencer!", err);
				});
			}, function () {
				//Cancel
			});
		};
	}

	angular.module('todevise', ['ngAnimate', 'ui.bootstrap', 'angular-multi-select', 'global-admin', 'global-desktop', 'api'])
		.controller('influencersCtrl', influencersCtrl)

}());