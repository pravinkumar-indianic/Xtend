var pointsname;
var scaledby;
var recievername;
var recieverid;
var senderid;

$('body').on('click', '.transfer-button', function() {

    pointsname = $(this).attr('data-pointsname');
    scaledby = $(this).attr('data-scaledby');
    recievername = $(this).attr('data-receiver-name');
    recieverid = $(this).attr('data-receiver-id');
    senderid = $(this).attr('data-sender-id');

      var body = ' <div class="ui inverted dimmer"><div class="ui text loader">Processing</div></div><div class="ui grid">';
      body +='<div class="ui sixteen wide column"><button class="ui button primary square">Use Existing Points</button><button class="ui button primary square">Create A New Transaction</button></div>';
      body += '<div class="ui sixteen wide column"><p>How Many Points To Transfer</p><div class="ui form"><div class="field"><input type="number" name="transfer-number" id="transfer-number"></div></div></div>';
      body += '<div class="ui sixteen wide column"><p>Display This Transfer In The Activity Feed</p><div class="ui form"><div class="field"><select class="ui dropdown" id="transfer-public"><option value="public">Display</option><option value="private">Private</option></select></div></div></div>';
      body += '<div class="ui sixteen wide column"><p>Please write your message for  '+ recievername +' to accompany this transaction</p><div class="ui form"><div class="field"><textarea rows="2" id="message-text"></textarea></div></div>';
      body += '<div id="errorcatcher"></div>';
      body += '</div>';
	  	$('.ui.modal .header').html('Transfer ' + pointsname + ' to '+ recievername + '.');
	  	$('.ui.modal .content').html(body);
  		$('.ui.modal .actions').html('<button class="ui primary right square button" id="transfer-submit" style="margin-bottom:20px;">Transfer '+ pointsname + '</button>')
	  	$('.ui.modal').modal({
			inverted: true
			}).modal('show');

});

$('body').on('click', '#transfer-submit', function() {

    console.log(recieverid);
    console.log(senderid);                    

    $('.ui.modal .content').dimmer('show');
    var amount = $('#transfer-number').val();
    var message = $('#message-text').val();
    var public = $('#transfer-public').val();

    amount = amount / scaledby;

    $.request('onTransferPoints', {
        data: {

            'recieverid' : recieverid, 
            'senderid' : senderid,
            'amount' : amount,
            'public' : public,
            'messagetext' : message,

        },

        success: function(data){

          $('.ui.modal .content').dimmer('hide');
          $('.ui.modal .header').html('Transfer ' + pointsname + ' to '+ recievername + '.');
          $('.ui.modal .content').html('<p>Succesful Transfer</p>');  
          $('.ui.modal .actions').html('');      

        },
        error: function(data){
          throw data;
        },
    });
  
});