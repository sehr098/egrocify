<?php

//////////////////////////////////////////////////////////////
//===========================================================
// class.php
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

// Get the client IP
function _pagelayer_getip(){
	if(isset($_SERVER["REMOTE_ADDR"])){
		return $_SERVER["REMOTE_ADDR"];
	}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
		return $_SERVER["HTTP_CLIENT_IP"];
	}
}

// Get the client IP
function pagelayer_getip(){

	global $pagelayer;

	// Just so that we have something
	$ip = _pagelayer_getip();
	$method = @$pagelayer->ip_method;
	
	$pagelayer->ip_method = (int) $method;

	if(isset($_SERVER["REMOTE_ADDR"])){
		$ip = $_SERVER["REMOTE_ADDR"];
	}

	if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $method == 1){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}

	if(isset($_SERVER["HTTP_CLIENT_IP"]) && $method == 2){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}

	// Hacking fix for X-Forwarded-For
	if(!pagelayer_valid_ip($ip)){
		return '';
	}

	return $ip;

}

// Execute a select query and return an array
function pagelayer_selectquery($query, $array = 0){
	global $wpdb;

	$result = $wpdb->get_results($query, 'ARRAY_A');

	if(empty($array)){
		return current($result);
	}else{
		return $result;
	}
}

// Check if an IP is valid
function pagelayer_valid_ip($ip){

	// IPv6
	if(pagelayer_valid_ipv6($ip)){
		return true;
	}

	// IPv4
	if(!ip2long($ip)){
		return false;
	}

	return true;
}

function pagelayer_valid_ipv6($ip){

	$pattern = '/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/';

	if(!preg_match($pattern, $ip)){
		return false;
	}

	return true;

}

// Check if a field is posted via POST else return default value
function pagelayer_optpost($name, $default = ''){

	if(!empty($_POST[$name])){
		return pagelayer_inputsec(pagelayer_htmlizer(trim($_POST[$name])));
	}

	return $default;
}

// Check if a field is posted via GET else return default value
function pagelayer_optget($name, $default = ''){

	if(!empty($_GET[$name])){
		return pagelayer_inputsec(pagelayer_htmlizer(trim($_GET[$name])));
	}

	return $default;
}

// Check if a field is posted via GET or POST else return default value
function pagelayer_optreq($name, $default = ''){

	if(!empty($_REQUEST[$name])){
		return pagelayer_inputsec(pagelayer_htmlizer(trim($_REQUEST[$name])));
	}

	return $default;
}

// For filling in posted values
function pagelayer_POSTval($name, $default = ''){

	return (!empty($_POST) ? (!isset($_POST[$name]) ? '' : esc_html($_POST[$name])) : $default);

}

function pagelayer_POSTchecked($name, $default = false){

	return (!empty($_POST) ? (isset($_POST[$name]) ? 'checked="checked"' : '') : (!empty($default) ? 'checked="checked"' : ''));

}

function pagelayer_POSTselect($name, $value, $default = false){

	if(empty($_POST)){
		if(!empty($default)){
			return 'selected="selected"';
		}
	}else{
		if(isset($_POST[$name])){
			if(trim($_POST[$name]) == $value){
				return 'selected="selected"';
			}
		}
	}

}

function pagelayer_inputsec($string){

	$string = addslashes($string);

	// This is to replace ` which can cause the command to be executed in exec()
	$string = str_replace('`', '\`', $string);

	return $string;

}

function pagelayer_htmlizer($string){

	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');

	preg_match_all('/(&amp;#(\d{1,7}|x[0-9a-fA-F]{1,6});)/', $string, $matches);//r_print($matches);

	foreach($matches[1] as $mk => $mv){
		$tmp_m = pagelayer_entity_check($matches[2][$mk]);
		$string = str_replace($matches[1][$mk], $tmp_m, $string);
	}

	return $string;

}

function pagelayer_entity_check($string){

	//Convert Hexadecimal to Decimal
	$num = ((substr($string, 0, 1) === 'x') ? hexdec(substr($string, 1)) : (int) $string);

	//Squares and Spaces - return nothing 
	$string = (($num > 0x10FFFF || ($num >= 0xD800 && $num <= 0xDFFF) || $num < 0x20) ? '' : '&#'.$num.';');

	return $string;

}

// Check if a checkbox is selected
function pagelayer_is_checked($post){

	if(!empty($_POST[$post])){
		return true;
	}
	return false;
}

// Report an error
function pagelayer_report_error($error = array()){

	if(empty($error)){
		return true;
	}

	$error_string = '<b>Please fix the below error(s) :</b> <br />';

	foreach($error as $ek => $ev){
		$error_string .= '* '.$ev.'<br />';
	}

	echo '<div id="message" class="error"><p>'
					. __pl($error_string)
					. '</p></div>';
}

// Report a notice
function pagelayer_report_notice($notice = array()){

	global $wp_version;

	if(empty($notice)){
		return true;
	}

	// Which class do we have to use ?
	if(version_compare($wp_version, '3.8', '<')){
		$notice_class = 'updated';
	}else{
		$notice_class = 'updated';
	}

	$notice_string = '<b>Please check the below notice(s) :</b> <br />';

	foreach($notice as $ek => $ev){
		$notice_string .= '* '.$ev.'<br />';
	}

	echo '<div id="message" class="'.$notice_class.'"><p>'
					. __pl($notice_string)
					. '</p></div>';
}

// Convert an objext to array
function pagelayer_objectToArray($d){

	if(is_object($d)){
		$d = get_object_vars($d);
	}

	if(is_array($d)){
		return array_map(__FUNCTION__, $d); // recursive
	}elseif(is_object($d)){
		return pagelayer_objectToArray($d);
	}else{
		return $d;
	}
}

// Sanitize variables
function pagelayer_sanitize_variables($variables = array()){

	if(is_array($variables)){
		foreach($variables as $k => $v){
			$variables[$k] = trim($v);
			$variables[$k] = escapeshellcmd($v);
		}
	}else{
		$variables = escapeshellcmd(trim($variables));
	}

	return $variables;
}

// Is multisite ?
function pagelayer_is_multisite() {

	if(function_exists('get_site_option') && function_exists('is_multisite') && is_multisite()){
		return true;
	}

	return false;
}

// Generate a random string
function pagelayer_RandomString($length = 10){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for($i = 0; $i < $length; $i++){
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function pagelayer_print($array){

	echo '<pre>';
	print_r($array);
	echo '</pre>';

}

function pagelayer_cleanpath($path){
	$path = str_replace('\\\\', '/', $path);
	$path = str_replace('\\', '/', $path);
	$path = str_replace('//', '/', $path);
	return rtrim($path, '/');
}

// Returns the Numeric Value of results Per Page
function pagelayer_get_page($get = 'page', $resperpage = 50){

	$resperpage = (!empty($_REQUEST['reslen']) && is_numeric($_REQUEST['reslen']) ? (int) pagelayer_optreq('reslen') : $resperpage);

	if(pagelayer_optget($get)){
		$pg = (int) pagelayer_optget($get);
		$pg = $pg - 1;
		$page = ($pg * $resperpage);
		$page = ($page <= 0 ? 0 : $page);
	}else{
		$page = 0;
	}
	return $page;
}

// Are we editing from the Admin panel ?
function pagelayer_is_editing($force = false){

	global $post, $pagelayer;

	if(!empty($force)){
		return true;
	}

	if(!is_admin()){
		return false;
	}

	$current_file = basename($_SERVER['PHP_SELF']);

	$type = get_post_type();
	//echo $type;return false;
	//$page = pagelayer_optreq('page');

	// Are we in the live editor mode OR is this a post which is supported
	if((pagelayer_supported_type($type) && in_array($current_file, array('post.php', 'post-new.php'))) || pagelayer_is_live()){
		return true;
	}else{
		return false;
	}

}

// Is the given post type editable by us ?
function pagelayer_supported_type($type){

	global $pagelayer;

	$type = trim($type);

	if(in_array($type, $pagelayer->settings['post_types'])){
		return true;
	}
	
	if($type == $pagelayer->builder['name']){
		return true;
	}

	return false;

}

function pagelayer_shortlink($id){
	
	$post = get_post( $id );
	if ( ! empty( $post->ID ) ) {
		$post_id = $post->ID;
	}
	
	$post_type = get_post_type_object( $post->post_type );
 
	if ( 'page' === $post->post_type && get_option( 'page_on_front' ) == $post->ID && 'page' === get_option( 'show_on_front' ) ) {
		$link = home_url( '/' );
	} elseif ( $post_type->public ) {
		$link = home_url( '?p=' . $post_id );
	}
	
	if(function_exists('is_post_status_viewable') && !is_post_status_viewable($post_id)){
		$link = get_permalink( $post->ID );
	}
	
	$link .= substr_count($link, '?') > 0 ? '' : '?';
	return $link;
}

// Pagelayer live link
function pagelayer_livelink($id){
	return pagelayer_shortlink($id).'&pagelayer-live=1';
}

// Are we in live mode ?
function pagelayer_is_live(&$error = array()){

	global $post;

	// Are we seeing the post ?
	if(!isset($post) || !isset($post->ID) || empty($post->ID)){
		$error[] = 'Post ID is missing or blank - '.@$post->ID;
		return false;
	}
	
	$parID = $post->ID;
	
	// Is revision?
	if(wp_is_post_revision($post->ID) ){
		$parID = wp_get_post_parent_id($post->ID);
	}
	
	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit($parID)){
		$error[] = 'You dont have editing rights for this page - '.$parID;
		return false;
	}

	// Is it the live mode ?
	if(pagelayer_optreq('pagelayer-live')){
		$error[] = 'pagelayer-live is missing';
		return true;
	}

	return false;

}

// Are we in live IFRAME mode ?
function pagelayer_is_live_iframe(&$error = array()){

	// Are we seeing the post ?
	if(!pagelayer_is_live($error)){
		return false;
	}

	// Is it the live mode ?
	if(pagelayer_optreq('pagelayer-iframe')){
		return true;
	}
	
	$error[] = 'pagelayer-iframe missing in GET';

	return false;

}

// Are we editing a live template
function pagelayer_is_live_template($post = []){

	// Are we seeing the post ?
	if(!pagelayer_is_live()){
		return false;
	}
	
	if(!$post){
		$post = $GLOBALS['post'];
	}
	
	if($post->post_type == 'pagelayer-template'){
		return true;
	}
	
	return false;
	
}

function pagelayer_has_blocks($post = null) {
	if ( ! has_blocks( $post ) ) {
		return false;
	}

	if ( ! is_string( $post ) ) {
		$wp_post = get_post( $post );
		if ( $wp_post instanceof WP_Post ) {
			$post = $wp_post->post_content;
		}
	}

	return false !== strpos( $post, '<!-- wp:pagelayer/' );
}

// Can the current user edit the post ?
function pagelayer_user_can_edit($post = NULL){

	global $wp_the_query, $current_user, $pagelayer;
	
	$post = get_post($post);

	if(empty($post)){
		return false;
	}

	// No trash editing
	if(get_post_status($post->ID) === 'trash'){
		return false;
	}
	
	// Is pagelayer supposed to edit this ?
	if(!pagelayer_supported_type($post->post_type)){
		return false;
	}
	
	// Get the post type object
	$object = get_post_type_object($post->post_type);
	
	// Is this type editable by the user ?
	if(!current_user_can($object->cap->edit_posts)){
		return false;
	}
	
	// Is this type editable ?
	if(!isset($object->cap->edit_post)){
		return false;
	}
	
	// Can this user edit the post type ?
	if(!current_user_can($object->cap->edit_post, $post->ID)){
		return false;
	}
	
	// Page for blogs not allowed
	if(get_option('page_for_posts') === $post->ID){
		return false;
	}

	return true;

}

// Language sting function
function __pl($key){

	global $pagelayer;

	if(!empty($pagelayer->l[$key])){
		return $pagelayer->l[$key];
	}

	return $key;

}

// Give the list of icon sources
function pagelayer_icon_sources(){
	return array();
}

// Sets the memory limit
function pagelayer_memory_limit($mb){
	
	$bytes = ($mb * 1024 * 1024);
	$mb_str = (string) $mb.'M';
	
	// Some servers might have ini_get disabled
	if(function_exists('ini_get')){
		$memory_limit = @ini_get('memory_limit');
	}
	
	if(empty($memory_limit)){
		return;
	}
	
	$memory_limit_bytes = (strpos($memory_limit, 'M') ? (intval($memory_limit) * 1024 * 1024) : intval($memory_limit));
	
	//$memory_limit_bytes > 0 is for memory limit = -1
	if($memory_limit_bytes <= $bytes && $memory_limit_bytes > 0){
		
		// Some servers might have ini_set disabled
		if(function_exists('ini_set')){
			@ini_set('memory_limit', $mb_str);
		}
	}

}

// Pagelayer the content
function pagelayer_the_content($content, $dump = false){
	global $pagelayer;
	
	$content = pagelayer_sanitize_content( $content );
	$content = do_blocks( $content );
	$content = do_shortcode( $content );
	
	if($dump){
		preg_match_all('/<div ([^>]*)pagelayer-id="([^"]*)"([^>]*)>/', $content, $matches);
	
		foreach($matches[0] as $k => $div){
			$id = $matches[2][$k];
			
			if(empty($pagelayer->data_attr[$id])){
				continue;
			}
			
			$data_attr = '<!-- '.pagelayer_serialize_attributes($pagelayer->data_attr[$id]).' -->';
			$content = str_replace($div, $div.$data_attr, $content);
		}
	}
	
	return $content;	
}

function pagelayer_create_id(){
	return pagelayer_RandomString(3).rand(1000, 9999);
}

// Loads the shortcodes
function pagelayer_load_shortcodes(){
	global $pagelayer, $post;
	
	if(!empty($pagelayer->shortcode_loaded)){
		return;
	}
	
	pagelayer_memory_limit(128);
	
	// We have loaded
	$pagelayer->shortcode_loaded = 1;

	// pQuery
	include_once(PAGELAYER_DIR.'/lib/pquery/IQuery.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_formatter.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_node_html.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_tokenizer.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_parser_html.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_selector_html.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_xml2array.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/pQuery.php');

	include_once(PAGELAYER_DIR.'/main/shortcode_functions.php');
	if(defined('PAGELAYER_PREMIUM')){
		include_once(PAGELAYER_DIR.'/main/freemium_functions.php');
		include_once(PAGELAYER_DIR.'/main/premium_functions.php');
	}
	include_once(PAGELAYER_DIR.'/main/shortcodes.php');
	
	// Apply filter to load custom widgets
	do_action('pagelayer_load_custom_widgets');
	
	// Render Pagelayer element by blocks
	add_action('pre_render_block', 'pagelayer_render_blocks', 10, 2);
	
	// Add global widget data
	if(defined('PAGELAYER_PREMIUM') && !pagelayer_is_gutenberg_editor()){
		
		// Get global widget templates id by type	
		$args = [
			'post_type' => $pagelayer->builder['name'],
			'status' => 'publish',
			'meta_key' => 'pagelayer_template_type',
			'meta_value' => array('global_widget', 'section',  'global_section'),
			'posts_per_page' => -1
		];
		
		$query = new WP_Query($args);
		
		$tmp_list = [];
		$global_widgets = array();
		$global_widgets['global_widget'] = array();
		$global_widgets['section'] = array();
		$global_widgets['global_section'] = array();
		
		foreach($query->posts as $template){
			
			// The type
			$pagelayer_template_type = get_post_meta($template->ID, 'pagelayer_template_type', true);
							
			$global_data = [];
			$global_data['post_id'] = $template->ID;
			$global_data['title'] = $template->post_title;
			$global_data['$'] = pagelayer_the_content($template->post_content, true);			
			$global_widgets[$pagelayer_template_type][$template->ID] = $global_data;

		}
		
		$pagelayer->global_widgets = $global_widgets['global_widget'];
		$pagelayer->saved_sections = $global_widgets['section'];
		$pagelayer->global_sections = $global_widgets['global_section'];
	}
}

// Add the shortcodes to the pagelayer list
function pagelayer_add_shortcode($tag, $params = array()){

	global $pagelayer, $post;
		
	if($tag == 'pl_row'){
		$inner_tag = 'pl_inner_row';
		add_shortcode($inner_tag, 'pagelayer_render_shortcode');
	}
	
	if($tag == 'pl_col'){
		$inner_tag = 'pl_inner_col';
		add_shortcode($inner_tag, 'pagelayer_render_shortcode');
	}
	
	add_shortcode($tag, 'pagelayer_render_shortcode');//$params['func']);
	//unset($params['func']);

	// Is there a group ?
	if(empty($params['group'])){
		$params['group'] = 'misc';
	}

	// Add the advanced styling group
	$params['options'] = [
		'ele_bg_styles' => __pl('ele_bg_styles'),
		'ele_styles' => __pl('ele_styles'),
		'border_styles' => __pl('border_styles'),
		'font_style' => __pl('font_style'),
		'position_styles' => __pl('position_styles'),
		'animation_styles' => __pl('animation_styles'),
		'motion_effects' => __pl('Motion Effects'),
		'responsive_styles' => __pl('responsive_styles'),
		'attributes' => __pl('attributes'),
		'custom_styles' => __pl('custom_styles'),
	];
	
	if(!empty($params['skip_props_cat'])){
		foreach($params['skip_props_cat'] as $k => $v){
			unset($params['options'][$v]);
		}
	}

	// Are the settings there which hold the params ?
	if(empty($params['settings'])){
		$params['settings'] = [
			'params' => $params['name'],
		];
	}

	// Disable the style options
	if(!empty($params['styles'])){
		$params['settings'] = array_merge($params['settings'], $params['styles']);
		unset($params['styles']);
	}
	
	/*// The following is for testing only
	$r = [];
	foreach($pagelayer->styles as $k => $v){
		foreach($v as $kk => $vv){
			$r[$kk] = $kk;
		}
	}
	//print_r($r);die();
	
	foreach($params['settings'] as $k => $v){
		if(empty($params[$k])) continue;
		foreach($params[$k] as $kk => $vv){
			if(!empty($r[$kk])){
				echo 'Duplicate KEY '.$kk.' in Shortcode '.$tag."<br>";
			}
		}
	}
	//die();*/
	
	$params = apply_filters( 'pagelayer_shortcode_params', $params, $tag );
	
	// Insert the shortcode
	$pagelayer->shortcodes[$tag] = $params;
	$pagelayer->groups[$params['group']][] = $tag;
	
	// Export the default values
	foreach($pagelayer->tabs as $tab){
		
		if(empty($pagelayer->shortcodes[$tag][$tab])){
			continue;
		}
		
		foreach($pagelayer->shortcodes[$tag][$tab] as $section => $Lsection){
			
			$props = empty($pagelayer->shortcodes[$tag][$section]) ? @$pagelayer->styles[$section] : @$pagelayer->shortcodes[$tag][$section];
			
			//echo $tab.' - '.$section.' - <br>';
			
			if(empty($props)){
				continue;
			}
			
			// Save customizer params
			if( $tag == 'pl_customizer' ){
				$pagelayer->customizer_params = array_merge($pagelayer->customizer_params, $props);
			}
				
			foreach($props as $prop => $param){
				
				// Set default values to export for JS
				if(isset($param['export-def']) && isset($param['default']) && $param['export-def'] == 1){
					$pagelayer->default_params[$tag][$prop] = $param['default'];
				}
				
			}
		}
	}
}

// Add a freemium shortcode i.e. available for render, but not to drag or edit 
function pagelayer_freemium_shortcode($tag, $params = array()){

	// If we are the free version, we just allow render and some edits
	if(!defined('PAGELAYER_PREMIUM')){
	
		$params['not_visible'] = 1;
		$params['freemium'] = 1;
		
		$cats = empty($params['styles']) ? array() : $params['styles'];
	
		if(!empty($params['settings'])){
			$cats = array_merge($cats, $params['settings']);
		}
		
		$cats['params'] = $params['name'];
		//pagelayer_print($cats);
		
		foreach($cats as $k => $v){
			if(empty($params[$k])) continue;
			
			foreach($params[$k] as $kk => $vv){
			
				if(empty($params[$k][$kk]['np'])){
					$params[$k][$kk]['pro'] = 1;
				}
				
			}
			
		}
		
	}
	
	return pagelayer_add_shortcode($tag, $params);
}

// Returns the permalink values
function pagelayer_permalink($id){
	
	if(is_numeric($id)){
		$id = (int) @$id;
		$perma = get_permalink($id);
		
		if(!empty($perma)){
			$id = $perma;
		}
	}
	
	$id = apply_filters('pagelayer_permalink', $id);
	
	return $id;
}

// Returns the Image values
function pagelayer_image($id = 0){
	
	global $pagelayer;
	
	$ret = [];

	if(!empty($id) && is_array($id)){
		
		foreach($id as $key => $image){
			$attachment = pagelayer_image(@$image);
			if(!empty($attachment)){
				foreach($attachment as $k => $v){
					if($key == 'retina'){
						$ret['retina-'.$k] = $v;
					}else if($key == 'retina_mobile'){
						$ret['retina-mobile-'.$k] = $v;
					}else{
						$ret[$k] = $v;
					}
				}
			}
		}
		
		return $ret;
	}
		
	// External image ?
	if(pagelayer_is_external_img($id)){

		$ret['url'] = $id;

	// Attachment
	}elseif(!empty($id)){

		$id = (int) @$id;

		$image = get_post($id);

		// Is there an attachment which is an image ?
		if(!empty($image) && $image->post_type == 'attachment' && wp_attachment_is_image($id)){
		
			// Need to export necessary media
			if(!empty($pagelayer->export_mode)){
				$pagelayer->media_to_export[] = $id;
			}

			$sizes = get_intermediate_image_sizes();
			array_unshift($sizes, 'full');

			foreach($sizes as $size){
				$src = wp_get_attachment_image_src($id, $size);
				$ret[$size.'-url'] = $src[0];
			}

			// Title and Alt
			$title = esc_attr($image->post_title);
			$alt = get_post_meta($id, '_wp_attachment_image_alt', true);
			$alt = empty($alt) ? $image->post_excerpt : $alt;
			$alt = empty($alt) ? $image->post_title : $alt;
			$alt = empty($alt) ? '' : esc_attr(trim(strip_tags($alt)));
			$link = get_attachment_link($id);
			$caption = wp_get_attachment_caption($id);
			$caption = !empty($caption) ? esc_attr($caption) : '';

		}

	}

	// First preference to full url
	if(!empty($ret['full-url'])){
		$ret['url'] = $ret['full-url'];
	}

	// No image
	if(empty($ret['url'])){
		$ret['url'] = PAGELAYER_URL.'/images/default-image.png';
	}

	$ret['alt'] = @$alt;
	$ret['title'] = @$title;
	$ret['link'] = @$link;
	$ret['caption'] = @$caption;
	
	$ret = apply_filters('pagelayer_image', $ret);
	
	if(pagelayer_is_default_img($ret['url'])){
		$ret['no-image-set'] = 1;
	}

	return $ret;

}

// Checks if the given parameter is an external link or a wp attachment id
function pagelayer_is_external_img($img = ''){

	if(empty($img)){
		return false;
	}

	if(preg_match('#http://#is', $img) || preg_match('#https://#is', $img) || preg_match('#^{{#is', $img)){
		return true;
	}

	return false;

}

// Checks if the given parameter is the default image
function pagelayer_is_default_img($img){
	
	if($img == PAGELAYER_URL.'/images/default-image.png'){
		return true;
	}
	
	return false;

}

// Returns the attachment url
function pagelayer_attachment($id){

	$ret = [];

	// External url ?
	if(pagelayer_is_external_img($id)){

		$ret['url'] = $id;

	// Attachment
	}elseif(!empty($id)){
		
		// Need to export necessary media
		if(!empty($pagelayer->export_mode)){
			$pagelayer->media_to_export[] = $id;
		}

		$ret['url'] = wp_get_attachment_url($id);

	}
	
	$ret = apply_filters('pagelayer_attachment', $ret);

	return $ret;

}

// Convert the regular URL of a Video to a Embed URL
// Todo : Check
function pagelayer_video_url($source, $no_url = false){

	global $pagelayer;

	if (!empty($source)) {
		
		$source = esc_url( $source );
		$source = str_replace('&amp;', '&', $source);
		$url = parse_url($source);
		$videoSite ='';
		$videoId ='';
		$vid_atts = [];

		$youtubeRegExp = '/youtube\.com|youtu\.be/is';
		$vimeoRegExp = '/vimeo\.com/is';

		if (preg_match($youtubeRegExp, $source)) {
			$videoSite = 'youtube';
		} else if (preg_match($vimeoRegExp, $source)) {
			$videoSite = 'vimeo';
		}

		switch ($videoSite) {
			case 'youtube':

				$pagelayer->append_yt_api = true;
				
				if (preg_match('/youtube\.com/is', $source)) {

					if (preg_match('/watch/is', $source)) {
						parse_str($url['query'], $parameters);

						if (isset($parameters['v']) && !empty($parameters['v'])) {
						   $videoId = $parameters['v'];
						}

					} else if (preg_match('/embed/is', $url['path'])) {
						$path = explode('/', $url['path']);
						if (isset($path[2]) && !empty($path[2])) {
							$videoId = $path[2];
						}
					}

				} else if (preg_match('/youtu\.be/is', $url['host'])) {
					$path = explode('/', $url['path']);

					if (isset($path[1]) && !empty($path[1])) {
						$videoId = $path[1];
					}

				}
				
				$vid_atts['type'] = 'youtube';
				$vid_atts['src'] = '//www.youtube.com/embed/'.$videoId;
				$vid_atts['id'] = $videoId;

				break;
			case 'vimeo':

				if (preg_match('/player\.vimeo\.com/is', $url['host']) && preg_match('/video/is', $url['path'])) {
					$path = explode('video/', $source);
				} else if (preg_match('/vimeo\.com/is', $url['host'])) {
					$path = explode('.com/', $source);
				}
				
				if(isset($path[1]) && !empty($path[1])) {
					$videoId = $path[1];
				}
				
				$vid_atts['type'] = 'vimeo';
				$vid_atts['src'] = '//player.vimeo.com/video/'.$videoId;
				$vid_atts['id'] = $videoId;

				break;
			default:
				
				$vid_atts['type'] = 'local';
				$vid_atts['src'] = $source;
				$vid_atts['id'] = $videoId;

		}
		
		if(!$no_url){
			return $vid_atts['src'];
		}
		
		return $vid_atts;

	}
}


// As per the JS specification
function pagelayer_escapeHTML($str){
	
	$replace = [
		']' => '&#93;',
		'[' => '&#91;',
		//'=' => '&#61;',
		'<' => '&lt;',
		'>' => '&gt;',
		'"' => '&quot;',
		//'&' => '&amp;',
		'\'' => '&#39;',
		'\\' => '&#92;'
	];
	
	$str = str_replace(array_keys($replace), array_values($replace), $str);
	
	return $str;
}

// As per the JS specification
function pagelayer_unescapeHTML($str){
	$replace = [
		'#93' => ']',
		'#91' => '[',
		//'#61' => '=',
		'lt' => '<',
		'gt' => '>',
		'quot' => '"',
		//'amp' => '&',
		'#39' => '\'',
		'#92' => '\\'
	];
	
	foreach($replace as $k => $v){
		$str = str_replace('&'.$k.';', $v, $str);
	}
	return $str;
}

// Return true if user can add js content
function pagelayer_user_can_add_js_content(){
	
	// Unfiltered_html cap needs to be checked for multisite
	if(current_user_can('unfiltered_html')){
		return true;
	}
	
	$pagelayer_js_permission = get_option('pagelayer_js_permission');
	$current_user = wp_get_current_user();
	
	// If not allowed any role by admin
	if(empty($pagelayer_js_permission) || empty($current_user->roles)){
		return false;
	}
	
	foreach($current_user->roles as $role){
		if(in_array($role, $pagelayer_js_permission)){
			return true;
		}
	}
	
	return false;
}

// Check for XSS codes in our shortcodes submitted
function pagelayer_xss_content($data){
	$data = pagelayer_unescapeHTML($data);
	$data = preg_split('/\s/', $data);
	$data = implode('', $data);
	//echo $data;
	
	if(preg_match('/["\']javascript\:/is', $data)){
		return 'javascript';
	}
	
	if(preg_match('/["\']vbscript\:/is', $data)){
		return 'vbscript';
	}
	
	if(preg_match('/\-moz\-binding\:/is', $data)){
		return '-moz-binding';
	}
	
	if(preg_match('/expression\(/is', $data)){
		return 'expression';
	}
	
	if(preg_match('/\<(iframe|frame|script|style|link|applet|embed|xml|svg|object|layer|ilayer|meta)/is', $data, $matches)){
		return $matches[1];
	}
	
	// These events not start with on
	$not_allowed = array('click', 'dblclick', 'mousedown', 'mousemove', 'mouseout', 'mouseover', 'mouseup', 'load', 'unload', 'change', 'submit', 'reset', 'select', 'blur', 'focus', 'keydown', 'keypress', 'keyup', 'afterprint', 'beforeprint', 'beforeunload', 'error', 'hashchange', 'message', 'offline', 'online', 'pagehide', 'pageshow', 'popstate', 'resize', 'storage', 'contextmenu', 'input', 'invalid', 'search', 'mousewheel', 'wheel', 'drag', 'dragend', 'dragenter', 'dragleave', 'dragover', 'dragstart', 'drop', 'scroll', 'copy', 'cut', 'paste', 'abort', 'canplay', 'canplaythrough', 'cuechange', 'durationchange', 'emptied', 'ended', 'loadeddata', 'loadedmetadata', 'loadstart', 'pause', 'play', 'playing', 'progress', 'ratechange', 'seeked', 'seeking', 'stalled', 'suspend', 'timeupdate', 'volumechange', 'waiting', 'toggle', 'animationstart', 'animationcancel', 'animationend', 'animationiteration', 'auxclick', 'beforeinput', 'beforematch', 'beforexrselect', 'compositionend', 'compositionstart', 'compositionupdate', 'contentvisibilityautostatechange', 'focusout', 'focusin', 'fullscreenchange', 'fullscreenerror', 'gotpointercapture', 'lostpointercapture', 'mouseenter', 'mouseleave', 'pointercancel', 'pointerdown', 'pointerenter', 'pointerleave', 'pointermove', 'pointerout', 'pointerover', 'pointerrawupdate', 'pointerup', 'scrollend', 'securitypolicyviolation', 'touchcancel', 'touchend', 'touchmove', 'touchstart', 'transitioncancel', 'transitionend', 'transitionrun', 'transitionstart', 'MozMousePixelScroll', 'DOMActivate', 'afterscriptexecute', 'beforescriptexecute', 'DOMMouseScroll', 'willreveal', 'gesturechange', 'gestureend', 'gesturestart', 'mouseforcechanged', 'mouseforcedown', 'mouseforceup', 'mouseforceup');
	
	$not_allowed = implode('|', $not_allowed);
		
	if(preg_match('/(on|onwebkit)+('.($not_allowed).')=/is', $data, $matches)){
		return $matches[1]+$matches[2];
	}
	
	return;

}

// Check for XSS codes in our blocks array
function pagelayer_sanitize_blocks_save_pre($block){
	
	foreach($block as $k => $v){
				
		// Recurse on  arrays
		if(is_array($v)){
			$block[$k] = pagelayer_sanitize_blocks_save_pre($v);
			
		// We dont support objects !
		}elseif(is_object($v)){
			$block[$k] = null;
		// Strings
		}else{
			
			if(is_string($v)){
				
				$v = wp_filter_post_kses($v);
				
				while(true){
					$str = '"'.($v);
					$found = pagelayer_xss_content($str);
					//echo (string)$v.'--'.$found."\n";
				
					if(strlen($found) > 0){
						$v = str_replace($found, '', $v);
					}else{
						break;
					}

				}
				
			}
			
			$block[$k] = $v;
		}
		
	}

	return $block;
}

function pagelayer_getting_started_notice(){
	
	// Is Sitepad setup done?
	$setup_done = get_option('sp_setup_done');
	
	if(defined('SITEPAD') && empty($setup_done)){
		return;
	}
	
	// If SitePad used custom BRAND SM
	if(defined('BRAND_SM_CUSTOM')){
		return;
	}
		
	echo '
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery("#pagelayer-getting-started-notice").click(function(e){
		
		if(jQuery(e.target).hasClass("notice-dismiss")){
			var data;
			jQuery("#pagelayer-getting-started-notice").hide();
			// Save this preference
			jQuery.post("'.admin_url('?pagelayer-getting-started=0&pagelayer_nonce='.wp_create_nonce("pagelayer_getting_started_nonce") ).'", data, function(response) {
			//alert(response);
			});
			return false;
		}
		
	});
});
</script>

	<div id="pagelayer-getting-started-notice" class="notice notice-success is-dismissible">
		<p style="font-size: 14px; font-weight: 600">';
		if(defined('SITEPAD')){
		
			echo '<a href="'.BRAND_SM_URL.'"><img src="'.BRAND_SM_LOGO .'" style="vertical-align: middle; margin:0px 10px" width="24" /></a>'.__('Thanks for choosing '.BRAND_SM .'. We recommend that you see the short and sweet <a href="'.admin_url('admin.php?page=pagelayer_getting_started').'">Getting Started Video</a> to know the basics of '.BRAND_SM.'.');
			
		}else{
		
			echo '<a href="'.PAGELAYER_WWW_URL.'"><img src="'.PAGELAYER_URL.'/images/pagelayer-logo-256.png" style="vertical-align: middle; margin:0px 10px" width="24" /></a>'.__('Thanks for choosing Pagelayer. We recommend that you see the short and sweet <a href="'.admin_url('admin.php?page=pagelayer_getting_started').'">Getting Started Video</a> to know the basics of Pagelayer.', 'pagelayer');
		
		}
		
	echo '</p>
	</div>';
	
}

// Show Changelog promo
function pagelayer_show_changelog_notice(){
	
	// Is Sitepad setup done?	
	if(defined('SITEPAD')){
		return;
	}
	
	echo '
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery("#pagelayer-changelog-notice").click(function(e){
		
		if(jQuery(e.target).hasClass("notice-dismiss")){
			var data;
			jQuery("#pagelayer-changelog-notice").hide();
			// Save this preference
			jQuery.post("'.admin_url('?pagelayer-changelog-notice=0&pagelayer_nonce='.wp_create_nonce("pagelayer_changelog_nonce") ).'", data, function(response) {
			//alert(response);
			});
			return false;
		}
		
	});
});
</script>

	<div id="pagelayer-changelog-notice" class="notice notice-info is-dismissible">
		<p style="font-size: 14px; font-weight: 600">
			<a href="'.PAGELAYER_WWW_URL.'"><img src="'.PAGELAYER_URL.'/images/pagelayer-logo-256.png" style="vertical-align: middle; margin:0px 10px" width="24" /></a>'.__('Empower Your Designs: Pagelayer 1.8.1 - Unleashing Seamless Integration with Gutenberg for Enhanced Website Creation! <a href="'.PAGELAYER_WWW_URL.'/blog/reinventing-pagelayer-for-gutenberg-a-seamless-fusion-of-powerful-page-building-and-wordpress-editor/">Read More</a>.', 'pagelayer') .'
		</p>
	</div>';
	
}

// Show promo notice on dashboard
function pagelayer_show_promo(){
	
	global $pagelayer_promo_opts;
	$opts = $pagelayer_promo_opts;
	
	echo '<style>
.pagelayer_promo_button {
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
.pagelayer_promo_button:focus,
.pagelayer_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.pagelayer_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.pagelayer_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.pagelayer_promo_button1:hover {
border:3px solid #4CAF50;
}
.pagelayer_promo_button2 {
color: white;
background-color: #0085ba;
}
.pagelayer_promo_button3 {
color: white;
background-color: #365899;
}
.pagelayer_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.pagelayer_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.pagelayer_promo-close:hover{
color: red;
}
</style>
<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#pagelayer_promo .pagelayer_promo-close").click(function(){
			var data;
			jQuery("#pagelayer_promo").hide();
			// Save this preference
			jQuery.post("'.admin_url('?pagelayer_promo=0').'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

	<div class="notice notice-success" id="pagelayer_promo" style="min-height:90px">
	<a class="pagelayer_promo-close" href="javascript:" aria-label="Dismiss this Notice">
		<span class="dashicons dashicons-dismiss"></span> Dismiss
	</a>';
	
	if(!empty($opts['image'])){
		echo '<a href="'.$opts['website'].'"><img src="'.$opts['image'].'" style="float:left; margin:10px 20px 10px 10px" width="67" /></a>';
	}
	
	echo '
	<p style="font-size:13px">We are glad you like <a href="'.$opts['website'].'"><b>Pagelayer</b></a> and have been using it since the past few days. It is time to take the next step !</p>
	<p>
		'.(empty($opts['pro_url']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_buy" target="_blank" href="'.$opts['pro_url'].'">Buy Pagelayer Pro</a>').'
		'.(empty($opts['rating']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button2" target="_blank" href="'.$opts['rating'].'">Rate it 5★\'s</a>').'
		'.(empty($opts['facebook']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button3" target="_blank" href="'.$opts['facebook'].'"><span class="dashicons dashicons-thumbs-up"></span> Facebook</a>').'
		'.(empty($opts['twitter']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button4" target="_blank" href="'.$opts['twitter'].'"><span class="dashicons dashicons-twitter"></span> Tweet</a>').'
		'.(empty($opts['website']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button4" target="_blank" href="'.$opts['website'].'">Visit our website</a>').'
	</p>
	<p style="font-size:13px"><a href="'.$opts['pro_url'].'"><b>Pagelayer Pro</b></a> has many more features like 60+ widgets, 400+ sections, Theme Builder, WooCommerce Builder, Theme Creator and Exporter, Form Builder, Popup Builder, etc.';
	
	if(date('Ymd') <= 20200331){
		echo '<br><span style="font-size: 14px"><b>Promotional Offer</b></span> : If you buy <a href="'.$opts['pro_url'].'"><b>Pagelayer Pro</b></a> before <b>31st March, 2020</b> then you will get an additional year free and your license will expire on <b>31st March, 2022</b>.';
	}
	
echo '</p></div>';

}

// Are we to show a promo ?
function pagelayer_maybe_promo($opts){
	
	global $pagelayer_promo_opts;
	
	// There must be an interval
	if(!current_user_can('activate_plugins')){
		return false;
	}
	
	// There must be an interval
	if(empty($opts['interval'])){
		return false;
	}
	
	// Are we to show a promo	
	$opt_name = 'pagelayer_promo_time';
	$promo_time = get_option($opt_name);
	
	// First time access
	if(empty($promo_time)){
		update_option($opt_name, time() + (!empty($opts['after']) ? $opts['after'] * 86400 : 0));
		$promo_time = get_option($opt_name);
	}
	
	// Is there interval elapsed
	if(time() > $promo_time){
		$pagelayer_promo_opts = $opts;
		add_action('admin_notices', 'pagelayer_show_promo');
	}
	
	// Are we to disable the promo
	if(isset($_GET['pagelayer_promo']) && (int)$_GET['pagelayer_promo'] == 0){
		update_option($opt_name, time() + ($opts['interval'] * 86400));
		die('DONE');
	}
	
}

// Show the Pro notice
function pagelayer_show_pro_notice(){
	
	if(defined('PAGELAYER_PREMIUM')){
		return;
	}
	
	echo '<div class="pagelayer-notice pagelayer-notice-info">'.__('This feature is a part of <a href="'.PAGELAYER_PRO_URL.'" target="_blank">Pagelayer Pro</a>. You will need to purchase <a href="'.PAGELAYER_PRO_URL.'" target="_blank">Pagelayer Pro</a> to use this feature.').'</div>';
	
}

// Show the Pro Div
function pagelayer_show_pro_div($head = '', $message = '', $admin_css = 1){
	
	if(defined('PAGELAYER_PREMIUM')){
		return;
	}

	if(basename(get_template_directory()) == 'popularfx'){
		$pro_url = 'https://popularfx.com/pricing?from=pagelayer-plugin';
		$pro_txt = 'PopularFX Pro';
	}else{
		$pro_url = PAGELAYER_PRO_URL;
		$pro_txt = 'Pagelayer Pro';
	}
	
	if(!empty($admin_css)){
		wp_enqueue_style( 'pagelayer-admin', PAGELAYER_CSS.'/pagelayer-admin.css', array(), PAGELAYER_VERSION);
	}
	
	echo '<div class="pagelayer-pro-div">';
	
	if(!empty($head)){
		echo '<h1 class="pagelayer-pro-head">'.$head.'</h1>';
	}
	
	echo '<div class="pagelayer-pro-message">';
	
	if(empty($message)){
		
		echo __('This feature is a part of <a href="'.$pro_url.'" target="_blank">'.$pro_txt.'</a>. You will need to purchase <a href="'.$pro_url.'" target="_blank">'.$pro_txt.'</a> to use this feature.');
	
	}else{
		
		echo $message;
		echo ' '.__('This feature is a part of <a href="'.$pro_url.'" target="_blank">'.$pro_txt.'</a>.');
		
	}
	
	echo '</div>
	<center><a class="button button-pagelayer" href="'.$pro_url.'" target="_blank">Get '.$pro_txt.'</a></center>
	</div>';
	
}

// Bread Crumbs with links
function pagelayer_get_breadcrumb(){
	global $post;
	
	// Home page
	$ret = '<a href="'.home_url().'" rel="nofollow">{{home}}</a>';
	
	// Is Front page
	if(is_front_page()){
		return $ret;
	
	// Is search query
	}elseif(is_search()){
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b><b class="pagelayer-breadcrumb-prefix" if="{{search_prefix}}">{{search_prefix}}</b>';
		$ret .= '<em>';
		$ret .= '<span>'.get_search_query().'</span>';
		$ret .= '</em>';
		
	//Is category or single post
	}elseif(is_category() || is_single()){
		
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b>';
		$categories = get_the_category();
		$separator = ' ';
		$output = '';
		if(!empty($categories)){
			$ret .= get_category_parents($categories[0], true, ' <b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b> ');
		}
		if(is_single()) {
			/* if (empty($categories)){
				$ret .= ' <b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b> ';
			} */
			$ret .= '<span>'.get_the_title().'</span>';
		}
	
	// Is page
	}elseif(is_page() ){
		if(!empty($post->post_parent)){
			$ancestors = array_reverse(get_post_ancestors( $post->ID ));
			$page_on_front = get_option('page_on_front');
			
			foreach( $ancestors as $ancestor ){
				
				if($page_on_front == $ancestor){
					continue;
				}
				
				$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b><a href="'.get_permalink($ancestor).'">'.get_the_title($ancestor).'</a>';
			}

			$ret .=' <b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b><span>'.get_the_title().'</span>';

		}else{
			$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b>';
			$ret .= '<span>'.get_the_title().'</span>';
		}
	}else{
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b>';
		$ret .=  '<span>'.wp_title('', false ).'</span>';
	}
	
	// wooCommerce Integration left
	
	return $ret;
}

// Portfolio Posts
function pagelayer_widget_posts($args){
	
	$r = new \WP_Query($args);
	
	$ret = '';
	if ( $r->have_posts() ){
		
		if($args['filter_by'] != 'none' && $args['post_type'] == 'post'){
			$ret .= '<div class="pagelayer-postfolio-filter">';
		
			$data_filter = ( $args['filter_by'] == 'category' ? get_categories() : get_tags() );
			
			if(!empty($data_filter)){
				
				$ret .= '<div class="pagelayer-postfolio-btn" data-filter="all">'.__pl('All').'</div>';
			
				foreach($data_filter as $filter) {
				   $ret .= '<div class="pagelayer-postfolio-btn" data-filter="'.$filter->name.'">' . $filter->name . '</div>';
				}
			
			}

			$ret .= '</div>';
		}
		
		$ret .= '<div class="pagelayer-postfolio-container">';
		
		while ( $r->have_posts() ) : $r->the_post();
			$post_meta = ( $args['filter_by'] == 'category' ? get_the_category() : get_the_tags() );
			$meta_attr = '';
			if($post_meta){
				
				$meta_array = array();
				foreach( $post_meta as $meta ){
					$meta_array[] = $meta->name;
				}
				$meta_attr .= ' data-category="'.implode(' ', $meta_array).'"';
			}
			
			$ret .= '<div class="pagelayer-postfolio-thumb"'.(has_post_thumbnail() ? ' style="background:url('.get_the_post_thumbnail_url().')"' : ''). $meta_attr .'>';
			$ret .= '<a href="'.esc_url( get_permalink() ).'" rel="bookmark">';
			$ret .= '<div class="pagelayer-postfolio-content">';
			$ret .= '<h4 class="pagelayer-entry-title">'.get_the_title().'</h4>';
			$ret .= '</div></a></div>';
		
		endwhile;
		
		$ret .= '</div>';
		
	}
	
	return $ret;
}

// List all available sizes of images registered on WordPress
function pagelayer_image_sizes(){
	
	$sizes = array();
	$sizes = get_intermediate_image_sizes();
	$ret = array();
	foreach($sizes as $size){
		$ret[$size] = __pl($size);
	}
	
	return $ret;
}

function pagelayer_remove_excerpt_more($more){
	return '';
}

function pagelayer_posts($params, $args = []){
	global $post, $wp_query;
	
	if(isset($params['exc_length'])){
		$exc_length = (int) $params['exc_length'];
		add_filter( 'excerpt_length', function($length) use($exc_length){
			return $exc_length;
		}, 999 );
	}
		
	add_filter('excerpt_more', 'pagelayer_remove_excerpt_more', 999);
	
	// If args is empty
	if(empty($args)){
		$args = array(
			'post_type' => $params['post_type'],
			'posts_per_page' => $params['posts_per_page'],
			'order' => $params['order']
		);

		if(!empty($params['paged'])){
			$args['paged'] = $params['paged'];
		}
		
		if(!empty($params['term'])){
			
			$term = explode(':', $params['term']);
			//pagelayer_print($term);
			if($term[0] == 'category'){
				$args['category_name'] = $term[1];
			}
			if($term[0] == 'post_tag'){
				$args['tag'] = $term[1];
			}
		}
		
		if(!empty($params['exc_term'])){
			
			$term = explode(':', $params['exc_term']);
			//pagelayer_print($term);
			if($term[0] == 'category'){
				$args['category__not_in'] = $term[2];
			}
			if($term[0] == 'post_tag'){
				$args['tag__not_in'] = $term[2];
			}
		}
		
		if(!empty($params['author_name'])){
			$author_name = explode(':', $params['author_name']);
			$args['author_name'] = $author_name[0];
		}
		
		if(!empty($params['exc_author'])){
			$exc_author = explode(':', $params['exc_author']);
			$args['author'] = '-'.$exc_author[1];
		}
		
		if(!empty($params['offset'])){
			$args['offset'] = $params['offset'];
		}
		
		if(!empty($params['ignore_sticky'])){
			$args['ignore_sticky_posts'] = $params['ignore_sticky'];
		}
		
		if(!empty($params['orderby'])){
			$args['orderby'] = $params['orderby'];
		}
		
		if(!empty($params['by_period'])){
			$date_arg = array();
			switch($params['by_period']){
				case 'last_day':
					$date_arg['day'] = date('j')-1;
					break;
				case 'last_week':
					$date_arg['week'] = date('W')-1;
					break;
				case 'last_month':
					$date_arg['month'] = date('n')-1;
					break;
				case 'last_year':
					$date_arg['year'] = date('Y')-1;
					break;
				case 'custom':
					$date_arg['before'] = $params['before_date'];
					$date_arg['after'] = $params['after_date'];
					break;
			}
			$args['date_query'] = array(
				$date_arg
			);
		}
	}
	//pagelayer_print($args);
	
	// Only see published posts
	$args['post_status'] = 'publish';
	
	$postsquery = new WP_Query($args);
	$data = '';
	
	if(!wp_doing_ajax() && !empty($params['infinite_types'])){
		$data.='<div class="pagelayer-post-max" data-max="'.$postsquery->max_num_pages.'"></div>';
	}
	
	if(!$postsquery->have_posts()){
		return '<h3>No posts found!</h3>';
	}
	
	// To reset the post when the $wp_query->post is empty
	$orig_post = $post;
	
	while($postsquery->have_posts()) : $postsquery->the_post();
		$data .= '<div class="pagelayer-wposts-col">
			<div class="pagelayer-wposts-post">
				<div class="pagelayer-wposts-featured">';
		$data .= '<a href="'. get_the_permalink() .'">';
		
		if(!empty($params['show_thumb'])){
			$data .= '<div class="pagelayer-wposts-thumb" style="background:url('.(has_post_thumbnail($postsquery->ID) ? get_the_post_thumbnail_url($postsquery->ID, $params['thumb_size']) : ( empty($params['thumb_img_type']) || empty($params['def_thumb_img']) ? PAGELAYER_URL.'/images/no_screenshot.png' : $params['def_thumb_img']) ).')"></div>';
		}
		/* if($params['show_thumb'] && has_post_thumbnail( $postsquery->ID )){
			$data .= get_the_post_thumbnail_url($postsquery->ID,$params['thumb_size']);
		} */
		$data .= '</a></div>
			<div class="pagelayer-wposts-content">';
		
		if(!empty($params['show_title'])){
			$data .= '<a href="'.esc_url( get_permalink() ).'" rel="bookmark"><div class="pagelayer-wposts-title">'. get_the_title().'</div></a>';
		}
		
		$data .= '<div class="pagelayer-wposts-meta">';
		$sep = '';
		if(!empty($params['meta_sep'])){
			$sep = ' <b class="pagelayer-wposts-sep">'.$params['meta_sep'].'</b> ';
		}
		if(!empty($params['author'])){
			$data .= '<span class="pagelayer-wposts-author">By <a class="pagelayer-wposts-author-url" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'">'.esc_html(get_the_author()).'</a></span>'.$sep;
		}
		if(!empty($params['date'])){
			$data .= '<span class="pagelayer-wposts-date"><time class="pagelayer-wposts-entry-date published updated" datetime="'.get_the_date('c').'"><span class="date-d">'.get_the_date('j').'</span> <span class="date-my">'.get_the_date('M, y').'</span></time></span>'.$sep;
		}
		
		if(!empty($params['category'])){
			$category = get_the_category();
			$singlecategory = '';
			foreach( $category as $cat ){
				$singlecategory .= '<a href="' . get_tag_link( $cat->term_id ) . '">'. $cat->name .'</a>';
			}
			
			if(!empty($singlecategory)){
				$data .= '<span class="pagelayer-wposts-category">' . $singlecategory . '</span>'.$sep;
			}
			
		}
		
		if(!empty($params['tags'])){
			$tags = get_the_tags();
			$singletag = '';
			if(!empty($tags)){
				foreach( $tags as $tag ){
					$singletag .= '<a href="' . get_tag_link( $tag->term_id ) . '">'. $tag->name .'</a>';
				}
				if(!empty($singletag)){
					$data .= '<span class="pagelayer-wposts-tags">'.$singletag.'</span>'.$sep;
				}
			}
			
		}
		
		if(!empty($params['comments']) && comments_open($postsquery->ID)){
			$data .= '<span class="pagelayer-wposts-comments"><a href="' . esc_url( get_permalink() ) . '">' . esc_html(get_comments_number()).' '.__pl('comments').'</a></span>'.$sep;
		}
		
		$data .= '</div>';
		
		if(!empty($params['show_content'])){
			$data .= '<div class="pagelayer-wposts-excerpt">';
			if($params['show_content'] == 'excerpt'){	
				$data .= pagelayer_the_content(get_the_excerpt());
			}elseif($params['show_content'] == 'full'){
				$data .= pagelayer_the_content(get_the_content());	
			}
			$data .= '</div>';
		}
		
		if(!empty($params['show_more'])){
			$data .= '<div class="pagelayer-wposts-mdiv"><a class="pagelayer-wposts-more pagelayer-btn-holder pagelayer-ele-link '.$params['btn_type'].' '.$params['size'].' '.$params['icon_position'].'" href="'. get_the_permalink().'">';
			
			if($params['icon']){
				$data .= '<i class="'.$params['icon'].' pagelayer-btn-icon"></i>';
			}
			
			if($params['more']){
				$data .= '<span class="pagelayer-btn-text">'.$params['more'].'</span>';
			}
			
			if($params['icon']){
				$data .= '<i class="'.$params['icon'].' pagelayer-btn-icon"></i>';
			}
			
			$data .= '</a></div>';
			
			//$data .= '<a class="pagelayer-wposts-more" href="'. get_the_permalink().'">'.$params['more'].'</a>';
		}
		
		//$data .= '<div class="pagelayer-wposts-date"><p>'.get_the_date().'</p></div>';
		$data .= '</div></div></div>';
	endwhile;
	
	// In the Gutenberg while adding new page the $wp_query->post was empty
	if ( !isset( $wp_query ) || empty($wp_query->post) ) {
		$GLOBALS['post'] = $orig_post;
	}else{
		wp_reset_postdata();
	}
	
	return $data;	
}

// Get Post Revision
function pagelayer_get_post_revision_by_id($postID){
	
	// Insert the post revision into the database
	$post_revisions = array();
	$reviews =  wp_get_post_revisions($postID);		
	
	foreach($reviews as $values){
		
		$date_format = date_i18n('j-M @ H:i', strtotime( $values->post_modified ) );
		$user_meta = get_userdata($values->post_author);
		
		if ( false !== strpos( $values->post_name, 'autosave' ) ) {
			$type = 'autosave';
		} else {
			$type = 'revision';
		}
		
		$post_tmp_data = array(
			'ID' => $values->ID,
			'post_author_name' => $user_meta->data->display_name,
			'post_author_url' => get_avatar_url($values->post_author),
			'post_date' => $date_format,
			'post_date_ago' => human_time_diff(strtotime($values->post_modified), current_time( 'timestamp' )) . ' ago ',
			'post_type' => $type,
		);
		
		$post_revisions[] = $post_tmp_data;
	}
	
	return $post_revisions;
}

// Gets author data
function pagelayer_author_data($postID){
	
	$authorID = get_post_field('post_author', $postID);
	$data['display_name'] = get_the_author_meta( 'display_name' , $authorID ); 
	$data['description'] = get_the_author_meta( 'description' , $authorID ); 
	$data['user_url'] = get_author_posts_url( $authorID , '' ); 
	$data['avatar'] = get_avatar_url( $authorID );

	return $data;
}

// Posts Slider
function pagelayer_posts_slider($params){

	$args = array();

	if(isset($params['post']['post_type'])){
		$args['post_type'] = $params['post']['post_type'];
	}

	if(isset($params['post']['category'])){
		$args['category_name'] = $params['post']['category'];
	}

	if(isset($params['post']['tags'])){
		$args['tag'] = $params['post']['tags'];
	}

	if(isset($params['post']['order_by'])){
		$args['orderby'] = $params['post']['order_by'];
	}

	if(isset($params['post']['sort_order'])){
		$args['order'] = $params['post']['sort_order'];
	}

	if(isset($params['post']['post_count'])){
		$args['posts_per_page'] = $params['post']['post_count'];
	}
	
	$data = '';
	$postsquery = new WP_Query($args);
	if ( $postsquery->have_posts() ){
		while ( $postsquery->have_posts() ) : $postsquery->the_post();
			$data .= '<li class="pagelayer-posts-slider-col">
				<div class="pagelayer-posts-slider-post">
					<div class="pagelayer-posts-slider-featured-img">';
			$data .= '<a href="'. get_the_permalink() .'">';
			if(has_post_thumbnail( $postsquery->ID ) ){
				if($params['post']['image_size'] == "full" || $params['post']['image_size'] == "custom" ){
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postsquery->ID ), 'single-post-thumbnail' );
					$data .= '<img class="pagelayer-img" src="'.$image[0].'" class="pagelayer-posts-slider-img">';
				}else if($params['post']['image_size'] == "thumbnail"){ 
					$data .= get_the_post_thumbnail();
				}
			}
			$data .= '</a></div>
				<div class="pagelayer-posts-slider-content">';
			$data .= '<p class="pagelayer-posts-slider-title">'. get_the_title().'</p>';
			$data .= '<div class="pagelayer-posts-slider-excerpt">';
			if($params['post']['show_excerpt'] == "true"){
				if(has_excerpt()){	
					$excerpt = get_the_excerpt();
					$data .= pagelayer_the_content($excerpt);
				}
			}
			$data .= '</div>';
			$data .= '<a class="pagelayer-posts-slider-link" href="'. get_the_permalink().'">Read More</a>';
			$data .= '<div class="pagelayer-post-slider-date"><p>'.get_the_date().'</p></div>';
			$data .= '</div></div></li>';
		endwhile;
	}
	return $data;
}

// Gets the site logo URLs 
function pagelayer_site_logo(){
	
	if(get_theme_mod('custom_logo')){
		$logo_id = get_theme_mod('custom_logo');
		return pagelayer_image($logo_id);
	}
	
	return NULL;
}

// Create select options
function pagelayer_create_sel_options( $opt_array , $selected = ''){
	$options = '';
	foreach($opt_array as $x => $val){

		// Single item
		if(is_string($opt_array[$x])){
			$options .= pagelayer_sel_option($x, $val, $selected);
		
		// Groups
		}else{
			
			if(array_key_exists('hide_drop', $opt_array[$x]) && !empty($opt_array[$x]['hide_drop'])){
				continue;
			}
			
			// If Label is there, then its a normal option
			if(array_key_exists('label', $opt_array[$x])){
				$options .= pagelayer_sel_option($x, $opt_array[$x]['label'], $selected);
			
			// Optgroups
			} else{
				$options .= '<optgroup label="'. $x .'">';
				$options .= pagelayer_create_sel_options($opt_array[$x], $selected);
				$options .= '</optgroup>';
			}
		}
	}
	
	return $options;	
}

// Create option HTML
function pagelayer_sel_option($val, $lang, $selected){
	return '<option value="'. $val .'" '. (($val != $selected) ? '' : 'selected="selected"') .' >'. $lang .'</option>';
}

// Get values from multi-dimensional array by key 
function pagelayer_multi_array_search(&$array, $key){
	
	if(!is_array($array)){
		return false;
	}
	
	foreach ($array as $k => $v) {
	
		if($k == $key){
			return $v;
		}
	
		if (is_array($v)) {
			$found = pagelayer_multi_array_search($v, $key);
			if(!empty($found)){
				return $found;
			}
		}
	}
	
	return false;
}

function pagelayer_get_post_term(){
	
	$args = [
		'taxonomy' => array('category','post_tag'),
		'hide_empty' => false,
	];

	$terms = get_terms( $args );
	
	$ret = array();
	foreach ( $terms as $term ) {
		$ret[$term->taxonomy.':'.$term->slug.':'.$term->term_taxonomy_id] = $term->taxonomy .': '. $term->name;
	}
	//pagelayer_print($terms);die();
	return $ret;
}

function pagelayer_get_post_author(){
	
	$args = [
		'capability' => array( 'edit_posts' ),
		'fields' => [
			'ID',
			'display_name',
			'user_nicename',
		]
	];
				
	// Capability queries were only introduced in WP 5.9.
	if( version_compare( $GLOBALS['wp_version'], '5.9-alpha', '<' ) ){
		$args['who'] = 'authors';
		unset( $args['capability'] );
	}

	$authors = new \WP_User_Query( $args );
	
	$ret = array();
	foreach ( $authors->get_results() as $author ) {
		$ret[$author->user_nicename.':'.$author->ID] = $author->display_name;
	}
	//pagelayer_print($authors->get_results());die();
	return $ret;
}

// Gets the registered post types
function pagelayer_get_public_post_types( $args = [] ) {
	
	global $pagelayer;
	
	$post_type_args = [
		'public' => true,
	];
	
	$post_type_args = wp_parse_args( $post_type_args, $args );
	
	$_post_types = get_post_types( $post_type_args, 'objects' );

	$post_types = array();

	foreach ( $_post_types as $post_type => $object ) {
		
		if($post_type == $pagelayer->builder['name']){
			continue;
		}
		
		$post_types[ $post_type ] = $object->label;
	}
	//print_r($post_types);
	
	return $post_types;
}

// Simply echo and dir
function pagelayer_json_output(&$done){

	echo json_encode($done);
	wp_die();
	
}

// Get the current query for render the product
function pagelayer_shortcode_current_query($query_args, $atts, $type){
	global $wp_query;
	
	if($type == 'pagelayer_current_query'){
		
		if ( ! is_page( wc_get_page_id( 'shop' ) ) ) {
			$query_args = $wp_query->query_vars;
		}

		add_action( "woocommerce_shortcode_before_{$type}_loop", function () {
			wc_set_loop_prop( 'is_shortcode', false );
		} );

		if(!empty($atts['paginate'])){
			$page = get_query_var( 'paged', 1 );

			if( 1 < $page ) {
				$query_args['paged'] = $page;
			}
		}

		// Always query only IDs
		$query_args['fields'] = 'ids';

	}
	
	return $query_args;
}

// Loads the tags which have parameters of a particular type
function pagelayer_get_prop_type($types){
	
	global $pagelayer;
	
	if(!is_array($types)){
		$types = [$types];
	}
	
	$ret = [];
	
	// Loop thru all shortcodes
	foreach($pagelayer->shortcodes as $tag => $vvv){
	
		// Lets create the CSS, Classes, Attr. Also clean the dependent atts
		foreach($pagelayer->tabs as $tab){
			
			if(empty($pagelayer->shortcodes[$tag][$tab])){
				continue;
			}
			
			foreach($pagelayer->shortcodes[$tag][$tab] as $section => $Lsection){
				
				$props = empty($pagelayer->shortcodes[$tag][$section]) ? @$pagelayer->styles[$section] : @$pagelayer->shortcodes[$tag][$section];
				
				//echo $tab.' - '.$section.' - <br>';
				
				if(empty($props)){
					continue;
				}
				
				// Loop all props
				foreach($props as $prop => $param){
					
					// Load any attachment values
					if(!in_array($param['type'], $types)){
						continue;
					}
					
					$ret[$tag][$prop] = $param['type'];
				
				}
		
			}
			
		}
		
	}
	
	//r_print($ret);die();
	
	return $ret;
	
}

function pagelayer_export_content($content){
	
	global $pagelayer;
	
	// Just call do_shortcode so we can get list of media files to export
	//do_shortcode($content);
	
	$theme_url = preg_replace('/http(s?):\/\//is', '', get_stylesheet_directory_uri());
	
	$content = preg_replace('/http(s?):\/\/'.preg_quote($theme_url, '/').'/is', '{{theme_url}}', $content);
	
	// Remove unnecessary spaces
	$content = preg_replace('/--\>\s*(?!<!--)/is', '-->', $content);
	
	// Replace links of the theme for images
	$content = pagelayer_export_theme_links($content, get_stylesheet_directory_uri());
	
	////////////////////
	// Handle Link IDs
	////////////////////
	
	if(empty($pagelayer->e['link_tags'])){
		$pagelayer->e['link_tags'] = pagelayer_get_prop_type('link');
		//r_print($pagelayer->e['link_tags']);die();
	}
	
	if(empty($pagelayer->e['media_tags'])){
		$pagelayer->e['media_tags'] = pagelayer_get_prop_type(['image', 'video', 'audio', 'media', 'multi_image']);
		//r_print($pagelayer->e['media_tags']);die();
	}
	
	$parsed = parse_blocks($content);
	//r_print($parsed);//die();
	
	$parsed = pagelayer_export_link_ids($parsed);
	
	// Export the media as well
	$parsed = pagelayer_export_media($parsed);
	
	// Reserialize
	$content = serialize_blocks($parsed);
	
	// We store in SP format
	$content = str_replace('<!-- wp:pagelayer', '<!-- sp:pagelayer', $content);
	$content = str_replace('<!-- /wp:pagelayer', '<!-- /sp:pagelayer', $content);
	
	// Just pass the content to 
	$content = pagelayer_export_extract_menu_ids($content);
	
	// Apply a filter
	$content = apply_filters('pagelayer_export_content', $content);
	
	return $content;
	
}

// Export the menu IDs
function pagelayer_export_extract_menu_ids($content){
	
	global $pagelayer;
	
	// Any Menu ?
	preg_match_all('/\[pl_wp_menu ([^\]]*)nav_list="(\d*)"([^\]]*)\]/is', $content, $matches);	
	//r_print($matches);
	
	if(!empty($matches[2])){
	
		foreach($matches[2] as $k => $v){
			$v = (int)$v;
			$pagelayer->export_menus[$v] = $v;
		}
		//r_print($pagelayer->export_menus);
	
	}
	
	// Also for block format
	preg_match_all('/<!--\s+(?P<closer>\/)?sp:pagelayer\/pl_wp_menu\s+(?P<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+\/?-->).)*+)?}\s+)?(?P<void>\/)?-->/s', $content, $matches);
	
	if(!empty($matches['attrs'])){
		
		foreach($matches['attrs'] as $k => $v){
			preg_match('/nav_list"\s*:\s*"(\d*)"/is', $v, $vmatch);
			
			if(!empty($vmatch[1])){
				$vmatch[1] = (int)$vmatch[1];
				$pagelayer->export_menus[$vmatch[1]] = $vmatch[1];
				//r_print($pagelayer->export_menus);
			}			
		}
	}
	
	return $content;
	
}

function pagelayer_export_link_ids($parsed){
	
	global $pagelayer;
	
	foreach($parsed as $k => $v){
		
		if(!empty($v['innerBlocks'])){
			$parsed[$k]['innerBlocks'] = pagelayer_export_link_ids($v['innerBlocks']);
		}
		
		if(!preg_match('/pagelayer/is', $v['blockName'])){
			continue;
		}
		
		$tag = str_replace('pagelayer/', '', $v['blockName']);
		//echo $tag;//die();
		
		// Is there a tag having a link prop 
		if(empty($pagelayer->e['link_tags'][$tag])){
			continue;
		}
		//echo $tag;die();
		
		foreach($pagelayer->e['link_tags'][$tag] as $kk => $vv){
			if(!empty($v['attrs'][$kk]) && is_numeric($v['attrs'][$kk])){
				$linked = get_post($v['attrs'][$kk]);
				if(!empty($linked->post_name)){
					$parsed[$k]['attrs'][$kk] = '||link_id|'.$linked->post_type.'|'.$linked->post_name.'||';
				}
				//echo $tag;echo '-'.$kk;r_print($parsed[$k]['attrs']);die();
			}
		}
		
	}
	
	return $parsed;
	
}

// Replace theme links with theme_url
function pagelayer_export_theme_links($content, $link){
	
	global $sitepad;
	
	// Theme URL correction code
	$theme_url = preg_replace('/http(s?):\/\//is', '', $link);
	
	$theme_urls[0] = 'http://'.$theme_url;
	$theme_urls[1] = 'https://'.$theme_url;
	$theme_urls[2] = $theme_url;
	$theme_urls[3] = str_replace('/', '\\/', $theme_urls[0]);
	$theme_urls[4] = str_replace('/', '\\/', $theme_urls[1]);
	$theme_urls[5] = str_replace('/', '\\/', $theme_url);
	
	foreach($theme_urls as $k => $v){
		$content = preg_replace_callback('/'.preg_quote($v, '/').'([^"]*)/is', 'pagelayer_export_theme_links_replacer', $content);
	}
	
	$content = str_ireplace($theme_urls, '{{theme_url}}', $content);
	
	return $content;
}

// This function is a part of pagelayer_export_theme_links
function pagelayer_export_theme_links_replacer($matches){
	return '{{theme_url}}'.str_replace('\\/', '/', $matches[1]);
}

// Export media
function pagelayer_export_media($parsed){
	
	global $pagelayer;
	
	foreach($parsed as $k => $v){
		
		if(!empty($v['innerBlocks'])){
			$parsed[$k]['innerBlocks'] = pagelayer_export_media($v['innerBlocks']);
		}
		
		if(!preg_match('/pagelayer/is', $v['blockName'])){
			continue;
		}
		
		$tag = str_replace('pagelayer/', '', $v['blockName']);
		//echo $tag;//die();
		
		// Is there a tag having a link prop 
		if(empty($pagelayer->e['media_tags'][$tag])){
			continue;
		}
		//echo $tag;die();
		
		foreach($pagelayer->e['media_tags'][$tag] as $kk => $vv){
			
			if(empty($v['attrs'][$kk])){
				continue;
			}
			
			$is_array = 0;
			
			// Is it an array
			if(is_array($v['attrs'][$kk])){
				
				$ids = $v['attrs'][$kk];
				$is_array = 1;
			
			// Is it of the format 1,2,3
			}elseif(preg_match('/^((\d*)(,?))*$/is', $v['attrs'][$kk])){
				
				$ids = pagelayer_maybe_explode(',', $v['attrs'][$kk]);
			
			// Its a number or string
			}else{
			
				// Is it our default image ?
				if(preg_match('/pagelayer-pro\/images\/default-image.png$/is', $v['attrs'][$kk])){
					$parsed[$k]['attrs'][$kk] = '';
					continue;
				}
				
				// It should be a number
				if(!is_numeric($v['attrs'][$kk])){
					continue;
				}
				
				$ids = [$v['attrs'][$kk]];
			}
			
			$ret = [];
			
			foreach($ids as $id){
			
				$file = pagelayer_export_media_files($id, $exp_img_url);
				
				// Did it export ?
				if(empty($file) || !file_exists($file)){
					continue;
				}
				
				$ret[] = $exp_img_url;
				
			}
			
			$parsed[$k]['attrs'][$kk] = $is_array ? $ret : implode(',', $ret);
			//echo $tag;echo '-'.$kk;r_print($parsed[$k]['attrs']);die();
		}
		
	}
	
	return $parsed;
	
}

// Exports the media to the images folder of the current theme
function pagelayer_export_media_files($id, &$url = ''){
	
	$theme_dir = get_stylesheet_directory();
	$image_dir = $theme_dir.'/images/';
	@mkdir($image_dir);
	
	// Get the file path
	$file = get_attached_file($id);
	
	if(empty($file) || !file_exists($file)){
		return false;
	}
	
	$dest_file = $image_dir.basename($file);
	
	// Copy the file
	copy($file, $dest_file);
	
	// Replace the text
	$url = '{{theme_url}}/images/'.basename($file);
	
	return $dest_file;
	
	//echo $content;
}

// Insert a post which is a Pagelayer Post
function pagelayer_sanitize_content($content){
	
	// Replace Vars
	$template_vars = pagelayer_template_vars();
	
	foreach($template_vars as $key => $val){
		$content = str_replace($key, $val, $content);
	}
	
	if(defined('PAGELAYER_BLOCK_PREFIX') && PAGELAYER_BLOCK_PREFIX == 'wp'){
		$content = str_replace('<!-- sp:pagelayer', '<!-- wp:pagelayer', $content);
		$content = str_replace('<!-- /sp:pagelayer', '<!-- /wp:pagelayer', $content);
	}
	
	return $content;
}

// Insert a post which is a Pagelayer Post
function pagelayer_insert_content($post, &$ret){
	
	$post = apply_filters('pagelayer_start_insert_content', $post);
	
	$post['post_content'] = pagelayer_sanitize_content($post['post_content']);
	//pagelayer_print($post);die();
	
	// Add slashes for safe insert
	$post['post_content'] = wp_slash($post['post_content']);
	
	$post = apply_filters('pagelayer_pre_insert_content', $post);
	
	// Now insert / update the post
	$ret = wp_insert_post($post);

	// Did we save the post ?
	if(empty($ret) || is_wp_error($ret)){
		return false;
	}

	// Convert to pagelayer accessed post
	if(!add_post_meta($ret, 'pagelayer-data', time(), true)){
		update_post_meta($ret, 'pagelayer-data', time());
	}
	
	return $ret;

}

// Gets the list of enabled fonts
function pagelayer_enabled_icons(){
		
	$stored_icons = get_option('pagelayer_icons_set');
	if(empty($stored_icons)){
		update_option('pagelayer_icons_set', ['font-awesome5']);
		$stored_icons = get_option('pagelayer_icons_set');
	}
	
	return $stored_icons;
	
}

// Install the Pro version
function pagelayer_install_pro(){
	
	global $pagelayer;
	
	// Include the necessary stuff
	include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	// Includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
	include_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/misc.php' );
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	// Filter to prevent the activate text
	add_filter('install_plugin_complete_actions', 'pagelayer_install_plugin_complete_actions', 10, 3);

	$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin(  ) );
	$installed = $upgrader->install(PAGELAYER_API.'download.php?version=latest&license='.$pagelayer->license['license']);
	
	if ( !is_wp_error( $installed ) && $installed ) {
		echo 'Activating Pagelayer Pro !';
		$activate = activate_plugin(PAGELAYER_PRO_BASE);
		
		if ( is_null($activate) ) {
			echo '<div id="message" class="updated"><p>'. __('Done! Pagelayer Pro is now installed and activated.', 'pagelayer'). '</p></div><br />';
			echo '<br><br><b>Done! Pagelayer Pro is now installed and activated.</b>';
		}
	}
	
	return $installed;
	
}

// Prevent pro activate text for installer
function pagelayer_install_plugin_complete_actions($install_actions, $api, $plugin_file){
	
	if($plugin_file == PAGELAYER_PRO_BASE){
		return array();
	}
	
	return $install_actions;
}

// Load license data
function pagelayer_load_license(){
	
	global $pagelayer;
	
	// Load license
	$pagelayer->license = get_option('pagelayer_license');
	
	// Update license details as well
	if(!empty($pagelayer->license) && (time() - @$pagelayer->license['last_update']) >= 86400){
		
		$resp = wp_remote_get(PAGELAYER_API.'license.php?license='.$pagelayer->license['license']);
		
		// Did we get a response ?
		if(is_array($resp)){
			
			$tosave = json_decode($resp['body'], true);
			
			// Is it the license ?
			if(!empty($tosave['license'])){
				$tosave['last_update'] = time();
				update_option('pagelayer_license', $tosave);
			}
			
		}
		
	}
	
}

// Handle hexa to rgba and also remove alpha which is ff
function pagelayer_hex8_to_rgba($val){
	
	// If opacity is ff then discard ff
	if(preg_match('/^#([a-f0-9]{6})ff$/is', $val)){
		return substr($val, 0, 7);
	}
	
	// Lets handle the RGB+opacity
	if(preg_match('/#([a-f0-9]{8})$/is', $val)){
		$val = pagelayer_hex2rgba($val);
	}
	
	return $val;
	
}

// Convert to RGBA from HEX
function pagelayer_hex2rgba($color){
 
	//Return if no color provided
	if(empty($color)){
		return;
	}
	
	//Sanitize $color if "#" is provided 
	if ($color[0] == '#'){
		$color = substr( $color, 1 );
	}
	
	//Check if color has 6 or 3 characters and get values
	if (strlen($color) == 8) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		$alpha = $color[6] . $color[7];
		//$alpha = '';
	} elseif (strlen($color) == 6) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return;
	}
 
	//Convert hexadec to rgb
	$rgb =  array_map('hexdec', $hex);

	//Check if opacity is set(rgba or rgb)
	if($alpha){
		
		$alpha = number_format((float)hexdec($alpha) / 255, 2, '.', '');
		//print_r($alpha);
		if(abs($alpha) > 1){
			$alpha = 1.0;
		}
		$output = 'rgba('.implode(",",$rgb).','.$alpha.')';
	} else {
		$output = 'rgb('.implode(",",$rgb).')';
	}

	//Return rgb(a) color string
	return $output;
}

// Get social URLs
function pagelayer_get_social_urls(){
	
	$urls = array();
	
	$urls['facebook'] = get_option('pagelayer-facebook-url');
	$urls['twitter'] = get_option('pagelayer-twitter-url');
	$urls['instagram'] = get_option('pagelayer-instagram-url');
	$urls['linkedin'] = get_option('pagelayer-linkedin-url');
	$urls['youtube'] = get_option('pagelayer-youtube-url');
	$urls['google'] = get_option('pagelayer-gplus-url');
	
	foreach($urls as $k => $v){
		
		if(empty($v)){
			unset($urls[$k]);
			continue;
		}
		
		$urls[$k] = esc_url($v);
	}
	
	return $urls;
}

function pagelayer_get_option($opt){
	$ret = get_option($opt);
	
	$opts = array(
		'pagelayer-address' => '1, My Address, My Street, New York City, NY, USA',
		'pagelayer-phone' => '+1234567890',
		'pagelayer-copyright' => '© '.date('Y').' '.get_option('blogname'),
		'pagelayer_cf_to_email' => 'contact@domain.com',
		'pagelayer_cf_success' => __pl('cf_success'),
		'pagelayer_cf_failed' => __pl('cf_failed'),
	);
	
	if(empty($ret)){
		return $opts[$opt];
	}
	
	return wp_kses_post($ret);
}

// Uploads an image / media
function pagelayer_upload_media($filename, $blob){
	
	if(empty($blob)){
		return false;
	}
	
	$md5 = md5($blob);
	
	// Do we have this image
	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'meta_query' => array(
			array(
				'key'     => 'pagelayer_image_md5',
				'value'   => $md5,
			)
		)
	);
			
	$query = new WP_Query($args);
	
	// If we found the image, return
	foreach($query->posts as $ck => $cv){
		return $cv->ID;
	}
	
	$upload = wp_upload_bits($filename, null, $blob);

	if( !empty( $upload['error'] ) ) {
		return false;
	}

	$file_path = $upload['file'];
	$file_name = basename( $file_path );
	$file_type = wp_check_filetype( $file_name, null );
	$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
	$wp_upload_dir = wp_upload_dir();

	$post_info = array(
		'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
		'post_mime_type' => $file_type['type'],
		'post_title'     => $attachment_title,
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );
	update_post_meta($attach_id, 'pagelayer_image_md5', $md5);	
	
	$lib = ABSPATH . 'site-admin/includes/image.php';
	$media = ABSPATH . 'site-admin/includes/media.php';
	$media = file_exists($media) ? $media : ABSPATH . 'wp-admin/includes/media.php';
	$lib = file_exists($lib) ? $lib : ABSPATH . 'wp-admin/includes/image.php';
	
	require_once($media);
	require_once($lib);
	
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
	wp_update_attachment_metadata( $attach_id,  $attach_data );
	
	return $attach_id;
	
}

// Show the notice of importing the active themes content
function pagelayer_theme_import_notices($return = false){
	
	$theme = wp_get_theme();
	
	$imported = get_option('pagelayer_theme_'.get_template().'_imported');
	$show = 0;
	
	// We need to import the content
	if(empty($imported) && is_admin() && current_user_can('switch_themes')){
		$show = 1;
	}
	
	$dismissed = get_option('pagelayer_theme_'.get_template().'_dismissed');
	
	// Is this dismissed
	if($dismissed){
		$show = 0;
	}
	
	//$show = 1;
	
	// Is it the importer page ?
	if(!empty($_REQUEST['page']) && $_REQUEST['page'] == 'pagelayer_import'){
		$show = 0;
	}
	
	// Show the message
	if(!empty($show)){
		$str = '

<style>
.pagelayer_promo_button {
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
.pagelayer_promo_button:focus,
.pagelayer_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.pagelayer_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.pagelayer_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.pagelayer_promo_button1:hover {
border:3px solid #4CAF50;
}
.pagelayer_promo_button2 {
color: white;
background-color: #0085ba;
}
.pagelayer_promo_button3 {
color: white;
background-color: #365899;
}
.pagelayer_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.pagelayer_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.pagelayer_promo-close:hover{
color: red;
}
</style>

<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#pagelayer_promo .pagelayer_promo-close").click(function(){
			var data;
			jQuery("#pagelayer_promo").hide();
			// Save this preference
			jQuery.post("'.admin_url('?pagelayer_promo=0').'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

<div class="notice notice-success" id="pagelayer_promo" style="min-height:30px">
	<a class="pagelayer_promo-close" href="javascript:" aria-label="Dismiss this Notice">
		<span class="dashicons dashicons-dismiss"></span> Dismiss
	</a>';
	
	$str .= apply_filters('pagelayer_theme_import_notice_content', '<p>Please click <a href="'.admin_url('admin.php?page=pagelayer_import').'">here</a> to import your themes content</p>').'
	
</div>';
	
		// Print it
		if(empty($return)){
			echo $str;
		}else{
			return $str;
		}
	}

}

// Parse the array variables
function pagelayer_replace_vars( $string, $array, $pre = '', $suf = ''){
	
	$array = (array) $array;
	
	foreach($array as $kk => $vv){
		
		if(is_array($vv)){
			$vv = pagelayer_flat_join($vv);
		}
		
		$string = str_replace($pre.$kk.$suf, $vv, $string);	
	}
	return $string;
}

// Add Attachment to the contact the email
function pagelayer_cf_email_attachment( &$phpmailer) {
	
	if ( ! empty( $_FILES ) ) {
		foreach ( $_FILES as $attachment ) {

			if(empty($attachment['name'])){
				continue;
			}

			try {
				$phpmailer->addAttachment($attachment['tmp_name'], $attachment['name'], $encoding = 'base64', $attachment['type'], $disposition = 'attachment');
			} catch ( \Exception $e ) {
				continue;
			}
		}
	}

}

function pagelayer_array_flatten( $input ) {
	if ( ! is_array( $input ) ) {
		return array( $input );
	}

	$output = array();

	foreach ( $input as $value ) {
		$output = array_merge( $output, pagelayer_array_flatten( $value ) );
	}

	return $output;
}

function pagelayer_flat_join( $array ) {
	$array = pagelayer_array_flatten( $array );
	$output = array();

	foreach ( (array) $array as $value ) {
		$output[] = trim( (string) $value );
	}

	return implode( ', ', $output );
}

// Verifies the Google Captcha
function pagelayer_captcha_verify(){
	
	// If secret key is not there, return
	$captcha_secret = get_option('pagelayer_google_captcha_secret');
	
	if(empty($captcha_secret)){
		return true;
	}
	
	$response = (!empty($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '');
	$ip = pagelayer_getip();
	
	// Is the IP or response not there ?
	if(empty($response) || empty($ip)){
		return false;
	}
	
	$url = 'https://www.google.com/recaptcha/api/siteverify';

	// Verify the post
	$req = wp_remote_post($url, array(
					'timeout' => 20, 
					'body' => array(
						'secret' => $captcha_secret,
						'response' => $response, 
						'remoteip' => $ip
					)
				)
			);

	// Was there an error posting ?
	if(is_wp_error($req)){		
		return false;
	}
	
	// Process the post response
	$resp = wp_remote_retrieve_body($req);
		
	// Is the body valid
	if(empty($resp)){
		return false;
	}
	
	$json = json_decode($resp, true);
	
	if(!empty($json['success'])){
		return true;
	}
	
	return false;
}

// Pagelayer serialize attributes
function pagelayer_serialize_attributes( $block_attributes ) {
	$encoded_attributes = json_encode( $block_attributes, JSON_FORCE_OBJECT);
	$encoded_attributes = preg_replace( '/--/', '\\u002d\\u002d', $encoded_attributes );
	$encoded_attributes = preg_replace( '/</', '\\u003c', $encoded_attributes );
	$encoded_attributes = preg_replace( '/>/', '\\u003e', $encoded_attributes );
	// Regex: /\\"/
	$encoded_attributes = preg_replace( '/\\\\"/', '\\u0022', $encoded_attributes );

	return $encoded_attributes;
}

function pagelayer_enable_giver(){
	global $pagelayer;
	return !empty($pagelayer->settings['enable_giver']) && $pagelayer->settings['enable_giver'] == 1;
}

function pagelayer_load_font_options(){
	include_once(PAGELAYER_DIR.'/main/font-options.php');
}

function pagelayer_is_utf8($str) {
    return (bool) preg_match('//u', $str);
}

// Create blank images
function pagelayer_create_blank_image($width, $height){
	
	$im = @imagecreate($width, $height);
	
	if(empty($im)){
		return false;
	}
	
	$background_color = imagecolorallocate($im, 220, 220, 220);
	$text_color = imagecolorallocate($im, 110, 110, 110);
	$string = $width.' x '.$height;
	$font_size = 30;
	$sx = ($width / 2) - (strlen($string) * $font_size / 2);
	$sy = ($height / 2) - ($font_size / 2);
	$font = realpath(PAGELAYER_DIR.'/fonts/OpenSans-BoldItalic.ttf');
	imagettftext($im, $font_size, 0, $sx, $sy, $text_color, $font, $string);
	ob_start();
	imagepng($im);
	imagedestroy($im);
	
	return ob_get_clean();
	
}

// Get category name by post type
function pagelayer_post_type_category($post_type){	
	
	$taxonomies = get_object_taxonomies( $post_type, 'objects');
	
	foreach( $taxonomies as $key => $tax){
		if(!empty($tax->hierarchical)){
			return $key;
		}
	}
	
	return false;
	
}

// Get tag name by post type
function pagelayer_post_type_tag($post_type){	
	
	$taxonomies = get_object_taxonomies( $post_type, 'objects');
	
	foreach( $taxonomies as $key => $tax){
		if(!empty($tax->show_tagcloud) && empty($tax->hierarchical)){
			return $key;
		}
	}
	return false;
	
}

// Get checkboxes of post categories.
function pagelayer_post_cats($post, &$cat_name = ''){
	
	$ret = [];
	
	$cat_name = pagelayer_post_type_category($post->post_type);
	
	if(empty($cat_name)){
		return $ret;
	}

	include_once(ABSPATH.PAGELAYER_CMS_DIR_PREFIX.'-admin/includes/template.php');

	$args1 = array(
		'taxonomy'             => $cat_name,
		'checked_ontop'        => false,
		'echo'        		   => false,
	);
	
	$ret['with_checkbox'] = '<ul class="pagelayer-post-category" >'.wp_terms_checklist($post->ID, $args1 ).'</ul>';
		
	$args2 = array(
		'taxonomy'	   => $cat_name,
		'hierarchical' => true,
		'echo' 		   => 0,
		'class'   	   => 'pagelayer-add-cat',
		'name'		   => 'pagelayer_cat_parent',
		'style'        => 'list',
		'hide_empty'   => 0,
	);
	
	$ret['without_checkbox'] = wp_dropdown_categories( $args2 );
	
	return $ret;
	
}

// Get checkboxes of post tags.
function pagelayer_post_tags($post, &$tag_name = ''){
	
	$ret = [];	
	
	$tag_name = pagelayer_post_type_tag($post->post_type);
	
	if(empty($tag_name)){
		return $ret;
	}
	
	$ret['postTags'] = wp_get_post_terms( $post->ID, $tag_name );
		
	$ret['allTags'] = get_tags(array( 'hide_empty' => false, 'taxonomy' => $tag_name ));
	
	return $ret;
	
}

// Get users who can edit a particular post type
function pagelayer_post_authors_by_type($post_type){

	global $wp_post_types;
	
	if(empty($wp_post_types[$post_type])){
		$users = get_users( array( 'role__in' => array( 'administrator' ) ) );
	}else{
		$req_role = $wp_post_types[$post_type]->cap->edit_posts;
		$roles__in = [];
		foreach( wp_roles()->roles as $role_slug => $role ){
			if( ! empty( $role['capabilities'][$req_role] ) )
				$roles__in[] = $role_slug;
		}
		$users = get_users( array( 'role__in' => $roles__in ) );
	}
	
	$user_array = [];
	
	foreach ( $users as $user ) {
		$user_array[$user->ID] = $user->display_name;
	}
	
	return $user_array;
}

function pagelayer_parent_post_prop($post){
	global $wp_post_types;
	
	if(!empty($wp_post_types[$post->post_type]->hierarchical)){
		return pagelayer_list_parents(0, 0, $post, []);
	}else{
		return [];
	}
}

function pagelayer_list_parents( $parent = 0, $level = 0, $post = null, $values=array('')){
	
	global $wpdb;

	$post  = get_post( $post );
	
	$items = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_parent, post_title FROM $wpdb->posts 
			WHERE post_parent = %d 
			AND post_type = '".$post->post_type."' 
			AND post_status = 'publish' 
			ORDER BY menu_order", $parent ) );
	
	if ( !empty($items) ) {
		foreach ( $items as $item ) {
			
			// A page cannot be its own parent.
			if ( $post && $post->ID && (int) $item->ID === $post->ID ) {
				continue;
			}
			
			$pad = str_repeat( '-', $level );
			
			if(count($values)==0){
				$values[] = '';
			}

			$values[' '.$item->ID] = $pad.esc_html( $item->post_title );

			$values = pagelayer_list_parents($item->ID, $level + 1, $post, $values );			
		}
	}
	
	return $values;
}

// Maybe explode
function pagelayer_maybe_explode( $separator = ',', $string = '', $limit = PHP_INT_MAX ){
	
	if(is_array($string)){
		return $string;
	}
	
	return explode($separator, $string, $limit);
}

// Maybe implode
function pagelayer_maybe_implode($array, $separator = ','){
	
	if(is_array($array)){
		return @implode($separator, $array);
	}
	
	return $array;
	
}

// Sanitize text field or array
function pagelayer_sanitize_text_field($str) {
	
	if ( !is_object( $str ) && !is_array( $str ) ) {
		return sanitize_text_field($str);
	}

	if( is_array($str) ){
		return array_map( 'pagelayer_sanitize_text_field', $str);
	}
	
	return $str;
}

// Update nav menu item
function pagelayer_save_nav_menu_items($items){
			
	// Menu setting keys
	$allowed_post = array('title' => 'post_title');
	$allowed_meta = array('_pagelayer_content');
	
	foreach($items as $id => $values){
		
		$meta_input = array();
		$id = (int) $id;
		$post = get_post($id);
					
		// Post found ?
		if(empty($post)){
			continue;
		}
					
		foreach($values as $kk => $vv){
			
			if(array_key_exists($kk, $allowed_post)){
				$name = $allowed_post[$kk];
				$post->$name = $vv;
			}
			
			if(!in_array($kk, $allowed_meta)){
				continue;
			}
			
			$meta_input[$kk] = stripslashes_deep($vv);			
		}
		
		if(isset($meta_input['_pagelayer_content'])){
			$meta_input['_pagelayer_content'] = base64_decode($meta_input['_pagelayer_content']);
		}
		
		$post->meta_input = $meta_input;
		wp_update_post($post);
	}
}

// Update customizer options
function pagelayer_save_customizer_options($customizer_options){
	global $pagelayer;
	
	// To load customizer params array
	if(empty($pagelayer->customizer_params)){
		pagelayer_load_shortcodes();
	}	
	
	$customizer_options = apply_filters( 'pagelayer_save_customizer_options', $customizer_options );
	$customizer_data = array();
	$customizer_option_data = array();
	
	foreach($pagelayer->customizer_params as $ck => $cv){
		foreach($pagelayer->screens as $sk => $sv){
			
			$screen = (!empty($sv) ? '_'.$sv : '');
			$screen_key = $ck.$screen;
			
			// Save to pagelayer customizer mods
			if( empty($cv['option']) ){	
				if( isset($customizer_options[$screen_key])){
					$customizer_data[$screen_key] = $customizer_options[$screen_key];
				}
				continue;
			}
			
			$option_key = $cv['option'].$screen;
			
			// Update option
			if( isset($customizer_options[$screen_key] ) ) {
				update_option( $option_key, $customizer_options[$screen_key]);
				continue;
			}
			
			delete_option($option_key);
		}
	}
	
	// Set Typography settings for global and current post type
	$posttype=array(
		'' => '', // Make this empty for global values
		'current' => $customizer_options['pagelayer_current_post_type']
	);

	$typo = ['font-family', 'font-size', 'font-style', 'font-weight', 'font-variant', 'decoration-line', 'line-height', 'text-transform', 'text-spacing', 'word-spacing'];
	
	foreach($posttype as $type => $tv){
		
		foreach($pagelayer->css_settings as $set => $params){
				
			foreach($pagelayer->screens as $sk => $sv){
				
				$screen = (!empty($sv) ? '_'.$sv : '');
				$post_type = (!empty($tv) ? '_'.$tv : '');
				$post_key = (!empty($type) ? $type.'_' : '');
				$post_key_set = $post_key.$set;
				
				// Create key as per customizer shortcode
				$key = $post_key_set.$screen;
				
				// Create option keys for the global and current post type 
				$option_setting = empty($params['key']) ? 'pagelayer_'.$set.'_css'.$post_type : $params['key'].$post_type;
				
				$props = array(
					'color' => $post_key_set.'_color'.$screen,
					'padding' => $post_key_set.'_padding'.$screen,
					'margin' => $post_key_set.'_margin'.$screen,
					'background-color' => $post_key_set.'_background_color'.$screen,
				);
				
				$globalsetting = array();
				
				// Set Typography
				if(isset($customizer_options[$key])){
					foreach($customizer_options[$key] as $k => $v){
						if($v == 'Default' || empty($v)){
							unset($customizer_options[$key][$k]);
						}

						// For sidebar, width default should not be saved
						if($set == 'sidebar' && $k == 'width' && $v == 20){
							unset($customizer_options[$key][$k]);
						}

						$globalsetting[$key][$typo[$k]] = $customizer_options[$key][$k];
						unset($customizer_options[$key][$k]);
					}
				}
				
				// Set other props with typography
				foreach($props as $pk => $pv){
					
					if(!isset($customizer_options[$pv])){
						continue;
					}
					
					$globalsetting[$key][$pk] = $customizer_options[$pv];
					unset($customizer_options[$pv]);
					
				}

				// Are we to save ?						
				if(!empty($globalsetting[$key])){
					update_option($option_setting.$screen, $globalsetting[$key]);
				}else{
					delete_option($option_setting.$screen);
				}
					
			}
			
		}
	}
	
	$customizer_data = apply_filters( 'pagelayer_customizer_options', $customizer_data, $customizer_options);
	
	update_option('pagelayer_customizer_mods', $customizer_data);
}

// Get customizer options values
// return array to set customizer block attributes
function pagelayer_get_customizer_options(){
	global $pagelayer, $post;
	
	$options = array();
	
	// To load customizer params array
	if(empty($pagelayer->customizer_params)){
		pagelayer_load_shortcodes();
	}	
	
	// Get values form options
	foreach($pagelayer->customizer_params as $ck => $cv){
		
		if( empty($cv['option']) ){
			continue;
		}
		
		$screens = array('desktop' => '');
		
		if( !empty($cv['screen']) ){
			$screens = $pagelayer->screens;
		}
		
		foreach($screens as $sk => $sv){
			
			$screen = (!empty($sv) ? '_'.$sv : '');
			$screen_key = $cv['option'].$screen;
			$screen_ck = $ck.$screen;
			
			// Get option value
			$c_option = get_option($screen_key);

			if(empty($c_option)){
				continue;
			}
			
			$options[$screen_ck] = $c_option;
		}
	}
	
	
	// Get values from pagelayer mods
	$mods = get_option('pagelayer_customizer_mods');

	if(!empty($mods)){
		$options = array_merge($options, $mods);
	}
	
	$posttype=array(
		'' => '',
		'current' => $post->post_type
	);
	
	$typo = ['font-family', 'font-size', 'font-style', 'font-weight', 'font-variant', 'decoration-line', 'line-height', 'text-transform', 'text-spacing', 'word-spacing'];

	foreach($posttype as $type => $tv){

		foreach($pagelayer->css_settings as $set => $params){		

			foreach($pagelayer->screens as $sk => $sv){
				
				$screen = (!empty($sv) ? '_'.$sv : '');
				$postprefix = (!empty($tv) ? '_'.$tv : '');
				$postk = (!empty($type) ? $type.'_' : '');
				$post_key_set = $postk.$set;
				
				// Create key as per customizer shortcode
				$key = $post_key_set.$screen;
				
				// Create option keys for the global and current post type 
				$setting = empty($params['key']) ? 'pagelayer_'.$set.'_css'.$postprefix : $params['key'].$postprefix;
				
				$props = array(
					'color' => $post_key_set.'_color'.$screen,
					'padding' => $post_key_set.'_padding'.$screen,
					'margin' => $post_key_set.'_margin'.$screen,
					'background-color' => $post_key_set.'_background_color'.$screen,
				);
				
				$optarray = get_option($setting.$screen);

				if(!empty($optarray)){
					
					// Get Typography
					foreach($typo as $tk => $tv){
						
						$tval = '';
						
						if(isset($optarray[$tv])){
							$tval = $optarray[$tv];
						}
						
						$options[$key][$tk] = $tval;
					}
					
					foreach($props as $pk => $pv){
						if(empty( $optarray[$pk])){
							continue;
						}
						
						$options[$pv] = $optarray[$pk];
					}
				}
			}
		}
	}
	
	$options = apply_filters( 'pagelayer_get_customizer_options', $options);
	
	return $options;
}

// Sanitize style props for colors and fons
function pagelayer_load_global_palette(){
	global $pagelayer;
	
	// Default global colors
	$pagelayer->global_colors = array(
		'primary' => array(
			'title' => 'Primary',
			'value' => '#007bff',
		),
		'secondary' => array(
			'title' => 'Secondary',
			'value' => '#6c757d',
		),
		'text' => array(
			'title' => 'Text',
			'value' => '#1d1d1d',
		),
		'accent' => array(
			'title' => 'Accent',
			'value' => '#61ce70',
		),
	);
	
	// Global color
	$global_color = get_option('pagelayer_global_colors');
	
	if(!empty($global_color)){
		$pagelayer->global_colors = json_decode($global_color, true);
	}
	
	// Default global fonts
	$pagelayer->global_fonts = array(
		'primary' => array(
			'title' => 'Primary',
			'value' => $pagelayer->default_font_styles(),
		),
		'secondary' => array(
			'title' => 'Secondary',
			'value' => $pagelayer->default_font_styles(array('font-family' => 'Roboto')),
		),
		'text' => array(
			'title' => 'Text',
			'value' => $pagelayer->default_font_styles(array('font-family' => 'Montserrat')),
		),
		'accent' => array(
			'title' => 'Accent',
			'value' => $pagelayer->default_font_styles(array('font-family' => 'Poppins')),
		),
	);
	
	// Global font
	$global_font = get_option('pagelayer_global_fonts');
	
	if(!empty($global_font)){
		$pagelayer->global_fonts = json_decode($global_font, true);
	}
}

// Get global colors
function pagelayer_sanitize_global_color($val){
	global $pagelayer;
	
	if(!is_string($val) || $val[0] != '$'){
		return $val;
	}
	
	$gkey = substr($val, 1);
	$gcolor = isset($pagelayer->global_colors[$gkey]) ? $gkey : 'primary';
	$val = 'var(--pagelayer-color-'.$gcolor.')';
		
	return $val;
}

// Sanitize style props for colors and fons
function pagelayer_sanitize_global_style($val, $val_desk = '', $mode = 'desktop'){
	global $pagelayer;
	
	// Sanitize Global background color
	if( !empty($val['background-color']) &&  $val['background-color'][0] == '$'){
		$val['background-color'] = pagelayer_sanitize_global_color($val['background-color']);
	}
	
	// Sanitize Global color
	if( !empty($val['color']) &&  $val['color'][0] == '$'){
		$val['color'] = pagelayer_sanitize_global_color($val['color']);
	}
	
	// Not set global for typo
	if(empty($val['global-font']) && empty($val_desk['global-font'])){
		return $val;
	}
	
	$_desk_global = false;
	
	if(empty($val['global-font'])){
		$font_key = @$val_desk['global-font'];
		$_desk_global = true;
	}else{
		$font_key = $val['global-font'];
		unset($val['global-font']);
	}
	
	if(empty($font_key)){
		return $val;
	}
	
	if(!isset($pagelayer->global_fonts[$font_key])){
		$font_key = 'primary';
	}
	
	$_val = [];
		
	// Apply global typo
	foreach($pagelayer->typo_props as $typo_key){
				
		if(!empty($val[$typo_key])){
			continue;
		}
		
		$global_val = $pagelayer->global_fonts[$font_key]['value'];
		
		if( empty($global_val[$typo_key]) || (is_array($global_val[$typo_key]) && empty($global_val[$typo_key][$mode])) || (!is_array($global_val[$typo_key]) && !empty($_desk_global) && $mode != 'desktop') ){
			continue;
		}
		
		$_val[$typo_key] = 'var(--pagelayer-font-'.$font_key.'-'.$typo_key.')';
	}
	
	// This array contains other values like margin padding so we merge the array
	$val = empty($val) ? array() : $val;
	$val = array_merge($val, $_val);
	return $val;
}

//Check an empty array
function pagelayer_is_empty_array($arr){

	foreach ($arr as $key => $value) {
		if (trim($value) !== '') return false;
	}

	return true;
}

// Pagelayer load font family
function pagelayer_load_font_family($font, $font_weight, $font_style){
	global $pagelayer;

	// Load global fonts
	if(strripos($font, 'var(') !== false){
		$matches = [];
		preg_match('/(var\(--pagelayer-font-)(\w+)(-font-family\))/i', $font, $matches);
		$font_key = @$matches[2];
		$font = @$pagelayer->global_fonts[$font_key]['value']['font-family'];
	}
	
	// Load global fonts weight
	if(!empty($font_weight) && strripos($font_weight, 'var(') !== false){
		$matches = [];
		preg_match('/(var\(--pagelayer-font-)(\w+)(-font-weight\))/i', $font_weight, $matches);
		$font_key = @$matches[2];
		
		// Convert in string
		$font_weight = @$pagelayer->global_fonts[$font_key]['value']['font-weight'];
	}
	
	// Load global fonts style
	if(!empty($font_style) && strripos($font_style, 'var(') !== false){
		$matches = [];
		preg_match('/(var\(--pagelayer-font-)(\w+)(-font-style\))/i', $font_style, $matches);
		$font_key = @$matches[2];
		
		// Convert in string
		$font_style = @$pagelayer->global_fonts[$font_key]['value']['font-style'];
	}
	
	if(empty($font)){
		return;
	}

	$font_weights = array('400' =>'400');
	$font_style = empty($font_style) ? 'normal' : $font_style;							
	$font_style = in_array($font_style, ['italic', 'oblique']) ? 'i' : '';

	if(!empty($font_weight)){
		
		if(!is_array($font_weight)){
			$font_weight = array($font_weight);
		}
		
		foreach($font_weight as $weight){
			$font_weights[$weight.$font_style] = $weight.$font_style;
		}
	}

	foreach($font_weights as $fkey => $fvalue){
		$pagelayer->runtime_fonts[$font][$fkey] = $fvalue;
	}

}
