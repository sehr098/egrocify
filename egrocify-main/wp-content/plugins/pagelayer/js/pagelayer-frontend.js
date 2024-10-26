/*
PAGELAYER
http://pagelayer.com/
(c) Pagelayer Team
*/

var pagelayer_doc_width;

// Things to do on document load
jQuery(document).ready(function(){
	
	// Current width
	pagelayer_doc_width = jQuery(document).width();
	
	// Rows
	jQuery('.pagelayer-row-stretch-full').each(function(){
		pagelayer_pl_row_full(jQuery(this));
	});
	
	jQuery('.pagelayer-anim_heading').each(function(){
		pagelayer_anim_heading(jQuery(this));
	});
	
	// Setup any sliders
	jQuery('.pagelayer-image_slider').each(function(){
		pagelayer_pl_image_slider(jQuery(this));
	});
	
	jQuery('.pagelayer-accordion').each(function(){
		pagelayer_pl_accordion(jQuery(this));
	});
	
	jQuery('.pagelayer-collapse').each(function(){
		pagelayer_pl_collapse(jQuery(this));
	});
	
	jQuery('.pagelayer-tabs').each(function(){
		pagelayer_pl_tabs(jQuery(this));
	});
	
	jQuery('.pagelayer-video').each(function(){
		pagelayer_pl_video(jQuery(this));
	});
	
	jQuery('.pagelayer-image').each(function(){
		pagelayer_pl_image(jQuery(this));
	});
	
	jQuery('.pagelayer-grid_gallery').each(function(){
		pagelayer_pl_grid_lightbox(jQuery(this));
	});
	
	jQuery('.pagelayer-row, .pagelayer-inner_row, .pagelayer-col').each(function(){
		pagelayer_pl_row_video(jQuery(this));
	});
	
	jQuery('.pagelayer-parallax-window img').each(function(){
		pagelayer_pl_row_parallax(jQuery(this));
	});
	
	jQuery('.pagelayer-recaptcha').each(function(){
		pagelayer_recaptcha_loader(jQuery(this));
	});
	
	jQuery('.pagelayer-wp_menu').each(function(){
		pagelayer_primary_menu(jQuery(this));
	});
	
	jQuery('.pagelayer-contact').each(function(){
		pagelayer_contact_form(jQuery(this));
	});
	
	jQuery('.pagelayer-countdown').each(function(){
		pagelayer_countdown(jQuery(this));
	});
	
	jQuery('.pagelayer-testimonial_slider').each(function(){
		pagelayer_pl_testimonial_slider(jQuery(this));
	});
	
	jQuery('.pagelayer-social_grp').each(function(){
		pagelayer_pl_social_profile(jQuery(this));
	});
	
	jQuery('.pagelayer-scroll-to-element').each(function () {
		pagelayer_button_element_scroll(jQuery(this));
	});

	jQuery('.pagelayer-bgimg-slider').each(function () {
		pagelayer_pl_row_slider(jQuery(this));
	});

	jQuery('.pagelayer-stars-container').each(function(){
		pagelayer_stars(jQuery(this));
	});
	
	jQuery('.pagelayer-infinite-posts').each(function(){
		pagelayer_infinite_posts(jQuery(this));
	});
	
	jQuery('.pagelayer-youtube-video').each(function(){
		pagelayer_create_yt_player(jQuery(this));
	});

	// We need to call the is visible thing to show the widgets loading effect
	if(jQuery('.pagelayer-counter-content,.pagelayer-progress-container').length > 0){

		// First Call
		pagelayer_counter();
		pagelayer_progress();
		
		jQuery(window).scroll(function() {
			pagelayer_progress();
			pagelayer_counter();
		});
	}
	
	new WOW({boxClass:'pagelayer-wow'}).init();
	
	// Remove pagelayer-wow temporary style after wow init added from wp_head
	jQuery('#pagelayer-wow-animation-style').remove();
	
});

// For automatic row change
jQuery(window).resize(function() {
	
	// Primary Menu
	jQuery('.pagelayer-wp_menu').each(function(){
		pagelayer_primary_menu(jQuery(this));
	});
	
	var new_vw = jQuery(document).width();
	
	if(new_vw == pagelayer_doc_width){
		return false;
	}
	
	pagelayer_doc_width = new_vw;
	
	// Remove style
	jQuery('.pagelayer-row-stretch-full').removeAttr('style');
	
	// Set a timeout to prevent bubbling
	setTimeout(function(){
		
		jQuery('.pagelayer-row-stretch-full').each(function(){
			pagelayer_pl_row_full(jQuery(this));
		});
	
	}, 200);
	
});

// Get document for gutenberg
function pagelayerGetDocumentElement(){
	
	// Find the iframe element with the name "editor-canvas".
	const editorIframe = document.querySelectorAll( 'iframe[name="editor-canvas"]' );

	if(! editorIframe?.[ 0 ]){
		return document;
	}
	
	// Get the document of the iframe.
	const iframeDocument = editorIframe[ 0 ]?.contentWindow?.document || editorIframe[ 0 ]?.contentDocument;

	if ( ! iframeDocument ) {
		return document;
	}
	
	// Set the root element to the iframe document.
	return iframeDocument;
};

// Get Window for gutenberg
function pagelayerGetCurrentWindow(){
	var doc = pagelayerGetDocumentElement();
	return doc.defaultView || window;
}

// Modify jQuery for gutenberg tablet and mobile
function pagelayer_query(sel, doc) {
	doc = doc || pagelayerGetDocumentElement();
	return jQuery(sel, doc);
}

// Check if element is visible
function pagelayer_isVisible(ele) {
	
	var win = pagelayerGetCurrentWindow();
	
	var offset = win.innerHeight;
	var viewTop = win.pageYOffset;
	var viewBottom = viewTop + offset - Math.min(ele.height(), ele.innerHeight());
	var top = ele.offset().top;
	var bottom = top + ele.innerHeight();
	
	if(top <= viewBottom && bottom >= viewTop){
		return true;
	}
	
	return false;
}

// Get media mode
function pagelayer_get_media_mode(){
	
	var win = pagelayerGetCurrentWindow();
	
	if(win.matchMedia("(min-width: "+ (pagelayer_settings['tablet_breakpoint'] + 1) +"px)").matches){
		return 'desktop';
	}
	
	if(win.matchMedia("(max-width: "+ pagelayer_settings['tablet_breakpoint'] +"px) and (min-width: "+ (pagelayer_settings['mobile_breakpoint'] + 1) +"px)").matches){
		return 'tablet';
	}
	
	if(win.matchMedia("(max-width: "+ pagelayer_settings['mobile_breakpoint'] +"px)").matches){
		return 'mobile';
	}
	
	return 'desktop';
}
	  
// Row background video and parallax
function pagelayer_pl_row_video(jEle){
	
	var vEle = jEle.children('.pagelayer-background-video');
	
	// Not a video in the element
	if(vEle.length < 1){
		return true;
	}
	
	var setup = vEle.attr('pagelayer-setup');
	if(setup && setup.length > 0){
		return true;
	}

	var frame_width = vEle.width();
	var frame_height = (frame_width/100)*56.25;
	var height = vEle.height();
	
	if(frame_height < height){
		frame_height = height;
	}
	
	vEle.children().css({'width':frame_width+'px','height':frame_height+'px'});

	if(vEle.find('.pagelayer-youtube-video').length > 0){
		pagelayer_create_yt_player(vEle.find('.pagelayer-youtube-video'));
	}

	vEle.attr('pagelayer-setup', 1);
	
}

function pagelayer_create_yt_player(jEle){
    
	var pEle = jEle.parent(),
	aspectRatioSetting = '16:9',
	containerWidth = pEle.outerWidth(),
	containerHeight = pEle.outerHeight(),
	aspectRatioArray = aspectRatioSetting.split(':'),
	aspectRatio = aspectRatioArray[0] / aspectRatioArray[1],
	isWidthFixed = containerWidth / containerHeight > aspectRatio,
	width= isWidthFixed ? containerWidth : containerHeight * aspectRatio,
	height= isWidthFixed ? containerWidth / aspectRatio : containerHeight;
	
	var yt_api_interval = setInterval(function(){
		
		if(!(window.YT && YT.loaded)){
			return
		}
		
		clearInterval(yt_api_interval);
		
		var settings ={};
		settings.loop = jEle.attr('data-loop');
		settings.videoid = jEle.attr('data-videoid');
		settings.mute = jEle.attr('data-mute');

		var player;
		
		var onPlayerReady = function(event) {
			event.target.playVideo();
		}

		player = new YT.Player(jEle[0], {
				width: width,
				height: height,
				videoId: settings.videoid,
				playerVars: {
				controls: 0,
				rel: 0,
				loop: settings.loop,
				mute:settings.mute,
				playsinline: 1,
				playlist: settings.videoid,	
			},
			events: {
				'onReady': onPlayerReady,
			}
		});

	}, 500);

}

// Row background parallax
function pagelayer_pl_row_parallax(jEle){
	
	//Parallax background
	var setup = jEle.attr('pagelayer-setup');
	if(setup && setup.length > 0){
		return true;
	}
	
	new pagelayerParallax(jEle);
	jEle.attr('pagelayer-setup', 1);
}

// Adjust rows
function pagelayer_pl_row_full(jEle, html){
	
	html = html || pagelayer_query('html');
	
	// Get current width
	var vw = html[0].clientWidth;
	
	// Now give the row the width
	jEle.css({'width': vw, 'max-width': '100vw'});
	
	// Set the offset
	jEle.offset({left: 0});
	
	// Set a timeout as well as some themes can interfere with us
	setTimeout(function(){
		jEle.offset({left: 0});
	}, 500);
	
};

// Modal open
function pagelayer_render_pl_modal(e){
	jQuery(e.target).closest('.pagelayer-modal-container').find('.pagelayer-modal-content').show();
};

// Modal close
function pagelayer_pl_modal_close(e){
	jQuery(e.target).closest('.pagelayer-modal-content').hide();
}

// Setup the image slider
function pagelayer_pl_image_slider(jEle){
	
	var ul = jEle.find('.pagelayer-image-slider-ul');
	
	// Build the options
	var options = pagelayer_fetch_dataAttrs(ul, 'data-slides-');
	
	pagelayer_owl_init(jEle, ul, options);

}

function pagelayer_get_tab_ele(temp_tabCont){
	
	if(!pagelayer_empty(temp_tabCont.children('.pagelayer-ele-wrap').length)){
		return temp_tabCont.children('.pagelayer-ele-wrap').children('.pagelayer-tab');
	}else{
		return temp_tabCont.children('.pagelayer-tab');
	}
}

function pagelayer_tab_show(el, pl_id) {
	
	var parent_id = jQuery(el).closest('.pagelayer-tabs').attr('pagelayer-id');
	
	var temp_tabCont = pagelayer_query('[pagelayer-id='+pl_id+']').closest('.pagelayer-tabcontainer');
	pagelayer_get_tab_ele(temp_tabCont).hide();
	
	pagelayer_query('[pagelayer-id='+pl_id+']').show();
	
	jQuery(el).parent().find('.pagelayer-tablinks').each(function(){
		jQuery(this).removeClass('active');
	});
	
	jQuery(el).addClass("active");
}

var pagelayer_tab_timers = {};

function pagelayer_pl_tabs(jEle) {
	
	var default_active = '';
	var jEle_id = jEle.attr('pagelayer-id');	

	var tabCont = jEle.children('.pagelayer-tabcontainer');
	var children = pagelayer_get_tab_ele(tabCont);
	
	// Loop thru
	children.each(function(){
		var tEle = jQuery(this);
		var pl_id = tEle.attr('pagelayer-id');
				
		var title = tEle.attr('pagelayer-tab-title') || 'Tab';
		var func = "pagelayer_tab_show(this, '"+pl_id+"')";
		
		var icon = '';
		if(tEle.attr('pagelayer-tab-icon')){
			icon = tEle.attr('pagelayer-tab-icon');
		}
		
		// Set the default tab
		if(tEle.attr('pagelayer-default_active') && !pagelayer_empty(pl_id)){
			default_active = pl_id;
		}
		
		jEle.children('.pagelayer-tabs-holder').append('<span tab-id="'+pl_id+'" class="pagelayer-tablinks" onclick="'+func+'"> <i class="'+icon+'"></i> <span>'+title+'</span></span>');
	});

	// Set the default tab
	if(default_active.length > 0){
		pagelayer_tab_show(jEle.find('[tab-id='+default_active+']'), default_active);
	// Set the first tab as active
	}else{
		var first_tab = jEle.find('[tab-id]').first();
		pagelayer_tab_show(first_tab, first_tab.attr('tab-id'));
	}

	try{
		clearInterval(pagelayer_tab_timers[jEle_id]);
	}catch(e){};
	
	var rotate = parseInt(jEle.attr('pagelayer-tabs-rotate'));
	
	// Are we to rotate
	if(rotate > 0){
		
		var i= 0;
		pagelayer_tab_timers[jEle_id] = setInterval(function () {
			
			if(i >= children.length){
				i = 0;
			}
			
			var tabCont = jEle.children('.pagelayer-tabcontainer');
			var tmp_pl_ele = pagelayer_get_tab_ele(tabCont)[i];
			
			var tmp_btn_ele = jEle.find('.pagelayer-tablinks')[i]
			var tmp_pl_id = jQuery(tmp_pl_ele).attr('pagelayer-id');
			
			jEle.find('.pagelayer-tablinks').each(function(){
				jQuery(this).removeClass('active');
			});
			
			jQuery(tmp_btn_ele).addClass("active");
			pagelayer_tab_show(tmp_btn_ele, tmp_pl_id);
			
			i++;
	   
		}, rotate);
	}
	
}

// Setup the Accordion
function pagelayer_pl_accordion(jEle){
	
	var holder = jEle.find('.pagelayer-accordion-holder');
	var accHolder = jEle.find('.pagelayer-accordion_item');
	var scrolltop = false;
	
	if(accHolder.length < 1){
		return false;
	}
		
	var icon = holder.attr('data-icon');
	var active_icon = holder.attr('data-active_icon');
	
	accHolder.find('.pagelayer-accordion-tabs span i').attr('class', icon);
	var currentActiveTab = jEle.find('.pagelayer-accordion_item.active').first();
	
	// Any URL HASH ?
	var hash = location.hash.slice(1);	
	if(!pagelayer_empty(hash)){
		var scrollTab = jEle.find('#'+hash);
	
		if(!pagelayer_empty(scrollTab) && scrollTab.length > 0){
			currentActiveTab = scrollTab.closest('.pagelayer-accordion_item');
		}
	}
	
	holder.unbind('click');
	holder.on('click', '.pagelayer-accordion-tabs', function(){
		
		var currentTab = jQuery(this).closest('.pagelayer-accordion_item');
		
		if(currentTab.hasClass('active') && currentTab.children('.pagelayer-accordion-panel').is(':visible')){
			currentTab.removeClass('active').children('.pagelayer-accordion-panel').slideUp('slow');
			currentTab.find('.pagelayer-accordion-tabs span i').attr('class', icon);
			return true;
		}
		
		accHolder.find('.pagelayer-accordion-tabs span i').attr('class', icon);
		accHolder.removeClass('active').filter(function(index){
			return accHolder[index]!=currentTab[0];
		}).children('.pagelayer-accordion-panel').slideUp('slow');							
	
		currentTab.addClass('active').children('.pagelayer-accordion-panel').slideDown('slow');
		currentTab.find('.pagelayer-accordion-tabs span i').attr('class', active_icon);
		
	});
	
	// To prevent default active click in gutenberg
	var setup = jEle.attr('pagelayer-setup');
	
	// Already setup ?
	if(setup && setup.length > 0){
		return;
	}
	
	jEle.attr('pagelayer-setup', 1);
	
	// If active first tab from all active tabs
	currentActiveTab.removeClass('active');
	currentActiveTab.find('.pagelayer-accordion-tabs').click();
}

// Setup the Collapse
function pagelayer_pl_collapse(jEle){
	
	var holder = jEle.find('.pagelayer-collapse-holder');
	var tabs = jEle.find('.pagelayer-accordion_item');
		
	if(tabs.length < 1){
		return false;
	}
		
	var setup = tabs.attr('pagelayer-setup');
	var icon = holder.attr('data-icon');
	var active_icon = holder.attr('data-active_icon');
	
	// Any URL HASH ?
	var hash = location.hash.slice(1);	
	if(!pagelayer_empty(hash)){
		var scrollTab = jEle.find('#'+hash);
	
		if(!pagelayer_empty(scrollTab) && scrollTab.length > 0){
			scrollTab.closest('.pagelayer-accordion_item').addClass('active');
		}
	}
	
	var activeTabs = jEle.find('.pagelayer-accordion_item.active');

	tabs.find('.pagelayer-accordion-tabs span i').attr('class', icon);
	jQuery(activeTabs).addClass('active').children('.pagelayer-accordion-panel').slideDown('slow');
	jQuery(activeTabs).find('.pagelayer-accordion-tabs span i').attr('class', active_icon);
		
	// Already setup ?
	if(setup && setup.length > 0){
		tabs.find('.pagelayer-accordion-tabs').unbind('click');
	}

	tabs.find('.pagelayer-accordion-tabs').click(function(){
		
		var currentTab = jQuery(this).closest('.pagelayer-accordion_item');
		
		if(currentTab.hasClass('active')){
			currentTab.removeClass('active').children('.pagelayer-accordion-panel').slideUp('slow');
			currentTab.find('.pagelayer-accordion-tabs span i').attr('class', icon);
			return true;
		}
			
		currentTab.addClass('active').children('.pagelayer-accordion-panel').slideDown('slow');
		currentTab.find('.pagelayer-accordion-tabs span i').attr('class', active_icon);
		
	});
	
	// Set that we have setup everything
	tabs.attr('pagelayer-setup', 1);
	
}

// Counter
function pagelayer_counter(){
	
	pagelayer_query('.pagelayer-counter-content').each(function(){
		
		var jEle = jQuery(this);
		
		if(pagelayer_isVisible(jEle)){
			
			var setup = jEle.attr('pagelayer-setup');
			
			// Already setup ?
			if(setup && setup.length > 0){
				return true;
			}
			
			var options = {};
			options['duration'] = jEle.children('.pagelayer-counter-display').attr('pagelayer-counter-animation-duration');
			options['delimiter'] = jEle.children('.pagelayer-counter-display').attr('pagelayer-counter-seperator-type');
			options['toValue'] = jEle.children('.pagelayer-counter-display').attr('pagelayer-counter-last-value');					
			jEle.children('.pagelayer-counter-display').numerator( options );
		
			// Set that we have setup everything
			jEle.attr('pagelayer-setup', 1);
			
		}
	});
}

function pagelayer_progress(){
	pagelayer_query('.pagelayer-progress-container').each(function(){
		var jEle = jQuery(this);
		
		if(pagelayer_isVisible(jEle)){
			
			var setup = jEle.attr('pagelayer-setup');
			if(setup && setup.length > 0){
				return true;
			}
			
			var progress_width = jEle.children('.pagelayer-progress-bar').attr('pagelayer-progress-width');
			if(progress_width == undefined){
				progress_width = "1";
			}
			
			var width = 0;
			var interval;
			
			var progress = function(){
				if (width >= progress_width) {
					clearInterval(interval);
				} else {
					width++;
					jEle.children('.pagelayer-progress-bar').css('width', width + '%'); 
					jEle.find('.pagelayer-progress-percent').text(width * 1  + '%');
				}
			}
			interval = setInterval(progress, 30);
			jEle.attr('pagelayer-setup', 1);
			
		}
	});
}

// Dismiss Alert Function
function pagelayer_dismiss_alert(x){
	
	if(!pagelayer_empty(pagelayer_is_live)){
		return;
	}
	
	jQuery(x).parent().parent().fadeOut();
}

// Video light box handler
function pagelayer_pl_video(jEle){
	var videoIframe = jEle.find('.pagelayer-video-iframe');
	// Adding loop, autoplay and mute properties on video before loading 
	videoIframe.on('load', function() {
		
		// Checking of video source if it is youtube or vimeo because 
		// TODO: Need to check, if this is not local file then return
		if(jQuery(this)[0].src.indexOf('youtube.com') != -1 || jQuery(this)[0].src.indexOf('vimeo.com') != -1){
			return;
		}
		
		var vidElm = jQuery(this).contents().find('video');
		var vidSrc = (pagelayer_empty(vidElm[0].src)) ? vidElm.children()[0].src : vidElm[0].src;	
		
		if(vidSrc[vidSrc.indexOf('&loop=')+6] == 1){
			vidElm.attr('loop','loop');
		}
		if(vidSrc[vidSrc.indexOf('&autoplay=')+10] == 0){
			vidElm.removeAttr('autoplay');
			vidElm[0].pause();
		}else if(vidSrc[vidSrc.indexOf('&autoplay=')+10] == 1){
			vidElm.attr('autoplay','');
			vidElm.attr('playsinline','');			
		}
		if(vidSrc[vidSrc.indexOf('&mute=')+6] == 1){
			vidElm[0].muted = "muted";
			vidElm.attr('muted','');	
		}
	});
	
	// A tag will be there ONLY if the lightbox is on
	var overlayval = jEle.find('.pagelayer-video-overlay');	
	var a = jEle.find(".pagelayer-video-holder a");
	
	// No lightbox
	if(a.length < 1 && pagelayer_empty(overlayval)){
		return;
	}

	a.nivoLightbox({
		effect: "fadeScale",
	});
	
	jEle.find(".pagelayer-video-holder .pagelayer-video-overlay").on("click", function(ev) {

		var target = jQuery(ev.target);

		if (!target.parent("a").length) {
			videoIframe[0].src = videoIframe[0].src.replace("&autoplay=0", "rel=0&autoplay=1");
			jQuery(this).hide();
		}
	});
	
}

// Image light box handler
function pagelayer_pl_image(jEle){
	
	// Drag and Drop function for image
	if (typeof pagelayer_preDAndD_image !== "undefined") {
		pagelayer_preDAndD_image(jEle);
	}
	
	// A tag will be there ONLY if the lightbox is on
	var a = jEle.find("[pagelayer-image-link-type=lightbox]");
	
	// No lightbox
	if(a.length < 1){
		return;
	}
	
	a.nivoLightbox({
		effect: "fadeScale",
	});
}

function pagelayer_stars(jEle){

	var setup = jEle.attr('pagelayer-setup');
	if(setup && setup.length > 0){
		return true;
	}
	var count = jEle.attr('pagelayer-stars-count');
		
	if (isNaN(count)) {
		count = '0';
	}
		
	i = 0;
	var stars = "";
	while(i < count){			
		stars +='<div class="pagelayer-stars-icon pagelayer-stars-empty"><i class="fas fa-star" aria-hidden="true"></i></div>';
		i++;
	}

	jEle.empty();
	jEle.append(stars);
	var starsval = jEle.attr('pagelayer-stars-value');
		
	if (isNaN(starsval)) {
		starsval = count;
	}

	starsval = starsval.split('.');		
	var fullstars = starsval[0];
	var value =  starsval[1];
	var halfstar = parseInt(fullstars) + 1;
	var emptystars = parseInt(fullstars) + 2;
	jEle.children('.pagelayer-stars-icon').attr("class","pagelayer-stars-icon");
	jEle.children('.pagelayer-stars-icon:nth-child(-n+'+ fullstars +')').addClass('pagelayer-stars-full'); 
	if(value != undefined){
		jEle.children('.pagelayer-stars-icon:nth-child('+ halfstar +')').addClass('pagelayer-stars-'+value);		
	}else{
		jEle.children('.pagelayer-stars-icon:nth-child('+ halfstar +')').addClass('pagelayer-stars-empty');
	}
	jEle.children('.pagelayer-stars-icon:nth-child(n+'+ emptystars +')').addClass('pagelayer-stars-empty'); 		
	jEle.attr('pagelayer-setup', 1);
}

// Grid Gallery pagination Off On function
function pagelayer_pl_grid_paginate(gridCont, pagination, pageValue, gridValue){
	gridCont.hide();
	pagination.removeClass('active');
	pagination.eq(pageValue).addClass('active');
	gridCont.eq(gridValue).show();
}

//Grid Gallery Lightbox
function pagelayer_pl_grid_lightbox(jEle){
	
	// Grid Gallery pagination settings
	var gridCont = jEle.find('.pagelayer-grid-gallery-container').children();
	var pagination = jEle.find('.pagelayer-grid-gallery-pagination ul').children();
	gridCont.hide();
	gridCont.eq(0).show();
	// Adding event listners to pagination
	jEle.find('.pagelayer-grid-page-item').each(function(){
		jQuery(this).on('click', function(event){
			var text = jQuery(this).text();
			switch(text){
				case '«':
					pagelayer_pl_grid_paginate(gridCont, pagination, 1, 0);
					break;
				case '»':
					pagelayer_pl_grid_paginate(gridCont, pagination, (pagination.length-2), (gridCont.length-1));
					break;
				default:
					pagelayer_pl_grid_paginate(gridCont, pagination, text, text-1);
					break;
			}
		});
	});	

	// A tag will be there ONLY if the lightbox is on
	var a = jEle.find("[pagelayer-grid-gallery-type=lightbox]");
	
	// No lightbox
	if(a.length < 1){
		return;
	}
	
	a.nivoLightbox({
		effect: "fadeScale",
		keyboardNav: true,
		clickImgToClose: false,
		clickOverlayToClose: true,
	});
}

// Is string?
function pagelayer_is_string(str){
   
   if(typeof str == 'string'){
	   return true;
   }
   
   return false;
}

// PHP equivalent empty()
function pagelayer_empty(mixed_var) {

  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, '', '0'];

  for (i = 0, len = emptyValues.length; i < len; i++) {
	if (mixed_var === emptyValues[i]) {
	  return true;
	}
  }

  if (typeof mixed_var === 'object') {
	for (key in mixed_var) {
	  // TODO: should we check for own properties only?
	  //if (mixed_var.hasOwnProperty(key)) {
	  return false;
	  //}
	}
	return true;
  }

  return false;
};

function pagelayer_fetch_dataAttrs(ele, prefix){
	
	var options = {};
	
	jQuery.each(ele.get(0).attributes, function(i, attrib){
		
		//console.log(attrib);
		if(attrib.name.includes(prefix)){
			
			var opt_name = attrib.name.substring(prefix.length);
			
			// Check for any Uppercase attribute
			if(opt_name.includes('-')){
				
				opt_name = opt_name.split('-');
				//console.log(opt_name);
				var opt_arr = [];
				jQuery.each(opt_name, function(key, value) {
					if(key != 0){
						opt_arr.push(value.charAt(0).toUpperCase() + value.slice(1));
					}else{
						opt_arr.push(value);
					}
				});
				//console.log(opt_arr);
				opt_name = opt_arr.join('');
			}
			
			// Make the values correct
			var val = attrib.value;
			if(val == 'true') val = true;
			if(val == 'false') val = false;
			if(jQuery.isNumeric(val)) val = parseInt(val);
			
			options[opt_name] = val;
		}
	});
	
	//console.log(options);
	
	if(options['controls']){
		switch(options['controls']){
			case 'arrows':
				options['nav'] = true;
				options['dots'] = false;
				break;
			case 'pager':
				options['dots'] = true;
				options['nav'] = false;
				break;
			case 'none':
				options['nav'] = false;
				options['dots'] = false;
				break;
		}
	}else{
		options['nav'] = true;
		options['dots'] = true;
	}
	
	if(options['animateIn']){
		switch(options['controls']){
			case 'horizontal':
				options['animateIn'] = 'slideInLeft';
				break;
			case 'vertical':
				options['animateIn'] = 'slideInDown';
				break;
			case 'kenburns':
				options['animateIn'] = 'zoomIn';
				break;
			default:
				options['animateIn'] = options['animateIn'];
		}
	}
	
	if(!options['items']){
		options['items'] = 1;
	}
	
	options['responsive'] = {
		0:{items: 1},
		500:{items: options['items']}
	}

	options['responsiveRefreshRate'] = 1000;
	
	// If we are in editor don't loop the Owl items
	if (window.location.href.indexOf('pagelayer-live=1') > -1) {
		//console.log('here');
		options['loop'] = false;
	}
	
	return options;
}

function pagelayer_owl_init(jEle, ul, options){
	
	//console.log(options);
	var setup = jEle.attr('pagelayer-setup');
	var id = jEle.closest('[pagelayer-id]').attr('[pagelayer-id]');
	
	if( options.navtext ) {
		var right = options.navtext.replace('left','right');
		options.navText = [`<i class="${options.navtext}"></i>`, `<i class="${right}"></i>`];
	}
	
	// Already setup ?
	if(setup && setup.length > 0){
		return true;
	}
	
	const wind = pagelayerGetCurrentWindow();
	options['responsiveBaseElement'] = wind;
	
	var owlCar = ul.pagelayerOwlCarousel(options);
	
	// Refreshing Image slider after first load of page.
	var referrerOwl = function(){
		setTimeout(function(){
			owlCar.trigger('refresh.owl.carousel');
		},700);
	}
	
	referrerOwl();
	
	// To prevent slider drag inside the editable area
	jEle.on('mousedown', function(e){
		var target = e.target;
		
		var isEditable = jQuery(target).closest('[contenteditable="true"]');
		
		if(isEditable.length < 1){
			return;
		}
		
		isEditable.on('mousedown.owl.core dragstart.owl.core selectstart.owl.core touchstart.owl.core touchcancel.owl.core', function(e){
			e.stopPropagation();
		});
		
	});
	
	// Gutenberg responsive Handler
	if(wind != window){		
		jQuery(wind).unbind('resize.pl_'+id, referrerOwl);
		jQuery(wind).on('resize.pl_'+id, referrerOwl);
	}
	
	// Set that we have setup everything
	jEle.attr('pagelayer-setup', 1);
	
}

// recaptcha handler
function pagelayer_recaptcha_loader(jEle, loadScript){
	
	loadScript = loadScript || false;
	
	// Render recaptcha
	var reParam = '';
	
	if(!pagelayer_empty(pagelayer_recaptch_lang)){
		reParam = '&hl='+pagelayer_recaptch_lang;
	}
	
	// Add recaptcha script
	if(pagelayer_empty(window.grecaptcha) && !pagelayer_empty(loadScript)){
		pagelayer_query('body').append('<script src="https://www.google.com/recaptcha/api.js?render=explicit'+reParam+'" async defer></script>');
	}
	
	// Render recaptcha
	var recaptcha_interval = setInterval(function(){
		
		if(!pagelayer_empty(window.grecaptcha)){
			grecaptcha.ready(function() {
				try{			
					var widgetID = grecaptcha.render(jEle.get(0), {'sitekey' : jEle.data("sitekey")});
					jEle.attr('recaptcha-widget-id', widgetID);
				}catch(e){
					console.log("There is some issue in rendering reCaptcha. Please check your recaptcha site-key !");
				}
				
			});
			clearInterval(recaptcha_interval);
		}

	}, 500);
 
}

// Scroll to element button effect
function pagelayer_button_element_scroll(jEle) {

	var speed = parseInt(jEle.attr('pagelayer_scrollto_speed') * 1000);
	var idspacing = 0;
	var scrollId = jEle.attr('pagelayer_scrollto_id');

	if(jEle.attr('pagelayer_scrollto_type') == 'toid'){

		var scrolltoEle = pagelayer_query('#' + scrollId);

		if(pagelayer_empty(scrollId) || scrolltoEle.length < 1){
			return;
		}

		var idpos = parseInt(scrolltoEle.offset().top);
		var spacing = parseInt(jEle.attr('pagelayer_scrollto_id_viewport'));

		if (isNaN(spacing)) {
			spacing = 0;
		}

		idspacing = idpos + spacing;
		
	}

	jEle.on('click', function (e) {
		e.preventDefault();
		pagelayer_query('html, body').animate({ scrollTop: idspacing }, speed);
	});
	
}

////////////
// Freemium
////////////

// Contact Form handler - Premium
function pagelayer_contact_form(jEle){
	
	jEle = jQuery(jEle);
	var id = jEle.attr('pagelayer-id');
	
	// Set pagelayer id to input field
	jEle.find('form input[name="cfa-pagelayer-id"]').val(id);
 
}

// Contact Form Submit handler - Premium
function pagelayer_contact_submit(jEle, e){
	e.preventDefault();
	
	// Checking for required checkboxes.
	for(var checkbox_div of jQuery(jEle).find('.pagelayer-contact-checkbox')){
		checkbox_div = jQuery(checkbox_div);
		if(checkbox_div.attr('required') == 'required'){
			if(pagelayer_empty(checkbox_div.find('input:checked').length)){
				alert('Kindly select the required checkbox');
				return;
			}
		}		
	}
	
	// Trigger an action
	jQuery(document).trigger('pagelayer_contact_submit', e, jEle);
	
	// Disabling submit button with loading animation.
	jQuery(jEle).find('.pagelayer-contact-submit-btn').prop('disabled', true);
	jQuery(jEle).find('.pagelayer-contact-submit-btn .fa-spin').show('0.6');
	
	//var fdata = jQuery(jEle).closest('form').serialize();
	var redirect = jQuery(jEle).find('input[name="cfa-redirect"]');
	var formData = new FormData( jQuery(jEle)[0] );
	var par = jQuery(jEle).parent();
	
	// Append the nonce
	formData.append('pagelayer_nonce', pagelayer_global_nonce);
	
	// Hide any message
	par.find(".pagelayer-message-box").hide();
	
	// Message pos to use ?
	var msg_pos = 'top';	
	if(par.parent().hasClass('pagelayer-message-box-bottom')){
		msg_pos = 'bottom';
	}
	
	par.find(".pagelayer-message-box").removeClass('pagelayer-cf-msg-err pagelayer-cf-msg-suc');
	
	jQuery.ajax({
		url: pagelayer_ajaxurl+'action=pagelayer_contact_submit',
		type: "POST",
		data: formData,
		processData: false,
		contentType: false,
		cache:false,
		success:function(result){
			var json = jQuery.parseJSON(result);
			
			jQuery(jEle).find('.pagelayer-contact-submit-btn').prop('disabled', false);
			jQuery(jEle).find('.pagelayer-contact-submit-btn .fa-spin').hide('0.6');
			
			if('success' in json){
				par.find(".pagelayer-message-"+msg_pos).addClass('pagelayer-cf-msg-suc').html(json['success']).fadeIn();
				
				if(redirect.length > 0 && !pagelayer_empty(redirect.val())){
					window.location.href = redirect.val();
				}
			}else{
				par.find(".pagelayer-message-"+msg_pos).addClass('pagelayer-cf-msg-err').html(json['failed']).fadeIn();
			}
		},
		error:function(result){
			par.find(".pagelayer-message-"+msg_pos).addClass('pagelayer-cf-msg-err').html(json['failed']).fadeIn();
		}
	});
	jEle.reset();
	
	jQuery(jEle).find('.pagelayer-recaptcha').each(function(){
		var widgetID = jQuery(this).attr('recaptcha-widget-id');
		
		if(!pagelayer_empty(window.grecaptcha)){
			grecaptcha.reset(widgetID);
		}
	});
	
	return false;
}

// Primary Menu Handler - Premium
function pagelayer_primary_menu(jEle){
	
	var container = jEle.find('.pagelayer-wp-menu-container');
	var menu_bar = jEle.find('.pagelayer-primary-menu-bar i');
	var menu_holder = jEle.find('.pagelayer-wp-menu-holder');
	var layout = menu_holder.attr('data-layout');
	var submenu_ind = menu_holder.attr('data-submenu_ind');
	var responsive = menu_holder.attr('data-responsive');
	var drop_breakpoint = menu_holder.attr('data-drop_breakpoint');
	var close = jEle.find('.pagelayer-wp_menu-close');
	
	var media_mode =  pagelayer_get_media_mode();
	
	if( (drop_breakpoint == 'tablet' && (media_mode == 'tablet' || media_mode == 'mobile')) || (drop_breakpoint == 'mobile' && media_mode == 'mobile') ){
		
		menu_holder.addClass('pagelayer-wp-menu-dropdown');
		container.addClass('pagelayer-menu-type-dropdown');
		container.removeClass('pagelayer-menu-type-'+layout);
		layout = 'dropdown';
		
	}else{
		menu_holder.removeClass('pagelayer-wp-menu-dropdown');
		container.removeClass('pagelayer-menu-type-dropdown');
		container.addClass('pagelayer-menu-type-'+layout);
	}
	
	// Set mega menu width
	// Wait for all other animations to finish
	setTimeout(function(){

		container.find('.pagelayer-mega-menu-item, .pagelayer-mega-column-item').each(function(){
  
			var liEle = jQuery(this),
			lEle = liEle.find('.pagelayer-nav_menu_item').first(),
			megaHolder = lEle.closest('.pagelayer-mega-menu'),				
			setClass = 'pagelayer-set-position';				
			
			if(liEle.hasClass('pagelayer-mega-column-item')){
				megaHolder = liEle.children('.sub-menu');
			}
			
			if(megaHolder.length < 1){
				return;
			}
						
			var Css = {};
			
			// Remove all css settings
			jQuery(document).unbind('scroll.megaMenu');
			megaHolder.css({'width' : '', 'left' : '', 'max-width' : '', 'max-height' : ''});
			
			if(layout == 'dropdown'){
				return;
			}
			
			// Set active to get position
			megaHolder.addClass(setClass);
			
			var megaLeft = megaHolder.offset().left,
			megaWidth = lEle.attr('pagelayer-mega-width'),
			wContainer = lEle.closest('.pagelayer-wp-menu-container'),
			megaCustomWidth = lEle.attr('pagelayer-mega-custom-width') || '',
			widthEle;
			
			// Is vertical menu?
			if(layout == 'vertical'){
				var docWidth = jQuery('body').width();
				var vWidth = docWidth - megaLeft;
				
				Css['max-width'] = vWidth;
				Css['width'] = vWidth;
				
				if(megaWidth == 'custom'){
					Css['width'] = megaCustomWidth;
				}
				
				megaHolder.css(Css);
				megaHolder.removeClass(setClass);
				return;
			}

			var megaMenuHeight = function(e){
				
				if(!pagelayer_empty(e) && megaHolder.is(':visible')){
					return;
				}

				var windowHeight = jQuery(window).height();
				var ulBottom = megaHolder.closest('.pagelayer-wp_menu-ul')[0].getBoundingClientRect().bottom;
				megaHolder.css('max-height', windowHeight - ulBottom);
			};

			megaMenuHeight();
			jQuery(document).on('scroll.megaMenu', megaMenuHeight);
			
			switch(megaWidth){
				case 'row_container':
					widthEle = lEle.closest('.pagelayer-row[pagelayer-id]');
					Css['width'] = widthEle.width();
					break;
				case 'custom':
					widthEle = lEle.closest('li');
					Css['width'] = megaCustomWidth;					
					break;
				default :
					widthEle = wContainer;
					Css['width'] = widthEle.width();
			}
			
			if(widthEle.length > 0){
				var wLeft = widthEle.offset().left;
				
				if( wLeft < megaLeft ){
					Css['left'] = (wLeft) - (megaLeft);
				}
			
			}
			
			megaHolder.css(Css);
			var mRect = megaHolder[0].getBoundingClientRect();
			var wRect = wContainer[0].getBoundingClientRect();
			
			// Set mega menu position
			if(megaWidth != 'custom' || mRect.right < wRect.right){
				megaHolder.removeClass(setClass);
				return;
			}
			
			var left = parseInt(megaHolder.css('left'));
			var moveLeft = mRect.right - wRect.right;
			
			if(mRect.left < moveLeft){
				moveLeft = moveLeft - (moveLeft - mRect.left);
			}
			
			left = left - moveLeft;
			megaHolder.css({'left': left});
			megaHolder.removeClass(setClass);
		});
  
	}, 500);
	
	// Menu toggle
	var toggle_class;
	jQuery(menu_bar).unbind('click');
	jQuery(menu_bar).click(function(){
		jQuery(container).toggleClass('pagelayer-togglt-on');
		
		toggle_class = jQuery(this).data('icon');
		toggle_class = ( pagelayer_empty(toggle_class) ? 'fas fa-bars' : toggle_class );
		
		if(jQuery(container).hasClass('pagelayer-togglt-on')){
			jQuery(this).removeClass(toggle_class);
			jQuery(this).addClass('fas fa-times');
		}else{
			jQuery(this).removeClass('fas fa-times');
			jQuery(this).addClass(toggle_class);
		}
	});
	
	// If has sub-menu the as icon
	var sub_menuEle = jQuery(container).find('.pagelayer-wp_menu-ul li.menu-item-has-children:not(.pagelayer-mega-menu-item), .pagelayer-wp_menu-ul li.pagelayer-mega-menu-item');
	
	var aEle_sub_menu = sub_menuEle.children('a');
	var afterIconSpan = aEle_sub_menu.children('.after-icon')
	if (aEle_sub_menu.children('.after-icon').length < 1) {
		aEle_sub_menu.append('<span class="after-icon fa fa-' + submenu_ind + '"></span>');
	}else{
		// For gutenberg
		afterIconSpan.attr('class', 'after-icon fa fa-' + submenu_ind);
	}

	// Toggle Sub nav
	var after_icon = jQuery(container).find('.pagelayer-wp_menu-ul li.menu-item-has-children .after-icon, .pagelayer-wp_menu-ul li.pagelayer-mega-menu-item .after-icon');
	
	after_icon.unbind('click');
	after_icon.click(function(e){
		e.preventDefault();
		if(window.matchMedia("(max-width: "+pagelayer_settings['tablet_breakpoint']+"px)").matches || layout != 'horizontal'){
			jQuery(this).closest('li').toggleClass('pagelayer-active-sub-menu');

		}else{
			jQuery(this).closest('li').removeClass('pagelayer-active-sub-menu');
		}
	});
	
	close.unbind('click');
	close.click(function(){
		jQuery(container).toggleClass('pagelayer-togglt-on');
		jQuery(menu_bar).removeClass('fas fa-times');
		jQuery(menu_bar).addClass(toggle_class);
	});
	
	// To edit the mega menu in live editor
	jQuery(document).trigger('pagelayer_primary_menu_setup_end', [jEle]);	
}

var count_int ={};
// Show countdown render
function pagelayer_countdown(jEle){
	
	var expiry_date = jEle.find('.pagelayer-countdown-container').attr('pagelayer-expiry-date');
	var timetype = jEle.find('.pagelayer-countdown-container').attr('pagelayer-time-type');
	var jEle_id = jEle.attr('pagelayer-id');
	
	if(pagelayer_empty(expiry_date) || expiry_date == "{{date}}"){
		var expiry_date = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
	}
	
	var now;
	if(timetype == "server"){
		now = new Date(pagelayer_server_time*1000).getTime();
	}else{
		now = new Date().getTime();
	}

	var countDownDate = new Date(expiry_date).getTime();
	var distance = countDownDate - now;

	clearInterval(count_int[jEle_id]);
	count_int[jEle_id] = setInterval(function() {
		
		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		
		jEle.find('.pagelayer-days-count').html(days);
		jEle.find('.pagelayer-hours-count').html(hours);
		jEle.find('.pagelayer-minutes-count').html(minutes);
		jEle.find('.pagelayer-seconds-count').html(seconds);
		
		// If the count down is over, write some text 
		if(distance < 0) {
			clearInterval(count_int[jEle_id]);
			jEle.find('.pagelayer-countdown-expired').show();
			jEle.find('.pagelayer-countdown-counter').hide();
		}
		
		distance = distance - 1000;
		
	}, 1000);
}

function pagelayer_pl_testimonial_slider(jEle){
	var ul = jEle.find('.pagelayer-testimonials-holder');
	
	// Build the options
	var options = pagelayer_fetch_dataAttrs(ul, 'data-slides-');
	
	pagelayer_owl_init(jEle, ul, options);
}

var pagelayerAnimTimer = {};
function pagelayer_anim_heading(jEle){
	var animationDelay = 2500,
		//loading bar effect
		barAnimationDelay = 3800,
		barWaiting = barAnimationDelay - 3000, //3000 is the duration of the transition on the loading bar - set in the scss/css file
		//letters effect
		lettersDelay = 50,
		//type effect
		typeLettersDelay = 150,
		selectionDuration = 500,
		typeAnimationDelay = selectionDuration + 800,
		//clip effect 
		revealDuration = 600,
		revealAnimationDelay = 1500;
	
	var id = jEle.attr('pagelayer-id');
	
	if(pagelayer_empty(pagelayerAnimTimer[id])){
		pagelayerAnimTimer[id] = {};
	}
	
	// Clear the previous animations
	for( var time in pagelayerAnimTimer[id] ){
		clearTimeout(pagelayerAnimTimer[id][time]);
	}
	
	initHeadline();

	function initHeadline(){
		
		// Re-set styles
		jEle.find('.pagelayer-aheading-holder').find('span').css('opacity', '');
		
		//insert <i> element for each letter of a changing word
		singleLetters(jEle.find('.pagelayer-aheading-holder.letters').find('span'));
		//initialise headline animation
		animateHeadline(jEle.find('.pagelayer-aheading-holder'));
	}

	function singleLetters($words){
		$words.each(function(){
			var word = jQuery(this),
				letters = word.text().split(''),
				selected = word.hasClass('pagelayer-is-visible');
			for (i in letters) {
				if(word.parents('.pagelayer-aheading-rotate2').length > 0) letters[i] = '<b>' + letters[i] + '</b>';
				letters[i] = (selected) ? '<strong class="pagelayer-aheading-in">' + letters[i] + '</strong>': '<strong>' + letters[i] + '</strong>';
			}
		    var newLetters = letters.join('');
		    word.html(newLetters).css('opacity', 1);
		});
	}

	function animateHeadline($headlines){
		var duration = animationDelay;
		$headlines.each(function(){
			var headline = jQuery(this);
			
			if(headline.hasClass('pagelayer-aheading-loading-bar')){
				duration = barAnimationDelay;
				setTimeout(function(){ headline.find('.pagelayer-words-wrapper').addClass('pagelayer-is-loading') }, barWaiting);
			}else if(headline.hasClass('pagelayer-aheading-clip')){
				var spanWrapper = headline.find('.pagelayer-words-wrapper'),
					newWidth = spanWrapper.width() + 10;
				spanWrapper.css('width', newWidth);
			} else if (!headline.hasClass('type') ){
				var words = headline.find('.pagelayer-words-wrapper span'),
					width = 0;
				words.each(function(){
					var wordWidth = jQuery(this).width();
				    if (wordWidth > width) width = wordWidth;
				});
				headline.find('.pagelayer-words-wrapper').css('width', width);
			};

			//trigger animation
			setTimeout(function(){
				hideWord( headline.find('.pagelayer-is-visible').eq(0) );
			}, duration);
		});
	}

	function hideWord($word){
		var nextWord = takeNext($word);
		
		if($word.parents('.pagelayer-aheading-holder').hasClass('letters')){
			var bool = ($word.children('strong').length >= nextWord.children('strong').length) ? true : false;
			hideLetter($word.find('strong').eq(0), $word, bool, lettersDelay);
			showLetter(nextWord.find('strong').eq(0), nextWord, bool, lettersDelay);

		}else if($word.parents('.pagelayer-aheading-holder').hasClass('pagelayer-aheading-clip')){
			$word.parents('.pagelayer-words-wrapper').animate({ width : '2px' }, revealDuration, function(){
				switchWord($word, nextWord);
				showWord(nextWord);
			});

		}else if($word.parents('.pagelayer-aheading-holder').hasClass('pagelayer-aheading-loading-bar')){
			$word.parents('.pagelayer-words-wrapper').removeClass('pagelayer-is-loading');
			switchWord($word, nextWord);
			
			clearTimeout(pagelayerAnimTimer[id][0]);
			pagelayerAnimTimer[id][0] = setTimeout(function(){
				hideWord(nextWord);
			}, barAnimationDelay);
			clearTimeout(pagelayerAnimTimer[id][1]);
			pagelayerAnimTimer[id][1] = setTimeout(function(){
				$word.parents('.pagelayer-words-wrapper').addClass('pagelayer-is-loading');
			}, barWaiting);

		}else{
			switchWord($word, nextWord);
			clearTimeout(pagelayerAnimTimer[id][2]);
			pagelayerAnimTimer[id][2] = setTimeout(function(){
				hideWord(nextWord) ;
			}, animationDelay);
		}
	}

	function showWord($word, $duration){
		if($word.parents('.pagelayer-aheading-holder').hasClass('pagelayer-aheading-clip')){
			$word.parents('.pagelayer-words-wrapper').animate({ 'width' : $word.width() + 10 }, revealDuration, function(){ 
				clearTimeout(pagelayerAnimTimer[id][3]);
				pagelayerAnimTimer[id][3] = setTimeout(function(){
					hideWord($word);
				}, revealAnimationDelay); 
			});
		}
	}

	function hideLetter($letter, $word, $bool, $duration){
		$letter.removeClass('pagelayer-aheading-in').addClass('pagelayer-aheading-out');
		
		if(!$letter.is(':last-child')){
			clearTimeout(pagelayerAnimTimer[id][4]);
			pagelayerAnimTimer[id][4] = setTimeout(function(){
				hideLetter($letter.next(), $word, $bool, $duration);
			}, $duration);  
		}else if($bool){ 
			clearTimeout(pagelayerAnimTimer[id][5]);
			pagelayerAnimTimer[id][5] = setTimeout(function(){
				hideWord(takeNext($word));
			}, animationDelay);
		}

		if($letter.is(':last-child') && pagelayer_query('html').hasClass('pagelayer-no-csstransitions')){
			var nextWord = takeNext($word);
			switchWord($word, nextWord);
		} 
	}

	function showLetter($letter, $word, $bool, $duration){
		$letter.addClass('pagelayer-aheading-in').removeClass('pagelayer-aheading-out');

		if(!$word.hasClass('pagelayer-is-visible')){ 
			$word.parent().children().removeClass('pagelayer-is-visible');
			$word.addClass('pagelayer-is-visible');
		}

		if(!$letter.is(':last-child')){ 
			clearTimeout(pagelayerAnimTimer[id][6]);
			pagelayerAnimTimer[id][6] = setTimeout(function(){
				showLetter($letter.next(), $word, $bool, $duration);
			}, $duration); 
		}else{
			if(!$bool) { 
				clearTimeout(pagelayerAnimTimer[id][7]);
				pagelayerAnimTimer[id][7] = setTimeout(function(){ 
					hideWord($word);
				}, animationDelay) 
			}
		}
	}

	function takeNext($word){
		return (!$word.is(':last-child')) ? $word.next() : $word.parent().children().eq(0);
	}

	function switchWord($oldWord, $newWord){
		$oldWord.removeClass('pagelayer-is-visible').addClass('pagelayer-is-hidden');
		if(!$newWord.hasClass('pagelayer-is-visible')){
			$newWord.removeClass('pagelayer-is-hidden').addClass('pagelayer-is-visible');
		}
	}
}

var pagelayerSetInterval = {};
function pagelayer_pl_row_slider(jEle){
	var index = 0;
 
	var id = jEle.closest('[pagelayer-id]').attr('pagelayer-id');
	var imageEls = jEle.find('.pagelayer-bgimg-slide'); // Get the images to be cycled.
	var speed = parseFloat(jEle.attr('data-speed')); // Get the speed of loop.
	imageEls.first().addClass('pagelayer-slide-show');
	
	clearInterval(pagelayerSetInterval[id]);
	pagelayerSetInterval[id] = setInterval(function (){
		// Get the next index.  If at end, restart to the beginning.
		index = index + 1 < imageEls.length ? index + 1 : 0;
		
		// Show the next
		imageEls.eq(index).addClass('pagelayer-slide-show');
		
		// Hide the previous
		imageEls.eq(index - 1).removeClass('pagelayer-slide-show');
	}, speed);
}

function pagelayer_pl_social_profile(jEle){
	var icon_holder = jEle.find('.pagelayer-icon-holder');
	
	// Assigning animation classes to icon holder
	if(!pagelayer_empty(jEle.attr('pagelayer-animation'))){
		icon_holder.addClass('pagelayer-animation-'+jEle.attr('pagelayer-animation'));
	}
}

// Post infinite scroll handler
function pagelayer_infinite_posts(jEle) {
	
	var loader = jEle.find('.pagelayer-btn-load');
	var autoScroll = jEle.find('.pagelayer-infinite-scroll-auto').length < 1;
	
	loader.on('click', function(){
	
		var bEle = jQuery(this);
		var loaded = jEle.attr('pagelayer-post-data-loading');
		
		// Is loading?
		if(!pagelayer_empty(loaded)){
			return;
		}
		
		jEle.attr('pagelayer-post-data-loading', 1);

		var current = bEle.attr('data-current') || 1;
		var nextPage = parseInt(current) + 1;
		var load_btn = jEle.find('.pagelayer_load_button');

		bEle.hide();
		load_btn.find('.pagelayer-loader-holder').show();

		if(jEle.find('.pagelayer-post-max').attr('data-max') <= 1) {
			load_btn.text(load_btn.data('text'));
			return;
		}
		
		// Get Data from local variable
		var data = window['pagelayer_local_scripts']['pagelayer_post_' + jEle.attr("pagelayer-id")];
		
		// Add next page number to load
		if(!pagelayer_empty(data.atts)){
			data.atts['paged'] = nextPage;
		}
		
		// Get the Posts
		jQuery.ajax({
			url: pagelayer_ajaxurl + 'action=pagelayer_infinite_posts',
			type: 'POST',
			data: {
				pagelayer_nonce: pagelayer_global_nonce,
				data: data,
			},
			success: function(result){
				
				var json = jQuery.parseJSON(result);
				var content = jQuery(json['posts']).find('.pagelayer-posts-container').html();
				
				jEle.find('.pagelayer-posts-container').append(content);

				load_btn.find('.pagelayer-loader-holder').hide();

				if (jEle.find('.pagelayer-post-max').attr('data-max') == nextPage) {
					load_btn.text(load_btn.data('text'));
				}else if(autoScroll){
					bEle.show();
				}
				
				bEle.attr('data-current', nextPage);
			},
			complete: function(){
				jEle.removeAttr('pagelayer-post-data-loading');
			}
		});	
		
	});
	
	// If already scrolled
	if(pagelayer_isVisible(loader)){
		loader.click();
	}
	
	// Auto scroll?
	if(autoScroll){
		return;
	}
	
	var win = jQuery(pagelayerGetCurrentWindow());
	
	win.on('scroll.archive_posts', function(){
		var current = parseInt(loader.attr('data-current')) || 1;
		var total = loader.attr('data-max');
		
		if(win.scrollTop() + win.height() < jEle.height() || current >= total) {
			return;
		}
	
		loader.click();
	});
	
}

////////////////
// Freemium End
////////////////