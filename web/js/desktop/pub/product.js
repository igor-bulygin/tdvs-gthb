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
		//var carosel = $('.carosel-inner');
		//var item = $('.carosel-item');
		//item.css('max-height', carosel.height() + "px");
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
 * Adjust the min height of the right bar and the main content
 */

$(function () {
	var h = 0;

	$('.info').children().each(function () {
		h += $(this).outerHeight();
	});

	$('.gallery_wrapper').css('min-height', h + "px");

	var th1 = $('.deviser_wrapper').outerHeight();
	var th2 = $('.gallery_wrapper').outerHeight();
	var th3 = $('.tabs-wrapper').outerHeight();
	$('.product').css('min-height', th1 + th2 + th3 + "px");
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
 * This enables the tab navigation and the flickr-layout of the products
 */

$(function() {
	function flickrify() {
		console.log("Flickering!");
		$("#deviser_works .products_holder").justifiedGallery({
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

	$scope.colors = _colors;

	$scope.colors_lookup = {};
	angular.forEach(_colors, function(color) {
		$scope.colors_lookup[color.value] = color;
	});

	$scope.c_tags = $cacheFactory("tags");
	$scope.c_tags_options = $cacheFactory("tags_options");

	$scope.selected_options = {};
	$scope.selected_options_index = {};
	$scope.selected_options_match = {};

	$scope.dump = function(obj) {
		return angular.toJson(obj, 4);
	};

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
		var res = $scope.c_tags_options.get(tag.short_id + "" + value);
		if(res !== undefined) return res;

		angular.forEach(tag.options, function(option) {
			if(option.value === value) {
				$scope.c_tags_options.put(tag.short_id + "" + value, option);
			}
		});

		return $scope.c_tags_options.get(tag.short_id + "" + value);
	};

	$scope.get_color_from_value = function(value) {
		if($scope.colors_lookup.hasOwnProperty(value)) {
			return $scope.colors_lookup[value];
		} else {
			return {};
		}
	};

	$scope.find_price = function () {
		for (var i = 0; i < $scope.product.price_stock.length; i++) {
			var option = $scope.product.price_stock[i];
			if (angular.equals(option.options, $scope.selected_options)) {
				$scope.selected_options_match = {
					weight: option.weight,
					stock: option.stock,
					price: option.price
				}
			}
		}
	}

	$scope.init = function () {
		/* Preselect the first available option of the product */
		$scope.selected_options = JSON.parse(JSON.stringify($scope.product.price_stock[0].options));
		angular.forEach($scope.selected_options, function (tag, tag_id) {
			$scope.selected_options_index[tag_id] = 0;
		});
	};

	$scope.$watch('selected_options', function (_new, _old) {
		$scope.find_price();
	}, true);
}]);
