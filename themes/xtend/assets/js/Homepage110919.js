$(document).ready(function() {

 	var pages = ['page1','page2','page3','page4','page5','page6'];
	$('#fullpage').fullpage({
		fadingEffect: true,
        anchors: pages,
        navigation: true,
        scrollingSpeed: 900,
        navigationPosition: 'right',
        scrollOverflow: true,
        touchSensitivity: 10,
        css3:true
	});

	//methods
	$.fn.fullpage.setAllowScrolling(true);

    $('.section').removeClass('loadstate');
    $('.ui.container').removeClass('loadstate');

    $(".slickable").slick({
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: false,
	  	autoplay: true,
	  	autoplaySpeed: 1500,
	    centerMode: true,
	 	responsive: [
		    {
		      	breakpoint: 769,
      	      	settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1,
	                centerMode: true
		    	}
		    }	    
		    // You can unslick at a given breakpoint now by adding:
		    // settings: "unslick"
		    // instead of a settings object
	  	]
    });


});

$('body').on('click', '#demo-button,#demo-button-bottom', function() {
  	$('.ui.modal').modal({
		inverted: true
	}).modal('show');
});

$('body').on('submit','#contact-form', function(e) {
	e.preventDefault();
	if(validateForm('#contact-form','.dz-hidden-input,.not-required,:hidden,:checkbox') === true){
		var inputs = $('#contact-form :input');
	    var data = {};
	    inputs.each(function() {
	        data[this.name] = $(this).val();
	    });
        requestFactory('LeadCreate::onCreateLead',data,['.ui.modal']);
    }
});

function requestFactory(requestName, data, loaderTarget)
{
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
                $.each(loaderTarget, function(key,value) {
                    showTarget(value)
                });
                if(data.html){
                    $.each(data.html, function(key,value) {
                        $(key).html(value);
                    });
                }
                if(data.manualerror){
                    appendAlert('.modal .content','negative', data.manualerror);
                }
                if(data.updatesucess){
                    appendAlert('.modal .content','success', data.updatesucess);
                }
            }
        },
        error: function(data)
        {
            returncount++;
            $.each( loaderTarget, function( key, value ) {
                showTarget(value);
            });
            appendAlert('.modal .content','negative', data.responseText);
        },
    });	
}

function showTarget(loaderTarget)
{
    $(loaderTarget).find("input, textarea").val("");
    $(loaderTarget).find('.active.inverted.dimmer').remove();
}

function clearTarget(loaderTarget)
{
    var spinner = '<div class="ui active inverted dimmer"><div class="ui loader"></div></div>';
    $(loaderTarget).prepend(spinner);
}

function validateForm(target,exceptions)
{
    targetstring = target+' select,input:not('+exceptions+'),textarea';
    console.log(targetstring);
    inputs = $(targetstring);
    console.log(inputs);
    var string = '';
    $.each(inputs, function( key, value ) {
        if(!$(value).val() || $(value).val() == ''){
            string += value.name+' Field Must Be Filled In To Proceed.<br>';
        }
    });
    if(string){
        appendAlert('.modal .content','negative', string);
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
    html += '<div class="ui container notification"><div class="ui message '+alertclass+'">';
    html += string;
    html += '</div></div>';
    $(target).prepend(html);
    $("html, body").animate({ scrollTop: 0 }, "slow");
}

function clearAlerts()
{
    $('.notification').remove();
}