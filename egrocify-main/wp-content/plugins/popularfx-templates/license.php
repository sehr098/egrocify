<?php

if(!function_exists('popularfx_license')){

global $popularfx;
$popularfx['t'] = wp_get_theme();

// Setup import
function pfx_redownload_template_fix(){
	
	global $popularfx, $pl_error;
	
	$slug = get_theme_mod('popularfx_template');
	
	// We dont have to setup anything
	if(empty($slug)){
		return;
	}
	
	$dest = pfx_templates_dir().'/'.$slug;
	
	// Download
	include_once(dirname(__FILE__).'/templates.php');	
	popularfx_download_template($slug);
	
	if(file_exists($dest.'/style.css')){
		pfx_fix_image_urls_108();
	}else{
		$pl_error['redownload_error'] = __('There was an error downloading the theme', 'popularfx');
		return false;
	}
	
	$GLOBALS['pl_redownload'] = true;
	
}

// The PopularFX Settings Header
function popularfx_page_header($title = 'PopularFX License'){
		
	$promos = apply_filters('popularfx_review_link', false);
		
	echo '<div style="margin: 0px;">	
<div class="metabox-holder">
<div class="postbox-container">	
<div class="wrap" style="margin-top:0px;">
	<h1 style="padding:0px"><!--This is to fix promo--></h1>
	<table cellpadding="2" cellspacing="1" width="100%" class="fixed" border="0">
		<tr>
			<td valign="top"><h1>'.$title.'</h1></td>
			'.($promos ? '<td align="right"><a target="_blank" class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/pagelayer">Review Pagelayer</a></td>' : '').'
			<td align="right" width="40"><a target="_blank" href="https://twitter.com/PopularFXthemes"><img src="'.PFX_URL.'/images/twitter.png" /></a></td>
			<td align="right" width="40"><a target="_blank" href="https://facebook.com/PopularFX"><img src="'.PFX_URL.'/images/facebook.png" /></a></td>
		</tr>
	</table>
	<hr />
	
	<!--Main Table-->
	<table cellpadding="8" cellspacing="1" width="100%" class="fixed">
	<tr>
		<td valign="top">';

}

// The Pagelayer Settings footer
function popularfx_page_footer(){
	
	global $popularfx;
	
	echo '
		</td>';
		
	$promos = apply_filters('pagelayer_right_bar_promos', true);
	
	if($promos){
	
		echo '
		<td width="200" valign="top" id="pagelayer-right-bar">';
			
		echo '
		<div class="postbox" style="min-width:0px !important;">
			<h2 class="hndle ui-sortable-handle">
				<span><a target="_blank" href="'.PAGELAYER_PRO_URL.'"><img src="'.PFX_URL.'/images/pagelayer_product.png" width="100%" /></a></span>
			</h2>
			<div class="inside">
				<i>The best WordPress page builder </i>:<br>
				<ul class="pagelayer-right-ul">
					<li>30+ Free Widgets</li>
					<li>60+ Premium Widgets</li>
					<li>400+ Premium Sections</li>
					<li>Theme Builder</li>
					<li>WooCommerce Builder</li>
					<li>Theme Creator and Exporter</li>
					<li>Form Builder</li>
					<li>Popup Builder</li>
					<li>And many more ...</li>
				</ul>
				<center><a class="button button-primary" target="_blank" href="'.PAGELAYER_PRO_URL.'">Upgrade</a></center>
			</div>
		</div>';
		
		echo '
		<div class="postbox" style="min-width:0px !important;">
			<h2 class="hndle ui-sortable-handle">
				<span><a target="_blank" href="https://loginizer.com/?from=pfx-theme"><img src="'.PFX_URL.'/images/loginizer-product.png" width="100%" /></a></span>
			</h2>
			<div class="inside">
				<i>Secure your website with the following features </i>:<br>
				<ul class="lz-right-ul">
					<li>PasswordLess Login</li>
					<li>Two Factor Auth - Email</li>
					<li>Two Factor Auth - App</li>
					<li>Login Challenge Question</li>
					<li>reCAPTCHA</li>
					<li>Rename Login Page</li>
					<li>Disable XML-RPC</li>
					<li>And many more ...</li>
				</ul>
				<center><a class="button button-primary" href="https://loginizer.com/pricing">Upgrade</a></center>
			</div>
		</div>';
		
		echo '
			<div class="postbox" style="min-width:0px !important;">
				<h2 class="hndle ui-sortable-handle">
					<span><a target="_blank" href="https://wpcentral.co/?from=pfx-theme"><img src="'.PFX_URL.'/images/wpcentral_product.png" width="100%" /></a></span>
				</h2>
				<div class="inside">
					<i>Manage all your WordPress sites from <b>1 dashboard</b> </i>:<br>
					<ul class="pagelayer-right-ul">
						<li>1-click Admin Access</li>
						<li>Update WordPress</li>
						<li>Update Themes</li>
						<li>Update Plugins</li>
						<li>Backup your WordPress Site</li>
						<li>Plugins & Theme Management</li>
						<li>Post Management</li>
						<li>And many more ...</li>
					</ul>
					<center><a class="button button-primary" target="_blank" href="https://wpcentral.co/?from=pfx-template">Visit wpCentral</a></center>
				</div>
			</div>
		
		</td>';
	}
	
	echo '
	</tr>
	</table>
	<br />';
	
	if(empty($GLOBALS['sitepad'])){
	
		echo '<div style="width:45%;background:#FFF;padding:15px; margin:auto">
		<b>Let your followers know that you use PopularFX to build your website :</b>
		<form method="get" action="https://twitter.com/intent/tweet" id="tweet" onsubmit="return dotweet(this);">
			<textarea name="text" cols="45" row="3" style="resize:none;">I easily built my #WordPress #site using @PopularFXthemes</textarea>
			&nbsp; &nbsp; <input type="submit" value="Tweet!" class="button button-primary" onsubmit="return false;" id="twitter-btn" style="margin-top:20px;"/>
		</form>
		
	</div>
	<br />
	
	<script>
	function dotweet(ele){
		window.open(jQuery("#"+ele.id).attr("action")+"?"+jQuery("#"+ele.id).serialize(), "_blank", "scrollbars=no, menubar=no, height=400, width=500, resizable=yes, toolbar=no, status=no");
		return false;
	}
	</script>
	
	<hr />
	<a href="'.$popularfx['www_url'].'" target="_blank">'.$popularfx['t']->get('Name').'</a> v'.$popularfx['t']->get('Version').' You can report any bugs <a href="'.$popularfx['support_url'].'" target="_blank">here</a>.';
	
	}

echo '
</div>	
</div>
</div>
</div>';

}

// The License Page
function popularfx_license(){
	
	global $popularfx, $pl_error;
	
	// 108 fixer option
	if(isset($_REQUEST['redownload'])){
		pfx_redownload_template_fix();
	}
	
	if(isset($_REQUEST['save_pfx_license'])){
		check_admin_referer('popularfx-options');
	}

	// Is there a license key ?
	if(isset($_POST['save_pfx_license'])){
	
		$license = pfx_optpost('popularfx_license');
		
		// Check if its a valid license
		if(empty($license)){
			$pl_error['lic_invalid'] = __('The license key was not submitted', 'popularfx');
			return popularfx_license_T();
		}
		
		$resp = wp_remote_get(pfx_api_url().'license.php?license='.$license, array('timeout' => 30));
		
		if(is_array($resp)){
			$json = json_decode($resp['body'], true);
			//print_r($json);
		}else{
		
			$pl_error['resp_invalid'] = __('The response was malformed<br>', 'popularfx').var_export($resp, true);
			return popularfx_license_T();
			
		}
		
		// Save the License
		if(empty($json['license'])){
		
			$pl_error['lic_invalid'] = __('The license key is invalid', 'popularfx');
			return popularfx_license_T();
			
		}else{
			
			$json['last_update'] = time();
			update_option('popularfx_license', $json);
	
			// Load license
			pfx_load_license();
			
			// Mark as saved
			$GLOBALS['pl_saved'] = true;
		}
		
	}
	
	popularfx_license_T();
	
}

// The License Page - THEME
function popularfx_license_T(){
	
	global $popularfx, $pagelayer, $pl_error;

	popularfx_page_header('PopularFX License');

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div class="notice notice-success"><p>'. __('The settings were saved successfully', 'popularfx'). '</p></div><br />';
	}

	// Saved ?
	if(!empty($GLOBALS['pl_redownload'])){
		echo '<div class="notice notice-success"><p>'. __('The template was redownloaded successfully', 'popularfx'). '</p></div><br />';
	}

	// If the license is active and you are the free version, then suggest to install the pro
	if(!empty($popularfx['license']['status']) && !defined('PAGELAYER_PREMIUM') && empty($_REQUEST['install_pro'])){
		echo '<div class="updated"><p>'. __('You have activated the license, but are using the Free version ! <a href="'.admin_url('admin.php?page=pagelayer_license&install_pro=1').'" class="button button-primary">Install Pagelayer Pro Now</a>', 'pagelayer'). '</p></div><br />';
	}
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}
	
	$slug = get_theme_mod('popularfx_template');
	
	?>
	
	<div class="postbox">		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('System Information', 'popularfx'); ?></span>
		</h2>
		
		<div class="inside">
		
		<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('popularfx-options'); ?>
		<table class="wp-list-table fixed striped users" cellspacing="1" border="0" width="95%" cellpadding="10" align="center">
		<?php
			echo '
			<tr>				
				<th align="left" width="25%">'.__('Theme Name', 'popularfx').'</th>
				<td>'.$popularfx['t']->get('Name').'</td>
			</tr>
			<tr>				
				<th align="left" width="25%">'.__('Theme Version', 'popularfx').'</th>
				<td>'.$popularfx['t']->get('Version').'</td>
			</tr>
			<tr>				
				<th align="left" width="25%">'.__('PopularFX Plugin Version', 'popularfx').'</th>
				<td>'.PFX_VERSION.'</td>
			</tr>';
			
			if(is_super_admin()){
			
				echo '
			<tr>			
				<th align="left" valign="top">'.__('PopularFX License', 'popularfx').'</th>
				<td align="left">
					'.(empty($popularfx['license']) ? '<span style="color:red">Free Version</span> &nbsp; &nbsp;' : '').' 
					<input type="text" name="popularfx_license" value="'.(empty($popularfx['license']) ? '' : $popularfx['license']['license']).'" size="30" placeholder="e.g. PFX-11111-22222-33333-44444" style="width:300px;" /> &nbsp; 
					<input name="save_pfx_license" class="button button-primary" value="Update License" type="submit" />';
					
					if(!empty($popularfx['license'])){
						
						$expires = $popularfx['license']['expires'];
						$expires = substr($expires, 0, 4).'/'.substr($expires, 4, 2).'/'.substr($expires, 6);
						
						echo '<div style="margin-top:10px;">License Status : '.(empty($popularfx['license']['status_txt']) ? 'N.A.' : $popularfx['license']['status_txt']).' &nbsp; &nbsp; &nbsp; 
						License Expires : '.($popularfx['license']['expires'] <= date('Ymd') ? '<span style="color:red">'.$expires.'</span>' : $expires).'
						</div>';
					}else{
						echo '<div style="margin-top:10px;">You are currently using the free version. If you have a Pro License, please enter it here to unlock the Pro features. You can buy a Pro license from our <a href="'.PFX_PRO_URL.'">website</a>.</div>';
					}
					
				echo 
				'</td>
			</tr>';
			
			}
			
			echo '<tr>				
				<th align="left" width="25%">'.__('Pagelayer Version', 'popularfx').'</th>
				<td>'.PAGELAYER_VERSION.(defined('PAGELAYER_PREMIUM') ? ' (PRO Version)' : '').'</td>
			</tr>
			
			<tr>				
				<th align="left" width="25%">'.__('Current Template', 'popularfx').'</th>
				<td>'.(empty($slug) ? 'N.A.' : ucfirst($slug).' &nbsp; <a class="button primary-button" href="'.admin_url('admin.php?page=popularfx&redownload=1').'">Re-Download Template</a>').'</td>
			</tr>
			
			<tr>
				<th align="left">'.__('URL', 'popularfx').'</th>
				<td>'.home_url().'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Path', 'popularfx').'</th>
				<td>'.ABSPATH.'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Server\'s IP Address', 'popularfx').'</th>
				<td>'.$_SERVER['SERVER_ADDR'].'</td>
			</tr>
			<tr>				
				<th align="left">'.__('wp-config.php is writable', 'popularfx').'</th>
				<td>'.(is_writable(ABSPATH.'/wp-config.php') ? '<span style="color:red">Yes</span>' : '<span style="color:green">No</span>').'</td>
			</tr>';
			
			if(file_exists(ABSPATH.'/.htaccess')){
				echo '
			<tr>				
				<th align="left">'.__('.htaccess is writable', 'popularfx').'</th>
				<td>'.(is_writable(ABSPATH.'/.htaccess') ? '<span style="color:red">Yes</span>' : '<span style="color:green">No</span>').'</td>
			</tr>';
			
			}
			
		?>
		</table>
		</form>
		
		</div>
	</div>

<?php
	
	popularfx_page_footer();

}

}