var pagerequests = 0;

var searchOptions = {
	'sortBy': null,
	'lowPrice':  null,
	'highPrice': null,
	'searchTerm': null,
	'redeemFilter': null,
	'refresh' : false,
	'offset' : null,
	'totalResults' : null,
};

$(document).ready(function() {
	$('#price-range').slider({
       range:true,
       min: parseInt($('#price-range').attr('data-minprice')),
       max: parseInt($('#price-range').attr('data-maxprice')),
       values: [ $('#price-range').attr('data-minprice'), $('#price-range').attr('data-maxprice') ],
       slide: function( event, ui ) {
			$('.range-slider-labels .low-price').text(ui.values[ 0 ] );
       		$('.range-slider-labels .high-price').text(ui.values[ 1 ] );
          	searchOptions.lowPrice =  ui.values[ 0 ];
          	searchOptions.highPrice =  ui.values[ 1 ];
       }
    });

    $( "#price-range" ).mouseup(function(ui){
		searchOptions.refresh = true;
    	callAjaxList()
    });

	$('.range-slider-labels .low-price').text($( "#price-range" ).slider( "values", 0 ) );
	$('.range-slider-labels .high-price').text($( "#price-range" ).slider( "values", 1 ));
	searchOptions.offset = parseInt($('#listWrapper').attr('data-offset'));
	searchOptions.totalResults = parseInt($('#listWrapper').attr('data-totalResults'));

});	

$('body').on('change', '.sortby', function() {
	searchOptions.sortBy = $(this).find(":selected").val();
	searchOptions.refresh = true;
	callAjaxList();
});

$('body').on('change', '.subcategory', function() {

	searchOptions.subCategory = $(this).dropdown("get value");
	searchOptions.refresh = true;
  	callAjaxList()

});

$('body').on('keypress', '.ui.search .search', function (e) {
    if (e.which === 13) {
        searchOptions.searchTerm = $(this).val();
        searchOptions.refresh = true;			
		callAjaxList()
        return false;
    }
});

$('body').on('change', '.redeem-slider', function() {
	$('.pricerange').toggleClass('disabled');
	//console.log($(this).is(':checked'));
	if($(this).is(':checked') === true){
		searchOptions.redeemFilter = true;
		searchOptions.refresh = true;
	} else {	
		searchOptions.redeemFilter = false;	
		searchOptions.lowPrice = $( "#price-range" ).slider( "values", 0 );
		searchOptions.highPrice = $( "#price-range" ).slider( "values", 1 );
		searchOptions.refresh = true;
	}
	callAjaxList()
	return false; 
});

function callAjaxList(){
	if (searchOptions.refresh === false){
		searchOptions.offset = searchOptions.offset + 12;
	} else {
		searchOptions.offset = 0;
	}
	if(searchOptions.offset <= searchOptions.totalResults){
    	requestFactory('RewardsList::onRefreshListFilter', searchOptions,['#listhtml']);
	}
}

$('#listhtml').visibility({
    once: false,
    observeChanges: true,
    onBottomVisible: function() {
    	if(searchOptions.refresh == true){
    		updateNewTotal();
    		searchOptions.refresh = false;
    	}
		callAjaxList();
  	},
});

function updateNewTotal()
{
	searchOptions.totalResults = parseInt($('#listWrapper').attr('data-totalResults'));
}