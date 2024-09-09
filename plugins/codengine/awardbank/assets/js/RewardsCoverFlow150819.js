$(document).ready(function(){
    if($('.product-coverflow').length) {
        if ($('.product-coverflow').html().length > 0) {
            $(".product-coverflow").slick({
                dots: true,
                infinite: true,
                centerMode: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: false,
                centerPadding: '0'
            });
        }
    }
});
