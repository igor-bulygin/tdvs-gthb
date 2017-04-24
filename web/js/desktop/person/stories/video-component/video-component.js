(function () {
	"use strict";

	function controller($scope, UtilService) {
		var vm = this;
		vm.youTubeRegEx = UtilService.youTubeRegEx;
		vm.has_error = UtilService.has_error;
		vm.addVideo = addVideo;

		function addVideo(form) {
			form.$setSubmitted();
			if(form.$valid) {
				if(!angular.isArray(vm.component.items))
					vm.component['items'] = []
				vm.component.items.push(vm.video_url);
			}
		}
	}

	var component = {
		templateUrl: currentHost() + "/js/desktop/person/stories/video-component/video-component.html",
		controller: controller,
		controllerAs: "storyVideoComponentCtrl",
		bindings: {
			component: '<'
		}
	}

	angular
		.module('todevise')
		.component('storyVideoComponent', component);

}());