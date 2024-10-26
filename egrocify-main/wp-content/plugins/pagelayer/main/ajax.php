<?php

//////////////////////////////////////////////////////////////
//===========================================================
// ajax.php
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

// Is the nonce there ?
if(empty($_REQUEST['pagelayer_nonce'])){
	return;
}

pagelayer_memory_limit(128);

// The ajax handler
add_action('wp_ajax_pagelayer_wp_widget', 'pagelayer_wp_widget_ajax');
function pagelayer_wp_widget_ajax(){

	global $pagelayer;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!current_user_can('edit_theme_options')){		
		$ret['error'][] = __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	pagelayer_load_shortcodes();
	
	header('Content-Type: application/json');
	
	$ret = [];
	$tag = @$_POST['tag'];
	//pagelayer_print($pagelayer->shortcodes[$tag]);
	
	// No tag ?
	if(empty($pagelayer->shortcodes[$tag])){
		$ret['error'][] =  __pl('no_tag');
		pagelayer_json_output($ret);
	}
	
	// Include the widgets
	include_once(ABSPATH . 'wp-admin/includes/widgets.php');
	
	$class = $pagelayer->shortcodes[$tag]['widget'];
	
	// Check the widget class exists ?
	if(empty($class) || !class_exists($class)){
		$ret['error'][] =  __pl('no_widget_class');
		pagelayer_json_output($ret);
	}
	
	$instance = [];
	$widget = new $class();
	$widget->_set('pagelayer-widget-1234567890');
	
	// Is there any existing data ?
	if(!empty($_POST['widget_data'])){
		$json = json_decode(stripslashes($_POST['widget_data']), true);
		//pagelayer_print($json);die();
		if(!empty($json)){
			$instance = $json;
		}
	}

	// Are there any form values ?
	if(!empty($_POST['values'])){		
		parse_str(stripslashes($_POST['values']), $data);
		//pagelayer_print($data);die();
		
		// Any data ?
		if(!empty($data)){
		
			// Rss widget checkboxes fix
			if(!empty($data['widget-rss'])){
				$data['widget-rss']['pagelayer-widget-1234567890']['show_summary'] = empty($data['widget-rss']['pagelayer-widget-1234567890']['show_summary'])? 0 : 1;
				$data['widget-rss']['pagelayer-widget-1234567890']['show_author'] = empty($data['widget-rss']['pagelayer-widget-1234567890']['show_author'])? 0 : 1;
				$data['widget-rss']['pagelayer-widget-1234567890']['show_date'] = empty($data['widget-rss']['pagelayer-widget-1234567890']['show_date'])? 0 : 1;				
			}
			
			// First key is useless
			$data = current($data);
			
			// Do we still have valid data ?
			if(!empty($data)){
				
				// 2nd key is useless and just over-ride instance
				$instance = current($data);
				
			}
		}
	}
	
	// Settings instance For Text widget
	if($widget->id_base == 'text'){
		$instance['visual'] = false;
		$instance['legacy'] = false;
	}
	
	// Get the form
	ob_start();
	$widget->form($instance);
	$ret['form'] = ob_get_contents();
	ob_end_clean();
	
	// Get the html
	ob_start();
	$widget->widget([], $instance);
	$ret['html'] = ob_get_contents();
	ob_end_clean();
	
	// Widget data to set
	if(!empty($instance)){
		$ret['widget_data'] = $instance;
	}
	
	// Custom html widget form elements
	if(!empty($widget) && $widget->name=='Custom HTML'){
		$custom_html = explode('>', $ret['form']);
	
		$custom_html[0] = '<label for="widget-custom_html-pagelayer-widget-1234567890-title">Title:</label>'.$custom_html[0];
		$custom_html[0] = str_replace('type="hidden"', 'type="text"',$custom_html[0]);
		
		$custom_html[1] = '<label for="widget-custom_html-pagelayer-widget-1234567890-content">Content:</label>'.$custom_html[1];
		$custom_html[1] = str_replace('hidden', '', $custom_html[1]);
		
		$ret['form'] = implode('>', $custom_html);
	}
	
	pagelayer_json_output($ret);
	
}

// Update Post content
add_action('wp_ajax_pagelayer_save_content', 'pagelayer_save_content');
function pagelayer_save_content(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$content = $_POST['pagelayer_update_content'];

	$postID = (int) $_GET['postID'];
	
	if(empty($postID)){
		$msg['error'] = __pl('invalid_post_id');
		pagelayer_json_output($msg);
	}
	
	$_post = get_post($postID);
	
	// Post found ?
	if(empty($_post)){
		$msg['error'] = __pl('invalid_post_id');
		pagelayer_json_output($msg);
	}

	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($postID)){
		$msg['error'][] =  __pl('no_permission');
		pagelayer_json_output($msg);
	}
	
	// Check if the post exists	
	if(!empty($postID)){
		
		$content = base64_decode($content);
		
		/*if(!pagelayer_is_utf8($content)){
			$content = utf8_encode($content);
		}*/
		
		$content = wp_slash($content);
		
		$post = array(
			'ID' => $postID,
			'post_content' => $content,
		);
		
		$is_xss = pagelayer_xss_content($content);
		 
		if(!pagelayer_user_can_add_js_content() && strlen($is_xss) > 0){
			$msg['error'][] =  __pl('xss_found').' - '.$is_xss;
			pagelayer_json_output($msg);
		}
		
		// Any properties ?			
		$allowed = ['post_title', 'post_name', 'post_excerpt', 'post_status', 'post_password', 'post_date', 'post_author', 'post_parent', 'menu_order'];

		foreach($allowed as $k){
			if(isset($_REQUEST[$k])){
				$post[$k] = $_REQUEST[$k];
			}
		}
		
		if(!empty($post['post_password']) && $_REQUEST['post_sticky'] == true){
			$msg['error'] = __pl('post_pass_with_sticky_err');
			pagelayer_json_output($msg);
		}
		
		$post['comment_status'] = !empty($_REQUEST['comment_status']) ? 'open' : 'closed';
		$post['ping_status'] = !empty($_REQUEST['ping_status']) ? 'open' : 'closed';
		$post['post_status'] = empty($post['post_status']) ? $_post->post_status : $post['post_status'];
		
		if(!empty($post['post_status']) && $post['post_status'] == 'publish'){
			
			// Allowed to publish pages ?
			if($_post->post_type == 'page' && !current_user_can('publish_pages')){
				$msg['error'][] =  __pl('no_publish_permission');
				pagelayer_json_output($msg);
			}
			
			// Allowed to publish posts ?
			if($_post->post_type == 'post' && !current_user_can('publish_posts')){
				$post['post_status'] = 'pending';
			}
		}	
		
		if(!empty($post['post_password'])){
			$post['post_password'] = (in_array($post['post_status'], array('pass_protected', 'publish')) ? $post['post_password'] : '');
			$post['post_status'] = 'publish';
		}else{	
			$post['post_status'] = ($post['post_status'] == 'pass_protected') ? 'publish' : $post['post_status'];
			$post['post_password'] = '';
		}
		
		// Set post GMT time
		if(!empty($post['post_date']) && '0000-00-00 00:00:00' !== $post['post_date']){
			$post['post_date_gmt'] = get_gmt_from_date( $post['post_date'] );
			
			if( in_array($post['post_status'], array('future', 'publish')) && $_post->post_date_gmt === '0000-00-00 00:00:00' ){
				$post['edit_date'] = true;
			}
		}
		
		$_REQUEST['featured_image'] = (int) $_REQUEST['featured_image'];
		if(!empty($_REQUEST['featured_image'])){
			set_post_thumbnail($postID, $_REQUEST['featured_image']);
		}else{
			delete_post_thumbnail($postID);
		}
		
		if(!isset($_REQUEST['post_category'])){
			$_REQUEST['post_category'] = '';
		}
		
		if(!isset($_REQUEST['post_tags'])){
			$_REQUEST['post_tags'] = '';
		}
		
		if($_post->post_type == 'post'){
			$post['post_category'] = pagelayer_sanitize_text_field($_REQUEST['post_category']);
			
			$post['tags_input'] = pagelayer_sanitize_text_field($_REQUEST['post_tags']);
		}else{
			$cat_name = pagelayer_post_type_category($_post->post_type);
			if($cat_name){
				$post['tax_input'][$cat_name] = pagelayer_sanitize_text_field($_REQUEST['post_category']);				
			}			
			
			$tag_name = pagelayer_post_type_tag($_post->post_type);
			if($tag_name){
				$post['tax_input'][$tag_name] = pagelayer_sanitize_text_field($_REQUEST['post_tags']);				
			}	
		}
		
		if(isset($_REQUEST['post_sticky']) && !empty($_REQUEST['post_sticky'])){
			stick_post( $postID );
		}else{
			if(is_sticky($postID)){
				unstick_post( $postID );
			}
		}
			
		// Any contact templates ?
		if(!empty($_REQUEST['contacts'])){
			update_post_meta($postID, 'pagelayer_contact_templates', $_REQUEST['contacts']);
		}else{
			delete_post_meta($postID, 'pagelayer_contact_templates');
		}
		
		// Save copyright
		if(isset($_REQUEST['copyright']) && current_user_can('manage_options')){
			update_option('pagelayer-copyright', wp_unslash($_REQUEST['copyright']));	
		}
		
		// Apply a filter
		$post = apply_filters('pagelayer_save_content', $post);
		
		// Update the post into the database
		$ret = wp_update_post($post, true);
		
		// Render the post
		//update_post_meta($postID, 'pagelayer_rendered_post', pagelayer_get_post_content($postID));

		if (is_wp_error($ret)) {
			$errors = $ret->get_error_messages();			
			$msg['error'] = __pl('post_update_err').' : '.implode('', $errors);
		}else{
			
			// Get the updated post
			$_post = get_post($postID);
			
			// Is this a Pagelayer post
			$data = get_post_meta($postID, 'pagelayer-data', true);

			if(empty($data)){
				
				// Convert to pagelayer accessed post
				if(!add_post_meta($postID, 'pagelayer-data', time(), true)){
					update_post_meta($postID, 'pagelayer-data', time());
				}
			}
			
			$msg['success'] = __pl('post_update_success');
		}
		
	}else{
		$msg['error'] = __pl('post_update_err');
	}
	
	$msg['post_status'] = (empty($_post->post_password)) ? $_post->post_status : 'pass_protected';
	
	// Save global widgets data
	if(!empty($_REQUEST['global_widgets'])){
		pagelayer_save_templ_content(true);
	}
	
	// Save nav menu data
	if(!empty($_REQUEST['pagelayer_nav_items']) && current_user_can('edit_theme_options')){
		$menu_items = (array) $_REQUEST['pagelayer_nav_items'];
		foreach($menu_items as $items){
			pagelayer_save_nav_menu_items($items);
		}
	}
	
	// Save Customizer data
	if(!empty($_REQUEST['pagelayer_customizer_options']) && current_user_can('edit_theme_options')){
		
		$customizer_options = wp_unslash($_REQUEST['pagelayer_customizer_options']);
		$customizer_options = json_decode($customizer_options, true);
		
		// Add current post type
		$customizer_options['pagelayer_current_post_type'] = $_post->post_type;
		
		pagelayer_save_customizer_options($customizer_options);
	}
	
	pagelayer_json_output($msg);
	
}

// Save sections and global sections
add_action('wp_ajax_pagelayer_save_templ_content', 'pagelayer_save_templ_content');
function pagelayer_save_templ_content($echo = false){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( ! current_user_can( get_post_type_object( 'pagelayer-template' )->cap->create_posts ) ) {
		$ret['error'][$g_post_id] = __pl('no_permission');	
		pagelayer_json_output($ret);
		return false;
	}
	
	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($_REQUEST['postID'])){
		$msg['error'][] =  __pl('no_permission');
		pagelayer_json_output($msg);
	}
	
	$ret = array();
	
	// Save global widgets data
	if(empty($_REQUEST['global_widgets'])){
		$ret['error'][] = 'No widgets given';	
		pagelayer_json_output($ret);
		return false;
	}
	
	$global_widgets = $_REQUEST['global_widgets'];

	foreach($global_widgets as $key => $value){
		
		$g_post_id = (int) $value['post_id'];
		
		// Are you allowed to edit ?
		if(!empty($g_post_id) && !pagelayer_user_can_edit($g_post_id)){
			$ret['error'][$g_post_id] =  __pl('no_permission').' : '.$g_post_id;
			continue;
		}
		
		// Decode base64 data
		$value['content'] = base64_decode($value['content']);
		$value['content'] = wp_slash($value['content']);
		
		$is_xss = pagelayer_xss_content($value['content']);
		 
		if(!current_user_can('manage_options') && strlen($is_xss) > 0){
			$ret['error'][$g_post_id] =  __pl('xss_found').' - '.$is_xss;
			pagelayer_json_output($ret);
		}
		
		// We need to create the post
		if(empty($value['post_id'])){
			
			$g_ret = wp_insert_post([
				'post_type' => 'pagelayer-template',
				'post_title' => $value['title'],
				'post_content' => $value['content'],
				'post_status' => 'publish',
				'comment_status' => 'closed',
				'ping_status' => 'closed'
			]);
			
			$g_post_id = $g_ret;
			
			// Save our template metas
			update_post_meta($g_post_id, 'pagelayer_template_type', $value['type']);
			update_post_meta($g_post_id, 'pagelayer-data', time());
			
		}else if(!empty($value['content'])){
			
			// Save global widget content
			$post = array(
				'ID' => $g_post_id,
				'post_title' => $value['title'],
				'post_content' => $value['content'],
			);
			
			wp_update_post($post);
		}
		
		if(is_wp_error($g_post_id)){
			$ret['error'][$g_post_id] = __pl('template_update_err');
		}else{
			$ret['success'][$g_post_id] = __pl('template_update_success');
		}
	}
	
	if(!$echo){ 
		pagelayer_json_output($ret);
	}else{
		return $ret;
	}
}

// Update the Site Title
add_action('wp_ajax_pagelayer_set_jscss_giver', 'pagelayer_set_jscss_giver');
function pagelayer_set_jscss_giver(){
	global $wpdb;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if( !current_user_can('manage_options') ){
		$ret['error'] =  __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	$val = (int) @$_REQUEST['set'];
	
	if(in_array($val, [1, -1])){
		update_option('pagelayer_enable_giver', $val);
	}
	
	$ret['success'] =  1;
	pagelayer_json_output($ret);
}

// Shortcodes Widget Handler
add_action('wp_ajax_pagelayer_do_shortcodes', 'pagelayer_do_shortcodes');
function pagelayer_do_shortcodes(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!current_user_can('edit_posts')){// TODO : WooCommerce
		$ret['error'][] = __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	$data = '';
	if(isset($_REQUEST['shortcode_data'])){
		$data = stripslashes($_REQUEST['shortcode_data']);
	}

	// Load shortcodes
	pagelayer_load_shortcodes();

	$data = pagelayer_the_content($data);
	
	// Create the HTML object
	$node = pagelayerQuery::parseStr($data);
	$node->query('.pagelayer-ele')->removeClass('pagelayer-ele');
	echo $node->html();
	
	wp_die();
	
}

// Give the JS
add_action('wp_ajax_pagelayer_givejs', 'pagelayer_givejs');
function pagelayer_givejs(){
	
	global $pagelayer;
	
	// WordPress adds the Expires header in all AJAX calls. We need to remove it for cache to work
	header_remove("Expires");
	header_remove("Cache-Control");
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	// Load font options
	pagelayer_load_font_options();
	
	// Pagelayer Template Loading Mechanism
	include_once(PAGELAYER_DIR.'/js/givejs.php');
	
	exit();
	
}

add_action('wp_ajax_pagelayer_givecss', 'pagelayer_givecss');
add_action('wp_ajax_nopriv_pagelayer_givecss', 'pagelayer_givecss');
function pagelayer_givecss(){
	
	global $pagelayer;
	
	// WordPress adds the Expires header in all AJAX calls. We need to remove it for cache to work
	header_remove("Expires");
	header_remove("Cache-Control");
			
	// Pagelayer Template Loading Mechanism
	include_once(PAGELAYER_DIR.'/css/givecss.php');
	
	exit();
	
}

// Shortcodes Widget Handler
add_action('wp_ajax_pagelayer_get_section_shortcodes', 'pagelayer_get_section_shortcodes');
function pagelayer_get_section_shortcodes(){
	
	global $pagelayer;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!current_user_can('edit_posts')){
		$ret['error'][] = __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	$data = '';
	if(isset($_REQUEST['pagelayer_section_id'])){
		
		$get_url = PAGELAYER_API.'/library.php?give_id='.$_REQUEST['pagelayer_section_id'].(!empty($pagelayer->license['license']) ? '&license='.$pagelayer->license['license'] : '');
		
		// For SitePad users
		if(function_exists('get_softaculous_file')){
			$get_url = get_softaculous_file($get_url, 1);
		}
		
		$fetch = wp_remote_get($get_url, array('timeout' => 60));
		
		if ( is_array( $fetch ) && ! is_wp_error( $fetch ) && isset( $fetch['body'] ) ) {
			$data = json_decode( $fetch['body'], true ); // use the content
		}else{
			$data['error'] = __pl('The response was malformed');
			pagelayer_json_output($data);
		}
	}
	
	if(isset($_REQUEST['postID'])){
		$post_id = (int) $_REQUEST['postID'];
		
		if(!empty($post_id)){
			$post = get_post( $post_id );
			// Need to make the reviews post global 
			if ( !empty( $post ) ) {
				$GLOBALS['post'] = $post;
				
				$GLOBALS['wp_query'] = new WP_Query([
					'post_type' => $GLOBALS['post']->post_type,
					'post__in' => array($post_id),
				]);
			}
		}
	}
	
	// Upload the images if any in the shortcode
	preg_match_all('/"'.preg_quote('{{pl_lib_images}}', '/').'([^"]*)"/is', $data['code'], $matches);
	
	foreach($matches[0] as $k => $v){
		$image_url = trim($v, '"\'');
		$urls[$image_url] = $image_url;
	}
	
	foreach($urls as $k => $image_url){
		
		$file = basename($image_url);
		$id = 0;
		
		// Upload this
		if(!empty($data[$file])){
			
			$id = pagelayer_upload_media($file, base64_decode($data[$file]));
			
			if(!empty($id)){
				$data['code'] = str_replace('"'.$image_url.'"', '"'.$id.'"', $data['code']);
			}
		}
		
	}

	// Load shortcodes
	pagelayer_load_shortcodes();
	
	if(!empty($data['code'])){
		$data['code'] = pagelayer_the_content($data['code'], true);
	}
	
	pagelayer_json_output($data);

}

// Shortcodes Widget Handler
add_action('wp_ajax_pagelayer_get_section_blocks', 'pagelayer_get_section_blocks');
function pagelayer_get_section_blocks(){
	
	global $pagelayer;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!current_user_can('edit_posts')){
		$ret['error'][] = __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	$data = '';
	if(isset($_REQUEST['pagelayer_section_id'])){
		
		$get_url = PAGELAYER_API.'/library.php?give_id='.$_REQUEST['pagelayer_section_id'].(!empty($pagelayer->license['license']) ? '&license='.$pagelayer->license['license'] : '');
		
		// For SitePad users
		if(function_exists('get_softaculous_file')){
			$get_url = get_softaculous_file($get_url, 1);
		}
		
		$fetch = wp_remote_get($get_url, array('timeout' => 60));
		
		if ( is_array( $fetch ) && ! is_wp_error( $fetch ) && isset( $fetch['body'] ) ) {
			$data = json_decode( $fetch['body'], true ); // use the content
		}else{
			$data['error'] = __pl('The response was malformed');
			pagelayer_json_output($data);
		}
	}
	
	// Upload the images if any in the shortcode
	preg_match_all('/"'.preg_quote('{{pl_lib_images}}', '/').'([^"]*)"/is', $data['code'], $matches);
	
	foreach($matches[0] as $k => $v){
		$image_url = trim($v, '"\'');
		$urls[$image_url] = $image_url;
	}
	
	foreach($urls as $k => $image_url){
		
		$file = basename($image_url);
		$id = 0;
		
		// Upload this
		if(!empty($data[$file])){
			
			$id = pagelayer_upload_media($file, base64_decode($data[$file]));
			
			if(!empty($id)){
				$data['code'] = str_replace('"'.$image_url.'"', '"'.$id.'"', $data['code']);
			}
		}
		
	}
	
	if ( false !== strpos( $data['code'], '[pl_' ) ) {
		// Load shortcodes
		pagelayer_load_shortcodes();
		
		// Load Parse Shortcodes
		include_once(PAGELAYER_DIR.'/main/parse-shortcodes.php');
		
		$data['code'] = pagelayer_do_shortcode_to_block($data['code']);
	}
	
	$data['code'] = pagelayer_add_tmp_atts($data['code']);
	
	pagelayer_json_output($data);

}

// Get the Site Title
add_action('wp_ajax_pagelayer_fetch_site_title', 'pagelayer_fetch_site_title');
function pagelayer_fetch_site_title(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	echo get_bloginfo('name');
	wp_die();
}

// Update the Site Title
add_action('wp_ajax_pagelayer_update_site_title', 'pagelayer_update_site_title');
function pagelayer_update_site_title(){
	global $wpdb;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$site_title = $_POST['site_title'];
	
	if(!current_user_can('manage_options')){
		$ret['error'][] = __pl('no_permission');
		pagelayer_json_output($ret);
	}

	update_option('blogname', $site_title);
	
	wp_die();
}

// Show the SideBars
add_action('wp_ajax_pagelayer_fetch_sidebar', 'pagelayer_fetch_sidebar');
function pagelayer_fetch_sidebar(){
	
	global $wp_registered_sidebars;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	// Create a list
	$pagelayer_wp_widgets = array();
	
	foreach($wp_registered_sidebars as $v){
		$pagelayer_wp_widgets[$v['id']] = $v['name'];
	}
	
	$id = @$_REQUEST['sidebar'];
		
	if(function_exists('dynamic_sidebar') && !empty($pagelayer_wp_widgets[$id])) {
		ob_start();
		dynamic_sidebar($id);
		$result = ob_get_clean();
	}else{
		$result =  __pl('no_widget_area');
	}
	
	echo $result;
	wp_die();
	
}

// Show the primary menu !
add_action('wp_ajax_pagelayer_fetch_primary_menu', 'pagelayer_fetch_primary_menu');
function pagelayer_fetch_primary_menu(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(isset($_POST['nav_list'])){
		$_POST['nav_list'] = (int) $_POST['nav_list'];
		
		// Load Pagelayer nav menu walker
		include_once(PAGELAYER_DIR.'/main/nav_walker.php');
		
		$postID = (int) $_REQUEST['postID'];
		
		// To on live mode
		$GLOBALS['post'] = get_post($postID);
		$GLOBALS['wp_query'] = new WP_Query([
			'post_type' => $GLOBALS['post']->post_type,
			'post__in' => array($postID),
		]);
		
		// Load short
		pagelayer_load_shortcodes();
		
		wp_nav_menu([
			'menu'   => wp_get_nav_menu_object($_POST['nav_list']),
			'menu_id' => $_POST["nav_list"],
			'menu_class' => 'pagelayer-wp_menu-ul',
			'walker' => new Pagelayer_Walker_Nav_Menu(),
			//'theme_location' => 'primary',
			'echo'	 => true,
		]);
	}
	
	wp_die();
}

// Save post revision 
add_action('wp_ajax_pagelayer_create_post_autosave', 'pagelayer_create_post_autosave');
function pagelayer_create_post_autosave(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$ret = array();
	$postID = (int) $_GET['postID'];	
	$content = $_REQUEST['pagelayer_post_content'];
	
	// Decode base64 data
	$content = base64_decode($content);
	$content = wp_slash($content);
	
	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($postID)){
		$ret['error'][] =  __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	if(empty($postID)){
		$ret['error'] =  __pl('invalid_post_id');
	}else{
		
		$post = array(
			'post_ID' => $postID,
			'post_content' => $content,
		);
		
		$ret['id'] = wp_create_post_autosave($post);
	}
	
	$ret['url'] = get_preview_post_link($postID);
	
	pagelayer_json_output($ret);
	
}

// Get post revision 
add_action('wp_ajax_pagelayer_get_revision', 'pagelayer_get_revision');
function pagelayer_get_revision(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$ret = array();
	$postID = (int) $_GET['postID'];

	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($postID)){
		$ret['error'][] =  __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	if(empty($postID)){
		$ret['error'] =  __pl('invalid_post_id');
	}else{
		$ret = pagelayer_get_post_revision_by_id($postID);
	}
	
	pagelayer_json_output($ret);
	
}

// Apply post revision
add_action('wp_ajax_pagelayer_apply_revision', 'pagelayer_apply_revision');
function pagelayer_apply_revision(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$revisionID = (int) $_REQUEST['revisionID'];
	$parID = wp_get_post_parent_id($revisionID);
	$ret = array();
	
	if(empty($parID)){
		$parID = $revisionID;
	}
	
	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($parID)){
		$ret['error'][] =  __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	if(empty($revisionID)){
		$ret['error'] =  __pl('invalid_post_id');
	}else{
		
		$post = get_post( $revisionID );
		
		if ( empty( $post ) ) {
			$ret['error'] =  __pl('invalid_revision');
			pagelayer_json_output($ret);
		}
		
		// Need to make the reviews post global 
		$GLOBALS['post'] = $post;
		$GLOBALS['wp_query'] = new WP_Query([
			'post_type' => $GLOBALS['post']->post_type,
			'post__in' => array($parID),
		]);
		
		// Need to reload the shortcodes
		pagelayer_load_shortcodes();
		
		$ret['id'] = $revisionID;
		$ret['content'] = pagelayer_the_content($post->post_content, true);
		
		if(is_wp_error($post)) {
			$ret['error'] =  __pl('rev_load_error');
		}else{
			$ret['success'] = __pl('rev_load_success');
		}
		
		wp_reset_postdata();
	}
	
	pagelayer_json_output($ret);
	
}

// Get post revision 
add_action('wp_ajax_pagelayer_delete_revision', 'pagelayer_delete_revision');
function pagelayer_delete_revision() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$revisionID = (int) $_REQUEST['revisionID'];
	$parID = wp_get_post_parent_id($revisionID);
	$ret = array();
	
	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($parID)){
		$ret['error'][] =  __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	if(empty($revisionID)){
		$ret['error'] =  __pl('invalid_post_id');
	}else{

		$revision = get_post( $revisionID );

		if ( empty( $revision ) ) {
			$ret['error'] =  __pl('invalid_revision');
		}else{

			if ( ! current_user_can( 'delete_post', $parID ) ) {
					$ret['error'] =  __pl('access_denied');
					pagelayer_json_output($ret);
			}

			$deleted = wp_delete_post_revision( $revision->ID );

			if ( ! $deleted || is_wp_error( $deleted ) ) {
				$ret['error'] =  __pl('delete_rev_error');
			}else{
				$ret['success'] =  __pl('delete_rev_success');
			}
		}
	}
	
	pagelayer_json_output($ret);
	
}

// Get post navigation 
add_action('wp_ajax_pagelayer_post_nav', 'pagelayer_post_nav');
function pagelayer_post_nav() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!isset($_REQUEST['data']) || !isset($_REQUEST['postID'])){
		return;
	}
	
	$el['atts'] = $_REQUEST['data'];
	
	$post = get_post($_REQUEST['postID']);
	
	// Need to make this post global
	$GLOBALS['post'] = $post;
	
	$in_same_term = false;
	$taxonomies = 'category';
	$title = '';
	$arrows_list = $el['atts']['arrows_list'];
	
	if($el['atts']['in_same_term']){
		$in_same_term = true;
		$taxonomies = $el['atts']['taxonomies'];
	}
	
	if($el['atts']['post_title']){
		$title = '<span class="pagelayer-post-nav-title">%title</span>';
	}
	
	$next_label = '<span class="pagelayer-next-holder">
		<span class="pagelayer-post-nav-link"> '.$el["atts"]["next_label"].'</span>'.$title.'
	</span>
	<span class="pagelayer-post-nav-icon fa fa-'.$arrows_list.'-right"></span>';
		
	$prev_label = '<span class="pagelayer-post-nav-icon fa fa-'.$arrows_list.'-left"></span>
	<span class="pagelayer-next-holder">
		<span class="pagelayer-post-nav-link"> '.$el["atts"]["prev_label"].'</span>'.$title.'
	</span>';

	$el['atts']['next_link'] = get_next_post_link('%link', $next_label, $in_same_term, '', $taxonomies); 

	$el['atts']['prev_link'] = get_previous_post_link('%link', $prev_label, $in_same_term, '', $taxonomies ); 
	
	pagelayer_json_output($el);
	
}

// Get post comment template
add_action('wp_ajax_pagelayer_post_comment', 'pagelayer_post_comment');
function pagelayer_post_comment() {
	global $post;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!isset($_REQUEST['postID'])){
		return true;
	}
	
	$GLOBALS['post'] = get_post($_REQUEST['postID']);
	$GLOBALS['withcomments'] = true;
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	$el = [];
	pagelayer_sc_post_comment($el);
	
	echo $el['atts']['post_comment'];
	
	wp_die();
		
}

// Get post comment template 
add_action('wp_ajax_pagelayer_post_info', 'pagelayer_post_info');
function pagelayer_post_info() {
	global $post;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	if(!isset($_REQUEST['postID']) || !isset($_REQUEST['el'])){
		return true;
	}
	
	$el['atts'] = $_REQUEST['el'];
	
	$GLOBALS['post'] = get_post($_REQUEST['postID']);
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	pagelayer_sc_post_info_list($el);
	
	pagelayer_json_output($el['atts']);
		
}

// Get the Featured Image
add_action('wp_ajax_pagelayer_fetch_featured_img', 'pagelayer_fetch_featured_img');
function pagelayer_fetch_featured_img(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$id = get_post_thumbnail_id( (int) $_POST['post_id'] );	
	$img = [];
	
	if(empty($id)){
		pagelayer_json_output($img);	
	}

	$img = pagelayer_image($id);
	pagelayer_json_output($img);
	
}

// Get the postfolio posts
add_action('wp_ajax_pagelayer_fetch_posts', 'pagelayer_fetch_posts');
function pagelayer_fetch_posts(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	// TODO : Allowed
	echo pagelayer_widget_posts($_POST);
	
	wp_die();
}

// Get the Posts
add_action('wp_ajax_pagelayer_posts_data', 'pagelayer_posts_data');
function pagelayer_posts_data(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	// TODO : Allowed
	echo pagelayer_posts($_POST);
	wp_die();
}

// Get the Posts
add_action('wp_ajax_pagelayer_archive_posts_data', 'pagelayer_archive_posts_data');
function pagelayer_archive_posts_data(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	// Set excerpt length
	if(!empty($_POST['atts']['exc_length'])){
		$exc_length = (int) $_POST['atts']['exc_length'];
		add_filter( 'excerpt_length', function($length) use($exc_length){
			return $exc_length;
		}, 999 );
	}
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	foreach($_POST['atts'] as $k => $v){
		$v = pagelayer_maybe_implode($v);
		$r[] = esc_html($k).'="'.pagelayer_escapeHTML($v).'"';
	}
	
	$string = implode(' ', $r);
	if(preg_match('/\]/is', $string)){
		die('Hacking Attempt');
	}
	
	$sc = '[pl_archive_posts '.$string.'][/pl_archive_posts]';
	
	// TODO : Allowed
	echo pagelayer_the_content($sc);
	wp_die();
}

// Handle Contact Form Data
add_action('wp_ajax_pagelayer_contact_submit', 'pagelayer_contact_submit');
add_action('wp_ajax_nopriv_pagelayer_contact_submit', 'pagelayer_contact_submit' );
function pagelayer_contact_submit(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_global', 'pagelayer_nonce');
	
	// A filter to short circuit this contact form
	$continue = apply_filters('pagelayer_contact_submit_start', 1);	
	if(empty($continue)){
		return false;
	}
	
	$formdata = $_POST;
	// NOTE : NEVER add anything to $formdata except $_POST vars
	
	if(isset($_POST['g-recaptcha-response']) ){
		
		if(!pagelayer_captcha_verify()){
			$wp['failed'] = get_option('pagelayer_recaptcha_failed', __pl('cap_ver_fail'));
			pagelayer_json_output($wp);
		}
		
		unset($formdata['g-recaptcha-response']);
	}
	
	// Unset the nonce
	unset($formdata['pagelayer_nonce']);
	
	$to_mail = get_option('pagelayer_cf_to_email');
	$from_mail = get_option('pagelayer_cf_from_email');
	$subject = get_option('pagelayer_cf_subject');
	$additional_headers = get_option('pagelayer_cf_headers');
	$reply_to = '';
	$body = '';
	$headers = '';
	$custom_templ = array();
	$use_custom = false;
	$use_html = false;
	$pagelayer_id = sanitize_text_field($formdata['cfa-pagelayer-id']);
	
	if(isset($formdata['cfa-custom-template']) && !empty($formdata['cfa-post-id'])){
		$post_id = (int) $formdata['cfa-post-id'];
		
		if(!empty($post_id)){
			$contact_array = get_post_meta($post_id, 'pagelayer_contact_templates', true);
			
			if(!empty($contact_array) && !empty($contact_array[$pagelayer_id])){
				$custom_templ = $contact_array[$pagelayer_id];
				$use_custom = true;
			}
		}
	}
	
	if($use_custom && !empty($custom_templ)){
		
		if(!empty($custom_templ['to_email'])){
			$to_mail = $custom_templ['to_email'];
		}
		
		if(!empty($custom_templ['from_email'])){
			$from_mail = $custom_templ['from_email'];
		}
		
		if(!empty($custom_templ['cont_subject'])){
			$subject = $custom_templ['cont_subject'];
		}
		
		if(!empty($custom_templ['cont_header'])){
			$additional_headers = $custom_templ['cont_header'];
		}
		
		if(!empty($custom_templ['cont_body'])){
			$body = $custom_templ['cont_body'];
		}
		
		if(!empty($custom_templ['cont_use_html'])){
			$use_html = true;
			$headers .= "Content-Type: text/html\n";
		}
	}
	
	if(!empty($from_mail)){
		$headers .= "From: $from_mail\n";
	}
	
	if ( !empty($additional_headers) ) {
		$headers .= $additional_headers . "\n";
	}
	
	if ( empty($body) ) {
	
		// Make the email content
		foreach($formdata as $k => $i){
			
			$not_allow = ['cfa-pagelayer-id', 'cfa-redirect', 'cfa-post-id', 'cfa-custom-template', 'pagelayer-contact-submit'];
			if(in_array($k, $not_allow)){
				continue;
			}
			
			if(is_array($i)){
				$i = pagelayer_flat_join($i);
			}
			
			// Record a reply to if it is to be used
			if(is_email(trim($i)) && empty($reply_to)){
				$reply_to = trim($i);
			}
			
			$body .= $k."\t : \t $".$k."\n";
			
		}
		
		$body .= "\n\n --\n This e-mail was sent from a contact form (".get_home_url().")";
	
	}
	
	// Dow we have a reply to in the headers ?
	if(!preg_match('/reply\-to/is', $headers) && !empty($reply_to)){
		$headers .= "Reply-To: $reply_to\n";
	}
	
	// Add attachment
	if(!empty($_FILES)){
		add_action('phpmailer_init', 'pagelayer_cf_email_attachment', 10, 1);
	}
	
	// If we are using HTML, then we should escape html as well
	if(!empty($use_html)){
		foreach($formdata as $k => $i){
			
			if(is_array($i)){
				$i = pagelayer_flat_join($i);
			}
			
			$formdata[$k] = esc_html($i);
		}
	}
	
	// Add Site Title as option in formdata
	$formdata['site_title'] = get_bloginfo( 'name' );
	
	// Do parse a variables
	$to_mail = pagelayer_replace_vars($to_mail, $formdata, '$');
	$from_mail = pagelayer_replace_vars($from_mail, $formdata, '$');
	$subject = pagelayer_replace_vars($subject, $formdata, '$');
	$headers = pagelayer_replace_vars($headers, $formdata, '$');
	$body = pagelayer_replace_vars($body, $formdata, '$');
	
	if ( $use_html && ! preg_match( '%<html[>\s].*</html>%is', $body ) ) {
		$header = '<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>' . esc_html( $subject ) . '</title></head>
<body>';

		$footer = '</body></html>';

		$body = $header . wpautop( $body ) . $footer;
	}
	
	$to_mail = apply_filters('pagelayer_contact_send', $to_mail, $formdata);
	
	// Send the email
	if(!empty($to_mail)){
		$r = wp_mail( $to_mail, $subject, $body, $headers );
	}
	
	if($r == TRUE){
		$wp['success'] = pagelayer_get_option( 'pagelayer_cf_success' );
	}else{
		$wp['failed'] = pagelayer_get_option( 'pagelayer_cf_failed' );
	}
	
	pagelayer_json_output($wp);
	
}

// Handle Login Submit
add_action('wp_ajax_pagelayer_login_submit', 'pagelayer_login_submit');
add_action('wp_ajax_nopriv_pagelayer_login_submit', 'pagelayer_login_submit');
function pagelayer_login_submit(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_global', 'pagelayer_nonce');

	$creds = array();
	$creds['user_login'] = $_REQUEST['username'];
	$creds['user_password'] = $_REQUEST['password'];
	$creds['remember'] = $_REQUEST['remember_me'];
	
	// Login the user
	$user = wp_signon( $creds, false );	
	
	if ( is_wp_error($user) ){
		$data['error'] = $user->get_error_message();
	}else{
	
		// If After logout URL, then save
		if(!empty($_REQUEST['logout_url'])){
			update_user_option($user->ID, 'pagelayer_logout_url', $_REQUEST['logout_url']);
		}
	
		$data['redirect'] = (empty($_REQUEST['login_url']) ? '' : $_REQUEST['login_url']);
		$data['error'] = '';
	}

	pagelayer_json_output($data);
	
}

// Get Page List for SiteMap
add_action('wp_ajax_pagelayer_get_pages_list', 'pagelayer_get_pages_list');
function pagelayer_get_pages_list(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$args = array(
		'post_type' => $_POST['type'],
		'orderby' => $_POST['post_order'],
		'order' => $_POST['order'],
		'hierarchical' => (empty($_POST['hier']) || $_POST['hier'] == null ? '' : $_POST['hier']),
		'number' => (empty($_POST['depth']) || $_POST['depth'] == null ? '' : $_POST['depth']),
		'posts_per_page' => -1,
	);
	
	$option = '<ul>';
	$pages = new WP_Query($args);
	$posts = $pages->posts;
	foreach ( $posts as $page ) {
		$option .= '<li class="pagelayer-sitemap-list-item" data-postID="'.$page->ID.'"><a class="pagelayer-ele-link" href="'.$page->guid.'">'.$page->post_name.'</a></li>';
	}
	$option .= '</ul>';
	
	echo $option;

	wp_die();
}

// Get the data for template
add_action('wp_ajax_pagelayer_search_ids', 'pagelayer_search_ids');
function pagelayer_search_ids() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_builder', 'pagelayer_nonce');
	
	if ( empty( $_POST['filter_type'] ) || empty( $_POST['search'] ) ) {
		wp_die();
	}

	$sel_opt = '';

	switch ( $_POST['filter_type'] ) {
		case 'taxonomy':
			$query_params = [
				'taxonomy' => $_POST['object_type'],
				'search' => $_POST['search'],
				'hide_empty' => false,
			];

			$terms = get_terms( $query_params );

			global $wp_taxonomies;

			foreach ( $terms as $term ) {
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $term->term_taxonomy_id .'">'. $term->name .'</span>';
			}

			break;

		case 'post':
			$query_params = [
				'post_type' => $_POST['object_type'], //$this->extract_post_type( $data ),
				's' => $_POST['search'],
				'posts_per_page' => -1,
			];

			if ( 'attachment' === $query_params['post_type'] ) {
				$query_params['post_status'] = 'inherit';
			}

			$query = new \WP_Query( $query_params );

			foreach ( $query->posts as $post ) {
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $post->ID .'">'. $post->post_title .'</span>';
			}
			break;

		case 'author':
			$query_params = [
				'capability' => array( 'edit_posts' ),
				'fields' => [
					'ID',
					'display_name',
				],
				'search' => '*' . $_POST["search"] . '*',
				'search_columns' => [
					'user_login',
					'user_nicename',
				],
			];			
			
			// Capability queries were only introduced in WP 5.9.
			if( version_compare( $GLOBALS['wp_version'], '5.9-alpha', '<' ) ){
				$args['who'] = 'authors';
				unset( $args['capability'] );
			}

			$user_query = new \WP_User_Query( $query_params );

			foreach ( $user_query->get_results() as $author ) {
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $author->ID .'">'. $author->display_name .'</span>';
			}
			break;
			
		/* case 'menu':
			
			$menuItems = wp_get_nav_menu_items( (int)$_POST['object_type']);
			
			foreach ( $menuItems as $item ) {
				
				if($item -> menu_item_parent !=0 ){
					continue;
				}
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $item -> ID .'">'. $item -> title.'</span>';
			}

			break; */
			
		default:
			$sel_opt = 'Result Not Found';
	}
	
	if(!empty($sel_opt)){
		echo $sel_opt;
	}else{
		echo 'Result Not Found';
	}
	
	wp_die();
}

// Save the post data from pagelayer setting page
add_action('wp_ajax_pagelayer_save_template', 'pagelayer_save_template');
function pagelayer_save_template() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_builder', 'pagelayer_nonce');
	
	$done = [];
	
	$post_id = (int) $_GET['postID'];

	// Are you allowed to edit ?
	if(!empty($post_id) && !pagelayer_user_can_edit($post_id)){
		$done['error'][] =  __pl('no_permission');
		pagelayer_json_output($done);
	}
	
	// We need to create the post
	if(empty($post_id)){
	
		if (!current_user_can('edit_posts')) {
			$done['error'] =  __pl('access_denied');
			pagelayer_json_output($done);
		}
	
		// Get the template type
		if(empty($_POST['pagelayer_template_type'])){
			$done['error'] = __pl('temp_error_type');
			pagelayer_json_output($done);
		}
		
		$ret = wp_insert_post([
			'post_title' => $_POST['pagelayer_lib_title'],
			'post_type' => 'pagelayer-template',
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed'
		]);
		
		// An error occured
		if(is_wp_error($ret)){
			$done['error'] = __pl('temp_error').' : '.$ret->get_error_message();
			pagelayer_json_output($done);
		}
		
		$post_id = $ret;
		$done['id'] = $post_id;
		
		// Save our template type
		$ret = update_post_meta($post_id, 'pagelayer_template_type', $_POST['pagelayer_template_type']);
		
	}
	
	// The ID in consideration
	$done['id'] = $post_id;
	
	// Check if the post title in not empty
	if(!empty($_POST['pagelayer_lib_title'])){
		
		$post = array(
					'ID' => $post_id,
					'post_title' => $_POST['pagelayer_lib_title'],
				);

		// Update the post into the database
		$ret = wp_update_post($post);
		
	}
	
	// Save template library display conditions
	$condi_array = array();
	$condi_len = count($_POST['pagelayer_condition_type']);
	if($_POST['pagelayer_template_type'] != 'section'){
		for( $i =0; $i < $condi_len; $i++ ){
			$condi_array[$i] = array(
				'type' => $_POST['pagelayer_condition_type'][$i],
				'template' => $_POST['pagelayer_condition_name'][$i],
				'sub_template' => $_POST['pagelayer_condition_sub_template'][$i],
				'id' => $_POST['pagelayer_condition_id'][$i],
			);
		}
	}
	//print_r($condi_array);
	
	$ret = update_post_meta($post_id, 'pagelayer_template_conditions', $condi_array);
	
	if(is_wp_error($post_id)){
		$done['error'] = __pl('temp_error').' : '.$ret->get_error_message();
	}else{
		$done['success'] =  __pl('temp_update_success');
	}

	pagelayer_json_output($done);
	
}

// Products Categories Handler
add_action('wp_ajax_pagelayer_product_categories', 'pagelayer_product_categories');
function pagelayer_product_categories(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$attributes = '';
	$attributes .= ' number="'. $_POST['atts']['number'] .'" ';
	$attributes .= ' columns="'. $_POST['atts']['columns'] .'" ';
	$attributes .= ' hide_empty="'. (!empty($_POST['atts']['hide_empty']) ? 1 : 0) .'" ';
	$attributes .= ' orderby="'. $_POST['atts']['nuorderbymber'] .'" ';
	$attributes .= ' order="'. $_POST['atts']['order'] .'" ';	
	
	if ( 'by_id' === $_POST['atts']['source'] ) {
		$attributes .= ' ids="'. $_POST['atts']['by_id'] .'" ';
	} elseif ( 'by_parent' === $_POST['atts']['source'] ) {
		$attributes .= ' parent="'. $_POST['atts']['parent'] .'" ';
	} elseif ( 'current_subcategories' === $_POST['atts']['source'] ) {
		$attributes .= ' parent="'. get_queried_object_id() .'" ';
	}

	$shortcode = '[product_categories '. $attributes .']';
	
	// do_shortcode the shortcode
	echo pagelayer_the_content($shortcode);
		
	wp_die();
}

// Products Categories Handler
add_action('wp_ajax_pagelayer_products_ajax', 'pagelayer_products_ajax');
function pagelayer_products_ajax(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( WC()->session ) {
		wc_print_notices();
	}
	
	$no_found = $_POST['atts']['no_found'];
	
	$attributes = '';
	$type = $_POST['atts']['source'];
	$attributes .= ' columns="'. $_POST['atts']['columns'] .'" ';
	$attributes .= ' rows="'. $_POST['atts']['rows'] .'" ';
	$attributes .= ' paginate="'. (!empty($_POST['atts']['paginate']) ? true : false) .'" ';
	$attributes .= ' orderby="'. $_POST['atts']['orderby'] .'" ';
	$attributes .= ' order="'. $_POST['atts']['order'] .'" ';	
	$attributes .= ' cache="false" ';	
	
	// Hide the catalog order
	if( empty($_POST['atts']['allow_order']) ){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	}
	
	// Hide the result count
	if( empty($_POST['atts']['show_result']) ){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	}
	
	if( $type == 'by_id' ){
		$type = 'products';
		$attributes .= ' ids="'. (!empty($_POST['atts']['ids']) ? $_POST['atts']['ids'] : '') .'" ';	
	}elseif( $type == 'pagelayer_current_query' ){
		
		$atts['paginate'] = (!empty($_POST['atts']['paginate']) ? true : false);
		$atts['cache'] = false;
				
		$type = 'pagelayer_current_query';
		
		// Set the current query
		add_action( 'woocommerce_shortcode_products_query', 'pagelayer_shortcode_current_query', 10, 10);
		
		// If product not found
		add_action( "woocommerce_shortcode_{$type}_loop_no_results", function ($attributes) use ($no_found){
			echo '<div class="pagelayer-product-no-found">'.$no_found.'</div>';
		} );
		
		// Get the products list
		$shortcode = new WC_Shortcode_Products( $atts, $type );
			
		echo $shortcode->get_content();
		return true;
	}
		
	$shortcode = '['.$type.' '. $attributes .']';
	
	$content = pagelayer_the_content($shortcode);
	
	// If product not found
	if('<div class="woocommerce columns-'.$_POST['atts']['columns'] .' "></div>' == $content){
		$content = '<div class="pagelayer-product-no-found">'. $no_found .'</div>';
	}
	
	echo $content;
		
	wp_die();
}

// Get Taxamony List for SiteMap
add_action('wp_ajax_pagelayer_get_taxonomy_list', 'pagelayer_get_taxonomy_list');
function pagelayer_get_taxonomy_list(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$args = array(
		'title_li' => 0,
		'orderby' => $_POST['post_order'],
		'order' => $_POST['order'],
		'style' => '',
		'hide_empty' => $_POST['empty'],
		'echo' => false,
		'hierarchical' => (empty($_POST['hier']) || $_POST['hier'] == null ? '' : $_POST['hier']),
		'taxonomy' => $_POST['type'],
		'depth' => (empty($_POST['depth']) || $_POST['depth'] == null ? '' : $_POST['depth']),		
	);

	$taxonomies = get_categories( $args );
	
	$option = '<ul>';	
	foreach ( $taxonomies as $taxonomy ) {
		$option .= '<li class="pagelayer-sitemap-list-item" data-postID="'.$taxonomy->term_id.'"><a class="pagelayer-ele-link" href="'.get_term_link($taxonomy->term_id).'">'.$taxonomy->name.'</a></li>';
	}
	$option .= '</ul>'; 
	
	echo $option;
	wp_die();	
}

// Export the template
add_action('wp_ajax_pagelayer_export_template', 'pagelayer_export_template');
function pagelayer_export_template(){
	
	global $pagelayer;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_builder', 'pagelayer_nonce');
	
	$done = [];
	
	if(!current_user_can('edit_theme_options')){		
		$done['error'][] = __pl('no_permission');
		pagelayer_json_output($done);
	}
	
	// Load the templates
	pagelayer_builder_load_templates();
	
	if(empty($pagelayer->templates)){
		$done['error'] = __pl('temp_export_empty');
		pagelayer_json_output($done);
	}
	
	// Load Shortcodes
	pagelayer_load_shortcodes();
	
	// Get the active theme
	$theme_dir = get_stylesheet_directory();
	$conf = [];
	
	$pagelayer->export_mode = 1;
	
	// Write the files
	foreach($pagelayer->templates as $k => $v){
		
		// Are there specific templates to export
		if(!empty($_POST['templates'])){
			if(!isset($_POST['templates'][$v->ID])){
				continue;
			}
		}
		
		// Only blocks allowed
		if(!has_blocks($v->post_content) && !empty($v->post_content)){
			$done['error'] = 'The pagelayer template '.$v->ID.' has Shortcodes which is not allowed for export !';
			pagelayer_json_output($done);
		}
		
		$v->post_name = (empty($v->post_name) && $v->post_status == 'draft') ? sanitize_title($v->post_title).'-draft' : $v->post_name;
		
		// Write the content
		file_put_contents($theme_dir.'/'.$v->post_name.'.pgl', pagelayer_export_content($v->post_content));		
		$conf[$v->post_name] = [
			'type' => get_post_meta($v->ID, 'pagelayer_template_type', true),
			'title' => $v->post_title,
			'conditions' => get_post_meta($v->ID, 'pagelayer_template_conditions', true),
		];
	}
	
	// Write the config
	file_put_contents($theme_dir.'/pagelayer.conf', json_encode($conf, JSON_PRETTY_PRINT));
			
	$conf = [];
	
	// Load the other posts
	foreach($pagelayer->settings['post_types'] as $type){
		
		// Anything to export for users ?
		if(!empty($_POST[$type]) && is_array($_POST[$type])){
			
			mkdir($theme_dir.'/data/');
			mkdir($theme_dir.'/data/'.$type);
			
			$pids = [];
			
			foreach($_POST[$type] as $k => $v){
				$pids[] = (int) $k;
			}
			
			// Load the type
			$_query = new WP_Query([
				'post_type' => $type,
				'status' => 'publish',
				'post__in' => $pids,
				'posts_per_page' => -1,
			]);
			
			$posts = $_query->posts;
		
			// Write the files
			foreach($posts as $k => $v){
		
				// Only blocks allowed
				if(!has_blocks($v->post_content) && !empty($v->post_content)){
					$done['error'] = 'The '.$type.' '.$v->ID.' has Shortcodes which is not allowed for export !';
					pagelayer_json_output($done);
				}
				
				$v->post_name = (empty($v->post_name) && $v->post_status == 'draft') ? sanitize_title($v->post_title).'-draft' : $v->post_name;
				
				file_put_contents($theme_dir.'/data/'.$type.'/'.$v->post_name, pagelayer_export_content($v->post_content));
				unset($v->post_content);
				
				$meta = get_post_meta($v->ID);
				$meta = array_combine(array_keys($meta), array_column($meta, 0));
				
				// Export media
				if(!empty($meta['_thumbnail_id'])){
					
					$file = pagelayer_export_media_files($meta['_thumbnail_id'], $exp_img_url);
					
					// Did it export ?
					if(!empty($file)){
						$meta['_thumbnail_id'] = $exp_img_url;
					}
					
				}
				
				// Also put the meta
				file_put_contents($theme_dir.'/data/'.$type.'/'.$v->post_name.'.meta', json_encode($meta, JSON_PRETTY_PRINT));
				
				//Export taxonomies in post
				$taxonomies = get_object_taxonomies( $v->post_type, 'objects' );
				$post_taxonomies = wp_filter_object_list( $taxonomies, [
					'public' => true,
					'show_in_nav_menus' => true,
				] );
								
				foreach( $post_taxonomies as $slug => $object ){
					
					if(empty($v->taxonomies) || !is_array($v->taxonomies)){
						$v->taxonomies = array();
					}
					
					$tax_name = $object->name;
					$the_terms = get_the_terms($v->ID, $tax_name);
					$v->taxonomies[$tax_name] = '';
					
					if(!empty($the_terms)){
						$v->taxonomies[$tax_name] = implode(',', array_column($the_terms, 'term_id'));
					}
				}
				
				$conf[$type][$v->post_name] = $v;
				
				do_action('pagelayer_'.$type.'_exported', $v, $theme_dir);
				
			}
			
			ksort($conf[$type]);
			
		}
	
	}
	
	// Export menus
	if(!empty($pagelayer->export_menus) && is_array($pagelayer->export_menus)){
		
		mkdir($theme_dir.'/data/menus');
		
		foreach($pagelayer->export_menus as $k => $v){
			
			$menu = (int) $k;
			$menu = wp_get_nav_menu_object( $menu );
			
			if(empty($menu)){
				$done['error'] = 'Could not export menu ID - '.$k;
				continue;
			}
			
			// Menu Items
			$menu_items = wp_get_nav_menu_items( $menu->term_id );
			$data = [];
			
			if(is_array($menu_items) && !empty($menu_items)){
				foreach($menu_items as $kk => $singlenav){
					//$navmetas = get_post_meta($singlenav->ID);
					//$navmetas = array_combine(array_keys($navmetas), array_column($navmetas, 0));
					$data[$kk]['post'] = $singlenav;
					$navmetas = array();
					
					$pl_content = get_post_meta($singlenav->ID, '_pagelayer_content', true);
					if(!empty($pl_content)){
						$navmetas['_pagelayer_content'] = pagelayer_export_content($pl_content);
					}
					
					$data[$kk]['post_metas'] = $navmetas;
				}
			}
			
			// Also put the meta
			file_put_contents($theme_dir.'/data/menus/'.$menu->slug, json_encode($data, JSON_PRETTY_PRINT));
			
			$conf['menus'][$menu->slug] = $menu;
			
			do_action('pagelayer_menus_exported', $v, $theme_dir);
		
		}
		
	}
	
	// Export the settings
	$settings = ['pagelayer_content_width', 'pagelayer_body_font', 'pagelayer_tablet_breakpoint', 'pagelayer_mobile_breakpoint', 'pagelayer_header_code','pagelayer_body_open_code', 'pagelayer_footer_code', 'pagelayer_sidebar', 'page_for_posts', 'pagelayer_global_fonts', 'pagelayer_global_colors'];
	
	foreach($settings as $v){
		
		$vv = get_option($v);
		
		if($vv){
			$conf['conf'][$v] = $vv;
		}
	
	}
	
	// Load CSS settings	
	foreach($pagelayer->css_settings as $k => $params){
		foreach($pagelayer->screens as $sk => $sv){
			$suffix = (!empty($sv) ? '_'.$sv : '');
			$setting = empty($params['key']) ? 'pagelayer_'.$k.'_css' : $params['key'];
			$tmp = get_option($setting.$suffix);
			if(!empty($tmp)){
				$conf['conf'][$setting.$suffix] = $tmp;
			}
		}
	}
	
	// Export all the taxonomies
	$post_types = pagelayer_get_public_post_types();
	
	// Export all the Post Type CSS Settings
	foreach ( $post_types as $pt_slug => $type ) {
		
		if ( $pt_slug == 'attachment' ) {
			continue;
		}

		foreach($pagelayer->css_settings as $k => $params){
			foreach($pagelayer->screens as $sk => $sv){
				$suffix = (!empty($sv) ? '_'.$sv : '');
				$setting = empty($params['key']) ? 'pagelayer_'.$k.'_css_'.$pt_slug : $params['key'].'_'.$pt_slug;
				$tmp = get_option($setting.$suffix);

				if(!empty($tmp)){
					$conf['conf'][$setting.$suffix] = $tmp;
				}
			}
		}
	}

	// Export all the taxonomies
	foreach ( $post_types as $post_type => $label ) {
		$type_taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$taxonomies = wp_filter_object_list( $type_taxonomies, [
			'public' => true,
			'show_in_nav_menus' => true,
		] );
		
		foreach( $taxonomies as $slug => $object ){
			
			$query_params = [
				'taxonomy' => $object->name,
				'hide_empty' => false,
			];
			$terms = get_terms( $query_params );
					
			foreach($terms as $term){
				$conf['taxonomies'][$term->term_id] = $term;
			}
		}
			
	}
	
	// Write the config
	if(!empty($conf)){
		file_put_contents($theme_dir.'/pagelayer-data.conf', json_encode($conf, JSON_PRETTY_PRINT));
	}
	
	// Are we to export any media ?
	if(!empty($pagelayer->media_to_export)){		
		// TODO
		//$done['media'] = $pagelayer->media_to_export;
	}
	
	do_action('pagelayer_template_export_completed');
	
	$done['success'] = __pl('temp_export_success');
	
	// Output and die
	pagelayer_json_output($done);
	
}

add_action('wp_ajax_pagelayer_get_cat_checkboxes', 'pagelayer_get_cat_checkboxes');
function pagelayer_get_cat_checkboxes(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$ret = [];
	$cat_name = '';
	
	if(!current_user_can('manage_categories')){		
		$ret['error'] = __pl('no_permission');
		pagelayer_json_output($ret);
	}

	if(empty($_POST['postid']) || !is_numeric($_POST['postid'])){
		$ret['error'] = __pl('invalid_post_id');
		pagelayer_json_output($ret);
	}
	
	$post = (int) $_POST['postid'];
	$post = get_post($post);
	
	if(empty($post) || is_wp_error($post)){
		$ret['error'] = __pl('invalid_post_id');
		pagelayer_json_output($ret);
	}
	
	$cat_name = pagelayer_post_type_category($post->post_type);
	
	if(!empty($_POST['new_cat'])){
		parse_str($_POST['new_cat'], $formdata);
		$ret['new_cat_id'] = wp_insert_category([
			'taxonomy' => $cat_name,
			'cat_name' => $formdata['category_name'],
			'category_parent' => (($formdata['pagelayer_cat_parent'] == 0) ? '' : $formdata['pagelayer_cat_parent'])
		]);
	}
	
	$ret += pagelayer_post_cats($post);
	
	pagelayer_json_output($ret);
	
}

add_action('wp_ajax_pagelayer_get_post_tags', 'pagelayer_get_post_tags');
function pagelayer_get_post_tags(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');	
	
	$ret = [];
	$tag_name = '';
	
	if(!current_user_can('manage_categories')){		
		$ret['error'] = __pl('no_permission');
		pagelayer_json_output($ret);
	}
	
	if(empty($_POST['postid']) || !is_numeric($_POST['postid']) ){
		pagelayer_json_output($ret);
	}
	
	$post = (int) $_POST['postid'];
	$post = get_post($post);
	
	if(empty($post) || is_wp_error($post)){
		$ret['error'] = __pl('invalid_post_id');
		pagelayer_json_output($ret);
	}
	
	$tag_name = pagelayer_post_type_tag($post->post_type);
	
	if(!empty($_POST['new_tag'])){
		$ret['tag_id'] = wp_insert_term($_POST['new_tag'], $tag_name);
		$ret['tag_id'] = $ret['tag_id']['term_id'];
	}
	
	$ret += pagelayer_post_tags($post);
	
	pagelayer_json_output($ret);
	
}

add_action('wp_ajax_pagelayer_custom_font', 'pagelayer_custom_font');
function pagelayer_custom_font(){
		
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$ret = [];
	
	if(empty($_POST['font_name'])){
		pagelayer_json_output($ret);
	}
	
	$name = preg_replace('/_plf$/is', '', pagelayer_optREQ('font_name'));	
	//echo $name;
	
	$args = [
		'post_type' => PAGELAYER_FONT_POST_TYPE,
		'status' => 'publish',
		'posts_per_page' => 1,
		'name' => $name
	];
	
	//var_dump($pagelayer->fonts);
	
	$query = get_posts($args);	
	//var_dump($query);
	
	if(empty($query)){
		pagelayer_json_output($ret);
	}
	
	$post = $query[0];
	$meta_box_value = get_post_meta( $post->ID, 'pagelayer_font_link', true);
	if(empty($meta_box_value)){
		pagelayer_json_output($ret);
	}
		
	$ret['style']= '<style id="'.$name.'_plf" >@font-face { font-family: "'.$name.'_plf"'.'; src: url("'.$meta_box_value.'"); font-weight: 100 200 300 400 500 600 700 800 900;}</style>';
	
	pagelayer_json_output($ret);
	
}

add_action('wp_ajax_pagelayer_trash_post', 'pagelayer_trash_post');
function pagelayer_trash_post(){
		
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$ret = [];
	
	if(empty($_POST['postid']) && !is_numeric($_POST['postid'])){
		$ret = ['error' => __pl('invalid_post_id')];
		pagelayer_json_output($ret);
	}

	if(!current_user_can( 'delete_post', $_POST['postid'] )){
		$ret = ['error' => __pl('no_permission')];
		pagelayer_json_output($ret);
	}

	$ret['url'] = admin_url('/edit.php?post_type=') .get_post_type($_POST['postid']);
	
	wp_trash_post($_POST['postid']);	
	
	pagelayer_json_output($ret);
	
}

add_action('wp_ajax_pagelayer_infinite_posts', 'pagelayer_infinite_posts');
add_action('wp_ajax_nopriv_pagelayer_infinite_posts', 'pagelayer_infinite_posts');
function pagelayer_infinite_posts(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_global', 'pagelayer_nonce');
	
	pagelayer_load_shortcodes();
	
	$tag = 'pl_posts';
	
	if(isset($_REQUEST['data']['tag']) && $_REQUEST['data']['tag'] == 'pl_archive_posts' ){
		$tag = 'pl_archive_posts';
	}
	
	$content = get_comment_delimited_block_content( 'pagelayer/'.$tag, $_REQUEST['data']['atts'] , '');
	$wp['posts'] = pagelayer_the_content($content);
	pagelayer_json_output( $wp );
}

