<?php
/**
 * PopularFX functions and definitions
 *
 * @link https://popularfx.com/docs/
 *
 * @package PopularFX
 */

if ( ! defined( 'POPULARFX_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'POPULARFX_VERSION', '1.2.5' );
}

if ( ! defined( 'POPULARFX_PAGELAYER_API' ) ) {
	define( 'POPULARFX_PAGELAYER_API', 'https://api.pagelayer.com/' );
}

if ( ! defined( 'POPULARFX_WWW_URL' ) ) {
	define( 'POPULARFX_WWW_URL', 'https://popularfx.com' );
}

if ( ! defined( 'POPULARFX_PRO_URL' ) ) {
	define( 'POPULARFX_PRO_URL', 'https://popularfx.com/pricing?from=pfx-theme' );
}

if ( ! defined( 'POPULARFX_URL' ) ) {
	define( 'POPULARFX_URL', get_template_directory_uri() );
}

if(!defined('PAGELAYER_VERSION')){
	define('POPULARFX_PAGELAYER_PRO_URL', 'https://pagelayer.com/pricing?from=pfx-theme');
}

if ( ! function_exists( 'popularfx_setup' ) ){
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function popularfx_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on PopularFX, use a find and replace
		 * to change 'popularfx' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'popularfx', get_stylesheet_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'popularfx' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		
		global $pagelayer, $popularfx;
		if(!empty($pagelayer->settings)){
			$pagelayer->template_call_sidebar = 1;
		}
		
		// Show promos
		popularfx_promos();
		
		$template = popularfx_get_template_name();
		if(empty($template)){
		
			// Set up the WordPress core custom background feature.
			add_theme_support('custom-background',
				apply_filters(
					'popularfx_custom_background_args',
					array(
						'default-color' => 'ffffff',
						'default-image' => '',
					)
				)
			);
			
			add_theme_support( 'custom-header',
				apply_filters(
					'popularfx_custom_header_args',
					array(
						'default-image'      => '',
						'default-text-color' => '000000',
						'width'              => 1200,
						'height'             => 250,
						'flex-height'        => true,
						'wp-head-callback'   => 'popularfx_header_style',
					)
				)
			);
			
			add_theme_support(
				'custom-logo',
				array(
					'height'      => 250,
					'width'       => 250,
					'flex-width'  => true,
					'flex-height' => true,
				)
			);
		
		}
		
		// Add woocommerce support
		add_theme_support( 'woocommerce', array(
			'product_grid' => array(
				'min_columns'=> 1,
				'max_columns' => 6,
			),
		) );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'popularfx_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function popularfx_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'popularfx_content_width', 640 );
}

// To get activated template for parent and child theme
function popularfx_get_template_name(){
	$tmp = get_option('theme_mods_popularfx');
	$mods = !empty($tmp['popularfx_template']) ? $tmp['popularfx_template'] : '';
	return $mods;
}

// Backward compat
function popularfx_copyright(){
	return popularfx_theme_credits();
}

// Show credit of our theme
function popularfx_theme_credits(){
	return '<a href="'.esc_url(POPULARFX_WWW_URL).'">'.__('PopularFX Theme', 'popularfx').'</a>';
}

// Shows the promos
function popularfx_promos(){
	
	if(is_admin() && current_user_can('install_themes')){
		
		//remove_theme_mod('popularfx_getting_started');
		//remove_theme_mod('popularfx_templates_promo');
		//remove_theme_mod('popularfx_show_promo');
		
		// Show the getting started video option
		$seen = get_theme_mod('popularfx_getting_started');
		if(empty($seen)){
			add_action('admin_notices', 'popularfx_getting_started_notice');
		}
	
		// Show the promo
		popularfx_maybe_promo([
			'after' => 1,// In days
			'interval' => 30,// In days
			'pro_url' => POPULARFX_PRO_URL,
			'rating' => 'https://wordpress.org/themes/popularfx/#reviews',
			'twitter' => 'https://twitter.com/PopularFXthemes?status='.rawurlencode('I love #PopularFX Theme by @pagelayer team for my #WordPress site - '.esc_url(home_url())),
			'facebook' => 'https://facebook.com/popularfx',
			'website' => POPULARFX_WWW_URL,
			'image' => POPULARFX_URL.'/images/popularfx-logo.png',
			'name' => 'popularfx_show_promo'
		]);
		
		$template = popularfx_get_template_name();
		if(empty($template)){
		
			// Show the image promo
			popularfx_maybe_promo([
				'after' => 0,// In days
				'interval' => 30,// In days
				'pro_url' => POPULARFX_PRO_URL,
				'rating' => 'https://wordpress.org/themes/popularfx/#reviews',
				'twitter' => 'https://twitter.com/PopularFXthemes?status='.rawurlencode('I love #PopularFX Theme by @pagelayer team for my #WordPress site - '.esc_url(home_url())),
				'facebook' => 'https://facebook.com/popularfx',
				'website' => POPULARFX_WWW_URL,
				'image' => POPULARFX_URL.'/images/popularfx-logo.png',
				'name' => 'popularfx_templates_promo'
			]);
		
		}
		//delete_option('popularfx_templates_promo');
	
	}
	
}
add_action( 'after_switch_theme', 'popularfx_promos', 10 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function popularfx_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'popularfx' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'popularfx' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'popularfx_widgets_init' );

// URL of the PFX templates uploads dir
function popularfx_templates_dir_url(){
	
	$template = popularfx_get_template_name();
	$style = get_template_directory().'/templates/'.$template.'/style.css';
	
	if(file_exists($style)){
		return get_stylesheet_directory_uri().'/templates';
	}
	
	$dir = wp_upload_dir(NULL, false);
	return $dir['baseurl'].'/popularfx-templates';
	
}

// URL of the PFX templates uploads dir
function popularfx_templates_dir($suffix = true){
	
	$template = popularfx_get_template_name();
	$style = get_template_directory().'/templates/'.$template.'/style.css';
	
	if(file_exists($style)){
		return get_template_directory().'/templates'.($suffix ? '/'.$template : '');
	}
	
	$dir = wp_upload_dir(NULL, false);
	return $dir['basedir'].'/popularfx-templates'.($suffix ? '/'.$template : '');
	
}

/**
 * Enqueue scripts and styles.
 */
function popularfx_scripts() {
	
	$template = popularfx_get_template_name();
	if(!empty($template) && defined('PAGELAYER_VERSION')){
		wp_enqueue_style( 'popularfx-style', popularfx_templates_dir_url().'/'.$template.'/style.css', array(), POPULARFX_VERSION );
	}else{
		wp_enqueue_style( 'popularfx-style', get_template_directory_uri().'/style.css', array(), POPULARFX_VERSION );
		wp_style_add_data( 'popularfx-style', 'rtl', 'replace' );
	}
	
	// Enqueue sidebar.css
	wp_enqueue_style( 'popularfx-sidebar', get_template_directory_uri().'/sidebar.css', array(), POPULARFX_VERSION );
	
	// Dashicons needed for WooCommerce and Scroll to Top
	if(class_exists( 'WooCommerce' ) || get_theme_mod('pfx_enable_scrolltop')){
		wp_enqueue_style('dashicons');
	}
	
	// Enqueue WooCommerce CSS
	if(class_exists( 'WooCommerce' )){
		wp_enqueue_style( 'popularfx-woocommerce', get_template_directory_uri().'/woocommerce.css', array(), POPULARFX_VERSION );
		wp_style_add_data( 'popularfx-woocommerce', 'rtl', 'replace' );
	}
	
	wp_enqueue_script( 'popularfx-navigation', get_template_directory_uri().'/js/navigation.js', array('jquery'), POPULARFX_VERSION, true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'popularfx_scripts' );

function popularfx_the_title($before = '', $after = '', $echo = true){
	if(is_page()){
		return;
	}
	
	the_title($before, $after, $echo);
}

// Show the templates promo
function popularfx_templates_promo(){
	
	global $popularfx_promo_opts, $popularfx;
	
	if(!empty($GLOBALS['pfx_notices_shown'])){
		return;
	}
	
	$GLOBALS['pfx_notices_shown'] = 1;
	
	$opts = $popularfx_promo_opts['popularfx_templates_promo'];
	
	echo '<style>
#popularfx_templates_promo{
border-left: 1px solid #ccd0d4;
}

.popularfx-templates-promo{
background: #fff;
padding: 5px;
}

.popularfx_promo_button {
background-color: #4CAF50; /* Green */
border: none;
color: white;
padding: 6px 10px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 13px;
margin: 4px 2px;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
cursor: pointer;
}
.popularfx_promo_button:focus,
.popularfx_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.popularfx_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.popularfx_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.popularfx_promo_button1:hover {
border:3px solid #4CAF50;
}
.popularfx_promo_button2 {
color: white;
background-color: #0085ba;
}
.popularfx_promo_button3 {
color: white;
background-color: #365899;
}
.popularfx_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.popularfx_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.popularfx_promo-close:hover{
color: red;
}

.popularfx_promo-left{
display: inline-block; max-width: 34%; vertical-align: top;
text-align: center;
}

.popularfx_promo-right{
display: inline-block; max-width: 65%; width: 65%; vertical-align: middle;
}

@media all and (max-width:599px){
.popularfx_promo-left, .popularfx_promo-right{
max-width: 100%;
}
}

</style>
<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#popularfx_templates_promo .popularfx_promo-close").click(function(){
			var data;
			jQuery("#popularfx_templates_promo").hide();
			// Save this preference
			jQuery.post("'.esc_url(admin_url('?'.$opts['name'].'=0')).'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

	<div class="'.(empty($opts['class']) ? 'notice notice-success' : 'popularfx-templates-promo').'" id="popularfx_templates_promo" style="min-height:90px">
		<div class="popularfx_promo-left">
			<img src="'.esc_url(POPULARFX_URL.'/images/templates.png').'" width="85%" />
		</div>
		<div class="popularfx_promo-right">
			<p align="center">
		<a class="popularfx_promo-close" href="javascript:" aria-label="Dismiss this Notice">
			<span class="dashicons dashicons-dismiss"></span> Dismiss
		</a>
				<a href="'.esc_url(POPULARFX_WWW_URL.'/templates').'" style="text-decoration: none; font-size: 15px;">
					'.__('Did you know PopularFX comes with 500+ Templates to design your website. <br>Click to choose your template !', 'popularfx').'<br>
				</a>
				<br>
				'.(empty($opts['pro_url']) || !empty($popularfx['license']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['pro_url']).'">'.__('Buy PopularFX Pro', 'popularfx').'</a>').'
				'.(empty($opts['rating']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['rating']).'">'.__('Rate it 5★\'s', 'popularfx').'</a>').'
				'.(empty($opts['facebook']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['facebook']).'"><span class="dashicons dashicons-thumbs-up" style="vertical-align: middle;"></span> '.__('Facebook', 'popularfx').'</a>').'
				'.(empty($opts['twitter']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['twitter']).'"><span class="dashicons dashicons-twitter" style="vertical-align: middle;"></span> '.__('Tweet', 'popularfx').'</a>').'
				'.(empty($opts['website']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['website']).'">'.__('Visit our website', 'popularfx').'</a>').'
			</p>
		</div>
	</div>';
	
}

function popularfx_getting_started_notice(){
	
	if(!empty($GLOBALS['pfx_notices_shown'])){
		return;
	}
	
	$GLOBALS['pfx_notices_shown'] = 1;
	
	// Are we to disable the promo
	if(isset($_GET['popularfx-getting-started']) && (int)$_GET['popularfx-getting-started'] == 0){
		check_ajax_referer('popularfx_getting_started_nonce', 'popularfx_nonce');
		set_theme_mod('popularfx_getting_started', time());
		die('DONE');
	}
	
	echo '
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery("#popularfx-getting-started-notice").click(function(e){
		
		if(jQuery(e.target).hasClass("notice-dismiss")){
			var data;
			jQuery("#popularfx-getting-started-notice").hide();
			// Save this preference
			jQuery.post("'.admin_url('?popularfx-getting-started=0&popularfx_nonce='.wp_create_nonce("popularfx_getting_started_nonce")).'", data, function(response) {
				//alert(response);
			});
			return false;
		}
		
	});
});
</script>

	<div id="popularfx-getting-started-notice" class="notice notice-success is-dismissible">
		<p style="font-size: 14px; font-weight: 600">
			<a href="'.esc_url(POPULARFX_WWW_URL).'"><img src="'.esc_url(POPULARFX_URL).'/images/popularfx-logo.png" style="vertical-align: middle; margin:0px 10px" width="24" /></a>'.__('Thanks for choosing PopularFX. We recommend that you see the <a href="https://www.youtube.com/watch?v=DCisrbrmjgI" target="_blank">PopularFX Theme Guide Video</a> to know the basics of PopularFX.', 'popularfx').'
		</p>
	</div>';
	
}

// Show promo notice on dashboard
function popularfx_show_promo($opts = []){
	
	global $popularfx_promo_opts, $popularfx;
	
	if(!empty($GLOBALS['pfx_notices_shown'])){
		return;
	}
	
	$GLOBALS['pfx_notices_shown'] = 1;

	$opts = $popularfx_promo_opts['popularfx_show_promo'];
	
	echo '<style>
#popularfx_promo{
border-left: 1px solid #ccd0d4;
}

.popularfx_promo_button {
background-color: #4CAF50; /* Green */
border: none;
color: white;
padding: 6px 10px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 13px;
margin: 4px 2px;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
cursor: pointer;
}
.popularfx_promo_button:focus,
.popularfx_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.popularfx_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.popularfx_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.popularfx_promo_button1:hover {
border:3px solid #4CAF50;
}
.popularfx_promo_button2 {
color: white;
background-color: #0085ba;
}
.popularfx_promo_button3 {
color: white;
background-color: #365899;
}
.popularfx_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.popularfx_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.popularfx_promo-close:hover{
color: red;
}

.popularfx_promo-left-1{
display: inline-block; width: 65%; vertical-align: top;
}

.popularfx_promo-right-1{
display: inline-block; width: 35%; vertical-align: top
}

@media all and (max-width:599px){
.popularfx_promo-left, .popularfx_promo-right{
width: 100%;
}
}

</style>
<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#popularfx_promo .popularfx_promo-close").click(function(){
			var data;
			jQuery("#popularfx_promo").hide();
			// Save this preference
			jQuery.post("'.esc_url(admin_url('?'.$opts['name'].'=0')).'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

	<div class="notice notice-success" id="popularfx_promo" style="min-height:90px">
		<div class="popularfx_promo-left-1">';
	
	if(!empty($opts['image'])){
		echo '<a href="'.esc_url($opts['website']).'"><img src="'.esc_url($opts['image']).'" style="float:left; margin:25px 20px 10px 10px" width="67" /></a>';
	}
	
	echo '
		<p style="font-size:14px; line-height: 1.6">'.sprintf( __('We are glad you are using %1$s to build your website. We really appreciate it ! <br>We would like to request you to give us a 5 Star rating on %2$s. <br>It will greatly boost our motivation !', 'popularfx'), '<a href="'.$opts['website'].'"><b>PopularFX</b></a>', '<a href="'.$opts['rating'].'" target="_blank">WordPress.org</a>').'</p>
		<p>
			'.(empty($opts['pro_url']) || !empty($popularfx['license']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['pro_url']).'">'.__('Buy PopularFX Pro', 'popularfx').'</a>').'
			'.(empty($opts['rating']) ? '' : '<a class="button button-primary" target="_blank" href="'.esc_url($opts['rating']).'">'.__('Rate it 5★\'s', 'popularfx').'</a>').'
			'.(empty($opts['facebook']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['facebook']).'"><span class="dashicons dashicons-thumbs-up" style="vertical-align: middle;"></span> '.__('Facebook', 'popularfx').'</a>').'
			'.(empty($opts['twitter']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['twitter']).'"><span class="dashicons dashicons-twitter" style="vertical-align: middle;"></span> '.__('Tweet', 'popularfx').'</a>').'
			'.(empty($opts['website']) ? '' : '<a class="button button-secondary" target="_blank" href="'.esc_url($opts['website']).'">'.__('Visit our website', 'popularfx').'</a>').'
		</p>
		'.(empty($opts['pro_url']) || !empty($popularfx['license']) ? '' : '<p style="font-size:13px">'.sprintf( __('%1$s has many more features like 500+ Website %2$s Templates%3$s, 90+ widgets, 500+ sections, Theme Builder, WooCommerce Builder, Theme Creator and Exporter, Form Builder, Popup Builder, etc. You get a Pagelayer Pro license with the PopularFX Pro version.', 'popularfx'), '<a href="'.esc_url($opts['pro_url']).'"><b>PopularFX Pro</b></a>', '<a href="'.esc_url($opts['website'].'/templates').'">', '</a>').'
		</p>').'
		</div>';
	

	
	echo '<div class="popularfx_promo-right-1">
			<a class="popularfx_promo-close" href="javascript:" aria-label="'.__('Dismiss this Notice', 'popularfx').'">
				<span class="dashicons dashicons-dismiss"></span> '.__('Dismiss', 'popularfx').'
			</a>
			<br>
			<center style="margin:10px;">
				<a href="'.esc_url(POPULARFX_WWW_URL.'/templates').'" style="text-decoration: none; font-size: 15px;"><img src="'.esc_url(POPULARFX_URL.'/images/templates.png').'" width="100%" /><br><br>'.__('Install from 500+ Website Templates', 'popularfx').'</a>
			</center>
		</div>
	</div>';

}

// Are we to show a promo ?
function popularfx_maybe_promo($opts){
	
	global $popularfx_promo_opts;
	
	// There must be an interval
	if(empty($opts['interval'])){
		return false;
	}
	
	// Are we to show a promo	
	$opt_name = empty($opts['name']) ? 'popularfx_show_promo' : $opts['name'];
	$func = empty($opts['name']) ? $opt_name : $opts['name'];
	$promo_time = get_theme_mod($opt_name);
	//echo $opt_name.' - '.$func.' - '.$promo_time.' - '.date('Ymd', $promo_time).'<br>';die();
	
	// First time access
	if(empty($promo_time)){
		set_theme_mod($opt_name, time() + (!empty($opts['after']) ? $opts['after'] * 86400 : 0));
		$promo_time = get_theme_mod($opt_name);
	}
	
	// Is there interval elapsed
	if(time() >= $promo_time){
		$popularfx_promo_opts[$opt_name] = $opts;
		add_action('admin_notices', $func);
	}
	
	// Are we to disable the promo
	if(isset($_GET[$opt_name]) && (int)$_GET[$opt_name] == 0){
		set_theme_mod($opt_name, time() + ($opts['interval'] * 86400));
		die('DONE');
	}
	
}

// Is the sidebar enabled
function popularfx_sidebar(){	

	// If no widgets in sidebar
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		return;
	}

	$enabled = NULL;

	// For page
	if(is_page()){
		$enabled = get_theme_mod('popularfx_sidebar_page', 'default');
	}
	
	$is_product = class_exists('WooCommerce') && is_product() ? true : false;

	// For products none !
	if(is_single() && $is_product){
		$enabled = '0';
	
	// For posts
	}elseif(is_single()){
		$enabled = get_theme_mod('popularfx_sidebar_post', 'right');
	}

	// For Archives
	if(is_archive() || is_home()){
		$enabled = get_theme_mod('popularfx_sidebar_archives', 'right');
	}
	
	// For Woocommerce
	if(class_exists( 'WooCommerce' ) && is_shop()){
		$enabled = get_theme_mod('popularfx_sidebar_woocommerce', 0);
	}
	
	// Load the default
	if($enabled == 'default' || is_front_page()){
		$enabled = get_theme_mod('popularfx_sidebar_default', 0);
	}

	// If its disabled
	if(empty($enabled)){
		return;
	}

	// In live mode of templates dont show this for header and footer
	if(function_exists('pagelayer_is_live') && pagelayer_is_live()){
		$pl_post_type = get_post_meta($GLOBALS['post']->ID, 'pagelayer_template_type', true);
		if(in_array($pl_post_type, ['header', 'footer'])){
			return;
		}
	}
	
	return $enabled;
}

add_action('wp_enqueue_scripts', 'popularfx_sidebar_css', 1000);
function popularfx_sidebar_css(){

	// Sidebar CSS
	$enabled = popularfx_sidebar();

	if(empty($enabled)){
		return;
	}

	$width = (int) get_theme_mod('popularfx_sidebar_width', 20);
	
	$custom_css = '
aside {
width: '.esc_attr($width).'%;
float: '.esc_attr($enabled).';
}

main, .pagelayer-content{
width: '.round(99 - esc_attr($width)).'% !important;
display: inline-block;
float: '.esc_attr($enabled == 'left' ? 'right' : 'left').';
}'.PHP_EOL;

	wp_add_inline_style('popularfx-style', $custom_css);
	
}

if ( ! function_exists( 'popularfx_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see popularfx_custom_header_setup().
	 */
	function popularfx_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
				}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;

add_action( 'wp_footer', 'popularfx_footer_setup' );
if( ! function_exists( 'popularfx_footer_setup' )){	
	function popularfx_footer_setup(){
	
		$pfx_enable_scrolltop = get_theme_mod('pfx_enable_scrolltop');
		if(empty($pfx_enable_scrolltop)){
			return;
		}
		
		echo '<a id="pfx-scroll-top" class="pfx-scroll-top"><span class="dashicons dashicons-arrow-up-alt2"></span><span class="screen-reader-text">Scroll to Top</span></a>';	
	}
}

/**
 * Custom template tags for this theme.
 */
require dirname( __FILE__ ) . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require dirname( __FILE__ ) . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require dirname( __FILE__ ) . '/inc/customizer.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require dirname( __FILE__ ) . '/inc/woocommerce.php';
}

// Update the theme
require_once dirname( __FILE__ ) . '/inc/popularfx.php';
