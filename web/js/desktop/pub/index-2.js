$(function() {
	$('#btnMoreWorks').click(function () {

		var data = $('#formPagination').serialize();
		$.get(currentHost() + '/public/more-works', data)

			.done(function (r) {
				var data = JSON.parse(r);
				$('#works-container').append(data.html);
				$('#category_id').val(data.category_id);
			})

			.fail(function () {
				location.reload();
			});
	});
});
