(function () {
	console.log("Global desktop admin");

	function controller($uibModalInstance, data) {
		var vm = this;

		vm.data = angular.extend({}, {
			title: "Please confirm",
			text: "",
			ok: "Ok",
			cancel: "Cancel"
		}, data);

		vm.ok = function () {
			$uibModalInstance.close();
		}

		vm.cancel = function () {
			$uibModalInstance.dismiss();
		}
	};

	angular.module('global-admin', ['toastr', 'template/modal/confirm.html'])
		.controller('confirmCtrl', controller);

	angular.module("template/modal/confirm.html", [])
		.run(["$templateCache", function ($templateCache) {
			$templateCache.put("template/modal/confirm.html",
				"<div class='modal-header'>" +
				"	<h3 class='modal-title'>{{ data.title }}</h3>" +
				"</div>" +
				"<div class='modal-body'>" +
				"	<div>{{ data.text }}</div>" +
				"</div>" +
				"<div class='modal-footer'>" +
				"	<button class='btn btn-danger' ng-click='ok()'>{{ data.ok }}</button>" +
				"	<button class='btn btn-primary' ng-click='cancel()'>{{ data.cancel }}</button>" +
				"</div>"
			);
			}]);
}());