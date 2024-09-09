forms = {
	'post-general-details-create' : 'onUpdateGeneralDetails',
	'post-general-details-update' : 'onUpdateGeneralDetails',
}

$.each( forms, function( key, value ) {
	$('body').on('submit','#'+key, function(e) {
		e.preventDefault();
		if(validateForm('#'+key,'.dz-hidden-input,.not-required,:hidden,:checkbox','textarea') === true){
			var inputs = $('#'+key+' :input');
		    var data = {};
		    if(key == 'post-general-details-update'){
		    	data['Managers'] = $('#Managers').dropdown('get value');
		    	//data['Alias'] = $('#Alias').dropdown('get value');
		    }
		    inputs.each(function() {
		        data[this.name] = $(this).val();
		    });

            requestFactory('PostEdit::'+value, data, ['#html2target'], () => { convertToCKEditor('contenttxt') });
	    }
	});
});

$('body').on('click','.change-tab',function(e) {
	e.preventDefault();
	navoption = $(this).attr('data-id');
    var data = {
    	'navoption' : navoption
    };
    $.when(requestFactory('PostEdit::onUpdateTab', data, ['#html2target']));
});
