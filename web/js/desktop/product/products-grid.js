$(document).ready(function() {

	var options = {
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
	};
	Macy.init(options);

	setTimeout(function () {
		Macy.recalculate();
	});

	$('a[data-toggle="tab"]').on('click', function(e) {
		setTimeout(function () {
			Macy.recalculate();
		}, 50);
	});

});