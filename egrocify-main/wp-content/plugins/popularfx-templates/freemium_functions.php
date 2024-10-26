<?php

//////////////////////////////////////////////////////////////
//===========================================================
// freemium_functions.php
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

// Get page title - 2C
function pagelayer_get_the_title( $including_context = false ) {
	$title = '';
	
	if(pagelayer_is_live() || wp_doing_ajax() || pagelayer_is_gutenberg_editor()){
		return 'Title';
	}

	if ( is_singular() ) {
		$title = get_the_title();

		if ( $including_context ) {
			$post_type_obj = get_post_type_object( get_post_type() );
			$title = sprintf( '%s: %s', $post_type_obj->labels->singular_name, $title );
		}
	} elseif ( is_search() ) {
		$title = sprintf( __( 'Search Results for: %s'), get_search_query() );

		if ( get_query_var( 'paged' ) ) {
			$title .= sprintf( __( '&nbsp;&ndash; Page %s' ), get_query_var( 'paged' ) );
		}
	} elseif ( is_category() ) {
		$title = single_cat_title( '', false );

		if ( $including_context ) {
			$title = sprintf( __( 'Category Archives: %s' ), $title );
		}
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
		if ( $including_context ) {
			$title = sprintf( __( 'Tag Archives: %s' ), $title );
		}
	} elseif ( is_author() ) {
		$title = get_the_author() ;

		if ( $including_context ) {
			$title = sprintf( __( 'Author Archives: %s' ), $title );
		}
	} elseif ( is_year() ) {
		$title = get_the_date( _x( 'Y', 'yearly archives date format' ) );

		if ( $including_context ) {
			$title = sprintf( __( 'Yearly Archives: %s' ), $title );
		}
	} elseif ( is_month() ) {
		$title = get_the_date( _x( 'F Y', 'monthly archives date format' ) );

		if ( $including_context ) {
			$title = sprintf( __( 'Monthly Archives: %s' ), $title );
		}
	} elseif ( is_day() ) {
		$title = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );

		if ( $including_context ) {
			$title = sprintf( __( 'Daily Archives: %s' ), $title );
		}
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );

		if ( $including_context ) {
			$title = sprintf( __( 'Archives: %s' ), $title );
		}
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );

		if ( $including_context ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, $title );
		}
	} elseif ( is_404() ) {
		$title = __( 'Page Not Found' );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	} elseif ( is_home() ) {
		$title = single_post_title('', false);
	}
	
	return $title;
}

// Get Taxonomies
function pagelayer_tax_list($item = '', $page = false){
	
	// Get types
	$types = pagelayer_post_types($page);
	
	// Loop thru
	foreach($types as $slug => $label){
		
		// Get the items
		$items = get_object_taxonomies($slug, 'objects');
		
		foreach($items as $name => $v) {
			if(!isset($taxonomies[$name])){
				$taxonomies[$name] = array('label' => $v->labels->singular_name, 'posttypes' => array($label));
			}else{
				$taxonomies[$name]['posttypes'][] = $label;
			}
		}	
	}
	
	// Make it simple
	foreach($taxonomies as $k => $v){
		$taxonomies[$k] = $v['label'].' ('.implode(', ', $v['posttypes']).')';
	}
	
	$pos = array_search($item, array_keys($taxonomies));
	if(!empty($pos)) {
		$cut = array_splice($taxonomies, $pos, 1);
		$taxonomies = $cut + $taxonomies;
	}

	return $taxonomies;
}

/////////////////////////////////////
// Miscellaneous Shortcode Functions
/////////////////////////////////////

// The types of Posts
function pagelayer_post_types($page = false){
	
	// Get the types
	$args = array('public' => TRUE);	
	$types = get_post_types($args, 'objects');
	
	// Unset Page if not required
	if($page == false){
		unset($types['page']);
	}
	
	// Remove Attachment types !
	unset($types['attachment']);
	
	foreach($types as $name => $type){
		$return[$name] = $type->labels->singular_name;
	}
	
	return $return;
}

// Get all posts and pages list
function pagelayer_get_posts($args = array()){
	
	if(empty($args)){
		$args = array_keys(pagelayer_post_types(true));
	}
	
	$posts_list = array();
	
	// Get type
	foreach($args as $p){
		
		// Create post list
		foreach(get_posts(['post_type' => $p]) as $post){
			$posts_list[$post->ID] = $post->post_title;
		}
	}
	
	return $posts_list;
}

// Get Menu List()
function pagelayer_get_menu_list($return_def = false){

	$menus = wp_get_nav_menus();
	$nav_menu = array(
		'0' => __('-- Select Menu --')
	);

	$default = $menus[0]->term_id;

	foreach ( $menus as $menu ) {
	$nav_menu[$menu->term_id] = $menu->name;

		if($default > $menu->term_id){
			$default = $menu->term_id;
		}
	}
	
	if($return_def){
		return $default;
	}
	
	return $nav_menu;
	
}

// Animated Heading
function pagelayer_sc_anim_heading(&$el){
	
	$el['atts']['rotate_html'] = '';
	
	//Creates html for rotating text
	if(!empty($el['atts']['rotate_text'])){
		
		$rotate_text = '';
		$rotate_text = explode(',', $el['atts']['rotate_text']);
		
		$el['atts']['rotate_html'] .= '<div class="pagelayer-animated-heading pagelayer-rotating-text pagelayer-words-wrapper">';
		//print_r($rotate_text);
		foreach($rotate_text as $key => $val){
			//print_r($key);
			$el['atts']['rotate_html'] .= '<span';
			if( $key == 0){
				$el['atts']['rotate_html'] .= ' class="pagelayer-is-visible"';
			}
			$el['atts']['rotate_html'] .= '>' . $rotate_text[$key] . '</span>';
		}
		
		$el['atts']['rotate_html'] .= '</div>';
	   
	}
	
	//Required classes for particular rotate
	$el['atts']['rotate_req'] = '';
	$letters = ['pagelayer-aheading-rotate2', 'pagelayer-aheading-rotate3', 'pagelayer-aheading-scale'];
	
	if(!empty($el['atts']['animations'])){
		if(in_array($el['atts']['animations'], $letters)){
			$el['atts']['rotate_req'] = 'letters ';
		}
		
		if($el['atts']['animations'] == 'clip'){
			$el['atts']['rotate_req'] = 'is-full-width ';
		}
	}
	
}

// Contact Form
function pagelayer_sc_contact(&$el){
	$el['atts']['grecaptcha'] = get_option('pagelayer_google_captcha');
	
	if(!empty($el['atts']['captcha'])){
		if(!wp_script_is('pagelayer_cap_script', 'registered')){
			$pagelayer_cap_lang = get_option('pagelayer_google_captcha_lang');
			$lang = empty($pagelayer_cap_lang) ? '' : '&hl='.$pagelayer_cap_lang;						
			wp_register_script('pagelayer_cap_script', "https://www.google.com/recaptcha/api.js?render=explicit$lang", array(), PAGELAYER_VERSION, true);
		}
		
		wp_enqueue_script('pagelayer_cap_script');
	}
}

// Contact Form Item
function pagelayer_sc_contact_item(&$el){
	$html = ''; 
	$options = array();
	$placeholder = '';
	$required = '';

	if(!empty($el['atts']['required'])){
		$required = 'required';
	}

	if(!empty($el['atts']['label_name']) && empty($el['atts']['label_as_holder'])){
		$html = '<label for="'.@$el['atts']['field_name'].'"><span class="pagelayer-form-label">'.$el['atts']['label_name'].'</span>';
				
		if(!empty($required)){
			$html .= ' *';
		}
		
		$html .= '</label>';
	}
		
	if(!empty($el['atts']['label_as_holder'])){
		$placeholder = $el['atts']['label_name'];
	}else{
		if(!empty($el['atts']['placeholder'])) $placeholder = $el['atts']['placeholder'];
	}
	
	// File accept
	$file_accept = '.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.odt,.avi,.ogg,.m4a,.mov,.mp3,.mp4,.mpg,.wav,.wmv';
	
	if(!empty($el['atts']['accept_file'])){
		$file_accept = $el['atts']['accept_file'];
	}
	
	if($el['atts']['field_type'] == 'select'){
		
		$html .= '<select name="'.$el['atts']['field_name'].'" '.$required.'>';
		
		if(!empty($el['atts']['label_name']) && !empty($el['atts']['label_as_holder'])){
			$html .= '<option value="" disabled selected>'.$el['atts']['label_name'].'</option>';
		}else{
			$html .= '<option value="" disabled selected>---</option>';
		}
		
		if(!empty($el['atts']['values'])){
			$options = explode("\n", $el['atts']['values']);
			for($x = 0; $x < sizeof($options); $x++){
				$html .= '<option value="'.trim($options[$x]).'" >'.trim($options[$x]).'</option>';
			}
		}
		$html .= '</select>';
	}elseif($el['atts']['field_type'] == 'checkbox'){
		$html .= '<div class="pagelayer-radcheck-holder pagelayer-contact-checkbox" '.$required.'>';
		if(!empty($el['atts']['values'])){
			$options = explode("\n", $el['atts']['values']);
			for($x = 0; $x < sizeof($options); $x++){
				$html .= '<div><input type="checkbox" id="'.$el["id"].trim($options[$x]).'" name="'.$el['atts']['field_name'].'[]" value="'.trim($options[$x]).'"/><label for="'.$el["id"].trim($options[$x]).'" class="pagelayer-form-label">'.trim($options[$x]).'</label></div>';
			}
		}
		$html .= '</div>';
	}elseif($el['atts']['field_type'] == 'radio'){
		$html .= '<div class="pagelayer-radcheck-holder">';
		if(!empty($el['atts']['values'])){
			$options = explode("\n", $el['atts']['values']);
			for($x = 0; $x < sizeof($options); $x++){
				$html .= '<div><input type="radio" name="'.$el['atts']['field_name'].'"'.$required.' value="'.trim($options[$x]).'"/><span>
				'.trim($options[$x]).'</span></div>';
			}
		}
		$html .= '</div>';
	}elseif($el['atts']['field_type'] == 'textarea'){
		$html .= '<textarea name="'.$el['atts']['field_name'].'" rows="'.$el['atts']['textarea_rows'].'" '.$required.' placeholder="'.$placeholder.'"></textarea>';
	}elseif($el['atts']['field_type'] == 'file'){
		$html .= '<input type="'.$el['atts']['field_type'].'" '.$required.' name="'.$el['atts']['field_name'].'" accept="'.$file_accept.'" />';
	}elseif($el['atts']['field_type'] == 'label'){
		$html .= '';
	}else{
		$html .= '<input type="'.$el['atts']['field_type'].'" '.$required.' placeholder="'.$placeholder.'" name="'.$el['atts']['field_name'].'" />';
	}
	
	$el['atts']['fieldhtml'] = $html;
}

// Featured Image Handler
function pagelayer_sc_featured_img(&$el){
	
	// Image size
	if(!empty($el['atts']['size'])){
		$size = $el['atts']['size'];
	}
	
	$thumbnail_id = get_post_thumbnail_id();
	$title = $alt = '';
	if(!empty($thumbnail_id)){
		$title = get_the_title($thumbnail_id);
		$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
	}
	
	if($size){
		$src = get_the_post_thumbnail_url(null, $size);
	}else{
		$src = get_the_post_thumbnail_url();
	}
	
	// Fallback image
	if(empty($src) && !empty($el['atts']['img'])){
		$src = !empty($el['tmp']['img-'.$size.'-url']) ? @$el['tmp']['img-'.$size.'-url'] : @$el['tmp']['img-url'];
		$alt = $el['tmp']['img-alt'];
		$title = $el['tmp']['img-title'];
	}
	
	$el['atts']['img_html'] = '';
	
	if(!empty($src)){
		$el['atts']['img_html'] = '<img class="pagelayer-img" src="'.$src.'" title="'.$title.'" alt="'.$alt.'" />';
	}elseif(pagelayer_is_live_template()){
		$el['atts']['img_html'] = '<img class="pagelayer-img" src="'.PAGELAYER_URL.'/images/default-image.png" />';
	}
	
	// What is the link ?
	if(!empty($el['atts']['link_type'])){
		
		// Custom url
		if($el['atts']['link_type'] == 'custom_url'){
			// Backward compatibility for new link props
			pagelayer_add_link_backward($el, array( 'rel' => '', 'selector' => '.pagelayer-ele-link'));
			$el['atts']['func_link'] = empty($el['tmp']['link']) ? '' : $el['tmp']['link'];
		}
		
		// Link to the media file itself
		if($el['atts']['link_type'] == 'media_file' || $el['atts']['link_type'] == 'lightbox'){
			$el['atts']['func_link'] = $src;
		}
		
	}
	
}

// Site Title Handler
function pagelayer_sc_wp_title(&$el){
	
	// Decide the image URL
	$el['atts']['func_image'] = @$el['tmp']['id-'.$el['atts']['id-size'].'-url'];
	$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$el['tmp']['id-url'] : $el['atts']['func_id'];
	
	// Default Logo
	if(empty($el['atts']['logo_img_type'])){
		
		// Load it
		$logo = pagelayer_site_logo();
		
		// Only if we get it
		if(!empty($logo)){
			
			$el['atts']['func_image'] = @$logo[$el['atts']['logo_img_size'].'-url'];
			$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$logo['url'] : $el['atts']['func_image'];
			$el['tmp']['logo_img-title'] = @$logo['title'];
			$el['tmp']['logo_img-alt'] = @$logo['alt'];
		}		
	
	// Custom logo
	}else{
		
		$el['atts']['func_image'] = @$el['tmp']['logo_img-'.$el['atts']['logo_img_size'].'-url'];
		$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$el['tmp']['logo_img-url'] : $el['atts']['func_image'];
		
		$el['tmp']['logo_img-title'] = @$el['tmp']['logo_img-title'];
		$el['tmp']['logo_img-alt'] = @$el['tmp']['logo_img-alt'];
		
	}
}

// Primary menu Handler 
function pagelayer_sc_wp_menu(&$el){
	
	if(!empty($el['atts']['inside_mega'])){
		$el['atts']['nav_menu'] = '';
		
		if(pagelayer_is_live()){
			$el['atts']['nav_menu'] = 'Primary Menu Holder. Please select the correct menu.';
		}
		
		return true;
	}
	
	// Setting default toggle icon. If the icon is empty.
	if(empty($el['atts']['menu_toggle_icon'])){
		$el['atts']['menu_toggle_icon'] = 'fas fa-bars';
	}
	
	// Load Pagelayer nav menu walker
	include_once(PAGELAYER_DIR.'/main/nav_walker.php');
		
	$el['atts']['nav_menu'] = wp_nav_menu( array(
		'menu'   => wp_get_nav_menu_object(@$el['atts']['nav_list']),
		'menu_id' => @$el['atts']['nav_list'],
		'menu_class' => 'pagelayer-wp_menu-ul',
		'walker' => new Pagelayer_Walker_Nav_Menu(),
		//'theme_location' => 'primary',
		'echo'	 => false,
	) );
}

// Post Navigation Handler
function pagelayer_sc_post_nav(&$el){
	
	$in_same_term = false;
	$taxonomies = 'category';
	$title = '';
	$arrows_list = $el['atts']['arrows_list'];
	
	if(!empty($el['atts']['in_same_term'])){
		$in_same_term = true;
		$taxonomies = $el['atts']['taxonomies'];
	}
	
	if(!empty($el['atts']['post_title'])){
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
}

// Comments Handler
function pagelayer_sc_post_comment(&$el){
	global $post;
	
	// Is it custom ?
	if($el['atts']['post_type'] == 'custom' && !empty($el['atts']['post_id'])){
		$orig_post = $post;
		$post = get_post($el['atts']['post_id']);
	}
	
	$post_id = $post->ID;
	//echo $post_id.' - '.$el['atts']['post_id'];
	
	if ( comments_open($post_id) || get_comments_number($post_id) ) {
		
		// Handel comments template echo  
		ob_start();
		comments_template();
		
		$el['atts']['post_comment'] =  '<div class="pagelayer-comments-template">'.ob_get_clean().'</div>';	
		
		// Comments are now closed
		if(!comments_open($post_id)){
			$el['atts']['post_comment'] = '<div class="pagelayer-comments-close">
			<h2>Comments are closed!</h2>
		</div>';
		}
		
	}else{
		$el['atts']['post_comment'] = '';
	}
	
	if(pagelayer_is_live_template() || $post->post_type == 'pagelayer-template'){
		$el['atts']['post_comment'] = '<div class="pagelayer-comments-close">
			<center><h4>Comments section !</h4></center>
		</div>';
	}
	
	if(!empty($orig_post)){
		$post = $orig_post;
	}
	
}

// post navigation Handler
function pagelayer_sc_post_info_list(&$el){
	
	global $post;
	
	$el['atts']['post_info_content'] ='';

	switch($el['atts']['type']){
		case 'author':
			
			$author_id = get_the_author_meta( 'ID' ) ? get_the_author_meta( 'ID' ) : $GLOBALS['post']->post_author;
			
			$el['atts']['link'] = get_author_posts_url( $author_id );
			$el['atts']['avatar_url'] = get_avatar_url( $author_id, 96 );
			$el['atts']['post_info_content'] = get_the_author_meta( 'display_name', $author_id );
			break;

		case 'date':
		
			$format = [
				'default' => 'F j, Y',
				'0' => 'F j, Y',
				'1' => 'Y-m-d',
				'2' => 'm/d/Y',
				'3' => 'd/m/Y',
				'custom' => empty( $el['atts']['date_format_custom'] ) ? 'F j, Y' : $el['atts']['date_format_custom'],
			];

			$el['atts']['post_info_content'] = get_the_time( $format[ $el['atts']['date_format'] ] );
			$el['atts']['link'] = get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) );
				
			break;

		case 'time':
		
			$format = [
				'default' => 'g:i a',
				'0' => 'g:i a',
				'1' => 'g:i A',
				'2' => 'H:i',
				'custom' =>  empty( $el['atts']['time_format_custom'] ) ? 'F j, Y' : $el['atts']['time_format_custom'],
			];
			$el['atts']['post_info_content'] = get_the_time( $format[ $el['atts']['time_format'] ] );
			
			if(!pagelayer_is_live_template()){
				$el['atts']['link'] = 'javascript:void(0)';
				$el['atts']['info_link'] = 1;
			}
			
			break;

		case 'comments':
		
			$el['atts']['post_info_content'] = (int) get_comments_number();
			$el['atts']['link'] = get_comments_link();
			
			// Comments are closed then dont show !
			if(pagelayer_is_live_template() || $GLOBALS['post']->post_type == 'pagelayer-template'){
				$el['atts']['post_info_content'] = 1;
			}elseif(!comments_open($post->ID)){
				$el['atts']['post_info_content'] = '';
			}
			
			break;

		case 'terms':
		
			$taxonomy = $el['atts']['taxonomy'];
			$terms = wp_get_post_terms( get_the_ID(), $taxonomy );
			foreach ( $terms as $term ) {
					$el['atts']['post_info_content'] .= ' <a href="'. get_term_link( $term ) .'"> '. $term->name .' </a>';
			}
			
			if(pagelayer_is_live_template() || $GLOBALS['post']->post_type == 'pagelayer-template'){
				$el['atts']['post_info_content'] .= '<a>Dummy '.ucfirst(str_replace('_', ' ', $el['atts']['taxonomy'])).'</a>';
			}
			
			$el['atts']['info_link'] = '';
			break;

		case 'custom':
		
			$el['atts']['post_info_content'] = $el['atts']['type_custom'];
			$el['atts']['link'] = $el['atts']['info_custom_link'];

			break;
	}
	

}

// Post Content Handler - 2C
function pagelayer_sc_post_content(&$el){	
	static $did_posts = [];
	
	global $pagelayer;
	
	$post_obj = get_post();
	
	if(empty( $post_obj )){
		return false;
	}
	
	if ( post_password_required( $post_obj->ID ) && !pagelayer_is_live() ) {
		$el['atts']['post_content'] = get_the_password_form( $post_obj->ID );
		return;
	}
		
	// Avoid recursion
	if ( isset( $did_posts[$post_obj->ID] ) || pagelayer_is_live_template($post_obj) ) {
		$el['atts']['post_content'] = '<div style="min-height:20px;background-color:#e3e3e3;text-align:center">Post Content Holder</div>';
		return;
	}
	
	// Is it an attachment
	if(is_attachment()){
		$el['atts']['post_content'] = '<center>'.wp_get_attachment_image( get_the_ID(), 'full' ).'</center>';
		return;
	}
	
	// To prevent recursion, set it to True
	$did_posts[$post_obj->ID] = true;
	
	$content = $post_obj->post_content;
	
	// If we are rendering a template and this post content is being edited live, then dont_make_editable is set true in pagelayer_get_post_content. Hence we need to set it as false and revert it after rendering the posts content !
	if(!empty($pagelayer->dont_make_editable)){
		$reset = $pagelayer->dont_make_editable;
		$pagelayer->dont_make_editable = false;
		
		// While rendering post content inside the template 
		$rendering_template_id = $pagelayer->rendering_template_id;
		$pagelayer->rendering_template_id = $post_obj->ID;
	}
	
	// Split to pages.
	setup_postdata( $post_obj );
	
	$content = apply_filters( 'the_content', get_the_content() );
	
	if(!empty($reset)){
		$pagelayer->rendering_template_id = $rendering_template_id;
		$pagelayer->dont_make_editable = $reset;
	}
	
	$content .= wp_link_pages( array(
			'before' => '<p class="post-nav-links pagelayer-page-links"><span class="page-links-title pagelayer-page-links-title">' . __( 'Pages:') . '</span>',
			'after' => '</p>',
			'echo' => 0,
		)
	);
	
	$el['atts']['post_content'] = $content;
}

// Archive Posts shows the posts as per the QUERY of the current page
function pagelayer_sc_archive_posts(&$el){
	global $wp_query, $pagelayer;
	
	$query_args = $wp_query->query_vars;
	
	if(pagelayer_is_live() || (wp_doing_ajax() && @$_REQUEST['action'] == 'pagelayer_archive_posts_data')){
		$query_args = ['post_type' => 'post'];
		$dummy_pagination = 10;
	}
	
	$allow_param = array('show_thumb', 'thumb_size', 'show_content', 'show_title', 'more', 'btn_type', 'size', 'icon_position', 'icon', 'show_more', 'meta_sep', 'exc_length', 'thumb_img_type', 'infinite_types' );
	
	$param = array();
	
	foreach($allow_param as $val){
		$param[$val] = !empty($el['atts'][$val]) ?  $el['atts'][$val] : '';
	}
	
	if(!empty($el['atts']['meta'])){
		
		$meta_arr = explode(',',$el['atts']['meta']);
		//pagelayer_print($el['atts']['meta']);
		foreach($meta_arr as $arr){
			$param[$arr] = $arr;
		}
		
	}
	
	if(!empty($el['atts']['thumb_img_type'])){
		$img_size = @$el['tmp']['def_thumb_img-'.$el['atts']['thumb_size'].'-url'];
		$param['def_thumb_img'] = empty($img_size) ? @$el['tmp']['def_thumb_img-url'] : $img_size;	
	}
	
	$el['atts']['pagelayer_pagination_top'] = '';
	$el['atts']['pagelayer_pagination_bottom'] = '';
		
	if(!empty($el['atts']['pagination']) && empty($el['atts']['infinite_types'])){	
		// Create array for pagination
		$pagination = array(
			'prev_next'				=> $el['atts']['pagination'] == 'number' ? false : true,
			'prev_text'				=> __( @$el['atts']['pagi_prev_text'] ),
			'next_text'				=> __( @$el['atts']['pagi_next_text'] ),
			'end_size'				=> $el['atts']['pagi_end_size'],
			'mid_size'				=> $el['atts']['pagi_mid_size'],
			'before_page_number'	=> @$el['atts']['before_page_number'],
			'after_page_number'		=> @$el['atts']['after_page_number'],
		);
		
		if(!empty($dummy_pagination)){
			$pagination['total'] = 10;
		}

		if( @$el['atts']['pagination_on'] == 'top'){
			$el['atts']['pagelayer_pagination_top'] = '<div class="pagelayer-pagination">'.paginate_links($pagination).'</div>';
		}else{
			$el['atts']['pagelayer_pagination_bottom'] = '<div class="pagelayer-pagination">'.paginate_links($pagination).'</div>';
		}
	}

	if(wp_doing_ajax() && @$_REQUEST['action'] == 'pagelayer_infinite_posts'){
		$query_args['paged'] = $el['atts']['paged'];
	}else{
		$data = array('tag' => $el['tag'], 'atts' => $el['oAtts']);
		$pagelayer->localScript['pagelayer_post_'.$el['id']] = $data;
	}

	$el['atts']['pagelayer_archive_posts'] = pagelayer_posts($param, $query_args);
}

// Flipbox handler
function pagelayer_sc_flipbox(&$el){
	
	// Flipbox front heading image 
	if(!empty($el['atts']['heading_image'])){		
		$el['atts']['func_image'] = @$el['tmp']['heading_image-'.$el['atts']['heading_image_size'].'-url'];
		$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$el['tmp']['heading_image-url'] : $el['atts']['func_image'];
	}
	
	$back = @$el['atts']['back_section'];
	if(pagelayer_is_live() && !empty($back)){
		$el['attr'][] = 'back_section="'.$back.'"';
	}
}

// Countdown handler
function pagelayer_sc_countdown(&$el){
	$exp = @$el['atts']['display_expired_text'];
	if(pagelayer_is_live() && !empty($exp)){
		$el['attr'][] = 'display_expired_text="'.$exp.'"';
	}
	
	if(!isset($el['atts']['custom_label_text'])){
		$el['atts']['days_label_text'] = 'Days';
		$el['atts']['hours_label_text'] = 'Hours';
		$el['atts']['minutes_label_text'] = 'Minutes';
		$el['atts']['seconds_label_text'] = 'Seconds';
	}
}

// Social Share Handler
function pagelayer_sc_share(&$el){
	
	if(empty($el['atts']['icon'])){
		return;
	}
	
	$profileName = '';
	
	if(isset($el['atts']['custom_profile'])){
		$profileName = $el['atts']['custom_profile'].'/';
	}
	
	$icon_splited = explode(' fa-', $el['atts']['icon']);
	$el['classes'][] = ['.pagelayer-share-content' => 'pagelayer-'.$icon_splited[1]];
	
	$icon = $icon_splited[1];
	
	$labelList = array(
		'Facebook' => array(
			'icons' => array('facebook', 'facebook-f', 'facebook-messenger', 'facebook-square', 'facebook-official'),
			'url' => 'https://www.facebook.com/sharer/sharer.php?u='
		),
		'Twitter' => array(
			'icons' => array('twitter', 'twitter-square'),
			'url' => 'https://twitter.com/intent/tweet?url='
		),
		'Google+' => array(
			'icons' => array('google-plus', 'google-plus-square', 'google-plus-g'),
			'url' => 'https://plus.google.com/share?url='
		),
		'Instagram' => array(
			'icons' => array('instagram'),
			'url' => 'https://www.instagram.com/'.$profileName,
			'no' => 1
		),
		'Linkedin' => array(
			'icons' => array('linkedin', 'linkedin-in', 'linkedin-square'),
			'url' => 'https://www.linkedin.com/shareArticle?url='
		),
		'pinterest' => array(
			'icons' => array('pinterest', 'pinterest-p', 'pinterest-square'),
			'url' => '//www.pinterest.com/pin/create/button/?url='
		),
		'Reddit' => array(
			'icons' => array('reddit-alien', 'reddit-square', 'reddit'),
			'url' => 'https://reddit.com/submit?url='
		),
		'Skype' => array(
			'icons' => array('skype'),
			'url' => 'https://web.skype.com/share?',
			'no' => 1
		),
		'Stumbleupon' => array(
			'icons' => array('stumbleupon', 'stumbleupon-circle'),
			'url' => 'https://www.stumbleupon.com/submit?url='
		),
		'Telegram' => array(
			'icons' => array('telegram', 'telegram-plane'),
			'url' => 'https://t.me/share/url?url='
		),
		'Tumblr' => array(
			'icons' => array('tumblr', 'tumblr-square'),
			'url' => 'https://www.tumblr.com/share/link?url='
		),
		'VK' => array(
			'icons' => array('vk'),
			'url' => 'http://vk.com/share.php?url='
		),
		'Weibo' => array(
			'icons' => array('weibo'),
			'url' => 'http://service.weibo.com/share/share.php?url='
		),
		'WhatsApp' => array(
			'icons' => array('whatsapp', 'whatsapp-square'),
			'url' => 'whatsapp://send?text='
		),
		'WordPress' => array(
			'icons' => array('wordpress', 'wordpress-simple'),
			'url' => 'https://wordpress.com/press-this.php?u='
		),
		'Xing' => array(
			'icons' => array('xing', 'xing-square'),
			'url' => 'https://www.xing.com/spi/shares/new?url='
		),
		'Delicious' => array(
			'icons' => array('delicious'),
			'url' => 'https://delicious.com/save?v=5&noui&jump=close&url='
		),
		'Dribbble' => array(
			'icons' => array('dribbble', 'dribbble-square'),
			'url' => 'https://dribbble.com/shots/'.$profileName,
			'no' => 1
		),
		'Pocket' => array(
			'icons' => array('get-pocket'),
			'url' => 'https://getpocket.com/edit?url='
		),
		'Email' => array(
			'icons' => array('envelope', 'envelope-open', 'envelope-o'),
			'url' => 'mailto:?body='
		)
	);
		
	if(!empty($el['atts']['text'])){
		$el['atts']['icon_label'] = $el['atts']['text'];
	}else{
		foreach($labelList as $key => $val){
			if(in_array($icon, $val['icons'])){
				$el['atts']['icon_label'] = $key;
				break;
			}
		}
	}
	
	foreach($labelList as $key => $val){
		if(in_array($icon, $val['icons'])){
			if(empty($val['no'])){
				$el['atts']['social_url'] = $val['url'].$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			}else{
				$el['atts']['social_url'] = $val['url'];
			}
			break;
		}
	}
}

// If the excerpt for current post is there only then give
function pagelayer_get_the_excerpt(){
	
	global $post;
	
	if(!empty($post->post_excerpt)){
		return $post->post_excerpt;
	}
	
	return '';
}

function pagelayer_sc_post_excerpt(&$el){
	
	$el['tmp']['post_excerpt'] = '';
	
	$tmp_excerpt = pagelayer_get_the_excerpt();
	
	if(!empty($tmp_excerpt) && strlen($tmp_excerpt) > 0){
		$el['tmp']['post_excerpt'] = '<div class="pagelayer-post-excerpt">'.$tmp_excerpt.'</div>';
	}
	
	if(pagelayer_is_live_template()){
		$el['tmp']['post_excerpt'] = '<div class="pagelayer-post-excerpt pagelayer-empty-widget"></div>';
	}
}

function pagelayer_sc_copyright(&$el){
	$el['atts']['copyright_text'] = pagelayer_get_option('pagelayer-copyright');	
	$el['oAtts']['copyright_text'] = $el['atts']['copyright_text'];	
}

// Pricing Handler
function pagelayer_sc_pricing(&$el){
	//Backward compatibility for new link props	
	pagelayer_add_link_backward($el, array( 
		'link' => 'button_url',
		'rel' => '',
		'target' => 'button_url_target',
		'selector' => '.pagelayer-pricing-btn'
	));
}

// Post Title
function pagelayer_sc_post_title(&$el){
	$el['atts']['open_html_tag'] = !empty($el['atts']['html_tag']) ? '<'.$el['atts']['html_tag'].'>' :'';
	$el['atts']['close_html_tag'] = !empty($el['atts']['html_tag']) ? '</'.$el['atts']['html_tag'].'>' :'';
}