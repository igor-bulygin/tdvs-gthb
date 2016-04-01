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
