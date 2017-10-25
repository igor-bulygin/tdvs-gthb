$(function() {
	console.log('pasando');
	$('#btnMoreWorks').click(function() {
		console.log('click');
		var url = currentHost()+'/public/more-works';
		console.log(url);
		$.get(url, function( data ) {
			console.log('loaded');
			$('#works-container').append(data);
			// $('#works-container').append(data.html);
		});
	});
});
