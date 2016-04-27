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

/*
 * Control the collapse widget on the right sidebar
 */

$(function () {
	$('#accordion').on('show.bs.collapse', function (e) {
		$(e.target).parent().removeClass("closed");
		$(e.target).parent().addClass("open");
	});

	$('#accordion').on('hidden.bs.collapse', function (e) {
		$(e.target).parent().removeClass("open");
		$(e.target).parent().addClass("closed");
	});

	$('#accordion .panel-heading a').on('click',function(e){
		if($(this).parents('.panel').children('.panel-collapse').hasClass('in')){
			e.stopPropagation();
		}
		e.preventDefault();
	});
});

/*
 * Make social share icons work
 */

$(function () {
	SocialShareKit.init();
});

todevise.controller('productCtrl', ['$scope', '$cacheFactory', function ($scope, $cacheFactory) {

	$scope.lang = _lang;
	$scope.product = _product;
	$scope.deviser = _deviser;
	$scope.tags = _tags;

	$scope.c_tags = $cacheFactory("tags");

	/*
	 ██████  ███████ ████████     ████████  █████   ██████
	██       ██         ██           ██    ██   ██ ██
	██   ███ █████      ██           ██    ███████ ██   ███
	██    ██ ██         ██           ██    ██   ██ ██    ██
	 ██████  ███████    ██           ██    ██   ██  ██████
	*/
	$scope.getTag = function(tag_id) {
		var res = $scope.c_tags.get(tag_id);
		if(res !== undefined) return res;

		angular.forEach(_tags, function(tag) {
			$scope.c_tags.put(tag.short_id, tag);
		});

		return $scope.c_tags.get(tag_id);
	};

	$scope.getTagOption = function(tag, value) {
		var _match = null;
		angular.forEach(tag.options, function(option) {
			if(option.value === value) _match = option;
		});
		return _match;
	};

	$scope.init = function () {
		console.log("init");
	};
}]);
