var todevise = angular.module('todevise', []);

/*
 * This makes the carousel work
 */

$(function () {

	calc_carousel_max_height();

	$(window).resize($.throttle(250, calc_carousel_max_height));

	$('.carosel-control-right').click(function() {
		var items = $('.carosel').find('.carosel-item');
		var f = items.first();
		items.animate({ left: -f.width() + "px" }, 'slow', function () {
			items.css('left', '0px');
			f.insertAfter(items.last());
		});
	});

	$('.carosel-control-left').click(function() {
		var items = $('.carosel').find('.carosel-item');
		var l = items.last();
		l.insertBefore(items.first());
		items.css('left', -l.width() + 'px');
		items.animate({ left: "0px" }, 'slow');
	});

	function calc_carousel_max_height () {
		$('.carosel-item').css('max-height', $('.carosel-inner').height() + "px");
	}

});

/*
 * This adjusts the height of the avatar
 */

$(function () {

	calc_avatar_height();

	$(window).resize($.throttle(250, calc_avatar_height));

	function calc_avatar_height () {
		var av = $('.product .deviser_wrapper .info .avatar_wrapper .avatar');
		av.height(av.width());
	}
});
