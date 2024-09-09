var requestcount = 0;
var returncount = 0;

$(document).ready(function() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
        $("#wrapper.toggled").find("#sidebar-wrapper").find(".collapse").collapse("hide");
    });
    semanticElementsLoad();
    magicReview();
});

function magicReview()
{
    // Hubee JS plugin

    if ($('.color-input').length) {
        var hueb = new Huebee( '.color-input', {
          // options
          notation: 'hex',
          saturations: 2,
        });
    };

    if ($('#image-upload').length) {
        $.uploadPreview({
            input_field: "#image-upload",   // Default: .image-upload
            preview_box: "#image-preview",  // Default: .image-preview
            label_field: "#image-label",    // Default: .image-label
            label_default: "Browse",   // Default: Choose File
            label_selected: "Change",  // Default: Change File
            no_label: false                 // Default: false
        });
    };

    $('.inline.icon')
      .popup({
        inline: true
      })
    ;


    // Modal popup for Product detail page
    $('body').on('click', '.product-gallery-trigger', function() {
        var popupTarget = $(this).attr('href');
        $(popupTarget).modal('show');
    });

    // Overflow on productdetail description
    var accordionHeight;
    var accordionContent;

    accordionContent = $('.accordion .content.active');
    accordionHeight = $(accordionContent).height();
    if (accordionHeight > 100) {
        $(accordionContent).addClass('overflow');
    }

    $('body').on('click', '.accordion .content.overflow', function() {
        $(this).toggleClass('overflow');
    });


    // Dash Wishlist Cat Categories on mobile
    if ($('.dash-carousel').length) {
        $(".dash-carousel").slick({
            dots: false,
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
        });
    }


    // Product Cat Categories on mobile
    /* Duplicated in plugins/codengine/awardbank/assests/js/product-list-cover-flow.js

    if ($('.categories-slider').length) {

        $(".product-coverflow").slick({
            dots: true,
            infinite: true,
            centerMode: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            centerPadding: '0'
        });
    }*/




    // Product Categories on mobile
    if ($('.categories-slider').length) {

          $slick_slider = $('.categories-slider');

          settings = {
            dots: true,
            infinite: true,
            centerMode: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            centerPadding: '0',
            mobileFirst: true,
            arrows: false,
          }


          // reslick only if it's not slick()
          $(window).on('load', function() {
            if ($(window).width() < 768) {

              return $slick_slider.slick(settings);

            }
          });


          $(window).on('resize', function() {
            if ($(window).width() > 767) {
              if ($slick_slider.hasClass('slick-initialized')) {
                $slick_slider.slick('unslick');
              }
              return
            }

            if (!$slick_slider.hasClass('slick-initialized')) {
              return $slick_slider.slick(settings);
            }
          });

    }

    // Main menu drop down for mobile
    $('.header-top-inner').dropdown();
    $('.mobile-search').dropdown();


    // Dashboard element heights
    if ($('.dash-points').length) {

        $(window).on('load resize', function(){
            //var dashPointHeight = $('.dash-points').outerHeight();

            var outerheight = $('.dash-awards').outerHeight();
            var awardheight = $('#awards-outer').outerHeight();
            //console.log(outerheight);
            //console.log(awardheight);
            var dashPointHeight = outerheight-awardheight-29;
            //console.log(dashPointHeight);
            $('.dash-post > .segment').css({'height': 'calc(100% + '+ dashPointHeight +'px)', 'margin-top': '-' + dashPointHeight + 'px'});
        });
    }

    // Pusher

    // using context
    $('.site-container .pushable .ui.sidebar')
      .sidebar({
        context: $('.site-container .pushable')
      })
      .sidebar('attach events', '.site-container .menu-trigger');
}

function semanticElementsLoad()
{

    $(".ui.accordion").accordion();//accordion trigger
    $(".ui.rating").rating();//rating trigger
    $(".ui.dropdown:not(.top-cart)").dropdown();//dropdown and select trigger

    $('.top-cart').dropdown({
        on: 'hover',
        action: 'nothing',
    });

    //$("body").niceScroll({ cursorcolor: "#ddd", cursorwidth: 5, cursorborderradius: 0, cursorborder: 0, scrollspeed: 50, autohidemode: true, zindex: 9999999 });//body scroll tigger by nicescroll

    $(".hamburger").on("click", function () {
        $('.ui.sidebar')
            .sidebar('setting', 'transition', 'push')
            .sidebar('toggle')
        ;
    });

    /**

        Matthew Additions
        UI Sidebar Close

    **/

    var dropdowns = $(".multiple.selection.dropdown");
    $.each(dropdowns, function( key, value ) {
        var selected = $(value).attr('data-selected').split(',');;
        var id = $(value).attr('id');
        $.each(selected, function( key2, value2 ) {
            $('#'+id).dropdown(
                'set selected', value2,
            );
        });
    });

    $('.ui.sidebar .window.close.outline.icon.red').on("click", function () {
        $('.ui.sidebar').sidebar('toggle');
    });

    $('.ui.checkbox').checkbox();

    $('.notification .close').on("click", function () {
        clearAlerts();
    });
}

function requestFactory(requestName, data, loaderTarget, successcallback = null)
{
    requestcount++;
    $.each(loaderTarget, function( key, value ) {
        clearTarget(value)
    });
    clearAlerts();
    $.request(requestName,
    {
        data: data,
        success: function(data)
        {
            if(data.X_OCTOBER_REDIRECT){
                window.location.replace(data.X_OCTOBER_REDIRECT);
            } else {
                returncount++;
                $.each(loaderTarget, function(key,value) {
                    showTarget(value)
                });
                if(data.html){
                    $.each(data.html, function(key,value) {
                        $(key).html(value);
                    });
                    semanticElementsLoad();
                }
                if(data.append){
                    $.each(data.append, function(key, value) {
                        $(key).append(value);
                    });
                    semanticElementsLoad();
                }
                if(data.manualerror){
                    appendAlert('.pusher.header-margin','negative', data.manualerror);
                }
                if(data.updatesucess){
                    appendAlert('.pusher.header-margin','success', data.updatesucess);
                }
                if(data.paywayrun){
                    paywayRun();
                }
                if(data.fileuploaderrun){
                    $('.responsiv-uploader-fileupload').fileUploader();
                }
                if(successcallback){
                    successcallback();
                }
            }
        },
        error: function(data)
        {
            returncount++;
            $.each( loaderTarget, function( key, value ) {
                showTarget(value);
            });
            appendAlert('.pusher.header-margin','negative', data.responseText);
        },
    });
}

function showTarget(loaderTarget)
{
    if(requestcount === returncount){
        //$(loaderTarget).find("input, textarea").val("");
        $(loaderTarget).find('.active.inverted.dimmer').remove();
        requestcount = 0;
        returncount = 0;
    }
}

function clearTarget(loaderTarget)
{
    if(requestcount === 1){
        var spinner = '<div class="ui active inverted dimmer"><div class="ui loader"></div></div>';
        $(loaderTarget).prepend(spinner);
        //$(loaderTarget).hide();
    }
}

function validateForm(target,exceptions,extraInputs='')
{
    extraInputs = extraInputs.length > 0 ? extraInputs + ', ' : extraInputs;
    targetstring = target + ' select, ' + extraInputs + 'input:not('+exceptions+')';
    //console.log('targetstring', targetstring);
    inputs = $(targetstring);
    //console.log(inputs);
    var string = '';
    $.each(inputs, function( key, value ) {
        if(!$(value).val() || $(value).val() == ''){
            string += value.name+' Field Must Be Filled In To Proceed.<br>';
        }
    });
    if(string){
        appendAlert('.pusher.header-margin','negative', string);
        return false;
    } else {
        clearAlerts();
        return true;
    }
}

function appendAlert(target,alertclass,string)
{
    clearAlerts();
    var html = '';
    html += '<div class="ui container notification"><div class="ui message '+alertclass+'"><i class="close icon"></i>';
    html += string;
    html += '</div></div>';
    $(target).prepend(html);
    semanticElementsLoad();
    //$("html, body").animate({ scrollTop: 0 }, "slow");
}

function clearAlerts()
{
    $('.notification').remove();
}
