<?php

//////////////////////////////////////////////////////////////
//===========================================================
// license.php
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

function pagelayer_clear_empty_r(&$r){
	
	foreach($r as $a => $b){
		if(empty($b)){
			unset($r[$a]);
			continue;
		}
		
		if(is_array($b)){
			pagelayer_clear_empty_r($r[$a]);
		}
	}
	
	return $r;
	
}

// The License Page
function pagelayer_website_settings(){
	
	global $pagelayer, $pl_error;
	
	pagelayer_load_font_options();
	
	if(!empty($_POST)){
		check_admin_referer('pagelayer-options');
	}
	
	if(isset($_POST['submit'])){
		
		foreach($pagelayer->css_settings as $set => $params){
			
			foreach($pagelayer->screens as $sk => $sv){
				
				$suffix = (!empty($sv) ? '_'.$sv : '');
				$key = $set.$suffix;
				$setting = empty($params['key']) ? 'pagelayer_'.$set.'_css' : $params['key'];
					
				if(isset($_POST[$key])){
					
					foreach($_POST[$key] as $k => $v){
						if($v == 'Default' || empty($v)){
							unset($_POST[$key][$k]);
						}
					
						// For sidebar, width default should not be saved
						if($set == 'sidebar' && $k == 'width' && $v == 20){
							unset($_POST[$key][$k]);
						}
					}
					
					// Padding and Margins or any array based setting
					if(!empty($_POST[$key]) && is_array($_POST[$key])){
						pagelayer_clear_empty_r($_POST[$key]);
						//pagelayer_print($_POST[$key]);
					}
					
					// Are we to save ?
					if(!empty($_POST[$key])){
						update_option($setting.$suffix, (!empty($_POST[$key]) ? $_POST[$key] : []));
					}else{
						delete_option($setting.$suffix);
					}
					
				}else{
					delete_option($setting.$suffix);
				}
				
			}
			
		}
		
		// Blank the old color values
		delete_option('pagelayer_color');
			
		// Blank the old Body font
		if(!empty($_POST['body']['font-family'])){
			update_option('pagelayer_body_font', '');
		}
		
		//pagelayer_print($_POST);		
	
		// Content Width
		if(isset($_REQUEST['pagelayer_content_width'])){
			update_option( 'pagelayer_content_width', $_REQUEST['pagelayer_content_width'] );
		}

		// Tablet breakpoint 
		if(isset($_REQUEST['pagelayer_tablet_breakpoint'])){			
			update_option( 'pagelayer_tablet_breakpoint', $_REQUEST['pagelayer_tablet_breakpoint'] );			
		}

		// Mobile breakpoint 
		if(isset($_REQUEST['pagelayer_mobile_breakpoint'])){
			update_option( 'pagelayer_mobile_breakpoint', $_REQUEST['pagelayer_mobile_breakpoint'] );
		}
		
		// Widget Space
		if(isset($_REQUEST['pagelayer_between_widgets'])){
			update_option( 'pagelayer_between_widgets', $_REQUEST['pagelayer_between_widgets'] );
		}
		
		if(defined('PAGELAYER_PREMIUM')){
		
			// Save Header code
			if(isset($_REQUEST['pagelayer_header_code'])){	
				$header_code = wp_unslash($_REQUEST['pagelayer_header_code']);
				if(!pagelayer_user_can_add_js_content()){
					$header_code = sanitize_textarea_field($header_code);
				}
				
				update_option( 'pagelayer_header_code', $header_code);
			}else{
				delete_option('pagelayer_header_code');
			}

			// Save Body open code
			if(isset($_REQUEST['pagelayer_body_open_code'])){	
				$body_code = wp_unslash($_REQUEST['pagelayer_body_open_code']);
				if(!pagelayer_user_can_add_js_content()){
					$body_code = sanitize_textarea_field($body_code);
				}
				
				update_option( 'pagelayer_body_open_code', $body_code);
			}else{
				delete_option('pagelayer_body_open_code');
			}

			// Save Footer code
			if(isset($_REQUEST['pagelayer_footer_code'])){
				$footer_code = wp_unslash($_REQUEST['pagelayer_footer_code']);
				if(!pagelayer_user_can_add_js_content()){
					$footer_code = sanitize_textarea_field($footer_code);
				}
				
				update_option( 'pagelayer_footer_code', $footer_code);
			}else{
				delete_option('pagelayer_footer_code');
			}
		
		}
		
		$GLOBALS['pl_saved'] = true;
		
	}
	
	pagelayer_website_settings_T();
	
}

// The License Page - THEME
function pagelayer_website_settings_T(){
	
	global $pagelayer, $pl_error;

	pagelayer_page_header('Pagelayer Website Settings');

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div class="notice notice-success"><p>'. __('The settings were saved successfully', 'pagelayer'). '</p></div><br />';
	}

	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}
	
	// Reduce load
	echo '<select id="skeleton_of_fonts" style="display:none">';
	foreach($pagelayer->fonts as $subType => $fontType){
		if($subType != 'default'){
			echo '<optgroup style="text-transform: capitalize" label="'.$subType.'">';
		}
		foreach($fontType as $k => $font){
			echo '<option value="'.esc_html(is_numeric($k) ? $font : $k).'">'. esc_html(empty($font) ? 'Default': $font) .'</option>';
		}		
	}
	echo '</select>';
	
	?>
	
<form class="pagelayer-setting-form" method="post" action="">
	<?php wp_nonce_field('pagelayer-options'); ?>
	<script src="https://unpkg.com/vanilla-picker@2.10.1/dist/vanilla-picker.min.js"></script>
	<div class="tabs-wrapper">
		<h2 class="nav-tab-wrapper pagelayer-wrapper">
			<a href="#headings" class="nav-tab "><?php echo __pl('elem_styles');?></a>
			<a href="#website_container" class="nav-tab"><?php echo __pl('container');?></a>
			<!--<a href="#pagelayer-sidebar" class="nav-tab">Sidebar</a>-->
			<a href="#hf" class="nav-tab "><?php echo __pl('hf');?></a>
		</h2>
		
		<div class="pagelayer-tab-panel" id="headings">
			
			<?php
			
			echo '<div style="display:inline-block;vertical-align: top;">
			<ul class="nav-tab-wrapper pagelayer-wrapper pagelayer-heading-wrapper">';
				
			foreach($pagelayer->css_settings as $k => $v){
				echo '<li><a href="#tab_'.$k.'" class="nav-tab pagelayer-heading-tab" tab-class="pagelayer-heading-tab-panel">'.$v['name'].' Style</a></li>';				
			}
			
			echo '</ul>
			</div>
			
			<div style="display:inline-block;vertical-align: top;">';
			
			foreach($pagelayer->css_settings as $k => $v){
				
				echo '<div class="pagelayer-heading-tab-panel" id="tab_'.$k.'">
				<center><h2>'.$v['name'].' Style</h2></center>
				
				<div style="vertical-align: top;">
				<ul class="nav-tab-wrapper pagelayer-wrapper pagelayer-styles-screens">';
				
				foreach($pagelayer->screens as $sk => $sv){
					echo '<li><a href="#tab_'.$k.'_'.$sk.'" class="nav-tab pagelayer-styles-screen-tab" tab-class="pagelayer-styles-screen-panel">'.ucfirst($sk).'</a></li>';					
				}
				
				echo '</ul>
				</div>';
				
				foreach($pagelayer->screens as $sk => $sv){
					echo '<div class="pagelayer-styles-screen-panel" id="tab_'.$k.'_'.$sk.'">';
					pagelayer_website_font_settings($k.(!empty($sv) ? '_'.$sv : ''));
					echo '</div>';
				}
				
				echo '</div>';
			}
			
			echo '</div>';
			
			?>
		
		</div>
	
		<div class="pagelayer-tab-panel" id="website_container">
		
			<table>	
			
				<tr>
					<th><?php echo __('Content Width') ?></th>
					<td>
						<input name="pagelayer_content_width" type="number" step="1" min="320" max="5000" placeholder="1170" <?php if(get_option('pagelayer_content_width')){
							echo 'value="'.get_option('pagelayer_content_width').'"';
						}?>>
						<p><?php echo __('Set the custom width of the content area. The default width set is 1170px.') ?></p>
					</td>
				<tr>
				<tr>
					<th><?php echo __('Tablet Breakpoint') ?></th>
					<td>
						<input name="pagelayer_tablet_breakpoint" type="number" step="1" min="320" max="5000" placeholder="780" <?php if(get_option('pagelayer_tablet_breakpoint')){
							echo 'value="'.get_option('pagelayer_tablet_breakpoint').'"';
						}?>>
						<p><?php echo __('Set the breakpoint for tablet devices. The default breakpoint for tablet layout is 768px.') ?></p>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Mobile Breakpoint') ?></th>
					<td>
						<input name="pagelayer_mobile_breakpoint" type="number" step="1" min="320" max="5000" placeholder="360" <?php if(get_option('pagelayer_mobile_breakpoint')){
							echo 'value="'.get_option('pagelayer_mobile_breakpoint').'"';
						}?>>
						<p><?php echo __('Set the breakpoint for mobile devices. The default breakpoint for mobile layout is 360px.') ?></p>
					</td>
				</tr>
				<tr>
					<th><?php echo __('Space Between Widgets') ?></th>
					<td>
						<input name="pagelayer_between_widgets" type="number" step="1" min="0" max="500" placeholder="15" <?php if(get_option('pagelayer_between_widgets')){
							echo 'value="'.get_option('pagelayer_between_widgets').'"';
						}?>>
						<p><?php echo __('Set the Space Between Widgets. The default Space set is 15px.') ?></p>
					</td>
				<tr>
		
			</table>
		
		</div>
	
		<div class="pagelayer-tab-panel" id="pagelayer-sidebar">
		
			<table width="100%">
				<tr>
					<td colspan="2">
						<b><?php echo __('Sidebar Preferences');?> :</b>
						<p><?php echo __('By default, the themes sidebar will be shown. But you can customize the settings here as per your preference. Note : This will work only if your theme uses the get_sidebar() function. Also the main content element and sidebar element should be siblings. If they are not siblings, then only the <b>No Sidebar</b> option will be usable.');?></p>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('Default');?> : </th>
					<td>
						<?php pagelayer_sidebar_select('default');?>
						<p> <?php echo __('Default layout for the Sidebar throughout the site', 'pagelayer') ?> </p>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('For Pages');?> : </th>
					<td>
						<?php pagelayer_sidebar_select('page');?>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('For Posts');?> : </th>
					<td>
						<?php pagelayer_sidebar_select('post');?>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('For Archives');?> : </th>
					<td>
						<?php pagelayer_sidebar_select('archives');?>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('Width');?> : </th>
					<td>
						<input type="number" name="sidebar[width]" min="1" step="1" value="<?php echo (!empty($_POST) ? esc_html($_POST['sidebar']['width']) : (!empty($pagelayer->css['sidebar']['width']) ? esc_html($pagelayer->css['sidebar']['width']) : '20') );?>" /><span>%</span>
					</td>
				</tr>
			</table>
			
		</div>
		
		<div class="pagelayer-tab-panel" id="hf">
			<?php pagelayer_show_pro_notice();?>
			<table width="100%">
				<tr>
					<td colspan="2">
						<b><?php echo __('Header, Body and Footer code');?> :</b>
						<p><?php echo __('You can add custom code like HTML, JavaScript, CSS etc. which will be inserted throughout your site.');?></p>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('Header Code');?> : </th>
					<td>
						<textarea name="pagelayer_header_code" style="width:80%;" rows="6"><?php echo get_option( 'pagelayer_header_code' ); ?></textarea>
						<p> <?php echo __('This code will be printed in <code>&lt;head&gt;</code> Section.') ?> </p>
					</td>
				</tr>
				<tr>
					<th valign="top"><?php echo __('Body Open Code');?> : </th>
					<td>
						<textarea name="pagelayer_body_open_code" style="width:80%;" rows="10"><?php echo  get_option( 'pagelayer_body_open_code' ); ?></textarea>
						<p> <?php echo __('This code will be printed from begning of the <code>&lt;body&gt;</code> Section.') ?> </p>
					</td>
				</tr>
				<tr>
				<tr>
					<th valign="top"><?php echo __('Footer Code');?> : </th>
					<td>
						<textarea name="pagelayer_footer_code" style="width:80%;" rows="6"><?php echo  get_option( 'pagelayer_footer_code' ); ?></textarea>
						<p> <?php echo __('This code will be printed before closing the <code>&lt;/body&gt;</code> Section.') ?> </p>
					</td>
				</tr>
			</table>
		</div>
		
	</div>
			
	<?php echo __pl('color_notice');?>
	<br><br>
	<center><input type="submit" name="submit" class="button button-primary button-submit" value="Save Changes" onclick="pagelayer_handle_website_submit(this)"></center>
	<br /><br />
</form>

<script>

function pagelayer_handle_website_submit(ele){
	
	var jEle = jQuery(ele);
	jEle.closest('form').find('input, select, textarea').each(function(){
		var j = jQuery(this);
		if(jEle.is(j)){
			return;
		}
		
		if(j.val().length == 0){
			j.prop("disabled", true);
		}
	});
	
	return true;
}
	
// Show the vanilla selector
function pagelayer_show_vanilla(){
	jQuery('.pagelayer-show-vanilla').each(function(){
		var jEle = jQuery(this);
		var par = jEle.parent();
		var input = par.find('input');
		var sColor = '';
		
		if(input.val().length > 0){
			sColor = input.val();
			jEle.find('.pagelayer-color-div').css('background', sColor);
			jEle.find('.pagelayer-color-div').removeClass('pagelayer-color-none');
		}
		
		var picker = new Picker({
			parent : jEle[0],
			color : sColor,
		});
		
		// You can do what you want with the chosen color using two callbacks: onChange and onDone.
		picker.onChange = function(color) {
			jEle.find('.pagelayer-color-div').css('background', color.rgbaString);
			jEle.find('.pagelayer-color-div').removeClass('pagelayer-color-none');
			input.val(color.hex);
		};
		
		jEle.find('.dashicons').on('click', function(event){
			event.preventDefault();
			event.stopPropagation();
			jEle.find('.pagelayer-color-div').addClass('pagelayer-color-none');
			input.val('');
		});
	});
}

function pagelayer_handle_custom(ele){
	jEle = jQuery(ele);
	if(jEle.val().length > 1){
		jEle.siblings().show();
	}else{
		jEle.siblings().hide();
		jEle.siblings('input').val('');
		jEle.siblings().children().val('');
	}
}

// Handle the font family
function pagelayer_handle_font_family(ele){
	jEle = jQuery(ele);
	if(jEle.children().length <= 1){
		var val = jEle.val();
		jEle.html(jQuery('#skeleton_of_fonts').html());
		jEle.val(val);
	}
}

function pagelayer_handle_textdecor(ele){
	jEle = jQuery(ele);
	if(jEle.val().length > 1 && jEle.val() !== 'none'){
		jEle.siblings().show();
	}else{
		jEle.siblings().hide();
		jEle.siblings().val('');
	}
}

jQuery(document).ready(function(){
	pagelayer_show_vanilla();
	jQuery('.pagelayer-show-custom').each(function(){
		pagelayer_handle_custom(jQuery(this));
	});
	
});
</script>

<?php
	
	pagelayer_page_footer();

}

function pagelayer_website_padding_field($name, $val){
?>
	<input type="number" name="<?php echo $name;?>[0]" step="1" class="pagelayer-website-padding" <?php echo (!empty($val[0]) ? 'value="'.esc_html($val[0]).'"' : '');?> />
	<input type="number" name="<?php echo $name;?>[1]" step="1" class="pagelayer-website-padding" <?php echo (!empty($val[1]) ? 'value="'.esc_html($val[1]).'"' : '');?> />
	<input type="number" name="<?php echo $name;?>[2]" step="1" class="pagelayer-website-padding" <?php echo (!empty($val[2]) ? 'value="'.esc_html($val[2]).'"' : '');?> />
	<input type="number" name="<?php echo $name;?>[3]" step="1" class="pagelayer-website-padding" <?php echo (!empty($val[3]) ? 'value="'.esc_html($val[3]).'"' : '');?> /><span>px</span>
<?php	
}

// Shows the font settings
function pagelayer_website_font_settings($prefix){
	
	global $pagelayer, $pl_error;
	
	// load css from settings
	pagelayer_load_global_css();
	
	if(!empty($_POST)){
		$vals = $_POST;
	}else{
		$vals = $pagelayer->css;
	}
	
	?>
	
	<table>
		
		<tr>
			<th scope="row"><?php echo __pl('padding');?></th>
			<td>
				<label>
					<select class="pagelayer-show-custom" onchange="pagelayer_handle_custom(this)">
						<option value="" <?php echo (empty($vals[$prefix]['padding']) ? 'selected="seleted"' : '');?>>Default</option>
						<option value="custom" <?php echo (!empty($vals[$prefix]['padding']) ? 'selected="seleted"' : '');?>>Custom</option>
					</select>
					<span>
					<?php pagelayer_website_padding_field($prefix.'[padding]', @$vals[$prefix]['padding']);?>
					</span>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('margin');?></th>
			<td>
				<label>
					<select class="pagelayer-show-custom" onchange="pagelayer_handle_custom(this)">
						<option value="" <?php echo (empty($vals[$prefix]['margin']) ? 'selected="seleted"' : '');?>>Default</option>
						<option value="custom" <?php echo (!empty($vals[$prefix]['margin']) ? 'selected="seleted"' : '');?>>Custom</option>
					</select>
					<span>
					<?php pagelayer_website_padding_field($prefix.'[margin]', @$vals[$prefix]['margin']);?>
					</span>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('font_family'); ?></th>
			<td>
				<label>
					<select name="<?php echo $prefix;?>[font-family]" onclick="pagelayer_handle_font_family(this)">
					<?php
						echo '<option value="'.esc_html(empty($vals[$prefix]['font-family']) ? 'Default': @$vals[$prefix]['font-family']).'">'.esc_html(empty($vals[$prefix]['font-family']) ? 'Default': @$vals[$prefix]['font-family']).'</option>';
					?>
					</select>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('font_size'); ?></th>
			<td>
				<label>
					<select class="pagelayer-show-custom" onchange="pagelayer_handle_custom(this)">
						<option value="" <?php echo (empty($vals[$prefix]['font-size']) ? 'selected="seleted"' : '');?>>Default</option>
						<option value="custom" <?php echo (!empty($vals[$prefix]['font-size']) ? 'selected="seleted"' : '');?>>Custom</option>
					</select>
					<input type="number" name="<?php echo $prefix;?>[font-size]" <?php echo (!empty($vals[$prefix]['font-size']) ? 'value="'.esc_html($vals[$prefix]['font-size']).'"' : '');?> /><span>px</span>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('font_style'); ?></th>
			<td>
				<label>
					<select name="<?php echo $prefix;?>[font-style]">
					<?php
						foreach($pagelayer->font_style as $k => $var){							
							echo '<option value="'.esc_html($k).'" '.(@$vals[$prefix]['font-style'] == $k ? 'selected' : '').'>'.esc_html($var).'</option>';
						}
					?>
					</select>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('font_weight');?></th>
			<td>
				<label>
					<select name="<?php echo $prefix;?>[font-weight]">
					<?php
						foreach($pagelayer->font_weight as $k => $var){							
							echo '<option value="'.esc_html($k).'" '.(@$vals[$prefix]['font-weight'] == $k ? 'selected' : '').'>'.esc_html($var).'</option>';
						}
					?>
					</select>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('text_transform');?></th>
			<td>
				<label>
					<select name="<?php echo $prefix;?>[text-transform]">
					<?php
						foreach($pagelayer->text_transform as $k => $var){							
							echo '<option value="'.esc_html($k).'" '.(@$vals[$prefix]['text-transform'] == $k ? 'selected' : '').'>'.esc_html($var).'</option>';
						}
					?>
					</select>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('line_height');?></th>
			<td>
				<label>
					<select class="pagelayer-show-custom" onchange="pagelayer_handle_custom(this)">
						<option value="" <?php echo (empty($vals[$prefix]['line-height']) ? 'selected="seleted"' : '');?>>Default</option>
						<option value="custom" <?php echo (!empty($vals[$prefix]['line-height']) ? 'selected="seleted"' : '');?>>Custom</option>
					</select>
					<input type="number" name="<?php echo $prefix;?>[line-height]" min="0.1" step="0.1" <?php echo (!empty($vals[$prefix]['line-height']) ? 'value="'.esc_html($vals[$prefix]['line-height']).'"' : '');?> />
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('text_spacing');?></th>
			<td>
				<label>
					<select class="pagelayer-show-custom" onchange="pagelayer_handle_custom(this)">
						<option value="" <?php echo (empty($vals[$prefix]['letter-spacing']) ? 'selected="seleted"' : '');?>>Default</option>
						<option value="custom" <?php echo (!empty($vals[$prefix]['letter-spacing']) ? 'selected="seleted"' : '');?>>Custom</option>
					</select>
					<input type="number" name="<?php echo $prefix;?>[letter-spacing]" min="1" step="1" <?php echo (!empty($vals[$prefix]['letter-spacing']) ? 'value="'.esc_html($vals[$prefix]['letter-spacing']).'"' : '');?> /><span>px</span>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('word_spacing');?></th>
			<td>
				<label>
					<select class="pagelayer-show-custom" onchange="pagelayer_handle_custom(this)">
						<option value="" <?php echo (empty($vals[$prefix]['word-spacing']) ? 'selected="seleted"' : '');?>>Default</option>
						<option value="custom" <?php echo (!empty($vals[$prefix]['word-spacing']) ? 'selected="seleted"' : '');?>>Custom</option>
					</select>
					<input type="number" name="<?php echo $prefix;?>[word-spacing]" min="1" step="1" <?php echo (!empty($vals[$prefix]['word-spacing']) ? 'value="'.esc_html($vals[$prefix]['word-spacing']).'"' : '');?> /><span>px</span>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php echo __pl('text_decoration');?></th>
			<td>
				<label>
					<table class="pagelayer-internal-table">
						<tr>
						<td>
							<select name="<?php echo $prefix;?>[text-decoration-line]" onchange="pagelayer_handle_textdecor(this)">
							<?php
								foreach($pagelayer->text_decoration_line as $k => $var){							
									echo '<option value="'.esc_html($k).'" '.(@$vals[$prefix]['text-decoration-line'] == $k ? 'selected' : '').'>'.esc_html($var).'</option>';
								}
							?>
							</select>
						</td>
						<td>
							<select name="<?php echo $prefix;?>[text-decoration-style]">
							<?php
								foreach($pagelayer->text_decoration_style as $k => $var){							
									echo '<option value="'.esc_html($k).'" '.(@$vals[$prefix]['text-decoration-style'] == $k ? 'selected' : '').'>'.esc_html($var).'</option>';
								}
							?>
							</select>
						</td>
						</tr>
						<tr>
							<td>Line</td>
							<td>Style</td>
						</tr>
					</table>
				</label>
			</td>
		</tr>
		
		<tr>
			<th scope="row">Background Color</th>
			<td>
				<a href="#" class="pagelayer-show-vanilla"><div class="pagelayer-color-div pagelayer-color-none"></div><span class="dashicons dashicons-no"></span></a><input type="hidden" name="<?php echo $prefix;?>[background-color]" <?php echo (!empty($vals[$prefix]['background-color']) ? 'value="'.esc_html($vals[$prefix]['background-color']).'"' : '');?>>
			</td>
		</tr>
		
		<tr>
			<th scope="row">Text Color</th>
			<td>
				<a href="#" class="pagelayer-show-vanilla"><div class="pagelayer-color-div pagelayer-color-none"></div><span class="dashicons dashicons-no"></span></a><input type="hidden" name="<?php echo $prefix;?>[color]" <?php echo (!empty($vals[$prefix]['color']) ? 'value="'.esc_html($vals[$prefix]['color']).'"' : '');?>>
			</td>
		</tr>
	</table>
	
<?php
	
}

function pagelayer_sidebar_select($name){
	
	global $pagelayer;
	
	$val = isset($pagelayer->settings['sidebar'][$name]) ? $pagelayer->settings['sidebar'][$name] : 'default';
	$val = !empty($_POST) ? @$_POST['sidebar'][$name] : $val;
	
	// We dont save the value "Default" (note case sensitivity), but the theme customizer saves "default"
	// We need to keep all values blank if user is submitting values as Default
	
	echo '
	<select class="pagelayer-show-custom" name="sidebar['.$name.']">
		<option value="Default" '.($val == 'default' ? 'selected="seleted"' : '').'>Default</option>
		<option value="no" '.($val == 'no' ? 'selected="seleted"' : '').'>No Sidebar</option>
		<option value="left" '.($val == 'left' ? 'selected="seleted"' : '').'>Left Sidebar</option>
		<option value="right" '.($val == 'right' ? 'selected="seleted"' : '').'>Right Sidebar</option>
	</select>
	';
}