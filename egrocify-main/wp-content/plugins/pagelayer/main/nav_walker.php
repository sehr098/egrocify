<?php

//////////////////////////////////////////////////////////////
//===========================================================
// nav_walker.php
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

if ( ! class_exists( 'Pagelayer_Walker_Nav_Menu' ) ) {

class Pagelayer_Walker_Nav_Menu extends Walker_Nav_Menu{
	

	// Starts the list before the elements are added.
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		// Filters the CSS class(es) applied to a menu list element.
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	// Ends the list of after the elements are added.
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	// Starts the element output.
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
		
		// Menu setting keys
		$keys = array('_pagelayer_content');
		
		foreach($keys as $key){
			$menu_item_setting = get_post_meta( $item->ID, $key, true );
			
			if(!empty($menu_item_setting)){
				$item->$key = $menu_item_setting;
			}
		}
		
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		
		// Get custom setting data
		$item_content = $this->get_item_data($item, '_pagelayer_content');
		
		$settings = array();
		$pagelayer_has_content = 0;
		
		if(!empty($item_content) && has_blocks($item_content)){
			
			$blocks = parse_blocks($item_content);
			
			$attrs = array();
			
			foreach($blocks as $index => $block){
				if($block['blockName'] != 'pagelayer/pl_nav_menu_item'){
					continue;
				}
								
				// Overrig the menu ID to apply css and others
				foreach($item as $kk => $vv){
					if($kk == '_pagelayer_content'){
						continue;
					}
				
					$block['attrs'][$kk] = $vv;
				}
				
				$settings = $block['attrs'];
				
				// Add settings to $item
				foreach($settings as $skey => $sval){
			
					if(isset($item->$skey)){
						continue;
					}
			
					$item->$skey = $sval;
				}	
							
				if(!empty($block['innerBlocks'])){
					$pagelayer_has_content = $this->has_block_content($block['innerBlocks']);
				}
				
				$blocks[$index] = $block;
			}
			
			$item_content = serialize_blocks($blocks);
		}
		
		$pagelayer_has_content = pagelayer_is_live() ? 1 : $pagelayer_has_content;
		
		// Get menu type
		$menu_type = $this->get_item_data($item, 'menu_type');
		
		if(!empty($menu_type) && $menu_type == 'mega' && $depth == 0 && !empty($pagelayer_has_content)){
			$classes[] = 'pagelayer-mega-menu-item';
		}
		
		if(!empty($menu_type) && $menu_type == 'column' && $depth == 0){
			$classes[] = 'pagelayer-mega-column-item';
		}
		
		$menu_icon_class = $this->get_item_data($item, 'icon_position');
		if(!empty($menu_icon_class)){
			$classes[] = 'pagelayer-nav-menu-icon-'.$menu_icon_class;
		}

		// Filters the arguments for a single nav menu item.
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		// Filters the CSS classes applied to a menu item's list item element.
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		// Filters the ID applied to a menu item's list item element.
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';
		
		// Filters the HTML attributes applied to a menu item's anchor element.
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				
				// Get disable links
				$disable_link = $this->get_item_data($item, 'disable_link');
				if ( 'href' === $attr && !empty($disable_link) ) {
					$value = 'javascript:void(0)';
				}
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = $this->get_item_data($item, 'title');
		$title = apply_filters( 'the_title', $title, $item->ID );

		// Filters a menu item's title.
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		
		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		
		// Add menu icon
		$menu_icon = $this->get_item_data($item, 'menu_icon');
		if (!empty($menu_icon)) {
			$item_output .= '<i class="pagelayer-menu-icon '.$menu_icon.'"></i>';
		}
		
		$item_output .= '<span class="pagelayer-nav-menu-title">' . $args->link_before . $title . $args->link_after .'</span>';

		// Add highlight lable
		$highlight_label = $this->get_item_data($item, 'highlight_label');
		if (!empty($highlight_label)) {
			$item_output .= '<span class="pagelayer-menu-highlight">'.$highlight_label.'</span>';
		}
		
		$item_output .= '</a>';
		$item_output .= $args->after;
		
		$mega_class = 'pagelayer-mega-editor-'.$item->ID;
				
		$item_output .= '<div class="pagelayer-mega-menu '.$mega_class.'">';
		
		// Add mega menu
		if(!empty($item_content)){
			$item_content =  pagelayer_the_content($item_content, true);
			
			// Change the pagelayer ID
			$item_output .= pagelayer_change_id($item_content);
		}
		
		$item_output .= '</div>';

		// Filters a menu item's starting output.
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	// Ends the element output, if needed.
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}
	
	// Get the post value
	public function get_item_data( $item, $name ) {
		
		if(pagelayer_is_live() && isset($_REQUEST['pagelayer_nav_items']) && isset($_REQUEST['pagelayer_nav_items'][$item->ID][$name]) ){
			$data = $_REQUEST['pagelayer_nav_items'][$item->ID][$name];
			
			// If Title is empty
			if(empty($data) && $name == 'title'){
				$_item = clone $item;
				$_item->post_title = '';
				$_item = wp_setup_nav_menu_item($_item);				
				return $_item->title;
			}
			
			if(!empty($data) && $name == '_pagelayer_content'){
				$data = base64_decode($data);
			}
			
			return stripslashes_deep($data);
		}
		
		return @$item->$name;
	}
	
	// Check the block has inner block
	public function has_block_content( $blocks ) {
		
		$tags = array('pagelayer/pl_row', 'pagelayer/pl_inner_row', 'pagelayer/pl_col', 'pagelayer/pl_inner_col');
		$has_content = 0;
		
		foreach($blocks as $block){
			if(!in_array( $block['blockName'], $tags) || !empty($has_content)){
				$has_content = 1;
				break;
			}
			
			if(empty($block['innerBlocks']) ){
				continue;
			}
			
			$has_content = $this->has_block_content($block['innerBlocks']);
		}
		
		return $has_content;
	}

}

}