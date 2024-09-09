$(document).ready(function() {
	var navoption = $('#myprofile').attr('data-navoption');
	if(navoption == 'billingdetails'){
		paywayRun();
	}
});

$('body').on('click','.change-tab',function(e) {
	e.preventDefault();
	navoption = $(this).attr('data-id');
    var data = {
    	'navoption' : navoption
    };
    requestFactory('MyProfile::onUpdateTab', data, ['#html2target']);
});


$('body').on('click','.manage-team',function(e){
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProfile::onManageTeam', data, ['#html2target']);
});

$('body').on('click','.thankyou-all-read-button',function(e) {
	e.preventDefault();
    var data = {

    };
    requestFactory('MyProfile::onAllThankyouRead', data, ['#html2target']);
});

$('body').on('click','.message-all-read-button',function(e) {
	e.preventDefault();
    var data = {

    };
    requestFactory('MyProfile::onAllMessageRead', data, ['#html2target']);
});

$('body').on('click','.thankyou-read-button',function(e) {
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProfile::onThankyouRead', data, ['#html2target']);
});

$('body').on('click','.message-read-button',function(e) {
	e.preventDefault();
	id = $(this).attr('data-id');
    var data = {
    	'id' : id
    };
    requestFactory('MyProfile::onMessageRead', data, ['#html2target']);
});

/** FORM SUBMISSIONS **/

forms = {
	'my-profile-general-details' : 'onUpdateGeneralDetails',
	'shippingaddressform' : 'onUpdateShippingAddress',
	'homeaddressform' : 'onUpdateHomeAddress',
	'team-form' : 'onUpdateTeam'
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
	        requestFactory('MyProfile::'+value, data, ['#html2target']);
	    }
	});
});