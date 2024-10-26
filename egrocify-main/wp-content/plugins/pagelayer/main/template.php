<?php

//////////////////////////////////////////////////////////////
//===========================================================
// template.php
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

// Define our array
function pagelayer_builder_array(){

global $pagelayer;

	// Array for custom post type data
	$pagelayer->builder = array(
		'name' => 'pagelayer-template',
		'type_slug' => 'pagelayer_template_type',
		'type'=> [
			'' => __('Select'),
			'header' => __('Header'),
			'footer' => __('Footer'),
			'single' => array(
				'label' => __('Single'),
				'need_selection' => 'singular', // To select the value in template select and hide it
			),
			'archive' => array(
				'label' => __('Archive'),
				'need_selection' => 'archives', // To select the value in template select and hide it
			),
			'popup' => __('Popup'),
			'section' => array(
				'label' => __('Section'),
				'no_condition' => 1, // To hide the conditions options
			),
			'global_section' => array(
				'label' => __('Global Section'),
				'no_condition' => 1, // To hide the conditions options
				'hide_drop' => 1,
			),
			'global_widget' => array(
				'label' => __('Global Widget'),
				'no_condition' => 1, // To hide the conditions options
				'hide_drop' => 1,
			),
		],
		'action' => [
			'include' => __('Include'),
			'exclude' => __('Exclude'),
		],
		'dispay_on' => [
			'' => __('Full Site'),
			'archives' => array(
				'label' => __('Archives'),
				'check_conditions' => function ( $args = null ){ 
					return is_archive() || is_home() || is_search();
				},
			),
			'singular' => array(
				'label' => __('Singular'),
				'check_conditions' => function ( $args = null ){
					return ( is_singular() && ! is_embed() ) || is_404();
				},
			),
		],
		// NOTE: Templates list array name should be dispay_on name with suffix _templates
		'archives_templates'=> [ 
			'' => __('All Archives / Posts Archive'),
			'author' => array(
				'label' => __('Author Archives'),
				'check_conditions' => function ( $args = null ){
					return is_author($args['id']);
				},
				'filter_type' => 'author', // should be (author, taxonomy and post type)
			),
			'date' => array(
				'label' => __('Date Archives'),
				'check_conditions' => function ( $args = null ){
					return is_date();
				},
				'no_id_section' => 1, // ID select section not required
			),
			'search' => array(
				'label' => __('Search Results'),
				'check_conditions' => function ( $args = null ){
					return is_search();
				},
				'no_id_section' => 1, // Id select section not required
			),
		],
		// NOTE: Templates list array name should be dispay_on name with suffix _templates
		'singular_templates'=> [
			'' => __('All Singular'), // singular.php
			'by_author' =>  array(
				'label' => __('By Author'),
				'check_conditions' => function ( $args = null ){
					return is_singular() && get_post_field( 'post_author' ) === $args['id'];
				},
				'filter_type' => 'author',
			),
			'front_page' =>  array(
				'label' => __('Front Page'),
				'check_conditions' => function ( $args = null ){
					return is_front_page();
				},
				'no_id_section' => 1, // Id select section not required
			),
			'404' => array(
				'label' => __('404'),
				'check_conditions' => function ( $args = null ){
					return is_404();
				},
				'no_id_section' => 1, // Id select section not required
			),
			//'direct_child_of' =>  __('Direct Child Of'),
			//'any_child_of' =>  __('Any Child Of'),
		], // conditions by post type
		
	);
	
	add_shortcode($pagelayer->builder['name'], 'pagelayer_builder_posts_shortcode');
	
	add_filter( 'manage_'. $pagelayer->builder['name'] .'_posts_columns', 'pagelayer_builder_add_columns' );
	
	add_action( 'manage_'. $pagelayer->builder['name'] .'_posts_custom_column' , 'pagelayer_builder_columns', 10, 2 );
	
	add_filter( 'views_edit-' . $pagelayer->builder['name'], 'pagelayer_builder_template_tabs' );

}

// Create template builder conditions for singular by post type
function pagelayer_builder_singular_conditions($post_types = []){
	$condition = [];
	
	if(empty($post_types)){
		$post_types = pagelayer_get_public_post_types();
		//unset( $post_types['product'] ); // WooCommerce related
		$post_types['attachment'] = get_post_type_object( 'attachment' )->label;
	}
	
	foreach ( $post_types as $post_type => $label ) {
		//$post_type_object = get_post_type_object( $post_type );
		
		if(array_key_exists($label, $condition)){
			continue;
		}
		
		$condition[$label] = [ //TC: Need to change the name of variable 
			$post_type =>  array(
				'label' => $label,
				'check_conditions' => function ( $args = null ) use ($post_type){
					if ( isset($args['id']) && !empty($args['id']) ) {
						$id = (int) $args['id'];
						if ( $id ) {
							return is_singular($post_type) && get_queried_object_id() === $id;
						}
					}

					return is_singular( $post_type );
				},
				'filter_type' => 'post',
				'object_type' => $post_type,
			),
		];
			
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$post_taxonomies = wp_filter_object_list( $taxonomies, [
			'public' => true,
			'show_in_nav_menus' => true,
		] );
		
		foreach ( $post_taxonomies as $slug => $object ) {
			
			$condition[$label] += [
				$object->name =>  array(
					'label' => sprintf( __( 'In %s' ), $object->labels->singular_name ),
					'check_conditions' => function ( $args = null ) use ($object){
						return is_singular() && has_term( (int) $args['id'], $object->name );
					},
					'filter_type' => 'taxonomy',
					'object_type' => $object->name,
				),
			];
			
			if ( $object->hierarchical ) {
				$condition[$label] += [
					'in_' . $object->name . '_children' =>  array(
						'label' => sprintf( __( 'In Child %s' ), $object->labels->name ),
						'check_conditions' => function ( $args = null ) use ($object){
							$id = (int) $args['id'];
							if ( ! is_singular() || ! $id ) {
								return false;
							}
							$child_terms = get_term_children( $id, $object->name );

							return ! empty( $child_terms ) && has_term( $child_terms, $object->name );
						},
						'filter_type' => 'taxonomy',
						'object_type' => $object->name,
					),
				];
			}

		}

		$condition[$label] += [
			$object->name . '_by_author' =>  array(
				'label' => sprintf( __( '%s By Author' ), $label ),
				'check_conditions' => function ( $args = null ) use ($post_type){
					return is_singular($post_type) && get_post_field( 'post_author' ) === $args['id'];
				},
				'filter_type' => 'author',
				'object_type' => $post_type . '_by_author',
			),
		];

	}
	
	return $condition;
}

// Create template builder conditions for archives by  post type
function pagelayer_builder_archives_conditions($post_types = []){
	
	$condition = [];
	
	if(empty($post_types)){
		$post_types = pagelayer_get_public_post_types();
		//unset( $post_types['product'] );
	}
	
	foreach ( $post_types as $post_type => $label ) {
		
		//$post_type_object = get_post_type_object( $post_type );
		if ( ! get_post_type_archive_link( $post_type ) ) {
			continue;
		}
		
		$condition[$label] = [ //TC: Need to change the name of variable 
			$post_type =>  array(
				'label' => $label,
				'check_conditions' => function ( $args = null ) use ($post_type){
					return is_post_type_archive( $post_type ) || ( 'post' === $post_type && is_home() );
				},
				'no_id_section' => 1, // Id select section not required
			),
		];
			
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$post_taxonomies = wp_filter_object_list( $taxonomies, [
			'public' => true,
			'show_in_nav_menus' => true,
		] );
		
		foreach ( $post_taxonomies as $slug => $object ) {
			
			$condition[$label] += [
				$object->name =>  array(
					'label' => sprintf( __( '%s' ), $object->label ),
					'check_conditions' => function ( $args = null ) use ($object){
						$taxonomy = $object->name;
						$id = (int) $args['id'];

						if ( 'category' === $taxonomy ) {
							return is_category( $id );
						}

						if ( 'post_tag' === $taxonomy ) {
							return is_tag( $id );
						}

						return is_tax( $taxonomy, $id );
					},
					'filter_type' => 'taxonomy',
					'object_type' => $object->name,
				),
			];

		}
		
		$all = 'pagelayer_all_'.$post_type;
		
		$condition[$label] += [
			$all => array(
				'label' => 'All '.$label.' Archive',
				'check_conditions' => function ( $args = null ) use ($post_type, $condition, $all, $label){
					
					foreach($condition[$label] as $k => $v){
						if($all == $k){
							continue;
						}
						
						$ret = $v['check_conditions']($args);
						
						if(!empty($ret)){
							return $ret;
						}
						
					}
					
				},
				'no_id_section' => 1, // Id select section not required
			),
		];

	}
	
	return $condition;
}

// Handle the links for the add URL
add_action('admin_head', 'pagelayer_builder_admin_head', 9999);
function pagelayer_builder_admin_head(){
	global $pagelayer, $post_type;
	
	if($post_type != $pagelayer->builder['name']){
		return;
	}
	
	
		echo '<script type="text/javascript">

jQuery(document).ready(function(){
	var but = jQuery(".page-title-action");
	
	if(but.length < 1){
		return;
	}
	
	but.attr("href", "'.esc_url(admin_url('admin.php?page=pagelayer_template_wizard')).'");
	
	var export_but = jQuery(but[0].outerHTML);
	export_but.html("'.__pl('export_template').'");
	export_but.addClass("pagelayer-temp-export-but");
	export_but.attr("href", "'.esc_url(admin_url('admin.php?page=pagelayer_template_export')).'");
	//console.log(export_but[0].outerHTML);
	
	but.after(export_but);
});

</script>';
	
}

// This function will handle the Template Builder pages in PageLayer 
add_action('init', 'pagelayer_builder_post_type', 9999);
function pagelayer_builder_post_type() {
	global $pagelayer;
	
	// Add Template Post Supports
	$supports = array(
		'title', // post title
		'editor', // post content
		'author', // post author
		'custom-fields', // custom fields
		'revisions', // post revisions
	);
	
	// Add Template Post lables
	$labels = array(
		'name' => _x('Pagelayer Templates', 'plural'),
		'singular_name' => _x('Pagelayer Template', 'singular'),
		'menu_name' => _x('Pagelayer Templates', 'admin menu'),
		'name_admin_bar' => _x('Pagelayer Templates', 'admin bar'),
		'add_new' => _x('Add New', 'add templates'),
		'add_new_item' => __('Add New'),
		'new_item' => __('New Template'),
		'edit_item' => __('Edit Template'),
		'view_item' => __('View Template'),
		'all_items' => __('All Templates'),
		'search_items' => __('Search Templates'),
		'not_found' => __('No Pagelayer templates found'),
	);
		
	$args = array(
		'supports' => $supports,
		'labels' => $labels,
		'menu_icon' => PAGELAYER_URL.'/images/pagelayer-logo-19.png',
		'public' => true,
		'show_in_menu' => false,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => false,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => true,		
	);
	
	// Register custom post type
	register_post_type($pagelayer->builder['name'] , $args);
	
	// Add any other templates as well
	$pagelayer->builder['archives_templates'] += pagelayer_builder_archives_conditions();	
	$pagelayer->builder['singular_templates'] += pagelayer_builder_singular_conditions();
	
}

// Register shortcode for custom post type
function pagelayer_builder_posts_shortcode($atts){

	global $pagelayer;
	
	// If id not exist
	if( !isset($atts['id']) ){return '';}
	
	// Get the post data
	$post = get_post((int) $atts['id']);
	
	// If post is not empty
	if(empty($post)){return '';}
	
	$pagelayer->dont_make_editable = true;
	$content = $post->post_content;
	$content = apply_filters( 'the_content', $content );
	$pagelayer->dont_make_editable = false;
	
	return $content;
		
}

// Handle edit link of the post type
add_filter('get_edit_post_link', 'pagelayer_builder_edit_link', 1000, 3);
function pagelayer_builder_edit_link($link, $postID, $context){
	global $pagelayer;

	$post = get_post($postID);

	if($post->post_type != $pagelayer->builder['name']){
		return $link;
	}
	
	return admin_url('admin.php?page=pagelayer_template_wizard&post='.$post->ID);

}

// Handle row actions for pagelayer-template
add_filter('post_row_actions', 'pagelayer_builder_row_actions', 10, 2);
function pagelayer_builder_row_actions($actions, $post){
	global $pagelayer;
	
	if($post->post_type != $pagelayer->builder['name']){
		return $actions;
	}
	
	$actions['pagelayer'] = '<a href="'.esc_url( pagelayer_shortlink($post->ID).'&pagelayer-live=1' ).'">'.__pl('edit_using').'</a>';

	return $actions;
}

// Add the custom columns to the pagelayer-template post type:
function pagelayer_builder_add_columns($columns) {
	
	// unset for re-setup
	unset( $columns['taxonomy-pagelayer_template_type'] );
	
	$offset = 2;
	$type = array(
		'pagelayer_template_type' => __( 'Type' ),
		'pagelayer_template_conditions' => __( 'Display On' ),
		'pagelayer_template_shortcode' => __( 'Shortcode' )
	);
	
	// Add the $type array in 2 position of $columns;
	$columns = array_slice( $columns, 0, $offset, true ) + $type  + array_slice( $columns, $offset, null, true );

	//print_r($columns);die();

	return $columns;
	
}

// Add the data to the custom columns for the pagelayer-template post type:
function pagelayer_builder_columns( $column, $post_id ) {
	global $pagelayer;
	
	switch ( $column ) {
		case 'pagelayer_template_type' :
			$type = get_post_meta( $post_id, 'pagelayer_template_type', true );
			
			if(!empty($type)){
				$type = pagelayer_multi_array_search($pagelayer->builder['type'], $type);
						
				if(is_array($type) && array_key_exists('label', $type)){
					$type = $type['label'];
				}
				
				echo '<span>' . $type . '</span>';
			}else{
				echo __('None');
			}
			
		break;
		
		case 'pagelayer_template_conditions' :
			$dis_conditions = get_post_meta( $post_id, 'pagelayer_template_conditions', true );
			if( !empty($dis_conditions) && is_array($dis_conditions)){
				$dis_html = '';
				foreach($dis_conditions as $condi){
					$dis_html .= '<span>';
					
					if(isset($condi['template'])){
						$template = pagelayer_multi_array_search($pagelayer->builder['dispay_on'], $condi['template']);
						
						if(is_array($template) && array_key_exists('label', $template)){
							$template = $template['label'];
						}
						
						$dis_html .= $template;
					}
					
					if(isset($condi['sub_template'])){
						$sub_template = pagelayer_multi_array_search($pagelayer->builder[$condi['template'].'_templates'], $condi['sub_template']);
						
						if(is_array($sub_template) && array_key_exists('label', $sub_template)){
							$sub_template = $sub_template['label'];
						}
						
						if(!empty($sub_template)){
							$dis_html .= ' > '. $sub_template;
						}
					}
					
					if(!empty($condi['id'])){
						$dis_html .= ' > #'. $condi['id'];
					}
					
					$dis_html .= '</span></br>';
				}
			}
			
			if(!empty($dis_html)){
				echo  $dis_html;
			}else{
				echo __('None');
			}
			
		break;
		
		case 'pagelayer_template_shortcode' :
			
			echo '[pagelayer-template id="'.$post_id.'"]';
			
		break;
		
	}
}

// Print template tabs.
function pagelayer_builder_template_tabs( $views ) {
	global $pagelayer;
	
	$current_type = '';
	$active_class = ' nav-tab-active';

	if ( ! empty( $_REQUEST[$pagelayer->builder['type_slug']] ) ) {
		$current_type = $_REQUEST[$pagelayer->builder['type_slug']];
		$active_class = '';
	}

	$url_args = [
		'post_type' => $pagelayer->builder['name'],
	];

	$baseurl = add_query_arg( $url_args, admin_url( 'edit.php' ) );

	$template_types = $pagelayer->builder['type'];

	if ( 1 >= count( $template_types ) ) {
		return $views;
	}

	// If any pre define title
	$all_title = ''; 
	if ( ! $all_title ) {
		$all_title = __( 'All' );
	}
	
	echo '<div id="pagelayer-template-tabs-wrapper" class="nav-tab-wrapper" style="margin-bottom:15px">
		<a class="nav-tab'. $active_class .'" href="'. $baseurl .'"> '. $all_title .'</a>';
		
		foreach ( $template_types as $type  => $class_name ) {
			$active_class = '';
			
			// If type is empty
			if( empty($type) ) continue;
			
			if ( $current_type === $type ) {
				$active_class = ' nav-tab-active';
			}

			$type_url = add_query_arg( $pagelayer->builder['type_slug'], $type, $baseurl );
			$type_label = is_array($class_name) && array_key_exists( 'label', $class_name) ? $class_name['label'] : $class_name;

			echo "<a class='nav-tab{$active_class}' href='{$type_url}'>{$type_label}</a>";
		}
		
	echo '</div>';	
	
	return $views;
}

// Add filter for pagelayer template
add_action( 'parse_query', 'pagelayer_builder_query_filter_types' );
function pagelayer_builder_query_filter_types( \WP_Query $query ) {
	global $pagenow, $typenow, $pagelayer;

	if ( ! ('edit.php' === $pagenow && $pagelayer->builder['name'] === $typenow) || ! empty( $query->query_vars['meta_key'] ) ) {
		return;
	}

	if ( empty($_REQUEST[$pagelayer->builder['type_slug']]) ){
		return;
	}
	
	$current_tab = $_REQUEST[$pagelayer->builder['type_slug']];

	$template_types = $pagelayer->builder['type'];
	
	if( !array_key_exists($current_tab , $template_types )){
		return;
	}

	$query->query_vars['meta_key'] = 'pagelayer_template_type';
	$query->query_vars['meta_value'] = $current_tab;
}

// Add the button for choose template type - Remove
add_action('edit_form_after_title', 'pagelayer_builder_editor_button', 11);
function pagelayer_builder_editor_button(){
	
	global $pagelayer, $post;
	
	if($post->post_type != $pagelayer->builder['name']){
		return;
	}
	
	// Get the current screen
	$current_screen = get_current_screen();
	
	// For gutenberg - Add the code in the footer
	if(method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()){
		add_action('admin_footer', 'pagelayer_builder_gutenberg');
		return;
	}

	echo '
<div style="margin-top:15px;display:inline-block;">
	<a href="'.esc_url(admin_url('admin.php?page=pagelayer_template_wizard&post='.$post->ID)).'" class="button button-primary button-large" style="height:auto; padding:6px; font-size:15px;">
		<img src="'.PAGELAYER_URL.'/images/pagelayer-logo-40.png" align="top" width="24" /> <span>'.__('Edit Template Options').'</span>
	</a>
</div>';

}

// For gutenberg editor
function pagelayer_builder_gutenberg(){
	
	global $pagelayer, $post;
	
	echo '
<div id="pagelayer-editor-template-edit" style="margin-left:15px; display:none">
	<a href="'.esc_url(admin_url('admin.php?page=pagelayer_template_wizard&post='.$post->ID)).'" class="button button-primary button-large" style="height:auto; padding:6px; font-size:15px;">
		<img src="'.PAGELAYER_URL.'/images/pagelayer-logo-40.png" align="top" width="24" /> <span>'.__('Edit Template Options').'</span>
	</a>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	
	var pagelayer_timer;
	var pagelayer_button = function(){
		var button = jQuery("#pagelayer-editor-template-edit");
		var g = jQuery(".edit-post-header-toolbar");
		if(g.length < 1){
			return;
		}
		button.detach();
		//console.log(button);
		g.append(button);
		button.show();
		clearInterval(pagelayer_timer);
	}
	pagelayer_timer = setInterval(pagelayer_button, 100);
});
</script>';
}

// Load all templates
function pagelayer_builder_load_templates(){
	
	global $pagelayer;
	
	// Load all post types that are pagelayer-template
	$args = [
		'post_type' => $pagelayer->builder['name'],
		'status' => 'publish',
		'meta_key' => 'pagelayer_template_conditions',
		'posts_per_page' => -1 // For get all posts
	];
	
	$query = new WP_Query($args);
	//print_r($query->posts);die();
	
	// Cache the same
	$pagelayer->templates = $query->posts;

}

// Load all our templates
add_action( 'template_redirect', 'pagelayer_builder_template_redirect');
function pagelayer_builder_template_redirect(){
	
	global $pagelayer, $post;
	
	// Load all post types that are pagelayer-template
	pagelayer_builder_load_templates();
	
	// If there is a match for a header
	$pagelayer->template_header = pagelayer_builder_try_to_apply('header');
	
	// Singular style posts
	if ( is_singular() || is_404() ) {
		$pagelayer->template_post = pagelayer_builder_try_to_apply('single');
	
	// Archive style posts
	} elseif ( is_archive() || is_home() || is_search() ) {
		$pagelayer->template_post = pagelayer_builder_try_to_apply('archive');
	}
	
	// If there is a match for a footer
	$pagelayer->template_footer = pagelayer_builder_try_to_apply('footer');
		
	// If the post type is pagelayer-template, then we are viewing i.e. EDITING PAGELAYER
	if(!empty($post) && $post->post_type == $pagelayer->builder['name']){
		
		// Turn on template editor and default it to pagelayer-content
		$pagelayer->template_editor = 'pagelayer-content';
		$pagelayer->template_post = $post->ID;
		
		// The type
		$pagelayer_template_type = get_post_meta($post->ID, 'pagelayer_template_type', true);
		
		// If the type is header
		if( $pagelayer_template_type == 'header' ){
			
			$pagelayer->template_editor = 'pagelayer-header';
			$pagelayer->template_header = $post->ID;// Fill in that we are rendering the header we want to edit
			$pagelayer->template_post = 0;
			$pagelayer->template_footer = 0;
		
		// If the type is footer
		}elseif( $pagelayer_template_type == 'footer' ){
			
			$pagelayer->template_editor = 'pagelayer-footer';
			$pagelayer->template_header = 0;
			$pagelayer->template_post = 0;
			$pagelayer->template_footer = $post->ID;// Fill in that we are rendering the footer we want to edit
			
		}
		
	}
	
	// If there is a match for a popup, get all ids with priority
	$pagelayer->template_popup_ids = pagelayer_builder_try_to_apply('popup', true);
		
	// Return all the posts
	//return $query['posts'];
	
	// Remove the filter first because it was added by 
	remove_filter('template_include', 'pagelayer_template_include', 1000);
	
	// Add the filter again
	add_filter('template_include', 'pagelayer_template_include', 1000, 1);
	
	do_action('pagelayer_builder_template_redirect');

}

// For check which template will be applied
function pagelayer_builder_try_to_apply($type , $return_all = false){
	
	global $pagelayer;
	
	// Get templates id by type
	$ids = pagelayer_builder_template_ids($type);
	$sel_id = pagelayer_template_check_conditons($ids, false, $return_all);
	
	if( !empty($ids) && !empty($sel_id) ){
		return $sel_id;
	}
	
	return false;
	
}

// Get template post ids and conditions by type
function pagelayer_builder_template_ids($type){
	
	global $pagelayer;
	
	// No templates
	if(empty($pagelayer->templates)){
		return [];
	}
	
	// List of templates to return
	$id_list = array();
	foreach($pagelayer->templates as $template){
		
		// The type
		$pagelayer_template_type = get_post_meta($template->ID, 'pagelayer_template_type', true);
		
		if($type == $pagelayer_template_type){
			$id_list[] = $template->ID;
		}
		
	}
	
	return $id_list;
}

// Its Free
if(!defined('PAGELAYER_PREMIUM')){
	
	// Wizard to create / edit templates
	function pagelayer_builder_template_wizard(){
		pagelayer_show_pro_div('Theme Template Creator', 'With the Pagelayer Theme Templates wizard you can create Headers, Footers, Singular, Archives and WooCommerce Templates. It allows you to control each and every aspect of your theme. You can also set conditions for these templates !');
	}
	
	// Wizard to export theme
	function pagelayer_builder_export(){
		pagelayer_show_pro_div('Export Templates', 'With the Pagelayer Export wizard you can export your Headers, Footers, Singular, Archives and WooCommerce Templates. These templates can then be imported in any other WordPress site.');
	}
	
}

// Fix the default blog template we insert for woocommerce
function pagelayer_template_product_fix(){

	global $pagelayer;
	
	// Have we already fixed
	$fixed = get_option('pagelayer_template_product_fix');
	if(!empty($fixed)){
		return;
	}
	
	// Make sure we have templates
	pagelayer_builder_load_templates();
	
	
	if(empty($pagelayer->templates)){
		return;
	}
	
	foreach($pagelayer->templates as $k => $v){
		if($v->post_name == 'blog-template'){
			$blog = $v;
		}
	}
		
	if(empty($blog)){
		return;
	}
	
	// Get meta
	$dis_conditions = get_post_meta( $blog->ID, 'pagelayer_template_conditions', true );
	//print_r($dis_conditions);
	
	foreach($dis_conditions as $k => $v){
		if(!empty($v['sub_template']) && $v['sub_template'] == 'pagelayer_all_product'){
			$found = 1;
		}
	}
	//echo $found;return;
	
	// We need to add conditions
	if(empty($found)){
		
		$dis_conditions[] = ['type' => 'exclude',
					'template' => 'archives',
					'sub_template' => 'pagelayer_all_product',
					'id' => ''];
		
		update_post_meta( $blog->ID, 'pagelayer_template_conditions', $dis_conditions );
		
	}
	
	update_option('pagelayer_template_product_fix', time());
	
}

// Pagelayer Template Loading Mechanism
add_action('setup_theme', 'pagelayer_template_setup_theme', 5);
function pagelayer_template_setup_theme(){
	
	global $pagelayer;
	
	//$theme = wp_get_theme();	
	//$theme_tags = $theme->get('Tags');
	//print_r($theme);
	//echo $theme->get('Tags').' Get option';
	
	$theme_dir = get_stylesheet_directory();
	$conf = $theme_dir.'/pagelayer.conf';
	//echo get_template_directory();
	
	// Pagelayer based template ?
	if(file_exists($conf)){
		
		$pagelayer->cache['template'] = 1;
		$pagelayer->template_conf = @json_decode(file_get_contents($conf), true);
		
	// Not a pagelayer theme
	}else{
		return;
	}
	
	// ORDER of preference of every template
	// 1) POST ID as per conditions - Only Premium
	// 2) TPL file if there - Free and Premium when pagelayer.conf
	// 3) PHP file if no Posts - Free and Premium
	
	// Filter to finally INCLUDE and render our template
	add_filter('template_include', 'pagelayer_template_include', 1000, 1);
	
}

// Handle the template files if any
// NOTE : This has a priority of 100 while the posts based pagelayer_builder_template_redirect has a priority of 10
// If there are any post based templates, then that is given priority
add_action( 'template_redirect', 'pagelayer_template_redirect', 100);
function pagelayer_template_redirect(){
	
	global $pagelayer, $post;
	
	// If no conf, then we dont have to do anything
	if(empty($pagelayer->template_conf)){
		return;
	}
	
	// If post template was not there, search for a header PGL file
	// Also when we are editing, we can render header only when its a pagelayer-content edit
	if(	
		(empty($pagelayer->template_editor) || @$pagelayer->template_editor == 'pagelayer-content')
		 && empty($pagelayer->template_header)
	){
		$pagelayer->template_header = pagelayer_template_try_to_apply('header');
	}
	
	// If post template was not there, search for a header PGL file
	// Also when we are editing, we cannot render the template file as post is being rendered
	if(empty($pagelayer->template_editor) && empty($pagelayer->template_post)){
	
		// Singular style posts
		if ( is_singular() || is_404() ) {
			$pagelayer->template_post = pagelayer_template_try_to_apply('single');
		
		// Archive style posts
		} elseif ( is_archive() || is_home() || is_search() ) {
			$pagelayer->template_post = pagelayer_template_try_to_apply('archive');
		}
	
	}
	
	// If post template was not there, search for a footer PGL file
	// Also when we are editing, we can render footer only when its a pagelayer-content edit
	if(	
		(empty($pagelayer->template_editor) || @$pagelayer->template_editor == 'pagelayer-content')
		 && empty($pagelayer->template_footer)
	){
		$pagelayer->template_footer = pagelayer_template_try_to_apply('footer');
	}
	
}

// Is our template being rendered
function pagelayer_template_include($template){
	
	global $pagelayer;
	
	$pagelayer_enqueue_frontend = false;
	
	// If we do have a header but not the footer or we have the footer and no header,
	// then we need to make sure to blank the other
	if(!empty($pagelayer->template_header) || !empty($pagelayer->template_footer)){
		$pagelayer_enqueue_frontend = true;
		
		// Disable AIOSEO for pagelayer post types
		if(!empty($GLOBALS['post']) && $GLOBALS['post']->post_type == $pagelayer->builder['name']){
			add_filter( 'aioseo_disable', '__return_true' );
		}
		
		add_action('get_header', 'pagelayer_get_header');
		add_action('get_footer', 'pagelayer_get_footer');
	}
	
	// Handle the sidebar settings !
	//add_action('get_sidebar', 'pagelayer_get_sidebar');
	
	// If we do have Popup templates, then append it in body
	if(!empty($pagelayer->template_popup_ids) && empty($pagelayer->template_editor)){
		$pagelayer_enqueue_frontend = true;
		add_action('wp_body_open', 'pagelayer_builder_popup_append');
		add_action('wp_footer', 'pagelayer_builder_popup_append');
	}
	
	// If the post being shown to the user is not a Pagelayer post, then we need to enqueue forcefully
	if(empty($pagelayer->cache['enqueue_frontend']) && $pagelayer_enqueue_frontend){
		pagelayer_enqueue_frontend(true);
	}
	
	// Is there any post templates OR are we editing a pagelayer-template ?
	if(!empty($pagelayer->template_post) || !empty($pagelayer->template_editor)){
		$template = $pagelayer->template_post;
	}
	
	// Its our template OR are we editing a pagelayer-template, then render it
	if(pathinfo($template, PATHINFO_EXTENSION) == 'pgl' || !empty($pagelayer->template_post) || !empty($pagelayer->template_editor)){
		
		// We rendered from header to footer
		$pagelayer->from_header_to_footer = true;
		
		get_header();
		echo '<div class="pagelayer-content">';
		pagelayer_template_render($template);
		echo '</div>';
		
		// If a template needs to call the sidebar !
		if(!empty($pagelayer->template_call_sidebar)){
			get_sidebar();
		}
		get_footer();
		
		return false;
	}
	
	// Just return the original template if its not our file
	return $template;
	
}

// Expects the file to include or the POST ID
function pagelayer_template_render($template){
	
	global $pagelayer;
	
	// $template can be blank, e.g. blank header / footer
	if(empty($template)){
		return;
	}
	
	if(is_numeric($template)){
		echo pagelayer_get_post_content($template);
	}else{
		echo pagelayer_the_content(file_get_contents(get_stylesheet_directory().'/'.$template.'.pgl'));
	}
}

// For check which template will be applied
function pagelayer_template_try_to_apply($type){
	
	global $pagelayer;
	
	// Get templates id by type
	$ids = [];
	
	// Find the templates by type
	foreach($pagelayer->template_conf as $k => $v){
		if($v['type'] == $type){
			$ids[] = $k;
		}
	}
	
	$file = pagelayer_template_check_conditons($ids, true);
	
	if( !empty($ids) && !empty($file) ){
		return $file;
	}
	
	return false;
	
}

// Check conditions of template post ids / template files
function pagelayer_template_check_conditons($ids = [], $file = false, $return_all = false){
	
	global $pagelayer;
	
	$selected_templs = [];
	
	foreach( $ids as $id ){
		
		$priority  = 0;	
		$selected_template = 0;
		$exclude_check = 1;
		
		// File based
		if($file){
			$pagelayer_template_conditions = $pagelayer->template_conf[$id]['conditions'];
		
		// Post Template based
		}else{
			$pagelayer_template_conditions = get_post_meta( $id, 'pagelayer_template_conditions', true );
		}
		
		if( !empty($pagelayer_template_conditions) ){
			foreach( $pagelayer_template_conditions as $condi ){
				
				$check = 0;
				
				// Get template array
				$tmpl_array = (array) pagelayer_multi_array_search( $pagelayer->builder['dispay_on'], $condi['template'] );
				
				// Get sub_template array
				$sub_tmpl_array = (array) pagelayer_multi_array_search( $pagelayer->builder[$condi['template'].'_templates'], $condi['sub_template']);
				
				// If the condition name is general priority
				if(empty($condi['template'])){
					
					$check = 1;
					$set_prio = 1;  // Set General Property 1
					
				// If the condition name is singular
				}elseif( array_key_exists('check_conditions', $tmpl_array) ){
					
					// If the condition callback is false, continue the loop
					if( is_callable($tmpl_array['check_conditions']) ){
						if( empty($tmpl_array['check_conditions']($condi)) ){
							continue;
						}
					}elseif( empty($tmpl_array['check_conditions']) ){
						continue;
					}
					
					// Check sub_template conditions
					if( empty($condi['sub_template']) ){
						$check = 1;
						$set_prio = 2;  // Set all sub_template Property 2
					}elseif( array_key_exists('check_conditions', $sub_tmpl_array ) ){
						
						// If the condition callback is false, continue the loop 
						if( is_callable($sub_tmpl_array['check_conditions']) ){
							if( empty($sub_tmpl_array['check_conditions']($condi)) ){
								continue;
							}
						}elseif( empty($sub_tmpl_array['check_conditions']) ){
							continue;
						}
						
						$check = 1;
						
						if( !empty($condi['id']) ){
							$set_prio = 4; // Set id Property 4
						}else{
							$set_prio = 3;// Set sub_template Property 3
							// If no id section then Property 
							if(!empty($sub_tmpl_array['no_id_section'])){ $set_prio = 4; } 
						}
					}
				}
				
				// IF is set to the exclude then
				if($condi['type'] == 'exclude' && $check){
					$exclude_check = 0;
				}
				
				if($check){
					// If the template is valid for apply 
					$selected_template = $check;
					
					// Set priority
					if($priority < $set_prio){ $priority = $set_prio; }
				}
			}
		}
		
		// Set priority to template id
		if( $selected_template && $exclude_check ){
			$selected_templs[$id] = $priority;
		}
	}
	
	// Return all ids with priority
	if($return_all){
		return $selected_templs;
	}
	
	$gprior = 0; 
	$sel_id = '';
	foreach( $selected_templs as $id => $prior ){
		if($gprior <= $prior){
			$gprior = $prior;
			$sel_id = $id;
		}
	}
	
	return $sel_id;
}

// The header to substitute
function pagelayer_get_header($name) {
	
	global $pagelayer;
	
	// Output default header always if we have a header or footer
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	<?php if(function_exists('wp_body_open')) { wp_body_open(); } ?>
	<?php
	
	// Output our content
	if(!empty($pagelayer->template_header)){
		
		echo '
	<header class="pagelayer-header">';
		
		// Render the content
		pagelayer_template_render($pagelayer->template_header);
		
		echo '
	</header>';
		
	}
		
	// Avoid running wp_head hooks again
	remove_all_actions('wp_head');

	$templates = [];
	$name = (string) $name;
	if ($name !== '') {
		$templates[] = 'header-'.$name.'.php';
	}

	$templates[] = 'header.php';
	
	// Since, we already outputted our header, we need to do a locate_template for the existing theme
	// This is because, locate_template has the 3rd param as require once, so in the get_header 
	// the header.php will not load again
	ob_start();
	locate_template( $templates, true );
	ob_get_clean();
	
}

// The footer to load
function pagelayer_get_footer($name) {
	
	global $pagelayer;
	
	// Output our content
	if(!empty($pagelayer->template_footer)){
		
		echo '
	<footer class="pagelayer-footer">';
	
		pagelayer_template_render($pagelayer->template_footer);
		
		echo '
	</footer>';
	
	}
	
	// Output default footer always if we have a header or footer		
	wp_footer();
	echo '</body>
	</html>';
	
	// Avoid running wp_footer hooks again
	remove_all_actions( 'wp_footer' );

	$templates = [];
	$name = (string) $name;
	if ($name !== '') {
		$templates[] = 'footer-'.$name.'.php';
	}

	$templates[] = 'footer.php';
	
	// Since, we already outputted our footer, we need to do a locate_template for the existing theme
	// This is because, locate_template has the 3rd param as require once, so in the get_footer 
	// the footer.php will not load again
	ob_start();
	locate_template( $templates, true );
	ob_get_clean();
	
}

// Any sidebar to load ?
function pagelayer_get_sidebar($name = '') {
	
	global $pagelayer;
	
	// If any of our setting has been set, then only we apply. Otherwise we return !
	if(is_customize_preview()){
		$pagelayer->settings['sidebar'] = get_option('pagelayer_sidebar');
	}
	
	if(is_array($pagelayer->settings['sidebar'])){
		foreach($pagelayer->settings['sidebar'] as $k => $v){
			$set = 1;
			break;
		}
	}
	
	// If no settings were saved for pagelayer, let the default persist
	// BUT if we are from_header_to_footer then we want default as no header and hence, we will render ours !
	if(empty($set) && empty($pagelayer->from_header_to_footer)){
		return;
	}
	
	// Output our content
	if(!empty($pagelayer->template_sidebar)){
		pagelayer_template_render($pagelayer->template_sidebar);
	}

	$templates = [];
	$name = (string) $name;
	if ($name !== '') {
		$templates[] = 'sidebar-'.$name.'.php';
	}

	$templates[] = 'sidebar.php';
	
	// Since, we already outputted our sidebar, we need to do a locate_template for the existing theme
	// This is because, locate_template has the 3rd param as require once, so in the get_sidebar
	// the sidebar.php will not load again
	ob_start();
	locate_template( $templates, true );
	$sidebar = ob_get_clean();	
	
	// Lets see what are settings are ?
	$set = !empty($pagelayer->settings['sidebar']) ? $pagelayer->settings['sidebar'] : [];
	
	// For page
	if(is_page()){
		$enabled = isset($set['page']) ? $set['page'] : 'default';
	
	// For post
	}elseif(is_single()){
		$enabled = isset($set['post']) ? $set['post'] : 'default';
	
	// For Archives
	}elseif(is_archive() || is_home()){
		$enabled = isset($set['archives']) ? $set['archives'] : 'default';
	}

	// Load the default
	if(@$enabled == 'default' || is_front_page()){
		$enabled = @$set['default'];
	}

	// If its not right or left, then its disabled. Disabled values can be stored as "no" or "0"
	if(!in_array($enabled, ['left', 'right'])){
		return;
	}

	$width = (int) (empty($set['width']) ? 20 : $set['width']);	
	
	if(empty($sidebar)){
		return;
	}
	
	echo $sidebar;
	
?><style>
aside, #sidebar {
width: <?php echo $width;?>%;
float: <?php echo $enabled;?>;
}

main, .pagelayer-content{
width: <?php echo round(99 - $width);?>% !important;
display: inline-block;
}

#wp-calendar{
min-width: 100%;
}
</style>

<?php

}

// Get the custom post content by id
function pagelayer_get_post_content($id){
	global $pagelayer;
	
	// Need to set post ID in Pagelayer boject, so we can get it while do shortcode
	$pagelayer->rendering_template_id = $id;
	
	// Get the content
	$post = get_post($id);
	
	if(is_attachment()){
		remove_filter( 'the_content', 'prepend_attachment' );
	}
	
	$content = $post->post_content;
	pagelayer_load_shortcodes();
	
	$pagelayer->dont_make_editable = true;
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$pagelayer->dont_make_editable = false;
	
	// Reset the id
	$pagelayer->rendering_template_id = 0;
	
	return $content;

}

// Vars that can be used in template files
function pagelayer_template_vars(){
	
	$replacers['{{theme_url}}'] = get_stylesheet_directory_uri();
	$replacers['{{theme_images}}'] = get_stylesheet_directory_uri().'/images/';
	$replacers['{{themes_dir}}'] = dirname(get_stylesheet_directory_uri());
	$replacers['{{content_url}}'] = dirname(dirname(dirname(get_stylesheet_directory_uri())));
	$replacers['{{pl_site_url}}'] = home_url();
	$replacers['{{pl_plugin_url}}'] = PAGELAYER_URL;
	
	return $replacers;
	
}