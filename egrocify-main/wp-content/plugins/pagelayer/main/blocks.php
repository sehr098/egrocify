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

if( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ){
	add_filter( 'block_categories_all', 'pagelayer_block_category', 999999999);
}else{
	add_filter( 'block_categories', 'pagelayer_block_category', 999999999 );
}

function pagelayer_block_category( $categories ) {
	
	// Create a custom category and add it at the beginning of the list
	array_unshift($categories, array(
		'slug'  => 'pagelayer',
		'title' => 'Pagelayer',
	));

	return $categories;
}

function pagelayer_block_name_by_tag($tag){
	return 'pagelayer/' . str_replace('_', '-', $tag);
}

add_action('template_redirect', 'pagelayer_block_init');
function pagelayer_block_init(){
	global $pagelayer;
	
	if(!function_exists('register_block_type') || !pagelayer_has_blocks()){
		return;
	}
	
	// Load shortcode
	pagelayer_load_shortcodes();
	
	$pl_blocks_styles = $pagelayer->styles;
	$pl_attrs = [];

	foreach ($pagelayer->shortcodes as $block => $pl_props) {
		
		if(!empty($pl_props['no_gt']) || $pl_props['group'] == 'woocommerce'){
			continue;
		}
		
		// Create attribute Object
		$attributes = [];
		$pagelayer_tabs = ['settings', 'options'];

		foreach($pagelayer_tabs as $tab){
			$section_close = false; // First section always open

			foreach($pl_props[$tab] as $section => $props){
				$props = array_key_exists($section, $pl_props) ? $pl_props[$section] : $pl_blocks_styles[$section];

				// Reset / Create the cache
				foreach($props as $x => $prop){
					$attributes[$x] = [
						'type' => $prop['type']
					];

					if ($prop['type'] === 'image') {
						$attributes['pagelayer-srcset'] = [
							'type' => 'string'
						];
					}

					// Are we to set this value?
					if (isset($prop['default']) && !empty($prop['default'])) {
						$tmp_val = $prop['default'];

						// If there is a unit and there is no unit suffix in atts value
						if(isset($prop['units'])){
							if (is_numeric($tmp_val)) {
								$tmp_val = $tmp_val . $prop['units'][0];
							} else {
								$sep = isset($prop['sep']) ? $prop['sep'] : ',';
								$tmp2 = explode($sep, $tmp_val);
								foreach ($tmp2 as $k => $value) {
									if (is_numeric($value)) {
										$tmp2[$k] = $value . $prop['units'][0];
									}
								}
								$tmp_val = implode($sep, $tmp2);
							}
						}

						$attributes[$x]['default'] = $tmp_val;
					}

					$modes = ['tablet', 'mobile'];

					// Do we have screen?
					if (array_key_exists('screen', $prop)) {
						foreach ($modes as $m) {
							$prop_name = $x . '_' . $m;

							$attributes[$prop_name] = [
								'type' => $prop['type']
							];

							// TODO: 
							// if (array_key_exists('default', $props[$prop_name])) {
							//     $attributes[$prop_name]['default'] = $props[$x]['default'];
							// }
						}
					}
				}
			}
		}
		
		// Register blocks
		register_block_type(
			pagelayer_block_name_by_tag($block),
			array(
				'attributes' => $attributes,
				'render_callback' => 'pagelayer_block_renderer',
			)
		);
	}
}

function pagelayer_block_renderer($attributes, $content, $_this){
	global $pagelayer;
	
	$parsed_block = $_this->parsed_block;
	$block_name = $parsed_block['blockName'];
	$tag = '';
	$inner_blocks = array(
		'blocks' => $parsed_block['innerBlocks'],
		'content' => $parsed_block['innerContent']
	);
	$attributes['is_not_sc'] = 1;
	
	if ( is_string( $block_name ) && 0 === strpos( $block_name, 'pagelayer/' ) ) {
		$tag = substr( $block_name, 10 );
	}
	
	// Convert as pagelayer shortcode
	$tag = str_replace('-', '_', $tag);
		
	if( empty($tag) || !array_key_exists($tag, $pagelayer->shortcodes) ){
		return '';
	}

	return pagelayer_render_shortcode($attributes, $content, $tag, $inner_blocks);
}

add_action('enqueue_block_editor_assets', 'pagelayer_enqueue_block_assets');
function pagelayer_enqueue_block_assets(){
	global $pagelayer;
	
	wp_enqueue_style( 'pagelayer-block-icon', PAGELAYER_CSS . '/pagelayer-icons.css', array('wp-edit-blocks'), PAGELAYER_VERSION );

	// Load styles and javascript
	pagelayer_enqueue_frontend(true);
	
	wp_enqueue_style( 'pagelayer-block-editor', PAGELAYER_CSS . '/pagelayer-blocks.css', array('wp-edit-blocks'), PAGELAYER_VERSION );
	
	// Components
	wp_enqueue_script( 'pagelayer-blocks', PAGELAYER_JS . '/blocks/index.js', [ 'wp-blob', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-html-entities', 'wp-i18n' ], PAGELAYER_VERSION, true );

	// Load shortcode
	pagelayer_load_shortcodes();
	
	// Load fonts
	pagelayer_load_font_options();
	
	$shortcodes = array();
	
	foreach($pagelayer->shortcodes as $block => $pl_props) {
		
		if(!empty($pl_props['no_gt']) || $pl_props['group'] == 'woocommerce'){
			continue;
		}
		
		$shortcodes[$block] = $pl_props;
	}
	
	wp_localize_script( 'pagelayer-blocks', 'pagelayer_config',
		array( 
			'pagelayer_shortcodes' => $shortcodes,
			'pagelayer_styles' => $pagelayer->styles,
			'pagelayer_groups' => $pagelayer->groups,
			'internal_linking_nonce' => wp_create_nonce('internal-linking'),
			'pagelayer_fonts' =>  $pagelayer->fonts,
		)
	);
}

// Load global JS
add_action( 'admin_print_scripts', 'pagelayer_block_global_js');
function pagelayer_block_global_js(){
	global $pagelayer, $post;
	
	// For gutenberg
	if(!pagelayer_is_gutenberg_editor()){	
		return;
	}
	
	// Load global colors and fonts
	pagelayer_load_global_palette();
		
	$pagelayer_recaptch_lang = get_option('pagelayer_google_captcha_lang');
	
	// Get CAPTCHA site key
	$pagelayer_recaptch_site_key = get_option('pagelayer_google_captcha');
	$pro_url = defined('POPULARFX_PRO_URL') ? POPULARFX_PRO_URL : PAGELAYER_PRO_URL;
	$pro_txt = defined('POPULARFX_PRO_URL') ? 'PopularFX Pro' : 'Pagelayer Pro';
	
	echo '<script type="text/javascript" id="pagelayer-block-global-js">
pagelayer_ajax_url = "'.admin_url( 'admin-ajax.php' ).'?";
pagelayer_url = "'.PAGELAYER_URL.'";
pagelayer_ver = "'.PAGELAYER_VERSION.'";
pagelayer_global_nonce = "'.wp_create_nonce('pagelayer_global').'";
pagelayer_server_time = '.time().';
pagelayer_pro = '.(int)defined('PAGELAYER_PREMIUM').';
pagelayer_is_live = 1;
pagelayer_pro_url = "'. $pro_url .'";
pagelayer_pro_txt = "'. $pro_txt .'";
pagelayer_facebook_id = "'.get_option('pagelayer-fbapp-id').'";
pagelayer_settings = '.json_encode($pagelayer->settings).';
pagelayer_recaptch_lang = "'.(!empty($pagelayer_recaptch_lang) ? $pagelayer_recaptch_lang : '').'";
pagelayer_global_colors = '.json_encode($pagelayer->global_colors).';
pagelayer_global_fonts = '.json_encode($pagelayer->global_fonts).';
pagelayer_ajax_nonce = "'.wp_create_nonce('pagelayer_ajax').'";
pagelayer_post_permalink = "'.get_permalink($post->ID).'";
pagelayer_author = '.json_encode(pagelayer_author_data($post->ID)).';
pagelayer_postID = "'.$post->ID.'";
pagelayer_site_logo = '.json_encode(pagelayer_site_logo()).';
pagelayer_recaptch_site_key = "'.(!empty($pagelayer_recaptch_site_key) ? $pagelayer_recaptch_site_key : '').'";
pagelayer_global_colors = '.json_encode($pagelayer->global_colors).';
pagelayer_global_fonts = '.json_encode($pagelayer->global_fonts).';
pagelayer_loaded_icons =  '.json_encode(pagelayer_enabled_icons()).';
pagelayer_customizer_url = "'.admin_url("/customize.php?return=").urlencode($_SERVER['HTTP_REFERER']).'";
pagelayerCacheBlockTags = {};
</script>';

		echo '<style id="pagelayer-block-global-style">
@media (min-width: '.($pagelayer->settings['tablet_breakpoint'] + 1).'px){
.pagelayer-hide-desktop{
filter:blur(3px);
}
.pagelayer-hide-desktop *{
filter:blur(2px);
}
}

@media (max-width: '.$pagelayer->settings['tablet_breakpoint'].'px) and (min-width: '.($pagelayer->settings['mobile_breakpoint'] + 1).'px){
.pagelayer-hide-tablet{
filter:blur(3px);
}
.pagelayer-hide-tablet *{
filter:blur(2px);
}
}

@media (max-width: '.$pagelayer->settings['mobile_breakpoint'].'px){
.pagelayer-hide-mobile{
filter:blur(3px);
}

.pagelayer-hide-mobile *{
filter:blur(2px);
}
}
</style>';

}

// Schema for save contact form template via react
add_action( 'init', 'pagelayer_register_metadata' );
function pagelayer_register_metadata() {
	
	register_meta(
		'post',
		'pagelayer_contact_templates',
		array(
			'type' => 'object',
			'description' => 'Contacts Data',
			'single' => true,
			'show_in_rest' => array(
				'schema' => array(
					'additionalProperties' => true,
					'items' => array(
						'type' => 'array',
						'items' => array(
							'type' => 'array',
							'items' => array(
								'type' => 'string',
							),
						),
					),
				),
			),
			'auth_callback' => function() {
				return current_user_can('edit_posts');
			}
		)
	);
	
	register_meta(
		'post',
		'_pagelayer_content',
		array(
			'type' => 'string',
			'description' => 'Menu Content',
			'single' => true,
			'show_in_rest' => true,
			'auth_callback' => function() {
				return current_user_can('edit_posts');
			}
		)
	);
}

add_filter( 'the_post', 'pagelayer_blocks_the_post' );
function pagelayer_blocks_the_post( $post ) {
	
	if(!pagelayer_is_gutenberg_editor() || !has_blocks( $post ) ){
		return;
	}
	
	// call block register
	// It is being used to load the runtime font family
	pagelayer_block_init();
	
    $post->post_content = pagelayer_add_tmp_atts($post->post_content);
}

// Add tmp attribute to block code
function pagelayer_add_tmp_atts($content){
	
	$blocks = parse_blocks( $content );
	$output = '';
	
	foreach ( $blocks as $block ) {
		$block_name = $block['blockName'];
		
		// Is pagelayer block
		if ( is_string( $block_name ) && 0 === strpos( $block_name, 'pagelayer/' ) ) {
			$_block = pagelayer_serialize_block($block);
			$output .= serialize_block($_block);
			continue;
		}
		
		$output .= serialize_block($block);
	}
		
	return $output;
}

function pagelayer_serialize_block($block){
	global $pagelayer;
	
	// Load shortcode
	pagelayer_load_shortcodes();
	
	// If block saved by Pagelayer Editor
	if(in_array( $block['blockName'], ['pagelayer/pl_inner_col', 'pagelayer/pl_inner_row'])){
		$block['blockName'] = str_replace('inner_', '', $block['blockName']);
	}
	
	$tag = substr( $block['blockName'], 10 );
	$pl_tag = str_replace('-', '_', $tag);
	
	if(isset($pagelayer->shortcodes[$pl_tag])){
	
		// Create attribute Object
		$pl_props = $pagelayer->shortcodes[$pl_tag];
		$el = array(
			'atts' => $block['attrs'],
			'tmp' => []
		);

		foreach($pagelayer->tabs as $tab){
			
			if(empty($pl_props[$tab])){
				continue;
			}
			
			foreach($pl_props[$tab] as $section => $_props){
				
				$props = !empty($pl_props[$section]) ? $pl_props[$section] : $pagelayer->styles[$section];
				
				if(empty($props)){
					continue;
				}
				
				// Reset / Create the cache
				foreach($props as $prop => $param){
					
					// No value set
					if(empty($el['atts'][$prop])){
						continue;
					}
				
					// Load any attachment values - This should go on top in the newer version @TODO
					if(in_array($param['type'], ['image', 'video', 'audio', 'media'])){
						$attachment = ($param['type'] == 'image') ? pagelayer_image(@$el['atts'][$prop]) : pagelayer_attachment(@$el['atts'][$prop]);
						
						if(!empty($attachment)){
							foreach($attachment as $k => $v){
								$el['tmp'][$prop.'-'.$k] = $v;
							}						
						}
						
					}
				
					// Load any attachment values - This should go on top in the newer version @TODO
					if($param['type'] == 'multi_image'){
						
						$img_ids = pagelayer_maybe_explode(',', $el['atts'][$prop]);					
						$img_urls = [];
						
						// Make the image URL
						foreach($img_ids as $k => $v){
							$image = pagelayer_image($v);
							$img_urls['i'.$v] = @$image['url'];
						}
						
						$el['tmp'][$prop.'-urls'] = json_encode($img_urls);
					}
					
					// Load permalink values
					if($param['type'] == 'link'){
						
						$link = $el['atts'][$prop];
						
						if( is_array($el['atts'][$prop]) ){
							
							// Link is required for check IF and IF-EXT in html
							if(!isset($el['atts'][$prop]['link']) || strlen(trim($el['atts'][$prop]['link'])) < 1){
								$link = '';
								unset($el['atts'][$prop]);
								continue;
							}
							
							$link = $el['atts'][$prop]['link'];
						}
						
						$el['tmp'][$prop] = pagelayer_permalink($link);
					}
				}
			}
		}
		
		$func = null;
		
		if(substr($pl_tag, 0, 3) == 'pl_'){
			$func = 'pagelayer_sc_block_'.substr($pl_tag, 3);
		}
		
		if(function_exists($func)){
			call_user_func_array($func, array(&$el));
		}
		
		if(!empty($el['tmp'])){
			$_tmp = $el['tmp'];
			$block['attrs']['tmpAtts'] = array_filter($_tmp);
		}
		
		// If block saved by Pagelayer Editor
		if(strpos($block['blockName'], '_') !== false){
			$block['blockName'] = str_replace('_', '-', $block['blockName']);
		}
	}
		
	// This have innerBlocks
	if(!empty($block['innerBlocks'])){
		foreach($block['innerBlocks'] as $key => $inner_block){
			$block['innerBlocks'][$key] = pagelayer_serialize_block($inner_block);
		}
	}
	
	return $block;
}

// TODO: create a seprate file or use all the functions from pagelayer editor files
//Grid Gallery Handler
function pagelayer_sc_block_grid_gallery(&$el){
	
	if(empty($el['atts']['ids'])){
		$el['atts']['ids'] = '';
	}
	
	$ids = pagelayer_maybe_explode(',', $el['atts']['ids']);
	$urls = [];
	$all_urls = [];
	$size = $el['atts']['size'];
	
	// Make the image URL
	foreach($ids as $k => $v){
		
		$image = pagelayer_image($v);
				
		$urls['i'.$v] = @$image['url'];
		$links['i'.$v] = @$image['link'];
		$titles['i'.$v] = @$image['title'];
		$captions['i'.$v] = @$image['caption'];
		
		foreach($image as $kk => $vv){
			$si = strstr($kk, '-url', true);
			if(!empty($si)){
				$all_urls['i'.$v][$si] = $vv;
			}
		}
		
	}
	
	// Make the TMP vars
	if(!empty($urls)){
		$el['tmp']['ids-urls'] = json_encode($urls);
		$el['tmp']['ids-all-urls'] = json_encode($all_urls);
		$el['tmp']['ids-all-links'] = json_encode($links);
		$el['tmp']['ids-all-titles'] = json_encode($titles);
		$el['tmp']['ids-all-captions'] = json_encode($captions);
	}
}