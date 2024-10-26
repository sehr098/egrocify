<?php

//////////////////////////////////////////////////////////////
//===========================================================
// getting_started.php
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

$app = (!defined('SITEPAD') ? 'Pagelayer' : BRAND_SM);
?>

<link rel="stylesheet" href="<?php echo PAGELAYER_CSS.'/font-awesome5.min.css';?>">

<div class="pagelayer-getting-started">
	<div class="pagelayer-getting-started-container">
		<div class="pagelayer-getting-started-block">
			<div class="pagelayer-getting-started-logo">
				<?php echo (!defined('SITEPAD')) ? '<img src="'.PAGELAYER_URL.'/images/pagelayer-logo-256.png'.'"/>' : '<img src="'.BRAND_SM_LOGO.'" style="width:auto"/>' ?>
			</div>
			<div class="pagelayer-getting-started-desc">
				<h1><?php echo __pl('welcome_to').$app;?></h1>
				<h6><?php echo (!defined('SITEPAD')) ? __pl('choose_pagelayer') : __pl('choose_sitepad');?></h6>
			</div>
			<div class="pagelayer-getting-started-video">
				<?php echo (!defined('SITEPAD')) ? '<iframe width="700" height="400" src="https://www.youtube.com/embed/t8Iz-v-qce8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' : '<iframe height="400" width="700" src="https://www.youtube.com/embed/8e3ROkKoFwA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';?>
			</div>
			<div class="pagelayer-getting-started-desc">
				<h6><?php echo (!defined('SITEPAD')) ? __pl('pagelayer_desc') : __pl('sitepad_desc');?></h6>
				<div class="pagelayer-getting-started-btn">
					<a href="<?php echo admin_url('/post-new.php?post_type=page')?>" class="button button-primary btn-sc"><?php echo __pl('first_page');?></a>
					<a href="<?php echo (!defined('SITEPAD')) ? PAGELAYER_WWW_URL.'getting-started' : "https://sitepad.com/docs/getting-started/"; ?>" class="button button-secondary btn-sc" target="_blank"><?php echo __pl('watch_guide');?></a>
				</div>
			</div>
		</div>
		<div class="pagelayer-features">
			<div class="pagelayer-getting-started-desc">
				<h1><?php echo $app.' '.__pl('feature_style');?></h1>
				<h6><?php echo $app.__pl('brand_feature_text');?></h6>
				<div class="pagelayer-features-list">
				<?php $style = (defined('SITEPAD')) ? 'style="width:30%; height: 265px"' : ''; ?>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fas fa-mouse-pointer" aria-hidden="true">' : '<i class="fas fa-paper-plane" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('dragdrop') : __pl('oneclick')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('dragdrop_desc') : __pl('oneclick_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-th-list" aria-hidden="true">' : '<i class="fas fa-random" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('widgets') : __pl('static_pages')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('widgets_desc') : __pl('static_pages_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-pencil" aria-hidden="true">' : '<i class="fas fa-mobile-alt" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('inline_edit') : __pl('responsive_styles')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('inline_edit_desc') : __pl('responsive_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-clone" aria-hidden="true">' : '<i class="fas fa-share-square" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('duplicate') : __pl('social_media')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('duplicate_desc') : __pl('social_media_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-snowflake-o fa-spin" aria-hidden="true">' : '<i class="fas fa-check" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('animation') : __pl('easy_use')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('animation_desc') : __pl('easy_use_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-text-width" aria-hidden="true">' : '<i class="fas fa-cog" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('style_option') : __pl('cpanel_integrate')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('style_option_desc') : __pl('cpanel_integrate_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-paint-brush" aria-hidden="true">' : '<i class="fas fa-th-large" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('real_design') : __pl('multisites')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('real_design_desc') : __pl('multisites_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-font" aria-hidden="true">' : '<i class="fas fa-copy" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('typography') : __pl('replicate_obj')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('typography_desc') : __pl('replicate_obj_desc');?></p>
						</div>
					</div>
					<div class="feature-block-card" <?php echo $style; ?>>
						<div class="feature-block">
							<?php echo (!defined('SITEPAD')) ? '<i class="fa fa-cubes" aria-hidden="true">' : '<i class="fas fa-shopping-cart" aria-hidden="true">' ?></i>
						</div>
						<div class="feature-block-content">
							<h5><?php echo (!defined('SITEPAD')) ? __pl('easy_customize') : __pl('whmcs')?></h5>
							<p><?php echo (!defined('SITEPAD')) ? __pl('easy_customize_desc') : __pl('whmcs_desc');?></p>
						</div>
					</div>
				<div class="pagelayer-getting-started-btn">
					<a href=" <?php echo (!defined('SITEPAD')) ? PAGELAYER_WWW_URL : "http://sitepad.com/"?>" class="button button-secondary btn-sc" target="_blank" style="margin-top:20px;"><?php echo __pl('why').' '.$app.'?';?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<style>

.pagelayer-getting-started{
	padding-top: 50px;
}

.pagelayer-getting-started-container{
	margin: 0 auto;
	max-width: 1000px;
	padding: 0;
	text-align: center;
}

.pagelayer-getting-started-block{
	background-color: #fff;
	border: 2px solid #e1e1e1;
	border-radius: 2px;
	margin-bottom: 30px;
	position: relative;
	padding-top: 40px;
}

.pagelayer-getting-started-logo img{
	width: 10%;
	height: auto;
}

.pagelayer-getting-started-desc{
	padding: 40px;
}

.pagelayer-getting-started-desc h1{
	color: #222;
	font-size: 24px;
	margin: 0 0 24px 0;
}

.pagelayer-getting-started-desc h6{
	font-size: 16px;
	font-weight: 400;
	line-height: 1.6;
	margin: 0 85px 0 85px;
}

.pagelayer-getting-started-btn{
	max-width: 600px;
	margin: 0 auto 0 auto;
	margin-top: 36px !important;
}

.btn-sc{
	font-size: 14px !important;
	min-height: 46px !important;
	line-height: 3.14285714 ! important;
	padding: 0px 36px !important;
}

.button-primary{
	margin-right: 20px !important;
	border-radius: 3px !important;
}

.pagelayer-features{
	background-color: #fff;
	border: 2px solid #e1e1e1;
	border-radius: 2px 2px 0 0;
	position: relative;
}

.feature-block-card{
	width: 25%;
	display: inline-block;
	margin: 60px 10px 0 10px;
	vertical-align: top;
	box-shadow: 0px 0px 20px 0px rgba(0,0,0,.1);
	padding: 20px;
	height: 220px;
}

.feature-block{
	background: linear-gradient(to right, rgb(116, 116, 191), rgb(52, 138, 199));
	border-radius: 50%;
	width: 54px;
	height: 54px;
	position: relative;
	display: inline-block;
}

.feature-block i{
	font-size: 30px;
	color: #fff;
	position: absolute;
	top: 13px;
	left: 0;
	right: 0;
}

.feature-block-content h5{
	color: #222;
	font-size: 20px;
	margin: 10px 0 0 0;
}

.feature-block-content p{
	color: #222;
	font-size: 16px;
	margin-top: 10px;
}

.fa-spin{
	-webkit-animation: fa-spin 2s infinite linear;
	animation: fa-spin 2s infinite linear;
}
</style>