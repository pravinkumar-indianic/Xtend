$(document).ready(function(){

});

$('body').on('click', '.nominate-button', function() {

	var element = $(this);
	$(this).addClass('oc-loading');

  	$.request('onGetAvailableAwards', 
	    {
	        success: function(data) 
	        {
	        	console.log(element);
	        	nominateModal(element,data);
	        },

    	}
	);

});

function nominateModal(element, data){

	var form = '<select id="awardid" class="ui dropdown" style="width:100%;min-height:30px;">';
	$.each( data, function( key, value ) {
		form += '<option value="'+value.id+'">'+value.name+'</option>';

	});
	form += '</select>';

	var receivername = element.attr('data-receiver-name');
	var receiverid = element.attr('data-receiver-id');
	var senderid = element.attr('data-sender-id');	



  	$('.ui.modal .header').html('<i class="trophy icon"></i> Nominate '+ receivername);
  	$('.ui.modal .content').html('<p>Please Select The Award You Would Like To Nominate This User For:</p><div>'+form+'</div>');
  	$('.ui.modal .actions').html('<button class="ui primary right square small labeled icon button" id="nomination-submit" data-receiver-id="'+ receiverid +'" data-sender-id="'+ senderid +'">Make Nomination <i class="trophy icon"></i></button>')
  	$('.ui.modal').modal({
		inverted: true
		}).modal('show');

}

$('body').on('click', '#nomination-submit', function() {    
	
	$(this).addClass('oc-loading');
	var awardid = $('#awardid').val();
	var receiverid = $(this).attr('data-receiver-id');
	var senderid = $(this).attr('data-sender-id');

	$('.ui.modal .content #errorcatcher').html();

    $.request('onCreateNomination', 
    {
        data: {

          'receiverid' : receiverid,
          'senderid' : senderid,
          'awardid' : awardid,

        },
        success: function(data) 
        {
        	$('.nominate-button').removeClass('oc-loading');
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


});   