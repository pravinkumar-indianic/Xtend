$('body').on('click', '.award_offset', function() {
	$('.award_offset').removeClass('active');
	var button = $(this);
	var offset = button.attr('data-offset');
	$.request('onUpdateTable', {
    	data: {
    		'offset' : offset,
    	},
        success: function(data)
        {
        	button.addClass('active');
        	$('.award-table tbody').html(data['html']);
        },
        error: function(data){
          throw data;
        },
    });
});