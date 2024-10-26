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

// Is there a block ?
function pagelayer_render_blocks($pre_render, $parsed_block){
	
	global $pagelayer;
	
	if(empty($parsed_block)){
		return $pre_render;
	}
	
	$block_name = $parsed_block['blockName'];
	$tag = '';
	$content = $parsed_block['innerHTML'];
	$inner_blocks = array(
		'blocks' => $parsed_block['innerBlocks'],
		'content' => $parsed_block['innerContent']
	);
	$atts = $parsed_block['attrs'];
	$atts['is_not_sc'] = 1;
	
	if ( is_string( $block_name ) && 0 === strpos( $block_name, 'pagelayer/' ) ) {
		$tag = substr( $block_name, 10 );
	}
	
	$allowed_tags = ['pl_inner_row', 'pl_inner_col'];
	
	if( (empty($tag) || !array_key_exists($tag, $pagelayer->shortcodes) ) && ! in_array( $tag, $allowed_tags) ){
		return $pre_render;
	}
	
	return pagelayer_render_shortcode($atts, $content, $tag, $inner_blocks);
}

// Is there a tag ?
function pagelayer_render_shortcode($atts, $content = '', $tag = '', $inner_blocks = array()){

	global $pagelayer;
	
	$is_block = 0;
	$el = [];
	
	// Is block ?
	if(!empty($atts['is_not_sc'])){
		$is_block = 1;
		unset($atts['is_not_sc']);
	}
	
	$_tag = $class = $tag;
	$final_tag = $tag;
	
	// Check if the tag is inner row and col then change it to row and col tag
	if($tag == 'pl_inner_row'){
		$tag = 'pl_row';
	}elseif($tag == 'pl_inner_col'){
		$tag = 'pl_col';
		$final_tag = $tag;
	}
	
	// Clear the pagelayer tags
	if(substr($tag, 0, 3) == 'pl_'){
		$_tag = str_replace('pl_', '', $final_tag);
		$class = 'pagelayer-'.$_tag;
	}
	
	if(empty($atts)){
		$atts = array();
	}else{
		$atts = (array) $atts;
	}
	
	// If global - > Get the post and replace $atts
	if(!empty($atts['global_id'])){
		
		if(!empty($pagelayer->global_widgets[$atts['global_id']])){
			$content = $pagelayer->global_widgets[$atts['global_id']]['$'];
			return pagelayer_change_id($content);
		}
		
		if(!empty($pagelayer->global_sections[$atts['global_id']])){
			$content = $pagelayer->global_sections[$atts['global_id']]['$'];
			return pagelayer_change_id($content);
		}
		
		// Set the global id as attr
		$el['attr'][] = 'pagelayer-global-id="'.$atts['global_id'].'"';
	}
	
	// Is there any function ?
	$func = @$pagelayer->shortcodes[$tag]['func'];
	
	// If not, we will search for a default func if prefix of tag is pl_
	if(empty($func) && substr($tag, 0, 3) == 'pl_'){
		$func = 'pagelayer_sc_'.substr($tag, 3);
	}
	
	// UnescapeHTML for the attributes, Fix for old shortcode method
	if(empty($is_block)){
		$atts = array_map('pagelayer_unescapeHTML', $atts);
	}

	// Create the element array. NOTE : This is similar to the JS el and is temporary
	$el['atts'] = $atts;
	$el['oAtts'] = $atts;
	$el['id'] =  !empty($atts['pagelayer-id']) ? $atts['pagelayer-id'] : pagelayer_create_id();
	$el['tmp'] = [];
	$el['tag'] = $final_tag;
	$el['content'] = $content;
	$el['inner_blocks'] = $inner_blocks;
	$el['selector'] = '[pagelayer-id="'.$el['id'].'"]';
	$el['cssSel'] = '.p-'.$el['id'];
	$el['wrap'] = '[pagelayer-wrap-id="'.$el['id'].'"]';
	
	// Remove pagelayer-id from attr
	if( !empty($atts['pagelayer-id']) ){
		unset($el['atts']['pagelayer-id']);
		unset($el['oAtts']['pagelayer-id']);
	}
	
	$innerHTML = @$pagelayer->shortcodes[$tag]['innerHTML'];
	if(!empty($innerHTML) && !empty($content)){
		$el['oAtts'][$innerHTML] = $content;
		$el['atts'][$innerHTML] = $content;
	}
	
	// The default class
	$el['classes'][] = 'p-'.$el['id'];
	$el['classes'][] = $class;
	
	// Register hook to filter $el
	$el = apply_filters('pagelayer_do_shortcode_el', $el);
	
	//pagelayer_print($el);
	
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
			
			foreach($props as $prop => $param){
			
				//echo $tab.' - '.$section.' - '.$prop.'<br>';
				
				// Handle the edit fields
				if(!empty($param['edit'])){
					$el['edit'][$prop] = $param['edit'];
				}
				
				// No value set
				if(empty($el['atts'][$prop]) && empty($el['atts'][$prop.'_tablet']) && empty($el['atts'][$prop.'_mobile'])){
					continue;
				}
				
				// Clean the not required atts
				if(!empty($param['req'])){
					
					$set = true;
					
					foreach($param['req'] as $rk => $reqval){
						$except = $rk[0] == '!' ? true : false;
						$rk = $except ? substr($rk, 1) : $rk;
						$val = @$el['atts'][$rk];
						
						//echo $prop.' - '.$rk.' : '.$reqval.' == '.$val.'<br>';
						
						// The value should not be there
						if($except){
							
							if(!is_array($reqval) && $reqval == $val){
								$set = false;
								break;
							}
							
							// Its an array and a value is found, then dont show
							if(is_array($reqval) && in_array($val, $reqval)){
								$set = false;
								break;
							}
							
						// The value must be equal
						}else{
							
							 if(!is_array($reqval) && $reqval != $val){
								$set = false;
								break;
							 }
							
							// Its an array and no value is found, then dont show
							if(is_array($reqval) && !in_array($val, $reqval)){
								$set = false;
								break;
							}
						}
						
					}
					
					// Unset as we dont need
					if(empty($set)){
						unset($el['atts'][$prop]);
						unset($el['atts'][$prop.'_tablet']);
						unset($el['atts'][$prop.'_mobile']);
						unset($el['tmp'][$prop]);
						unset($el['tmp'][$prop.'_tablet']);
						unset($el['tmp'][$prop.'_mobile']);
					}
					
				}
				
				// We could have unset the value above, so we need to check again if the value is there
				if(empty($el['atts'][$prop]) && empty($el['atts'][$prop.'_tablet']) && empty($el['atts'][$prop.'_mobile'])){
					continue;
				}
				
				// Load any attachment values - This should go on top in the newer version @TODO
				if(in_array($param['type'], ['image', 'video', 'audio', 'media'])){
					
					$attachment = ($param['type'] == 'image') ? pagelayer_image(@$el['atts'][$prop]) : pagelayer_attachment(@$el['atts'][$prop]);
					
					if(!empty($attachment)){
						foreach($attachment as $k => $v){
							$el['tmp'][$prop.'-'.$k] = $v;
						}						
					}
					
				}
				
				// Load any attachment values - This should go on top in the newer version @TODO
				if($param['type'] == 'multi_image'){
					
					$img_ids = pagelayer_maybe_explode(',', $el['atts'][$prop]);					
					$img_urls = [];
					
					// Make the image URL
					foreach($img_ids as $k => $v){
						$image = pagelayer_image($v);
						$img_urls['i'.$v] = @$image['url'];
					}
					
					$el['tmp'][$prop.'-urls'] = json_encode($img_urls);
				}
				
				// Backward compatibility of row
				if($el['tag'] == 'pl_row' && $prop == 'content_pos' && !empty($el['atts'][$prop])){
					if($el['atts'][$prop] == 'baseline'){
						$el['atts'][$prop] = $el['oAtts'][$prop] = 'flex-start';
					}else if($el['atts'][$prop] == 'end'){
						$el['atts'][$prop] = $el['oAtts'][$prop] = 'flex-end';
					}
				}
				
				// Backward compatibility of Icons
				if($param['type'] == 'icon' && !empty($el['atts'][$prop]) && !preg_match('/\s/', $el['atts'][$prop])){
					$el['atts'][$prop] = $el['oAtts'][$prop] = 'fa fa-'.$el['atts'][$prop];
				}
				
				// Backward compatibility of Box Shadow
				if($param['type'] == 'box_shadow' && !empty($el['atts'][$prop])){
					$shadow_atts = pagelayer_maybe_explode(',', $el['atts'][$prop]);
					if(count($shadow_atts) == 4){
						$shadow_atts[] = '0';
						$shadow_atts[] = '';
						$el['atts'][$prop] = $el['oAtts'][$prop] = $shadow_atts;
					}
				}
				
				// Backward compatibility of units. And also for the default set value if it is numeric
				if(!empty($param['units']) && isset($el['atts'][$prop]) && is_numeric($el['atts'][$prop])){
					$el['atts'][$prop] = $el['oAtts'][$prop] = $el['atts'][$prop].$param['units'][0];
				}
				
				// Load permalink values
				if($param['type'] == 'link'){
					
					$link = $el['atts'][$prop];
					
					if( is_array($el['atts'][$prop]) ){
						
						// Link is required for check IF and IF-EXT in html
						if(!isset($el['atts'][$prop]['link']) || strlen(trim($el['atts'][$prop]['link'])) < 1){
							$link = '';
							unset($el['atts'][$prop]);
							continue;
						}
						
						$link = $el['atts'][$prop]['link'];
						
						if(!empty($el['atts'][$prop]['target'])){
							$el['attr'][][$param['selector']] = 'target="_blank"';
						}
						
						if(!empty($el['atts'][$prop]['rel'])){
							$el['attr'][][$param['selector']] = 'rel="nofollow"';
						}
						
						if(!empty($el['atts'][$prop]['attrs'])){

							$atts_ar = pagelayer_string_to_attributes($el['atts'][$prop]['attrs']);

							if(!empty($atts_ar)){
								foreach($atts_ar as $att => $value){
									$el['attr'][][$param['selector']] = $att.'="'.$value.'"';							
								}
							}
						}
					}
					
					$el['tmp'][$prop] = pagelayer_permalink($link);
				}
				
				// Handle the AddClasses
				if(!empty($param['addClass']) && !empty($el['atts'][$prop])){
					
					// Convert to an array
					if(!is_array($param['addClass'])){
						$param['addClass'] = array($param['addClass']);
					}
					
					// Loop through
					foreach($param['addClass'] as $k => $v){
						$k = str_replace('{{element}}', '', $k);
						$el['classes'][] = [trim($k) => str_replace('{{val}}', $el['atts'][$prop], $v)];
					}
					
				}
				
				// Handle the AddAttributes
				if(!empty($param['addAttr']) && !empty($el['atts'][$prop])){
					
					// Convert to an array
					if(!is_array($param['addAttr'])){
						$param['addAttr'] = array($param['addAttr']);
					}
					
					// Loop through
					foreach($param['addAttr'] as $k => $v){
						$k = str_replace('{{element}}', '', $k);
						$el['attr'][] = [trim($k) => $v];
					}
					
				}				
				
				$modes = [
					'desktop' => '',
					'tablet' => '_tablet',
					'mobile' => '_mobile'
				];
				
				$global_typo = ($param['type'] == 'typography') ? pagelayer_is_global_typo(@$el['atts'][$prop]) : '';
				
				// Handle the CSS
				if(!empty($param['css'])){
					//echo $prop.'<br>';
					// Convert to an array
					if(!is_array($param['css'])){
						$param['css'] = array($param['css']);
					}
					
					// Loop the modes and check for values
					foreach($modes as $mk => $mv){
						
						$M_prop = $prop.$mv;
						
						$prop_val = isset($el['atts'][$M_prop]) ? $el['atts'][$M_prop] : '';
						
						// If is global font
						if( $param['type'] == 'typography' && (!empty($prop_val) || !empty($global_typo)) ){
							$prop_val = pagelayer_parse_typo($prop_val, $global_typo, $mk);
						}
						
						// Any value ?
						if(empty($prop_val)){
							continue;
						}
						
						// Global color handler
						if($param['type'] == 'color'){
							$prop_val = pagelayer_parse_color($prop_val);
						}
						
						// If there is global gradient color
						if($param['type'] == 'gradient'){
							
							$prop_val = pagelayer_maybe_explode(',', $prop_val);
							
							foreach($prop_val as $grad_key => $grad_val){
								
								if($grad_val[0] != '$'){
									continue;
								}
								
								$prop_val[$grad_key] = pagelayer_parse_color($grad_val);
								
							}
							
						}

						// Loop through
						foreach($param['css'] as $k => $v){
							
							// Make the selector
							$selector = (!is_numeric($k) ? $k : $el['cssSel']);
							$selector = pagelayer_parse_el_vars($selector, $el);
							
							if($mk == 'tablet'){
								$selector = '|pl_tablet|'.$selector;
							}
							
							if($mk == 'mobile'){
								$selector = '|pl_mobile|'.$selector;
							}
							
							// Make the CSS
							if(!empty($selector)){
								$el['css'][$selector][] = rtrim( trim( pagelayer_css_render($v, $prop_val, @$param['sep']) ), ';' );
							}else{
								$el['css'][][] = pagelayer_parse_el_vars($el['atts'][$M_prop],$el);
							}
						}
						
					}
					
				}
				
				$font_cache = '';
				// Loop the modes and check for values
				foreach($modes as $mk => $mv){
					
					$M_prop = $prop.$mv;

					if($param['type'] == 'typography' && !empty($el['atts'][$M_prop])){
						
						$prop_val = pagelayer_parse_typo($el['atts'][$M_prop], $global_typo, $mk);
						
						$val = pagelayer_maybe_explode(',', $prop_val);
												
						//For backward comaptibility
						if($mk == 'desktop'){
							$font_cache = $val[0];
						}

						$val[0] = empty($val[0]) ? $font_cache : $val[0];
						
						if(!empty($val[0])){
							pagelayer_load_font_family($val[0], @$val[3], @$val[2]);
														
							//pagelayer_print($pagelayer->runtime_fonts);
						}
					}
					
					if($prop == 'font_family' && !empty($el['atts'][$M_prop])){
						$val = $el['atts'][$M_prop];
						if(!empty($val)){
							pagelayer_load_font_family($val, @$el['atts']['font_weight'.$mv], @$el['atts']['font_style'.$mv]);
						}
					}
				}
			}
			
		}
		
	}
	
	//@pagelayer_print($el['css']);
	
	// Is there a function of the tag ?
	if(function_exists($func)){
		call_user_func_array($func, array(&$el));
	}
	
	// Create the default atts and tmp atts
	if(pagelayer_is_live()){
		pagelayer_create_sc($el, $is_block);
	}
	
	$div = '<div pagelayer-id="'.$el['id'].'">
<style pagelayer-style-id="'.$el['id'].'"></style>';
	
	$is_group = !empty($pagelayer->shortcodes[$tag]['has_group']) ? true : false;
	
	// If there is an HTML AND you are not a GROUP, then make use of it, or append the real content
	if(!empty($pagelayer->shortcodes[$tag]['html'])){
		
		// Create the HTML object
		$node = pagelayerQuery::parseStr($pagelayer->shortcodes[$tag]['html']);
		
		// Remove the if-ext
		foreach($node('[if-ext]') as $v){
			$reqvar = pagelayer_var($v->attr('if-ext'));
			$v->removeAttr('if-ext');
			
			// Is the element there ?
			if(empty($el['atts'][$reqvar])){
				$ext_html = $v->html();
				if(strlen($ext_html) > 0){
					$v->after($ext_html);
				}
				$v->remove();
			}
		}
		
		// Remove the if
		foreach($node('[if]') as $v){
			$reqvar = pagelayer_var($v->attr('if'));
			$v->removeAttr('if');
			
			// Is the element there ?
			if(empty($el['atts'][$reqvar])){
				$v->remove();
			}
		}
		
		//die($node->html());
		
		// Do we have a holder ? Mainly for groups
		if(!empty($pagelayer->shortcodes[$tag]['holder'])){
			$node->query($pagelayer->shortcodes[$tag]['holder'])->html('{{pagelayer_do_shortcode}}');
			$do_shortcode = 1;
		}
		
		$html = pagelayer_parse_vars($node->html(), $el);
		
		// Append to the DIV
		$div .= $html;
		
	// Is it a widget ?
	}elseif(!empty($pagelayer->shortcodes[$tag]['widget'])){
		
		$class = $pagelayer->shortcodes[$tag]['widget'];
		$instance = [];
		
		// Is there any existing data ?
		if(!empty($el['atts']['widget_data'])){		
			$json = trim($el['atts']['widget_data']);
			$json = json_decode($json, true);
			//pagelayer_print($json);die();
			if(!empty($json)){
				$instance = $json;
			}
		}
		
		ob_start();
		the_widget($class, $instance, array('widget_id'=>'arbitrary-instance-'.$el['id'],
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		));
		
		$div .= ob_get_contents();
		ob_end_clean();
		
	}else{
		$div .= '{{pagelayer_do_shortcode}}';
		$do_shortcode = 1;
	}
	
	// End the tag
	$div .= '</div>';
	
	// Add classes and attributes
	if(!empty($el['classes']) || !empty($el['attr']) || !empty($el['atts']['ele_attributes'])){
	
		// Create the HTML object
		$node = pagelayerQuery::parseStr($div);
		
		// Add the editable values
		if(!empty($el['edit']) && pagelayer_is_live()){
			
			foreach($el['edit'] as $k => $v){
				$node->query($v)->attr('pagelayer-editable', $k);
			}
			
		}
		
		// Add the post data editable 
		if(pagelayer_is_live() && !empty($pagelayer->shortcodes[$tag]['edit_props']) && is_array($pagelayer->shortcodes[$tag]['edit_props'])){
			
			$edit_props = $pagelayer->shortcodes[$tag]['edit_props'];				

			foreach($edit_props as $k => $v){
				$node->query($k)->attr('pagelayer-props-editable', $v);
			}
			
		}
		
		// Add the classes
		if(!empty($el['classes'])){
			
			//pagelayer_print($el['classes']);
			
			foreach($el['classes'] as $k => $v){
				
				if(!is_array($v)){
					$v = [$v];
				}
				
				foreach($v as $kk => $vv){
					//echo $kk.' - '.$vv."\n";
					if(is_numeric($kk)){
						$node->query($el['selector'])->addClass($vv);
					}else{
						$node->query($kk)->addClass($vv);
					}
					
				}
				
			}
			
			//echo $node->html();
			//die();
			
		}
	
		// Add the attributes		
		if(!empty($el['attr'])){
			
			//pagelayer_print($el['attr']);
			
			foreach($el['attr'] as $k => $v){
				
				if(!is_array($v)){
					$v = [$v];
				}
				
				foreach($v as $kk => $vv){
					
					$att = explode('=', $vv, 2);
					$att[1] = pagelayer_parse_vars($att[1], $el);
					$att[1] = trim($att[1], '"');
					
					if(is_numeric($kk)){
						$node->query($el['selector'])->attr($att[0], $att[1]);
					}else{
						$node->query($kk)->attr($att[0], $att[1]);
					}
					
				}
				
			}
			
		}
	
		// Adding Custom Attributes
		if(!empty($el['atts']['ele_attributes'])){
			
			$val = pagelayer_string_to_attributes($el['atts']['ele_attributes']);
			if(!empty($val)){
				foreach($val as $att => $value ){
					$node->query($el['selector'])->attr($att, $value);
				}
			}
			
		}
				
		// Get font family form inline style
		foreach($node->query('[style]') as $snode){
			$ss = $snode->attr('style');
			
			if(strpos($ss, 'font-family') === false){
				continue;
			}
			
			$ss = explode(';', html_entity_decode($snode->attr('style')));
			foreach($ss as $sss){
				
				if(strpos($sss, 'font-family') === false){
					continue;
				}
				
				$ff = explode(':', $sss);
				$val = trim( trim($ff[1]), '"' );
				$fw = array('100', '100i', '200', '200i', '300', '300i', '400', '400i', '500', '500i', '600', '600i', '700', '700i', '800', '800i', '900', '900i');
				
				foreach($fw as $ww){
					$pagelayer->runtime_fonts[$val][$ww] = $ww;
				}
			}
		}
		
		$div = $node->html();
		//die($div);
	
	}
		
	// Add the CSS if any or remove it
	$style = '';
	if(!empty($el['css'])){
		
		$screen_style = array('tablet' => '', 'mobile' => '');
		$style = '<style pagelayer-style-id="'.$el['id'].'">';
		foreach($el['css'] as $ck => $cv){
			preg_match('/\|pl_(mobile|tablet)\|/is', $ck, $screen_matches);
			$ck = str_replace(['|pl_mobile|', '|pl_tablet|'], '', $ck);
			$media = @$screen_matches[1];
			
			$merge_css = implode(';', $cv);
			$combine_css = (!is_numeric($ck) ? $ck.'{'.$merge_css.'}' : $merge_css ).PHP_EOL;
			
			// Mobile or tablet ?
			if(!empty($media)){
				$screen_style[$media] .= $combine_css;
				continue;
			}
			
			$style .= $combine_css;
		}
		
		if(!empty($screen_style['tablet'])){
			$style .= '@media (max-width: '.$pagelayer->settings['tablet_breakpoint'].'px) and (min-width: '.($pagelayer->settings['mobile_breakpoint'] + 1).'px){'.$screen_style['tablet'].'}'.PHP_EOL;
		}
		
		if(!empty($screen_style['mobile'])){		
			$style .= '@media (max-width: '.$pagelayer->settings['mobile_breakpoint'].'px){'.$screen_style['mobile'].'}'.PHP_EOL;
		}
		
		$style .= '</style>';
		$style = pagelayer_parse_vars($style, $el);
	
		if(!empty($pagelayer->shortcodes[$tag]['overide_css_selector'])){
			$overide_css_selector = pagelayer_parse_el_vars($pagelayer->shortcodes[$tag]['overide_css_selector'], $el);
			$style = str_replace($el['cssSel'], $overide_css_selector, $style);
			$style = str_replace($el['wrap'], $overide_css_selector, $style);
		}
		
		$style = pagelayer_unescapeHTML($style);
	}
	
	$div = str_replace('<style pagelayer-style-id="'.$el['id'].'"></style>', $style, $div);
	
	// Is there an inner content which requires a SHORTCODE ?
	if(!empty($do_shortcode)){
		
		$inner_content =  pagelayer_render_inner_content($el);
		
		$div = str_replace('{{pagelayer_do_shortcode}}', $inner_content, $div);
	}
	
	// Sanitize the content
	$div = apply_filters( 'pagelayer_sanitize_do_shortcode', $div );
	
	return $div;
	
}

// Render inner content
function pagelayer_render_inner_content(&$el){
	
	$inner_content = '';
	
	// Is block code?
	if( !empty($el['inner_blocks']) ){
		
		$index = 0;

		foreach ( $el['inner_blocks']['content'] as $chunk ) {
			if ( is_string( $chunk ) ) {
				
				// If any string in Column the conver this is text widget in pagelayer live
				if(!empty(trim($chunk)) && pagelayer_is_live() && $el['tag'] == 'pl_col'){
					$parsed_block['blockName'] = 'pagelayer/pl_text';
					$parsed_block['innerHTML'] = $chunk;
					$parsed_block['attrs'] = [];
					$inner_content .= render_block($parsed_block);
					continue;
				}
				
				$inner_content .= $chunk;
				continue;
			}
			
			$inner_block  = $el['inner_blocks']['blocks'][ $index ];
			$inner_content .= render_block($inner_block);
			++$index;
		}
	}else{
		$inner_content .=  do_shortcode($el['content']);
	}
	
	return $inner_content;
}

// Change pagelayer id in html
function pagelayer_change_id($content){
	global $pagelayer;
	
	preg_match_all('/pagelayer-id="(.*?)"/', $content, $matches);
	$matches = array_unique($matches[1]);
	
	foreach($matches as $val){
		$id = pagelayer_create_id();
		$content = str_replace($val, $id, $content);
	}
	
	return $content;
}

// Creates the shortcode and returns a base64 encoded files
function pagelayer_create_sc(&$el, $is_block = 0){
	
	global $pagelayer;
	
	$a = $tmp = array();
	
	$pagelayer->data_attr[$el['id']] = ['attr' => $el['oAtts'], 'tmp' => $el['tmp']];
	
	/*if(!empty($el['oAtts'])){
		
		foreach($el['oAtts'] as $k => $v){
			$v = str_replace('&', '&amp;', $v);
			if($is_block){
				$v = pagelayer_escapeHTML($v);
			}
			$el['attr'][] = 'pagelayer-a-'.$k.'="'.$v.'"';
		}
		
	}
	
	// Tmp atts
	if(!empty($el['tmp'])){
		
		foreach($el['tmp'] as $k => $v){
			$v = str_replace('&', '&amp;', $v);
			if($is_block){
				$v = pagelayer_escapeHTML($v);
			}
			$el['attr'][] = 'pagelayer-tmp-'.$k.'="'.$v.'"';
		}
		
	}*/
	
	// Add the tag
	$el['attr'][] = 'pagelayer-tag="'.$el['tag'].'"';
	
	// Make it a PageLayer element for editing
	$el['classes'][] = 'pagelayer-ele';
	
}

// Converts {{val}} to val
function pagelayer_var($var){
	return substr($var, 2, -2);
}

// Is the given global color
function pagelayer_is_global_typo($value){
	global $pagelayer;

	$typo_key = '';
	
	// Backward compatibility
	if(is_string($value) && $value[0] == '$'){
		$typo_key = substr($value, 1);
	}
	
	if(is_array($value) && isset($value['global-font'])){
		$typo_key = $value['global-font'];
	}
		
	// If global color not exist
	if(!empty($typo_key)){
		$typo_key = isset($pagelayer->global_fonts[$typo_key]) ? $typo_key : 'primary';
	}
	
	return $typo_key;
	
}

// Parse typography and handle Backward compatibility
function pagelayer_parse_typo($value, $desk_global = '', $mk = 'desktop'){
	global $pagelayer;
	
	$value = empty($value)? [] : $value;
	
	// Backward compatibility for comma seperated val
	if(!is_array($value) && $value[0] != '$'){
		return $value;
	}
	
	$val = ['','','','','','','','','','',''];
	$global_typo = pagelayer_is_global_typo($value);
	$_desk_global = false;
	
	if( empty($global_typo) ){
		$global_typo = $desk_global;
		$_desk_global = true;
	}
	
	// Apply global typo
	foreach($pagelayer->typo_props as $typo => $typo_key){
		
		// Backspace compatibility for normal array and if is set global in '$' format like $primary
		if(is_array($value) && !empty($value[$typo])){
			$val[$typo] = $value[$typo];
		}
		
		if(!empty($value[$typo_key])){
			$val[$typo] = $value[$typo_key];
		}
		
		if(!empty($val[$typo]) || empty($global_typo)){
			continue;
		}
		
		$global_val = $pagelayer->global_fonts[$global_typo]['value'];
		
		if( empty($global_val[$typo_key]) || (is_array($global_val[$typo_key]) && empty($global_val[$typo_key][$mk])) || (!is_array($global_val[$typo_key]) && !empty($_desk_global) && $mk != 'desktop') ){
			continue;
		}
		
		$val[$typo] = 'var(--pagelayer-font-'.$global_typo.'-'.$typo_key.')';
	}
	
	return $val;	
}

// Parse color for global color
function pagelayer_parse_color($value, $var = true){
	global $pagelayer;
		
	// Global color handler
	if($value[0] != '$' ){
		return $value;
	}
	
	$gkey = substr($value, 1);
	$gkey = isset($pagelayer->global_colors[$gkey]) ? $gkey : 'primary';
	
	if(empty($var)){
		return @$pagelayer->global_colors[$gkey]['value'];
	}
	
	return 'var(--pagelayer-color-'.$gkey.')';
}

// Replace the variables
function pagelayer_parse_el_vars($str, &$el){
	
	global $pagelayer, $post;
	
	// if is 404 then @$post->ID
	if(!empty( $pagelayer->rendering_template_id ) && @$post->ID != $pagelayer->rendering_template_id){
		$is_editable = false;
	}else{
		$is_editable = true;
	}
	
	$str = str_replace('{{element}}', $el['cssSel'], $str);
	$is_live = pagelayer_is_live();
	if(!empty($is_live) && $is_editable){
		$str = str_replace('{{wrap}}', $el['wrap'], $str);
	}else{
		$str = str_replace('{{wrap}}', $el['cssSel'], $str);
	}
	$str = str_replace('{{ele_id}}', $el['id'], $str);
	
	return $str;

}

// Parse the variables
function pagelayer_parse_vars($str, &$el){
	
	//pagelayer_print($el);
	if(!empty($el['tmp']) && is_array($el['tmp'])){
		foreach($el['tmp'] as $k => $v){
			$str = str_replace('{{{'.$k.'}}}', pagelayer_maybe_implode($el['tmp'][$k]), $str);
		}
	}
	
	if(is_array($el['atts'])){
		foreach($el['atts'] as $k => $v){
			$str = str_replace('{{'.$k.'}}', pagelayer_maybe_implode($el['atts'][$k]), $str);
		}
	}
	
	return $str;
}

// Make the rule
function pagelayer_css_render($rule, $val, $sep = ','){
	
	// Seperator
	$sep = empty($sep) ? ',' : $sep;
	
	if(is_array($val)){
		$val = implode($sep, $val);
	}
	
	// Replace the val
	$rule = pagelayer_css_val_replace('{{val}}', pagelayer_hex8_to_rgba($val), $rule);
	
	// If there is an array
	if(preg_match('/\{val\[\d/is', $rule)){
		$val = explode($sep, $val);
		foreach($val as $k => $v){
			$rule = pagelayer_css_val_replace('{{val['.$k.']}}', pagelayer_hex8_to_rgba($v), $rule);
		}
	}
	
	return $rule;
}

// Make the rule
function pagelayer_css_val_replace($val, $v, $rule){
	
	// If value has css var then we remove units
	if(strripos($v, 'var(') !== false){
		$pattern = '/'.preg_quote($val, '/').'?[^\s|;]+/is';
		$rule = preg_replace($pattern, $v, $rule);
		return $rule;
	}
	
	$rule = str_replace($val, $v, $rule);
	return $rule;
}

// Post Property Handler
function pagelayer_sc_post_props(&$el){
	
	global $post;
	
	if(empty($post)){
		return;
	}
	
	$el['oAtts']['post_title'] = $post->post_title;
	$el['oAtts']['post_name'] = $post->post_name;
	$el['oAtts']['post_excerpt'] = $post->post_excerpt;
	$el['oAtts']['post_status'] = (empty($post->post_password)) ? $post->post_status : 'pass_protected';
	$el['oAtts']['post_password'] = $post->post_password;
	$el['oAtts']['featured_image'] = get_post_thumbnail_id($post);
	$el['oAtts']['comment_status'] = ($post->comment_status == 'open') ? 'true' : '';
	$el['oAtts']['ping_status'] = ($post->ping_status == 'open') ? 'true' : '';
	$el['oAtts']['post_date'] = $post->post_date;
	$el['oAtts']['post_sticky'] = is_sticky($post->ID) ? 'true' : '';
	$el['oAtts']['post_parent'] = $post->post_parent;
	$el['oAtts']['menu_order'] = $post->menu_order;
	$el['oAtts']['post_author'] = $post->post_author;
	$el['oAtts']['post_category'] = '';
	$el['oAtts']['post_tags'] = '';
	
	$tag_name = pagelayer_post_type_tag($post->post_type);
	if(!empty($tag_name)){
		$postTags = wp_get_post_terms( $post->ID, $tag_name );
		$el['oAtts']['post_tags'] = array_column((array)$postTags, 'name');
	}

	$cat_name = pagelayer_post_type_category($post->post_type);	
	if(!empty($cat_name)){
		$category = get_the_terms( $post->ID, $cat_name );  
		$el['oAtts']['post_category'] = array_column((array)$category, 'term_id');
	}	
	
	// Load featured image details
	if(!empty($el['oAtts']['featured_image'])){
		
		$attachment = pagelayer_image($el['oAtts']['featured_image']);

		if(!empty($attachment)){
			foreach($attachment as $k => $v){
				$el['tmp']['featured_image-'.$k] = $v;
			}
		}
	
	}
	
}

// ROW Handler
function pagelayer_sc_row(&$el){
	
	pagelayer_bg_video($el);
	
	if(!empty($el['atts']['row_shape_type_top'])){
		$path_top = PAGELAYER_DIR.'/images/shapes/'.$el['atts']['row_shape_type_top'].'-top.svg';
		$el['atts']['svg_top'] = file_get_contents($path_top);
	}
	
	if(!empty($el['atts']['row_shape_type_bottom'])){
		$path_bottom = PAGELAYER_DIR.'/images/shapes/'.$el['atts']['row_shape_type_bottom'].'-bottom.svg';
		$el['atts']['svg_bottom'] = file_get_contents($path_bottom);
	}
	
	// Row background slider
	if(!empty($el['atts']['bg_slider'])){
		$ids = pagelayer_maybe_explode(',', $el['atts']['bg_slider']);
		$urls = [];
		$el['atts']['slider'] = '';
		
		// Make the image URL
		foreach($ids as $k => $v){
			
			$image = pagelayer_image($v);
			$urls['i'.$v] = @$image['url'];
			
			$el['atts']['slider'] .= '<div class="pagelayer-bgimg-slide" style="background-image:url(\''.$image['url'].'\')"></div>';
			
		}
		
		if(!empty($urls)){
			$el['tmp']['bg_slider-urls'] = json_encode($urls);
		}
		
	}
	
	// Row background parallax image.
	if(!empty($el['atts']['parallax_img'])){
		$img_size = @$el['tmp']['parallax_img-'.$el['atts']['parallax_id_size'].'-url'];
		$el['atts']['parallax_img_src'] = empty($img_size) ? @$el['tmp']['parallax_img-url'] : $img_size;
	}
	
}

// Column Handler
function pagelayer_sc_col(&$el){
	
	// Add the default col class
	$el['classes'][] = 'pagelayer-col';
	
	//return do_shortcode($el['content']);
	
	pagelayer_bg_video($el);
	
	// Column background slider
	if(!empty($el['atts']['bg_slider'])){
		$ids = pagelayer_maybe_explode(',', $el['atts']['bg_slider']);
		$urls = [];
		$el['atts']['slider'] = '';
		
		// Make the image URL
		foreach($ids as $k => $v){
			
			$image = pagelayer_image($v);
			$urls['i'.$v] = @$image['url'];
			
			$el['atts']['slider'] .= '<div class="pagelayer-bgimg-slide" style="background-image:url(\''.$image['url'].'\')"></div>';
			
		}
		
		if(!empty($urls)){
			$el['tmp']['bg_slider-urls'] = json_encode($urls);
		}
		
	}
	
	// Col background parallax image.
	if(!empty($el['atts']['parallax_img'])){
		$img_size = @$el['tmp']['parallax_img-'.$el['atts']['parallax_id_size'].'-url'];
		$el['atts']['parallax_img_src'] = empty($img_size) ? @$el['tmp']['parallax_img-url'] : $img_size;
	}
	
}

// Just for BG handling
function pagelayer_bg_video(&$el){
	
	if(empty($el['tmp']['bg_video_src-url'])){
		return false;
	}
	
	// Get the video URL for the iframe
	$iframe_atts = pagelayer_video_url($el['tmp']['bg_video_src-url'], true);
	
	$source = esc_url( $el['tmp']['bg_video_src-url'] );
	$source = str_replace('&amp;', '&', $source);
	$url = parse_url($source);
	
	$iframe_atts['src'] .= substr_count($iframe_atts['src'], '?') > 0 ? '' : '?';
	
	if(!empty($el['atts']['mute'])){
		$iframe_atts['src'] .= "&mute=1";
		$el['atts']['mute'] = " muted ";
	}else{
		$iframe_atts['src'] .= "&mute=0";
		$el['atts']['mute'] = "";
	}

	if(empty($el['atts']['stop_loop'])){
		$iframe_atts['src'] .= "&loop=1";	
		$el['atts']['stop_loop'] = " loop ";
	}else{
		$iframe_atts['src'] .= "&loop=0";	
		$el['atts']['stop_loop'] = "";
	}
	
	if (!empty($source)) {
		
		if ($iframe_atts['type'] == 'youtube') {
		
			$settings = ' data-loop="'.( !empty($el['atts']['stop_loop']) ? 1 : 0 ).'" data-mute="'.( !empty($el['atts']['mute']) ? 1 : 0 ).'" data-videoid = "'.( $iframe_atts['id'] ).'"';
						
			$el['atts']['vid_src'] = '<div class = "pagelayer-youtube-video" '. $settings .'></div>';
			
		} else if ($iframe_atts['type'] == 'vimeo') {
			
			$el['atts']['vid_src'] = '<iframe src="'.$iframe_atts['src'].'&background=1&autoplay=1&byline=0&title=0" allowfullscreen="1" webkitallowfullscreen="1" mozallowfullscreen="1" frameborder="0"></iframe>';
			
		}else{
			
			$el['atts']['vid_src'] = '<video autoplay playsinline '.$el['atts']['mute'].$el['atts']['stop_loop'].'>'.
				'<source src="'.$iframe_atts['src'].'" type="video/mp4">'.
			'</video>';
			
		}
	}
}

// Heading Handler
function pagelayer_sc_heading(&$el){
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array('rel' => '', 'selector' => '.pagelayer-link-sel'));
}

// Heading Handler
function pagelayer_sc_icon(&$el){
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array('rel' => '', 'selector' => '.pagelayer-ele-link'));
}

// Heading Handler
function pagelayer_sc_badge(&$el){
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'badge_url',
		'rel' => '',
		'target' => 'badge_target',
		'selector' => '.pagelayer-ele-link'
	));
}

// Heading Handler
function pagelayer_sc_btn(&$el){
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array('selector' => '.pagelayer-btn-holder'));
}

// Image Handler
function pagelayer_sc_social(&$el){
	
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'social_url',
		'rel' => '',
		'selector' => '.pagelayer-ele-link'
	));
	
	if(empty($el['atts']['icon'])) return;
	$icon = explode(' fa-', $el['atts']['icon']);
	$el['classes'][] = ['.pagelayer-icon-holder' => 'pagelayer-'.$icon[1]];
}

// Image Handler
function pagelayer_sc_image(&$el){
	
	// Decide the image URL
	$el['atts']['func_id'] = @$el['tmp']['id-'.$el['atts']['id-size'].'-url'];
	$el['atts']['func_id'] = empty($el['atts']['func_id']) ? @$el['tmp']['id-url'] : $el['atts']['func_id'];
	$el['atts']['pagelayer-srcset'] = $el['atts']['func_id'].', '.$el['atts']['func_id'].' 1x, ';
	
	$image_atts = array(
		'name' => 'id',
		'size' => 'id-size'
	);
	
	pagelayer_get_img_srcset($el, $image_atts);
		
	// What is the link ?
	if(!empty($el['atts']['link_type'])){
		
		// Custom url
		if($el['atts']['link_type'] == 'custom_url'){
			
			// Backward compatibility for new link props
			pagelayer_add_link_backward($el, array( 'rel' => '', 'selector' => '.pagelayer-ele-link'));
			
			$el['atts']['func_link'] = @$el['tmp']['link'];
		}
		
		// Link to the media file itself
		if($el['atts']['link_type'] == 'media_file'){
			$el['atts']['func_link'] = $el['atts']['func_id'];
		}
		
		// Lightbox
		if($el['atts']['link_type'] == 'lightbox'){
			$el['atts']['func_link'] = $el['atts']['func_id'];
		}
		
	}
	
	//pagelayer_print($el);
	
}

// Image Slider Handler
function pagelayer_sc_image_slider(&$el){
	
	// Backward compatibility for new link props
	if( !empty($el['atts']['link_type']) && $el['atts']['link_type'] == 'custom_url' ){
		pagelayer_add_link_backward($el, array( 'rel' => '', 'selector' => '.pagelayer-link-sel'));
	}
	
	if(empty($el['atts']['ids'])){
		$el['atts']['ids'] = '';
	}
	
	$ids = pagelayer_maybe_explode(',', $el['atts']['ids']);
	$urls = [];
	$all_urls = [];
	$final_urls = [];
	$ul = [];
	$size = $el['atts']['size'];
	
	// Make the image URL
	foreach($ids as $k => $v){
		
		$image = pagelayer_image($v);
		
		$final_urls[$v] = empty($image[$size.'-url']) ? @$image['url'] : $image[$size.'-url'];
		
		$urls['i'.$v] = @$image['url'];
		
		foreach($image as $kk => $vv){
			$si = strstr($kk, '-url', true);
			if(!empty($si)){
				$all_urls['i'.$v][$si] = $vv;
			}
		}
		
		$li = '<li class="pagelayer-slider-item">';
		
		// Any Link ?
		if(!empty($el['atts']['link_type'])){
			$link = ($el['atts']['link_type'] == 'media_file' ? (!empty($image['url']) ? $image['url'] : $final_urls[$v]) : @$el['tmp']['link']);
			$li .= '<a href="'.$link.'" class="pagelayer-link-sel">';
		}
		
		// The Image
		$li .= '<img class="pagelayer-img" src="'.$final_urls[$v].'" title="'.$image['title'].'" alt="'.$image['alt'].'">';
		
		if(!empty($el['atts']['link_type'])){
			$li .= '</a>';
		}
		
		$li .= '</li>';
		
		$ul[] = $li;
		
	}
	
	//pagelayer_print($urls);
	//pagelayer_print($final_urls);
	//pagelayer_print($all_urls);
	
	// Make the TMP vars
	if(!empty($urls)){
		$el['tmp']['ids-urls'] = json_encode($urls);
		$el['tmp']['ids-all-urls'] = json_encode($all_urls);
		$el['atts']['ul'] = implode('', $ul);
	
		// Which arrows to show
		if(in_array(@$el['atts']['controls'], ['arrows', 'none'])){
			$el['attr'][] = ['.pagelayer-image-slider-ul' => 'data-pager="false"'];
		}
		
		if(in_array(@$el['atts']['controls'], ['pager', 'none'])){
			$el['attr'][] = ['.pagelayer-image-slider-ul' => 'data-controls="false"'];
		}
	}
	
};

//Grid Gallery Handler
function pagelayer_sc_grid_gallery(&$el){
	
	if(empty($el['atts']['ids'])){
		$el['atts']['ids'] = '';
	}
	
	$ids = pagelayer_maybe_explode(',', $el['atts']['ids']);
	$urls = [];
	$all_urls = [];
	$final_urls = [];
	$ul = [];
	$pagin = '<li class="pagelayer-grid-page-item active">1</li>';
	$size = $el['atts']['size'];
	$i = 0;
	$j = 1;
	$img_Page = $el['atts']['images_no'];
	$gallery_rand = 'gallery-id-'.floor((rand() * 100) + 1);
	
	$ul[] = '<ul class="pagelayer-grid-gallery-ul">';
	// Make the image URL
	foreach($ids as $k => $v){
		
		$image = pagelayer_image($v);
		
		$final_urls[$v] = empty($image[$size.'-url']) ? @$image['url'] : $image[$size.'-url'];
		
		$urls['i'.$v] = @$image['url'];
		$links['i'.$v] = @$image['link'];
		$titles['i'.$v] = @$image['title'];
		$captions['i'.$v] = @$image['caption'];
		
		foreach($image as $kk => $vv){
			$si = strstr($kk, '-url', true);
			if(!empty($si)){
				$all_urls['i'.$v][$si] = $vv;
			}
		}
		
		if($img_Page != 0 && ($i % $img_Page) == 0 && $i != 0 ){
			$ul[] = '</ul><ul class="pagelayer-grid-gallery-ul">';
			$j++;
			$pagin .= '<li class="pagelayer-grid-page-item">'.$j.'</li>';
		}
		
		$li = '<li class="pagelayer-gallery-item" >';
		
		if(empty($el['atts']['link_to'])){
			$li .= '<div>';
		}
		
		// Any Link ?
		if(!empty($el['atts']['link_to']) &&  $el['atts']['link_to'] == 'media_file'){
			$link = ($el['atts']['link_to'] == 'media_file' ? $final_urls[$v] : @$el['atts']['link']);
			$li .= '<a href="'.$link.'" class="pagelayer-ele-link">';
		}
		
		// Any Link ?
		if(!empty($el['atts']['link_to']) &&  $el['atts']['link_to'] == 'attachment' ){
			$link = $image['link'];
			$li .= '<a href="'.$link.'" class="pagelayer-ele-link">';
		}
		
		if(!empty($el['atts']['link_to']) && $el['atts']['link_to'] == 'lightbox'){			
			$li .= '<a href="'.$image['url'].'" data-lightbox-gallery="'.$gallery_rand.'" alt="'.$image['alt'].'" class="pagelayer-ele-link" pagelayer-grid-gallery-type="'.$el['atts']['link_to'].'">';
		}
		// The Image
		$li .= '<img class="pagelayer-img" src="'.$final_urls[$v].'" title="'.$image['title'].'" alt="'.$image['alt'].'">';
		
		if(!empty($el['atts']['caption'])){
			$li .= '<span class="pagelayer-grid-gallery-caption">'.$image['caption'].'</span>';
		}
		
		if(!empty($el['atts']['link_to'])){
			$li .= '</a>';
		}
		
		if(empty($el['atts']['link_to'])){
			$li .= '</div>';
		}
		
		$li .= '</li>';
		
		$ul[] = $li;
		$i++;
	}
	
	$ul[] = '</ul>';
	
	$pagiComplete[] = '<div class="pagelayer-grid-gallery-pagination"><ul class="pagelayer-grid-page-ul">'.'<li class="pagelayer-grid-page-item">&laquo;</li>'.$pagin.'<li class="pagelayer-grid-page-item">&raquo;</li>'.'</ul></div>';
	//pagelayer_print($urls);
	//pagelayer_print($final_urls);
	//pagelayer_print($all_urls);
	
	// Make the TMP vars
	if(!empty($urls)){
		$el['tmp']['ids-urls'] = json_encode($urls);
		$el['tmp']['ids-all-urls'] = json_encode($all_urls);
		$el['tmp']['ids-all-links'] = json_encode($links);
		$el['tmp']['ids-all-titles'] = json_encode($titles);
		$el['tmp']['ids-all-captions'] = json_encode($captions);
		$el['atts']['ul'] = implode('', $ul);
		$el['atts']['pagin'] = ($j>1) ? implode('', $pagiComplete) : '';	
		$el['tmp']['gallery-random-id'] = $gallery_rand;
	
	}
}

// Testimonial Handler
function pagelayer_sc_testimonial(&$el){
	
	if(empty($el['atts']['avatar']) || !empty($el['tmp']['avatar-no-image-set'])){
		$el['atts']['avatar'] = '';
	}
	
	$custom_size = empty($el['atts']['custom_size']) ? '' : @$el['tmp']['avatar-'.$el['atts']['custom_size'].'-url'];
	$el['atts']['func_image'] = empty($custom_size) ? @$el['tmp']['avatar-url'] : $custom_size;
	
}

// Video Handler
function pagelayer_sc_video(&$el){
	
	$el['atts']['custom_size'] = empty($el['atts']['custom_size']) ? '' : $el['atts']['custom_size'];
	$el['tmp']['video_overlay_image-url'] = empty($el['tmp']['video_overlay_image-url']) ? '' : $el['tmp']['video_overlay_image-url'];
	$el['atts']['video_overlay_image'] = empty($el['atts']['video_overlay_image']) ? '' : $el['atts']['video_overlay_image'];
	
	$el['atts']['video_overlay_image-url'] = empty($el['tmp']['video_overlay_image-'.$el['atts']['custom_size'].'-url']) ? $el['tmp']['video_overlay_image-url'] : $el['tmp']['video_overlay_image-'.$el['atts']['custom_size'].'-url'];
	$el['atts']['video_overlay_image-url'] = empty($el['atts']['video_overlay_image-url']) ? $el['atts']['video_overlay_image'] : $el['atts']['video_overlay_image-url'];
	
	// Get the video URL for the iframe
	$vid_atts = pagelayer_video_url($el['tmp']['src-url'], true);
	
	$vid_atts['src'] .= substr_count($vid_atts['src'], '?') > 0 ? '' : '?';
	
	$vid_atts['src'] .= !empty($el['atts']['autoplay']) ? '&autoplay=1' : '&autoplay=0' ;

	$mute = !empty($el['atts']['mute']) ? 1 : 0;
	$vid_atts['src'] .='&'.($vid_atts['type'] == 'vimeo' ? 'muted' : 'mute').'='.$mute;
  
	$vid_atts['src'] .= !empty($el['atts']['loop']) == 'true' ? '&loop=1' : '&loop=0' ;
	
	$el['atts']['vid_src'] = $vid_atts['src'].($vid_atts['type'] == 'youtube' ? '&playlist='.$vid_atts['id'] : '');
	
	$el['tmp']['ele_id'] = $el['id'];
	
}

// Shortcodes Handler
function pagelayer_sc_shortcodes(&$el){
	$is_live = pagelayer_is_live();
	if(empty($is_live)){
		$el['tmp']['shortcode'] = pagelayer_the_content($el['atts']['data']);
	}
}

// Missing Handler
function pagelayer_sc_missing(&$el){
	if(pagelayer_is_live()){
		$el['tmp']['missing_msg'] = __('The pagelayer doesn’t support this content. You can leave this block intact or remove it completely.');
	}
}

// Shortcodes Handler
function pagelayer_sc_wp_widgets(&$el){
	
	global $wp_registered_sidebars;
	
	$data = '';	
	foreach($wp_registered_sidebars as $v){
		if($el['atts']['sidebar'] == $v['id']){
			ob_start();
			dynamic_sidebar($v['id']);
			$data = ob_get_clean();
		}
	}
	
	$el['tmp']['data'] = $data;
}

// Service Handler
function pagelayer_sc_service(&$el){
	
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'box_url',
		'rel' => '',
		'target' => 'box_target',
		'selector' => '.pagelayer-box-link'
	));
	
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'heading_url',
		'rel' => '',
		'target' => 'heading_target',
		'selector' => '.pagelayer-service-heading-link'
	));
	
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'service_button_url',
		'rel' => '',
		'target' => 'service_button_target',
		'selector' => '.pagelayer-service-btn'
	));
	
	if(!empty($el['atts']['service_image'])){
		
		// Decide the image URL
		$el['atts']['func_image'] = @$el['tmp']['service_image-'.$el['atts']['service_image_size'].'-url'];
		$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$el['tmp']['service_image-url'] : $el['atts']['func_image'];
		$el['atts']['pagelayer-srcset'] = $el['atts']['func_image'].', '.$el['atts']['func_image'].' 1x, ';
		
		$image_atts = array(
			'name' => 'service_image',
			'size' => 'service_image_size'
		);
	
		pagelayer_get_img_srcset($el, $image_atts);
		
	}
}

// Icon box Handler
function pagelayer_sc_iconbox(&$el){
	
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'box_url',
		'rel' => '',
		'target' => 'box_target',
		'selector' => '.pagelayer-box-link'
	));
	
	//Backward compatibility for new link props
	pagelayer_add_link_backward($el, array(
		'link' => 'heading_url',
		'rel' => '',
		'target' => 'heading_target',
		'selector' => '.pagelayer-service-heading-link',
	));
	
}

function pagelayer_sc_google_maps(&$el){
	
	$el['atts']['show_v2'] = true;
	
	if(empty($el['atts']['api_version'])){
		$el['atts']['src_code'] = '';
		return;
	}
	
	$el['atts']['show_v2'] = false;
	$api_key = @$el['atts']['api_key'];
	
	if( empty($api_key) && !empty(get_option('pagelayer-gmaps-api-key')) ){
		$api_key = get_option('pagelayer-gmaps-api-key');
	}
	
	if($el['atts']['map_modes'] == 'view'){
		$el['atts']['center'] = empty($el['atts']['center']) ? '-33.8569,151.2152' : $el['atts']['center'];
	}
	
	$src_code = (empty($el['atts']['center']) ? '' : '&center='.$el['atts']['center']).($el['atts']['map_modes'] == 'streetview' ? '' : '&maptype='.$el['atts']['map_type'].'&zoom='.$el['atts']['zoom']);
	
	switch($el['atts']['map_modes']){
		case 'place':
			$src_code .= '&q='.(empty($el['atts']['address']) ? 'New York, New York, USA' : urlencode($el['atts']['address']) );
			break;
			
		case 'directions':
			$src_code .= '&origin='.(empty($el['atts']['direction_origin']) ? 'Oslow Norway' : urlencode($el['atts']['direction_origin']) );
			$src_code .= '&destination='.(empty($el['atts']['direction_destination']) ? 'Telemark Norway' : urlencode($el['atts']['direction_destination']) );
			$src_code .= (empty($el['atts']['direction_waypoints']) ? '' : '&waypoints='.join('|', explode(' ', trim($el['atts']['direction_waypoints']))) );
			$src_code .= (empty($el['atts']['direction_modes']) ? '' : '&mode='.$el['atts']['direction_modes'] );
			$src_code .= (empty($el['atts']['direction_avoid']) ? '' : '&avoid='.join('|', explode(',', $el['atts']['direction_avoid'])) );
			$src_code .= (empty($el['atts']['direction_units']) ? '' : '&units='.$el['atts']['direction_units'] );
			break;
			
		case 'streetview':
			$src_code .= '&pano='.(empty($el['atts']['streetview_pano']) ? 'eTnPNGoy4bxR9LpjjfFuOw' : $el['atts']['streetview_pano'] );
			$src_code .= '&location='.(empty($el['atts']['streetview_location']) ? '46.414382,10.013988' : $el['atts']['streetview_location'] );
			$src_code .= (empty($el['atts']['streetview_heading']) ? '' : '&heading='.$el['atts']['streetview_heading'] );
			$src_code .= (empty($el['atts']['streetview_pitch']) ? '' : '&pitch='.$el['atts']['streetview_pitch'] );
			$src_code .= (empty($el['atts']['streetview_fov']) ? '' : '&fov='.$el['atts']['streetview_fov'] );
			break;
			
		case 'search':
			$src_code .= '&q='.(empty($el['atts']['search_term']) ? 'Record stores in Seattle' : urlencode($el['atts']['search_term']) );
			break;
			
	}
	
	$src_iframe = 'https://www.google.com/maps/embed/v1/'.$el['atts']['map_modes'].'?key='.$api_key.$src_code;
	
	$el['atts']['src_code'] = '<iframe width="600" height="450" style="border:0" loading="lazy" allowfullscreen src="'.$src_iframe.'"></iframe>';
	
}

/*pagelayer_print($atts);
pagelayer_print($content);
die();*/

/////////////////////////////////////
// Miscellaneous Shortcode Functions
/////////////////////////////////////

// The font family list
function pagelayer_font_family(){
	return array(
		'arial' => 'Arial',				
		'terminal' => 'Terminal'
	);
}

// Supported Icons
function pagelayer_icon_class_list(){
	return array();
}

// Function to convert string into set of attributes and their corresponding values.
function pagelayer_string_to_attributes($val){
	
	$final_att = [];
	$semi_arr = explode(';', $val);
	
	foreach($semi_arr as $att){
		
		$attrs = preg_split("/=/", trim($att), 2);

		if(empty($attrs[0]) || !preg_match("/^[a-z_]+[\w:.-]*$/i", $attrs[0]) ){
			continue;
		}	
		
		if(!isset( $attrs[1])){
			$final_att[$attrs[0]] = '';
			continue;
		}
		
		$final_att[$attrs[0]] = $attrs[1];
	}
	
	return $final_att;
	
}

// Retina image setting attribute.
function pagelayer_get_img_srcset(&$el, $image_atts){

	// Check if retina images is set
	if(isset($el['tmp'][$image_atts['name'].'-retina-url']) && strpos($el['tmp'][$image_atts['name'].'-retina-url'],'default-image') == false){
		$retina_image = @$el['tmp'][$image_atts['name'].'-retina-'.$el['atts'][$image_atts['size']].'-url'];
		$retina_image = empty($retina_image) ? @$el['tmp'][$image_atts['name'].'-retina-url'] : $retina_image;
		$el['atts']['pagelayer-srcset'] .= $retina_image.' 2x, ';			
	}
	
	// Check if retina mobile images is set
	if(isset($el['tmp'][$image_atts['name'].'-retina-mobile-url']) && strpos($el['tmp'][$image_atts['name'].'-retina-mobile-url'],'default-image') == false){			
		$retina_image_mobile = @$el['tmp'][$image_atts['name'].'-retina-mobile-'.$el['atts'][$image_atts['size']].'-url'];
		$retina_image_mobile = empty($retina_image_mobile) ? @$el['tmp'][$image_atts['name'].'-retina-mobile-url'] : $retina_image_mobile;		
		$el['atts']['pagelayer-srcset'] .= $retina_image_mobile.' 3x';
	}
}

// Backward compatibility of target and rel attrs for link
function pagelayer_add_link_backward(&$el, $atts = array()){
	global $pagelayer;
	
	$defaults = array(
		'link' => 'link',
		'target' => 'target',
		'rel' => 'rel',
		'selector' => 0,
	);
	
	$_atts = wp_parse_args( $atts, $defaults );
	
	if(empty($el['atts'][$_atts['link']])){
		return;
	}

	$link = array();
	
	if(!empty($_atts['target']) && !empty($el['atts'][$_atts['target']]) ){
		$link['target'] = true;
		unset($el['oAtts'][$_atts['target']]);
	}
	
	if(!empty($_atts['rel']) && !empty($el['atts'][$_atts['rel']]) ){
		$link['attrs'] = 'rel='.$el['atts'][$_atts['rel']]; 
		unset($el['oAtts'][$_atts['rel']]);
	}
	
	if(empty($link)){
		return;
	}
	
	// Set Attributes for backward compatibility
	if(!empty($link['target'])){
		$el['attr'][][$_atts['selector']] = 'target="_blank"';
	}
	
	// Set Attributes for backward compatibility
	if(!empty($link['attrs']) ){
		$el['attr'][][$_atts['selector']] = $link['attrs'];
	}
	
	if(!is_array($el['atts'][$_atts['link']])){
		$link['link'] = @$el['atts'][$_atts['link']];
	}
	
	$el['oAtts'][$_atts['link']] = $link;
	$el['atts'][$_atts['link']] = $link;
}
