//Handles which tabs are open and opens the relative containers
jQuery( document ).ready(function(){

jQuery( ".tabpage_latest" ).each(function( index ) {
  jQuery(this).height(jQuery(this).prev().height());
  jQuery(this).css( "display", "none" );
});

jQuery( ".mytabs" ).on( "click", function() {

jQuery( "#tabpage_" + jQuery(this).attr('id')).siblings().css( "display", "none" );
jQuery( "#tabpage_" + jQuery(this).attr('id')).css( "display", "" );
});

});
