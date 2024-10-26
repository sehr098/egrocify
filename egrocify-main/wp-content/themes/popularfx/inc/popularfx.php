<?php

if(!function_exists('popularfx_cleanpath')){
	
function popularfx_cleanpath($path){
	$path = str_replace('\\', '/', $path);
	$path = str_replace('//', '/', $path);
	return rtrim($path, '/');
}

}

if(!function_exists('popularfx_get_current_theme_slug')){

// Return the name of the current theme folder
function popularfx_get_current_theme_slug(){
	
	$theme_root = popularfx_cleanpath(get_theme_root());	
	$debug = debug_backtrace();
	$caller = popularfx_cleanpath($debug[0]['file']);
	
	$left = str_ireplace($theme_root.'/', '', $caller);
	$val = explode('/', $left);
	return trim($val[0]);
	
}

}

// Install Pagelayer Pro
if(!function_exists('popularfx_install_pagelayer')){
	
function popularfx_install_pagelayer(){
	
	global $pagelayer;
	
	if(!empty($_GET['license']) && function_exists('pfx_install_pagelayer_pro')){
		return pfx_install_pagelayer_pro();
	}
	
	// Include the necessary stuff
	include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	// Includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
	include_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/misc.php' );
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	// Filter to prevent the activate text
	add_filter('install_plugin_complete_actions', 'popularfx_install_pagelayer_complete_actions', 10, 3);
	
	echo '<h2>'.__('Install Pagelayer Free Version', 'popularfx').'</h2>';

	$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin(  ) );
	$installed = $upgrader->install('https://downloads.wordpress.org/plugin/pagelayer.zip');
	
	if(is_wp_error( $installed ) || empty($installed)){
		return $installed;
	}
	
	if ( !is_wp_error( $installed ) && $installed ) {
		echo __('Activating Pagelayer Plugin !', 'popularfx');
		$installed = activate_plugin('pagelayer/pagelayer.php');
		
		if ( is_null($installed)) {
			$installed = true;
			echo '<div id="message" class="updated"><p>'. sprintf(__('Done! Pagelayer is now installed and activated.  Please click <a href="%s">here</a> to import your themes content', 'popularfx'), esc_url(admin_url('admin.php?page=pagelayer_import'))). '</p></div><br />';
			echo '<br><br><b>'.__('Done! Pagelayer is now installed and activated', 'popularfx').'.</b>';
		}
	}
	
	return $installed;
	
}

// Prevent pro activate text for installer
function popularfx_install_pagelayer_complete_actions($install_actions, $api, $plugin_file){
	
	if($plugin_file == 'pagelayer-pro/pagelayer-pro.php'){
		return array();
	}
	
	if($plugin_file == 'pagelayer/pagelayer.php'){
		return array();
	}
	
	return $install_actions;
	
}

}

if(!function_exists('popularfx_admin_menu')){

// This adds the left menu in WordPress Admin page
add_action('admin_menu', 'popularfx_admin_menu', 5);
function popularfx_admin_menu() {
	
	if(defined('PFX_FILE')){
		return;
	}
	
	$capability = 'edit_theme_options';// TODO : Capability for accessing this page

	add_theme_page('PopularFX', __('PopularFX Options', 'popularfx'), $capability, 'popularfx', 'popularfx_page_handler');

	/*// Add the menu page
	add_menu_page('PopularFX', 'PopularFX', $capability, 'popularfx', 'popularfx_page_handler', POPULARFX_URL.'/images/popularfx-logo-menu.png');

	// Options Page
	add_submenu_page('popularfx', 'PopularFX', __('Information', 'popularfx'), $capability, 'popularfx', 'popularfx_page_handler');
	
	// PopularFX Templates
	add_submenu_page('popularfx', __('Website Templates', 'popularfx'), __('Website Templates', 'popularfx'), $capability, 'popularfx_templates', 'popularfx_page_templates');*/

}

}

// Install Templates part
if(!function_exists('popularfx_page_templates')){
	
function popularfx_page_templates() {

	include_once(dirname(__FILE__).'/popularfx-templates.php');
	
	popularfx_templates();

}

}

if(!function_exists('popularfx_page_handler')){
	
function popularfx_page_handler() {

	include_once(dirname(__FILE__).'/popularfx-dashboard.php');
	
	popularfx_dashboard();

}

}

////////////////
// Some vars
////////////////
$popularfx['www_url'] = esc_url('https://popularfx.com');
$popularfx['support_url'] = esc_url('https://popularfx.deskuss.com');
$popularfx['slug'] = popularfx_get_current_theme_slug();

// Show the theme import notice if not shown
if(file_exists(dirname(dirname(__FILE__)).'/pagelayer.conf')){
	
	if(!function_exists('pagelayer_theme_import_notices')){
		//add_action('admin_notices', 'popularfx_pagelayer_required');
	}else{
		add_action('admin_notices', 'pagelayer_theme_import_notices');
	}
	
}