$(window).scroll(function() {
	var current = ($(window).scrollTop() + $(window).height());
	var limit = $(document).height()-1500;
	if (current >= limit) {
		moreWorks();
	}
});

function moreWorks() {
	var more = $('#more').val();
	if (more == 1) {
		$('#more').val(0);
		var data = $('#formPagination').serialize();
		$.get('works/more-works', data)
			.done(function (r) {
				var data = JSON.parse(r);
				$('#macy-container').append(data.html);
				$('#page').val(data.page);
				$('#more').val(data.more);
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
		trueOrder: true,
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
}

initMacyProducts();
