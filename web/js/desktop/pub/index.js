var todevise = angular.module('todevise', []);

/*
 * This makes the 'Devisers' row scroll when the mouse moves inside the div.
 */
$(function() {
	var body_content = $('.body-content');
	var container_width = $('.devisers_carousel_wrapper').innerWidth();
	var slider_width = 0;
	var items = $('.devisers_carousel .deviser_holder').each(function() {
		slider_width += $(this).outerWidth(true);
	});

	var diff = container_width - slider_width;
	var offset = body_content.offset().left

	var padding = body_content.width() / 4;
	diff -= padding;

	$('.devisers_carousel_wrapper').mousemove(function(e) {
		var real_x = e.pageX - offset;
		x = diff * (real_x / container_width);
		x += padding / 2;
		items.css({ left: x + "px" });
	});
});

/*
 * This enables the tab navigation and the flickr-layout of the products
 */
$(function() {
	function flickrify(tab_id) {
		$("#" + tab_id + " .products_holder").justifiedGallery({
			fixedHeight: true,
			rowHeight: 210,
			caption: false,
			margins: 10,
			border: 0,
			waitThumbnailsLoad: false
		});
	}

	$('[role="tabpanel"]').on("pjax:success", function (e) {
		var id = $(e.target).attr("id");
		flickrify(id);
	});

	$('[data-toggle="tab"]').on("show.bs.tab", function (e) {
		var id = $(e.target).attr("href").substr(1);
		flickrify(id);
	})

	$('[data-toggle="tab"]:first').tab('show');
});
