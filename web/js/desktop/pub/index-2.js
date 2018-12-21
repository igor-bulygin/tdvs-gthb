$(function() {
	$('#btnMoreWorks').click(function () {

		var load_products = "";
		$("#works-container image-hover-buttons").each(function( index ) {
			if(load_products == ""){
  			load_products += $(this).attr('product-id');
			} else {
				load_products += "," + $(this).attr('product-id');
			}
		});

		$("#product_ids").val(load_products);

		var data = $('#formPagination').serialize();
		$.get(currentHost() + '/public/more-works', data)

			.done(function (r) {
				var data = JSON.parse(r);
				angular.element('#works-container').injector().invoke(function($rootScope, $compile) {
					$('#works-container').append($compile(data.html)($rootScope));
					$('#category_id').val(data.category_id);
					if(data.num_works < 48) {
						$("#btnMoreWorks").css('display','none');
					}
					
					setTimeout(function() {
						var bLazy = new Blazy({
							offset: 500
						});
					}, 250);
				})
			})

			.fail(function () {
				location.reload();
			});
	});
});
