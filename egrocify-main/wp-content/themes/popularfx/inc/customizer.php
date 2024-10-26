<?php
/**
 * PopularFX Theme Customizer
 *
 * @package PopularFX
 */

add_action( 'wp_head', 'popularfx_global_styles', 4 );
function popularfx_global_styles(){
	
	global $pagelayer, $popularfx;
	
	$settings = ['body', 'p', 'button', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'color', 'max_width' => 'content_width', 'tablet_breakpoint', 'mobile_breakpoint', 'template'];
	
	foreach($settings as $k => $v){
		
		$set = get_theme_mod('popularfx_'.$v);
		
		if(empty($set)){
			continue;
		}
		
		$css[$v] = $set;
		
		$key = is_numeric($k) ? $v : $k;
		
		// We override pagelayer settings for widths and breakpoint
		if(defined('PAGELAYER_VERSION') && in_array($v, ['content_width', 'tablet_breakpoint', 'mobile_breakpoint'])){
			$pagelayer->settings[$key] = $set;
		}
		
	}
	
	$styles = '<style id="popularfx-global-styles" type="text/css">'.PHP_EOL;
	
	$pfx_template_dir = popularfx_templates_dir();
	
	// Add the max width only when we have no template
	if( empty($css['template']) && !file_exists($pfx_template_dir.'/pagelayer.conf') ){
		$styles .= '.entry-content{ max-width: '.(empty($css['content_width']) ? 1170 : esc_attr($css['content_width'])).'px; margin-left: auto !important; margin-right: auto !important;}'.PHP_EOL;
	}
	
	// Colors
	if(!empty($css['color']['background'])){
		$css['body']['background-color'] = $css['color']['background'];
	}
	
	if(!empty($css['color']['text'])){
		$css['body']['color'] = $css['color']['text'];
	}
	
	// Global CSS settings
	$css_settings = ['body', 'p', 'button', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
	
	// PX suffix
	$pxs = ['font-size', 'letter-spacing', 'word-spacing'];
	
	foreach($css_settings as $k => $v){
		
		$key = is_numeric($k) ? $v : $k;
		$r = [];
			
		if(empty($css[$key])){
			continue;
		}
		
		if(!empty($css[$v]['font-family']) && strtolower($css[$v]['font-family']) == 'inherit'){
			unset($css[$v]['font-family']);
		}
	
		// Fetch body font if given
		if(!empty($css[$v]['font-family'])){
			$val = $css[$v]['font-family'];			
			$font_weight = empty($css[$v]['font-weight']) ? 400 : $css[$v]['font-weight'];
			$font_style = empty($css[$v]['font-style']) ? 'normal' : $css[$v]['font-style'];
			$font_style = in_array($font_style, ['italic', 'oblique']) ? 'i' : '';			
			$popularfx['runtime_fonts'][$val][$font_weight.$font_style] = $font_weight.$font_style;
		}
		
		foreach($css[$key] as $kk => $vv){
			
			if(empty($vv)){
				continue;
			}
			
			$r[] = $kk.':'.$vv.(in_array($kk, $pxs) ? 'px' : '');
			
		}
		
		if(empty($r)){
			continue;
		}
		
		$styles .= 'body.popularfx-body '.esc_attr($v == 'body' ? '' : $v).'{'.esc_attr(implode(';', $r))."}\n";
	}
	
	// Link Color
	if(!empty($css['color']['link'])){
		$styles .= 'body.popularfx-body a{color: '.esc_attr($css['color']['link']).'}'.PHP_EOL;
	}
	
	// Link Hover Color
	if(!empty($css['color']['link-hover'])){
		$styles .= 'body.popularfx-body a:hover{color: '.esc_attr($css['color']['link-hover']).'}'.PHP_EOL;
	}
	
	// Heading Color
	if(!empty($css['color']['heading'])){
		$styles .= 'body.popularfx-body h1,h2,h3,h4,h5,h6{color: '.esc_attr($css['color']['heading']).'}'.PHP_EOL;
	}
	
	// Header Background Color
	$header_bg_color = get_theme_mod('popularfx_header_bg_color', '#ffffff');
	$styles .= '.site-header {background-color:'.esc_attr($header_bg_color).'!important;}'.PHP_EOL;
	
	// Site Title Color
	$site_title_color = get_theme_mod('popularfx_site_title_color', '#171717');
	$styles .= '.site-title a {color:'.esc_attr($site_title_color).'!important;}'.PHP_EOL;
	
	// Site title size
	$site_title_size = get_theme_mod( 'popularfx_site_title_size', 30 );
	$styles.= '.site-title a { font-size: ' . esc_attr( $site_title_size ) .' px; }'.PHP_EOL;
	
	// Site Description Color
	$description_color = get_theme_mod('popularfx_site_tagline_color', '#171717');
	$styles .= '.site-description {color:'.esc_attr($description_color).' !important;}'.PHP_EOL;
	
	// Site Description size
	$tagline_size = get_theme_mod( 'popularfx_tagline_size', 15 );
	$styles .= '.site-description {font-size: ' . esc_attr($tagline_size) . 'px;}'.PHP_EOL;
	
	// Footer Background Color
	$footer_bg_color = get_theme_mod('popularfx_footer_bg_color', '#171717');
	$styles .= '.site-footer {background-color:'.esc_attr($footer_bg_color).'! important;}'.PHP_EOL;
	
	if ( get_header_image() ){
		$styles .= '.site-header {background-image: url("'.esc_url(get_header_image()).'");}'.PHP_EOL;
	}
	
	// Scroll to Top
	if( get_theme_mod( 'pfx_enable_scrolltop' ) ){
		$pfx_scrolltop_iconsize = get_theme_mod('pfx_scrolltop_iconsize', 15);
		$styles .= 'a#pfx-scroll-top.pfx-scroll-top { left: '. get_theme_mod('pfx_scrolltop_position', 95) . '%;  padding: '. get_theme_mod('pfx_scrolltop_padding', 10) . 'px; border: '. get_theme_mod('pfx_scrolltop_borderwidth', 2) . 'px solid; border-radius: '. get_theme_mod('pfx_scrolltop_borderradius', 15) . 'px; background-color: '. get_theme_mod('pfx_scrolltop_bg_color', '#ffffff') . ';  border-color: '. get_theme_mod('pfx_scrolltop_border_color', '#000000') . ' !important; }'.PHP_EOL;
		$styles .= 'a#pfx-scroll-top span.dashicons.dashicons-arrow-up-alt2 { width: '. $pfx_scrolltop_iconsize . 'px; height: '. $pfx_scrolltop_iconsize . 'px; font-size: '. $pfx_scrolltop_iconsize . 'px;  color: '. get_theme_mod('pfx_scrolltop_color', '#000000') . '; }'.PHP_EOL;
		$styles .= 'a#pfx-scroll-top:hover { background-color: '. get_theme_mod('pfx_scrolltop_bg_hover_color', '#000000') . ';  border-color: '. get_theme_mod('pfx_scrolltop_hover_border_color', '#000000') . ' !important; } ';
		$styles .= 'a#pfx-scroll-top:hover span.dashicons.dashicons-arrow-up-alt2 { color: '. get_theme_mod('pfx_scrolltop_hover_color', '#ffffff') . '; }'.PHP_EOL;
		$styles .= 'a#pfx-scroll-top {position: fixed;	left: 95%;	bottom: 30px; z-index: 9999999;	line-height: 1;	cursor: pointer; display:none;}';

	}
	
	$styles .= PHP_EOL.'</style>';
	
	echo $styles;
	
	//pagelayer_print($pagelayer->settings);
}

add_filter('body_class', 'popularfx_body_class', 10, 2);
function popularfx_body_class($classes, $class){
	$classes[] = 'popularfx-body';
	return $classes;
}

// Load the google fonts
add_action('wp_footer', 'popularfx_enqueue_fonts', 4);
function popularfx_enqueue_fonts(){
	
	global $pagelayer, $popularfx;
	
	if(empty($popularfx['runtime_fonts'])){
		return;
	}
	
	$url = [];
	
	foreach($popularfx['runtime_fonts'] as $font => $weights){
		$url[] = $font.':'.implode(',', $weights);
	}
	
	// If no fonts are to be set, then we dont set
	if(empty($url)){
		return false;
	}
	
	wp_register_style('popularfx-google-font', 'https://fonts.googleapis.com/css?family='.rawurlencode(implode('|', $url)), array(), POPULARFX_VERSION);
	wp_enqueue_style('popularfx-google-font');
	
}

if(class_exists('WP_Customize_Section')){
		
	class WP_Customize_No_Pagelayer_Section extends WP_Customize_Section { 
		public function render(){

			echo '<li id="accordion-section-no_pagelayer" class="accordion-section control-section control-section-install-pgl">
			<a href="'.admin_url('plugin-install.php?s=pagelayer&tab=search&type=term').'" target="_blank"><span class="dashicons dashicons-megaphone"></span>'.__('Install Pagelayer to get full access !', 'popularfx').'</a>
			</li>';
		
		}
		
	}

	class WP_Customize_PFX_Pro_Section extends WP_Customize_Section {
		public $type = 'pfxpro';

		public function render() {
		?>
		<li id="accordion-section-popularfx_pro_link" class="accordion-section control-section control-section-<?php echo esc_html( $this->type ); ?>">
				<a href="<?php echo POPULARFX_WWW_URL; ?>/pricing?from=pfx-customizer" target="_blank"><?php _e('Get More Options with PopularFX Pro', 'popularfx'); ?><span class="dashicons dashicons-arrow-right-alt2"></span></a>
			</li>
		<?php
		}
	}

}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function popularfx_customize_register( $wp_customize ) {
	
	global $popularfx;
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if(empty($popularfx['license']['status'])){
		
		$wp_customize->add_section( new WP_Customize_PFX_Pro_Section($wp_customize, 'popularfx_pro_link', array(
			'capability' => 'edit_theme_options',
			'priority'   => 1,
			'title'      => __('Get More Options with PopularFX Pro', 'popularfx')
		) ) );
	
		// Install Pagelayer PRO if PRO user not installed already
		$wp_customize->add_setting('popularfx_pro_show', array(
			'capability' => 'edit_theme_options',
			'type'       => 'hidden',
			'autoload'   => false,
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control('popularfx_pro_show', array(
			'label'   => __('See Pro Features', 'popularfx'),
			'description' => 'test',
			'section' => 'popularfx_pro_link',
			'type'    => 'hidden',
		) );
	
	// Suggest to install Pagelayer
	}elseif(!defined('PAGELAYER_VERSION')){
		
		$wp_customize->add_section( new WP_Customize_No_Pagelayer_Section($wp_customize, 'popularfx_no_pagelayer', array(
			'capability' => 'edit_theme_options',
			'priority'   => 1,
			'title'      => __('Install Pagelayer', 'popularfx')
		) ) );

		// Install Pagelayer if not installed already
		$wp_customize->add_setting('popularfx_pagelayer_show', array(
			'capability' => 'edit_theme_options',
			'type'       => 'hidden',
			'autoload'   => false,
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control('popularfx_pagelayer_show', array(
			'label'   => __('Install Pagelayer', 'popularfx'),
			'description' => 'test',
			'section' => 'popularfx_no_pagelayer',
			'type'    => 'hidden',
		) );
	}
	
	// Loads for the left panel
	wp_register_style('popularfx-customizer', get_template_directory_uri().'/customizer.css', POPULARFX_VERSION);
	wp_enqueue_style('popularfx-customizer');
	
	// Loads for the left pane
	wp_register_script('popularfx-customizer-controls', get_template_directory_uri().'/js/customizer-controls.js', array(), POPULARFX_VERSION, true);
	
	// Add custom Controls
	require_once dirname( __FILE__ ) . '/customizer-controls.php';
	
	$pages = '';
	$templates = '';
	$html = '';	
	
	//---------------------------------
	// Edit Header Footer Pages option
	//---------------------------------
	
	$theme = wp_get_theme();

	$template = popularfx_get_template_name();
	
	// If there is a template in use
	if(!empty($template) && defined('PAGELAYER_VERSION')){
		
		$wp_customize->add_section( 'popularfx_edit_links', array(
			'capability' => 'edit_theme_options',
			'priority'   => 1,
			'title'      => __( 'Header, Footer, Templates, Pages', 'popularfx' )
		) );
		 
		// Get list of pages and pagelayer templates to edit
		$args = array(
			'post_type' => ['page', 'pagelayer-template'],
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'pagelayer_imported_content',
					'value' => $template,
					'compare' => '='
				)
			)
		);
		
		$query = new WP_Query($args);

		foreach($query->posts as $k => $v){
			$a = '<a href="'.pagelayer_livelink($v->ID).'" class="popularfx-edit-link" target="_blank">'.$v->post_title.'</a>';
			
			if($v->post_type == 'page'){
				$pages .= $a;
			}else{
				
				$temp_type = get_post_meta( $v->ID, 'pagelayer_template_type', true );
				
				if(in_array($temp_type, ['header', 'footer'])){
					$html .= $a;
				}else{
					$templates .= $a;
				}
				
			}
		}
		
		$pages .= '<p>'.__('<b>Note:</b> The Pagelayer editor will open to edit these pages', 'popularfx').'</p>';
	
		// Theme Header Footer Edit option of Pagelayer
		$wp_customize->add_setting('popularfx_hf', array(
			'capability' => 'edit_theme_options',
			'type'       => 'hidden',
			'autoload'   => false,
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );	

		$wp_customize->add_control('popularfx_hf', array(
			'label'   => __('Edit Header / Footer', 'popularfx'),
			'description' => $html,
			'section' => 'popularfx_edit_links',
			'type'    => 'hidden',
		) );
		
		if(!empty($templates)){
		
			// Template edit options of Pagelayer
			$wp_customize->add_setting('popularfx_templates', array(
				'capability' => 'edit_theme_options',
				'type'       => 'hidden',
				'autoload'   => false,
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			) );

			$wp_customize->add_control('popularfx_templates', array(
				'label'   => __('Edit Templates', 'popularfx'),
				'description' => $templates,
				'section' => 'popularfx_edit_links',
				'type'    => 'hidden',
			) );
		
		}
	
		// Theme Page edit options of Pagelayer
		$wp_customize->add_setting('popularfx_pages', array(
			'capability' => 'edit_theme_options',
			'type'       => 'hidden',
			'autoload'   => false,
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control('popularfx_pages', array(
			'label'   => __('Edit Pages', 'popularfx'),
			'description' => $pages,
			'section' => 'popularfx_edit_links',
			'type'    => 'hidden',
		) );
	
	// No template
	}else{
		
		$wp_customize->add_section( 'popularfx_edit_links', array(
			'capability' => 'edit_theme_options',
			'priority'   => 1,
			'title'      => __( 'Header & Footer Options', 'popularfx' )
		) );
		
		$wp_customize->add_setting('popularfx_header_bg_color', array(
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
		
		$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
			$wp_customize, 'popularfx_header_bg_color', array(
				'section' => 'popularfx_edit_links',
				'description' => $html,
				'settings' => 'popularfx_header_bg_color',
				'label' => __('Header Background Color', 'popularfx' )
			)
		) );
		
		//Site title font size
		$wp_customize->add_setting( 'popularfx_site_title_size', array(
			'capability' => 'edit_theme_options',
			'default' => 30,
			'transport' => 'refresh',
			'sanitize_callback' => 'absint',
		) );
		
		$wp_customize->add_control( 'popularfx_site_title_size', array(
			'type' => 'number',
			'section' => 'popularfx_edit_links',
			'settings' => 'popularfx_site_title_size',
			'label' => __( 'Font size', 'popularfx' ),
			'description' => __( 'Change font size of site title', 'popularfx' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 200,
				'step' => 1,
			),
		) );
				
		$wp_customize->add_setting('popularfx_site_title_color', array(
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
			
		$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
			$wp_customize, 'popularfx_site_title_color', array(
				'section' => 'popularfx_edit_links',
				'settings' => 'popularfx_site_title_color',
				'label' => __('Site Title Color', 'popularfx' )
			)
		) );
		
		//Tagline font size
		$wp_customize->add_setting( 'popularfx_tagline_size', array(
			'capability' => 'edit_theme_options',
			'default' => 15,
			'transport' => 'refresh',
			'sanitize_callback' => 'absint',
		) );
		
		$wp_customize->add_control( 'popularfx_tagline_size', array(
			'type' => 'number',
			'section' => 'popularfx_edit_links',
			'settings' => 'popularfx_tagline_size',
			'label' => __( 'Font size', 'popularfx' ),
			'description' => __( 'Change font size of site tagline', 'popularfx' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 200,
				'step' => 1,
			),
		) );
		
		//Tagline font size
		$wp_customize->add_setting( 'popularfx_tagline_size', array(
			'capability' => 'edit_theme_options',
			'default' => 15,
			'transport' => 'refresh',
			'sanitize_callback' => 'absint',
		) );
		
		$wp_customize->add_control( 'popularfx_tagline_size', array(
			'type' => 'number',
			'section' => 'popularfx_edit_links',
			'settings' => 'popularfx_tagline_size',
			'label' => __( 'Font size', 'popularfx' ),
			'description' => __( 'Change font size of site description', 'popularfx' ),
			'input_attrs' => array(
				'min' => 0,
				'max' => 200,
				'step' => 1,
			),
		) );
		
		$wp_customize->add_setting('popularfx_site_tagline_color', array(
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
			
		$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
			$wp_customize, 'popularfx_site_tagline_color', array(
				'section' => 'popularfx_edit_links',
				'settings' => 'popularfx_site_tagline_color',
				'label' => __('Tagline Color', 'popularfx' )
			)
		) );
		
		// Footer text
		$wp_customize->add_setting( 'popularfx_footer_text', array(
			'capability' => 'edit_theme_options',
			'default' => 'Proudly powered by WordPress | PopularFX Theme',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control( 'popularfx_footer_text', array(
			'type' => 'text',
			'section' => 'popularfx_edit_links',
			'settings' => 'popularfx_footer_text',
			'label' => __( 'Footer Text / HTML', 'popularfx' ),
			'description' => __( 'Add any text to your footer. e.g. your copyright - &copy; Site Name', 'popularfx' ),
		) );
		
		$wp_customize->add_setting('popularfx_footer_bg_color', array(
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
			) 
		);
		
		$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
			$wp_customize, 'popularfx_footer_bg_color', array(
				'section' => 'popularfx_edit_links',
				'settings' => 'popularfx_footer_bg_color',
				'label' => __('Footer Background Color', 'popularfx' )
			)
		) );

	}
	
	$wp_customize->add_panel( 'popularfx_global', array(
			'capability' => 'edit_theme_options',
			'priority'   => 2,
			'title'      => __( 'Global','popularfx')
	) );
		
	$wp_customize->add_panel( 'typography', array(
		'capability' => 'edit_theme_options',
		'title' => __( 'Typography', 'popularfx'),
		'priority' => 2
	) );
	
	// Load the options
	//pagelayer_load_font_options();
	
	// Create the sections
	popularfx_customize_font('body', 'Body', $wp_customize);
	popularfx_customize_font('p', 'Paragraph', $wp_customize);
	popularfx_customize_font('h1', 'H1', $wp_customize);
	popularfx_customize_font('h2', 'H2', $wp_customize);
	popularfx_customize_font('h3', 'H3', $wp_customize);
	popularfx_customize_font('h4', 'H4', $wp_customize);
	popularfx_customize_font('h5', 'H5', $wp_customize);
	popularfx_customize_font('h6', 'H6', $wp_customize);
	//popularfx_customize_font('button', 'Button', $wp_customize);
	
	
	//---------------------------------
	// Colors
	//---------------------------------
	$wp_customize->add_section( 'popularfx_colors', array(
		'capability' => 'edit_theme_options',
		'priority'   => 2,
		'title'      => __( 'Colors','popularfx'),
		'panel'      => 'popularfx_global'
	) );
		
	// BG Color
	$wp_customize->add_setting( 'popularfx_color[background]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'popularfx_color[background]', array(
			'priority' => 2,
			'section' => 'popularfx_colors',
			'settings' => 'popularfx_color[background]',
			'label' => __( 'Background Color', 'popularfx' ),
		) 
	) );
	
	// text Color
	$wp_customize->add_setting( 'popularfx_color[text]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'popularfx_color[text]', array(
			'priority' => 2,
			'section' => 'popularfx_colors',
			'settings' => 'popularfx_color[text]',
			'label' => __( 'Text Color', 'popularfx' ),
		) 
	) );
		
	// link Color
	$wp_customize->add_setting( 'popularfx_color[link]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'popularfx_color[link]', array(
			'priority' => 2,
			'section' => 'popularfx_colors',
			'settings' => 'popularfx_color[link]',
			'label' => __( 'Link Color', 'popularfx' ),
		) 
	) );
		
	// link-hover Color
	$wp_customize->add_setting( 'popularfx_color[link-hover]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'popularfx_color[link-hover]', array(
			'priority' => 2,
			'section' => 'popularfx_colors',
			'settings' => 'popularfx_color[link-hover]',
			'label' => __( 'Link Hover Color', 'popularfx' ),
		) 
	) );
		
	// heading Color
	$wp_customize->add_setting( 'popularfx_color[heading]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'popularfx_color[heading]', array(
			'priority' => 2,
			'section' => 'popularfx_colors',
			'settings' => 'popularfx_color[heading]',
			'label' => __( 'Heading Color (H1-H6)', 'popularfx' ),
		) 
	) );
	
	//---------------------------------
	// Sidebar
	//---------------------------------
	$wp_customize->add_section( 'popularfx_sidebar', array(
		'capability' => 'edit_theme_options',
		'priority'   => 2,
		'title'      => __( 'Sidebar','popularfx')
	) );
		
	// Default Sidebar
	$wp_customize->add_setting( 'popularfx_sidebar_default', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'sanitize_text_field',
	) );
		
	$wp_customize->add_control( 'popularfx_sidebar_default', array(
		'priority' => 1,
		'type' => 'select',
		'section' => 'popularfx_sidebar',
		'settings' => 'popularfx_sidebar_default',
		'label' => __( 'Default Sidebar', 'popularfx' ),
		'description' => __( 'Default layout for the Sidebar throughout the site', 'popularfx' ),
		'choices' => array(
			'0' => __( 'No Sidebar', 'popularfx' ),
			'left' => __( 'Left Sidebar', 'popularfx' ),
			'right' => __( 'Right Sidebar', 'popularfx' ),
		),
	) );
		
	// Page Sidebar
	$wp_customize->add_setting( 'popularfx_sidebar_page', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'default',
		'sanitize_callback' => 'sanitize_text_field',
		) );
		
	$wp_customize->add_control( 'popularfx_sidebar_page', array(
		'type' => 'select',
		'section' => 'popularfx_sidebar',
		'settings' => 'popularfx_sidebar_page',
		'label' => __( 'Page Sidebar', 'popularfx' ),
		'choices' => array(
			'default' => __( 'Default', 'popularfx' ),
			'0' => __( 'No Sidebar', 'popularfx' ),
			'left' => __( 'Left Sidebar', 'popularfx' ),
			'right' => __( 'Right Sidebar', 'popularfx' ),
		),
	) );
		
	// Posts Sidebar
	$wp_customize->add_setting( 'popularfx_sidebar_post', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'right',
		'sanitize_callback' => 'sanitize_text_field',
		) );
		
	$wp_customize->add_control( 'popularfx_sidebar_post', array(
		'type' => 'select',
		'section' => 'popularfx_sidebar',
		'settings' => 'popularfx_sidebar_post',
		'label' => __( 'Post Sidebar', 'popularfx' ),
		'choices' => array(
			'default' => __( 'Default', 'popularfx' ),
			'0' => __( 'No Sidebar', 'popularfx' ),
			'left' => __( 'Left Sidebar', 'popularfx' ),
			'right' => __( 'Right Sidebar', 'popularfx' ),
		),
	) );
		
	// Archives Sidebar
	$wp_customize->add_setting( 'popularfx_sidebar_archives', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'right',
		'sanitize_callback' => 'sanitize_text_field',
		) );
		
	$wp_customize->add_control( 'popularfx_sidebar_archives', array(
		'type' => 'select',
		'section' => 'popularfx_sidebar',
		'settings' => 'popularfx_sidebar_archives',
		'label' => __( 'Archives Sidebar', 'popularfx' ),
		'choices' => array(
			'default' => __( 'Default', 'popularfx' ),
			'0' => __( 'No Sidebar', 'popularfx' ),
			'left' => __( 'Left Sidebar', 'popularfx' ),
			'right' => __( 'Right Sidebar', 'popularfx' ),
		),
	) );
	
	// Is WooCommerce Enabled
	if(class_exists( 'WooCommerce' )){
	
		// Woocommerce Sidebar
		$wp_customize->add_setting( 'popularfx_sidebar_woocommerce', array(
			'capability' => 'edit_theme_options',
			'transport' => 'refresh',
			'default' => 0,
			'sanitize_callback' => 'sanitize_text_field',
			) );
		
		$wp_customize->add_control( 'popularfx_sidebar_woocommerce', array(
			'type' => 'select',
			'section' => 'popularfx_sidebar',
			'settings' => 'popularfx_sidebar_woocommerce',
			'label' => __( 'Woocommerce Shop Page Sidebar', 'popularfx' ),
			'choices' => array(
				'default' => __( 'Default', 'popularfx' ),
				'0' => __( 'No Sidebar', 'popularfx' ),
				'left' => __( 'Left Sidebar', 'popularfx' ),
				'right' => __( 'Right Sidebar', 'popularfx' ),
			),
		) );
	
	}
		
	// Sidebar Width
	$wp_customize->add_setting( 'popularfx_sidebar_width', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 20,
		'sanitize_callback' => 'absint',
	) );
		
	$wp_customize->add_control( 'popularfx_sidebar_width', array(
		'type' => 'number',
		'section' => 'popularfx_sidebar',
		'settings' => 'popularfx_sidebar_width',
		'label' => __( 'Sidebar Width', 'popularfx' ),
		'description' => __( 'Set the width of the Sidebar in percentage','popularfx'),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
		
	//---------------------------------
	// Scroll to Top
	//---------------------------------
	
	$wp_customize->add_section( 'popularfx_scrolltop', array(
		'capability' => 'edit_theme_options',
		'priority'   => 5,
		'title'      => __( 'Scroll to Top Button','popularfx'),
		'panel'      => 'popularfx_global'
	) );
	
	$wp_customize->add_setting( 'pfx_enable_scrolltop', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'pfx_enable_scrolltop', array(
		'type' => 'checkbox',
		'section' => 'popularfx_scrolltop',
		'priority' => 1,
		'settings' => 'pfx_enable_scrolltop',
		'label' => __('Enable Scroll to Top', 'popularfx' ),
		'input_attrs' => array(
			'class' => 'popularfx-customizer-checkbox',
			'style' => 'border: 1px solid #900',
		),
	) );
	
	$wp_customize->add_setting( 'pfx_scrolltop_position', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 95,
	) );
		
	$wp_customize->add_control( 'pfx_scrolltop_position', array(
		'type' => 'number',
		'section' => 'popularfx_scrolltop',
		'settings' => 'pfx_scrolltop_position',
		'label' => __( 'Button Position ', 'popularfx' ),
		'description' => __( 'Set button position for scroll top button in %', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting( 'pfx_scrolltop_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 10,
	) );
		
	$wp_customize->add_control( 'pfx_scrolltop_padding', array(
		'type' => 'number',
		'section' => 'popularfx_scrolltop',
		'settings' => 'pfx_scrolltop_padding',
		'label' => __( 'Button Spacing ', 'popularfx' ),
		'description' => __( 'Set button spacing for scroll top button in px', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting( 'pfx_scrolltop_iconsize', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 15,
	) );
		
	$wp_customize->add_control( 'pfx_scrolltop_iconsize', array(
		'type' => 'number',
		'section' => 'popularfx_scrolltop',
		'settings' => 'pfx_scrolltop_iconsize',
		'label' => __( 'Icon Size', 'popularfx' ),
		'description' => __( 'Set icon size for scroll top button in px', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting( 'pfx_scrolltop_borderwidth', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 2,
	) );
		
	$wp_customize->add_control( 'pfx_scrolltop_borderwidth', array(
		'type' => 'number',
		'section' => 'popularfx_scrolltop',
		'settings' => 'pfx_scrolltop_borderwidth',
		'label' => __( 'Border Width', 'popularfx' ),
		'description' => __( 'Set border width for scroll top button in px', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting( 'pfx_scrolltop_borderradius', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 15,
	) );
		
	$wp_customize->add_control( 'pfx_scrolltop_borderradius', array(
		'type' => 'number',
		'section' => 'popularfx_scrolltop',
		'settings' => 'pfx_scrolltop_borderradius',
		'label' => __( 'Border Radius', 'popularfx' ),
		'description' => __( 'Set border radius for scroll top button in px', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting('pfx_scrolltop_bg_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_scrolltop_bg_color', array(
			'section' => 'popularfx_scrolltop',
			'description' => 'Set background color for scrolltop',
			'settings' => 'pfx_scrolltop_bg_color',
			'label' => __('Background Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_scrolltop_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	));
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_scrolltop_color', array(
			'section' => 'popularfx_scrolltop',
			'description' => 'Set icon color for scrolltop',
			'settings' => 'pfx_scrolltop_color',
			'label' => __('Icon Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_scrolltop_bg_hover_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_scrolltop_bg_hover_color', array(
			'section' => 'popularfx_scrolltop',
			'description' => 'Set hover background color for scrolltop',
			'settings' => 'pfx_scrolltop_bg_hover_color',
			'label' => __('OnHover Background Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_scrolltop_hover_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_scrolltop_hover_color', array(
			'section' => 'popularfx_scrolltop',
			'description' => 'Set hover color for scrolltop',
			'settings' => 'pfx_scrolltop_hover_color',
			'label' => __('OnHover Icon Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_scrolltop_border_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_scrolltop_border_color', array(
			'section' => 'popularfx_scrolltop',
			'description' => 'Set border color for scrolltop',
			'settings' => 'pfx_scrolltop_border_color',
			'label' => __('Border Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_scrolltop_hover_border_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_scrolltop_hover_border_color', array(
			'section' => 'popularfx_scrolltop',
			'description' => 'Set hover border color for scrolltop',
			'settings' => 'pfx_scrolltop_hover_border_color',
			'label' => __('OnHover Border Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	//---------------------------------
	// Container
	//---------------------------------
	$wp_customize->add_section( 'popularfx_container', array(
		'capability' => 'edit_theme_options',
		'priority'   => 5,
		'title'      => __( 'Container', 'popularfx'),
		'panel'      => 'popularfx_global'
	) );
		
	// Container Width
	$wp_customize->add_setting( 'popularfx_content_width', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => get_option('pagelayer_content_width', 1170),
		'sanitize_callback' => 'absint',
		) );
		
	$wp_customize->add_control( 'popularfx_content_width', array(
		'type' => 'number',
		'section' => 'popularfx_container',
		'settings' => 'popularfx_content_width',
		'label' => __( 'Content Width', 'popularfx' ),
		'description' => __( 'The width of the content container. Default is 1170px', 'popularfx' ),
		'input_attrs' => array(
			'min' => 800,
			'step' => 1,
			'placeholder' => 1170
		),
	) );
		
	// Tablet Breakpoint
	$wp_customize->add_setting( 'popularfx_tablet_breakpoint', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => get_option('pagelayer_tablet_breakpoint', 768),
		'sanitize_callback' => 'absint',
		) );
		
	$wp_customize->add_control( 'popularfx_tablet_breakpoint', array(
		'type' => 'number',
		'section' => 'popularfx_container',
		'settings' => 'popularfx_tablet_breakpoint',
		'label' => __( 'Tablet Breakpoint', 'popularfx' ),
		'description' => __( 'Set the breakpoint for tablet devices. The default breakpoint for tablet layout is 768px.','popularfx'),
		'input_attrs' => array(
			'min' => 500,
			'step' => 1,
			'placeholder' => 768
		),
	) );
		
	// Mobile Breakpoint
	$wp_customize->add_setting( 'popularfx_mobile_breakpoint', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => get_option('pagelayer_mobile_breakpoint', 360),
		'sanitize_callback' => 'absint',
		) );
		
	$wp_customize->add_control( 'popularfx_mobile_breakpoint', array(
		'type' => 'number',
		'section' => 'popularfx_container',
		'settings' => 'popularfx_mobile_breakpoint',
		'label' => __( 'Mobile Breakpoint', 'popularfx' ),
		'description' => __( 'Set the breakpoint for mobile devices. The default breakpoint for mobile layout is 360px.','popularfx'),
		'input_attrs' => array(
			'min' => 200,
			'step' => 1,
			'placeholder' => 360
		),
	) );

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'popularfx_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'popularfx_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'popularfx_customize_register', 10);

// Shows the font settings
function popularfx_customize_font($prefix, $text, &$wp_customize){
	
	global $pagelayer, $pl_error;
	
	$popularfx_styles['font-weight'] = ['100', '200', '300', '400', '500', '600', '700', '800', '900', 'normal', 'lighter', 'bold', 'bolder', 'inherit'];
	
	$popularfx_styles['transform'] = ['inherit', 'initial', 'capitalize', 'uppercase', 'lowercase', 'none'];
	
	$popularfx_styles['font-family'] = ['Inherit','ABeeZee', 'Abel', 'Abhaya Libre', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro', 'Aguafina Script', 'Akaya Kanadaka', 'Akaya Telivigala', 'Akronim', 'Aladin', 'Alata', 'Alatsi', 'Aldrich', 'Alef', 'Alegreya', 'Alegreya Sans', 'Alegreya Sans SC', 'Alegreya SC', 'Aleo', 'Alex Brush', 'Alfa Slab One', 'Alice', 'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allison', 'Allura', 'Almarai', 'Almendra', 'Almendra Display', 'Almendra SC', 'Alumni Sans', 'Amarante', 'Amaranth', 'Amatic SC', 'Amethysta', 'Amiko', 'Amiri', 'Amita', 'Anaheim', 'Andada Pro', 'Andika', 'Andika New Basic', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic', 'Antic Didone', 'Antic Slab', 'Anton', 'Antonio', 'Arapey', 'Arbutus', 'Arbutus Slab', 'Architects Daughter', 'Archivo', 'Archivo Black', 'Archivo Narrow', 'Are You Serious', 'Aref Ruqaa', 'Arima Madurai', 'Arimo', 'Arizonia', 'Armata', 'Arsenal', 'Artifika', 'Arvo', 'Arya', 'Asap', 'Asap Condensed', 'Asar', 'Asset', 'Assistant', 'Astloch', 'Asul', 'Athiti', 'Atkinson Hyperlegible', 'Atma', 'Atomic Age', 'Aubrey', 'Audiowide', 'Autour One', 'Average', 'Average Sans', 'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Azeret Mono', 'B612', 'B612 Mono', 'Bad Script', 'Bahiana', 'Bahianita', 'Bai Jamjuree', 'Ballet', 'Baloo 2', 'Baloo Bhai 2', 'Baloo Bhaina 2', 'Baloo Chettan 2', 'Baloo Da 2', 'Baloo Paaji 2', 'Baloo Tamma 2', 'Baloo Tammudu 2', 'Baloo Thambi 2', 'Balsamiq Sans', 'Balthazar', 'Bangers', 'Barlow', 'Barlow Condensed', 'Barlow Semi Condensed', 'Barriecito', 'Barrio', 'Basic', 'Baskervville', 'Battambang', 'Baumans', 'Bayon', 'Be Vietnam', 'Be Vietnam Pro', 'Bebas Neue', 'Belgrano', 'Bellefair', 'Belleza', 'Bellota', 'Bellota Text', 'BenchNine', 'Benne', 'Bentham', 'Berkshire Swash', 'Besley', 'Beth Ellen', 'Bevan', 'Big Shoulders Display', 'Big Shoulders Inline Display', 'Big Shoulders Inline Text', 'Big Shoulders Stencil Display', 'Big Shoulders Stencil Text', 'Big Shoulders Text', 'Bigelow Rules', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'BioRhyme', 'BioRhyme Expanded', 'Birthstone', 'Birthstone Bounce', 'Biryani', 'Bitter', 'Black And White Picture', 'Black Han Sans', 'Black Ops One', 'Blinker', 'Bodoni Moda', 'Bokor', 'Bona Nova', 'Bonbon', 'Bonheur Royale', 'Boogaloo', 'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Brygada 1918', 'Bubblegum Sans', 'Bubbler One', 'Buda', 'Buenard', 'Bungee', 'Bungee Hairline', 'Bungee Inline', 'Bungee Outline', 'Bungee Shade', 'Butcherman', 'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Cairo', 'Caladea', 'Calistoga', 'Calligraffitti', 'Cambay', 'Cambo', 'Candal', 'Cantarell', 'Cantata One', 'Cantora One', 'Capriola', 'Caramel', 'Carattere', 'Cardo', 'Carme', 'Carrois Gothic', 'Carrois Gothic SC', 'Carter One', 'Castoro', 'Catamaran', 'Caudex', 'Caveat', 'Caveat Brush', 'Cedarville Cursive', 'Ceviche One', 'Chakra Petch', 'Changa', 'Changa One', 'Chango', 'Charm', 'Charmonman', 'Chathura', 'Chau Philomene One', 'Chela One', 'Chelsea Market', 'Chenla', 'Cherish', 'Cherry Cream Soda', 'Cherry Swash', 'Chewy', 'Chicle', 'Chilanka', 'Chivo', 'Chonburi', 'Cinzel', 'Cinzel Decorative', 'Clicker Script', 'Coda', 'Coda Caption', 'Codystar', 'Coiny', 'Combo', 'Comfortaa', 'Comic Neue', 'Coming Soon', 'Commissioner', 'Concert One', 'Condiment', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Cormorant', 'Cormorant Garamond', 'Cormorant Infant', 'Cormorant SC', 'Cormorant Unicase', 'Cormorant Upright', 'Courgette', 'Courier Prime', 'Cousine', 'Coustard', 'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Crete Round', 'Crimson Pro', 'Crimson Text', 'Croissant One', 'Crushed', 'Cuprum', 'Cute Font', 'Cutive', 'Cutive Mono', 'Damion', 'Dancing Script', 'Dangrek', 'Darker Grotesque', 'David Libre', 'Dawning of a New Day', 'Days One', 'Dekko', 'Dela Gothic One', 'Delius', 'Delius Swash Caps', 'Delius Unicase', 'Della Respira', 'Denk One', 'Devonshire', 'Dhurjati', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'DM Mono', 'DM Sans', 'DM Serif Display', 'DM Serif Text', 'Do Hyeon', 'Dokdo', 'Domine', 'Donegal One', 'Doppio One', 'Dorsa', 'Dosis', 'DotGothic16', 'Dr Sugiyama', 'Duru Sans', 'Dynalight', 'Eagle Lake', 'East Sea Dokdo', 'Eater', 'EB Garamond', 'Economica', 'Eczar', 'El Messiri', 'Electrolize', 'Elsie', 'Elsie Swash Caps', 'Emblema One', 'Emilys Candy', 'Encode Sans', 'Encode Sans Condensed', 'Encode Sans Expanded', 'Encode Sans SC', 'Encode Sans Semi Condensed', 'Encode Sans Semi Expanded', 'Engagement', 'Englebert', 'Enriqueta', 'Ephesis', 'Epilogue', 'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Exo 2', 'Expletus Sans', 'Explora', 'Fahkwang', 'Fanwood Text', 'Farro', 'Farsan', 'Fascinate', 'Fascinate Inline', 'Faster One', 'Fasthand', 'Fauna One', 'Faustina', 'Federant', 'Federo', 'Felipa', 'Fenix', 'Festive', 'Finger Paint', 'Fira Code', 'Fira Mono', 'Fira Sans', 'Fira Sans Condensed', 'Fira Sans Extra Condensed', 'Fjalla One', 'Fjord One', 'Flamenco', 'Flavors', 'Fleur De Leah', 'Fondamento', 'Fontdiner Swanky', 'Forum', 'Francois One', 'Frank Ruhl Libre', 'Fraunces', 'Freckle Face', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fruktur', 'Fugaz One', 'Fuggles', 'Gabriela', 'Gaegu', 'Gafata', 'Galada', 'Galdeano', 'Galindo', 'Gamja Flower', 'Gayathri', 'Gelasio', 'Gemunu Libre', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Georama', 'Geostar', 'Geostar Fill', 'Germania One', 'GFS Didot', 'GFS Neohellenic', 'Gideon Roman', 'Gidugu', 'Gilda Display', 'Girassol', 'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Glory', 'Gluten', 'Goblin One', 'Gochi Hand', 'Goldman', 'Gorditas', 'Gothic A1', 'Gotu', 'Goudy Bookletter 1911', 'Gowun Batang', 'Gowun Dodum', 'Graduate', 'Grand Hotel', 'Grandstander', 'Gravitas One', 'Great Vibes', 'Grechen Fuemen', 'Grenze', 'Grenze Gotisch', 'Grey Qo', 'Griffy', 'Gruppo', 'Gudea', 'Gugi', 'Gupter', 'Gurajada', 'Habibi', 'Hachi Maru Pop', 'Hahmlet', 'Halant', 'Hammersmith One', 'Hanalei', 'Hanalei Fill', 'Handlee', 'Hanuman', 'Happy Monkey', 'Harmattan', 'Headland One', 'Heebo', 'Henny Penny', 'Hepta Slab', 'Herr Von Muellerhoff', 'Hi Melody', 'Hina Mincho', 'Hind', 'Hind Guntur', 'Hind Madurai', 'Hind Siliguri', 'Hind Vadodara', 'Holtwood One SC', 'Homemade Apple', 'Homenaje', 'Ibarra Real Nova', 'IBM Plex Mono', 'IBM Plex Sans', 'IBM Plex Sans Arabic', 'IBM Plex Sans Condensed', 'IBM Plex Sans Devanagari', 'IBM Plex Sans Hebrew', 'IBM Plex Sans KR', 'IBM Plex Sans Thai', 'IBM Plex Sans Thai Looped', 'IBM Plex Serif', 'Iceberg', 'Iceland', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Imbue', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika', 'Inknut Antiqua', 'Inria Sans', 'Inria Serif', 'Inter', 'Irish Grover', 'Istok Web', 'Italiana', 'Italianno', 'Itim', 'Jacques Francois', 'Jacques Francois Shadow', 'Jaldi', 'JetBrains Mono', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Jomhuria', 'Jomolhari', 'Josefin Sans', 'Josefin Slab', 'Jost', 'Joti One', 'Jua', 'Judson', 'Julee', 'Julius Sans One', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'K2D', 'Kadwa', 'Kaisei Decol', 'Kaisei HarunoUmi', 'Kaisei Opti', 'Kaisei Tokumin', 'Kalam', 'Kameron', 'Kanit', 'Kantumruy', 'Karantina', 'Karla', 'Karma', 'Katibeh', 'Kaushan Script', 'Kavivanar', 'Kavoon', 'Kdam Thmor', 'Keania One', 'Kelly Slab', 'Kenia', 'Khand', 'Khmer', 'Khula', 'Kirang Haerang', 'Kite One', 'Kiwi Maru', 'Klee One', 'Knewave', 'Kodchasan', 'Koh Santepheap', 'KoHo', 'Kosugi', 'Kosugi Maru', 'Kotta One', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Krona One', 'Krub', 'Kufam', 'Kulim Park', 'Kumar One', 'Kumar One Outline', 'Kumbh Sans', 'Kurale', 'La Belle Aurore', 'Lacquer', 'Laila', 'Lakki Reddy', 'Lalezar', 'Lancelot', 'Langar', 'Lateef', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton', 'Lemon', 'Lemonada', 'Lexend', 'Lexend Deca', 'Lexend Exa', 'Lexend Giga', 'Lexend Mega', 'Lexend Peta', 'Lexend Tera', 'Lexend Zetta', 'Libre Barcode 128', 'Libre Barcode 128 Text', 'Libre Barcode 39', 'Libre Barcode 39 Extended', 'Libre Barcode 39 Extended Text', 'Libre Barcode 39 Text', 'Libre Barcode EAN13 Text', 'Libre Baskerville', 'Libre Caslon Display', 'Libre Caslon Text', 'Libre Franklin', 'Life Savers', 'Lilita One', 'Lily Script One', 'Limelight', 'Linden Hill', 'Literata', 'Liu Jian Mao Cao', 'Livvic', 'Lobster', 'Lobster Two', 'Londrina Outline', 'Londrina Shadow', 'Londrina Sketch', 'Londrina Solid', 'Long Cang', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel', 'Luckiest Guy', 'Lusitana', 'Lustria', 'M PLUS 1p', 'M PLUS Rounded 1c', 'Ma Shan Zheng', 'Macondo', 'Macondo Swash Caps', 'Mada', 'Magra', 'Maiden Orange', 'Maitree', 'Major Mono Display', 'Mako', 'Mali', 'Mallanna', 'Mandali', 'Manjari', 'Manrope', 'Mansalva', 'Manuale', 'Marcellus', 'Marcellus SC', 'Marck Script', 'Margarine', 'Markazi Text', 'Marko One', 'Marmelad', 'Martel', 'Martel Sans', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'McLaren', 'Meddon', 'MedievalSharp', 'Medula One', 'Meera Inimai', 'Megrim', 'Meie Script', 'Merienda', 'Merienda One', 'Merriweather', 'Merriweather Sans', 'Metal', 'Metal Mania', 'Metamorphous', 'Metrophobic', 'Michroma', 'Milonga', 'Miltonian', 'Miltonian Tattoo', 'Mina', 'Miniver', 'Miriam Libre', 'Mirza', 'Miss Fajardose', 'Mitr', 'Modak', 'Modern Antiqua', 'Mogra', 'Molengo', 'Molle', 'Monda', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'MonteCarlo', 'Montez', 'Montserrat', 'Montserrat Alternates', 'Montserrat Subrayada', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Mouse Memoirs', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards', 'Mukta', 'Mukta Mahee', 'Mukta Malar', 'Mukta Vaani', 'Mulish', 'MuseoModerno', 'Mystery Quest', 'Nanum Brush Script', 'Nanum Gothic', 'Nanum Gothic Coding', 'Nanum Myeongjo', 'Nanum Pen Script', 'Nerko One', 'Neucha', 'Neuton', 'New Rocker', 'New Tegomin', 'News Cycle', 'Newsreader', 'Niconne', 'Niramit', 'Nixie One', 'Nobile', 'Nokora', 'Norican', 'Nosifer', 'Notable', 'Nothing You Could Do', 'Noticia Text', 'Noto Kufi Arabic', 'Noto Music', 'Noto Naskh Arabic', 'Noto Nastaliq Urdu', 'Noto Rashi Hebrew', 'Noto Sans', 'Noto Sans Adlam', 'Noto Sans Adlam Unjoined', 'Noto Sans Anatolian Hieroglyphs', 'Noto Sans Arabic', 'Noto Sans Armenian', 'Noto Sans Avestan', 'Noto Sans Balinese', 'Noto Sans Bamum', 'Noto Sans Bassa Vah', 'Noto Sans Batak', 'Noto Sans Bengali', 'Noto Sans Bhaiksuki', 'Noto Sans Brahmi', 'Noto Sans Buginese', 'Noto Sans Buhid', 'Noto Sans Canadian Aboriginal', 'Noto Sans Carian', 'Noto Sans Caucasian Albanian', 'Noto Sans Chakma', 'Noto Sans Cham', 'Noto Sans Cherokee', 'Noto Sans Coptic', 'Noto Sans Cuneiform', 'Noto Sans Cypriot', 'Noto Sans Deseret', 'Noto Sans Devanagari', 'Noto Sans Display', 'Noto Sans Duployan', 'Noto Sans Egyptian Hieroglyphs', 'Noto Sans Elbasan', 'Noto Sans Elymaic', 'Noto Sans Georgian', 'Noto Sans Glagolitic', 'Noto Sans Gothic', 'Noto Sans Grantha', 'Noto Sans Gujarati', 'Noto Sans Gunjala Gondi', 'Noto Sans Gurmukhi', 'Noto Sans Hanifi Rohingya', 'Noto Sans Hanunoo', 'Noto Sans Hatran', 'Noto Sans Hebrew', 'Noto Sans Hong Kong', 'Noto Sans Imperial Aramaic', 'Noto Sans Indic Siyaq Numbers', 'Noto Sans Inscriptional Pahlavi', 'Noto Sans Inscriptional Parthian', 'Noto Sans Japanese', 'Noto Sans Javanese', 'Noto Sans Kaithi', 'Noto Sans Kannada', 'Noto Sans Kayah Li', 'Noto Sans Kharoshthi', 'Noto Sans Khmer', 'Noto Sans Khojki', 'Noto Sans Khudawadi', 'Noto Sans Korean', 'Noto Sans Lao', 'Noto Sans Lepcha', 'Noto Sans Limbu', 'Noto Sans Linear A', 'Noto Sans Linear B', 'Noto Sans Lisu', 'Noto Sans Lycian', 'Noto Sans Lydian', 'Noto Sans Mahajani', 'Noto Sans Malayalam', 'Noto Sans Mandaic', 'Noto Sans Manichaean', 'Noto Sans Marchen', 'Noto Sans Masaram Gondi', 'Noto Sans Math', 'Noto Sans Mayan Numerals', 'Noto Sans Medefaidrin', 'Noto Sans Meroitic', 'Noto Sans Miao', 'Noto Sans Modi', 'Noto Sans Mongolian', 'Noto Sans Mono', 'Noto Sans Mro', 'Noto Sans Multani', 'Noto Sans Myanmar', 'Noto Sans Nabataean', 'Noto Sans New Tai Lue', 'Noto Sans Newa', 'Noto Sans Nüshu', 'Noto Sans Ogham', 'Noto Sans Ol Chiki', 'Noto Sans Old Hungarian', 'Noto Sans Old Italic', 'Noto Sans Old North Arabian', 'Noto Sans Old Permic', 'Noto Sans Old Persian', 'Noto Sans Old Sogdian', 'Noto Sans Old South Arabian', 'Noto Sans Old Turkic', 'Noto Sans Oriya', 'Noto Sans Osage', 'Noto Sans Osmanya', 'Noto Sans Pahawh Hmong', 'Noto Sans Palmyrene', 'Noto Sans Pau Cin Hau', 'Noto Sans Phags Pa', 'Noto Sans Phoenician', 'Noto Sans Psalter Pahlavi', 'Noto Sans Rejang', 'Noto Sans Runic', 'Noto Sans Samaritan', 'Noto Sans Saurashtra', 'Noto Sans Sharada', 'Noto Sans Shavian', 'Noto Sans Siddham', 'Noto Sans Simplified Chinese', 'Noto Sans Sinhala', 'Noto Sans Sogdian', 'Noto Sans Sora Sompeng', 'Noto Sans Soyombo', 'Noto Sans Sundanese', 'Noto Sans Syloti Nagri', 'Noto Sans Symbols', 'Noto Sans Symbols 2', 'Noto Sans Syriac', 'Noto Sans Tagalog', 'Noto Sans Tagbanwa', 'Noto Sans Tai Le', 'Noto Sans Tai Tham', 'Noto Sans Tai Viet', 'Noto Sans Takri', 'Noto Sans Tamil', 'Noto Sans Tamil Supplement', 'Noto Sans Telugu', 'Noto Sans Thaana', 'Noto Sans Thai', 'Noto Sans Thai Looped', 'Noto Sans Tifinagh', 'Noto Sans Tirhuta', 'Noto Sans Traditional Chinese', 'Noto Sans Ugaritic', 'Noto Sans Vai', 'Noto Sans Wancho', 'Noto Sans Warang Citi', 'Noto Sans Yi', 'Noto Sans Zanabazar Square', 'Noto Serif', 'Noto Serif Ahom', 'Noto Serif Armenian', 'Noto Serif Balinese', 'Noto Serif Bengali', 'Noto Serif Devanagari', 'Noto Serif Display', 'Noto Serif Dogra', 'Noto Serif Ethiopic', 'Noto Serif Georgian', 'Noto Serif Grantha', 'Noto Serif Gujarati', 'Noto Serif Gurmukhi', 'Noto Serif Hebrew', 'Noto Serif Japanese', 'Noto Serif Kannada', 'Noto Serif Khmer', 'Noto Serif KR', 'Noto Serif Lao', 'Noto Serif Malayalam', 'Noto Serif Myanmar', 'Noto Serif Nyiakeng Puachue Hmong', 'Noto Serif Simplified Chinese', 'Noto Serif Sinhala', 'Noto Serif Tamil', 'Noto Serif Tangut', 'Noto Serif Telugu', 'Noto Serif Thai', 'Noto Serif Tibetan', 'Noto Serif Traditional Chinese', 'Noto Serif Yezidi', 'Noto Traditional Nüshu', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'NTR', 'Numans', 'Nunito', 'Nunito Sans', 'Odibee Sans', 'Odor Mean Chey', 'Offside', 'Oi', 'Old Standard TT', 'Oldenburg', 'Oleo Script', 'Oleo Script Swash Caps', 'Open Sans', 'Open Sans Condensed', 'Oranienbaum', 'Orbitron', 'Oregano', 'Orelega One', 'Orienta', 'Original Surfer', 'Oswald', 'Otomanopee One', 'Over the Rainbow', 'Overlock', 'Overlock SC', 'Overpass', 'Overpass Mono', 'Ovo', 'Oxanium', 'Oxygen', 'Oxygen Mono', 'Pacifico', 'Padauk', 'Palanquin', 'Palanquin Dark', 'Palette Mosaic', 'Pangolin', 'Paprika', 'Parisienne', 'Passero One', 'Passion One', 'Pathway Gothic One', 'Patrick Hand', 'Patrick Hand SC', 'Pattaya', 'Patua One', 'Pavanam', 'Paytone One', 'Peddana', 'Peralta', 'Permanent Marker', 'Petit Formal Script', 'Petrona', 'Philosopher', 'Piazzolla', 'Piedra', 'Pinyon Script', 'Pirata One', 'Plaster', 'Play', 'Playball', 'Playfair Display', 'Playfair Display SC', 'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Poor Story', 'Poppins', 'Port Lligat Sans', 'Port Lligat Slab', 'Potta One', 'Pragati Narrow', 'Prata', 'Preahvihear', 'Press Start 2P', 'Pridi', 'Princess Sofia', 'Prociono', 'Prompt', 'Prosto One', 'Proza Libre', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Public Sans', 'Puritan', 'Purple Purse', 'Qahiri', 'Quando', 'Quantico', 'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Quintessential', 'Qwigley', 'Racing Sans One', 'Radley', 'Rajdhani', 'Rakkas', 'Raleway', 'Raleway Dots', 'Ramabhadra', 'Ramaraja', 'Rambla', 'Rammetto One', 'Rampart One', 'Ranchers', 'Rancho', 'Ranga', 'Rasa', 'Rationale', 'Ravi Prakash', 'Recursive', 'Red Hat Display', 'Red Hat Text', 'Red Rose', 'Redressed', 'Reem Kufi', 'Reenie Beanie', 'Reggae One', 'Revalia', 'Rhodium Libre', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Risque', 'Roboto', 'Roboto Condensed', 'Roboto Mono', 'Roboto Slab', 'Rochester', 'Rock Salt', 'RocknRoll One', 'Rokkitt', 'Romanesco', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Rowdies', 'Rozha One', 'Rubik', 'Rubik Beastly', 'Rubik Mono One', 'Ruda', 'Rufina', 'Ruge Boogie', 'Ruluko', 'Rum Raisin', 'Ruslan Display', 'Russo One', 'Ruthie', 'Rye', 'Sacramento', 'Sahitya', 'Sail', 'Saira', 'Saira Condensed', 'Saira Extra Condensed', 'Saira Semi Condensed', 'Saira Stencil One', 'Salsa', 'Sanchez', 'Sancreek', 'Sansita', 'Sansita Swashed', 'Sarabun', 'Sarala', 'Sarina', 'Sarpanch', 'Satisfy', 'Sawarabi Gothic', 'Sawarabi Mincho', 'Scada', 'Scheherazade', 'Scheherazade New', 'Schoolbell', 'Scope One', 'Seaweed Script', 'Secular One', 'Sedgwick Ave', 'Sedgwick Ave Display', 'Sen', 'Sevillana', 'Seymour One', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Share Tech', 'Share Tech Mono', 'Shippori Mincho', 'Shippori Mincho B1', 'Shojumaru', 'Short Stack', 'Shrikhand', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Single Day', 'Sintony', 'Sirin Stencil', 'Six Caps', 'Skranji', 'Slabo 13px', 'Slabo 27px', 'Slackey', 'Smokum', 'Smythe', 'Sniglet', 'Snippet', 'Snowburst One', 'Sofadi One', 'Sofia', 'Solway', 'Song Myung', 'Sonsie One', 'Sora', 'Sorts Mill Goudy', 'Source Code Pro', 'Source Sans Pro', 'Source Serif Pro', 'Space Grotesk', 'Space Mono', 'Spartan', 'Special Elite', 'Spectral', 'Spectral SC', 'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Sree Krushnadevaraya', 'Sriracha', 'Srisakdi', 'Staatliches', 'Stalemate', 'Stalinist One', 'Stardos Stencil', 'Stick', 'Stick No Bills', 'Stint Ultra Condensed', 'Stint Ultra Expanded', 'STIX Two Text', 'Stoke', 'Strait', 'Style Script', 'Stylish', 'Sue Ellen Francisco', 'Suez One', 'Sulphur Point', 'Sumana', 'Sunflower', 'Sunshiney', 'Supermercado One', 'Sura', 'Suranna', 'Suravaram', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Syne', 'Syne Mono', 'Syne Tactile', 'Tajawal', 'Tangerine', 'Taprom', 'Tauri', 'Taviraj', 'Teko', 'Telex', 'Tenali Ramakrishna', 'Tenor Sans', 'Text Me One', 'Texturina', 'Thasadith', 'The Girl Next Door', 'Tienne', 'Tillana', 'Timmana', 'Tinos', 'Titan One', 'Titillium Web', 'Tomorrow', 'Tourney', 'Trade Winds', 'Train One', 'Trirong', 'Trispace', 'Trocchi', 'Trochut', 'Truculenta', 'Trykker', 'Tulpen One', 'Turret Road', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Uchen', 'Ultra', 'Uncial Antiqua', 'Underdog', 'Unica One', 'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'Urbanist', 'Vampiro One', 'Varela', 'Varela Round', 'Varta', 'Vast Shadow', 'Vesper Libre', 'Viaoda Libre', 'Vibes', 'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Vollkorn SC', 'Voltaire', 'VT323', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Warnes', 'Wellfleet', 'Wendy One', 'WindSong', 'Wire One', 'Work Sans', 'Xanh Mono', 'Yaldevi', 'Yanone Kaffeesatz', 'Yantramanav', 'Yatra One', 'Yellowtail', 'Yeon Sung', 'Yeseva One', 'Yesteryear', 'Yomogi', 'Yrsa', 'Yusei Magic', 'ZCOOL KuaiLe', 'ZCOOL QingKe HuangYou', 'ZCOOL XiaoWei', 'Zen Dots', 'Zen Loop', 'Zen Tokyo Zoo', 'Zeyada', 'Zhi Mang Xing', 'Zilla Slab', 'Zilla Slab Highlight'];
	
		
	foreach($popularfx_styles['font-family'] as $k => $font){	
		$r[$font] = esc_attr($font);
	}
	
	$wp_customize->add_section( 'popularfx_'.$prefix.'_typo', array(
		'title' => $text,
		'panel' => 'typography',
	) );
	
	// Font Family
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[font-family]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
		
	$wp_customize->add_control( 'popularfx_'.$prefix.'[font-family]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[font-family]',
		'label' => __( 'Font Family', 'popularfx' ),
		'choices' => $r,
	) );
	
	$fsizes = [];
	$fsizes[0] = 'Default';
	for($i = 5; $i <= 75; $i++){
		$fsizes[$i] = esc_attr($i.'px');
	}
	
	// Font Size
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[font-size]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 20,
		'sanitize_callback' => 'absint',
		)
	);
		
	$wp_customize->add_control( 'popularfx_'.$prefix.'[font-size]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[font-size]',
		'label' => __( 'Font Size', 'popularfx' ),
		'choices' => $fsizes,
	) );
	
	// Font Style
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[font-style]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
		
	$wp_customize->add_control( 'popularfx_'.$prefix.'[font-style]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[font-style]',
		'label' => __( 'Font Style', 'popularfx' ),
		'choices' => array(
			'default' => __( 'Default', 'popularfx' ),
			'normal' => __( 'Normal', 'popularfx' ),
			'italic' => __( 'Italic', 'popularfx' ),
			'oblique' => __( 'Oblique', 'popularfx' )
		),
	) );
	
	$popularfx_weight = array();	
	foreach($popularfx_styles['font-weight'] as $k => $weight){
		$popularfx_weight[$weight] = esc_attr($weight);
	}

	// Font Weight
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[font-weight]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
		
	$wp_customize->add_control( 'popularfx_'.$prefix.'[font-weight]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[font-weight]',
		'label' => __( 'Font Weight', 'popularfx' ),
		'choices' => $popularfx_weight,
	) );
	
	$popularfx_transform = array();	
	foreach($popularfx_styles['transform'] as $k => $transform){
		$popularfx_transform[$transform] = esc_attr($transform);
	}
	
	// Text Transform
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[text-transform]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
		
	$wp_customize->add_control( 'popularfx_'.$prefix.'[text-transform]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[text-transform]',
		'label' => __( 'Text Transform', 'popularfx' ),
		'choices' => $popularfx_transform,
	) );
	
	
	$r = [];
	$r[0] = 'Default';
	for($i = 1; $i <= 50; $i++){
		$v = (string) round($i/10, 1);
		$r[$v] = esc_attr($v);
	}
	
	//pagelayer_print($r);
	
	// Line Height
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[line-height]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control( 'popularfx_'.$prefix.'[line-height]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[line-height]',
		'label' => __( 'Line Height', 'popularfx' ),
		'choices' => $r,
	) );
	
	// Text Spacing
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[letter-spacing]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control( 'popularfx_'.$prefix.'[letter-spacing]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[letter-spacing]',
		'label' => __( 'Text Spacing', 'popularfx' ),
		'choices' => $r,
	) );
	
	// Word Spacing
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[word-spacing]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	
	$wp_customize->add_control( 'popularfx_'.$prefix.'[word-spacing]', array(
		'type' => 'select',
		'section' => 'popularfx_'.$prefix.'_typo',
		'settings' => 'popularfx_'.$prefix.'[word-spacing]',
		'label' => __( 'Word Spacing', 'popularfx' ),
		'choices' => $r,
	) );
	
	//Font Colors
	$wp_customize->add_setting( 'popularfx_'.$prefix.'[color]', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 'inherit',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
		)
	);
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'popularfx_'.$prefix.'[color]', array(
			'section' => 'popularfx_'.$prefix.'_typo',
			'settings' => 'popularfx_'.$prefix.'[color]',
			'label' => __( 'Text Color', 'popularfx' )
		)
	) );
	
}

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function popularfx_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function popularfx_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function popularfx_customize_preview_js() {
	wp_enqueue_script( 'popularfx-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'popularfx_customize_preview_js' );


