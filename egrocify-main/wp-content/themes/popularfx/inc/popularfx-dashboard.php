<?php

if(!function_exists('popularfx_dashboard')){

global $popularfx;
$popularfx['t'] = wp_get_theme();

// The PopularFX Settings Header
function popularfx_page_header($title = 'PopularFX Theme'){
		
	$promos = apply_filters('popularfx_review_link', false);
		
	echo '<div style="margin: 0px;">	
<div class="metabox-holder">
<div class="postbox-container">	
<div class="wrap" style="margin-top:0px;">
	<h1 style="padding:0px"><!--This is to fix promo--></h1>
	<table cellpadding="2" cellspacing="1" width="100%" class="fixed" border="0">
		<tr>
			<td valign="top"><h1>'.$title.'</h1></td>
			'.($promos ? '<td align="right"><a target="_blank" class="button button-primary" href="'.esc_url('https://wordpress.org/support/view/plugin-reviews/pagelayer').'">'.__('Review Pagelayer', 'popularfx').'</a></td>' : '').'
			<td align="right" width="40"><a target="_blank" href="'.esc_url('https://twitter.com/PopularFXthemes').'"><img src="'.esc_url(POPULARFX_URL.'/images/twitter.png').'" /></a></td>
			<td align="right" width="40"><a target="_blank" href="'.esc_url('https://facebook.com/PopularFX').'"><img src="'.esc_url(POPULARFX_URL.'/images/facebook.png').'" /></a></td>
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
		
	$promos = true;
	
	if($promos){
	
		echo '
		<td width="200" valign="top" id="pagelayer-right-bar">';
			
		echo '
		<div class="postbox" style="min-width:0px !important;">
			<h2 class="hndle ui-sortable-handle">
				<span><a target="_blank" href="'.esc_url(POPULARFX_PAGELAYER_PRO_URL).'"><img src="'.esc_url(POPULARFX_URL.'/images/pagelayer_product.png').'" width="100%" /></a></span>
			</h2>
			<div class="inside">
				<i>'.__('The best WordPress page builder', 'popularfx').' </i>:<br>
				<ul class="pagelayer-right-ul">
					<li>'.__('30+ Free Widgets', 'popularfx').'</li>
					<li>'.__('60+ Premium Widgets', 'popularfx').'</li>
					<li>'.__('400+ Premium Sections', 'popularfx').'</li>
					<li>'.__('Theme Builder', 'popularfx').'</li>
					<li>'.__('WooCommerce Builder', 'popularfx').'</li>
					<li>'.__('Theme Creator and Exporter', 'popularfx').'</li>
					<li>'.__('Form Builder', 'popularfx').'</li>
					<li>'.__('Popup Builder', 'popularfx').'</li>
					<li>'.__('And many more ...', 'popularfx').'</li>
				</ul>
				<center><a class="button button-primary" target="_blank" href="'.esc_url(POPULARFX_PAGELAYER_PRO_URL).'">'.__('Upgrade', 'popularfx').'</a></center>
			</div>
		</div>';
		
		echo '
		<div class="postbox" style="min-width:0px !important;">
			<h2 class="hndle ui-sortable-handle">
				<span><a target="_blank" href="https://loginizer.com/?from=pfx-theme"><img src="'.esc_url(POPULARFX_URL.'/images/loginizer-product.png').'" width="100%" /></a></span>
			</h2>
			<div class="inside">
				<i>'.__('Secure your website with the following features', 'popularfx').' </i>:<br>
				<ul class="lz-right-ul">
					<li>'.__('PasswordLess Login', 'popularfx').'</li>
					<li>'.__('Two Factor Auth - Email', 'popularfx').'</li>
					<li>'.__('Two Factor Auth - App', 'popularfx').'</li>
					<li>'.__('Login Challenge Question', 'popularfx').'</li>
					<li>'.__('reCAPTCHA', 'popularfx').'</li>
					<li>'.__('Rename Login Page', 'popularfx').'</li>
					<li>'.__('Disable XML-RPC', 'popularfx').'</li>
					<li>'.__('And many more ...', 'popularfx').'</li>
				</ul>
				<center><a class="button button-primary" href="https://loginizer.com/pricing">'.__('Upgrade', 'popularfx').'</a></center>
			</div>
		</div>';
		
		echo '
			<div class="postbox" style="min-width:0px !important;">
				<h2 class="hndle ui-sortable-handle">
					<span><a target="_blank" href="https://wpcentral.co/?from=pfx-theme"><img src="'.esc_url(POPULARFX_URL.'/images/wpcentral_product.png').'" width="100%" /></a></span>
				</h2>
				<div class="inside">
					<i>'.__('Manage all your WordPress sites from <b>1 dashboard</b>', 'popularfx').' </i>:<br>
					<ul class="pagelayer-right-ul">
						<li>'.__('1-click Admin Access', 'popularfx').'</li>
						<li>'.__('Update WordPress', 'popularfx').'</li>
						<li>'.__('Update Themes', 'popularfx').'</li>
						<li>'.__('Update Plugins', 'popularfx').'</li>
						<li>'.__('Backup your WordPress Site', 'popularfx').'</li>
						<li>'.__('Plugins & Theme Management', 'popularfx').'</li>
						<li>'.__('Post Management', 'popularfx').'</li>
						<li>'.__('And many more ...', 'popularfx').'</li>
					</ul>
					<center><a class="button button-primary" target="_blank" href="'.esc_url('https://wpcentral.co/?from=pfx-theme-'.popularfx_get_current_theme_slug()).'">'.__('Visit wpCentral', 'popularfx').'</a></center>
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
		<b>'.__('Let your followers know that you use PopularFX to build your website', 'popularfx').' :</b>
		<form method="get" action="https://twitter.com/intent/tweet" id="tweet" onsubmit="return dotweet(this);">
			<textarea name="text" cols="45" row="3" style="resize:none;">'.__('I easily built my #WordPress #site using @PopularFXthemes', 'popularfx').'</textarea>
			&nbsp; &nbsp; <input type="submit" value="Tweet!" class="button button-primary" onsubmit="return false;" id="twitter-btn" style="margin-top:20px;"/>
		</form>
		
	</div>
	<br />';

	wp_register_script( 'pfx_common', get_stylesheet_directory_uri() .'/js/common.js', array(), POPULARFX_VERSION, true );
	
	wp_enqueue_script('pfx_common');
	
	echo '<hr />
	<a href="'.esc_url($popularfx['www_url']).'" target="_blank">'.$popularfx['t']->get('Name').'</a> v'.$popularfx['t']->get('Version').' '.__('You can report any bugs', 'popularfx').' <a href="'.esc_url($popularfx['support_url']).'" target="_blank">'.__('here', 'popularfx').'</a>.';
	
	}

echo '
</div>	
</div>
</div>
</div>';

}

// The License Page
function popularfx_dashboard(){
	
	global $popularfx, $pl_error;
	
	if(isset($_REQUEST['save_pfx_license'])){
		check_admin_referer('popularfx-options');
	}
	
	popularfx_dashboard_T();
	
}

// The License Page - THEME
function popularfx_dashboard_T(){
	
	global $popularfx, $pagelayer, $pl_error;

	popularfx_page_header('PopularFX Dashboard');

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div class="notice notice-success"><p>'. __('The settings were saved successfully', 'popularfx'). '</p></div><br />';
	}
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}
	
	?>
	
	<div class="postbox">
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('Theme Information', 'popularfx'); ?></span>
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
				<th align="left" valign="top">'.__('PopularFX Templates Plugin', 'popularfx').'</th>
				<td align="left">
					<a href="'.esc_url(POPULARFX_WWW_URL).'/plugin">'.(defined('PFX_VERSION') ? PFX_VERSION : __('Install PopularFX Website Templates Plugin', 'popularfx')).'</a>
				</td>
			</tr>
			
			<tr>				
				<th align="left" width="25%">'.__('Pagelayer Version', 'popularfx').'</th>
				<td>'.(defined('PAGELAYER_VERSION') ? PAGELAYER_VERSION : 'Not Installed').(defined('PAGELAYER_PREMIUM') ? ' (PRO Version)' : '').'</td>
			</tr>
			
			<tr>
				<th align="left">'.__('URL', 'popularfx').'</th>
				<td>'.esc_url(home_url()).'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Path', 'popularfx').'</th>
				<td>'.ABSPATH.'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Server\'s IP Address', 'popularfx').'</th>
				<td>'.$_SERVER['SERVER_ADDR'].'</td>
			</tr>';
			
		?>
		</table>
		</form>
		
		</div>
	</div>

<?php

	global $popularfx_promo_opts;
	
	$popularfx_promo_opts['popularfx_templates_promo'] = [
		'after' => 0,// In days
		'interval' => 30,// In days
		'pro_url' => POPULARFX_PRO_URL,
		'rating' => 'https://wordpress.org/themes/popularfx/#reviews',
		'twitter' => 'https://twitter.com/PopularFXthemes?status='.rawurlencode('I love #PopularFX Theme by @pagelayer team for my #WordPress site - '.esc_url(home_url())),
		'facebook' => 'https://facebook.com/popularfx',
		'website' => POPULARFX_WWW_URL,
		'image' => POPULARFX_URL.'/images/popularfx-logo.png',
		'name' => 'popularfx_templates_promo',
		'class' => 'popularfx-templates-promo'
	];

	popularfx_templates_promo();
	
	popularfx_page_footer();

}

}