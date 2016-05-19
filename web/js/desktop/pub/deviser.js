var todevise = angular.module('todevise', []);

/*
 * This enables the tab navigation and the flickr-layout of the products
 */

$(function() {
	function flickrify() {
		$(".products .products_holder").justifiedGallery({
			fixedHeight: true,
			rowHeight: 210,
			caption: false,
			margins: 10,
			border: 0,
			waitThumbnailsLoad: false
		});
	}

	$('[role="tabpanel"]').on("pjax:success", function (e) {
		flickrify();
	});

	$('[data-toggle="tab"]').on("show.bs.tab", function (e) {
		var id = $(e.target).attr("href").substr(1);
		if(id !== "deviser_works") return;
		flickrify();
	})

	$('[data-toggle="tab"]:first').tab('show');

	flickrify();
});
