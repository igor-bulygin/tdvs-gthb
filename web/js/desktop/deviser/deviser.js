(function () {
	"use strict";

	function config(nyaBsConfigProvider, $provide) {
		nyaBsConfigProvider.setLocalizedText('en-us', {
			defaultNoneSelection: 'Choose an option'
		});

		//events
		$provide.value("deviserEvents", {
			deviser_changed: 'deviser-changed',
			deviser_updated: 'deviser-updated',
		});
	}

	angular
		.module('todevise', ['api', 'util', 'toastr', 'nya.bootstrap.select', 'textAngular', 'ngFileUpload', 'dndLists', 'ui.bootstrap', 'ngYoutubeEmbed', 'ngImgCrop'])
		.config(config)
}());