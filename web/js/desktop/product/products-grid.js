$(document).ready(function() {

	var options = {
		container: '#macy-container',
		trueOrder: true,
		waitForImages: true,
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

	$('a[data-toggle="tab"]').on('click', function(e) {
		setTimeout(function () {
			Macy.recalculate();
		}, 50);
	});

});