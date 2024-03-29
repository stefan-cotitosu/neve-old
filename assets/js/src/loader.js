/**
 * Handles the order for all the scripts.
 */
jQuery( document ).ready( function () {
	jQuery.neveNavigation.init();
} );
jQuery( window ).load( function () {
	jQuery.neveBlog.init();
} );

/**
 * Do resize events timer based so they don't run continuously.
 */
var resizeTimeout;
jQuery( window ).on( 'resize', function () {
	clearTimeout( resizeTimeout );
	resizeTimeout = setTimeout( function () {
		jQuery.neveNavigation.repositionDropdowns();
	}, 500 );
} );