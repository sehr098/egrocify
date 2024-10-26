<?php

//////////////////////////////////////////////////////////////
//===========================================================
// givejs.php
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

if(!empty($_REQUEST['test'])){
	echo 1;
	die();
}

// Read the file
$data = '';
$data_premium = '';
$self_path = dirname(__FILE__);
$files = array(
		// Admin JS
		'pagelayer-editor.js',
		'widgets.js',
		'premium.js',
		'properties.js',
		'base-64.min.js',
		'slimscroll.js',
		'vanilla-picker.min.js',
		'tlite.min.js',
		'pagelayer-pen.js',
		// Enduser JS
		'imagesloaded.min.js',
		'nivo-lightbox.min.js',
		'owl.carousel.min.js',
		'pagelayer-frontend.js',
		'premium-frontend.js',
		'wow.min.js',
		'jquery-numerator.js',
		'simpleParallax.min.js',
		'chart.min.js',
		'shuffle.min.js'
	);

// What files to give		
$give = @$_REQUEST['give'];

// Premium
$premium = @$_REQUEST['premium'];

if(!empty($give)){
	
	$give = explode(',', $give);
	
	// Check all files are in the supported list
	foreach($give as $file){
		if(in_array($file, $files)){
			$final[md5($file)] = $file;
		}
	}
	
}

if(!empty($premium)){
	
	$premium = explode(',', trim($premium, ','));
	
	// Check all files are in the supported list
	foreach($premium as $file){
		if(in_array($file, $files)){
			$final_premium[md5($file)] = $file;
		}
	}
	
}

// Give all
if(empty($final)){
	$final = $files;
}

foreach($final as $k => $v){
	//echo $k.'<br>';
	$data .= file_get_contents($self_path.'/'.$v)."\n\n";
}

if(!empty($final_premium)){

	foreach($final_premium as $k => $v){
		//echo $k.'<br>';
		$data_premium .= file_get_contents($self_path.'/'.$v)."\n\n";
	}

}

// We are zipping if possible
if(function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')){
	ob_start('ob_gzhandler');
}
		
// Type javascript
header("Content-type: text/javascript; charset: UTF-8");

// Set a zero Mtime
$filetime = filemtime($self_path.'/pagelayer-editor.js');

// Are we to also serve Shortcodes ?
if(!empty($pagelayer->shortcodes)){
	$data .= 'pagelayer_shortcodes = '.json_encode($pagelayer->shortcodes).';'."\n\n";
	$data .= 'pagelayer_styles = '.json_encode($pagelayer->styles).';'."\n\n";
	$data .= 'pagelayer_groups = '.json_encode($pagelayer->groups).';'."\n\n";
}

// Add the langs as well
preg_match_all('/pagelayer_l\([\'"](\w*)[\'"]\)/is', $data, $matches);
if(!empty($matches[1]) && function_exists('__pl')){
	foreach($matches[1] as $lk => $lv){
		$export_langs[$lv] = __pl($lv);
	}
}

// Also add the fonts
if(!empty($pagelayer->fonts)){
	$export_langs['pl_fonts_list'] = $pagelayer->fonts;
}

// And lang string ?
if(!empty($export_langs)){
	$data .= 'pagelayer_lang = '.json_encode($export_langs).';'."\n\n";
}

// Cache Control
header("Cache-Control: must-revalidate");

// Checking if the client is validating his cache and if it is current.
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $filetime)) {
	
	// Client's cache IS current, so we just respond '304 Not Modified'.
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 304);
	
	return;
	
}else{
	
	// Image not cached or cache outdated, we respond '200 OK' and output the image.
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 200);
	
}

echo $data;
echo $data_premium;

// Write if we are front-end only then
$dev = dirname(dirname(__FILE__)).'/dev.php';
if(!empty($_REQUEST['write']) && file_exists($dev)){
	include_once($dev);
	write_js();
}


