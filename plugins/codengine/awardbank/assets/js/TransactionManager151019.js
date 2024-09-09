$('body').on('keyup', '#pointsvalue', function() {
	clearAlerts();
	var pointvalue = $(this).val();
	var scale = $(this).attr('data-scale');
	var limit = $(this).attr('data-limit');
	if(limit >= 1){
		if(pointvalue >= limit){
			pointvalue = limit;
			$(this).val(limit);
			appendAlert('.pusher.header-margin','negative', 'Program Limit On Purchasable Points Is '+limit);
		}
	}
	dollarvalue = Math.ceil(pointvalue / scale);
	$('#dollarvalue').val(dollarvalue).text(dollarvalue);
});

forms = {
	'buy-points' : 'onCreateTransaction',
}

$.each( forms, function( key, value ) {
	$('body').on('submit','#'+key, function(e) {
		e.preventDefault();
		if(validateForm('#'+key,'.dz-hidden-input,.not-required,:hidden,:checkbox') === true){
			var inputs = $('#'+key+' :input');
		    var data = {};
		    inputs.each(function() {
		        data[this.name] = $(this).val();
		    });
	        requestFactory('TransactionManager::'+value, data, ['#html1target']);
	    }
	});
});
