<?php

//////////////////////////////////////////////////////////////
//===========================================================
// custom_fonts.php
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

// This function will handle the custom fonts pages in PageLayer 
add_action('init', 'pagelayer_custom_fonts_page', 9999);
function pagelayer_custom_fonts_page() {
	
	global $pagelayer;

	// Custom fonts supports
	$supports = array(
		'title', // post title
	);
	
	// Add custom fonts lables
	$labels = array(
		'name' => _x('Custom Fonts', 'plural'),
		'singular_name' => _x('Custom Font', 'singular'),
		'menu_name' => _x('Custom Fonts', 'admin menu'),
		'name_admin_bar' => _x('Custom Fonts', 'admin bar'),
		'add_new' => _x('Add New', 'Add'),
		'add_new_item' => __('Add New'),
		'new_item' => __('New Font'),
		'edit_item' => __('Edit Font'),
		'view_item' => __('View Font'),
		'all_items' => __('All Fonts'),
		'search_items' => __('Search Fonts'),
		'not_found' => __('No Pagelayer custom fonts found'),
	);
		
	$args = array(
		'supports' => $supports,
		'labels' => $labels,
		'public' => false,
		'show_in_menu' => false,		
		'publicly_queryable' => true,  
		'show_ui' => true, 
		'exclude_from_search' => true,  
		'show_in_nav_menus' => false,  
		'has_archive' => false,  
		'rewrite' => false, 	
	);
	
	// Register custom post type
	register_post_type(PAGELAYER_FONT_POST_TYPE, $args);
	remove_post_type_support( PAGELAYER_FONT_POST_TYPE, 'editor');	
}

// Removing extra columns
add_filter( 'manage_'.PAGELAYER_FONT_POST_TYPE.'_posts_columns', 'pagelayer_add_custom_columns' );
function pagelayer_add_custom_columns($columns){
	
	unset( $columns['author'] );
	unset( $columns['date']   );
	 
	$columns['pl-preview'] = __('Preview'); 
	 
	return $columns;
}

// Adding preview column data
add_action( 'manage_'.PAGELAYER_FONT_POST_TYPE.'_posts_custom_column' , 'pagelayer_add_custom_columns_data', 10, 2 );
function pagelayer_add_custom_columns_data( $column, $post_id ){
	if($column == __('pl-preview')){
		$font_link = get_post_meta( $post_id, 'pagelayer_font_link', true );		
		echo '<style>@font-face { font-family: "'.get_the_title($post_id).'"; src: url("'.wp_unslash( $font_link ).'"); }</style>';
		echo '<span style="font-family:\''.get_the_title($post_id).'\'; font-size:16px" >Preview of the CUSTOM font</span>';
	}
}

// Removing row actions
add_filter( 'post_row_actions', 'pagelayer_remove_row_actions', 10, 1 );
function pagelayer_remove_row_actions( $actions ){
	if( get_post_type() === PAGELAYER_FONT_POST_TYPE ){
		foreach($actions as $action => $html){
			if($action == 'edit' || $action == 'trash' || $action == 'clone' || $action == 'untrash' || $action == 'delete'){
				continue;
			}else{
				unset($actions[$action]);
			}
		}
	}
	return $actions;
}

// Removing Screen options
add_filter('screen_options_show_screen', 'pagelayer_remove_screen_options');
function pagelayer_remove_screen_options() { 
	if(get_post_type() == PAGELAYER_FONT_POST_TYPE) {
		return false;
	}
	return true; 
}

// Removing all other metaboxes.
add_action('admin_init', function() {pagelayer_remove_all_metaboxes(PAGELAYER_FONT_POST_TYPE);});
function pagelayer_remove_all_metaboxes($type) {
	add_filter("get_user_option_meta-box-order_{$type}", function() use($type) {
		global $wp_meta_boxes;
		$publishbox = $wp_meta_boxes[$type]['side']['core']['submitdiv'];
		$fontsBox = $wp_meta_boxes[$type]['normal']['default']['pl-fonts-link-box'];
		$wp_meta_boxes[$type] = array(	  
							'side' => array(
								'core' => array(
									'submitdiv' => $publishbox
								)
							),
							'normal' => array(
								'default' => array(
									'pl-fonts-link-box' => $fontsBox
									)
								)
							);
		return array();
	}, PHP_INT_MAX);
} 

// Hiding extra options of publish metabox
add_action( 'admin_head', 'pagelayer_hide_publish_options' );
function pagelayer_hide_publish_options() {
	if(get_post_type() == PAGELAYER_FONT_POST_TYPE){
		echo '<style>.submitbox #minor-publishing{ display: none; }</style>';		
	}
}

// Adding source metabox
add_action('add_meta_boxes', 'pagelayer_add_meta_box');
function pagelayer_add_meta_box(){
	add_meta_box( 'pl-fonts-link-box', _x('Source', 'font source'), 'pagelayer_font_link_metabox', PAGELAYER_FONT_POST_TYPE, 'normal', 'default', null);
}

function pagelayer_font_link_metabox($object){
	wp_enqueue_media();
	wp_nonce_field('pagelayer-font-post', 'pagelayer');
	$link = get_post_meta($object->ID, 'pagelayer_font_link', true);
	?>
	<div>
		<table width="100%">
			<tr>
				<th valign="top" style="text-align:right; padding-right:20px; width:20%;"><?php echo __('Font File');?> : </th>
				<td>
					<div>
						<input type="text" class="pagelayer_font_input" id="pl_font_link" name="pagelayer_font_link" onclick="fontUpload(event)" style="width:70%" value="<?php echo wp_unslash($link); ?>" autocomplete="false" readonly="true"/>
						<button type="button" class="button button-light" onclick="fontUpload(event)">Upload Font</button>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<script>
	
	window.onload = function(){
		jQuery('#submitdiv').on('click', '#publish', function(e){
			
			if(jQuery('#title').val()==''){
				alert('Please insert title of the page');
				return false;
			}else{
				if(jQuery('#pl_font_link').val()==''){
					alert('Please insert link of the font');				
					return false;
				}else{
					return true;					
				}				
			}
			
		});
	}
	
	function fontUpload(e){
		var allowed_mime_type = ['.ttf', '.woff', '.woff2','.otf'];
		var allClear = false;
		var custom_uploader = wp.media({
			title: 'Upload Font',
			library : {
				type : 'font'
			},
			button: {
				text: 'Select Font' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			for(var i=0; i<allowed_mime_type.length; i++){
				if(attachment['filename'].indexOf(allowed_mime_type[i]) != -1){
					allClear=true;				
					break;
				}
			}
			if(allClear){
				jQuery('.pagelayer_font_input').val(attachment['url']);
			}else{
				alert('Kindly insert a correct font file. Allowed font file types are (otf|ttf|woff|woff2)');
			}
		}).open();
	}
	</script>
	
<?php }

// Saving source metabox content
add_action('save_post', 'pagelayer_save_source_meta_box', 10, 3);
function pagelayer_save_source_meta_box($post_id, $post, $update){

	if(PAGELAYER_FONT_POST_TYPE != $post->post_type){
		return $post_id;
	}
	
	// DO an admin referrer check
	if(!empty($_POST)){
		check_admin_referer('pagelayer-font-post', 'pagelayer');
	}else{
		return $post_id;
	}

	$meta_box_link_value = '';

	if(isset($_POST['pagelayer_font_link'])){
		$meta_box_link_value  = wp_unslash($_POST['pagelayer_font_link']);
	}   

	update_post_meta($post_id, 'pagelayer_font_link', $meta_box_link_value );
	
}

// 	Adding custom mime type
add_filter('upload_mimes', 'pagelayer_custom_mime_types', 1, 1);	
function pagelayer_custom_mime_types($mime_types = array()){
	global $pagelayer;
	
	forEach($pagelayer->allowed_mime_type as $key => $value){
		$mime_types[$key]=$value;		
	}
	return $mime_types;
}

// Adding custom mime type
add_filter( 'mime_types', 'pagelayer_mime_types' );
function pagelayer_mime_types($default_mimes){
	global $pagelayer;
	
	forEach($pagelayer->allowed_mime_type as $key => $value){
		$default_mimes[$key]=$value;		
	}
	
	return $default_mimes;
}

// Adding custom mime type
add_filter( 'wp_check_filetype_and_ext', 'pagelayer_check_filetype_and_ext', 10, 5 );
function pagelayer_check_filetype_and_ext( $types, $file, $filename, $mimes, $real_mime = false ){	
	global $pagelayer;
	
	forEach($pagelayer->allowed_mime_type as $key => $value){
		if ( false !== strpos( $filename, '.'.$key ) ) {
			$types['ext'] = $key;
			$types['type'] = $value;
		}		
	}
	
	return $types;
}

// Removing notification.
add_filter( 'post_updated_messages', 'pagelayer_delete_notification' );
function pagelayer_delete_notification( $messages ){
	if(get_post_type() == PAGELAYER_FONT_POST_TYPE){
		unset($messages['post'][1]);
		unset($messages['post'][6]);
		return $messages;		
	}
}

