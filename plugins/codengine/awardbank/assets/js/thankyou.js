$(document).ready(function(){

	$('.thankyou-count-button').popup({exclusive:true})
;
});

$('body').on('click', '.thankyou-button', function() {

	var receivername = $(this).attr('data-receiver-name');
	var receiverid = $(this).attr('data-receiver-id');
	var senderid = $(this).attr('data-sender-id');	

  	$('.ui.modal .header').html('<i class="star icon"></i> Thank '+ receivername);
  	$('.ui.modal .content').html('<p>Please give a brief description of why you are thanking '+ receivername +'</p><div class="ui form"><div class="field"><textarea rows="2" id="thankyou-text"></textarea></div></div><div id="errorcatcher"></div>');
  	$('.ui.modal .actions').html('<button class="ui primary right square small labeled icon button" id="thankyou-submit" data-receiver-id="'+ receiverid +'" data-sender-id="'+ senderid +'">Send Thanks<i class="star icon"></i></button>')
  	$('.ui.modal').modal({
		inverted: true
		}).modal('show');

});

$('body').on('click', '#thankyou-submit', function() {    
	
	$(this).addClass('oc-loading');
	$('#thankyou-count-button_'+receiverid).addClass('oc-loading');
	var thankyoutext = $('#thankyou-text').val();
	var receiverid = $(this).attr('data-receiver-id');
	var senderid = $(this).attr('data-sender-id');

	if(thankyoutext === null || thankyoutext === ''){

		$(this).removeClass('oc-loading');
		$('.ui.modal .content #errorcatcher').html('<p>You must enter a description for your thankyou!</p>');

	} else if(thankyoutext.length >= 280) {

		$('.ui.modal .content #errorcatcher').html('<p>Love the enthusiasm, but keep it brief. Twitter rules of 280 Character apply!</p>');	

	} else {

		$('.ui.modal .content #errorcatcher').html();

	    $.request('onCreateThankyou', 
	    {
	        data: {

	          'receiverid' : receiverid,
	          'senderid' : senderid,
	          'thankyoutext' : thankyoutext,

	        },
	        success: function(data) 
	        {
        		$(this).removeClass('oc-loading');
				$('#thankyou-count-button_'+receiverid).removeClass('oc-loading');
        		$('.ui.modal').modal('hide');
        		$('.ui.modal .header').html('');
        		$('.ui.modal .content').html('');
        		$('.ui.modal .actions').html('');
        		$('#thankyou-count-button_'+receiverid).html('<i class="star icon"></i> '+data['count'])
        		$('#thankyou-count-button_'+receiverid).attr('data-content', data['receivername']+" has been thanked "+ data['count'] +" time(s)");
    			$('#thankyou-count-button_'+receiverid).popup('destroy');
    			$('#thankyou-count-button_'+receiverid).popup({exclusive:true});
	        },
	        error: function(data)
	        {

	        	console.log(data);
        		$(this).removeClass('oc-loading');
				$('#thankyou-count-button_'+receiverid).removeClass('oc-loading');
	          	$('.ui.modal .content #errorcatcher').html(data.responseText);

	        },
	    });

	}

});   