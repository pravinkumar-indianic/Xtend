$('body').on('click', '.out-wishlist', function() {

    var element = $(this);    
    wishlistToggle(element);

});

$('body').on('click', '.in-wishlist', function() {

    var element = $(this);    
    wishlistToggle(element);

});

function wishlistToggle(element){

    var productID = element.attr('data-id');

    element.addClass('loading');

    var inwishlist = false;

    if(element[0].classList.contains('in-wishlist')){

        inwishlist = true;

    } 

    $('.wishlistdisplay').dimmer('show');

    $.request('Wishlist::onToggleWishlist', {

        data: {
          'productID' : productID,      
        },
        beforeSend: function(){

        },
        success: function(data) {
          //console.log(data.result);
          element.removeClass('loading');     
          element.toggleClass('in-wishlist');             
          element.toggleClass('out-wishlist');

          if(inwishlist == false){       
              element.attr('data-content','Remove Product To Your Wishlist');               
          } else {   
            element.attr('data-content','Add Product To Your Wishlist');               
          }

          $('.wishlisttargetbox').html(data['wishlist']);
          $('.wishlistdisplay').dimmer('hide');
          
        },
        error: function(data){
          throw data;
        },
    });

}