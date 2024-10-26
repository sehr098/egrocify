<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package PopularFX
 */


/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function popularfx_woocommerce_scripts() {
	$inline = '.popularfx-body.woocommerce-page main{
padding: 15px;
}';

	wp_add_inline_style( 'popularfx-style', $inline );
}
add_action( 'wp_enqueue_scripts', 'popularfx_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function popularfx_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'popularfx_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function popularfx_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'popularfx_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'popularfx_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function popularfx_woocommerce_wrapper_before() {
		?>
			<main id="primary" class="site-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'popularfx_woocommerce_wrapper_before' );

if ( ! function_exists( 'popularfx_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function popularfx_woocommerce_wrapper_after() {
		?>
			</main><!-- #main -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'popularfx_woocommerce_wrapper_after' );

// Show the star rating
add_action('woocommerce_after_shop_loop_item_title', 'popularfx_woocommerce_star_rating', 5);
function popularfx_woocommerce_star_rating() {    
	global $product;
	$rating = $product->get_average_rating();   
	$rating_html  = '</a><a href="' . get_the_permalink() . '#respond"><div class ="pfx-star-rating-container"><div class="star-rating pfx-star-rating"><span style="width:' . (( $rating / 5 ) * 100) . '%"></span></div></div></a>';
	echo $rating_html;
}


/**
* Show cart contents / total Ajax
*/
add_filter( 'woocommerce_add_to_cart_fragments', 'popularfx_wc_header_add_to_cart_fragment' );

function popularfx_wc_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();
	?>
	<li class="page-item pfx-menu-cart cart-customlocation menu-item menu-item-type-post_type menu-item-object-page"><a href="<?php echo esc_url(wc_get_cart_url()); ?>"><span class="dashicons dashicons-cart"></span><sup><?php echo WC()->cart->get_cart_contents_count();?></sup></a></li>
	<?php
	$fragments['li.cart-customlocation'] = ob_get_clean();
	return $fragments;
}
