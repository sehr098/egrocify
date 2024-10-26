<?php

//////////////////////////////////////////////////////////////
//===========================================================
// live.php
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


function pagelayer_live_body(){

global $post, $pagelayer;
	
	$icons = pagelayer_enabled_icons();
	$icons_list = array();
	
	// Load all icons
	foreach($icons as $icon){
		$icons_list[] = $icon.'.min.css';
	}
	
	$css_url = admin_url('admin-ajax.php?action=pagelayer_givecss&pagelayer_nonce=1&');
	if(pagelayer_enable_giver()){
		$css_url = PAGELAYER_CSS.'/givecss.php?';
	}
	
	$dark_mode = get_option('pagelayer_enable_dark_mode');
	$body_class = '';
	if(!empty($dark_mode)){
		$body_class = 'pagelayer-dark';
	};
	
	if(defined('SITEPAD')){
		$body_class .= ' sitepad-body';
	}
	
	$shortcut_groups = [
		'general_shortcuts' => [
			'undo' => 'Ctrl+Z',
			'redo' => 'Ctrl+Y',
			'save_post' => 'Ctrl+S',
			'copy_widget' => 'Ctrl+C',
			'paste_widget' => 'Ctrl+V',
			'duplicate_widget' => 'Ctrl+D',
			'delete_widget' => 'Delete'
		]
	];
	
	echo '
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="'.$css_url.'give=pagelayer-editor.css,trumbowyg.min.css,pagelayer-icons.css,'.implode(',' ,$icons_list).'&ver='.PAGELAYER_VERSION.'">';
	
	do_action('pagelayer_live_body_head');
	
	// Brand Name
	$brand = strtoupper($pagelayer->BRAND_TEXT);
	$brand = str_split($brand);
	
	echo '
<style>
/*Set responsive variables*/
.pagelayer-screen-tablet{
width: '. $pagelayer->settings['tablet_breakpoint'] .'px;
}
.pagelayer-screen-mobile{
width: '. $pagelayer->settings['mobile_breakpoint'] .'px;
}
.pagelayer-errorBox-support{
text-decoration:none;
}
</style>
</head>

	<body class="pagelayer-normalize pagelayer-body '.$body_class.'">
<div class="pagelayer-errorBox">
	<div class="pagelayer-errorBox-close"><i class="fas fa-times"></i></div>
	<div class="pagelayer-errorBox-main">
		<h2><i class="fas fa-times"></i>'.__pl('error').'</h2>
		<div class="pagelayer-errorBox-content"></div>
	</div>
	<div class="pagelayer-errorBox-resolve">
		<p>'.__pl('error_submitting').'</p>
		<button type="button" class="pagelayer-errorBox-copy" onclick="pagelayer_copy_error(event)">'.__pl('copy').'</button>
		<a type="button" class="pagelayer-errorBox-support"  href="'. $pagelayer->support .'" target="_bank">'.__pl('support').'</a>
	</div>
</div>
<div id="pagelayer-loader-wrapper">
	<div class="pagelayer-animation-section">
		<div class="pagelayer-loader">
			<div class="pagelayer-percent-parent">
				<div class="pagelayer-percent">10<sup>%</sup></div>
			</div>
		</div>
		<div class="pagelayer-txt-loading">';
			
			foreach($brand as $k => $v){
				echo '<span data-text-preloader="'.$v.'" class="letters-loading">'.$v.'</span>';
			}
			
		echo '</div>
	</div>
</div>

<table class="pagelayer-normalize pagelayer-body-table" cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" width="270" class="pagelayer-leftbar-table">
		<table class="pagelayer-normalize" cellpadding="0" cellspacing="0">
			<tr class="pagelayer-close-bar">
				<td>
					<div class="pagelayer-close-bar-icons">
						<i class="pagelayer-leftbar-minimize fa fa-minus"></i>
						<i class="pagelayer-leftbar-close fa fa-close"></i>
					</div>
				</td>
			</tr>
			<tr height="45">
				<td class="pagelayer-topbar-holder" valign="middle" align="center">
					<span class="pagelayer-options-icon pli pli-menu"></span>
					<div class="pagelayer-elpd-header" style="display:none">
						<div class="pagelayer-elpd-close"><i class="pli pli-cross" aria-hidden="true"></i></div>
						<div class="pagelayer-elpd-title pagelayer-topbar-mover">Edit</div>
					</div>
					<div class="pagelayer-logo">
						<img src="'.$pagelayer->LOGO.'" width="28" /><span class="pagelayer-logo-text pagelayer-topbar-mover">'.$pagelayer->BRAND_TEXT.(defined('PAGELAYER_PREMIUM') && strtolower($pagelayer->BRAND_TEXT) == 'pagelayer' ? '<sup style="margin: 3px; font-size: 12px; letter-spacing: 1px; font-family: "Roboto";">Pro</sup>' : '').'</span>
						<span class="pagelayer-settings-icon pli pli-service" aria-hidden="true"></span>
					</div>
				</td>
			</tr>
			<tr height="*" valign="top">
				<td style="position: relative;"><div class="pagelayer-leftbar-holder"></div></td>
			</tr>
			<tr height="35" class="pagelayer-bottombar-row">
				<td><div class="pagelayer-bottombar-holder"></div></td>
			</tr>
		</table>
		<div class="pagelayer-leftbar-toggle">&lsaquo;</div>
	</td>
	<td class="pagelayer-iframe" valign="top">
		<div class="pagelayer-iframe-top-bar">';
		do_action('pagelayer_iframe_top_bar');
echo '
		</div>
		<div class="pagelayer-iframe-holder">
			<iframe src="'.(pagelayer_shortlink(0).'&pagelayer-iframe=1&'.$_SERVER['QUERY_STRING']).'" class="pagelayer-normalize skip-lazy" id="pagelayer-iframe"></iframe>
		</div>
	</td>
</tr>
</table>

<div class="pagelayer-pro-notice">';
	pagelayer_show_pro_div('Premium Feature<span class="pli pli-cross pagelayer-pro-x"></span>', '', 0);
echo '</div>
<div class="pagelayer-editor-notice"></div>
<div class="pagelayer-props-modal">
	<div class="pagelayer-props-holder">
		<div class="pagelayer-props-wrap">
			<div class="pagelayer-props-loading-screen"></div>
			<i class="pagelayer-props-modal-close pli pli-cross" aria-hidden="true"></i>
		</div>
	</div>
</div>
<script>
var pagelayer_iframe_cw = document.getElementById("pagelayer-iframe").contentWindow;
var start_time = new Date().getTime();

// Show loading progress
function loader(ran) {
	var inner = document.getElementsByClassName("pagelayer-percent")[0];
	var w = 0;
	var t = setInterval(function() {
		w = w + 1;
		inner.innerHTML = (w+"<sup>%</sup>");
		if (w === ran || inner.getAttribute("loaded") == "1"){
			clearInterval(t);
			w = 0;
		}
	}, 50);
}
loader(90);

function $p(sel){
	return pagelayer_iframe_cw.pagelayer.$$(sel);
}

// Load the window if necessary i.e. an error in the JS of the iframe
function onIframeLoad(){
	setTimeout(function(){
		force_pagelayer_start();
	}, 5000);
}

function force_pagelayer_start(){
try{
	if(pagelayer_iframe_cw.pagelayer.loaded == 1){
		return;
	}
	pagelayer_iframe_cw.pagelayer_start();
	var end_time = new Date().getTime();
	var diff = (end_time - start_time) / 1000;
	console.log("['.$pagelayer->BRAND_TEXT.'] Live Body had to load after : "+diff+" seconds");

// If this fails, lets just hide the loader
}catch(e){
	alert("Fatal error within the '.$pagelayer->BRAND_TEXT.' iFrame as pagelayer object not found ! Please contact '.$pagelayer->BRAND_TEXT.' support");
	document.getElementById("pagelayer-loader-wrapper").remove();
}
}

document.getElementById("pagelayer-iframe").onload = onIframeLoad;

// ErrorBox content copy function
function pagelayer_copy_error(){
	var tempInput = document.createElement("textarea");
	tempInput.value = document.querySelector(".pagelayer-errorBox-content").innerText;
	document.body.appendChild(tempInput);
	tempInput.select();
	document.execCommand("copy");
	document.body.removeChild(tempInput);
	alert("'.__pl('copy_success').'");
}

</script>

<div class="pagelayer-editor-modal pagelayer-shortcuts-modal">
	<div class="pagelayer-editor-modal-wrap">
		<div class="pagelayer-editor-modal-header">
			<h2>'.__pl('keyboard_shortcuts').'</h2>
			<span class="pagelayer-editor-modal-close-icon"><i class="fas fa-times"></i></span>
		</div>
		<div class="pagelayer-editor-modal-body">';
			foreach($shortcut_groups as $grp_name => $group){
			
				if(empty($group)) {
					return;
				}
				
				echo '<div class="pagelayer-edt-modal-block">'. (!empty($grp_name)  ? '<h3>'.__pl($grp_name).'</h3>' :'' ) .'<ul>';
				
				foreach($group as $desc => $shortcut) {
					echo '<li><span class="pagelayer-keyboard-shortcut-desc">'.__pl($desc).'</span>
					<span class="pagelayer-keyboard-shortcut-keys">';
					
					//Considering the whole shortcut as a single key
					$key = '<span>'.$shortcut.'</span>';
					
					//replacing "+" and "," from the string to put it inside <span>
					$key = preg_replace('/\+/', '</span> + <span>', $key);
					$key = preg_replace('/\,/', '</span><span style="display:block; margin-top:10px;">', $key);
						
					echo $key.'</span></li>';
				}
				
				echo '</ul></div>';
			}
			
		echo '</div>
	</div>
</div>
</body>';

die();

}