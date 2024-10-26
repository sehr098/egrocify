<?php

//////////////////////////////////////////////////////////////
//===========================================================
// live.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:       23rd Jan 2017
// Time:       23:00 hrs
// Site:       http://pagelayer.com/wordpress (PAGELAYER)
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


class PageLayer_LiveEditor{

	// The constructor
	function __construct() {

		global $pagelayer;
		
		// Convert the post to a PageLayer Post first
		$this->check_post();

		// Add the shortcodes
		// TODO : Make a json file
		add_action('wp_head', array($this, 'wp_head'), 999);

		// Add the Body Class Filter
		//add_filter('body_class', array($this, 'body_class'));

		// Add the content handler
		add_filter('the_content', array($this, 'the_content'), 999999);
		
		// Skip do_blocks for invalid blocks
		add_filter( 'pre_render_block', array($this, 'pre_do_render_block'), 10, 3 );
		
		// Skip do_shortcode for invalid shortcodes
		add_filter( 'pre_do_shortcode_tag',  array($this, 'pre_do_shortcode_tag'), 10, 4 );
		
		// Build the Shortcodes MD5 for cache
		$scmd5 = md5(json_encode($pagelayer->shortcodes).json_encode($pagelayer->groups).json_encode($pagelayer->styles));
		
		// Enqueue our Editor's JS
		wp_register_script('pagelayer-editor', admin_url( 'admin-ajax.php?action=pagelayer_givejs' ).'&give=pagelayer-editor.js,widgets.js,'.(defined('PAGELAYER_PREMIUM') ? 'premium.js,' : '').'properties.js,base-64.min.js,slimscroll.js,vanilla-picker.min.js,trumbowyg.js,trumbowyg.fontfamily.js,trumbowyg-pagelayer.js,tlite.min.js,pagelayer-pen.js,&pagelayer_nonce=1&scmd5='.$scmd5, array('jquery'), PAGELAYER_VERSION);
		
		wp_enqueue_script('pagelayer-editor');

		$css_url = admin_url('admin-ajax.php?action=pagelayer_givecss&pagelayer_nonce=1&');
		if(pagelayer_enable_giver()){
			$css_url = PAGELAYER_CSS.'/givecss.php?';
		}

		// Enqueue the Editor's CSS
		wp_register_style('pagelayer-editor', $css_url.'give=pagelayer-editor-frontend.css,pagelayer-pen.css,'.(defined('PAGELAYER_PREMIUM') ? ',owl.theme.default.min.css,owl.carousel.min.css' : ''), array(), PAGELAYER_VERSION);
		wp_enqueue_style('pagelayer-editor');

		// Enqueue the DateTime picker CSS
		/* wp_register_style('datetime-picker', PAGELAYER_CSS.'/datetime-picker.css', array(), PAGELAYER_VERSION);
		wp_enqueue_style('datetime-picker'); */

		// Enqueue the media library
		if(!did_action('wp_enqueue_media')){
			wp_enqueue_media();
		}

		// Force the Frontend CSS and JS if not already loaded
		pagelayer_enqueue_frontend(true);

		// Hide Admin Bar
		show_admin_bar(false);
		remove_action('wp_head', '_admin_bar_bump_cb');
		
		// Load custom widgets
		do_action('pagelayer_custom_editor_enqueue');

		// Add the footer scripts
		add_action('wp_footer', array($this, 'wp_footer'), 1);

	}

	// Add our body class
	function body_class($classes){
		return array_merge($classes, array('pagelayer-body'));
	}

	// Header function to add certain things
	function wp_head(){

		global $pagelayer, $post, $wp_query, $_wp_post_type_features;
		
		// Export the post props
		$_post = clone $post;
		
		unset($_post->post_content);
		
		// Add template type
		if(!empty($pagelayer->template_editor)){
			$_post->pagelayer_template_type = get_post_meta($_post->ID, 'pagelayer_template_type', true); 	
		}
		
		$returnURL = ($_post->post_type == 'post' ? admin_url('edit.php') : admin_url('edit.php?post_type='.$_post->post_type) );
		
		// Get CAPTCHA site key
		$pagelayer_recaptch_site_key = get_option('pagelayer_google_captcha');
		
		$pro_url = defined('POPULARFX_PRO_URL') ? POPULARFX_PRO_URL : PAGELAYER_PRO_URL;
		$pro_txt = defined('POPULARFX_PRO_URL') ? 'PopularFX Pro' : 'Pagelayer Pro';
		$post_type = get_post_type_object($post->post_type);
		$post_type_name = (!empty($post_type->labels->singular_name)) ? $post_type->labels->singular_name : ucfirst($post_type->name);
		
		// Create list of nav menus and it's items list
		$pagelayer_menus_items_list = array();
		$menus = wp_get_nav_menus();
		
		foreach($menus as $menu){
			
			if(!is_nav_menu($menu)){
				continue;
			}

			$pagelayer_menus_items_list[$menu->term_id] = wp_get_nav_menu_items($menu->term_id, array('post_status' => 'any'));
		}
		
		echo '
<script type="text/javascript">
pagelayer_ver = "'.PAGELAYER_VERSION.'";
pagelayer_block_prefix = "'.PAGELAYER_BLOCK_PREFIX.'";
pagelayer_pro = '.(int)defined('PAGELAYER_PREMIUM').';
pagelayer_pro_url = "'.PAGELAYER_PRO_URL.'";
pagelayer_pro_txt = "'.addslashes('This feature is a part of <a href="'.$pro_url.'" target="_blank">'.$pro_txt.'</a>. You will need purchase <a href="'.$pro_url.'" target="_blank">'.$pro_txt.'</a> to use this feature.').'";
pagelayer_api_url = "'.PAGELAYER_API.'";
pagelayer_ajax_url = "'.admin_url( 'admin-ajax.php' ).'?&";
pagelayer_post_props = "'.admin_url( 'admin.php?page=pagelayer_meta_setting&post=' ).$post->ID.'";
pagelayer_ajax_nonce = "'.wp_create_nonce('pagelayer_ajax').'";
pagelayer_media_ajax_nonce = "'.wp_create_nonce('media-form').'";
pagelayer_internal_linking_nonce = "'.wp_create_nonce('internal-linking').'";
pagelayer_preview_nonce = "'. wp_create_nonce( 'post_preview_' . $post->ID ).'";
pagelayer_url = "'.PAGELAYER_URL.'";
pagelayer_postID = "'.$post->ID.'";
pagelayer_permalink_structure = "'.(empty(get_option('permalink_structure')) ? 0 : 1).'";
pagelayer_post_permalink = "'.get_permalink($post->ID).'";
pagelayer_tabs = '.json_encode($pagelayer->tabs).';
pagelayer_isDirty = false;
pagelayer_returnURL = "'.$returnURL.'";
pagelayer_theme_vars = '.json_encode( pagelayer_template_vars() ).';
pagelayer_revision_obj = '.json_encode( pagelayer_get_post_revision_by_id( $post->ID ) ).';
pagelayer_author = '.json_encode(pagelayer_author_data($post->ID)).';
pagelayer_site_logo = '.json_encode(pagelayer_site_logo()).';
pagelayer_support_FI = "'. ( current_theme_supports('post-thumbnails') )  .'";	
pagelayer_editable = ".'.(!empty($pagelayer->template_editor) ? $pagelayer->template_editor : 'pagelayer-editable-area').'";
pagelayer_recaptch_site_key = "'.(!empty($pagelayer_recaptch_site_key) ? $pagelayer_recaptch_site_key : '').'";
pagelayer_post = '. @json_encode($_post) .';
pagelayer_loaded_icons =  '.json_encode(pagelayer_enabled_icons()).';
pagelayer_social_urls =  '.json_encode(pagelayer_get_social_urls()).';
pagelayer_global_widgets = '.json_encode($pagelayer->global_widgets).';
pagelayer_saved_sections = '.json_encode($pagelayer->saved_sections).';
pagelayer_global_sections = '.json_encode($pagelayer->global_sections).';
pagelayer_brand = "'.addslashes($pagelayer->BRAND_TEXT).'";
pagelayer_post_type_features = '.json_encode(@$_wp_post_type_features[$post->post_type]).';
pagelayer_post_categories = '.json_encode(pagelayer_post_cats($post)).';
pagelayer_post_tags = '.json_encode(pagelayer_post_tags($post)).';
pagelayer_shortcodes.pl_post_props.name = "'.ucfirst($post_type_name).' '. __pl('Settings').'";
pagelayer_shortcodes.pl_post_props.params.post_author.list = '.json_encode(pagelayer_post_authors_by_type($post->post_type)).';
pagelayer_shortcodes.pl_post_props.pageParent.post_parent.list = '.json_encode(pagelayer_parent_post_prop($post)).';
pagelayer_gmaps_key = "'.get_option('pagelayer-gmaps-api-key').'";
pagelayer_ajax_post_data = {};
pagelayer_menus_items_list = '.json_encode($pagelayer_menus_items_list).';
pagelayer_customizer_values = '.json_encode(pagelayer_get_customizer_options()).';
pagelayer_global_colors = '.json_encode($pagelayer->global_colors).';
pagelayer_global_fonts = '.json_encode($pagelayer->global_fonts).';
pagelayer_customizer_url = "'.admin_url("/customize.php?return=").urlencode($_SERVER['HTTP_REFERER']).'";
pagelayer_support_url = "'.$pagelayer->support .'";';

if(defined('PAGELAYER_PREMIUM')){
	echo 'pagelayer_shortcodes.pl_popup.advance_options.popup_cookie_name.default = "popup_cookie_'.rand(100, 999).'";';
}

// Detect JS via givejs for better performance
if(empty($pagelayer->settings['enable_giver'])){
	echo '
jQuery(document).ready(function(){	
	var test_giver = -1;
	
	return jQuery.ajax({
		url: "'.PAGELAYER_JS.'/givejs.php?test=1",
		type: "GET",
		dataType: "text",
		success:function(data){
			
			if(data !== "1"){
				return;
			}
			
			test_giver = 1;
		},
		complete:function(data){
			jQuery.ajax({
				type: "POST",
				url: pagelayer_ajax_url+"&action=pagelayer_set_jscss_giver",
				data: { 
					pagelayer_nonce: pagelayer_ajax_nonce,
					set : test_giver
				},
				error: function(errorThrown){
					console.log("Error saving giver data");
					console.log(errorThrown);
				}
			});
			
		}
	});
});
';
}

echo '
</script>';

		echo '<style>
@media (min-width: '.($pagelayer->settings['tablet_breakpoint'] + 1).'px){
.pagelayer-hide-desktop{
display:initial;
filter:blur(3px);
}
.pagelayer-hide-desktop *{
filter:blur(2px);
}
}

@media (max-width: '.$pagelayer->settings['tablet_breakpoint'].'px) and (min-width: '.($pagelayer->settings['mobile_breakpoint'] + 1).'px){
.pagelayer-hide-tablet{
display:initial;
filter:blur(3px);
}
.pagelayer-hide-tablet *{
filter:blur(2px);
}
}

@media (max-width: '.$pagelayer->settings['mobile_breakpoint'].'px){
.pagelayer-hide-mobile{
display:initial;
filter:blur(3px);
}

.pagelayer-hide-mobile *{
filter:blur(2px);
}
}
</style>';
		do_action('pagelayer_editor_wp_head');

	}

	// Footer function to add certain things
	function wp_footer(){
		
		global $pagelayer;
		
		echo '<script>
pagelayer_default_params = '.json_encode($pagelayer->default_params, JSON_FORCE_OBJECT).';
pagelayer.el = '.json_encode($pagelayer->data_attr, JSON_FORCE_OBJECT).';
</script>';
		
		wp_enqueue_script('heartbeat');
		_wp_footer_scripts();
	}

	// Convert to Pagelayer post
	function check_post(){

		global $post;

		// Is this a Pagelayer post
		$data = get_post_meta($post->ID, 'pagelayer-data', true);

		if(empty($data)){

			// Is it a Gutenburg Post ?
			if(!empty($post->post_content)){
				
				// Add our surrounding tag
				$post->post_content = '<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_row -->
<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_col {col=12} -->
'.$post->post_content.'
<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_col -->
<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_row -->';

			// Regular post, but its empty so we will add our blocks !
			}else{

				// Add our surrounding tag
				$post->post_content = '<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_row {"stretch":"auto","col_gap":"10","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","pagelayer-id":""} -->
<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_col {"overlay_hover_delay":"400","pagelayer-id":""} -->
<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_col -->
<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl_row -->';
			
				// Update the post
				$new_post = array(
							'ID' => $post->ID,
							'post_content' => $post->post_content,
						);

				// Update the post into the database
				wp_update_post($new_post);

				// Convert to pagelayer accessed post
				if(!add_post_meta($post->ID, 'pagelayer-data', time(), true)){
					update_post_meta($post->ID, 'pagelayer-data', time());
				}

			}
		}

	}

	// Add certain things
	function the_content($content) {

		global $post, $pagelayer;
		
		if(empty($pagelayer->dont_make_editable)){
			$content = '<div class="pagelayer-editable-area">'.$content.'</div>';
		}
		
		// Check if we're inside the main loop in a single post page.
		if ( is_single() && in_the_loop() && is_main_query() ) {
			return $content;
		}
	 
		return $content;

	}
	
	// Skip do_shortcode for invalid shortcodes
	function pre_do_shortcode_tag($return, $tag, $attr, $m){
		
		if(!empty($pagelayer->dont_make_editable) || ! doing_filter('the_content')){
			return $return;
		}
		
		$prefixes = ['pl_'];
		
		$prefixes = apply_filters( 'pagelayer_valid_shortcode_tag', $prefixes);
		
		$vailid = false;

		foreach($prefixes as $prefix) {
			if (strpos($tag, $prefix) === 0) {
				$vailid = true;
				break;
			}
		}
		
		if($vailid){
			return $return;
		}
		
		return $m[0];
	}

	// Skip do_blocks for invalid blocks
	function pre_do_render_block($return, $parsed_block = [], $parent_block = null){
		
		if(!empty($pagelayer->dont_make_editable) ||  ! doing_filter('the_content') || empty($parsed_block['blockName'])){
			return $return;
		}
		
		$prefixes = ['pagelayer/'];
		
		$prefixes = apply_filters( 'pagelayer_valid_block_tag', $prefixes);
		
		$vailid = false;

		foreach($prefixes as $prefix) {
			if (strpos($parsed_block['blockName'], $prefix) === 0) {
				$vailid = true;
				break;
			}
		}
		
		if($vailid){
			return $return;
		}
		
		$serialize_block = serialize_block($parsed_block);
			
		$parsed_block['blockName'] = 'pagelayer/pl_missing';
		$parsed_block['innerHTML'] = $serialize_block;
		$parsed_block['attrs'] = [];
		
		return render_block($parsed_block);
	}

}