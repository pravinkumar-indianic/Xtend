$(document).ready(function(){

});

$('body').on('click', '.email-popup', function() {
		/* Get the text field */
		var value = $(this).attr('data-email');

			var $temp = $("<input>");
		  $("body").append($temp);
			$temp.val(value).select();
		  document.execCommand("copy");
		  $temp.remove();
	  	$('.ui.modal .header').html('Copyied To Clipboard');
	  	$('.ui.modal .content').html('<p>The e-mail '+ value +' has been copied to your clipboard</p>');
	  	$('.ui.modal').modal({
			inverted: true
			}).modal('show');
});

$('body').on('click', '.message-button', function() {

	var receivername = $(this).attr('data-receiver-name');
	var receiverid = $(this).attr('data-receiver-id');
	var senderid = $(this).attr('data-sender-id');	

  	$('.ui.modal .header').html('<i class="envelope icon"></i> Message '+ receivername);
  	$('.ui.modal .content').html('<p>Please write your message for  '+ receivername +' below</p><div class="ui form"><div class="field"><textarea rows="2" id="message-text"></textarea></div></div><div id="errorcatcher"></div>');
  	$('.ui.modal .actions').html('<button class="ui primary right square small labeled icon button" id="message-submit" data-receiver-id="'+ receiverid +'" data-sender-id="'+ senderid +'">Send Message<i class="envelope icon"></i></button>')
  	$('.ui.modal').modal({
		inverted: true
		}).modal('show');

});

$('body').on('click', '#message-submit', function() {    
	$(this).prop('disabled', true);
	$(this).addClass('oc-loading');
	var messagetext = $('#message-text').val();
	var receiverid = $(this).attr('data-receiver-id');
	var senderid = $(this).attr('data-sender-id');

	if(messagetext === null || messagetext === ''){

		$(this).removeClass('oc-loading');
		$('.ui.modal .content #errorcatcher').html('<p>You must enter a message!</p>');

	} else if(messagetext.length >= 280) {

		$('.ui.modal .content #errorcatcher').html('<p>Love the enthusiasm, but keep it brief. Twitter rules of 280 Character apply!</p>');	

	} else {

		$('.ui.modal .content #errorcatcher').html();

	    $.request('onCreateMessage', 
	    {
	        data: {

	          'receiverid' : receiverid,
	          'senderid' : senderid,
	          'messagetext' : messagetext,

	        },
	        success: function(data) 
	        {
        		$(this).removeClass('oc-loading');
        		$('.ui.modal').modal('hide');
        		$('.ui.modal .header').html('');
        		$('.ui.modal .content').html('');
        		$('.ui.modal .actions').html('');
	        },
	        error: function(data)
	        {

	        	console.log(data);
        		$(this).removeClass('oc-loading');
	          	$('.ui.modal .content #errorcatcher').html(data.responseText);

	        },
	    });

	}

});   