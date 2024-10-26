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

//function is called first to select the route 
function pagelayer_replace_page(){
	
	global $pl_error;

	if(!current_user_can('upload_files')){
		wp_die(esc_html__('You do not have permission to upload files.', 'pagelayer'));
	}
	
	$post_id = (int) $_GET['id'];
	
	if(empty($post_id)){
		wp_die(esc_html__('ID not found .', 'pagelayer'));
	}
	
	// Load the attachment
	$post = get_post($post_id);
	
	if(empty($post) || is_wp_error($post)){
		wp_die(esc_html__('ID not found .', 'pagelayer'));
	}
	
	// Process the POST !
	if(isset($_FILES['userfile'])){
	
		if(!check_admin_referer()){
			wp_die('Invalid Nonce');
		}
		
		/** Check if file is uploaded properly **/
		if(!is_uploaded_file($_FILES['userfile']['tmp_name'])){
			$pl_error['upload_error'] = __('No file was uploaded ! Please try again.');
			pagelayer_media_replace_theme();
			return;
		}
		
		if(isset($_FILES['userfile']['error']) && $_FILES['userfile']['error'] > 0){
			$pl_error['upload_error'] = __('There was some error uploading the file ! Please try again.');
			pagelayer_media_replace_theme();
			return;
		}
		
		$filedata = wp_check_filetype_and_ext($_FILES['userfile']['tmp_name'], $_FILES['userfile']['name']);
		
		if ($filedata['ext'] == false){
			$pl_error['ext_error'] = __('The File type could not be determined. Please upload a permitted file type.');
			pagelayer_media_replace_theme();
			return;
		}
		
		$result = pagelayer_replace_attachment($_FILES['userfile']['tmp_name'], $post_id, $err);
		
		if(empty($result)){
			$pl_error['replace_error'] = $err;
			pagelayer_media_replace_theme();
			return;
		}
		
		$redirect_success = admin_url('post.php');
		$redirect_success = add_query_arg(array(
			'action' => 'edit', 
			'post' => $post_id,
		), $redirect_success);
		
		echo '<meta http-equiv="refresh" content="0;url='.$redirect_success.'" />';
	
	}
	
	// Show the theme
	pagelayer_media_replace_theme();
	
}

// Theme of the page
function pagelayer_media_replace_theme(){
	
	global $pl_error;
	
	pagelayer_report_error($pl_error);echo '<br />';
	
	$id = (int) $_GET['id'];
	
?>
<div class="wrap">
<h1><?php echo esc_html__("Replace Media File", 'pagelayer'); ?></h1>
<form enctype="multipart/form-data" method="POST">
	<div class="editor-wrapper">
		<section class="image_chooser wrapper">
			<input type="hidden" name="ID" id="ID" value="<?php echo $id ?>" />
			<p><?php echo esc_html__("Choose a file to upload from your computer", 'pagelayer'); ?></p>
			<div class="drop-wrapper">
				<p><input type="file" name="userfile" id="userfile" /></p>
				<?php wp_nonce_field(); ?>
			</div>
		</section>
		<section class="form_controls wrapper">
			<input id="submit" type="submit" class="button button-primary" name="submit" value="<?php echo esc_attr__("Upload", 'pagelayer');?>" />
		</section>
	</div>
</form>
<?php

}

// Replace the uploaded media with the new one
function pagelayer_replace_attachment($file, $post_id, &$error = ''){

	if(function_exists('wp_get_original_image_path')){
		$targetFile = wp_get_original_image_path($post_id);
	}else{
		$targetFile = trim(get_attached_file($post_id, apply_filters( 'pagelayer_unfiltered_get_attached_file', true )));
	}
	
	$fileparts = pathinfo($targetFile);
	$filePath = isset($fileparts['dirname']) ? trailingslashit($fileparts['dirname']) : '';
	$fileName = isset($fileparts['basename']) ? $fileparts['basename'] : '';
	$filedata = wp_check_filetype_and_ext($targetFile, $fileName);
	$fileMime = (isset($filedata['type'])) ? $filedata['type'] : false;
	
	if(empty($targetFile)){
		return false;
	}
	
	if(empty($filePath)){
		$error = 'No folder for the target found !';
		return false;
	}
	
	// Remove the files of the original attachment
	pagelayer_remove_attahment_files($post_id);
	
	$result_moved = move_uploaded_file($file, $targetFile);
	
	if (false === $result_moved){
		$error = sprintf( esc_html__('The uploaded file could not be moved to %1$s. This is most likely an issue with permissions, or upload failed.', 'pagelayer'), $targetFile );
		return false;
	}
	
	$permissions = fileperms($targetFile) & 0777;
	if ($permissions > 0){
		chmod( $targetFile, $permissions ); // restore permissions
	}
	
	$updated = update_attached_file($post_id, $targetFile);
	
	$target_url = wp_get_attachment_url($post_id);
	
	// Run the filter, so other plugins can hook if needed.
	$filtered = apply_filters( 'wp_handle_upload', array(
		'file' => $targetFile,
		'url'  => $target_url,
		'type' => $fileMime,
	), 'sideload');
	
	// Check if file changed during filter. Set changed to attached file meta properly.
	if (isset($filtered['file']) && $filtered['file'] != $targetFile ){
		update_attached_file($post_id, $filtered['file']);
	}

	$metadata = wp_generate_attachment_metadata($post_id, $targetFile);
	wp_update_attachment_metadata($post_id, $metadata);

	return true;
	
}

function pagelayer_remove_attahment_files($post_id){
	
	$meta = wp_get_attachment_metadata( $post_id );

	if (function_exists('wp_get_original_image_path')){ // WP 5.3+
		$fullfilepath = wp_get_original_image_path($post_id);
	}else{
		$fullFilePath = trim(get_attached_file($post_id, apply_filters( 'pagelayer_unfiltered_get_attached_file', true )));
	}

	$backup_sizes = get_post_meta( $post_id, '_wp_attachment_backup_sizes', true );
	$file = $fullFilePath;
	$result = wp_delete_attachment_files($post_id, $meta, $backup_sizes, $file );

	// If attached file is not the same path as file, this indicates a -scaled images is in play.
	$attached_file = get_attached_file($post_id);
	
	if ($file !== $attached_file && file_exists($attached_file)){
		@unlink($attached_file);
	}
}
