
$(document).ready(function(){

    $('.inbox').click(function() {
        getMessage($(this).data("id"));
    });

});


function getMessage(postId){


    $.request('PostManagement::onGetMessage', 
    {
        data: {

          'postId' : postId,

        },
        success: function(data) 
        {
            placeMessage(data['html']);

        },
        error: function(data)
        {

          throw data;
        },
    });

}

function placeMessage(html){

  $('#inboxdiv').html(html);

}



