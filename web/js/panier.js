var panier = {
		
};

jQuery(document).ready(function($) {
	$('.acheter').on('click', function() {
		var $reference = $(this).parent().find('.reference').val();
		$.ajax({
			url: "../panier",
			type: "POST",
			data: {ref : $reference},
			dataType: "json"
		});
		return false;
	});
});