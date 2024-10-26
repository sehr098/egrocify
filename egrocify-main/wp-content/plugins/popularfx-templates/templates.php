<?php

function popularfx_ajax_output($data){
	
	echo json_encode($data);
	
	wp_die();
	
}

function popularfx_ajax_output_xmlwrap($data){
	
	echo '<popularfx-xmlwrap>'.json_encode($data).'</popularfx-xmlwrap>';
	
	wp_die();
}

function popularfx_import_template($slug, $items = array()){
	
	$data = [];
	
	$destination = pfx_templates_dir().'/'.$slug;
	
	include_once(PAGELAYER_DIR.'/main/import.php');
	
	// Our function needs to efficiently replace the variables
	$GLOBALS['popularfx_template_import_slug'] = $slug;	
	add_filter('pagelayer_start_insert_content', 'popularfx_pagelayer_start_insert_content', 10);
	
	// Full import
	if(empty($items)){
	
		// Now import the template
		if(!pagelayer_import_theme($slug, $destination)){
			$data['error']['import_err'] = __('Could not import the template !', 'popularfx');
			$data['error'] = array_merge($data['error'], $pl_error);
			return $data;
		}
		
		// Save the name of the slug
		set_theme_mod('popularfx_template', $slug);
	
	// Single items
	}else{
	
		// Now import the SINGLE ITEMS
		if(!pagelayer_import_single($slug, $items, $destination)){
			$data['error']['import_err'] = __('Could not import the single item !', 'popularfx');
			$data['error'] = array_merge($data['error'], $pl_error);
			return $data;
		}
	
	}
	
	$data['done'] = 1;
	
	return $data;
	
}

// Download the template
function popularfx_download_template($slug){
	
	global $popularfx, $pl_error;	

	set_time_limit(300);
	
	$data = [];
		
	// Now lets download the templates
	if(!function_exists( 'download_url' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	
	$url = pfx_api_url().'/givetemplate.php?slug='.$slug.'&license='.@$popularfx['license']['license'];
	//echo $url;
	
	$tmp_file = download_url($url);
	//echo filesize($tmp_file);
	//var_dump($tmp_file);
	
	// Error downloading
	if(is_wp_error($tmp_file) || filesize($tmp_file) < 1){
		if(!empty($tmp_file->errors)){			
			$data['error']['download_err'] = __('Could not download the theme !', 'popularfx').var_export($tmp_file->errors, true);
			return $data;
		}
	}
	
	$destination = pfx_templates_dir().'/'.$slug;
	@mkdir($destination, 0755, true);
	//echo $destination;
	
	define('FS_METHOD', 'direct');
	WP_Filesystem();
	$ret = unzip_file($tmp_file, $destination);
	//r_print($ret);
	
	// Try to delete
	@unlink($tmp_file);
	
	// Error downloading
	if(is_wp_error($ret) || !file_exists($destination.'/style.css')){
		if(!empty($ret->errors)){
			$data['error']['download'] = __('Could not extract the template !', 'popularfx').var_export($ret->errors, true);
			return $data;
		}
	}
	
	return $data;
	
}

// Get list of templates
function popularfx_get_templates_list(){
	
	$data = get_transient('popularfx_templates');

	// Get any existing copy of our transient data
	if(false === $data){
	
		// Start checking for an update
		$send_for_check = array(
			'timeout' => 90,
			'user-agent' => 'WordPress'		
		);
		
		$raw_response = wp_remote_post( pfx_api_url().'templates.json', $send_for_check );
		//pagelayer_print($raw_response);die();
	
		// Is the response valid ?
		if ( !is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ){		
			$data = json_decode($raw_response['body'], true);
		}
		//pagelayer_print($data);die();
	
		// Feed the updated data into the transient
		if(!empty($data['list']) && count($data['list']) > 10){
			set_transient('popularfx_templates', $data, 2 * HOUR_IN_SECONDS);
		}
		
	}
	
	return $data;
	
}

// Get the template info from our servers
function popularfx_ajax_template_info(){

	// Some AJAX security
	check_ajax_referer('popularfx_ajax', 'popularfx_nonce');
	
	$data = [];
	
	if(isset($_REQUEST['slug'])){		
		$resp = wp_remote_get(pfx_api_url().'template-info.php?slug='.$_REQUEST['slug'], array('timeout' => 30));
	
		// Is the response valid ?
		if ( !is_wp_error( $resp ) && ( $resp['response']['code'] == 200 ) ){		
			$data = json_decode($resp['body'], true);
		}
	}	
	
	popularfx_ajax_output($data);
	
}

// Start the installation of the template
function popularfx_ajax_start_install_template(){
	
	global $popularfx;
	
	// Some AJAX security
	check_ajax_referer('popularfx_ajax', 'popularfx_nonce');

	set_time_limit(300);
	
	$data = [];
	
	//pagelayer_print($_POST);die();
	$license = pfx_optpost('popularfx_license');
	
	// Check if its a valid license
	if(!empty($license)){
	
		$resp = wp_remote_get(pfx_api_url().'license.php?license='.$license, array('timeout' => 30));
	
		if(is_array($resp)){
			$json = json_decode($resp['body'], true);
			//print_r($json);
		}else{
		
			$data['error']['resp_invalid'] = __('The response from PopularFX servers was malformed. Please try again in sometime !', 'popularfx').var_export($resp, true);
			popularfx_ajax_output($data);
			
		}
	
		// Save the License
		if(empty($json['license'])){
		
			$data['error']['lic_invalid'] = __('The license key is invalid', 'popularfx');
			popularfx_ajax_output($data);
			
		}else{
			
			update_option('popularfx_license', $json);
	
			// Load license
			pfx_load_license();
			
		}
		
	}
	
	// Load templates
	$popularfx['templates'] = popularfx_get_templates_list();
	
	$slug = pfx_optpost('theme');
	
	if(!defined('PAGELAYER_VERSION')){
		$data['error']['pl_req'] = __('Pagelayer is required to run these templates !', 'popularfx');
		popularfx_ajax_output($data);
	}
	
	$single = pfx_optpost('single');
	if(!empty($single) && version_compare(PAGELAYER_VERSION, '1.3.2', '<')){
		$data['error']['pl_single_import'] = __('You need Pagelayer 1.3.2 to import single pages', 'popularfx');
		popularfx_ajax_output($data);
	}
	
	// See if the theme is valid
	if(empty($popularfx['templates']['list'][$slug])){
		$data['error']['template_invalid'] = __('The template you submitted is invalid !', 'popularfx');
		popularfx_ajax_output($data);
	}
	
	$template = $popularfx['templates']['list'][$slug];
	
	// Do we have the req PL version ?
	if(!empty($template['pl_ver']) && version_compare(PAGELAYER_VERSION, $template['pl_ver'], '<')){
		$data['error']['pl_ver'] = __('Your Pagelayer version is '.PAGELAYER_VERSION.' while the template requires '.$template['pl_ver'], 'popularfx');
		popularfx_ajax_output($data);
	}
	
	// Do we have the req PFX Plugin version ?
	if(!empty($template['pfx_ver']) && version_compare(PFX_VERSION, $template['pfx_ver'], '<')){
		$data['error']['pfx_ver'] = __('Your PopularFX Plugin version is '.PFX_VERSION.' while the template requires '.$template['pfx_ver'], 'popularfx');
		popularfx_ajax_output($data);
	}
	
	// Is it a pro template ?
	if($template['type'] > 1 && empty($popularfx['license']['status'])){
		$data['error']['template_pro'] = sprintf(__('The selected template is a Pro template and you have a free or expired license. Please purchase the PopularFX Pro license from <a href="%s" target="_blank">here</a>.', 'popularfx'), PFX_PRO_URL);
		popularfx_ajax_output($data);
	}
	
	$do_we_have_pro = defined('PAGELAYER_PREMIUM');
	
	// Do we need to install Pagelayer or Pagelayer PRO ?
	if(!function_exists('pagelayer_theme_import_notices') || (empty($do_we_have_pro) && $template['type'] > 1)){
		if($template['type'] > 1){
			$installed = pfx_install_pagelayer_pro(@$popularfx['license']['license']);
		}else{
			$installed = popularfx_install_pagelayer();
		}
		
		// Did we fail to install ?
		if(is_wp_error($installed) || empty($installed)){
			$install_url = admin_url('admin.php?page=popularfx_install_pagelayer&license=').@$popularfx['license']['license'];
			$data['error']['pagelayer'] = sprintf(__('There was an error in installing Pagelayer which is required by this template. Please install Pagelayer manually by clicking <a href="%s" target="_blank">here</a> and then install the template !', 'popularfx'), $install_url);
			if(!empty($installed->errors)){
				$data['error']['pagelayer_logs'] = var_export($installed->errors, true);
			}
			popularfx_ajax_output_xmlwrap($data);
		}
		
	}
	
	// Lets notify to download
	$data['download'] = 1;
	
	popularfx_ajax_output_xmlwrap($data);
	
}

// Download template
function popularfx_ajax_download_template(){
	
	global $popularfx;
	
	// Some AJAX security
	check_ajax_referer('popularfx_ajax', 'popularfx_nonce');
	
	$slug = pfx_optpost('theme');
	
	// Do the download
	$data = popularfx_download_template($slug);
	
	// Any error ?
	if(!empty($data['error'])){
		popularfx_ajax_output($data);
	}
	
	// Lets import then
	$data['import'] = 1;
	
	popularfx_ajax_output($data);
	
}

// Import template
function popularfx_ajax_import_template(){
	
	global $popularfx, $pl_error;

	// Some AJAX security
	check_ajax_referer('popularfx_ajax', 'popularfx_nonce');
	
	$slug = pfx_optpost('theme');
	$single = pfx_optpost('single');
	$items = !empty($single) ? ['page' => [$single]] : [];
	
	// Import the template
	$data = popularfx_import_template($slug, $items);
	
	popularfx_ajax_output($data);
	
}

// This is to replace the image variables for the template URL
function popularfx_pagelayer_start_insert_content($post){
	
	$url = pfx_templates_dir_url().'/'.$GLOBALS['popularfx_template_import_slug'].'/';
	
	$replacers['{{theme_url}}/images/'] = $url.'images/';
	$replacers['{{theme_url}}'] = $url;
	$replacers['{{theme_images}}'] = $url.'images/';
	$replacers['{{themes_dir}}'] = dirname(get_stylesheet_directory_uri());
	
	foreach($replacers as $key => $val){
		$post['post_content'] = str_replace($key, $val, $post['post_content']);
	}
		
	return $post;
	
}

if(!function_exists('popularfx_templates')){

// The Templates Page
function popularfx_templates(){

	global $popularfx, $pl_error;
	
	$popularfx['templates'] = popularfx_get_templates_list();
	
	if(isset($_REQUEST['install'])){
		check_admin_referer('popularfx-template');
	}

	// Is there a license key ?
	if(isset($_POST['install'])){
		
		$done = 1;
		
	}
	
	popularfx_templates_T();
	
}

// The License Page - THEME
function popularfx_templates_T(){
	
	global $popularfx, $pagelayer, $pl_error;
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}
	
?>

</div><!--TODO : REMOVE and rectify CSS -->

<div id="popularfx_search" class="popularfx-row">
	<div class="popularfx-logo">
		<a href="<?php echo PFX_WWW_URL;?>" target="_blank"><img src="<?php echo PFX_URL.'/images/popularfx-logo.png';?>" width="35" /></a>
	</div>
	<div class="popularfx-back">&lt;</div>
	<div class="popularfx-dropdown popularfx-categories">
		<div class="popularfx-current-cat">All</div><span class="popularfx-down">&#8964;</span>
		<div class="popularfx-dropdown-content"><div class="popularfx-cat-holder popularfx-row"></div></div>
	</div>
	<div class="popularfx-search">
		<span class="dashicons dashicons-search"></span><input class="popularfx-search-field" /><span class="popularfx-sf-empty dashicons dashicons-no-alt"></span>
		<div id="popularfx-suggestion"></div>
	</div>
	<div class="popularfx-sort">
		<label>Sort By :</label>
		<select id="popularfx-sortby" class="popularfx-sortby">
			<option value="default">--</option>
			<option value="latest">Latest</option>
			<option value="oldest">Oldest</option>
		</select>
	</div>
</div>
<div class="popularfx-page" id="popularfx-templates-holder">
	<div id="popularfx-pagination"></div>
	<div id="popularfx-templates" class="popularfx-row"></div>
	<div id="popularfx-single-template">

		<div style="margin-bottom: 20px; margin-top: 10px;">
			<h1 style="display: inline-block;margin: 0px;vertical-align: middle;" id="popularfx-template-name"></h1>
			<a href="" id="popularfx-demo" class="button popularfx-demo-btn" target="_blank">Demo</a>
		</div>
		<div style="margin: 0px; vertical-align: top;">
			<div style="width: 52%; display: inline-block; text-align: center;">
				<div style="width: 100%; max-height: 400px; overflow: auto; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
					<img id="popularfx_display_image" src="" width="100%">
				</div>
			</div>
			<div id="popularfx_screenshots" style="width: 45%; display: inline-block; padding: 0px 10px; vertical-align: top;"></div>
		</div>

		<div style="position:fixed; bottom: 50px; right: 30px;">
			<input name="import_theme" class="button button-popularfx" value="Import Theme Content" type="button" onclick="popularfx_modal('#popularfxModal')" /> &nbsp;
			<input name="import_single" id="pfx_import_single" class="button button-popularfx-single hidden" value="Import Single Page" type="button" onclick="popularfx_modal('#popularfxModal', true)" />
		</div>	
	
	</div>
</div>
<?php if(!defined('PAGELAYER_VERSION')){
	?>
	<div id="popularfx_notification" class="popularfx-row">
	<div class="popularfx-notification-div">
		<div class="popularfx-notification-text">PopularFX templates requires <a href="<?php echo admin_url('plugin-install.php?s=pagelayer&tab=search&type=term')?>" target="_blank">Pagelayer Builder</a> to be installed & activated !</div>
	</div>
</div>
<?php
}
?>
<style>

.popularfx-page{
position: relative;
top: 25px;
width: 100%;
margin-bottom: 80px;
}

.popularfx-logo{
margin-right: 20px;
}

.popularfx-back{
border: 1px solid #fff;
border-radius : 2px;
font-weight: bold;
margin-right: 20px;
padding: 10px;
line-height: 100%;
cursor: pointer;
}

.popularfx-search{
text-align:center;
margin: 0px;
position:relative;
min-width: 375px;
margin-right:20px;
}

.popularfx-search-field{
width:100%;
line-height:120%;
padding: 5px 20px 5px 30px;
border-radius: 3px;
border: none;
font-size: 14px;
height:100%;
}

.popularfx-search .dashicons{
position: absolute;
top: 0;
padding: 0 7px;
color: #666;
line-height: 40px;
}

.popularfx-search .popularfx-sf-empty{
right:0;
left:auto;
font-weight:bolder;
cursor:pointer;
}

#popularfx-suggestion {
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 420px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
padding: 12px 16px;
z-index: 1;
color: #111;
top: 38px;
left: -1px;
}

#popularfx-single-template{
display:none;
}

#popularfx-pagination{
float: right;
margin-right: 20px;
}

#popularfx-pagination ul li{
display: inline-block;
padding: 4px 12px;
border-top: 1px solid #358cb7;
border-bottom: 1px solid #358cb7;
border-left: 1px solid #358cb7;
background: #fff;
}

#popularfx-pagination ul li:last-child{
border-right: 1px solid #358cb7;
border-top-right-radius: 4px;
border-bottom-right-radius: 4px;
}

#popularfx-pagination ul li:first-child{
border-top-left-radius: 4px;
border-bottom-left-radius: 4px;
}

#popularfx-pagination ul li.active{
background: #358cb7;
}

#popularfx-pagination ul li.active a{
color: #fff;
}

#popularfx-pagination ul li a{
text-decoration: none;
}

#popularfx_search{
position: fixed;
top: 32px;
margin-left: -20px;
width: 100%;
z-index: 10000;
background: #466d8e;
color: #FFF;
box-shadow: 0 2px 10px 0 #2b2b2b6b;
padding: 15px;
}
#popularfx_notification{
position: fixed;
bottom: 0px;
margin-left: -20px;
width: 100%;
z-index: 10000;
background: #F90716;
color: #FFF;
padding: 10px;
}
.popularfx-notification-div{
width: 90%;
}
.popularfx-notification-text{
text-align: center;
}
.popularfx-notification-text a{
color: #ffffff;
font-weight: bold;
}
.popularfx-categories{
padding: 10px;
border: 1px solid #fff;
border-radius : 2px;
min-width: 150px;
margin-right: 20px;
}

.popularfx-cat{
padding: 7px 10px;
box-sizing: border-box;
cursor: pointer;
}

.popularfx-down{
position: absolute;
left: auto;
right: 0;
right: 10px;
top: 6px;
}

.popularfx-dropdown {
position: relative;
display: inline-block;
}

.popularfx-dropdown-content {
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 420px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
padding: 12px 16px;
z-index: 1;
color: #111;
top: 38px;
left: -1px;
}

.popularfx-dropdown:hover .popularfx-dropdown-content {
display: block;
}

.popularfx-row{
box-sizing: border-box;
display: flex;
flex: 1 0 auto;
flex-direction: row;
flex-wrap: wrap;
width:100%;
align-content: stretch;
position: relative;
}

.popularfx-md-4{
width:25%;
}

.popularfx-theme-details{
margin-right:20px;
margin-bottom:40px;
transition: all 0.4s;
border-radius: 2px;
border: 1px solid #ccc;
cursor: pointer;
}

.popularfx-theme-details:hover{
margin-top: -2px;
box-shadow: 0 2px 40px 0 rgba(0, 0, 0, 0.1), 0 3px 20px 0 rgba(0, 0, 0, 0.1);
}

.popularfx-theme-screenshot img{
max-width:100%;
}

.popularfx-theme-screenshot{
position:relative;
}

.popularfx-premium-themes{
position: absolute;
right: -10px;
top: 10px;
font-size: 14px;
background: red;
color: #fff;
padding: 5px 12px;
border-radius: 3px;
z-index: 1000;
font-weight: bold;
}

.popularfx-theme-name{
background: #fff;
padding: 15px;
font-size: 14px;
font-weight: 600;
}

.popularfx_img_screen{
width: 120px;
margin: 0px 15px 10px 15px;
display: inline-block;
border: 1px solid transparent;
border-radius: 3px;
cursor: pointer;
}

.popularfx_img_selected{
border: 1px solid #1A9CDB;
}

.popularfx_img_div{
overflow: hidden;
height: 160px;
}

.popularfx_img_name{
text-align: center;
background: #fff;
padding: 5px 10px;
border-top: 1px solid #ccc;
text-transform: capitalize;
}

#popularfx-demo{
display: inline-block;
vertical-align: middle;
margin-left: 40px;
}

.popularfx-demo-btn{
padding: 2px 25px !important;
font-size: 15px !important;
font-weight: bold;
background: #4590d2 !important;
color: #fff !important;
border: 1px solid #4590d2 !important;
transition: all .3s linear;
}

.popularfx-demo-btn:hover{
background: #fff !important;
color: #4590d2 !important;
}

.button-popularfx{
padding: 12px 25px !important;
font-size: 15px !important;
font-weight: bold;
background: #7444fd !important;
color: #fff !important;
border: 1px solid #7444fd !important;
transition: all .3s linear;
cursor: pointer;
}

.button-popularfx:hover{
background: #fff !important;
color: #7444fd !important;
}

.button-popularfx-single{
padding: 12px 25px !important;
font-size: 15px !important;
font-weight: bold;
background: #4590d2 !important;
color: #fff !important;
border: 1px solid #4590d2 !important;
transition: all .3s linear;
cursor: pointer;
}

.button-popularfx-single:hover{
background: #fff !important;
color: #4590d2 !important;
}

/* The Modal (background) */
.popularfx-modal {
display: none;
position: fixed;
z-index: 10000;
left: 0;
top: 0;
width: 100%;
height: 100%;
overflow: auto;
background-color: rgb(0,0,0);
background-color: rgba(0,0,0,0.4);
}

/* Modal Content/Box */
.popularfx-modal-holder {
background-color: #fefefe;
margin: 15% auto; /* 15% from the top and centered */
border: 1px solid #888;
width: 50%;
min-height: 175px;
position: relative;
}

/* The Close Button */
.popularfx-modal-close {
color: #aaa;
float: right;
font-size: 28px;
font-weight: bold;
}

.popularfx-modal-close:hover,
.popularfx-modal-close:focus {
color: black;
text-decoration: none;
cursor: pointer;
}

.popularfx-modal-header{
max-height: 80px;
top: 0px;
border-bottom: 1px solid #ccc;
}

.popularfx-modal-footer{
max-height: 80px;
bottom: 0px;
border-top: 1px solid #ccc;
text-align: right;
}

.popularfx-modal-header,
.popularfx-modal-content,
.popularfx-modal-footer{
padding: 15px;
width: 100%;
box-sizing: border-box;
}

#popularfx-import-form>div{
padding: 4px;
font-weight: 600;
}

.popularfx-exp{
font-size: 12px;
}

#popularfx_license_div{
margin: 20px 60px 10px 60px;
display: none;
}

#popularfx-error-template{
display: none;
background: #f7dbdb;
padding: 1px 10px;
margin-bottom: 15px;
}

#popularfx-progress-template{
display: none;
background: #dcf1f9;
padding: 10px;
margin-bottom: 15px;
}

#popularfx-progress-template img{
vertical-align: middle;
margin-right: 10px;
}

#wpbody div#setting-error-tgmpa, #wpbody .update-nag, #wpbody .notice, #wpbody div.error {
display: none;
}

#wpfooter{
position: relative;
}

.pfx-sub-head,
.pfx-content{
margin: 5px 0px;
}

#popularfx-sortby{
height:100%;	
}

</style>

<!-- The Modal -->
<div id="popularfxModal" class="popularfx-modal">

	<!-- Modal holder -->
	<div class="popularfx-modal-holder">

		<!-- Modal header -->
		<div class="popularfx-modal-header">
			<b>Import Theme Contents</b> <span class="popularfx-modal-close">&times;</span>
		</div>
		
		<!-- Modal content -->
		<div class="popularfx-modal-content">
			<div class="popularfx-import">
				<div id="popularfx-error-template"></div>
				<div id="popularfx-progress-template">
					<img src="<?php echo PFX_URL;?>/images/progress.svg" width="20" /> <span id="popularfx-progress-txt"></span>
				</div>
			
				<form id="popularfx-import-form" method="post" enctype="multipart/form-data">
					<?php wp_nonce_field('popularfx-template');?>
					<input name="theme" id="popularfx-template-install" value="" type="hidden" />
					<div class="pfx-single"><input type="checkbox" name="save_as_draft" /> Save as Draft</div>
					<div class="pfx-full"><input type="checkbox" name="no_header_menu" /> Do not create Header Menu</div>
					<div class="pfx-full"><input type="checkbox" name="delete_old_import" /> Delete Previously Imported Content</div>
					<div><input type="checkbox" name="overwrite" /> Overwrite existing Page(s) with same name</div>
					<div class="pfx-full"><input type="checkbox" name="set_home_page" checked /> Set the Home Page as per the content</div>
					<hr/>
					<div class="pfx-full">
						<h2 class="pfx-sub-head">Image Copyright</h2>
						<p>We try our best to use images that are free from legal perspectives. However, we do not take any responsibility for the same. Do you want to use the demo images with this theme ?</p>
						<ul class="pfx-content">
							<li><input type="checkbox" name="download_imgs" value="1"/> If you click here, then the images will be downloaded from their respective sources.<br/></li>
							<li>By default it will use placeholder images which are distributed with this theme and can be replaced easily.</li>
						</ul>						
					</div>
					
					<input type="hidden" name="single" value="" />
					<?php
					
					if(empty($popularfx['license'])){
						echo '<div id="popularfx_license_div">License Key : <input type="text" id="popularfx_license_key" name="popularfx_license" placeholder="PFX-XXXXX-XXXXX-XXXXX-XXXXX" size="40" /><br>
						<span class="popularfx-exp">This is a Pro Template and you will need to enter your <a href="'.PFX_PRO_URL.'">PopularFX License</a> to install this !</span>
						</div>';
					}
					
					?>
					<input name="install" value="1" type="hidden" />
				</form>
			</div>
			<div class="popularfx-done" style="display: block;">
				<h3 style="margin-top: 0px;">Congratulations, the template was imported successfully !</h3>
				You can now customize the website as per your requirements with the help of Pagelayer or the Customizer.<br><br>
				<b>Note</b> : We strongly recommend you change all images and media. We try our best to use images which are copyright free or are allowed under their licensing. However, we take no responsibilities for the same and recommend you change all media and images !
			</div>
		</div>
		
		<!-- Modal footer -->
		<div class="popularfx-modal-footer">
			<div class="popularfx-done">
				<a class="button popularfx-demo-btn" href="<?php echo site_url();?>" target="_blank">Visit Website</a>
			</div>
			<div class="popularfx-import">
				<button class="button button-primary" onclick="return popularfx_start_install_template()">Import</button> &nbsp;
				<button class="button popularfx-cancel">Cancel</button>
			</div>
		</div>
	</div>

</div>

<script>

function popularfx_modal(sel, single){
	
	var modal = jQuery(sel);
	single = single || false;
	
	modal.show();
	
	modal.find('.popularfx-done').hide();
	modal.find('.popularfx-import').show();
	
	if(single){
		var page_name = jQuery('.popularfx_img_selected').attr('page-name');
		modal.find('[name=single]').val(page_name);
		modal.find('.pfx-full').hide();
		modal.find('.pfx-single').show();
		modal.find('[name=save_as_draft]').attr('checked', 'checked');
	}else{
		modal.find('[name=single]').val('');
		modal.find('.pfx-full').show();
		modal.find('.pfx-single').hide();
	}

	// Get the <span> element that closes the modal
	var span = modal.find(".popularfx-modal-close, .popularfx-cancel");

	// When the user clicks on <span> (x), close the modal
	span.on("click", function() {
		modal.hide();
	});

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if(event.target == modal[0]){
			modal.hide();
		}
	}
}

// Show any errors
function popularfx_show_error(err){
	
	var html = '<ul>';
	
	for(var x in err){
		html += '<li>'+err[x]+'</li>';
	}
	
	html += '</ul>';
	
	jQuery('#popularfx-error-template').html(html).show();
	jQuery('#popularfx-progress-template').hide();
	
}

// Start install
function popularfx_start_install_template(){
	
	jQuery('#popularfx-error-template').hide();
	jQuery('#popularfx-progress-template').show();
	jQuery('#popularfx-progress-txt').html('Checking the requirements ...');
	
	// Make the call
	jQuery.ajax({
		url: popularfx_ajax_url+'&action=popularfx_start_install_template',
		type: 'POST',
		data: jQuery('#popularfx-import-form').serialize()+'&popularfx_nonce='+popularfx_ajax_nonce,
		success: function(data){
			
			// Install plugin gives too much output, hence match the data
			var res = data.match(/<popularfx\-xmlwrap>(.*?)<\/popularfx\-xmlwrap>/is);
			if(res){
				data = res[1];
			}
			
			data = JSON.parse(data);
			
			popularfx_download_template(data);
		},
		error: function(jqXHR, textStatus, errorThrown){
			popularfx_show_error({err: 'AJAX failure ! Status : '+textStatus+' | Error : '+errorThrown});
		}
	});
	
	return false;
	
}

// Download template
function popularfx_download_template(data){
	
	if('error' in data){
		popularfx_show_error(data['error']);
		return false;
	}
	
	jQuery('#popularfx-progress-txt').html('Downloading the template ...');
	
	// Make the call
	jQuery.ajax({
		url: popularfx_ajax_url+'&action=popularfx_download_template',
		type: 'POST',
		data: jQuery('#popularfx-import-form').serialize()+'&popularfx_nonce='+popularfx_ajax_nonce,
		dataType: 'json',
		success: popularfx_import_template,
		error: function(jqXHR, textStatus, errorThrown){
			popularfx_show_error({err: 'AJAX failure ! Status : '+textStatus+' | Error : '+errorThrown});
		}
	});
	
}

// Import template
function popularfx_import_template(data){
	
	if('error' in data){
		popularfx_show_error(data['error']);
		return false;
	}
	
	jQuery('#popularfx-progress-txt').html('Importing the template ...');
	
	// Make the call
	jQuery.ajax({
		url: popularfx_ajax_url+'&action=popularfx_import_template',
		type: 'POST',
		data: jQuery('#popularfx-import-form').serialize()+'&popularfx_nonce='+popularfx_ajax_nonce,
		dataType: 'json',
		success: function(data){
			//console.log(data);
			var modal = jQuery('#popularfxModal');
			
			if('done' in data){				
				modal.find('.popularfx-done').show();
				modal.find('.popularfx-import').hide();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			popularfx_show_error({err: 'AJAX failure ! Status : '+textStatus+' | Error : '+errorThrown});
		}
	});
	
}

// Add to tabs override
jQuery(document).ready(function(){
	popularfx_templates_fn(jQuery);
});

var popularfx_ajax_nonce = '<?php echo wp_create_nonce('popularfx_ajax');?>';
var popularfx_ajax_url = '<?php echo admin_url( 'admin-ajax.php' );?>?&';
var popularfx_demo = 'https://demos.popularfx.com/';

function popularfx_templates_fn($){

popularfx_templates = <?php echo json_encode($popularfx['templates']);?>;
var themes = popularfx_templates['list'];
var categories = popularfx_templates['categories'];
var mirror = '<?php echo pfx_sp_api_url();?>files/themes/';

// Back button handler
$('.popularfx-back').click(function(){
	show_themes();
});

// Fill the categories
var chtml = '<div class="popularfx-md-4 popularfx-cat" data-cat="">All</div>';
for(var x in categories){
	chtml += '<div class="popularfx-md-4 popularfx-cat" data-cat="'+x+'">'+categories[x]['en']+'</div>';
}

$('.popularfx-cat-holder').html(chtml);
$('.popularfx-cat-holder').find('.popularfx-cat').click(function(){
	show_themes($(this).data('cat'));
});

// Show the themes
function show_themes(cat, search, page){
	
	var sortby = $('.popularfx-sortby').val();
	
	$("#popularfx-suggestion").hide();	
	$("#popularfx-single-template").hide();
	$("#popularfx-pagination").show();
	$("#popularfx-templates").show();
	
	// Blank html
	$('#popularfx-templates').html('');
	$('#popularfx-pagination').html('');
	
	var search = search || "";
	var cat = cat || "";
	var num = 60;
	var page = page || 1;
	var start = num * (page - 1);
	var end = num + start;
	var i = 0;
	
	if(cat.length > 0){
		$('.popularfx-current-cat').html(categories[cat]['en']);
		$('.popularfx-current-cat').data('cat', cat);
	}else{
		$('.popularfx-current-cat').html('All');
		$('.popularfx-current-cat').data('cat', '');
	}
	
	var allowed_list = [];
	
	if(search.length > 0){
		search = search.toLowerCase();
		
		for(var x in popularfx_templates['tags']){
			if(x.toLowerCase().indexOf(search) >= 0){
				allowed_list = allowed_list.concat(popularfx_templates['tags'][x]);
			}
		}
	}
	
	if(allowed_list.length > 0){
		allowed_list = Array.from(new Set(allowed_list));
	}
	
	var themeids = [];
	var sorted = {};
	var rsorted = {};
	
	for(var x in themes){
		themeids.push(parseInt(themes[x].thid));
	}	
	
	if(sortby == "latest"){
		var datatheme = Object.values(themes);
		var rsorted_ids = themeids.sort().reverse();
		for(var x of rsorted_ids){
			for( var y in datatheme){
				if(datatheme[y].thid == x){
					rsorted[datatheme[y].slug] = datatheme[y];
				}
			}
		}
		themes = rsorted;
	
	}else if(sortby == "oldest"){
		var datatheme = Object.values(themes);
		var sorted_ids = themeids.sort();
		for(var x of sorted_ids){
			for( var y in datatheme){
				if(datatheme[y].thid == x){
					sorted[datatheme[y].slug] = datatheme[y];
				}
			}
		}
		
		themes = sorted;
		
	}else{
		themes = popularfx_templates['list'];		
	}	
	
	for(var x in themes){
		
		// Is it same category
		if(cat.length > 0 && cat != themes[x].category){
			continue;
		}
		
		// Is it a searched item
		if(search.length > 0 && themes[x].name.toLowerCase().indexOf(search) === -1 && allowed_list.indexOf(themes[x].thid) === -1){
			continue;
		}
		
		if(i >= start && i < end){
			//console.log(x+' '+i+' '+start+' '+end);
			show_theme_tile(themes[x], x);
		}
		
		i++;
		
	}
	
	$('.popularfx-theme-details').click(function(){
		var jEle = $(this);
		show_theme_details(jEle.attr('slug'));
	});
	
	var pages = Math.ceil(i/num);
	
	if(pages > 1){
		
		var html = '<ul class="pagination">';
		
		for(var p = 1; p <= pages; p++){
			html += '<li class="page-item '+(page == p ? 'active' : '')+'"><a class="page-link" href="#" data-cat="'+cat+'" data-search="'+search+'" data-page="'+p+'">'+p+'</a></li>';
		}
		
		html += '</ul>';
		
		$('#popularfx-pagination').html(html);
		
		$('#popularfx-pagination').find('.page-link').click(function(){
			var j = $(this);
			show_themes(j.data('cat'), j.data('search'), j.data('page'));
		});
		
	}
	
}

function show_theme_tile(theme, x){
	var html = '<div class="popularfx-md-4">'+
		'<div class="popularfx-theme-details" slug="'+theme['slug']+'" thid="'+theme['thid']+'">'+
			'<div class="popularfx-theme-screenshot">'+
				(theme['type'] != 1 ? '<div class="popularfx-premium-themes">Pro</div>' : '')+
				'<img src="'+mirror+'/'+theme['slug']+'/screenshot.jpg" loading="lazy" alt="" />'+
			'</div>'+
			'<div class="popularfx-theme-name">'+theme['name']+'</div>'+
		'</div>'+
	'</div>';
	$('#popularfx-templates').append(html);
}

function strip_extension(str) {
    return str.substr(0,str.lastIndexOf('.'));
}

// Show the theme details
function show_theme_details(slug){
	
	var theme = themes[slug];
	
	$("#popularfx-suggestion").hide();	
	$("#popularfx-single-template").show();
	$("#popularfx-pagination").hide();
	$("#popularfx-templates").hide();
	
	// Set install value
	$('#popularfx-template-install').val(slug);
			
	// Set name
	$("#popularfx-template-name").html(theme['name']);
			
	// Demo URL
	$("#popularfx-demo").attr("href", popularfx_demo+(theme['name'].replace(' ', '_')));
	
	// Blank screenshots
	$("#popularfx_screenshots").html('');
	
	// Is the license PRO ?
	if(theme['type'] >= 2){
		$('#popularfx_license_div').css('display', 'inline-block');
	}else{
		$('#popularfx_license_div').hide();
	}
	
	var url = mirror+'/'+theme['slug'];
	
	// Show home image
	$("#popularfx_display_image").attr("src", "");
	$("#popularfx_display_image").attr("src", url+'/screenshots/home.jpg');
	$("#popularfx_display_image").parent().scrollTop(0);
	
	// Make the call
	jQuery.ajax({
		url: popularfx_ajax_url+'&action=popularfx_template_info',
		type: 'POST',
		data: {
			popularfx_nonce: popularfx_ajax_nonce,
			slug: slug
		},
		dataType: 'json',
		success:function(theme) {
			
			$("#pfx_import_single").addClass("hidden");
			
			var sc = '';
			
			// Show the screenshots
			for(var x in theme['screenshots']){
				var page_name = strip_extension(theme['screenshots'][x]);
				sc += '<div class="popularfx_img_screen" page="'+x+'" page-name="'+page_name+'">'+
					'<div class="popularfx_img_div"><img src="'+url+'/screenshots/'+theme['screenshots'][x]+'" width="100%" /></div>'+
					'<div class="popularfx_img_name">'+page_name+'</div>'+
				'</div>'
			}
			
			$("#popularfx_screenshots").html(sc);
			
			$("#popularfx_screenshots").find('.popularfx_img_screen').click(function(){
				jEle = $(this);
				$("#popularfx_display_image").attr("src", jEle.find("img").attr("src"));
				$("#popularfx_display_image").parent().scrollTop(0);
				
				$("#pfx_import_single").removeClass("hidden");
				$(".popularfx_img_screen").removeClass("popularfx_img_selected");
				jEle.addClass("popularfx_img_selected");
			});
			
		}
	});
	
}

// Search Clear
$('.popularfx-sf-empty').click(function(){
	$('.popularfx-search-field').val('');
	show_themes();
});

// Seach
$('.popularfx-search-field').on('keyup', function(e){
	show_themes('', $(this).val());
});

// Sort themes
$('.popularfx-sortby').change(function(){
	show_themes($('.popularfx-current-cat').data('cat'), $('.popularfx-search-field').val());
});

/*var timer = null;

// Search for a theme
function suggest_theme(e, a) {
	
	clearTimeout(timer);

	// check value only after user stops typing
	timer = setTimeout(function () {
		if (e.keyCode == 8) {
			if (!a) {
				$("#popularfx-suggestion").hide();
			}
		}

		if (a) {
			a = a.split(" ");//split if has space
			show_searched_theme(a); //search for theme
		}

	}, 200);

}

function show_searched_theme(val) {
	
	var data = new Array();
	var arr = new Array();
	var vale = val.join(" "); // join split file
	var vale_slug = val.join(""); //join for slug
	vale_slug = vale_slug.toLowerCase();
	vale = vale.toLowerCase();
	
	//val = jQuery.trim(val);
	for (var x in themes) {

		var slug = themes[x].slug;
		//var tags = themes[x].tags;

		//search by theme name
		if ((themes[x].name.substring(0, vale.length) === vale)) {
			data.push(themes[x].slug);
			arr.push(themes[x].name);
		}

		//search by slug
		if ((themes[x].slug.substring(0, vale_slug.length) === vale_slug)) {
			data.push(themes[x].slug);
			arr.push(themes[x].name);
		}
	}

	// Search by tags
	//for (var z in popularfx_templates.tags) {
	//	var tag = z.toLowerCase();
	//	if (tag.lastIndexOf(vale, 0) === 0) {
	//		if (data.indexOf(themes[x].slug) == -1 && arr.indexOf(themes[x].name) == -1) {
	//			data.push(themes[x].slug);
	//			arr.push(themes[x].name);
	//		}
	//	}
	//}

	var txt = "";			// from here add value for search suggestion
	txt = '<ul id="list-suggestion" style="padding: 0px;margin-bottom:0 !important">';
	if (!jQuery.isEmptyObject(data)) {
		for (i in data) {
			txt += '<a style="text-decoration: none;cursor:pointer;"><li>' + arr[i] + '</li></a>';
		}
	} else {
		txt += '<a class="inliner" href="javascript:void(0);"><li value="no-suggestion">No themes found with this search criteria</li></a>';
	}
	txt += '</ul>';
	
	$("#popularfx-suggestion").html(txt);
	$("#popularfx-suggestion").show();
	
	$("#popularfx-suggestion a").click(function(){
		
	});
	
}*/

show_themes();

};

</script>

<?php

}

}