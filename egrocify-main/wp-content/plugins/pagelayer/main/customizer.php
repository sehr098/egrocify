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

// Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
add_action( 'customize_preview_init', 'pagelayer_customize_preview_js' );
function pagelayer_customize_preview_js() {
	//wp_enqueue_script( 'pagelayer-customizer-preview', get_template_directory_uri() . '/js/customizer-preview.js', array( 'jquery', 'customize-preview' ), PAGELAYER_VERSION, true );
}

// JS handlers for controls.
add_action( 'customize_controls_enqueue_scripts', 'pagelayer_customize_scripts' );
function pagelayer_customize_scripts(){
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'pagelayer-customizer', PAGELAYER_JS.'/customizer.js', array( 'customize-controls' ), PAGELAYER_VERSION, true );
}

// Print global Style.
add_action( 'customize_controls_print_styles', 'pagelayer_customize_controls_print_styles' );
function pagelayer_customize_controls_print_styles(){
	global $pagelayer;
	
	$font_family = (array) $pagelayer->fonts;
	$style = array('' => 'Default', 'normal' => 'Normal', 'italic' => 'Italic', 'oblique' => 'Oblique');
	$weight = array('' => 'Default', '100' => '100', '200' => '200', '300' => '300', '400' => '400', '500' => '500', '600' => '600', '700' => '700', '800' => '800', '900' => '900', 'normal' => 'Normal', 'lighter' => 'Lighter', 'bold' => 'Bold', 'bolder' => 'Bolder', 'unset' => 'Unset');
	$variant = array('' => 'Default', 'normal' => 'Normal', 'small-caps' => 'Small Caps');
	$decoration = array('' => 'Default', 'none' => 'None', 'overline' => 'Overline', 'line-through' => 'Line-through', 'underline' => 'Underline', 'underline overline' => 'Underline Overline');
	$decoration_style = array('' => 'None', 'solid' => 'Solid', 'double' => 'Double', 'dotted' => 'Dotted', 'dashed' => 'Dashed', 'wavy' => 'Wavy');
	$transform = array('' => 'Default', 'capitalize' => 'Capitalize', 'uppercase' => 'Uppercase', 'lowercase' => 'Lowercase');
	
	$pagelayer->font_settings = array(
		'font-family' => array(
			'label' => __('Family', 'pagelayer'), 
			'choices' => $font_family
		),
		'font-size' => array(
			'label' => __('Size', 'pagelayer'),
			'responsive' => 1,
		),
		'font-style' => array(
			'label' => __('Style', 'pagelayer'), 
			'choices' => $style,
		),
		'font-weight' => array(
			'label' => __('Weight', 'pagelayer'), 
			'choices' => $weight,
			'responsive' => 1,
		),
		'font-variant' => array(
			'label' => __('Variant', 'pagelayer'), 
			'choices' => $variant,
		),
		'text-decoration-line' => array(
			'label' => __('Decoration', 'pagelayer'), 
			'choices' => $decoration,
		),
		'text-decoration-style' => array(
			'label' => __('Decoration Style', 'pagelayer'), 
			'choices' => $decoration_style,
		),
		'line-height' => array(
			'label' => __('Line Height', 'pagelayer'),
			'responsive' => 1,
		),
		'text-transform' => array(
			'label' => __('Transform', 'pagelayer'),
			'choices' => $transform,
		),
		'letter-spacing' => array(
			'label' => __('Text Spacing', 'pagelayer'),
			'responsive' => 1,
		),
		'word-spacing' => array(
			'label' => __('Word Spacing', 'pagelayer'),
			'responsive' => 1,
		),
	);
	
	$styles = '<style id="pagelayer-customize-global-style">:root{';
	
	// Set global colors styles
	foreach($pagelayer->global_colors as $gk => $gv){
		$styles .= '--pagelayer-color-'.$gk.':'.$gv['value'].';';
	}

	$styles .= '}
	</style>'.PHP_EOL;
	
	// Added global JavaSript variables
	$styles .= '<script id="pagelayer-customize-global-js">
		var pagelayer_global_colors = '.json_encode($pagelayer->global_colors).';
		var pagelayer_global_fonts = '.json_encode($pagelayer->global_fonts).';
		var pagelayer_global_font_settings = '.json_encode($pagelayer->font_settings).';
	</script>'.PHP_EOL;
	
	echo $styles;
}

add_action( 'customize_register', 'pagelayer_customize_register', 11 );
function pagelayer_customize_register( $wp_customize ) {
	global $pagelayer;
	
	// CSS for the custom controls
	wp_register_style('pagelayer-customizer', PAGELAYER_CSS.'/customizer.css', PAGELAYER_VERSION);
	wp_enqueue_style('pagelayer-customizer');
	
	// Load fonts
	pagelayer_load_font_options();
	
	// Load global colors and fonts
	pagelayer_load_global_palette();
	
	// Add custom controls
	include_once(PAGELAYER_DIR . '/main/customizer-controls.php');
	
	$post_types = array('' => __('Global'));
	$exclude = [ 'attachment', 'pagelayer-template' ];
	$pt_objects = get_post_types(['public' => true,], 'objects');

	foreach ( $pt_objects as $pt_slug => $type ) {
		
		if ( in_array( $pt_slug, $exclude ) ) {
			continue;
		}
		
		$post_types[$pt_slug] = $type->labels->name;
	}
	
	// Pagelayer Panel
	$wp_customize->add_panel( 'pagelayer_settings', array(
		'priority'       => 10,
		'title'          => 'Pagelayer',
	));
	
	// Global colors section
	$wp_customize->add_section( 'pagelayer_global_colors_sec', array(
		'capability' => 'edit_theme_options',
		'priority' => 10,
		'title' => __('Colors'),
		'panel' => 'pagelayer_settings',
	));
	
	$wp_customize->add_setting( 'pagelayer_global_colors', array(
		'type' => 'option',
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => json_encode( $pagelayer->global_colors )
	));
	
	$wp_customize->add_control( new Pagelayer_Color_Repeater_Control($wp_customize, 'pagelayer_global_colors', array(
		'priority' => 10,
		'section' => 'pagelayer_global_colors_sec',
	)));
	
	// Global fonts section
	$wp_customize->add_section( 'pagelayer_global_fonts_sec', array(
		'capability' => 'edit_theme_options',
		'priority' => 10,
		'title' => __('Typography'),
		'panel' => 'pagelayer_settings',
	));
	
	$wp_customize->add_setting( 'pagelayer_global_fonts', array(
		'type' => 'option',
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => json_encode($pagelayer->global_fonts),
	));
	
	$wp_customize->add_control( new Pagelayer_Font_Repeater_Control($wp_customize, 'pagelayer_global_fonts', array(
		'priority' => 10,
		'section' => 'pagelayer_global_fonts_sec',
	)));
	
	foreach($post_types as $sk => $sv){
		
		$post_type = empty($sk) ? '' : '_'.$sk;
		$global_section = 'pagelayer_global_sec'.$post_type;
		$global_text = empty($sk) ? '' : __('Global');
				
		// Global section
		$wp_customize->add_section( $global_section, array(
			'capability' => 'edit_theme_options',
			'priority' => 10,
			'title' => sprintf( __('%s %s Styles', 'pagelayer'), $sv, $global_text),
			'panel' => 'pagelayer_settings',
		));
		
		foreach($pagelayer->css_settings as $set => $setv){
			
			$setting_name = $set.$sk;
			$setting = empty($setv['key']) ? 'pagelayer_'.$set.'_css'.$post_type : $setv['key'].$post_type;
			
			$wp_customize->add_setting( 'pagelayer_lable_'.$setting_name, array(
				'capability' => 'edit_theme_options',
			));
			
			$wp_customize->add_control( new Pagelayer_Customize_Control(
				$wp_customize, 'pagelayer_lable_'.$setting_name, array(
					'type' => 'hidden',
					'section' => $global_section,
					'description' => sprintf( __('<div class="pagelayer-customize-heading"><div>%s</div><span class="dashicons dashicons-arrow-right-alt2"></span></div>', 'pagelayer'), $sv.' '.$setv['name']),
					'li_class' => 'pagelayer-accordion-tab',
				)
			));
			
			// Register the typography control for body
			pagelayer_register_typo_customizer_control($wp_customize, array(
				'control' => $setting,
				'section' => $global_section,
				'label' => __( 'Typography', 'pagelayer' ),
				'capability' => 'edit_theme_options',
				'setting_type' => 'option',
				'transport' => 'refresh',
				'default' => '',
				'units' => ['px', 'em', '%'],
				'responsive' => 1,
			));

			$wp_customize->add_setting( $setting.'[color]', array(
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'transport' => 'refresh',
			));
			
			$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control(
				$wp_customize, $setting.'[color]', array(
					'section' => $global_section,
					'label' => __( 'Color', 'pagelayer' ),
				)
			));

			$wp_customize->add_setting( $setting.'[background-color]', array(
				'type' => 'option',
				'capability' => 'edit_theme_options',
				'transport' => 'refresh',
			));
			
			$wp_customize->add_control( new Pagelayer_Customize_Alpha_Color_Control(
				$wp_customize, $setting.'[background-color]', array(
					'section' => $global_section,
					'label' => __( 'Background Color', 'pagelayer' ),
				)
			));
			
			// Register the padding control for scroll to top
			pagelayer_register_padding_customizer_control($wp_customize, array(
				'control' => $setting,
				'control_array_sufix' => 'padding',
				'section' => $global_section,
				'label' => __( 'Padding', 'pagelayer' ),
				'capability' => 'edit_theme_options',
				'setting_type' => 'option',
				'transport' => 'refresh',
				'default' => '',
				'units' => ['px', 'em', '%'],
				'setting_parts' => array('0', '1', '2', '3', 'unit'),
				'responsive' => 1,
			));
			
			// Register the padding control for scroll to top
			pagelayer_register_padding_customizer_control($wp_customize, array(
				'control' => $setting,
				'control_array_sufix' => 'margin',
				'section' => $global_section,
				'label' => __( 'Margin', 'pagelayer' ),
				'capability' => 'edit_theme_options',
				'setting_type' => 'option',
				'transport' => 'refresh',
				'default' => '',
				'units' => ['px', 'em', '%'],
				'setting_parts' => array('0', '1', '2', '3', 'unit'),
				'responsive' => 1,
			));
			
		}
	}
}

/**
 * Register the Typography control.
 *
 * @return void
 */
function pagelayer_register_typo_customizer_control($wp_customize, $args, $screen_array = false){
			
	$settings_for_control = array();
	$settings = array('font-family', 'font-size', 'font-style', 'font-weight', 'font-variant', 'text-decoration-line', 'text-decoration-style', 'line-height', 'text-transform', 'letter-spacing', 'word-spacing', 'global-font');
	$screens = array('');
	$control_array_sufix = '';
	
	if(!empty($args['responsive'])){
		$screens = array('desktop' => '', 'tablet' => '_tablet', 'mobile' => '_mobile');
	}
	
	if(!empty($args['control_array_sufix'])){
		$control_array_sufix = '['.$args['control_array_sufix'].']';
	}
	
	// Register settings
	foreach($screens as $_screen => $screen){
		foreach($settings as $setting){
			
			// Skip units for responsive
			if($setting == 'unit' && !empty($screen)){
				continue;
			}
				
			$setting_name = $args['control'];
			
			if($screen_array && count($screens) > 1){
				$setting_name .= $control_array_sufix.'['.$_screen.']';
			}else{
				$setting_name .= $screen.$control_array_sufix;
			}

			$setting_name .= '['.$setting.']';
			$settings_for_control[$setting.$screen] = $setting_name;
			
			$setting_args = array(
				'capability' => $args['capability'],
				'transport' => $args['transport'],
			);
			
			if(!empty($args['setting_type'])){
				$setting_args['type'] = $args['setting_type'];
			}
			
			if(!empty($args['default'])){
				$setting_args['default'] = $args['default'];
			}
			
			$wp_customize->add_setting( $setting_name, $setting_args);
		}
	}
	
	$args['settings'] = $settings_for_control;
	
	$wp_customize->add_control( new Pagelayer_typo_Control(
		$wp_customize, $args['control']. @$args['control_array_sufix'], $args
	));
}

/**
 * Register the padding control.
 *
 * @return void
 */
function pagelayer_register_padding_customizer_control($wp_customize, $args, $screen_array = false){
			
	$settings_for_control = array();
	$screens = array('');
	$control_array_sufix = '';
	
	if(empty($args['setting_parts'])){
		$settings = array('top', 'right', 'bottom', 'left', 'unit');
	}else{
		$settings = $args['setting_parts'];
	}
	
	if(!empty($args['responsive'])){
		$screens = array('desktop' => '', 'tablet' => '_tablet', 'mobile' => '_mobile');
	}
	
	if(!empty($args['control_array_sufix'])){
		$control_array_sufix = '['.$args['control_array_sufix'].']';
	}
	
	// Register settings
	foreach($screens as $_screen => $screen){
		foreach($settings as $setting){
			
			// Skip units for responsive
			if($setting == 'unit' && (!empty($screen) || $screen_array)){
				continue;
			}
			
			$setting_name = $args['control'];
			
			if($screen_array && count($screens) > 1){
				$setting_name .= $control_array_sufix.'['.$_screen.']';
			}else{
				$setting_name .= $screen.$control_array_sufix;
			}
			
			$setting_name .= '['.$setting.']';
			$settings_for_control[$setting.$screen] = $setting_name;
			
			$setting_args = array(
				'capability' => $args['capability'],
				'transport' => $args['transport'],
			);
			
			if(!empty($args['default'])){
				$setting_args['default'] = $args['default'];
			}
			
			if(!empty($args['setting_type'])){
				$setting_args['type'] = $args['setting_type'];
			}
			
			if(!empty($args['sanitize_callback'])){
				$setting_args['sanitize_callback'] = $args['sanitize_callback'];
			}
			
			$wp_customize->add_setting( $setting_name, $setting_args);
		}
	}
	
	// If we save responsive values in same variables
	if($screen_array && !empty($args['units'])){
		$setting_name = $args['control'].$control_array_sufix.'[unit]';
		$settings_for_control['unit'] = $setting_name;
		$setting_args = array(
			'capability' => $args['capability'],
			'transport' => $args['transport'],
		);
			
		if(!empty($args['setting_type'])){
			$setting_args['type'] = $args['setting_type'];
		}
		
		$wp_customize->add_setting( $setting_name, $setting_args);
	}
	
	$args['settings'] = $settings_for_control;
	$wp_customize->add_control( new Pagelayer_Padding_Control(
		$wp_customize, $args['control']. @$args['control_array_sufix'], $args
	));
}

/**
 * Register the slider control.
 *
 * @return void
 */
function pagelayer_register_slider_custoze_control($wp_customize, $args){
			
	$settings_for_control = array();
	$setting = 'slider';
	$screens = array('');
	
	if(!empty($args['responsive'])){
		$screens = array('desktop' => '_desktop', 'tablet' => '_tablet', 'mobile' => '_mobile');
	}
	
	// Register settings
	foreach($screens as $screen => $_screen){
		
		$setting_name = $args['control'];
		
		if(count($screens) > 1){
			$setting_name .= '['.$screen.']';
		}
			
		$settings_for_control[$setting.$_screen] = $setting_name;
			
		$setting_args = array(
			'capability' => $args['capability'],
			'transport' => $args['transport'],
		);

		if(!empty($args['default'])){
			$setting_args['default'] = $args['default'];
		}
			
		if(!empty($args['setting_type'])){
			$setting_args['type'] = $args['setting_type'];
		}
		
		if(!empty($args['sanitize_callback'])){
			$setting_args['sanitize_callback'] = $args['sanitize_callback'];
		}

		$wp_customize->add_setting($setting_name, $setting_args);
	}
	
	// Register setting for units
	if(!empty($args['units'])){
		$setting_name = $args['control'].'[unit]';
		$settings_for_control['unit'] = $setting_name;
		$setting_args = array(
			'capability' => $args['capability'],
			'transport' => $args['transport'],
		);
		
		if(!empty($args['setting_type'])){
			$setting_args['type'] = $args['setting_type'];
		}
		
		$wp_customize->add_setting( $setting_name, $setting_args);
	}
	
	$args['settings'] = $settings_for_control;
	$args['type'] = 'pl_slider';
	
	$wp_customize->add_control( new Pagelayer_Custom_Control( $wp_customize, $args['control'], $args ));
}