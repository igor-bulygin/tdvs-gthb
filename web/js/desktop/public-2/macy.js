$('.macy-container').each(function() {
	var defaults = {
		trueOrder: true,
		waitForImages: true,
		margin: 2,
		columns: 6,
		breakAt: {
			1200: 5,
			940: 3,
			520: 2,
			400: 2
		}
	};
	var options = {
		container: '#' + this.id,
		trueOrder: $(this).data('trueorder'),
		waitForImages: $(this).data('waitforimages'),
		margin: $(this).data('margin'),
		columns: $(this).data('columns')
	};
	options = $.extend(defaults, options);

	window.onload = function() {
		Macy.init(options);
	}

});
