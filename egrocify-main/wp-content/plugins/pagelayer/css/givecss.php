<?php

//////////////////////////////////////////////////////////////
//===========================================================
// givecss.php
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


// Read the file
$data = '';
$data_premium = '';
$self_path = dirname(__FILE__);
$files = array(
		// Admin CSS
		'pagelayer-editor.css',
		'pagelayer-icons.css',
		'pagelayer-editor-frontend.css',
		// Enduser CSS
		'font-awesome5.min.css',
		'font-awesome5-v4shims.css',
		'nivo-lightbox.css',
		'owl.carousel.min.css',
		'owl.theme.default.min.css',
		'pagelayer-frontend.css',
		'premium-frontend.css',
		'animate.min.css',
		'chartist.min.css',
		'pagelayer-pen.css'
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

// Type CSS
header("Content-type: text/css; charset: UTF-8");

// Set a zero Mtime
$filetime = filemtime($self_path.'/pagelayer-editor.css');

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

if(defined('DOING_AJAX') && !defined('SITEPAD')){
	$data = str_replace('../fonts/', '../wp-content/plugins/'.(basename(dirname(dirname(__FILE__)))).'/fonts/', $data);
}

echo $data;
echo $data_premium;

// Write if we are front-end only then
$dev = dirname(dirname(__FILE__)).'/dev.php';
if(!empty($_REQUEST['write']) && file_exists($dev)){
	include_once($dev);
	write_css();
}

