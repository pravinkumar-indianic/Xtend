
$(document).ready(function(){
    getTotalInbox();
});

function getTotalInbox(){

    /**
    $.request('Cart::onGetTotalInbox', 
    {

        success: function(data) 
        {
            //console.log('works');
            //console.log(data);
            if(data['count'] == null){

                placeInboxNav('0');

            } else {

                placeInboxNav(data['count']);

            }

        },
        error: function(data)
        {

            //console.log(data.statusText);
        },
    });

    **/

}

function placeInboxNav(count){

  $('.mailbox-count').html(count);

}



