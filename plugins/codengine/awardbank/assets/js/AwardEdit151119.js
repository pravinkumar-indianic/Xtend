forms = {
	'award-general-details-create' : 'onUpdateGeneralDetails',
	'award-general-details-update' : 'onUpdateGeneralDetails',
	'prize-form-create' : 'onUpdatePrize',
	'prize-form-update' : 'onUpdatePrize',
	'prize-form-winner-update' : 'onUpdatePrize',
	'nomination-questions' : 'onUpdateNominationQuestions',
	'vote-questions' : 'onUpdateVoteQuestions',
}

$.each( forms, function( key, value ) {
	$('body').on('submit','#'+key, function(e) {
		e.preventDefault();
		if(validateForm('#'+key,'.dz-hidden-input,.not-required,:hidden,:checkbox') === true){
			var inputs = $('#'+key+' :input');
		    var data = {};
		    if(key == 'award-general-details-update'){
		    	data['Managers'] = $('#Managers').dropdown('get value');
		    	data['NominationManagers'] = $('#NominationManagers').dropdown('get value');
		    	data['WinnerManagers'] = $('#WinnerManagers').dropdown('get value');
		    	data['NominatableUsers'] = $('#NominatableUsers').dropdown('get value');
		    	data['NominatableTeams'] = $('#NominatableTeams').dropdown('get value');
		    	data['NominationTeams'] = $('#NominationTeams').dropdown('get value');
		    	data['VoteableTeams'] = $('#VoteableTeams').dropdown('get value');
		    	data['VotingTeams'] = $('#VotingTeams').dropdown('get value');
		    }
		    if(key == 'prize-form-winner-update'){
		    	data['UserWinners'] = $('#UserWinners').dropdown('get value');
		    	data['TeamWinners'] = $('#TeamWinners').dropdown('get value');		    	
		    }
		    inputs.each(function() {
		        data[this.name] = $(this).val();
		    });
	        requestFactory('AwardEdit::'+value, data, ['#html2target']);
	    }
	});
});

$('body').on('click','.change-tab',function(e) {
	e.preventDefault();
	navoption = $(this).attr('data-id');
    var data = {
    	'navoption' : navoption
    };
    $.when(requestFactory('AwardEdit::onUpdateTab', data, ['#html2target']));
});

$('body').on('click','.manage-prize',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('AwardEdit::onManagePrize', data, ['#html2target']);
});

$('body').on('click','.delete-prize',function(e){
	console.log('click');
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('AwardEdit::onDeletePrize', data, ['#html2target']);
});

$('body').on('click','.won-prize',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('AwardEdit::onShowWinners', data, ['#html2target']);
});

$('body').on('click','.add-question',function(e){
	e.preventDefault();
	$('#noquestions').remove();
	nextloop = $(this).data('nextloop');
	$('.add-question').data('nextloop',nextloop+1);
    var data = {
    	nextloop : nextloop,
    };
    requestFactory('AwardEdit::onAppendQuestion', data, ['#html2target']);
});

$('body').on('click','.delete-question',function(e){
	e.preventDefault();
	loop = $(this).data('bigloop');
	$('.question-set.'+loop).remove();
});

$('body').on('click','.add-option',function(e){
	e.preventDefault();
	bigloop = $(this).data('bigloop');
	nextloop = $(this).data('nextloop');
	$('.add-option.'+bigloop).data('nextloop',nextloop+1);
    var data = {
    	'bigloop' : bigloop,
    	'nextloop' : nextloop,
    };
    requestFactory('AwardEdit::onAppendOption', data, ['#html2target']);
});

$('body').on('click','.delete-option',function(e){
	e.preventDefault();
	bigloop = $(this).attr('data-bigloop');
	smallloop = $(this).attr('data-smallloop');
	$('.optionset.'+bigloop+'.'+smallloop).remove();
});

$('body').on('click','.nomination-approval',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('AwardEdit::onNominationApproval', data, ['#html2target']);

});

$('body').on('click', '#exportnominations', function() {

	target = $(this);
	target.addClass('loading');
    $.request('onExportNominations', {
        success: function(response)
        {
            // The actual download
            var blob = new Blob([response], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'nominations.csv';

            document.body.appendChild(link);

            link.click();

            document.body.removeChild(link);
            target.removeClass('loading');
        },

        error: function(data){
          throw data;
        },
    });
});