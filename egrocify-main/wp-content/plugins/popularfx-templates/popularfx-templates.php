<?php
/*
Plugin Name: PopularFX Website Templates
Plugin URI: https://popularfx.com/
Description: PopularFX is a lightweight theme with 500+ templates to make beautiful websites with Pagelayer. PopularFX can be used for a blogging site, WooCommerce Store, Business website, personal portfolio, etc.
Version: 1.2.4
Author: Pagelayer Team
Author URI: https://pagelayer.com/
Text Domain: popularfx
*/

// We need the ABSPATH
if (!defined('ABSPATH')) exit;

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

define('PFX_FILE', __FILE__);
define('PFX_BASE', plugin_basename(PFX_FILE));
define('PFX_PRO_BASE', 'popularfx-templates/popularfx-templates.php');
define('PFX_VERSION', '1.2.4');
define('PFX_DIR', dirname(PFX_FILE));
define('PFX_SLUG', 'popularfx-templates');
define('PFX_URL', plugins_url('', PFX_FILE));
define('PFX_CSS', PFX_URL.'/css');
define('PFX_JS', PFX_URL.'/js');
define('PFX_PRO_URL', 'https://popularfx.com/pricing?from=pfx-plugin');
define('PFX_WWW_URL', 'https://popularfx.com/');
define('PFX_DOCS', 'https://popularfx.com/docs/');
define('PFX_API', 'https://a.softaculous.com/popularfx/');
define('PFX_PAGELAYER_API', 'https://api.pagelayer.com/');

// Ok so we are now ready to go
register_activation_hook(PFX_FILE, 'pfx_activation');

// Is called when the ADMIN enables the plugin
function pfx_activation(){

	global $wpdb;

	$sql = array();

	add_option('popularfx_version', PFX_VERSION);

}

// Checks if we are to update ?
function pfx_update_check(){

global $wpdb;

	$sql = array();
	$current_version = get_option('popularfx_version');
	$version = (int) str_replace('.', '', $current_version);

	// No update required
	if($current_version == PFX_VERSION){
		return true;
	}

	// Is it first run ?
	if(empty($current_version)){

		// Reinstall
		pfx_activation();

		// Trick the following if conditions to not run
		$version = (int) str_replace('.', '', PFX_VERSION);

	}

	// Save the new Version
	update_option('popularfx_version', PFX_VERSION);

	// Bug fix prior to 108 - We can only attempt this once
	if($version <= 108 && get_option('template') == 'popularfx'){
		
		$template = get_theme_mod('popularfx_template');
		$template_dir = get_template_directory().'/templates/'.$template;
		$style = $template_dir.'/style.css';
		$dest = pfx_templates_dir().'/'.$template;
		
		if(!empty($template) && file_exists($style)){
		
			if(!function_exists( 'download_url' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			
			define('FS_METHOD', 'direct');
			WP_Filesystem();
			
			// Create the dir if missing
			wp_mkdir_p($dest);
			
			// Just copy the dir
			copy_dir($template_dir, $dest);
			
			if(file_exists($dest.'/style.css')){
				pfx_fix_image_urls_108();
			}
		
		}
		
	}

}

// Add the action to load the plugin 
add_action('plugins_loaded', 'pfx_load_plugin');

// The function that will be called when the plugin is loaded
function pfx_load_plugin(){

	global $pagelayer, $popularfx;

	// Check if the installed version is outdated
	pfx_update_check();
	
	// Load license
	pfx_load_license();
	
	// Load the language
	load_plugin_textdomain('popularfx', false, PFX_SLUG.'/languages/');
	
	// Check for updates
	include_once(PFX_DIR.'/plugin-update-checker.php');
	$popularfx_updater = PopularFX_PucFactory::buildUpdateChecker(PFX_API.'update2.php?version='.PFX_VERSION, PFX_FILE);
	
	// Add the license key to query arguments
	$popularfx_updater->addQueryArgFilter('pfx_updater_filter_args');
	
	// Show the text to install the license key
	add_filter('puc_manual_final_check_link-pagelayer-pro', 'pfx_updater_check_link', 10, 1);

	// Template Installation related ajax calls
	add_action('wp_ajax_popularfx_template_info', 'pfx_templates_ajax');
	add_action('wp_ajax_popularfx_start_install_template', 'pfx_templates_ajax');
	add_action('wp_ajax_popularfx_download_template', 'pfx_templates_ajax');
	add_action('wp_ajax_popularfx_import_template', 'pfx_templates_ajax');

	// Load the freemium widgets
	if(!defined('PAGELAYER_PREMIUM')){
		add_action('pagelayer_load_custom_widgets', 'pfx_freemium_shortcodes');
	}
	
	// Are we to setup a template ?
	$slug = get_option('popularfx_setup_template');
	if(!empty($slug)){
		add_action('after_setup_theme', 'pfx_setup_template_import');
	}

}

// Setup import
function pfx_setup_template_import(){
	
	$slug = get_option('popularfx_setup_template');
	
	// We dont have to setup anything
	if(empty($slug)){
		return;
	}
	
	// Setup the theme credit
	update_option('pagelayer-copyright', pagelayer_get_option('pagelayer-copyright').' | '.popularfx_copyright());
	
	// Set that we have setup
	delete_option('popularfx_setup_template');
	
	include_once(dirname(__FILE__).'/templates.php');
	
	$_POST['delete_old_import'] = 1;
	$_POST['set_home_page'] = 1;
	$data = popularfx_import_template($slug);
	if(!empty($_GET['install-pfx-template'])){		
		popularfx_ajax_output($data);
	}else{
		wp_redirect(home_url());
	}	
	exit();
}

// Load the freemium widgets
function pfx_freemium_shortcodes(){
	
	if(defined('PAGELAYER_PREMIUM')){
		return;
	}
	
	include_once(dirname(__FILE__).'/freemium_functions.php');
	include_once(dirname(__FILE__).'/freemium.php');
}

// Add our license key if ANY
function pfx_updater_filter_args($queryArgs) {
	
	global $popularfx;
	
	if ( !empty($popularfx['license']['license']) ) {
		$queryArgs['license'] = $popularfx['license']['license'];
	}
	
	return $queryArgs;
}

// Handle the Check for update link and ask to install license key
function pfx_updater_check_link($final_link){
	
	global $popularfx;
	
	if(empty($popularfx['license']['license'])){
		return '<a href="'.admin_url('admin.php?page=popularfx').'">Install PopularFX Pro License Key</a>';
	}
	
	return $final_link;
}

// This adds the left menu in WordPress Admin page
add_action('admin_menu', 'pfx_admin_menu', 5);
function pfx_admin_menu() {

	$capability = 'edit_theme_options';// TODO : Capability for accessing this page

	// Add the menu page
	add_menu_page(__('PopularFX'), __('PopularFX'), $capability, 'popularfx', 'pfx_page_handler', PFX_URL.'/images/popularfx-logo-menu.png');

	// Options Page
	add_submenu_page('popularfx', __('PopularFX'), __('Options'), $capability, 'popularfx', 'pfx_page_handler');
	
	// PopularFX Templates
	add_submenu_page('popularfx', __('Website Templates'), __('Website Templates'), $capability, 'popularfx_templates', 'pfx_page_templates');
	
	// Manually Import Template 
	//add_submenu_page('popularfx', __('Manual Import'), __('Manual Import'), $capability, 'popularfx_manual_import', 'pfx_manual_import');

	if(!function_exists('pagelayer_theme_import_notices')){
		add_submenu_page('popularfx', 'Install Pagelayer', 'Install Pagelayer', 'install_plugins', 'popularfx_install_pagelayer', 'pfx_install_pagelayer');
	}

}
	
function pfx_install_pagelayer(){
	
	global $pagelayer;
	
	if(!empty($_GET['license'])){
		return pfx_install_pagelayer_pro();
	}
	
	// Include the necessary stuff
	include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	// Includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
	include_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/misc.php' );
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	// Filter to prevent the activate text
	add_filter('install_plugin_complete_actions', 'pfx_install_pagelayer_complete_actions', 10, 3);
	
	echo '<h2>Install Pagelayer Free Version</h2>';

	$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin(  ) );
	$installed = $upgrader->install('https://downloads.wordpress.org/plugin/pagelayer.zip');
	
	if(is_wp_error( $installed ) || empty($installed)){
		return $installed;
	}
	
	if ( !is_wp_error( $installed ) && $installed ) {
		echo 'Activating Pagelayer Plugin !';
		$installed = activate_plugin('pagelayer/pagelayer.php');
		
		if ( is_null($installed)) {
			$installed = true;
			echo '<div id="message" class="updated"><p>'. sprintf(__('Done! Pagelayer is now installed and activated.  Please click <a href="%s">here</a> to import your themes content', 'popularfx'), admin_url('admin.php?page=pagelayer_import')). '</p></div><br />';
			echo '<br><br><b>Done! Pagelayer is now installed and activated.</b>';
		}
	}
	
	return $installed;
	
}

// Install Pagelayer Pro	
function pfx_install_pagelayer_pro($license = ''){
	
	global $pagelayer;
	
	$license = empty($license) ? $_GET['license'] : $license;
	
	// Include the necessary stuff
	include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	// Includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
	include_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/misc.php' );
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	// Filter to prevent the activate text
	add_filter('install_plugin_complete_actions', 'pfx_install_pagelayer_complete_actions', 10, 3);
	
	echo '<h2>Install Pagelayer Pro</h2>';

	$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin(  ) );
	$installed = $upgrader->install(PFX_PAGELAYER_API.'download.php?version=latest&license='.$license);
	
	if(is_wp_error( $installed ) || empty($installed)){
		return $installed;
	}
	
	if ( !is_wp_error( $installed ) && $installed ) {
		echo 'Activating Pagelayer Pro !';
		$installed = activate_plugin('pagelayer-pro/pagelayer-pro.php');
		
		if ( is_null($installed)) {
			$installed = true;
			echo '<div id="message" class="updated"><p>'. sprintf(__('Done! Pagelayer Pro is now installed and activated.  Please click <a href="%s">here</a> to import your themes content', 'popularfx'), admin_url('admin.php?page=pagelayer_import')). '</p></div><br />';
			echo '<br><br><b>Done! Pagelayer Pro is now installed and activated.</b>';
		}
	}
	
	return $installed;
	
}

// Prevent pro activate text for installer
function pfx_install_pagelayer_complete_actions($install_actions, $api, $plugin_file){
	
	if($plugin_file == 'pagelayer-pro/pagelayer-pro.php'){
		return array();
	}
	
	if($plugin_file == 'pagelayer/pagelayer.php'){
		return array();
	}
	
	return $install_actions;
}

// Show the message to install pagelayer Pro
function pfx_pagelayer_required(){
	
	if($_REQUEST['page'] == 'popularfx_install_pagelayer'){
		return;
	}
	
	echo '
<div class="notice notice-warning">
	<p style="font-size:13px">You need the <a href="'.PFX_WWW_URL.'" target="_blank"><b>Pagelayer Editor</b></a> to run this theme and also import its content ! Please enter your PopularFX / Pagelayer Pro License key and click on <b>Install</b></p>
	<p style="font-size:13px">
		<input type="text" id="pfx_license_key" placeholder="PFX-XXXXX-XXXXX-XXXXX-XXXXX" size="40" /> <button class="button button-primary" onclick="popularfx_install_pagelayer()">Install</button>
	</p>
</div>

<script>
function pfx_install_pagelayer(){
	var url = "'.admin_url('admin.php?page=popularfx_install_pagelayer&license=').'"+jQuery("#pfx_license_key").val();
	window.location = url;
}
</script>';
	
}
	
function pfx_page_templates() {

	include_once(dirname(__FILE__).'/templates.php');
	
	popularfx_templates();

}

function pfx_templates_ajax() {

	include_once(dirname(__FILE__).'/templates.php');
	
	if($_GET['action'] == 'popularfx_template_info'){
		popularfx_ajax_template_info();
	}
	
	if($_GET['action'] == 'popularfx_start_install_template'){
		popularfx_ajax_start_install_template();
	}
	
	if($_GET['action'] == 'popularfx_download_template'){
		popularfx_ajax_download_template();
	}
	
	if($_GET['action'] == 'popularfx_import_template'){
		popularfx_ajax_import_template();
	}

}
	
function pfx_page_handler() {

	include_once(dirname(__FILE__).'/license.php');
	
	popularfx_license();

}

// Default Templates for Blog
function pfx_create_blog_template($template_name){
	
	global $pagelayer;
	
	$file = pfx_cleanpath(get_theme_root());
	
	// Do we have the blog template ?
	if(file_exists($file.'/blog-template.pgl')){
		return;
	}
	
	$data['blog-template'] = '[pl_row pagelayer-id="ffbgB5e4xPIruUJC" stretch="auto" col_gap="10" width_content="auto" row_height="default" overlay_hover_delay="400" row_shape_top_color="#227bc3" row_shape_top_width="100" row_shape_top_height="100" row_shape_bottom_color="#e44993" row_shape_bottom_width="100" row_shape_bottom_height="100"]
[pl_col pagelayer-id="aF6cze85x0CVnb4I" overlay_hover_delay="400"]
[pl_archive_title pagelayer-id="a6sL2H8c5FJDwHmL" align="left" typo=",,,,,,Solid,,,," ele_margin="0px,0px,18px,0px" font_size="28"]
[/pl_archive_title]
[pl_archive_posts pagelayer-id="CrFuxlpqwrKx1cok" type="default" columns="3" columns_mobile="1" col_gap="20" row_gap="40" data_padding="5,5,5,5" bg_color="#ffffff" show_thumb="true" show_title="true" meta="author,date,comments" meta_sep="|" show_content="excerpt" content_color="#121212" content_align="left" pagination="number_prev_next" thumb_size="medium_large" ratio="0.7" title_color="#0986c0" title_typo=",18,,,,,solid,,,," exc_length="10" pagi_prev_text="Previous" pagi_next_text="Next" pagi_end_size="1" pagi_mid_size="2" pagi_align="center"]
[/pl_archive_posts]
[/pl_col]
[/pl_row]';

	$data['single-template'] = '[pl_row pagelayer-id="TeNMIn3gRsvsyDZj" stretch="auto" col_gap="10" width_content="auto" row_height="default" overlay_hover_delay="400" row_shape_top_color="#227bc3" row_shape_top_width="100" row_shape_top_height="100" row_shape_bottom_color="#e44993" row_shape_bottom_width="100" row_shape_bottom_height="100"]
[pl_col pagelayer-id="qyP2XV3ClSd9cEWM" overlay_hover_delay="400"]
[pl_post_title pagelayer-id="nNt87422AXwZoBQg" title_color="" typo=",35,,700,,,solid,,,," align="center"]
[/pl_post_title]
[/pl_col]
[/pl_row]
[pl_row pagelayer-id="6UuOjtSrBDhWOnWG" stretch="auto" col_gap="10" width_content="fixed" row_height="default" overlay_hover_delay="400" row_shape_top_color="#227bc3" row_shape_top_width="100" row_shape_top_height="100" row_shape_bottom_color="#e44993" row_shape_bottom_width="100" row_shape_bottom_height="100" row_width="70%" fixed_width="70%" fixed_width_tablet="85%" fixed_width_mobile="100%"]
[pl_col pagelayer-id="gzGSF2JVwcPcNUk6" overlay_hover_delay="400" col_width="80" col="12"]
[pl_post_info pagelayer-id="gBDuE9nYBu0bIHyv" layout="vertical" space_between="15" align="center" icon_colors="normal" text_colors="normal"]
[pl_post_info_list pagelayer-id="ZMZjpaTiEc9Ien3t" type="author" info_link="true" info_icon_on="true" info_icon="fas fa-user-circle"]
[/pl_post_info_list]
[pl_post_info_list pagelayer-id="xI8gpn9VRfPDkZ0Q" type="date" info_link="true" info_icon_on="true" info_icon="fas fa-calendar-alt" date_format="default"]
[/pl_post_info_list]
[pl_post_info_list pagelayer-id="LaEZYd9SjEnQHsg3" type="time" info_link="true" info_icon_on="true" info_icon="fas fa-clock" time_format="default"]
[/pl_post_info_list]
[pl_post_info_list pagelayer-id="9lTHiEQJQqESt6YG" type="comments" info_link="true" info_icon_on="true" info_icon="fas fa-comment"]
[/pl_post_info_list]
[/pl_post_info]
[pl_post_excerpt pagelayer-id="NklzzZGW3ve1X8BS" ele_margin="15px,0px,15px,0px" align="left"]
[/pl_post_excerpt]
[pl_featured_img pagelayer-id="sZLiICVhGCbBTx1a" size="full" img_filter="0,100,100,0,0,100,100" caption_color="#0986c0" img_hover_delay="400" custom_size="70%,0%" align="center"]
[/pl_featured_img]
[pl_post_content pagelayer-id="7JbkxQEvq0skyUUl" ele_margin="35px,0px,35px,0px" font_size="NaN"]
[/pl_post_content]
[pl_post_info pagelayer-id="tvno5FCIKdwGa8IE" layout="horizontal" space_between="5" align="left" icon_colors="normal" text_colors="normal" input_typo=",,,,,,Solid,,,,"]
[pl_post_info_list pagelayer-id="SwoZ4cxl3XFMLE3l" type="terms" info_link="true" info_icon_on="" info_icon="fas fa-user-circle" taxonomy="category" info_before="Category :"]
[/pl_post_info_list]
[pl_post_info_list pagelayer-id="sgTqNx5LkBHODkrG" type="terms" info_link="true" info_icon_on="" info_icon="fas fa-user-circle" taxonomy="post_tag" info_before="Tags :"]
[/pl_post_info_list]
[/pl_post_info]
[/pl_col]
[/pl_row]
[pl_row pagelayer-id="heO1UxRj8lIQZ52M" stretch="auto" col_gap="10" width_content="auto" row_height="default" overlay_hover_delay="400" row_shape_top_color="#227bc3" row_shape_top_width="100" row_shape_top_height="100" row_shape_bottom_color="#e44993" row_shape_bottom_width="100" row_shape_bottom_height="100"]
[pl_col pagelayer-id="s3sgObVllcHz0CB7" overlay_hover_delay="400"]
[pl_post_nav pagelayer-id="RrRky7duRa9KGmsA" lables="true" post_title="true" arrows="true" sep_color="#bdbdbd" sep_rotate="20" sep_width="5" prev_label="Previous" next_label="Next" label_colors="normal" title_colors="normal" arrows_list="angle" icon_colors="normal"]
[/pl_post_nav]
[/pl_col]
[/pl_row]
[pl_row pagelayer-id="duGtpLrwHkOWbE0m" stretch="auto" col_gap="10" width_content="auto" row_height="default" overlay_hover_delay="400" row_shape_top_color="#227bc3" row_shape_top_width="100" row_shape_top_height="100" row_shape_bottom_color="#e44993" row_shape_bottom_width="100" row_shape_bottom_height="100"]
[pl_col pagelayer-id="ad58IjV6dHjcRBmV" overlay_hover_delay="400"]
[pl_post_comment pagelayer-id="bwueyBxPgdNLC1Ec" comment_skin="theme_comment" post_type="current"]
[/pl_post_comment]
[/pl_col]
[/pl_row]';

	$conf = '{
		"single-template": {
			"type": "single",
			"title": "Single Template",
			"conditions": [
				{
					"type": "include",
					"template": "singular",
					"sub_template": "post",
					"id": ""
				},
				{
					"type": "include",
					"template": "singular",
					"sub_template": "attachment",
					"id": ""
				}
			]
		},
		"blog-template": {
			"type": "archive",
			"title": "Blog Template",
			"conditions": [
				{
					"type": "include",
					"template": "archives",
					"sub_template": "",
					"id": ""
				}
			]
		}
		
	}';
	
	$pgl = json_decode($conf, true);
	
	// Loop the default template
	foreach($pgl as $k => $v){
		
		$new_post = array();
	
		// Is the page there ?
		$template = get_page_by_path($k, OBJECT, $pagelayer->builder['name']);
		
		// It does exist so save the revision IF its the header and footer
		if(!empty($template)){
			
			$rev = wp_save_post_revision($template->ID);
			
			// Did we save the rev ?
			//if(empty($rev)){
				// TODO : Throw error
			//}
			
			$new_post['ID'] = $template->ID;
			
		}
		
		// Make an array
		$new_post['post_content'] = $data[$k];
		$new_post['post_title'] = $v['title'];
		$new_post['post_name'] = $k;
		$new_post['post_type'] = $pagelayer->builder['name'];
		$new_post['post_status'] = 'publish';
		$new_post['comment_status'] = 'closed';
		$new_post['ping_status'] = 'closed';
		//pagelayer_print($new_post);die();
		
		// Now insert / update the post
		$ret = pagelayer_insert_content($new_post, $err);
		$post_id = $ret;
		
		// Did we save the rev ?
		if(empty($ret)){
			die('Could not update the Pagelayer Template '.$k);
		}
		
		// Save our template type
		update_post_meta($post_id, 'pagelayer_template_type', $v['type']);
		update_post_meta($post_id, 'pagelayer_template_conditions', $v['conditions']);
		
		update_post_meta($post_id, 'pagelayer_imported_content', $template_name);
	}
	
}

// Load license data
function pfx_load_license(){
	
	global $popularfx, $pagelayer;

	// Load license
	$popularfx['license'] = get_option('popularfx_license');
	
	if(empty($popularfx['license'])){
		return;
	}
	
	// Update license details as well
	if(empty($popularfx['license']['last_update']) || 
		(!empty($popularfx['license']['last_update']) && (time() - $popularfx['license']['last_update']) >= 86400)
	){
		
		$resp = wp_remote_get(pfx_api_url().'license.php?license='.$popularfx['license']['license']);
		
		// Did we get a response ?
		if(is_array($resp)){
			
			$tosave = json_decode($resp['body'], true);
			
			// Is it the license ?
			if(!empty($tosave['license'])){
				$tosave['last_update'] = time();
				update_option('popularfx_license', $tosave);
			}
			
		}
		
	}
	
	// Add the same license to Pagelayer if we have Pagelayer Pro with an unlicensed value
	if(function_exists('pagelayer_theme_import_notices') && empty($pagelayer->license['status']) && !empty($popularfx['license']['status'])){
		$pagelayer->license = $popularfx['license'];
	}
	
}
	
function pfx_sp_api_url(){
	
	global $popularfx;
	
	return str_replace('/popularfx', '/sitepad', pfx_api_url());
	
}
	
function pfx_api_url(){
	
	global $popularfx;
	
	$r = array(
		'https://s1.softaculous.com/a/popularfx/',
		'https://s2.softaculous.com/a/popularfx/',
		'https://s3.softaculous.com/a/popularfx/',
		'https://s4.softaculous.com/a/popularfx/',
		'https://s5.softaculous.com/a/popularfx/'
	);
	
	$mirror = $r[array_rand($r)];
	
	// If the license is newly issued, we need to fetch from API only
	if(empty($popularfx['license']['last_edit']) || 
		(!empty($popularfx['license']['last_edit']) && (time() - 1800) < $popularfx['license']['last_edit'])
	){
		$mirror = 'https://a.softaculous.com/popularfx/';
	}
	
	return $mirror;
	
}

// Add settings link on plugin page
add_filter('plugin_action_links_popularfx-templates/popularfx-templates.php', 'pfx_plugin_action_links');
function pfx_plugin_action_links($links){
	
	global $popularfx;
	
	if(empty($popularfx['license']['status'])){
		 $links[] = '<a href="'.PFX_PRO_URL.'" style="color:#3db634;" target="_blank">'._x('Go Pro', 'Upgrade to PopularFX Pro for many more features', 'popularfx').'</a>';
	}

	$settings_link = '<a href="admin.php?page=popularfx">Settings</a>';	
	array_unshift($links, $settings_link); 
	
	return $links;
}

// Check if a field is posted via POST else return default value
function pfx_optpost($name, $default = ''){

	if(!empty($_POST[$name])){
		return pfx_inputsec(pfx_htmlizer(trim($_POST[$name])));
	}

	return $default;
}



function pfx_inputsec($string){

	$string = addslashes($string);

	// This is to replace ` which can cause the command to be executed in exec()
	$string = str_replace('`', '\`', $string);

	return $string;

}

function pfx_htmlizer($string){

	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');

	preg_match_all('/(&amp;#(\d{1,7}|x[0-9a-fA-F]{1,6});)/', $string, $matches);//r_print($matches);

	foreach($matches[1] as $mk => $mv){
		$tmp_m = pfx_entity_check($matches[2][$mk]);
		$string = str_replace($matches[1][$mk], $tmp_m, $string);
	}

	return $string;

}

function pfx_entity_check($string){

	//Convert Hexadecimal to Decimal
	$num = ((substr($string, 0, 1) === 'x') ? hexdec(substr($string, 1)) : (int) $string);

	//Squares and Spaces - return nothing 
	$string = (($num > 0x10FFFF || ($num >= 0xD800 && $num <= 0xDFFF) || $num < 0x20) ? '' : '&#'.$num.';');

	return $string;

}
	
function pfx_cleanpath($path){
	$path = str_replace('\\', '/', $path);
	$path = str_replace('//', '/', $path);
	return rtrim($path, '/');
}

function pfx_templates_dir(){
	$dir = wp_upload_dir(NULL, false);
	return $dir['basedir'].'/popularfx-templates';
}

function pfx_templates_dir_url(){
	$dir = wp_upload_dir(NULL, false);
	return $dir['baseurl'].'/popularfx-templates';
}

// Fix the image URLs for versions prior to 108
function pfx_fix_image_urls_108(){
	
	$template = get_theme_mod('popularfx_template');
		 
	// Get list of pages and pagelayer templates to edit
	$args = array(
		'post_type' => ['page', 'pagelayer-template'],
	);
	
	$query = new WP_Query($args);

	foreach($query->posts as $k => $v){
		$v->post_content = str_replace(get_stylesheet_directory_uri().'/templates/'.$template.'/images/', pfx_templates_dir_url().'/'.$template.'/images/', $v->post_content);
		
		// For fixing wwww.abc.com which may be abc.com only
		if(preg_match('/'.preg_quote('/wp-content/themes/popularfx/templates/'.$template.'/images/', '/').'/is', $v->post_content)){
		
			$v->post_content = str_replace('/wp-content/themes/popularfx/templates/'.$template.'/images/', '/wp-content/uploads/popularfx-templates/'.$template.'/images/', $v->post_content);
		
		}
		
		wp_update_post($v);
	}
	
}

add_action('wp_loaded', 'pfx_woo_fix');
function pfx_woo_fix(){
	if(class_exists( 'woocommerce' ) && function_exists('pagelayer_template_product_fix')){
		pagelayer_template_product_fix();
	}
}