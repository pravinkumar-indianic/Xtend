 $('body').on('click', '.tabular.menu .item.active', function() {
    $('.category-filter-tab-content').css('height', 0 );
    $(this).removeClass('active');
    $(this).parent('.category-filter-mobile').removeClass('active');
});

// Category Filter Dropdown.
$('body').on('click', '.tabular.menu .item:not(.active):not(.disabled) ', function() {

    var tabHeight = $('.category-filter-tab-content').height();
    $('.category-filter-tab-content').css('height', (tabHeight + 30) );

    var tabThis = $(this);
    var tab = $(tabThis).data('tab');
    var tabElement = $('.tab[data-tab=' + tab + ']');

    // Set timer to give enough time to reset tab height for animation to occur
    setTimeout(function(){ 
        $('.tabular a, .tab').removeClass('active');
        $(tabThis).add(tabElement).addClass('active');
        $(tabThis).parent('.category-filter-mobile').addClass('active');

        // set a height so that we get an animation
        tabHeight = $(tabElement).height();
        $('.category-filter-tab-content').css('height', (tabHeight + 30) );
    }, 10);

});

// Category filter expander 

$('body').on('click', 'li > .plus.icon', function() {
  	$(this).parent().siblings().find('ul').slideUp();
  	$(this).parent().find('ul').slideToggle();

  	// reset the height of the surrounding div so it won't cut off content 
  	$('.category-filter-tab-content').css('height', 'auto' );
});