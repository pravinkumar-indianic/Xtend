$('body').on('click','.postlikebutton',function(e) {
	e.preventDefault();
    var data = {
    };
    requestFactory('PostView::onUpdateLike', data, ['#html1target']);
});

$('body').on('submit','#createCommentForm', function(e) {
	e.preventDefault();
	if(validateForm('#createCommentForm','.dz-hidden-input,.not-required,:hidden,:checkbox') === true){
		var inputs = $('#createCommentForm :input');
	    var data = {};
	    inputs.each(function() {
	        data[this.name] = $(this).val();
	    });
        requestFactory('PostView::onCreateComment', data, ['#html1target']);
    }
});