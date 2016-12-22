var todevise = angular.module('todevise', ['api', 'util', 'header']);

/*
 * This makes the 'Devisers' row scroll when the mouse moves inside the div.
 */

$(function() {
	var container_width, offset, diff, padding, items;
	var body_content = $('.body-content');

	calc_carousel_width();

	$(window).resize($.throttle(250, calc_carousel_width));

	$('.devisers_carousel_wrapper').mousemove(function(e) {
		var real_x = e.pageX - offset;
		x = diff * (real_x / container_width);
		x += padding / 2;
		items.css({ left: x + "px" });
	});

	function calc_carousel_width () {
		container_width = $('.devisers_carousel_wrapper').innerWidth();
		var slider_width = 0;
		items = $('.devisers_carousel .deviser_holder').each(function() {
			slider_width += $(this).outerWidth(true);
		});

		diff = container_width - slider_width;
		offset = body_content.offset().left

		padding = body_content.width() / 4;
		diff -= padding;

		/*Center slider*/
		var left = (slider_width - container_width) / 2;
		items.css({ left: -left + "px" });
	}
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
