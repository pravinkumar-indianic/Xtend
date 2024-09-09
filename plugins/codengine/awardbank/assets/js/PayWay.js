function paywayRun()
{

	publishableKey = $('#payway-credit-card').attr('data-publishablekey');
	
	if (typeof creditCardFrame !== 'undefined' && creditCardFrame !== null) {
		creditCardFrame.destroy();
	}
    creditCardFrame = null;

	$('body').on('click', '#update_billing_details', function() {
		$(this).addClass('oc-loading');
		$(this).addClass('disabled');
		creditCardFrame.getToken( tokenCallback );
	});

	var createdCallback = function( err, frame ) {
      	if ( err ) {
			appendAlert('.pusher.header-margin','negative', err.message);
		} else {
			creditCardFrame = frame;			
		}
	};

	var tokenCallback = function( err, data ) {
	  	if ( err ) {
	    	appendAlert('.pusher.header-margin','negative', err.message);
	  	} else { 
			processSubmitCreate(data);
	  	} 
        creditCardFrame.destroy();
	};

	var styleobject = {
	    'div.payway-account' : 
	    	{ 
	    		'background-color': 'white', 
             	'border-radius': '0em' ,
				'width': '100%',
	        },
	    '.payway-account label' : {  },
	    '.payway-account input' : {  }
	};

	payway.createCreditCardFrame({
	    publishableApiKey: publishableKey,
	    onValid: function() { $('#update_billing_details').removeClass('disabled'); },
	    onInvalid: function() { $('#update_billing_details').addClass('disabled'); },
	    tokenMode: 'callback' },
	    createdCallback 
	);


	function processSubmitCreate(responsedata){
		$('#update_billing_details').removeClass('oc-loading');
		$('#update_billing_details').removeClass('disabled');
		var inputs = $('#billing-details-update :input');
	    var data = {
	    	'responsedata' : responsedata,
	    };		    
	    inputs.each(function() {
	        data[this.name] = $(this).val();
	    });
        requestFactory('onUpdatePaymentMethod', data, ['#html2target']);
	}
}