var panier = {
	quantite: function(decalage, stock) {
		var qt = decalage + parseInt($('input[name=quantite]').val());
		if (qt < 1 || qt > stock) 
			return;
		$('input[name=quantite]').val(qt);
	}
};

jQuery(document).ready(function($) {
	$('a').on('click', '.glyphicon-remove', function() {
		var $produit = $(this).parent().find('.reference');
		alert('ok');
		return false;
	});
	$('#achatProduit').on('click', function() {
		var $reference = $(this).parent().find('.reference').val();
		var $quantite  = $('input[name=quantite]').val();
		$.ajax({
			url: "../panier/add",
			type: "POST",
			data: {
				ref : $reference,
				qte : $quantite
			},
			dataType: "json"
		}).done(function(data) {
			if (data.ajax === 'ok') {
				$(location).attr('href', '../panier');
			}
		});
		return false;
	});
});