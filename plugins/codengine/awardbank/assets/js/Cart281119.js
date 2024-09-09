$('body').on('click', '.add-to-cart-on-product', function () {
    cartAdd($(this),['.product-segment','#cartnavhtmltarget']);
});

$('body').on('click', '.add-to-wishlist-on-product', function () {
    wishlistAdd($(this),['.product-segment','.wishlisttarget','#cartnavhtmltarget']);
});

$('body').on('click', '.plus-product-in-cart', function () {
    $(this).attr('disabled', true)
    cartAdd($(this),['.product-segment','#orderplace-htmltarget','#cartnavhtmltarget','#cart-dropdown']);
});

$('body').on('click', '.minus-product-in-cart', function () {
    $(this).attr('disabled', true)
    cartRemove($(this),['.product-segment','#orderplace-htmltarget','#cartnavhtmltarget','#cart-dropdown']);
});

$('body').on('click', '.minus-product-in-wishlist', function () {
    wishlistRemove($(this),['.product-segment','.wishlisttarget','#cartnavhtmltarget']);
});


function cartAdd(element,loading){
    var productID = element.attr('data-id');
    var page = element.attr('data-page');
    data = {
      'productID' : productID,
      'page' : page,
    }
    requestFactory('Cart::onAddToCart', data, loading);
}

function cartRemove(element,loading,target){
    var productID = element.attr('data-id');
    var page = element.attr('data-page');
    data = {
      'productID' : productID,
      'page' : page,
    }
    requestFactory('Cart::onRemoveFromCart', data, loading);
}

function wishlistAdd(element,loading){
    var productID = element.attr('data-id');
    var page = element.attr('data-page');
    data = {
      'productID' : productID,
      'page' : page,
    }
    requestFactory('Cart::onAddToWishlist', data, loading);
}

function wishlistRemove(element,loading,target){
    var productID = element.attr('data-id');
    var page = element.attr('data-page');
    data = {
      'productID' : productID,
      'page' : page,
    }
    requestFactory('Cart::onRemoveFromWishlist', data, loading);
}
