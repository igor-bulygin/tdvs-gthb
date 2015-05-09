console.log("Global desktop admin");
var todevise = angular.module('todevise', ['ui.bootstrap', 'ngJsTree', 'ngAnimate', 'toastr', 'template/modal/confirm.html']);

todevise.controller("confirmCtrl", function($scope, $modalInstance, data) {
	$scope.data = angular.extend({}, {
		title: "Please confirm",
		text: "",
		ok: "Ok",
		cancel: "Cancel"
	}, data);

	$scope.ok = function() {
		$modalInstance.close();
	};

	$scope.cancel =  function() {
		$modalInstance.dismiss();
	};
});

angular.module("template/modal/confirm.html", []).run(["$templateCache", function($templateCache) {
	$templateCache.put("template/modal/confirm.html",
		"<div class=\"modal-header\">" +
		"	<h3 class=\"modal-title\">{{ data.title }}</h3>" +
		"</div>" +
		"<div class=\"modal-body\">" +
		"	<div>{{ data.text }}</div>" +
		"</div>" +
		"<div class=\"modal-footer\">" +
		"	<button class=\"btn btn-danger\" ng-click=\"ok()\">{{ data.ok }}</button>" +
		"	<button class=\"btn btn-primary\" ng-click=\"cancel()\">{{ data.cancel }}</button>" +
		"</div>" +
		"");
}]);