function openSubList(e){

	var element = $(e);
	var parentLi = element.closest('li');
	var nextsibling = parentLi.next();
	nextsibling.slideToggle();
	element.toggleClass('icon-chevron-circle-down');
	element.toggleClass('icon-chevron-circle-up');

}

$(document).ready(function(){

    $(window).on("load resize scroll",function(e){
        resizeListControlsStick();
    });

    

	$('body').on('click', '#process_all_products', function() {


        startval = $('#process_all_products_first_int').val();   
        endval =  $('#process_all_products_last_int').val();

        if(startval === null || startval <= 0 || startval === ''){

            console.log('here');

            startval = 1;

        } else {

            startval = parseInt(startval);
                     
        }

        if(endval === null || endval <= startval || endval === ''){

            endval = null;

        } else {

            endval = parseInt(endval);
                     
        }

		onRefreshProducts(startval,endval,startval);

	});

	function onRefreshProducts(startval,endval,offset){

		var progressBar = $('#process_all_products_progress_bar');

		var loadingIcon = $('#process_all_products_loading_indicator');

        loadingIcon.find('.current').html(offset);

        if((endval !== null) && offset+49 <= endval){

            loadingIcon.find('.final').html(offset+49);    

        } else if(endval != null)  {

            loadingIcon.find('.final').html(endval);  

        }

		loadingIcon.show();

	    $.request('onRefreshProducts', {

            data: {

                'startval' : startval, 
                'endval' : endval,
                'offset' : offset,
            },

        	success: function(data) {

        		console.log(data);

        		startval = data['startval'];
        		endval = data['endval'];
                offset = data['offset'];

        		if(offset <= endval){

					var percent = (offset-startval) / (endval - startval);
                    progressBar.css("width", (Math.ceil(percent*100))+'%'); 
                    progressBar.find('.number').html(percent);
                    onRefreshProducts(startval,endval, offset);

        		} else {

        			loadingIcon.hide();
                    progressBar.css("width", '100%'); 
                    progressBar.find('.number').html('100');
                    location.reload();

        		}
    		},

            error: function(data){
              throw data;
            },
	    });


	}

    function resizeListControlsStick(){

        var windowHeight = $( window ).height();

        if(windowHeight >= 940){

            $('#sticky-control-list table.table.data tbody').css('height','750px');

        } else if(windowHeight >= 840) {

            $('#sticky-control-list table.table.data tbody').css('height','650px');

        } else if(windowHeight >= 740) {

            $('#sticky-control-list table.table.data tbody').css('height','550px');

        } else if(windowHeight >= 640) {

            $('#sticky-control-list table.table.data tbody').css('height','550px');

        } else { 

            $('#sticky-control-list table.table.data tbody').css('height','450px');

        }
    }

    $(document).on('ajaxSuccess', function(event, context) {


        console.log(context);

        if(context.handler == 'onAddressCreate'){
            runMaps();
        }

    });

});


