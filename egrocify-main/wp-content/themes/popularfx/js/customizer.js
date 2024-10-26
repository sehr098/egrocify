/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a, .site-description' ).css( {
					color: to,
				} );
			}
		} );
	} );

	var custom_preview = {
		'pfx_scrolltop_position' : {'sel' : 'a#pfx-scroll-top', 'css' : "left" , 'val' : "{{val}}%"},
		'pfx_scrolltop_padding' : {'sel' : 'a#pfx-scroll-top', 'css' : "padding" , 'val' : "{{val}}px"},
		'pfx_scrolltop_borderwidth' : {'sel' : 'a#pfx-scroll-top', 'css' : "border" , 'val' : "{{val}}px solid"},
		'pfx_scrolltop_borderradius' : {'sel' : 'a#pfx-scroll-top', 'css' : "border-radius" , 'val' : "{{val}}px"},
		'pfx_scrolltop_bg_color' : {'sel' : 'a#pfx-scroll-top', 'css' : "background-color" , 'val' : "{{val}}"},
		'pfx_scrolltop_color' : {'sel' : "a#pfx-scroll-top span.dashicons.dashicons-arrow-up-alt2", 'css' : "color" , 'val' : "{{val}}"},
		'pfx_scrolltop_iconsize' : {'sel' : "a#pfx-scroll-top span.dashicons.dashicons-arrow-up-alt2", 'css' : ['width', 'height', 'font-size'] , 'val' : "{{val}}px"},
	};
	
	$.each( custom_preview, function (i, value){

		// Append & Apply scrolltop setting
		wp.customize( i, function(values) {
			values.bind( function( to ){
				
				var sel = value.sel;
				var newVal = value.val;
				
				newVal = newVal.split('{{val}}').join(to);
				
				if(typeof value.css == 'object'){
					for( var kk in value.css){
							$( sel ).css( value.css[kk] , newVal );
					}
					return;
				}
				
				$( sel ).css( value.css , newVal );
			});
		});

	});
	
}( jQuery ) );


