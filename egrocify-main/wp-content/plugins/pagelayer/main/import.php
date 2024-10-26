<?php

//////////////////////////////////////////////////////////////
//===========================================================
// template_import.php
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

include_once(PAGELAYER_DIR.'/main/settings.php');

function pagelayer_import(){
	
	global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
		
	$pagelayer_theme = wp_get_theme();
	$pagelayer_theme_url = get_stylesheet_directory_uri();
	$pagelayer_theme_path = get_stylesheet_directory();
	
	// Get the pages
	$pagelayer_templates = @json_decode(file_get_contents($pagelayer_theme_path.'/pagelayer.conf'), true);
	$pagelayer_pages = @json_decode(file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf'), true);
	
	if(isset($_POST['theme'])){
		check_admin_referer('pagelayer-import');
		$GLOBALS['pl_saved'] = pagelayer_import_theme($pagelayer_theme->template);
	}
	
	// Have we already imported ?
	$imported = get_option('pagelayer_theme_'.get_template().'_imported');
	if(!empty($imported)){
		$GLOBALS['pl_warn'] = __('You have already imported the content of this theme. You can re-import the same by either choosing to over-write existing pages / pagelayer templates OR creating duplicate content !', 'pagelayer');
	}
	
	// Call the theme
	pagelayer_import_T();
	
}

function pagelayer_import_T(){
	
	global $pagelayer, $pagelayer_theme, $pagelayer_theme_url, $pagelayer_theme_path, $pagelayer_pages, $pl_error;
	
	pagelayer_page_header('Pagelayer - Import Template');
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div class="notice notice-success"><p>'. __('The theme content was successfully imported', 'pagelayer'). '</p></div>';

	// Warn ?
	}elseif(!empty($GLOBALS['pl_warn'])){
		echo '<div class="notice notice-warning"><p>'.$GLOBALS['pl_warn'].'</p></div>';
	}
	
	// Is it a pagelayer theme ?
	if(!file_exists($pagelayer_theme_path.'/pagelayer.conf')){
		echo 'This utility is for importing content of the current active theme if its a Pagelayer Theme. Your current theme is <b>not</b> a Pagelayer exported theme ! If you want to export your content and make it into a distributable theme, please refer to the guide <a href="">here</a>.';
		die();
	}
	
	// Home screenshot
	$screenshot = $pagelayer_theme_url.'/screenshots/home.jpg';	
	if(!file_exists($pagelayer_theme_path.'/screenshots/home.jpg')){
		$screenshot = PAGELAYER_URL.'/images/no_screenshot.png';
	}
	
	echo '
<style>
.pagelayer_img_screen{
width: 120px;
margin: 0px 15px 10px 15px;
display: inline-block;
border: 1px solid transparent;
border-radius: 3px;
}

.pagelayer_img_selected{
border: 1px solid #1A9CDB;
}

.pagelayer_img_div{
overflow: hidden;
height: 160px;
}

.pagelayer_img_name{
text-align: center;
background: #fff;
padding: 5px 10px;
border-top: 1px solid #ccc;
}

/* The Modal (background) */
.pagelayer-modal {
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
.pagelayer-modal-holder {
background-color: #fefefe;
margin: 15% auto; /* 15% from the top and centered */
border: 1px solid #888;
width: 50%;
min-height: 200px;
position: relative;
}

/* The Close Button */
.pagelayer-modal-close {
color: #aaa;
float: right;
font-size: 28px;
font-weight: bold;
}

.pagelayer-modal-close:hover,
.pagelayer-modal-close:focus {
color: black;
text-decoration: none;
cursor: pointer;
}

.pagelayer-modal-header{
max-height: 80px;
top: 0px;
border-bottom: 1px solid #ccc;
}

.pagelayer-modal-footer{
max-height: 80px;
bottom: 0px;
border-top: 1px solid #ccc;
text-align: right;
}

.pagelayer-modal-header,
.pagelayer-modal-content,
.pagelayer-modal-footer{
padding: 15px;
width: 100%;
box-sizing: border-box;
}

#pagelayer-import-form>div{
padding: 4px;
font-weight: 600;
}

</style>

<!-- The Modal -->
<div id="pagelayerModal" class="pagelayer-modal">

	<!-- Modal holder -->
	<div class="pagelayer-modal-holder">

		<!-- Modal header -->
		<div class="pagelayer-modal-header">
			<b>Import Theme Contents</b> <span class="pagelayer-modal-close">&times;</span>
		</div>
		
		<!-- Modal content -->
		<div class="pagelayer-modal-content">		
			<form id="pagelayer-import-form" method="post" enctype="multipart/form-data">';
				wp_nonce_field('pagelayer-import');
				echo '<input name="theme" value="'.get_template().'" type="hidden" />
				<div><input type="checkbox" name="no_header_menu" /> Do not create Header Menu</div>
				<div><input type="checkbox" name="delete_old_import" id="delete_old_import" /> Delete Previously Imported Content</div>
				<div><input type="checkbox" name="overwrite" /> Overwrite existing Pages with same name</div>
				<div><input type="checkbox" name="set_home_page" checked /> Set the Home Page as per the content</div>
				<div class="pagelayer-image-copyright">
					<h2 class="pagelayer-sub-head">Image Copyright</h2>
					<p>We try our best to use images that are free from legal perspectives. However, we do not take any responsibility for the same. Do you want to use the demo images with this theme ?</p>
					<ul class="pagelayer-content">
						<li><input type="checkbox" name="download_imgs" value="1"/> If you click here, then the images will be downloaded from their respective sources.<br/></li>
						<li>By default it will use placeholder images which are distributed with this theme and can be replaced easily.</li>
					</ul>						
				</div>
			</form>
		</div>
		
		<!-- Modal footer -->
		<div class="pagelayer-modal-footer">
			<button class="button button-primary" onclick="jQuery(\'#pagelayer-import-form\').submit()">Import</button> &nbsp;
			<button class="button pagelayer-cancel">Cancel</button>
		</div>
	</div>

</div>

<script>

function pagelayer_modal(sel){
	
	var modal = jQuery(sel);
	
	modal.show();

	// Get the <span> element that closes the modal
	var span = modal.find(".pagelayer-modal-close, .pagelayer-cancel");

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

jQuery(document).ready(function(){
	var $ = jQuery;

	var choose_image = function(jEle){		
		$("#pagelayer_display_image").attr("src", jEle.find("img").attr("src"));
		
		$(".pagelayer_img_screen").removeClass("pagelayer_img_selected");
		jEle.addClass("pagelayer_img_selected");
	}
	
	var first = $(".pagelayer_img_screen:first");
	var home = $(".pagelayer_img_screen[page=home]");
	
	if(home.length > 0){
		first = home;
	}
	
	choose_image(first);
	
	$(".pagelayer_img_screen").on("click", function(){
		choose_image($(this));
	});
	
	$("#pagelayer-import-form").on("submit", function(){
		
		if(!jQuery("#delete_old_import").is(":checked")){
			return true;
		}
		
		if(confirm("This will delete any pages / pagelayer templates imported earlier. Should we proceed ?")){
			return true;
		}else{
			return false;
		}
		
	});
	
});
</script>

<div><h1 style="margin-bottom: 10px; padding-top: 0px;">'.$pagelayer_theme->name.'</h1></div>
<div style="margin: 0px -10px; vertical-align: top;">
	<div style="width: 52%; display: inline-block; text-align: center;">
		<div style="width: 100%; max-height: 400px; overflow: auto; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<img id="pagelayer_display_image" src="'.$screenshot.'" width="100%">
		</div>
	</div>
	<div style="width: 45%; display: inline-block; padding: 0px 10px; vertical-align: top;">';
	$pages = (array) @$pagelayer_pages['page'];
	foreach( $pages as $k => $v){
		
		$screenshot = $pagelayer_theme_url.'/screenshots/'.$k.'.jpg';
		
		if(!file_exists($pagelayer_theme_path.'/screenshots/'.$k.'.jpg')){
			$screenshot = PAGELAYER_URL.'/images/no_screenshot.png';
		}
		
		echo '<div class="pagelayer_img_screen" page="'.$k.'">
			<div class="pagelayer_img_div"><img src="'.$screenshot.'" width="100%" /></div>
			<div class="pagelayer_img_name">'.$v['post_title'].'</div>
		</div>';
	}
	
	echo '</div>
</div>

<div style="position:fixed; bottom: 30px; right: 30px;">
	<input name="import_theme" class="button button-pagelayer" value="Import Theme Content" type="button" onclick="pagelayer_modal(\'#pagelayerModal\')" />
</div>';

add_filter('pagelayer_right_bar_promos', '__return_false');

pagelayer_page_footer(1);
	
}

// Imports the required conf
function pagelayer_import_conf(&$conf){
	
	foreach($conf as $k => $v){
		
		if(in_array($k, ['page_for_posts'])){
			continue;
		}
		
		update_option($k, $v);		
	}
	
}

// The actual function to import the theme
function pagelayer_import_single($template_name, $items, $pagelayer_theme_path = ''){
	
global $wpdb, $wp_rewrite;	
global $pagelayer, $pl_error;
	
	if(empty($pagelayer_theme_path)){
		$pagelayer_theme_path = get_stylesheet_directory();
	}
	
	if(empty($items)){
		$pl_error[] = 'Items were not submitted';
		return false;
	}
	
	/////////////////////////
	// Handle the PAGES Data
	/////////////////////////
	
	// Load the new themes pages array
	$data = file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf');
	$data = @json_decode($data, true);
	//r_print($data);die();
	
	if(empty($data['page'])){
		$pl_error[] = 'Pages list not found. This is not a proper template !';
		return false;
	}
	
	// Check the theme files
	foreach($data['page'] as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/page/'.$k);
		
		// Does it have the title and slug ?
		if(empty($v['post_title']) || empty($v['post_name'])){
			$pl_error[] = 'Something is fishy with this theme as there is no title or slug for '.$k;
			return false;
		}
		
		// Does the page exist ?
		if(!file_exists($path) || pagelayer_cleanpath(realpath($path)) != $path){
			$pl_error[] = 'Something is fishy with this theme';
			return false;
		}
		
	}
	
	$status = empty($_POST['save_as_draft']) ? 'publish' : 'draft';
	
	// Now check the pages if it exist in this installation ?
	foreach($data['page'] as $k => $v){
		
		if(!in_array($k, $items['page'])){
			continue;
		}
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/page/'.$k);
		
		// Is the page there ?
		$page = get_page_by_path($v['post_name'], OBJECT, array('page'));
		//r_print($page);
			
		$new_post = array();
		
		// It does exist so save the revision IF its the header and footer
		if(!empty($page) && isset($_POST['overwrite'])){
			
			$rev = wp_save_post_revision($page->ID);
			
			$new_post['ID'] = $page->ID;
			
		}
			
		// Make an array
		$new_post['post_content'] = file_get_contents($path);
		$new_post['post_title'] = $v['post_title'];
		$new_post['post_name'] = $v['post_name'];
		$new_post['post_type'] = 'page';
		$new_post['post_status'] = $status;			
		//r_print($new_post);die();
		
		// Now insert / update the post
		$ret = pagelayer_insert_content($new_post, $err);
		
		// Did we save the post ?
		if(empty($ret)){
			$pl_error[] = 'Could not update the page '.$v['post_name'];
			return false;
		}
		
		update_post_meta($ret, 'pagelayer_imported_content', $template_name);
		
	}
	
	//To import typography and breakpoint
	if(!empty($data['conf'])){		
		pagelayer_import_conf($data['conf']);
	}
	
	return true;
	
}

// The actual function to import the theme
function pagelayer_import_theme($template_name, $pagelayer_theme_path = ''){

global $wpdb, $wp_rewrite;
global $pagelayer, $pl_error, $sitepad;
	
	if(empty($pagelayer_theme_path)){
		$pagelayer_theme_path = get_stylesheet_directory();
	}
	//die($pagelayer_theme_path);
	
	// Delete Old Data ?
	if(isset($_POST['delete_old_import'])){
		$args = array(
			'post_type' => ['page', 'post', $pagelayer->builder['name']],
			'meta_query' => array(
				array(
					'key' => 'pagelayer_imported_content',
					'compare' => 'EXISTS'
				)
			)
		);
		$query = new WP_Query($args);

		foreach ( $query->posts as $p ) {
			//echo $p->ID.'<br>';
			wp_delete_post($p->ID);
		}
	}
	
	$pagelayer->import_links = [];
	
	/////////////////////////
	// Handle PAGELAYER DATA
	/////////////////////////
	
	// Load the PGL conf
	$pgl = file_get_contents($pagelayer_theme_path.'/pagelayer.conf');
	$pgl = @json_decode($pgl, true);
	
	if(empty($pgl['header'])){
		$pl_error[] = 'Header list not found. Report to Website Builder Team';
		return false;
	}
	
	// Load the new themes pages array
	$data = file_get_contents($pagelayer_theme_path.'/pagelayer-data.conf');
	$data = @json_decode($data, true);
	//r_print($data);die();
	
	if(empty($data['page'])){
		$pl_error[] = 'Pages list not found. This is not a proper template !';
		return false;
	}
	
	// Check the theme files
	foreach($pgl as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/'.$k.'.pgl');
		//print_r($path);
		
		// Does the page exist ?
		if(!file_exists($path) || (empty($GLOBALS['sitepad']['dev']) && pagelayer_cleanpath(realpath($path)) != $path)){
			$pl_error[] = 'Something is fishy with this theme as the template - '.$k.' - of type - '.$v['type'].' - was not found';
			return false;
		}
		
	}
	
	// Are we to add default templates ?
	if(empty($_POST['no_blog_templates'])){
		add_filter('pagelayer_importing_templates', 'pagelayer_blog_templates', 10, 1);
	}
	
	///////////////////////////
	// Lets import all MEDIA
	///////////////////////////
		
	// Now lets download the templates
	if(!function_exists( 'list_files' ) ) {
		require_once ABSPATH . PAGELAYER_CMS_DIR_PREFIX.'-admin/includes/file.php';
	}
	
		
	$_media = list_files($pagelayer_theme_path.'/images', 1);
	$imgs_json = array(); 
	//pagelayer_print($_media);die();
	
	if(file_exists($pagelayer_theme_path.'/images.json')){
		$imgs_json = @json_decode(file_get_contents($pagelayer_theme_path.'/images.json'), true);
	}
	
	// Download images
	if(!empty($_REQUEST['download_imgs'])){
		
		/* foreach($imgs_json as $k => $v){
			
			if(empty($v['download_url'])){
				continue;
			}
				
			$dest_dir = $pagelayer_theme_path.'/images';
			$dest_file = $dest_dir.'/'.$k;
			$image_file = $v['download_url'];
			
			// Compare image md5 
			if($v['md5'] != md5_file($image_file)){
				continue;
			}
			
			if(file_exists($dest_file)){
				$imagesize = getimagesize($dest_file);
				
				// Download and resize image
				$resize_file = pagelayer_resizeImage($v['download_url'], $imagesize[0], $imagesize[1]);
				if(!empty($resize_file)){
					$image_file = $resize_file;
				}
			}
			
			// Put image in file
			file_put_contents($dest_file, $image_file);
			
		} */
		
		// Update option to set no
		update_option('pagelayer_import_images_'.$template_name, 'yes');
		
	}elseif(empty($_REQUEST['download_imgs'])){ // && !file_exists($pagelayer_theme_path.'/images.json')
		foreach($_media as $k => $v){
			$imagesize = getimagesize($v);
			$mime = !empty($imagesize['mime']) ? $imagesize['mime'] : '';
			
			// Create blank image
			if(strpos($mime, "image/" ) !== false) {
				$blank_image = pagelayer_create_blank_image($imagesize[0], $imagesize[1]);
				file_put_contents($v, $blank_image);			
			}
		}

		// Update option to set no
		update_option('pagelayer_import_images_'.$template_name, 'no');		
	}
	
	foreach($_media as $k => $v){
		$file_name = basename($v);
		
		// We are going to create a loop to find the image
		for($i = 1; $i <= 3; $i++){
			
			// Upload the image
			$ret = pagelayer_upload_media($file_name, file_get_contents($v));
			
			// Lets check the file exists ?
			if(!empty($ret)){
				
				// Lets check if the file exists
				$tmp_image_path = pagelayer_cleanpath(get_attached_file($ret));
				
				// If the file does not exist, simply delete the old upload as well
				if(!file_exists($tmp_image_path)){
					wp_delete_attachment($ret, true);
					$ret = false;
				
				// The image does exist and we can continue
				}else{
					break;
				}
				
			}
		
		}
		
		if(!empty($ret)){
			$pagelayer->import_media['{{theme_url}}/images/'.$file_name] = $ret;
			
			if(isset($imgs_json[$file_name])){
				$fields = array('sitepad_img_source', 'sitepad_download_url', 'sitepad_img_lic');
				
				foreach($fields as $field){
					$_field = str_replace('sitepad_', '', $field);
					
					if(!empty($imgs_json[$file_name][$_field])){
						update_post_meta($ret, $field, $imgs_json[$file_name][$_field]);
					}
				}
			}
		}
	}	
	//r_print($pagelayer->import_media);die();
	
	// If we are to import default templates
	$pgl = apply_filters('pagelayer_importing_templates', $pgl);
	
	//////////////////////
	// Create Menus
	//////////////////////
	
	// Create the menu
	if(empty($_POST['no_header_menu'])){
		
		// Is there any MENU in this theme ?
		if(empty($data['menus'])){
			$menu_id = pagelayer_import_create_menu($template_name.' Header Menu');
		}else{
			
			foreach($data['menus'] as $k => $v){
				$new_id = pagelayer_import_create_menu($v['name']);
				$pagelayer->imported_menus[$v['term_id']] = $new_id;
				$pagelayer->imported_menus_slug[$new_id] = $k;
			}
			
			//r_print($pagelayer->imported_menus);die();
			
			$menu_id = current($pagelayer->imported_menus);
			
		}
		
	}else{
		
		// Get the first menu that has items if we still can't find a menu.
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu_maybe ) {
			$menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) );
			if ( $menu_items ) {
				$menu_id = $menu_maybe->term_id;
				break;
			}
		}
		
	}
	
	// Make a array of OLD IDs => NEW IDs for replace
	$pagelayer->imported_menus_preg = [];
	
	// If we have menus !
	if(!empty($pagelayer->imported_menus)){
		
		foreach($pagelayer->imported_menus as $k => $v){
			$pagelayer->imported_menus_preg['('.$k.')'] = $v;
		}
		
	// Theme didnt import menus, so lets replace with 0
	}else{
		$pagelayer->imported_menus_preg['(\d*)'] = $menu_id;
	}
	
	//////////////////////
	// Start import
	//////////////////////
	
	// Import the Pagelayer Templates files
	foreach($pgl as $k => $v){
		
		$path = pagelayer_cleanpath($pagelayer_theme_path.'/'.$k.'.pgl');
		
		$new_post = array();
	
		// Is the page there ?
		$template = get_page_by_path($k, OBJECT, array($pagelayer->builder['name']));
		
		// It does exist so save the revision IF its the header and footer
		if(!empty($template)){
			
			$rev = wp_save_post_revision($template->ID);
			
			// Did we save the rev ?
			if(empty($rev)){
				// TODO : Throw error
			}
			
			$new_post['ID'] = $template->ID;
			
		}
		
		// Make an array
		$new_post['post_content'] = empty($v['post_content']) ? file_get_contents($path) : $v['post_content'];
		$new_post['post_title'] = $v['title'];
		$new_post['post_name'] = $k;
		$new_post['post_type'] = $pagelayer->builder['name'];
		$new_post['post_status'] = 'publish';
		$new_post['comment_status'] = 'closed';
		$new_post['ping_status'] = 'closed';		
		//pagelayer_print($new_post);die();
		
		// Handle Menu data
		$new_post['post_content'] = pagelayer_import_handle_replaces($new_post['post_content']);
		
		//pagelayer_print($new_post);die();
		
		// Now insert / update the post
		$ret = pagelayer_insert_content($new_post, $err);
		$post_id = $ret;
		$pagelayer->import_map[$k] = $ret;
		$pagelayer->imported_ids[$new_post['post_type']][$new_post['post_name']] = $ret;
		
		// Did we save the rev ?
		if(empty($ret)){
			$pl_error[] = 'Could not update the Pagelayer Template '.$k;
			return false;
		}
		
		// Save our template type
		update_post_meta($post_id, 'pagelayer_template_type', $v['type']);
		update_post_meta($post_id, 'pagelayer_template_conditions', $v['conditions']);
		update_post_meta($post_id, 'pagelayer_imported_content', $template_name);
		
		// Any conditions having Page IDs that need to be updated ?
		if(!empty($v['conditions'])){
			
			foreach($v['conditions'] as $ck => $cv){
				if(!empty($cv['id'])){
					$conditions[$post_id][$ck] = $cv['id'];
				}
			}
			
		}
		
	}
	
	/////////////////////////
	// Handle the PAGES Data
	/////////////////////////
	
	//pagelayer_print($data);
	
	// Import taxonomies
	$taxonomy_ids = array();
	if(!empty($data['taxonomies'])){
		$taxonomy_ids = pagelayer_import_taxonomies($data['taxonomies']);
	}
	
	foreach($data as $data_type => $data_v){
		
		$pagelayer->imported[$data_type] = 1;
		
		// To import theme related settings
		if($data_type == 'conf'){
			pagelayer_import_conf($data['conf']);
			continue;
		}
		
		if($data_type == 'menus' || $data_type == 'taxonomies'){
			continue;
		}
	
		// Check the theme files
		foreach($data[$data_type] as $k => $v){
			
			$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/'.$data_type.'/'.$k);
			
			// Does it have the title and slug ?
			if(empty($v['post_title']) || empty($v['post_name'])){
				$pl_error[] = 'Something is fishy with this theme as there is no title or slug for '.$k;
				return false;
			}
			
			// Does the file exist ?
			if(!file_exists($path) || (empty($GLOBALS['sitepad']['dev']) && pagelayer_cleanpath(realpath($path)) != $path)){
				$pl_error[] = 'Something is fishy with this theme';
				return false;
			}
			
		}
		
		$menu_pages = [];
		
		// Now check the pages if it exist in this installation ?
		foreach($data[$data_type] as $k => $v){
			
			$path = pagelayer_cleanpath($pagelayer_theme_path.'/data/'.$data_type.'/'.$k);
			
			$args = ['post_type' => $data_type,
				'name' => $v['post_name'], 
				'post_status' => $v['post_status'],
				'posts_per_page' => -1
			];
			
			$type_query = new WP_Query($args);
			
			// Is the page there ?
			$page = @current($type_query->posts);
			//r_print($page);
				
			$new_post = array();
			$insert_meta = 1;
			
			// It does exist so save the revision IF its the header and footer
			if(!empty($page)){
				
				$insert_meta = 0;
				
				if(isset($_POST['overwrite'])){
					$rev = wp_save_post_revision($page->ID);
					$new_post['ID'] = $page->ID;
					$insert_meta = 1;
				}
				
			}
			
			// Make an array
			$new_post['post_content'] = file_get_contents($path);
			$new_post['post_excerpt'] = $v['post_excerpt'];
			$new_post['post_title'] = $v['post_title'];
			$new_post['post_name'] = $v['post_name'];
			$new_post['post_type'] = $data_type;
			$new_post['post_status'] = 'publish';
			
			if(isset($_POST['maintain_ids']) && empty($new_post['ID'])){
				$new_post['import_id'] = $v['ID'];
			}
			
			// Category register
			if(!empty($v['taxonomies'])){
				
				foreach($v['taxonomies'] as $tax => $tax_ids){
					
					if(!empty($tax_ids)){
						
						// Need to replace ids with new ids 
						$tax_ids = explode(',', $tax_ids);

						foreach($tax_ids as $key => $id){
							$tax_ids[$key] = (int) $taxonomy_ids[$id];
						}
						
						switch ($tax) {
							case 'category':
								$new_post['post_category'] = $tax_ids;
								break;
							case 'post_tag':
								$new_post['tags_input'] = $tax_ids;
								break;
							default:
								$new_post['tax_input'][$tax] = $tax_ids;
						}
					}
					
				}
				
			}
			
			// Meta file path
			$meta_path = pagelayer_cleanpath($pagelayer_theme_path.'/data/'.$data_type.'/'.$k.'.meta');
			
			if($insert_meta && file_exists($meta_path)){
				$meta_path = pagelayer_cleanpath($pagelayer_theme_path.'/data/'.$data_type.'/'.$k.'.meta');
				$new_post['meta_input'] = file_get_contents($meta_path);
				$new_post['meta_input'] = json_decode($new_post['meta_input']);
			}
			
			//r_print($new_post);die();
		
			// Handle Menu data
			$new_post['post_content'] = pagelayer_import_handle_replaces($new_post['post_content']);
			
			// Now insert / update the post
			$ret = pagelayer_insert_content($new_post, $err);
			
			// Did we save the post ?
			if(empty($ret)){
				$pl_error[] = 'Could not update the '.$data_type.' '.$v['post_name'];
				return false;
			}
			
			update_post_meta($ret, 'pagelayer_imported_content', $template_name);
			
			$pagelayer->import_map[$v['ID']] = $ret;
			$pagelayer->imported_ids[$new_post['post_type']][$new_post['post_name']] = $ret;
			
			// Skip Header, Footer and Home pages
			if($data_type == 'page' && preg_match('/^home/is', $new_post['post_name'])){
				$home_page = $ret;
			}
			
			if(defined('SITEPAD')){
				
				// Does the screenshot exist ?
				$screenshot_file = $pagelayer_theme_path.'/screenshots/'.$v['post_name'].'.jpg';
				if(file_exists($screenshot_file)){
					@mkdir($sitepad['screenshots_path'], 0755, true);
					@copy($screenshot_file, $sitepad['screenshots_path'].'/'.$v['post_name'].'.jpg');
				}
			
			}
			
		}
	
	}
	
	// Update Post for import
	if(!empty($conditions)){
		
		foreach($conditions as $post_ID => $v){
			
			$cond = get_post_meta($post_ID, 'pagelayer_template_conditions', 1);
			
			foreach($v as $ck => $cv){
			
				if(!empty($pagelayer->import_map[$cv])){
					$cond[$ck]['id'] = $pagelayer->import_map[$cv];
				}
			
			}
			
			update_post_meta($post_id, 'pagelayer_template_conditions', $cond);
			
		}
		
	}
	
	// Call a function for the theme if they want to execute something like create more templates, etc
	$ret = apply_filters('pagelayer_theme_imported', $template_name);
	
	if(isset($_POST['set_home_page']) || isset($_POST['create_blog_page'])){
		
		// Get the home page ID
		$blog = get_page_by_path('blog', OBJECT, array('page'));
		
		// Insert the blog page
		if(empty($blog)){
			
			$new_post['post_content'] = '';
			$new_post['post_title'] = 'Blog';
			$new_post['post_name'] = 'blog';
			$new_post['post_type'] = 'page';
			$new_post['post_status'] = 'publish';
		
			// Now insert / update the post
			$blog_id = wp_insert_post($new_post);
			
		}else{
			$blog_id = $blog->ID;
		}
		
		// Set the blog page
		update_option('page_for_posts', $blog_id);
		
	}
	
	if(!empty($data['conf']['page_for_posts'])){
		$pagelayer->import_map[$data['conf']['page_for_posts']] = $blog_id;
		$pagelayer->imported_ids['page']['blog'] = $blog_id;
	}
	
	// Update any links that are to be updated
	if(!empty($pagelayer->import_links)){
		
		foreach($pagelayer->import_links as $post_type => $v){
			foreach($v as $slug => $link_maps){
				
				// Lets get the post
				$tmp_post = get_post($pagelayer->imported_ids[$post_type][$slug]);
				
				foreach($link_maps as $old_link_type => $old_link_slugs){
					
					//pagelayer_print($old_link_slugs);die();
					
					foreach($old_link_slugs as $old_link_slug){
						
						// Did we have such a link ?
						$new_link_id = @$pagelayer->imported_ids[$old_link_type][$old_link_slug];
						
						// If not found, lets try to find a similar post
						if(empty($new_link_id)){
							
							$args = ['name' => $old_link_slug,
								'post_type' => $old_link_type];
							
							// Make query
							$query = new WP_Query($args);
							
							// Get post
							if(!empty($query->posts)){
								$link_post = current($query->posts);
								//echo $old_link_slug.' - ';pagelayer_print($link_post->post_name);die();
								
								$new_link_id = @$link_post->ID;
							}
							
						}
						
						if(empty($new_link_id)){
							continue;
						}
						
						$tmp_post->post_content = str_replace('||link_id|'.$old_link_type.'|'.$old_link_slug.'||', $new_link_id, $tmp_post->post_content);
					}
				}
				
				//pagelayer_print($tmp_post);
				wp_update_post($tmp_post);
			}
		}
		
	}
	
	if(isset($_POST['set_home_page'])){
		
		// Set the blog page
		update_option('show_on_front', 'page');
		
		// Set home page as the default page
		if(!empty($home_page)){
			update_option('page_on_front', $home_page);
		}
		
	}
	
	// Update the menu
	if(empty($_POST['no_header_menu'])){
		
		// Are we importing from the theme ?
		if(!empty($pagelayer->imported_menus)){
			
			foreach($pagelayer->imported_menus as $k => $v){
				pagelayer_import_update_menus($v, $pagelayer_theme_path);
			}
			
		// We created the menu, lets update it
		}else{
			pagelayer_update_header_menu($menu_id, $pagelayer->import_map);
		}
	}
	
	// Save that we have imported the theme
	update_option('pagelayer_theme_'.$template_name.'_imported', time(), true);
	
	// Blank woocommerce fix
	update_option('pagelayer_template_product_fix', 0);
	
	return true;

}

// Import Taxonomies Handler
function pagelayer_import_taxonomies($taxonomy){
	
	$new_ids = array();
	
	foreach($taxonomy as $term_id => $term){
		
		$parent_id = null;
		$term_par = 0;
		
		// If tern has parent
		if(!empty($term['parent']) && empty($new_ids[$term['parent']])){
			
			// Get parent taxonomy
			$par_terms = get_terms( array(
				'taxonomy' => $term['taxonomy'],
				'hide_empty' => false,
				'meta_key' => 'pagelayer_imported_id',
				'meta_value' => $term['parent']
			) );
			
			$par_terms_len = count($par_terms) - 1;
			
			// If not exists
			if(is_wp_error($par_terms) || empty($par_terms)){
				$par_ids =  pagelayer_import_taxonomies(array($term['parent'] => $taxonomy[$term['parent']]));
				$term_par = $parent_id = $par_ids[$term['parent']];
				$new_ids[$term['parent']] = $parent_id;
			}elseif($par_terms_len > -1){
				$term_par = $parent_id = $par_terms[$par_terms_len]->term_id;
			}
			
		}elseif(!empty($new_ids[$term['parent']])){
			$term_par = $parent_id = $new_ids[$term['parent']];
		}
		
		$exist_term = term_exists($term['name'], $term['taxonomy'], $parent_id );
		
		if($exist_term === null){
			$tax_details = wp_insert_term($term['name'], $term['taxonomy'] ,array('description' => $term['description'],'parent' => $term_par, 'slug' => $term['slug']));
			if(!(is_wp_error( $tax_details ))){
				$new_id = $tax_details['term_id'];
			}
		}elseif(is_array($exist_term)){
			$new_id = $exist_term['term_id'];
		}else{
			$new_id = $exist_term;
		}
		
		// ID is empty?
		if(empty($new_id)){
			continue;
		}
		
		update_term_meta( $new_id, 'pagelayer_imported_id', $term_id);
		$new_ids[$term_id] = $new_id;
	}
	
	return $new_ids;
}

add_filter('pagelayer_start_insert_content', 'pagelayer_import_start_insert_content');
function pagelayer_import_start_insert_content($post){
	
	global $pagelayer;
	
	$_post = json_encode($post);
	
	// Does it have links ?
	if(preg_match_all('/(\|\|link_id\|([\w-]*)\|([\w-]*)\|\|)/', $_post, $matches)){
		foreach($matches[3] as $kk => $link){
			$pagelayer->import_links[$post['post_type']][$post['post_name']][$matches[2][$kk]][] = $link;
		}
		//pagelayer_print($matches);pagelayer_print($pagelayer->import_links);die();
	}
	
	if(preg_match('/theme_url/is', $_post)){
		$do = 1;
	}
	
	// Lets replace the images
	foreach($pagelayer->import_media as $k => $v){
		$_post = str_replace($k, $v, $_post);
		$k = str_replace('/', '\/', $k);// Handle JSON
		$_post = str_replace($k, $v, $_post);
		$k = str_replace('/', '\/', addslashes($k));// Handle Doubled JSON
		$_post = str_replace($k, $v, $_post);
	}
	
	$post = json_decode($_post, true);
	
	if(!empty($do)){
		//echo $_post;
		//pagelayer_print($post);die();
	}
	
	return $post;
}

// Create the menu
function pagelayer_import_create_menu($name){
		
	// Create the menu if not exists
	$menu_name = (empty($name) ? 'Pagelayer Menu' : $name);
	$menu_exists = wp_get_nav_menu_object($menu_name);
	
	// If there is no menu we will need to add it
	if(!empty($menu_exists)){
		wp_delete_nav_menu($menu_exists);
	}
	
	// Insert the Menu
	$menu_id = wp_create_nav_menu($menu_name);
	
	//r_print($menu_exists);r_print($menu_name);r_print($menu_id);die();
	
	if(!is_int($menu_id)){
		return false;
	}
	
	// We need to DISABLE auto add TEMPORARILY
	$options = (array) get_option('nav_menu_options');
	
	if (isset($options['auto_add'])){
		$key = array_search($menu_id, $options['auto_add']);
		
		if(!empty($key)){
			unset($options['auto_add'][$key]);
			update_option('nav_menu_options', $options);
		}
	}
	
	return $menu_id;

}

// Callback for menu replacement	
function pagelayer_import_handle_replaces($content){
	global $pagelayer;
	
	// Replace the old ID structure
	$content = preg_replace_callback('/pagelayer-id="(\w{16})"/s', 'pagelayer_handle_id_sc', $content);
	$content = preg_replace_callback('/"pagelayer-id"\:"(\w{16})"/s', 'pagelayer_handle_id', $content);
	
	foreach($pagelayer->imported_menus_preg as $k => $v){
		$content = preg_replace('/\[pl_wp_menu ([^\]]*)nav_list="'.$k.'"([^\]]*)\]/is', '[pl_wp_menu ${1}nav_list="'.$v.'"${3}]', $content);
	}
	
	// Also for block format
	$content = preg_replace_callback('/<!--\s+(?P<closer>\/)?sp:pagelayer\/pl_wp_menu\s+(?P<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+\/?-->).)*+)?}\s+)?(?P<void>\/)?-->/s', 'pagelayer_handle_wp_menu', $content);
		
	// Lets replace the variables for social icons
	$content = preg_replace_callback('/\[pl_social ([^\]]*)\]/is', 'pagelayer_handle_social_urls', $content);
	
	$content = preg_replace_callback('/<!--\s+(?P<closer>\/)?sp:pagelayer\/pl_social\s+(?P<attrs>{(?:(?:[^}]+|}+(?=})|(?!}\s+\/?-->).)*+)?}\s+)?(?P<void>\/)?-->/s', 'pagelayer_handle_social_urls_blocks', $content);
	
	return $content;
}

// Update the header menu
function pagelayer_update_header_menu($menu_id, $pages){
	
	$menu_pages = [];
	
	$home = get_option('page_on_front');
	if(!empty($home)){
		$menu_pages[] = $home;
	}
	
	$blog = get_option('page_for_posts');
	if(!empty($blog)){
		$menu_pages[] = $blog;
	}
	
	// The other links
	foreach($pages as $pk => $pv){
		
		$tmp = get_post($pv);
		
		if(is_wp_error($tmp) || $tmp->post_type !== 'page'){
			continue;
		}
		
		// Skip Header, Footer and Home pages
		if(in_array($pv, $menu_pages)){
			continue;
		}
		
		$menu_pages[] = $pv;
		
	}
	
	// Get the pages
	foreach($menu_pages as $pk => $page_id){
		$menu_pages[$pk] = get_post($page_id);
	}
	
	// The other links
	foreach($menu_pages as $pk => $pv){
		
		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' =>  $pv->post_title,
			'menu-item-url' => home_url( '/'.$pv->post_name.'/' ),
			'menu-item-status' => 'publish',
			'menu-item-type' => 'post_type',
			'menu-item-object' => 'page',
			'menu-item-object-id' => $pv->ID));
		
	}
	
	// We need to enable auto add new pages
	$options = (array) get_option('nav_menu_options');
	
	if (!isset($options['auto_add'])){
		$options['auto_add'] = array();
	}
	
	$options['auto_add'][] = $menu_id;
	update_option('nav_menu_options', $options);
	
}

// For import of our exported menus
function pagelayer_import_update_menus($menu_id, $pagelayer_theme_path = ''){
	
	global $pagelayer;
	
	$old_id = array_search($menu_id, $pagelayer->imported_menus);
	$slug = $pagelayer->imported_menus_slug[$menu_id];
	
	$data = file_get_contents($pagelayer_theme_path.'/data/menus/'.$slug);
	$data = @json_decode($data, true);
	
	$ids = [];
	
	// Insert the links
	foreach($data as $k => $v){
		
		$r = [];		
		$r['menu-item-title'] = $v['post']['title'];
		$r['menu-item-status'] = $v['post']['post_status'];
		$r['menu-item-type'] = $v['post']['type'];
		$r['menu-item-object'] = $v['post']['object'];
		$r['menu-item-classes'] = implode(' ', $v['post']['classes']);
		
		// Any parent ?
		if(!empty($v['post']['menu_item_parent'])){
			
			$parent = $ids[$v['post']['menu_item_parent']];
			
			if(!empty($parent)){
				$r['menu-item-parent-id'] = $parent;
			}
			
		}
		
		// Regular Data Object
		if($r['menu-item-type'] !== 'custom'){
			
			$r['menu-item-object-id'] = $pagelayer->import_map[$v['post']['object_id']];
			
			if(empty($r['menu-item-object-id'])){
				continue;
			}
			
			$r['menu-item-url'] = get_permalink($r['menu-item-object-id']);
		
		// Custom URL
		}else{
			$r['menu-item-url'] = $v['post']['url'];
		}
		
		//r_print($r);
			
		$updated_nav = wp_update_nav_menu_item($menu_id, 0, $r);
		$ids[$v['post']['db_id']] = $updated_nav;
		
		if(isset($v['post_metas']['_pagelayer_content'])){
			$mdata = pagelayer_import_handle_replaces($v['post_metas']['_pagelayer_content']);
			$mdata = pagelayer_sanitize_content($mdata);
			update_post_meta($updated_nav, '_pagelayer_content', $mdata);
		}
	}

	// We need to enable auto add new pages
	$options = (array) get_option('nav_menu_options');
	
	if (!isset($options['auto_add'])){
		$options['auto_add'] = array();
	}
	
	$options['auto_add'][] = $menu_id;
	update_option('nav_menu_options', $options);

}

// Callback for menu replacement	
function pagelayer_handle_wp_menu($matches){
	global $pagelayer;
	
	foreach($pagelayer->imported_menus_preg as $k => $v){
		$matches[0] = preg_replace('/nav_list"\s*:\s*"'.$k.'"/is', 'nav_list":"'.$v.'"', $matches[0]);
	}
	
	return $matches[0];
	
}

// Change the old style ID to the new style
function pagelayer_handle_id($matches){
	//r_print($matches);die();
	$str = '"pagelayer-id":"'.pagelayer_create_id().'"';
	return $str;
}

// Change the old style ID to the new style
function pagelayer_handle_id_sc($matches){
	//r_print($matches);die();
	$str = 'pagelayer-id="'.pagelayer_create_id().'"';
	return $str;
}

// Replace Social URLs with the one given in setup
function pagelayer_handle_social_urls($matches){
	//r_print($matches);die();
	
	// Get the icon
	preg_match('/icon=(\'|")([^\'"]*)(\'|")/is', $matches[0], $icon);
	$icon = $icon[2];
	
	$urls = pagelayer_get_social_urls();
	
	foreach($urls as $k => $v){
		if(preg_match('/'.preg_quote($k, '/').'/is', $icon)){
			$social_url = $v;
			break;
		}
	}
	
	if(!empty($social_url)){
		
		// Is the social_url param there ?
		if(!preg_match('/social_url=/is', $matches[0])){
			$matches[0] = substr($matches[0], 0, -1).'social_url="#"]';
		}
		
		$matches[0] = preg_replace('/social_url=(\'|")([^\'"]*)(\'|")/is', 'social_url="'.$social_url.'"', $matches[0]);
	}
	
	//r_print($matches);die();
	
	return $matches[0];
	
}

// Replace Social URLs with the one given in setup
function pagelayer_handle_social_urls_blocks($matches){
	
	// Get the icon
	preg_match('/icon":"([^"]*)"/is', $matches[0], $icon);
	$icon = $icon[1];
	
	$urls = pagelayer_get_social_urls();
	
	foreach($urls as $k => $v){
		if(preg_match('/'.preg_quote($k, '/').'/is', $icon)){
			$social_url = $v;
			break;
		}
	}
	
	if(!empty($social_url)){
		
		// Is the social_url param there ?
		if(!preg_match('/"social_url"/is', $matches[0])){
			$matches[0] = preg_replace('/("icon"\s*:\s*"([^"]*)")/is', '"icon":"'.$icon.'","social_url":"#"', $matches[0]);
		}
		
		$matches[0] = preg_replace('/social_url"\s*:\s*"([^"]*)"/is', 'social_url":"'.$social_url.'"', $matches[0]);
	}
	
	return $matches[0];
	
}

// Add the blog templates
function pagelayer_blog_templates($pgl){

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
		},
		"404": {
			"type": "single",
			"title": "404",
			"conditions": [
				{
					"type": "include",
					"template": "singular",
					"sub_template": "404",
					"id": ""
				}
			]
		}
	}';
	
	$conf = json_decode($conf, true);
	
	// Do we have the blog template ?
	if(empty($pgl['blog-template'])){
	
		$conf['blog-template']['post_content'] = '<!-- sp:pagelayer/pl_row {"stretch":"auto","col_gap":"0","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","ele_padding_tablet":"0px,0px,0px,0px","ele_padding_mobile":"0px,0px,0px,0px","ele_margin":"80px,0px,40px,0px","ele_padding":"0px,0px,0px,0px","pagelayer-id":"m4k2309"} -->
<!-- sp:pagelayer/pl_col {"overlay_hover_delay":"400","widget_space":"0","col_width":"100","col_width_mobile":"100","ele_padding_mobile":"10px,10px,10px,10px","col_width_tablet":"100","ele_padding_tablet":"10px,10px,10px,10px","pagelayer-id":"8yo2717"} -->
<!-- sp:pagelayer/pl_archive_posts {"type":"default","columns":"3","columns_mobile":"1","col_gap":"30","row_gap":"30","data_padding":"10,10,10,10","bg_color":"#ffffff","show_thumb":"true","show_title":"true","meta":"date","meta_sep":"","show_content":"excerpt","content_color":"#adb5bdff","content_align":"left","pagination":"number_prev_next","thumb_size":"medium_large","ratio":"0.7","title_color":"#495057ff","title_typo":",20,,bold,,,,,,,","exc_length":"10","pagi_prev_text":"Previous","pagi_next_text":"Next","pagi_end_size":"1","pagi_mid_size":"2","pagi_align":"center","box_shadow":"0,1,5,#00000026,0,","title_spacing":"0,0","meta_color":"#666666ff","meta_align":"left","meta_typo":"Roboto,12,,600,,,,,Uppercase,,","content_padding":"10,0,10,0","pagi_colors":"active","pagi_color":"#495057ff","pagi_hover_color":"#000000ff","pagi_current_color":"#000000ff","pagi_typo":",,,,,,,,,,","meta_tag_pos":"absolute","meta_width":"50%","meta_vposition":"bottom","meta_hposition":"left","meta_vposition_offset":"8px","meta_hposition_offset":"8px","show_more":"true","more":"Read More","align":"right","icon_position":"pagelayer-btn-icon-right","more_typo":"Roboto,12,,bold,,,,,Capitalize,,","btn_type":"pagelayer-btn-custom","size":"pagelayer-btn-custom","icon":"fas fa-angle-right","icon_spacing":"5","btn_bg_color":"#00000000","more_color":"#adb5bdff","btn_hover":"hover","btn_custom_size":"0,0","pagi_padding":"50,0,0,0","columns_tablet":"1","pagelayer-id":"mue2352"} /-->
<!-- /sp:pagelayer/pl_col -->
<!-- /sp:pagelayer/pl_row -->';
	
		$pgl['blog-template'] = $conf['blog-template'];

	}
	
	// Do we have the blog template ?
	if(empty($pgl['404'])){
	
		$conf['404']['post_content'] = '<!-- sp:pagelayer/pl_row {"stretch":"auto","col_gap":"10","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","ele_margin":"120px,0px,120px,0px","ele_padding":"0px,0px,0px,0px","pagelayer-id":"rxs3267"} -->
<!-- sp:pagelayer/pl_col {"widget_space":"15","overlay_hover_delay":"400","col_width":"50","col_width_mobile":"40","col_width_tablet":"40","pagelayer-id":"iyr6907"} -->
<!-- sp:pagelayer/pl_image {"id":"{{pl_plugin_url}}/images/404image.jpg","id-size":"full","align":"right","img_hover":"normal","img_hover_delay":"400","caption_color":"#0986c0","max-width":"36","custom_size":"400x400","max-width_mobile":"100","max-width_tablet":"100","pagelayer-id":"tem415"} /-->
<!-- /sp:pagelayer/pl_col -->
<!-- sp:pagelayer/pl_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col_width":"50","col_width_mobile":"55","col_width_tablet":"55","pagelayer-id":"wnp3287"} -->
<!-- sp:pagelayer/pl_heading {"text":"\u003cp\u003eThe page you requested was not found we suggest you to go back to HomePage\u003c\/p\u003e","heading_state":"normal","align":"left","color":"#495057ff","heading_typo":",,,,,,,,,,","heading_text_shadow":",,,","ele_custom_pos":"true","ele_align":"margin","ele_height":"auto","ele_custom_width":"80%","ele_custom_width_mobile":"100%","ele_custom_width_tablet":"100%","pagelayer-id":"a8q1058"} --><p>The page you requested was not found we suggest you to go back to HomePage</p><!-- /sp:pagelayer/pl_heading -->
<!-- sp:pagelayer/pl_btn {"text":"Back To HomePage","align":"left","type":"pagelayer-btn-custom","size":"pagelayer-btn-custom","btn_hover_delay":"400","icon_position":"pagelayer-btn-icon-left","icon_spacing":"5","link":"||link_id|page|home||","btn_typo":"Roboto,15,Normal,500,Normal,None,,1,Uppercase,1,1","btn_bg_color":"#ff8474ff","btn_color":"#ffffff","btn_custom_size":"25","btn_hover":"hover","btn_bg_color_hover":"#583d72ff","btn_color_hover":"#ffffffff","btn_shadow":"1,1,1,#000000ff,1,","btn_typo_mobile":",14,,,,,,,,,","btn_typo_tablet":",14,,,,,,,,,","pagelayer-id":"znu8912"} /-->
<!-- /sp:pagelayer/pl_col -->
<!-- /sp:pagelayer/pl_row -->';
	
		$pgl['404'] = $conf['404'];

	}
	
	
	// Do we have the blog template ?
	if(empty($pgl['single-template'])){
		
		$conf['single-template']['post_content'] = '<!-- sp:pagelayer/pl_row {"stretch":"auto","col_gap":"0","width_content":"fixed","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","ele_margin":"80px,0px,80px,0px","ele_padding":"0px,0px,0px,0px","fixed_width":"75%","fixed_width_mobile":"100%","fixed_width_tablet":"100%","pagelayer-id":"bdr9414"} -->
<!-- sp:pagelayer/pl_col {"widget_space":"15","overlay_hover_delay":"400","ele_padding":"10px,10px,10px,10px","content_pos":"center","col_width":"100","pagelayer-id":"yfr4566"} -->
<!-- sp:pagelayer/pl_post_title {"title_color":"#495057ff","typo":",35,,bold,,,,1.3,,,","shadow":"0,0,1,#000000ff","align":"left","typo_mobile":",30,,,,,,,,,","typo_tablet":",30,,,,,,,,,","pagelayer-id":"v7n9444"} /-->
<!-- sp:pagelayer/pl_post_excerpt {"align":"left","pe_margin":"0px,0px,0px,0px","color":"#adb5bdff","typo":",15,Italic,500,,,,1.5,,,","ele_margin":"0px,0px,0px,0px","ele_padding":"0px,0px,0px,0px","pagelayer-id":"kgd1425"} /-->
<!-- sp:pagelayer/pl_featured_img {"size":"full","img_filter":"0,100,100,0,0,100,100","caption_color":"#0986c0","img_hover_delay":"400","align":"center","img_shadow":"0,24,36,#0000001a,0,","custom_size":"80%,60%","pagelayer-id":"4wb1030"} /-->
<!-- sp:pagelayer/pl_inner_row {"stretch":"auto","col_gap":"10","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","pagelayer-id":"f8n3612"} -->
<!-- sp:pagelayer/pl_inner_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col":"4","pagelayer-id":"i795860"} -->
<!-- sp:pagelayer/pl_post_info {"layout":"vertical","space_between":"25","align":"left","icon_colors":"normal","text_colors":"hover","icon_color_normal":"#495057ff","text_color_normal":"#495057ff","text_color_hover":"#000000ff","input_typo":"Roboto,14,,500,,,,1.6,,,","ele_align":"margin","ele_height":"auto","ele_width":"initial","icon_size":"120%","pagelayer-id":"qip3968"} -->
<!-- sp:pagelayer/pl_post_info_list {"type":"author","info_link":"true","info_icon_on":"true","info_icon":"fas fa-user-circle","info_avatar":"true","info_avatar_size":"22","pagelayer-id":"l1i4146"} /-->
<!-- sp:pagelayer/pl_post_info_list {"type":"date","info_link":"true","info_icon_on":"true","info_icon":"far fa-clock","date_format":"default","pagelayer-id":"xj43574"} /-->
<!-- /sp:pagelayer/pl_post_info -->
<!-- /sp:pagelayer/pl_inner_col -->
<!-- sp:pagelayer/pl_inner_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col":"7","pagelayer-id":"t2j1983"} -->
<!-- sp:pagelayer/pl_post_info {"layout":"vertical","space_between":"0","align":"right","icon_colors":"normal","text_colors":"normal","icon_color_normal":"#495057ff","text_color_normal":"#0072ffff","text_color_hover":"","input_typo":"Roboto,14,,500,,,,1.6,,,","ele_align":"margin","ele_height":"auto","ele_width":"initial","icon_size":"120%","ele_position":"","ele_hposition":"left","ele_hposition_offset":"100%","ele_bg_type":"","ele_bg_color":"#66aaff26","ele_padding":"0px,0px,0px,0px","align_mobile":"left","align_tablet":"left","anchor_text_colors":"normal","anchor_text_color_normal":"#f48989ff","anchor_background_color_normal":"#4bd34bff","anchor_text_padding":"5,10,5,10","anchor_text_margin":"10,10,10,10","terms_text_colors":"normal","terms_background_color_normal":"#66aaff26","terms_text_color_normal":"#0072ffff","terms_text_padding":"5,10,5,10","terms_text_margin":"0,5,0,5","terms_border_radius":"4,4,4,4","pagelayer-id":"dbq3916"} -->
<!-- sp:pagelayer/pl_post_info_list {"type":"terms","info_link":"true","info_icon_on":"","info_icon":"fas fa-user-circle","taxonomy":"category","pagelayer-id":"wpv7212"} /-->
<!-- /sp:pagelayer/pl_post_info -->
<!-- /sp:pagelayer/pl_inner_col -->
<!-- /sp:pagelayer/pl_inner_row -->
<!-- sp:pagelayer/pl_inner_row {"stretch":"auto","col_gap":"10","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","pagelayer-id":"trw3096"} -->
<!-- sp:pagelayer/pl_inner_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col":"2","pagelayer-id":"wwu6541"} -->
<!-- sp:pagelayer/pl_post_content {"align":"left","pagelayer-id":"ccj4284"} /-->
<!-- /sp:pagelayer/pl_inner_col -->
<!-- /sp:pagelayer/pl_inner_row -->
<!-- sp:pagelayer/pl_inner_row {"stretch":"auto","col_gap":"10","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","pagelayer-id":"wl6689"} -->
<!-- sp:pagelayer/pl_inner_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col":"6","pagelayer-id":"fja274"} -->
<!-- sp:pagelayer/pl_post_info {"layout":"vertical","space_between":"0","align":"left","icon_colors":"normal","text_colors":"normal","icon_color_normal":"#495057ff","text_color_normal":"#0072ffff","text_color_hover":"","input_typo":"Roboto,13,,500,,,,1.6,Uppercase,,","ele_align":"margin","ele_height":"auto","ele_width":"initial","icon_size":"120%","ele_position":"","ele_hposition":"left","ele_hposition_offset":"100%","ele_bg_type":"","ele_bg_color":"#66aaff26","ele_padding":"0px,0px,0px,0px","align_mobile":"left","align_tablet":"left","anchor_text_colors":"normal","anchor_text_color_normal":"#f48989ff","anchor_background_color_normal":"#4bd34bff","anchor_text_padding":"5,10,5,10","anchor_text_margin":"10,10,10,10","terms_text_colors":"normal","terms_background_color_normal":"#66aaff26","terms_text_color_normal":"#0072ffff","terms_text_padding":"5,10,5,10","terms_text_margin":"0,5,0,5","terms_border_radius":"4,4,4,4","pagelayer-id":"dcl5111"} -->
<!-- sp:pagelayer/pl_post_info_list {"type":"terms","info_link":"true","info_icon_on":"","info_icon":"fas fa-user-circle","taxonomy":"post_tag","info_before":"\ud83d\udd16Tags:","pagelayer-id":"7401458"} /-->
<!-- /sp:pagelayer/pl_post_info -->
<!-- /sp:pagelayer/pl_inner_col -->
<!-- sp:pagelayer/pl_inner_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col":"6","pagelayer-id":"jzl6522"} -->
<!-- sp:pagelayer/pl_share_grp {"type":"icon-label","bg_shape":"pagelayer-social-bg-none","align":"right","vspace":"0","hspace":"0","height":"35","icon_size":"27","icon_space":"0","color_scheme":"pagelayer-scheme-official","icon_color":"#adb5bdff","social_hover_delay":"400","name_typo":"Roboto,15,,500,,,,,,,","count":"","icon_bg_color":"#00000000","social_hover":"","icon_color_hover":"#000000ff","icon_bg_color_hover":"#00000000","vspace_mobile":"10","hspace_mobile":"10","height_mobile":"NaN","icon_size_mobile":"20","name_typo_mobile":",13,,,,,,,,,","align_mobile":"left","align_tablet":"left","vspace_tablet":"10","hspace_tablet":"10","icon_size_tablet":"20","name_typo_tablet":",13,,,,,,,,,","pagelayer-id":"xsb5673"} -->
<!-- sp:pagelayer/pl_share {"icon":"fab fa-facebook-square","target":"true","text":"Share on Facebook","pagelayer-id":"9mh2934"} /-->
<!-- sp:pagelayer/pl_share {"icon":"fab fa-twitter-square","text":"Share on Twitter","target":"true","pagelayer-id":"rai4926"} /-->
<!-- /sp:pagelayer/pl_share_grp -->
<!-- /sp:pagelayer/pl_inner_col -->
<!-- /sp:pagelayer/pl_inner_row -->
<!-- sp:pagelayer/pl_inner_row {"stretch":"auto","col_gap":"10","width_content":"auto","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","pagelayer-id":"zqp4850"} -->
<!-- sp:pagelayer/pl_inner_col {"widget_space":"15","overlay_hover_delay":"400","content_pos":"center","col":"2","pagelayer-id":"q725202"} -->
<!-- sp:pagelayer/pl_post_nav {"lables":"true","post_title":"true","arrows":"true","sep_color":"#adb5bdff","sep_rotate":"20","sep_width":"1","prev_label":"Previous","next_label":"Next","label_colors":"hover","title_colors":"normal","arrows_list":"chevron","icon_colors":"hover","label_color":"#adb5bdff","label_hover_color":"","label_typo":",15,,,,,,,,,","title_color":"#000000ff","title_typo":"Roboto,18,,bold,,,,,,,","icon_color":"#adb5bdff","icon_hover_color":"","icon_size":"30","disable_sep":"true","taxonomies":"category","pagelayer-id":"55r4448"} /-->
<!-- /sp:pagelayer/pl_inner_col -->
<!-- /sp:pagelayer/pl_inner_row -->
<!-- /sp:pagelayer/pl_col -->
<!-- /sp:pagelayer/pl_row -->
<!-- sp:pagelayer/pl_row {"stretch":"auto","col_gap":"0","width_content":"fixed","row_height":"default","overlay_hover_delay":"400","row_shape_top_color":"#227bc3","row_shape_top_width":"100","row_shape_top_height":"100","row_shape_bottom_color":"#e44993","row_shape_bottom_width":"100","row_shape_bottom_height":"100","ele_margin":"0px,0px,0px,0px","ele_padding":"0px,0px,0px,0px","fixed_width":"75%","fixed_width_mobile":"100%","ele_margin_mobile":"0px,0px,0px,0px","ele_padding_mobile":"0px,0px,0px,0px","fixed_width_tablet":"100%","pagelayer-id":"kf35301"} -->
<!-- sp:pagelayer/pl_col {"widget_space":"15","overlay_hover_delay":"400","ele_padding":"10px,10px,10px,10px","content_pos":"center","col_width":"100","pagelayer-id":"d539589"} -->
<!-- sp:pagelayer/pl_post_comment {"comment_skin":"theme_comment","post_type":"current","pagelayer-id":"1oh7228"} /-->
<!-- /sp:pagelayer/pl_col -->
<!-- /sp:pagelayer/pl_row -->';
	
		$pgl['single-template'] = $conf['single-template'];

	}
	
	return $pgl;
	
}

// Resize Image
function pagelayer_resizeImage($filename, $newwidth, $newheight){

	$imagesize = getimagesize($filename);
	$width = $imagesize[0];
	$height = $imagesize[1];
	
	// Calculate the Height and width
	if($width <= $newwidth || $height <= $newheight){
		return false;
	}
	
	$thumb = imagecreatetruecolor($newwidth, $newheight);
	
	switch($imagesize['mime']) {
		case 'image/jpg':
		case 'image/jpeg':
			$source = imagecreatefromjpeg($filename);
			break;
		case 'image/gif':
			$source = imagecreatefromgif($filename);
			break;
		case 'image/png':
			$source = imagecreatefrompng($filename);
			break;
	}
	
	if(empty($source)){
		return false;
	}
	
	imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	imagedestroy($source);
	ob_start();
	
	switch($imagesize['mime']) {
		case 'image/jpg':
		case 'image/jpeg':
			imagejpeg($thumb);
			break;
		case 'image/gif':
			imagegif($thumb);
			break;
		case 'image/png':
			imagepng($thumb);
			break;
	}
	
	$image = ob_get_clean();
	imagedestroy($thumb);
	
	return $image;
}
