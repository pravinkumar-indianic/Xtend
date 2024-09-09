$('body').on('click','.won-prize',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('AwardView::onShowWinners', data, ['#html1target']);
});

$('body').on('click','.submit-vote-button',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('AwardView::onAddVote', data, ['#html1target']);
});


forms = {
	'nomineecreateforms' : 'onCreateNomination',
}

$.each( forms, function( key, value ) {
	$('body').on('submit','#'+key, function(e) {
		e.preventDefault();
		if(validateForm('#'+key,'.dz-hidden-input,.search,.not-required,:hidden,:checkbox') === true){
			var inputs = $('#'+key+' :input');
			console.log($('#nomineeselectiondropdown').length);
			if($('#nomineeselectiondropdown').length >= 1){
				nominee = $('#nomineeselectiondropdown').dropdown('get value');
				var data =  {
					'nominee' : nominee,
				};
			} else {
		    	console.log('empty inject');
		    	data = {
		    		'nominee' : 'self',
		    	};				
			}
		    inputs.each(function() {
		    	console.log($(this).attr('type'));
		    	if($(this).attr('type') == 'checkbox'){
		    		result = '';
		    		console.log($(this).is(":checked"));
		    		if($(this).is(":checked") == true){
		    			if(typeof data[this.name] === 'undefined'){
		    				data[this.name] = $(this).val();
		    			} else {
		    				data[this.name] += ' ; '+$(this).val();
		    			}
		    		}
		    	} else {
		    		data[this.name] = $(this).val();
	    		}
		    });
		    console.log('AwardView::'+value);
		    console.log(data);
		    requestFactory('AwardView::'+value, data, ['#html1target']);  
	    }
    });
});

