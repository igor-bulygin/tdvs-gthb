$(function() {
	$('#btnMoreWorks').click(function () {

		var data = $('#formPagination').serialize();
		$.get(currentHost() + '/public/more-works', data)

			.done(function (r) {
				var data = JSON.parse(r);
				angular.element('#works-container').injector().invoke(function($rootScope, $compile) {
					$('#works-container').append($compile(data.html)($rootScope));
					$('#category_id').val(data.category_id);
				})
			})

			.fail(function () {
				location.reload();
			});
	});
});
