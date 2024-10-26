<?php

//////////////////////////////////////////////////////////////
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

function pagelayer_do_shortcode_to_block( $content, $ignore_html = false ) {
	global $shortcode_tags;
	
	if ( false === strpos( $content, '[' ) ) {
		return $content;
	}
	
	// Find all registered tag names in $content.
	preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
	$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

	if( empty( $tagnames ) ){
		return $content;
	}

	$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );
	
	$pattern = get_shortcode_regex( $tagnames );
	$content = preg_replace_callback( "/$pattern/", 'pagelayer_do_shortcode_tag', $content );
	
	return $content;
}

function pagelayer_do_shortcode_tag($m){
	
	// Allow [[foo]] syntax for escaping a tag.
	if ( '[' === $m[1] && ']' === $m[6] ) {
		return substr( $m[0], 1, -1 );
	}

	$tag  = $m[2];
	$attr = shortcode_parse_atts( $m[3] );
	$content = isset( $m[5] ) ? $m[5] : null;

	$output = $m[1] . pagelayer_shortcode_to_block( $attr, $content, $tag ) . $m[6];
	
	return $output;
}

function pagelayer_shortcode_to_block($attr, $content, $tag){
	
	if($tag == 'pl_post_props'){
		return '';
	}
	
	if($tag == 'pl_inner_col'){
		$tag = 'pl_col';
	}
	
	if($tag == 'pl_inner_row'){
		$tag = 'pl_row';
	}
	
	$block_name = 'pagelayer/'.str_replace('_', '-', $tag);
	
	$func = 'pagelayer_fix_block_'.$tag;
	
	// Is there a function of the tag ?
	if(function_exists($func)){
		call_user_func_array($func, array(&$block_name, &$attr, &$content));
	}
	
	$content = pagelayer_do_shortcode_to_block($content);
	
	return get_comment_delimited_block_content( $block_name, $attr, $content );
}

function pagelayer_fix_block_pl_accordion_item(&$block_name, &$attr, &$content){
	
	if(pagelayer_has_blocks($content) || false !== strpos( $content, '[pl_' )){
		return;
	}
	
	pagelayer_content_to_block($content);
}

function pagelayer_content_to_block(&$content){

	$content = '<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl-row {"stretch":"auto"} -->
<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl-col {"overlay_hover_delay":"400"} -->
<!-- '.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl-text -->'.$content.'<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl-text -->
<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl-col -->
<!-- /'.PAGELAYER_BLOCK_PREFIX.':pagelayer/pl-row -->';
}