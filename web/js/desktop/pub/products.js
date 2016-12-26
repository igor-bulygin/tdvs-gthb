var page = 2;
$(window).scroll(function() {
	var current = ($(window).scrollTop() + $(window).height());
	var height = $(document).height();
	var limit = height-1500;
	console.log('current: '+current+'; height: '+height+'; limit: '+limit);
	if (current >= limit) {
		moreWorks();
	}
	if (current == height) {
		Macy.recalculate(); // force macy recalculate at the end of the page
	}
});

function moreWorks() {
	if (page != null) {
		var data = location.search.replace('?', '') + '&page=' + page;
		page = null;
		$.get('works/more-works', data)
				.done(function (r) {
					var data = JSON.parse(r);
					$('#macy-container').append(data.html);
					page = data.page;
					Macy.recalculate();
				})
				.fail(function () {
					location.reload();
				});
	}
}

function initMacyProducts() {
	Macy.init({
		container: '#macy-container',
		trueOrder: false,
		waitForImages: false,
		margin: 2,
		columns: 6,
		breakAt: {
			1200: 6,
			940: 3,
			520: 2,
			400: 1
		}
	});

	Macy.onImageLoad(null, function () {
		Macy.recalculate();
	});
}

initMacyProducts();
