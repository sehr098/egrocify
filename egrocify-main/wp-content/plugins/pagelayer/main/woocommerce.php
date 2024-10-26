<?php

//////////////////////////////////////////////////////////////
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

// Are we being accessed directly ?
if(!defined('PAGELAYER_VERSION')) {
	exit('Hacking Attempt !');
}

add_action( 'wp', 'pagelayer_wc_customization' );
function pagelayer_wc_customization(){
	
	$options = pagelayer_get_customize_options();
	
	if(!empty($options['woo_disable_cross_sells'])){
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}
	
	if(is_product()){
		// Disable Breadcrumb.
		if(!empty($options['woo_disable_breadcrumb'])){
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		}
	}

	// Checkout customization
	if(!is_checkout()){
		return;
	}
		
	// Disable order notes.
	if(!empty( $options['woo_disable_order_note'] )){
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
	}
	
	// Disable coupon.
	if(!empty( $options['woo_disable_coupon_field'] )){
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	}
}

add_filter('wp_nav_menu_items', 'pagelayer_add_woo_cart', 10, 2);
add_filter('wp_page_menu', 'pagelayer_add_woo_cart', 10, 2);
function pagelayer_add_woo_cart($items, $args) {
		
	$menu_id = $args->menu->term_id;
	$locations = get_nav_menu_locations();
	
	//pagelayer_print($locations);
	if(empty($locations) || !isset($locations['primary']) || $locations['primary'] != $menu_id) {
		return $items;
	}
	
	$options = pagelayer_get_customize_options();
	
	if(!empty($options['woo_enable_menu_cart'])) {
		$items .= '<li class="page-item pagelayer-menu-cart cart-customlocation menu-item menu-item-type-post_type menu-item-object-page"><a href=""><span class="dashicons dashicons-cart"></span><sup></sup></a></li>';
	}
	
	return $items;
}

add_action( 'customize_controls_print_scripts', 'pagelayer_wc_add_scripts'  );
function pagelayer_wc_add_scripts(){
?>
<script>

// Script to load Shop page when user click woocommerce customizer
jQuery( function( $ ) {
	wp.customize.panel( 'pgl_woocommerce', function( panel ) {
		panel.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( "<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>" );
			}
		} );
	} );
	wp.customize.section( 'pgl_woo_cart_page', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if( isExpanded ){
				wp.customize.previewer.previewUrl.set( "<?php echo esc_js( wc_get_page_permalink( 'cart' ) ); ?>" );
			}
		} );
	} );
	wp.customize.section( 'pgl_woo_checkout', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( "<?php echo esc_js( wc_get_page_permalink( 'checkout' ) ); ?>" );
			}
		} );
	} );
	wp.customize.section( 'pgl_woo_myaccount_page', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( "<?php echo esc_js( wc_get_page_permalink( 'myaccount' ) ); ?>" );
			}
		} );
	} );
	wp.customize.section( 'pgl_woo_product_catalog', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( "<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>" );
			}
		} );
	} );
	wp.customize.section( 'pgl_woo_general', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( "<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>" );
			}
		} );
	} );
});
</script>
<?php
}
function pagelayer_parse_customize_styles($val, $rule, $unit = 'px'){
	
	$parse = str_replace(array('{{val}}', '{{color}}', '{{unit}}'), array($val, pagelayer_sanitize_global_color($val), $unit), $rule);
	$parse = rtrim( trim($parse), ';' );
	
	return $parse;
}

add_action( 'wp_head', 'pagelayer_woocommerce_styles', 1000 );
function pagelayer_woocommerce_styles(){
	global $pagelayer;
	
	// Get the option defaults
	$options = pagelayer_get_customize_options();
	$modes = array('desktop', 'tablet', 'mobile');
	$css = array();
	
	$woo_styles = array(
		'woo_notice_bg_color' => array(
			'.woocommerce-store-notice.demo_store' => 'background-color: {{color}}',
		),
		'woo_notice_color' => array(
			'.woocommerce-store-notice.demo_store' => 'color: {{color}}',
		),
		'woo_notice_a_color' => array(
			'.woocommerce-store-notice.demo_store a' => 'color: {{color}}',
		),
		'woo_notice_a_hover_color' => array(
			'.woocommerce-store-notice.demo_store a:hover' => 'color: {{color}}',
		),
		'woo_myaccount_padding' => array(
			'body.woocommerce-account main.site-main' => array(
				'top' => 'padding-top: {{val}}{{unit}};',
				'right' => 'padding-right: {{val}}{{unit}};',
				'bottom' => 'padding-bottom: {{val}}{{unit}};',
				'left' => 'padding-left: {{val}}{{unit}};'
			)
		),
		'woo_checkout_padding' => array(
			'body.woocommerce-checkout main.site-main' => array(
				'top' => 'padding-top: {{val}}{{unit}};',
				'right' => 'padding-right: {{val}}{{unit}};',
				'bottom' => 'padding-bottom: {{val}}{{unit}};',
				'left' => 'padding-left: {{val}}{{unit}};'
			)
		),
		'woo_cart_padding' => array(
			'body.woocommerce-cart main.site-main' => array(
				'top' => 'padding-top: {{val}}{{unit}};',
				'right' => 'padding-right: {{val}}{{unit}};',
				'bottom' => 'padding-bottom: {{val}}{{unit}};',
				'left' => 'padding-left: {{val}}{{unit}};'
			)
		),
		'woo_product_padding' => array(
			'body.single-product main.site-main' => array(
				'top' => 'padding-top: {{val}}{{unit}};',
				'right' => 'padding-right: {{val}}{{unit}};',
				'bottom' => 'padding-bottom: {{val}}{{unit}};',
				'left' => 'padding-left: {{val}}{{unit}};'
			)
		),
		'woo_product_cat_padding' => array(
			'body.post-type-archive-product .site-main' => array(
				'top' => 'padding-top: {{val}}{{unit}};',
				'right' => 'padding-right: {{val}}{{unit}};',
				'bottom' => 'padding-bottom: {{val}}{{unit}};',
				'left' => 'padding-left: {{val}}{{unit}};'
			)
		),
		'woo_menu_cart_color' => array(
			'li.cart-customlocation span.dashicons-cart' => 'color: {{color}};',
		),
		'woo_menu_cart_number_color' => array(
			'li.cart-customlocation span.dashicons-cart + sup' => 'color: {{color}};',
		)
	);
	
	$woo_styles = apply_filters('pagelayer_wc_styles_array', $woo_styles);

	// Apply customizer css
	foreach($woo_styles as $key => $rules){
		
		$value = @$options[$key];
		
		if(empty($value) && $value != '0'){
			continue;
		}
		
		foreach($rules as $sel => $rule){
			
			// Is not reponsive or not variable value?
			if(!is_array($value)){
				$css['desktop'][$sel][] = pagelayer_parse_customize_styles($value, $rule);
				continue;
			}
			
			// If unit exists
			$unit = !empty($value['unit'])? $value['unit'] : 'px';
			
			// Parse in array if responsive rule in string
			$rule = (array) $rule;
			
			foreach($rule as $kk => $_rule){
				
				// Is not reponsive or not variable value?
				if(isset($value[$kk]) && !is_array($value[$kk])){
					
					if(empty($value[$kk]) && $value[$kk] != '0'){
						continue;
					}
					
					$css['desktop'][$sel][] = pagelayer_parse_customize_styles($value[$kk], $_rule, $unit);					
				}
				
				foreach($modes as $mode){
					
					// First level responsive key
					if(isset($value[$mode])){
						
						// Responsive without variable
						$mode_val = is_numeric($kk) ? $value[$mode] : $value[$mode][$kk] ;
							
						if(empty($mode_val) && $mode_val != '0'){
							continue;
						}
						
						$css[$mode][$sel][] = pagelayer_parse_customize_styles($mode_val, $_rule, $unit);
						
						// We are already in responsive mode
						continue;						
					}
					
					// Second level responsive key like font size
					if(!isset($value[$kk][$mode]) || empty($value[$kk][$mode]) && $value[$kk][$mode] != '0'){
						continue;
					}
					
					$css[$mode][$sel][] = pagelayer_parse_customize_styles($value[$kk][$mode], $_rule, $unit);
				}
			}
		}
	}
	
	// Create css
	$screen_css = array('desktop' => '', 'tablet' => '', 'mobile' => '');
	foreach($css as $mode => $_css){
		foreach($_css as $selector => $val){
			$parsr_style = $selector.'{'.implode(';', $val)."}\n";
			$screen_css[$mode] .= $parsr_style;
		}
	}
	
	$styles = '<style id="pagelayer-woocommerce-styles" type="text/css">'.PHP_EOL;
	$styles .= $screen_css['desktop'];
	
	if(!empty($screen_css['woo_product_image_width'])){
		$styles .= '@media(min-width: 902px) {.woocommerce #content div.product div.images, .woocommerce div.product div.images, .woocommerce-page #content div.product div.images, .woocommerce-page div.product div.images{
			width: '.$options['woo_product_image_width'].'% !important;
		}
		.woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary{
			width: calc(96% - '.$options['woo_product_image_width'].'%) !important;
		}}';
	}
	
	if(!empty($screen_css['tablet'])){
		$styles .= '@media(max-width: ' . $pagelayer->settings['tablet_breakpoint'] . 'px) {'.$screen_css['tablet'].'}'.PHP_EOL;
	}
	
	if(!empty($screen_css['mobile'])){
		$styles .= '@media(max-width: ' . $pagelayer->settings['mobile_breakpoint'] . 'px) {'.$screen_css['mobile'].'}';
	}
	
	$styles .= '</style>';
	
	echo $styles;
}

// Get Option Values
function pagelayer_get_customize_options(){
	return get_option('pagelayer_customizer_options', array());
}

add_action( 'customize_register', 'pagelayer_woo_customize_register', 11 );
function pagelayer_woo_customize_register( $wp_customize ) {
	
	//PageLayer + WooCommerce Panel
	$wp_customize->add_panel( 'pgl_woocommerce', array(
		'priority'       => 10,
		'title'          => __('Pagelayer + WooCommerce'),
	) );
	
	// Add Store Notice Section
	$wp_customize->get_section( 'woocommerce_store_notice' )->description = '<strong><a href="customize.php?autofocus[section]=pgl_woo_store_notice">'.__('Click here') .'</a> '. __('to change color scheme of store notice') .'</strong>';
			
	// Add Store Notice Section
	$wp_customize->add_section( 'pgl_woo_store_notice', array(
			'panel'    => 'pgl_woocommerce',
			'priority' => 1,
			'title' => __('Store Notice'),
			'description' => '<strong><a href="customize.php?autofocus[section]=woocommerce_store_notice">'.__('Click here') .'</a> '. __('to enable the store notice') .'</strong>',
		)
	);
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_notice_bg_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control( $wp_customize, 'pagelayer_customizer_options[woo_notice_bg_color]', array(
			'label' => __('Background Color'),
			'section' => 'pgl_woo_store_notice',
			'priority' => 1
		) )
	);
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_notice_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control( $wp_customize, 'pagelayer_customizer_options[woo_notice_color]', array(
			'label' => __('Text Color'),
			'section' => 'pgl_woo_store_notice',
			'priority' => 1
		) )
	);	
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_notice_a_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control( $wp_customize, 'pagelayer_customizer_options[woo_notice_a_color]', array(
			'label' => __('Link Color'),
			'section' => 'pgl_woo_store_notice',
			'priority' => 1
		) )
	);	
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_notice_a_hover_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control( $wp_customize, 'pagelayer_customizer_options[woo_notice_a_hover_color]', array(
			'label' => __('Link Hover Color'),
			'section' => 'pgl_woo_store_notice',
			'priority' => 1
		) )
	);	
	
	// Add Store Notice Section
	$wp_customize->add_section( 'pgl_woo_general', array(
			'panel'    => 'pgl_woocommerce',
			'title' => __('General'),
			'priority' => 2,
		)
	);
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_star_rating_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
		)
	);
	
	$wp_customize->add_setting( 'pagelayer_lable_menu_cart', array(
		'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control( new Pagelayer_Customize_Control(
		$wp_customize, 'pagelayer_lable_menu_cart', array(
			'type' => 'hidden',
			'section' => 'pgl_woo_general',
			'description' => __('<div class="pagelayer-customize-heading"><div>Cart Icon on Menu</div></div>', 'pagelayer'),
			'li_class' => 'pagelayer-accordion-tab',
			'priority' => 9
		)
	));
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_enable_menu_cart]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Custom_Control( $wp_customize, 'pagelayer_customizer_options[woo_enable_menu_cart]', array(
			'type' => 'checkbox',
			'label' => __('Show Cart Icon On Primary Menu'),
			'section' => 'pgl_woo_general',
			'priority' => 9
		))
	);
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_menu_cart_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh'
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control( $wp_customize, 'pagelayer_customizer_options[woo_menu_cart_color]', array(
			'label' => __('Icon Color'),
			'section' => 'pgl_woo_general',
			'priority' => 10,
		) )
	);
	
	// Adds Customizer settings
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_menu_cart_number_color]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh'
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control( $wp_customize, 'pagelayer_customizer_options[woo_menu_cart_number_color]', array(
			'label' => __('Cart Numbers Color'),
			'section' => 'pgl_woo_general',
			'priority' => 10,
		) )
	);
	
	// Shop Page Section
	$wp_customize->add_section( 'pgl_woo_product_catalog', array(
			'panel'    => 'pgl_woocommerce',
			'title' => __('Product Catalog'),
			'priority' => 4,
		)
	);
	
	// Register the WooCommerce Default Padding
	pagelayer_register_padding_customizer_control($wp_customize, array(
		'control' => 'pagelayer_customizer_options',
		'control_array_sufix' => 'woo_product_cat_padding',
		'section' => 'pgl_woo_product_catalog',
		'label' => __( 'Padding', 'pagelayer' ),
		'capability' => 'edit_theme_options',
		'setting_type' => 'option',
		'transport' => 'refresh',
		'default' => '',
		'units' => ['px', 'em', '%'],
		'responsive' => 1,
		'priority' => 1
	), true);
	
	// Single Product Page Sections
	$wp_customize->add_section( 'pgl_woo_single_product', array(
			'panel'    => 'pgl_woocommerce',
			'title' => __('Single Product'),
			'priority' => 5,
		)
	);
	
	// Register the WooCommerce single page Padding
	pagelayer_register_padding_customizer_control($wp_customize, array(
		'control' => 'pagelayer_customizer_options',
		'control_array_sufix' => 'woo_product_padding',
		'section' => 'pgl_woo_single_product',
		'label' => __( 'Padding', 'pagelayer' ),
		'capability' => 'edit_theme_options',
		'setting_type' => 'option',
		'transport' => 'refresh',
		'default' => '',
		'units' => ['px', 'em', '%'],
		'responsive' => 1,
		'priority' => 1
	), true);
	
	// Single Product Page Breadcrumb Enabler
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_disable_breadcrumb]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',						
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Custom_Control( $wp_customize, 'pagelayer_customizer_options[woo_disable_breadcrumb]', array(
			'type' => 'checkbox',
			'label' => __('Disable Breadcrumb'),
			'section' => 'pgl_woo_single_product',
			'priority' => 5
		))
	);
	
	// Cart page settings
	$wp_customize->add_section( 'pgl_woo_cart_page', array(
			'panel'    => 'pgl_woocommerce',
			'title' => __('Cart'),
			'priority' => 7,
		)
	);
	
	pagelayer_register_padding_customizer_control($wp_customize, array(
		'control' => 'pagelayer_customizer_options',
		'control_array_sufix' => 'woo_cart_padding',
		'section' => 'pgl_woo_cart_page',
		'label' => __( 'Padding', 'pagelayer' ),
		'capability' => 'edit_theme_options',
		'setting_type' => 'option',
		'transport' => 'refresh',
		'default' => '',
		'units' => ['px', 'em', '%'],
		'responsive' => 1,
		'priority' => 1
	), true);
	
	// cross-sells disable
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_disable_cross_sells]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',						
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Custom_Control( $wp_customize,  'pagelayer_customizer_options[woo_disable_cross_sells]', array(
			'type' => 'checkbox',
			'label' => __('Disable Cross-sells'),
			'section' => 'pgl_woo_cart_page',
			'priority' => 2
		))
	);
	
	// Checkout Page Section
	$wp_customize->add_section( 'pgl_woo_checkout', array(
			'panel'    => 'pgl_woocommerce',
			'title' => __('Checkout'),
			'priority' => 9,
		)
	);
	
	// Checkout page settings
	pagelayer_register_padding_customizer_control($wp_customize, array(
		'control' => 'pagelayer_customizer_options',
		'control_array_sufix' => 'woo_checkout_padding',
		'section' => 'pgl_woo_checkout',
		'label' => __( 'Padding', 'pagelayer' ),
		'capability' => 'edit_theme_options',
		'setting_type' => 'option',
		'transport' => 'refresh',
		'default' => '',
		'units' => ['px', 'em', '%'],
		'responsive' => 1,
		'priority' => 1
	), true);
	
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_disable_order_note]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',						
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Custom_Control( $wp_customize, 'pagelayer_customizer_options[woo_disable_order_note]', array(
			'type' => 'checkbox',
			'label' => __('Disable Order Note'),
			'section' => 'pgl_woo_checkout',
			'priority' => 2
		))
	);
	
	$wp_customize->add_setting( 'pagelayer_customizer_options[woo_disable_coupon_field]', array(
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',						
		)
	);
	
	$wp_customize->add_control( new Pagelayer_Custom_Control( $wp_customize, 'pagelayer_customizer_options[woo_disable_coupon_field]', array(
			'type' => 'checkbox',
			'label' => __('Disable Coupon Field'),
			'section' => 'pgl_woo_checkout',
			'priority' => 3
		))
	);
	
	// My Account Page Section
	$wp_customize->add_section( 'pgl_woo_myaccount_page', array(
			'panel'    => 'pgl_woocommerce',
			'title' => __('My Account'),
			'priority' => 10,
		)
	);
	
	// My Account page settings
	pagelayer_register_padding_customizer_control($wp_customize, array(
		'control' => 'pagelayer_customizer_options',
		'control_array_sufix' => 'woo_myaccount_padding',
		'section' => 'pgl_woo_myaccount_page',
		'label' => __( 'Padding', 'pagelayer' ),
		'capability' => 'edit_theme_options',
		'setting_type' => 'option',
		'transport' => 'refresh',
		'default' => '',
		'units' => ['px', 'em', '%'],
		'responsive' => 1,
		'priority' => 1
	), true);
}

// Get product
function pagelayer_get_product(){
	
	$_product = wc_get_product();
	
	if(!empty($_product)){
		return $_product;
	}
	
	$post = $GLOBALS['post'];
	
	if( !wp_doing_ajax() && $post->post_type != 'pagelayer-template'){
		return false;
	}
	
	$products = get_posts([
		'post_type' => 'product',
		'numberposts' => '1',
	]);
	
	if(empty($products)){
		return false;
	}
	
	$_product = wc_get_product($products[0]->ID);
	
	return $_product;
}

// Load Product configurations to edit the product template
add_action( 'template_redirect', 'pagelayer_load_product_template');
function pagelayer_load_product_template($post = []){
	global $pagelayer, $product;
	
	if(!$post){
		$post = $GLOBALS['post'];
	}
	
	if(!class_exists('woocommerce') || empty($post->post_type) || $post->post_type != 'pagelayer-template' || !isset($pagelayer->builder['singular_templates']['Products']) ){
		return false;
	}
	
	$products = array_keys($pagelayer->builder['singular_templates']['Products']);
	$conditions = get_post_meta( $post->ID, 'pagelayer_template_conditions', true );
	
	$is_product_temp = false;
	
	foreach( $conditions as $condi ){				
		if(in_array($condi['sub_template'], $products)){
			$is_product_temp = true;
		}
	}
	
	if(!$is_product_temp){
		return false;
	}
	
	// Add WooCommerce Class to body 
	add_filter('body_class', function($classes){
		$classes[] = 'woocommerce';
		return $classes;
	});
	
	$product = pagelayer_get_product();
	
	//pagelayer_print($product);
	
	wp_enqueue_script( 'wc-single-product' );
	wp_enqueue_style( 'wc-single-product' );
	
	// Load woocomerce css and js
	if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
		wp_enqueue_script( 'zoom' );
	}

	if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
		wp_enqueue_script( 'flexslider' );
	}

	if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
		wp_enqueue_script( 'photoswipe-ui-default' );
		wp_enqueue_style( 'photoswipe-default-skin' );
		add_action( 'wp_footer', 'woocommerce_photoswipe' );
	}
	
	wp_enqueue_style( 'photoswipe' );
	wp_enqueue_style( 'photoswipe-default-skin' );
	wp_enqueue_style( 'photoswipe-default-skin' );
	wp_enqueue_style( 'woocommerce_prettyPhoto_css' );
}

if(defined('PAGELAYER_PREMIUM')){
	include_once(dirname(__FILE__).'/premium-woocommerce.php');
}