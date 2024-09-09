$(document).ready(function() {
	var navoption = $('#myprogram').attr('data-navoption');
	if(navoption == 'billingdetails'){
		paywayRun();
	}
});

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

$('body').on('click','.change-tab',function(e) {
	e.preventDefault();
	navoption = $(this).attr('data-id');
    var data = {
    	'navoption' : navoption
    };
    $.when(requestFactory('MyProgram::onUpdateTab', data, ['#html2target']));
});


$('body').on('click','.manage-team',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onManageTeam', data, ['#html2target']);
});

$('body').on('click','.manage-new-user',function(e) {
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onManageNewUser', data, ['#html2target']);
});

$('body').on('click','.manage-user',function(e) {
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onManageUser', data, ['#html2target']);
});

$('body').on('click','.delete-user',function(e) {
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onDeleteUser', data, ['#html2target']);
});

$('body').on('click','.delete-team',function(e) {
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onDeleteTeam', data, ['#html2target']);
});

$('body').on('click','.nomination-approval',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onNominationApproval', data, ['#html2target']);

});

$('body').on('click','.reverse-transfer',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProgram::onReverseTransfer', data, ['#html2target']);

});

$('body').on('click','.transfer-prize',function(e){
    e.preventDefault();
    id = $(this).attr('data-id');
    var data = {
        'id' : id
    };
    requestFactory('MyProgram::onTransferPrize', data, ['#html2target']);
});


$('body').on('click','.filter.submit.button', function(e) {
	e.preventDefault();
	var inputs = $('#filters :input');
    var data = {};
    inputs.each(function() {
        data[this.name] = $(this).val();
    });
    requestFactory('MyProgram::onUpdateTab', data, ['#html2target']);
});

$('body').on('click','.filter.export.button.nominations', function(e) {
	e.preventDefault();
	var inputs = $('#filters :input');
    var data = {};
    inputs.each(function() {
        data[this.name] = $(this).val();
    });
	target = $(this);
	target.addClass('loading');
    $.request('onExportNominations', {
        data: data,
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

$('body').on('click','.filter.export.button.thankyous', function(e) {
	e.preventDefault();
	var inputs = $('#filters :input');
    var data = {};
    inputs.each(function() {
        data[this.name] = $(this).val();
    });
	target = $(this);
	target.addClass('loading');
    $.request('onExportThankyous', {
        data: data,
        success: function(response)
        {
            // The actual download
            var blob = new Blob([response], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'thankyous.csv';
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

$('body').on('click','.filter.export.button.users', function(e) {
    e.preventDefault();
    var inputs = $('#filters :input');
    var data = {};
    inputs.each(function() {
        data[this.name] = $(this).val();
    });
    target = $(this);
    target.addClass('loading');
    $.request('onExportUsers', {
        data: data,
        success: function(response)
        {
            // The actual download
            var blob = new Blob([response], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'users.csv';
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

$('body').on('click','.filter.export.button.userspoints', function(e) {
    e.preventDefault();
    var inputs = $('#filters :input');
    var data = {};
    inputs.each(function() {
        data[this.name] = $(this).val();
    });
    target = $(this);
    target.addClass('loading');
    $.request('onExportUsersPointsLedger', {
        data: data,
        success: function(response)
        {
            // The actual download
            var blob = new Blob([response], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'userspoints.csv';
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

/** FORM SUBMISSIONS **/

forms = {

	'my-program-general-details' : 'onUpdateGeneralDetails',
	'addressform' : 'onUpdateAddress',
	'team-form' : 'onUpdateTeam',
	'program-update-user-form' : 'onUpdateUser',
	'program-create-user-form' : 'onCreateUser',
	'billing-contact-form' : 'onUpdateBillingContact',
	'my-program-transfer-points' : 'onTransferPoints',
	'organization-form' : 'onUpdateOrganization',
	'buy-points' : 'onCreateTransaction',
}

$.each( forms, function( key, value ) {
	$('body').on('submit','#'+key, function(e) {
		e.preventDefault();
		if(validateForm('#'+key,'.dz-hidden-input,.not-required,:hidden,:checkbox') === true){
			var inputs = $('#'+key+' :input');
		    var data = {};
		    if(key == 'program-update-user-form' || key == 'program-create-user-form'){
		    	data['Teams'] = $('#Teams').dropdown('get value');
		    }
		    if(key == 'team-form'){
		    	data['Children'] = $('#Children').dropdown('get value');
		    }
		    inputs.each(function() {
		        data[this.name] = $(this).val();
		    });
	        requestFactory('MyProgram::'+value, data, ['#html2target']);
	    }
	});
});

$('body').on('click', '.send_activation', function() {
	var id = $(this).attr('data-id');
	var button = $(this);
	button.addClass('oc-loading');	
	button.text('Sending');
	$.request('onSingleActivateUser', {
    	data: {
    		'id' : id,
    	},
        success: function(data)
        {
        	button.text('Sent');
        	button.removeClass('oc-loading');
        	button.removeClass('send_activation');
        },
        error: function(data){
          throw data;
        },
    });
});

$('body').on('click', '#exportnominations', function() {


});
