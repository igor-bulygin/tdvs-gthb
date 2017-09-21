(function () {

	$('a[data-toggle="tab"]').on('click', function (e) {
		setTimeout(function () {
			Macy.recalculate();
		}, 50);
	});

});