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

	$('.products').on("pjax:success", function (e) {
		flickrify();
	});

	flickrify();
});
