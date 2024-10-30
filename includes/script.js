jQuery(document).ready(function() {
    var $container = jQuery('#ba_container');
    // initialize
    $container.imagesLoaded(function() {
        $container.masonry({
            itemSelector: '.auto'
        });
    });
});