
pagelayer_svg_cache = {};
var pagelayer_document_width;

// For automatic row parent change
jQuery(window).resize(function(){
		
	var new_vw = jQuery(document).width();
	
	if(new_vw == pagelayer_document_width){
		return false;
	}
	
	pagelayer_document_width = new_vw;
	
	// Set a timeout to prevent bubbling
	setTimeout(function(){

		jQuery(pagelayer_editable+' .pagelayer-row-stretch-full').each(function(){
			var par = jQuery(this).parent();
			pagelayer_pl_row_parent_full(par);
		});
	
	}, 200);
	
});

// Render for row
function pagelayer_render_pl_row(el){
	
	var img_urls = !pagelayer_empty(el.tmp['bg_slider-urls']) ? JSON.parse(el.tmp['bg_slider-urls']) : [];
	el.atts['slider'] = '';
	if(!pagelayer_empty(img_urls)){
		for(var x in img_urls){
			el.atts['slider'] += '<div class="pagelayer-bgimg-slide" style="background-image:url('+img_urls[x]+')"></div>'; 
		}
	}
	
	// Row background parallax image.
	if(!pagelayer_empty(el.atts['parallax_img'])){
		el.atts['parallax_img_src'] = el.tmp['parallax_img-'+el.atts['parallax_id_size']+'-url'] || el.tmp['parallax_img-url'];
		el.atts['parallax_img_src'] = el.atts['parallax_img_src'] || el.atts['parallax_img'];
	}
	
	pagelayer_bg_video(el);
}

// Render for inner row
function pagelayer_render_pl_inner_row(el){
	pagelayer_render_pl_row(el);
}

// Render for col
function pagelayer_render_pl_col(el){
	
	var img_urls = !pagelayer_empty(el.tmp['bg_slider-urls']) ? JSON.parse(el.tmp['bg_slider-urls']) : [];
	el.atts['slider'] = '';
	if(!pagelayer_empty(img_urls)){
		for(var x in img_urls){
			el.atts['slider'] += '<div class="pagelayer-bgimg-slide" style="background-image:url('+img_urls[x]+')"></div>'; 
		}
	}
	
	// We need the parent of type pagelayer-wrap-col
	var par = el.$.parent('.pagelayer-wrap-col');
	
	// Apply to wrapper
	if(!pagelayer_empty(el.atts['col'])){
		
		for(var x=1; x<=12; x++){
			if(par.hasClass('pagelayer-col-'+x)){
				par.removeClass('pagelayer-col-'+x);
				break;
			}
		}

		par.addClass('pagelayer-col-'+el.atts['col']);
		par.css('width', '');
	}
	
	if(el.atts['col_width']){
		par.css('width', '');
	}
	
	// Col background parallax image.
	if(!pagelayer_empty(el.atts['parallax_img'])){
		el.atts['parallax_img_src'] = el.tmp['parallax_img-'+el.atts['parallax_id_size']+'-url'] || el.tmp['parallax_img-url'];
		el.atts['parallax_img_src'] = el.atts['parallax_img_src'] || el.atts['parallax_img'];
	}
	
	pagelayer_bg_video(el);
}	
	
function pagelayer_bg_video(el){
	
	el.tmp['bg_video_src-url'] = el.tmp['bg_video_src-url'] || el.atts['bg_video_src'];
	
	var src = el.tmp['bg_video_src-url'];
	
	if(pagelayer_empty(src)){
		return;
	}
	
	var iframe_atts = pagelayer_video_url(src, true);
	// console.log(iframe_atts);
  
	iframe_atts['src'] += (iframe_atts['src'].indexOf('?') == -1 ? '?' : '');
  
	// Adding mute and loop option in row or col	
	if(el.atts['mute'] == "true"){
		iframe_atts['src'] +="&mute=1";
		el.atts['mute'] = " muted ";
	}else{
		iframe_atts['src'] +="&mute=0";
		el.atts['mute'] = "";
	}

	if(el.atts['stop_loop'] != "true"){
		iframe_atts['src'] +="&loop=1";	
		el.atts['stop_loop'] = " loop ";
	}else{
		iframe_atts['src'] +="&loop=0";	
		el.atts['stop_loop'] ="";
	}
  
	if (iframe_atts['type'] == 'youtube') {
		
		var settings = 'data-loop="'+(!pagelayer_empty(el['atts']['stop_loop']) ? 1 : 0)+'" data-mute="'+ (!pagelayer_empty(el['atts']['mute']) ? 1 : 0)+'" data-videoid="'+(iframe_atts['id'].split('&')[0])+'"';
		
		el.atts['vid_src'] =  '<div class = "pagelayer-youtube-video" '+ settings +'></div>';

	} else if (iframe_atts['type'] == 'vimeo') {
		
		el.atts['vid_src'] = '<iframe src="'+iframe_atts['src']+'&background=1&autoplay=1&byline=0&title=0" allowfullscreen="1" webkitallowfullscreen="1" mozallowfullscreen="1" frameborder="0"></iframe>';
		
	}else{
		
		el.atts['vid_src'] = '<video autoplay playsinline '+el.atts['mute']+el.atts['stop_loop']+'>'+
				'<source src="'+iframe_atts['src']+'" type="video/mp4">'+
			'</video>';
			
	}
	
}

// Load the full width row
function pagelayer_render_end_pl_row(el){
		
	// The parent
	var par = el.$.parent();
	
	// Any class with full width
	if(el.$.hasClass('pagelayer-row-stretch-full')){
		
		// Give it the full width
		pagelayer_pl_row_full(el.$);
		
		// Give full width to the parent
		pagelayer_pl_row_parent_full(par);
		
		// Also add that we had a full width
		el.$.addClass('pagelayer-row-stretch-had-full');
	
	// Did this row have full width ?
	}else if(el.$.hasClass('pagelayer-row-stretch-had-full')){
		
		// Remove style
		el.$.removeAttr('style');
		par.removeAttr('style');
		par.children('.pagelayer-ele-overlay').removeAttr('style');
		
		// Remove HAD class
		el.$.removeClass('pagelayer-row-stretch-had-full');
		
	}
	
	pagelayer_pl_row_video(el.$);
	
	el.$.find('.pagelayer-parallax-window img').each(function(){
		pagelayer_pl_row_parallax(jQuery(this));
	});
	
	el.$.find('.pagelayer-bgimg-slider').each(function(){
		pagelayer_pl_row_slider(jQuery(this));
	});
	
	// Row shape
	if('row_shape_type_top' in el.atts){
		pagelayer_render_row_shape(el, 'top')
	}
	
	if('row_shape_type_bottom' in el.atts){
		pagelayer_render_row_shape(el, 'bottom')
	}
}

// Render for inner row
function pagelayer_render_end_pl_inner_row(el){
	pagelayer_render_end_pl_row(el);
}

// Set Row parent width
function pagelayer_pl_row_parent_full(par){
	var vw = jQuery('html').width();
	par.css({'width': vw,'max-width': '100vw'});
	par.offset({left: 0});
	par.children('.pagelayer-row').css({left: 0});
}

// Row shape render
function pagelayer_render_row_shape(el, shape_pos){
		
	var name = el.atts['row_shape_type_'+shape_pos]+'-'+shape_pos+'.svg';

	// DO we have in cache
	if(!(name in pagelayer_svg_cache)){
		// Make url and fetch
		var url = pagelayer_url+'/images/shapes/'+name;
		jQuery.get(url, function(data){
			el.$.find('.pagelayer-svg-'+shape_pos).html(data);
			pagelayer_svg_cache[name] = data;
		}, 'html');
	
	// Fill with cache
	}else{
		el.$.find('.pagelayer-svg-'+shape_pos).html(pagelayer_svg_cache[name]);
	}

}

// Load the col
function pagelayer_render_end_pl_col(el){
	
	pagelayer_pl_row_video(el.$);
	
	el.$.find('.pagelayer-parallax-window img').each(function(){
		pagelayer_pl_row_parallax(jQuery(this));
	});
	
	el.$.find('.pagelayer-bgimg-slider').each(function(){
		pagelayer_pl_row_slider(jQuery(this));
	});
	
}

// Render the image object
function pagelayer_render_pl_image(el){
	
	// Decide the image URL
	el.atts['func_id'] = el.tmp['id-'+el.atts['id-size']+'-url'] || el.tmp['id-url'];
	el.atts['func_id'] = el.atts['func_id'] || el.atts['id'];
	el.atts['pagelayer-srcset'] = el.atts['func_id']+', '+el.atts['func_id']+' 1x, ';
	
	var image_atts = {
		name : 'id',
		size : 'id-size'
	};
	
	pagelayer_get_img_src(el, image_atts);
	
	// What is the link ?
	if('link_type' in el.atts){
		
		// Custom url
		if(el.atts['link_type'] == 'custom_url'){
			el.atts['func_link'] = el.tmp['link'] || '';
		}
		
		// Link to the media file itself
		if(el.atts['link_type'] == 'media_file'){
			el.atts['func_link'] = el.tmp['id-url'] || el.atts['id'];
		}
		
		// Lightbox
		if(el.atts['link_type'] == 'lightbox'){
			el.atts['func_link'] = el.tmp['id-url'] || el.atts['id'];
		}
	}
}

// Incase if there is a lightbox
function pagelayer_render_end_pl_image(el){
	pagelayer_pl_image(el.$);
}

// Pre DragAndDrop function 
function pagelayer_preDAndD_image(jEle){
	
	dropzoneParent = jEle.find('.pagelayer-img').parent();
	
	// Check if drop zone is already there then return
	if(dropzoneParent.find('.pagelayer-image-drop-zone').length > 0){
		return;
	}
	
	var dropDiv = '<div class="pagelayer-image-drop-zone">'+
					'<div>'+
						'<i class="fas fa-upload"></i>'+
						'<h4>'+pagelayer_l('drop_file')+'</h4>'+
						'<div class="pagelayer-img-up-progress">'+
							'<div class="pagelayer-img-up-bar"></div>'+
						'</div>'+
					'</div>'+
				   '</div>';
				   
	dropzoneParent.prepend(dropDiv);		
	
	dropZone = dropzoneParent.find('.pagelayer-image-drop-zone');
	
	// Inserting values in image drag and drop function
	pagelayer_img_dragAndDrop(dropzoneParent, dropZone, jEle, '');	
	
}

// Render for video
function pagelayer_render_pl_video(el){	
	el.atts['video_overlay_image-url'] = el.tmp['video_overlay_image-'+el.atts['custom_size']+'-url'] || el.tmp['video_overlay_image-url'];
	el.atts['video_overlay_image-url'] = el.atts['video_overlay_image-url'] || el.atts['video_overlay_image'];	
	el.tmp['src-url'] = el.tmp['src-url'] || el.atts['src'];
	el.tmp['ele_id'] = el['id'];
	var vid_atts = pagelayer_video_url(el.tmp['src-url'], true);
  
	vid_atts['src'] += (vid_atts['src'].indexOf('?') == -1 ? '?' : '');
  
	vid_atts['src'] += el.atts['autoplay'] == 'true' ? '&autoplay=1' : '&autoplay=0' ;

	var mute = el.atts['mute'] == 'true' ? 1 : 0;
	vid_atts['src'] +='&'+(vid_atts['type'] == 'vimeo' ? 'muted' : 'mute')+'='+mute;

	vid_atts['src'] += el.atts['loop'] == 'true' ? '&loop=1' : '&loop=0' ;
	
	el.atts['vid_src'] = vid_atts['src']+(vid_atts['type'] == 'youtube' ? '&playlist='+vid_atts['id'] : '');
}

// Incase if there is a lightbox
function pagelayer_render_end_pl_video(el){
	pagelayer_pl_video(el.$);
}

// Render the testimonial
function pagelayer_render_pl_testimonial(el){
	
	if(!pagelayer_empty(el.tmp['avatar-no-image-set'])){
		el.atts['avatar'] = '';
		return;
	}
	
	//console.log(el);
	
	// Decide the image URL
	el.atts['func_image'] = el.tmp['avatar-'+el.atts['custom_size']+'-url'] || el.tmp['avatar-url'];
	el.atts['func_image'] = el.atts['func_image'] || el.atts['avatar'];
	
}

// Render the stars
function pagelayer_render_end_pl_stars(el){
	var jEle = el.$.find('.pagelayer-stars-container');
	pagelayer_stars(jEle);
};

// Render the service box
function pagelayer_render_pl_service(el){
	
	// Decide the image URL
	el.atts['func_image'] = el.tmp['service_image-'+el.atts['service_image_size']+'-url'] || el.tmp['service_image-url'];
	el.atts['func_image'] = el.atts['func_image'] || el.atts['service_image'];
	el.atts['pagelayer-srcset'] = el.atts['func_image']+', '+el.atts['func_image']+' 1x, ';
	
	var image_atts = {
		name : 'service_image',
		size : 'service_image_size'
	};
	
	pagelayer_get_img_src(el, image_atts);
	
}

function pagelayer_render_end_pl_service(el){
	// Drag and Drop function for image
	if (typeof pagelayer_preDAndD_image !== "undefined") { 
		pagelayer_preDAndD_image(el.$);
	}
}

function pagelayer_social(jEle,sel){
	var holder = jEle.find(sel);
	var icon = holder.data('icon');
	
	if(pagelayer_empty(icon)){
		return;
	}
	
	//alert(icon);
	var icon_splited = icon.split(' fa-');
	//console.log(icon_splited);
	holder.addClass('pagelayer-'+icon_splited[1]);
	
}

function pagelayer_social_icon_onchange(jEle, row, val){
	
	var url = '';
	
	// Lets get the value of the nearest social icon
	for(var k in pagelayer_social_urls){
		var patt = new RegExp(k, 'i');
		if(patt.test(val)){
			url = pagelayer_social_urls[k];
		}
	}
	
	if(url.length > 0){
		var social_url_row = row.parent().find('[pagelayer-elp-name="social_url"]');
		//console.log(social_url_row);
		social_url_row.find('.pagelayer-elp-link').val(url).trigger('change');
	}
}

// Render the social icon
function pagelayer_render_end_pl_social(el){
	pagelayer_social(el.$, '.pagelayer-icon-holder');
}

// Render the social profile group
function pagelayer_render_end_pl_social_grp(el){
	
	// Removing extra animation classes
	el.$.find('.pagelayer-icon-holder').removeClass (function (index, className) {
		return (className.match (/(^|\s)pagelayer-animation-\S+/g) || []).join(' ');
	});
	
	pagelayer_pl_social_profile(el.$);
}

// Render the counter
function pagelayer_render_end_pl_counter(el){
	pagelayer_counter();
};

// Render the progress
function pagelayer_render_end_pl_progress(el){
	pagelayer_progress();
};
 
// Render the image slider
function pagelayer_render_pl_image_slider(el){
	
	// The URLs
	var img_urls = !pagelayer_empty(el.tmp['ids-urls']) ? JSON.parse(el.tmp['ids-urls']) : [];
	var all_urls = !pagelayer_empty(el.tmp['ids-all-urls']) ? JSON.parse(el.tmp['ids-all-urls']) : [];
	var img_title = !pagelayer_empty(el.tmp['ids-all-titles']) ? JSON.parse(el.tmp['ids-all-titles']) : [];
	//console.log(img_urls);
		
	var ul = '';
	var is_link = 'link_type' in el.atts && !pagelayer_empty(el.atts['link_type']) ? true : false;
	
	// Create figure HTML
	for (var x in img_urls){
		
		// Use the default URL first
		var url = img_urls[x];
		
		// But if we have a custom size, use that
		if(el.atts['size'] != 'custom' && x in all_urls && el.atts['size'] in all_urls[x]){
			url = all_urls[x][el.atts['size']];
		}
		
		ul += '<li class="pagelayer-slider-item">';
		
		if(is_link){
			var link = (el.atts['link_type'] == 'media_file' ? (!pagelayer_empty(img_urls[x]) ? img_urls[x] : url) : (el.tmp['link'] || ''))
			ul += '<a href="'+link+'" class="pagelayer-link-sel">';
		}
		
		ul += '<img class="pagelayer-img" src="'+url+'" title="'+img_title[x]+'" alt="'+img_title[x]+'">';
		
		if(is_link){
			ul += '</a>';
		}
		
		ul += '</li>';
	}
	
	if(pagelayer_empty(ul)){
		ul = '<h4 style="text-align:center;">'+ pagelayer_l('Please select Images from left side Widget properties.')+'</h4>';
	}
	
	el.atts['ul'] = ul;
	
	// Which arrows to show
	if('controls' in el.atts && (el.atts['controls'] == 'arrows' || el.atts['controls'] == 'none')){
		el.CSS.attr.push({'sel': '.pagelayer-image-slider-ul', 'val': 'data-pager="false"'});
	}
	
	if('controls' in el.atts && (el.atts['controls'] == 'pager' || el.atts['controls'] == 'none')){
		el.CSS.attr.push({'sel': '.pagelayer-image-slider-ul', 'val': 'data-controls="false"'});
	}
	
};

// Render the image slider
function pagelayer_render_end_pl_image_slider(el){
	pagelayer_owl_destroy(el.$, '.pagelayer-image-slider-ul');
	pagelayer_pl_image_slider(el.$);
}

// Render the grid gallery
function pagelayer_render_pl_grid_gallery(el){
	// The URLs
	var img_urls = !pagelayer_empty(el.tmp['ids-urls']) ? JSON.parse(el.tmp['ids-urls']) : [];
	var all_urls = !pagelayer_empty(el.tmp['ids-all-urls']) ? JSON.parse(el.tmp['ids-all-urls']) : [];
	var img_title = !pagelayer_empty(el.tmp['ids-all-titles']) ? JSON.parse(el.tmp['ids-all-titles']) : [];
	var img_links = !pagelayer_empty(el.tmp['ids-all-links']) ? JSON.parse(el.tmp['ids-all-links']) : [];
	var img_captions = !pagelayer_empty(el.tmp['ids-all-captions']) ? JSON.parse(el.tmp['ids-all-captions']) : [];
	//console.log(img_urls);
		
	var ul = '';
	var	pagin = '<li class="pagelayer-grid-page-item active">1</li>';
	var is_link = 'link_to' in el.atts && !pagelayer_empty(el.atts['link_to']) ? true : false;
	
	var i = 0;
	var j = 1;
	if(pagelayer_empty(el.tmp)){
		ul = '<h4 style="text-align:center;">'+ pagelayer_l('select_images')+'</h4>';
		el.atts['ul'] = ul;
		el.atts['pagin'] = '';
		return;
	}
  
	ul += '<ul class="pagelayer-grid-gallery-ul">';
	var gallery_rand = 'gallery-id-'+Math.floor((Math.random() * 100) + 1);
	var imgInPage = el.atts['images_no'];
	
	// Create figure HTML
	for (var x in img_urls){
		
		if(imgInPage != 0 && (i % imgInPage) == 0 && i != 0){
			ul += '</ul><ul class="pagelayer-grid-gallery-ul">';
			j++;
			pagin += '<li class="pagelayer-grid-page-item">'+j+'</li>';			
		}
		
		// Use the default URL first
		var url = img_urls[x];
		
		// But if we have a custom size, use that
		if(el.atts['size'] != 'custom' && x in all_urls && el.atts['size'] in all_urls[x]){
			url = all_urls[x][el.atts['size']];
		}

		
		ul += '<li class="pagelayer-gallery-item" >';
		
		if(!is_link){
			ul += '<div>';
		}
		
		if(is_link && el.atts['link_to'] == 'media_file'){
			var link = (el.atts['link_to'] == 'media_file' ? url : (el.atts['link'] || ''));
			ul += '<a href="'+link+'" class="pagelayer-ele-link">';
		}
		
		if(is_link && el.atts['link_to'] == 'attachment'){
			var link = img_links[x];
			ul += '<a href="'+link+'" class="pagelayer-ele-link">';
		}
		
		if(is_link && el.atts['link_to'] == 'lightbox'){			
			ul += '<a href="'+img_urls[x]+'" class="pagelayer-ele-link" data-lightbox-gallery="'+gallery_rand+'" alt="'+img_title[x]+'" pagelayer-grid-gallery-type="'+el.atts['link_to']+'">';
		}
		
		ul += '<img class="pagelayer-img" src="'+url+'" title="'+img_title[x]+'" alt="'+img_title[x]+'">';
		
		if(el.atts['caption'] == 'true'){
			ul += '<span class="pagelayer-grid-gallery-caption">'+img_captions[x]+'</span>';
		}
		
		if(is_link){
			ul += '</a>';
		}
		
		if(!is_link){
			ul += '</div>';
		}
		
		ul += '</li>';
		i++;
	}
	ul += '</ul>';
	
	el.atts['pagin'] = (j > 1) ? '<div class="pagelayer-grid-gallery-pagination"><ul class="pagelayer-grid-page-ul">'+'<li class="pagelayer-grid-page-item">&laquo;</li>'+
						pagin+
						'<li class="pagelayer-grid-page-item">&raquo;</li>'+'</ul></div>' : '';
	
	el.tmp['gallery-random-id'] = gallery_rand;
	
	el.atts['ul'] = ul;

}

function pagelayer_render_end_pl_grid_gallery(el){
	pagelayer_pl_grid_lightbox(el.$);
}

// Render for tabs
function pagelayer_render_html_pl_tabs(el){
	el.CSS.attr.push({'sel': '{{element}}', 'val': 'pagelayer-tabs-rotate="'+el.atts["rotate"]+'"'});
};

// Render the tab item
function pagelayer_render_end_pl_tabs(el){
	pagelayer_pl_tabs(el.$);
}

// Render the accordion item
function pagelayer_render_end_pl_accordion(el){
	pagelayer_pl_accordion(el.$);
};

// Render the collapse item
function pagelayer_render_end_pl_collapse(el){
	pagelayer_pl_collapse(el.$);	
};

// Render the accordion item handler
pagelayer_add_action('pagelayer_element_setup', function(e, jEle){
	var tag = pagelayer_tag(jEle);
	
	if( tag != 'pl_accordion_item'){
		return;
	}
	
	var panel = jEle.find('.pagelayer-accordion-panel');
	
	pagelayer_add_inner_row_notice(panel);
});

// Render the accordion item handler
pagelayer_add_action('pagelayer_do_dirty', function(e, jEle){
	var panel = jEle.closest('.pagelayer-accordion-panel');
	
	pagelayer_add_inner_row_notice(panel);
});

// Add inner row notice
function pagelayer_add_inner_row_notice(panel){
	
	if(panel.length < 1 || panel.children('.pagelayer-ele-wrap, .pagelayer-ele').not('.pagelayer-row-not-found').length > 0){
		
		if(panel.children('.pagelayer-row-not-found').length > 0){
			panel.children('.pagelayer-row-not-found').remove();
		}
		
		return;
	}
	
	var div = `<div class="pagelayer-row-not-found pagelayer-ele-wrap"> There is no editable area found. Please <span class="pagelayer-click-add-row">click here</span> to Add a Row and continue editing!<div>`;
	
	panel.html(div);
	
	panel.find('.pagelayer-click-add-row').on('click', function(){
		panel.find('.pagelayer-row-not-found').remove();

		// Create Row		
		var row = jQuery('<div pagelayer-tag="pl_inner_row"></div>');
		panel.append(row);
		var row_id = pagelayer_onadd(row, false);
		var rEle = pagelayer_ele_by_id(row_id);
		
		// Create Column
		var col = jQuery('<div pagelayer-tag="pl_col"></div>');
		rEle.find('.pagelayer-row-holder').append(col);
		var col_id = pagelayer_onadd(col, false);
		var cEle = pagelayer_ele_by_id(col_id);
		cEle.click();
		
	});
	
}

// Shortcode Handler
var pagelayer_shortcodes_timer;
function pagelayer_render_pl_shortcodes(el){
			
	// Clear any previous timeout
	clearTimeout(pagelayer_shortcodes_timer);
	
	// Set a timer for constant change
	pagelayer_shortcodes_timer = setTimeout(function(){
		
		// Make the call
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_do_shortcodes',
			type: 'POST',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				shortcode_data: el.atts['data']
			},
			success:function(data) {
				el.$.find('.pagelayer-shortcodes-container').html(data);
			}
		});
	
	}, 500);
	
};

// Render the widget area i.e. Sidebars
function pagelayer_render_pl_wp_widgets(el){
			
	// Clear any previous timeout
	clearTimeout(pagelayer_shortcodes_timer);
	
	// Set a timer for constant change
	pagelayer_shortcodes_timer = setTimeout(function(){
	
		// Make the call
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_fetch_sidebar',
			type: 'POST',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				sidebar: el.atts['sidebar']
			},
			success:function(data) {
				el.$.find('.pagelayer-wp-sidebar-holder').html(data);
			}
		});
	
	}, 500);

};

function pagelayer_owl_destroy(jEle, slides_class){
	
	var ul = jEle.find(slides_class);
	var setup = jEle.attr('pagelayer-setup');
	
	// Already setup ?
	if(setup && setup.length > 0){
		if(ul.children('.pagelayer-ele-wrap')){
			ul.pagelayerOwlCarousel('destroy');
			ul.find('[class^="pagelayer-owl-"]').remove();
			jEle.removeAttr('pagelayer-setup');
		}
	}
}


// Render the google maps v3
function pagelayer_render_pl_google_maps(el){
	
	el.atts['show_v2'] = true;
    
	if(pagelayer_empty(el.atts['api_version'])){		
		el.atts['src_code'] = '';
		return;
	}
	
	el.atts['show_v2'] = false;
	
	var gmaps_key = (pagelayer_empty(pagelayer_gmaps_key) ? '' : pagelayer_gmaps_key);
	
	var api_key = (pagelayer_empty(el.atts['api_key']) ? gmaps_key : el.atts['api_key']);
	
	if(el.atts['map_modes'] == 'view'){
		el.atts['center'] = pagelayer_empty(el.atts['center']) ? '-33.8569,151.2152' : el.atts['center'];
	}
		
	var src_code = (pagelayer_empty(el.atts['center']) ? '' : '&center='+el.atts['center'])+(el.atts['map_modes'] == 'streetview' ? '' : '&maptype='+el.atts['map_type']+'&zoom='+el.atts['zoom']);
	
	switch(el.atts['map_modes']){
		case 'place':
			src_code += encodeURI('&q='+(pagelayer_empty(el.atts['address']) ? 'New York, New York, USA' : el.atts['address'] ));
			break;
			
		case 'directions':
			src_code += encodeURI('&origin='+(pagelayer_empty(el.atts['direction_origin']) ? 'Oslow Norway' : el.atts['direction_origin'] ));
			src_code += encodeURI('&destination='+(pagelayer_empty(el.atts['direction_destination']) ? 'Telemark Norway' : el.atts['direction_destination'] ));
			src_code += (pagelayer_empty(el.atts['direction_waypoints']) ? '' : '&waypoints='+(el.atts['direction_waypoints'].trim()).split(' ').join('|') );
			src_code += (pagelayer_empty(el.atts['direction_modes']) ? '' : '&mode='+el.atts['direction_modes'] );
			src_code += (pagelayer_empty(el.atts['direction_avoid']) ? '' : '&avoid='+el.atts['direction_avoid'].split(',').join('|') );
			src_code += (pagelayer_empty(el.atts['direction_units']) ? '' : '&units='+el.atts['direction_units'] );
			break;
			
		case 'streetview':
			src_code += '&pano='+(pagelayer_empty(el.atts['streetview_pano']) ? 'eTnPNGoy4bxR9LpjjfFuOw' : el.atts['streetview_pano'] );
			src_code += '&location='+(pagelayer_empty(el.atts['streetview_location']) ? '46.414382,10.013988' : el.atts['streetview_location'] );
			src_code += (pagelayer_empty(el.atts['streetview_heading']) ? '' : '&heading='+el.atts['streetview_heading'] );
			src_code += (pagelayer_empty(el.atts['streetview_pitch']) ? '' : '&pitch='+el.atts['streetview_pitch'] );
			src_code += (pagelayer_empty(el.atts['streetview_fov']) ? '' : '&fov='+el.atts['streetview_fov'] );
			break;
			
		case 'search':
			src_code += encodeURI('&q='+(pagelayer_empty(el.atts['search_term']) ? 'Record stores in Seattle' : el.atts['search_term'] ));
			break;
			
	}
	
	var src_code_url = 'https://www.google.com/maps/embed/v1/'+el.atts['map_modes']+'?key='+api_key+src_code;
	
	el.atts['src_code'] = '<iframe width="600" height="450" style="border:0" loading="lazy" allowfullscreen src="'+src_code_url+'"></iframe>';
	
}

////////////
// Freemium
////////////

// Render the excerpt
function pagelayer_render_html_pl_post_excerpt(el){
	el.$.find('.pagelayer-post-excerpt').addClass('pagelayer-empty-widget');
}

// Render the featured image
function pagelayer_render_html_pl_featured_img(el){
	
	var param = {};
	
	param['pagelayer_nonce'] = pagelayer_ajax_nonce;
	
	// Post Id
	param['post_id'] = pagelayer_postID;
	
	// Image size
	if('size' in el.atts){
		param['size'] = el.atts['size'];
	}
	
	jQuery.ajax({
		url: pagelayer_ajax_url+'action=pagelayer_fetch_featured_img',
		type: 'post',
		data: param,
		dataType: 'json',
		success: function(data){
			
			var src = '';
			var title = '';
			var alt = '';
			if(pagelayer_empty(data)){
				src = el.tmp['img-'+el.atts['size']+'-url'] || el.tmp['img-url'];
				src = src || el.atts['img'];
			}else{
				src = data['url'];
				alt = data['alt'];
				title = data['title'];
				if(el.atts['size']+'-url' in data){
					src = data[el.atts['size']+'-url'];
				}
			}
			
			var img_html = '<img class="pagelayer-img" src="'+pagelayer.blank_img+'" />';
			if(src){
				img_html = '<img class="pagelayer-img" src="' + src + '" title="' + title + '" alt="' + alt + '"/>';
			}
			
			el.$.find('.pagelayer-featured-img').html(img_html);
			
			if('link_type' in el.atts){
		
				// Custom url
				if(el.atts['link_type'] == 'custom_url'){
					el.$.find('a').attr('href', el.tmp['link']);
				}
				
				// Link to the media file itself
				if(el.atts['link_type'] == 'media_file' || el.atts['link_type'] == 'lightbox'){
					el.$.find('a').attr('href', src);
				}
			}
			
			pagelayer_pl_image(el.$);
		}
	});
}

// Retina image setting attribute.
function pagelayer_get_img_src(el, image_atts){
	
	// Check if retina images is set
	if(!pagelayer_empty(el.tmp[image_atts.name+'-retina-url']) && el.tmp[image_atts.name+'-retina-url'].includes('default-image') == false){
		var retina_image = el.tmp[image_atts.name+'-retina-'+el.atts[image_atts.size]+'-url'];
		retina_image = pagelayer_empty(retina_image) ? el.tmp[image_atts.name+'-retina-url'] : retina_image;
		el.atts['pagelayer-srcset'] += retina_image +' 2x, ';			
	}
	
	// Check if retina mobile images is set
	if(!pagelayer_empty(el.tmp[image_atts.name+'-retina-mobile-url']) && el.tmp[image_atts.name+'-retina-mobile-url'].includes('default-image') == false){			
		var retina_image_mobile = el.tmp[image_atts.name+'-retina-mobile-'+el.atts[image_atts.size]+'-url'];
		retina_image_mobile = pagelayer_empty(retina_image_mobile) ? el.tmp[image_atts.name+'-retina-mobile-url'] : retina_image_mobile;		
		el.atts['pagelayer-srcset'] += retina_image_mobile +' 3x';
	}
}

/////////////////
// Freemium
/////////////////

// If you want to store ajax data then you can use this variable
var pagelayer_ajax_data = {};

var pagelayer_posts_data = {};

// Compare two objects
function pagelayer_compare_object(obj1, obj2){
   var objectsAreSame = true;
   for(var propertyName in obj1){
	  if(obj1[propertyName] !== obj2[propertyName]){
		 objectsAreSame = false;
		 break;
	  }
   }
   for(var propertyName in obj2){
	  if(obj1[propertyName] !== obj2[propertyName]){
		 objectsAreSame = false;
		 break;
	  }
   }
   return objectsAreSame;
}

// Incase if there is a lightbox
function pagelayer_render_end_pl_featured_img(el){
	pagelayer_pl_image(el.$);
}

// Render the archive Posts
function pagelayer_render_pl_archive_posts(el){
	
	// Need to do empty
	el.atts['pagelayer_pagination_top'] = '';
	el.atts['pagelayer_pagination_bottom'] = '';
	
}

// Render the archive Posts
function pagelayer_render_end_pl_archive_posts(el){
	var post = {};
	
	// All atts
	post['atts'] = JSON.parse(JSON.stringify(el.atts));
	post['atts']['pagelayer-id'] = el['id'];
	
	// The nonce
	post['pagelayer_nonce'] = pagelayer_ajax_nonce;
	
	var data_handle = function(data){
		//console.log(data);
		var d = jQuery(data);
		el.$.children(':not(style)').remove();
		var child = el.$.append(d.children(':not(style)'));
		pagelayer_ajax_data[el['id']] = data;
	}
	
	if(pagelayer_empty(pagelayer_posts_data) || !pagelayer_compare_object(pagelayer_posts_data, post) || pagelayer_empty(pagelayer_ajax_data[el['id']])){
		
		pagelayer_posts_data = post;
	
		jQuery.ajax({
			url: pagelayer_ajax_url+'action=pagelayer_archive_posts_data',
			type: 'post',
			data: post,
			success: data_handle
		});
	}else{
		data_handle(pagelayer_ajax_data[el['id']]);
	}
}

function pagelayer_apply_megamenu_items(html, menuID, menuEle, eleActive){
	
	if(pagelayer_empty(pagelayer_menus_items_list[menuID])){
		return html;
	}
	
	var menu_data = jQuery('<div>').html(html);	
	var $elements = pagelayer_menus_items_list[menuID];
	var unset_ele = function(navItem){
		var src = jQuery(navItem);
		var nhtml = src[0].outerHTML;
		
		var nEle = jQuery(nhtml);		
		nEle.removeAttr('pagelayer-parent');
		nEle.find('[pagelayer-parent]').removeAttr('pagelayer-parent');
		nEle.find('style').remove();
		nEle.find('.pagelayer-ele-overlay').remove();
		
		// Unwrap the wraps
		nEle.find('.pagelayer-ele').each(function (){
			var ele = jQuery(this);
			if(ele.parent().is('.pagelayer-ele-wrap')){
				ele.unwrap();
			}
		});
		
		return nEle;
	}
	
	for($e in $elements){
		var savedHTML = '';
		
		if(pagelayer_empty($elements[$e]['pagelayer_content'])){
			 continue;
		}
		
		var mID = $elements[$e]['ID'];
		var navItem = menuEle.find('.pagelayer-mega-editor-'+mID).find('.pagelayer-nav_menu_item').first();
		var id = pagelayer_id(menuEle);

		if(navItem.length > 0 && eleActive){		
			savedHTML = unset_ele(navItem);
		}else{
			savedHTML = pagelayer_element_unsetup($elements[$e]['pagelayer_content']);
		}
		
		menu_data.find('.pagelayer-mega-editor-'+mID).html(savedHTML[0].outerHTML);
	}
		
	return menu_data.html();
}

var pagelayer_nav = {};
var pagelayer_wp_menu_timer;
var pagelayer_nav_force_refresh = {};

// Render the Primary menu
function pagelayer_render_pl_wp_menu(el){
	
	var jEle = el.$;
	var menuID = el.atts['nav_list'];
	var parMenu = jEle.parent().closest('.pagelayer-wp_menu');
	var inside_mega = '';
	var menu_error = '';
	
	// If we are inside primary menu and have a same menu ID
	if(parMenu.length > 0){
		
		var parMenuID = pagelayer_get_att(parMenu, 'nav_list');
		
		if(menuID == parMenuID){
			pagelayer_show_msg('Not allowed same Menu inside the Primary menu widget!', 'warning');
			inside_mega = true;
		
		// If parent menu menuID is empty then we prevent menu inside menu
		}else if(pagelayer_empty(menuID) || pagelayer_empty(parMenuID) ){
			menu_error = 'Primary Menu Holder. Please select the correct menu or parent menu.';
			inside_mega = true;
		}
    
	}
	
	// Set atts for easy rendering in PHP
	pagelayer_set_atts(jEle, 'inside_mega', inside_mega);
	
	if(!pagelayer_empty(inside_mega)){
		
		if(pagelayer_empty(menu_error)){
			menu_error = 'Primary Menu Holder. Please select the correct menu.';
		}
		
		el.atts['nav_menu'] = menu_error;
		return;
	}
	
	// Setting default toggle icon. If the icon is empty.
	if(pagelayer_empty(el.atts['menu_toggle_icon'])){
		el.atts['menu_toggle_icon'] = 'fas fa-bars';
	}
	
	// To avoid remove pagelayer id of mega menu item
	var eleActive = !pagelayer_empty(pagelayer_active.el) && (el.id == pagelayer_active.el.id || jEle.find(pagelayer_active.el.$).length > 0);
	
	if(pagelayer_empty(pagelayer_nav[menuID]) || !pagelayer_empty(pagelayer_nav_force_refresh[el.id])){
		
		var pagelayer_nav_items_list = pagelayer_get_nav_items(jEle);
		var findPar = jEle.find('.pagelayer-wp_menu-ul').parent();
		el.atts['nav_menu'] = '';
		
		// Get menu container for the hold place of the menu
		if(findPar.length > 0){
			el.atts['nav_menu'] = findPar[0].outerHTML;
		}
		
		// Clear any previous timeout
		clearTimeout(pagelayer_wp_menu_timer);
		
		// Set a timer for constant change
		pagelayer_wp_menu_timer = setTimeout(function(){
			
			jQuery.ajax({
				url: pagelayer_ajax_url+'&action=pagelayer_fetch_primary_menu&postID='+pagelayer_postID, // Send post id to on live mode
				type: 'post',
				data: {
					pagelayer_nonce: pagelayer_ajax_nonce,
					nav_list: menuID,
					pagelayer_nav_items: pagelayer_nav_items_list,
					'pagelayer-live': 1
				},
				success: function(data) {
					//console.log(data);
					data = pagelayer_apply_megamenu_items(data, menuID, jEle, eleActive);
					pagelayer_nav[menuID] = data;
				},
				complete: function() {
					//console.log(data);
					
					// Is element html rendered ?
					var findCont = setInterval( function(){
						
						var container = jEle.find('.pagelayer-wp-menu-container');
						
						if(container.length < 1){
							return;
						}
						
						clearInterval(findCont);

						// Replace the menu HTML
						container.find('.pagelayer-wp_menu-ul').parent().remove();
						container.append(pagelayer_nav[menuID]);
						
						var render_ref = pagelayer_render_menu_par;
						pagelayer_render_menu_par = false;
						
						container.find('.pagelayer-ele').each(function(){
							var iEle = jQuery(this);
							
							if(iEle.parent('.pagelayer-ele-wrap').length > 0){
								return;
							}
							
							var id = pagelayer_assign_id(iEle);
							pagelayer_element_setup('[pagelayer-id="'+id+'"]', true);
						});
						
						pagelayer_render_menu_par = render_ref;
						
						pagelayer_primary_menu(jEle);
												
					}, 100);
					
				}
			});
		}, 500);
		
	}else{
		el.atts['nav_menu'] = pagelayer_apply_megamenu_items(pagelayer_nav[menuID], menuID, jEle, eleActive);
	}
	
	pagelayer_nav_force_refresh[el.id] = false;
}

// Render end the Primary menu
function pagelayer_render_end_pl_wp_menu(el){
	
	var jEle = el.$;
	var render_ref = pagelayer_render_menu_par;
	
	pagelayer_render_menu_par = false;
	
	// Re-setup the element
	jEle.find('.pagelayer-ele').each(function(){
		var ele = jQuery(this);
		
		if(ele.parent('.pagelayer-ele-wrap').length > 0){
			return;
		}
		
		var id = pagelayer_assign_id(ele);
		
		pagelayer_element_setup('[pagelayer-id="'+id+'"]', true);
	});
	
	pagelayer_render_menu_par = render_ref;
	
	pagelayer_primary_menu(el.$);
}

// The Primary menu handler on live
pagelayer_add_action('pagelayer_primary_menu_setup_end', function(e, jEle){
	jEle.find('li.pagelayer-mega-menu-item a > .after-icon').unbind('click');
	jEle.unbind('click.mega_menu');
	jEle.on('click.mega_menu', 'li.pagelayer-mega-menu-item', function(e){
							
		var target = jQuery(e.target);
		var mEle = jQuery(this);
		
		if(target.closest('.pagelayer-mega-menu').length > 0 || target.closest(mEle).length < 1){
			return;
		}
		
		jQuery('.pagelayer-active-mega-menu').each(function(){
			var oEle = jQuery(this);
			
			if(mEle.is(oEle)){
				return;
			}
			
			oEle.removeClass('pagelayer-active-mega-menu');
		});
		
		mEle.toggleClass('pagelayer-active-mega-menu');
	});
});

var pagelayer_render_menu = {};
var pagelayer_render_menu_par = true;

// Render end the Primary menu
function pagelayer_render_end_pl_nav_menu_item(el){
	
	if(pagelayer_empty(pagelayer_menus_items_ref[el.atts['ID']])){
		return;
	}
	
	var jEle = el.$;
	
	if(pagelayer_render_menu_par){
		
		// Render parent
		clearTimeout(pagelayer_render_menu);
		pagelayer_render_menu = setTimeout(function(){
			var par = jEle.closest('.pagelayer-wp_menu');
			var plID = pagelayer_id(par);
			
			if(par.length < 1) return;
			
			pagelayer_nav_force_refresh[plID] = true;

			pagelayer_render_menu_par = false;
			pagelayer_sc_render(par);
			pagelayer_render_menu_par = true;
		}, 500);
	}
}

var pagelayer_nav_menu_timmer = {}

// On nav dirty handler
pagelayer_add_action('pagelayer_do_dirty', function(e, jEle){		
		
	var navEle = jEle.closest('[pagelayer-tag="pl_nav_menu_item"]');
	
	if(navEle.length < 1){
		return;
	}
	
	var itemData = pagelayer_data(navEle);
	var atts = itemData.atts;
	var itemID = atts['ID'];
	
	if( !(itemID in pagelayer_menus_items_ref) ){
		pagelayer_menus_items_ref[itemID] = {};
	}
	
	var props = pagelayer_get_props(navEle);
	var menuEle = jEle.closest('.pagelayer-wp_menu');
	var plID = pagelayer_id(menuEle);
	
	for(var prop in props['settings']){
		for(var section in props[prop]){
			
			if(section in atts){
				pagelayer_menus_items_ref[itemID][section] = atts[section];
				continue;
			}
			
			pagelayer_menus_items_ref[itemID][section] = '';
		}
	}
   
	pagelayer_menus_items_ref[itemID]['pagelayer_content'] = navEle;
	pagelayer_menus_items_ref[itemID]['is_dirty'] = true;
	
	var currentID = pagelayer_get_att(menuEle, 'nav_list');
	
	// Prevent unnecessary render
	jQuery(pagelayer_editable).find('.pagelayer-wp_menu').each(function(){
		var mEle = jQuery(this);
		var mID = pagelayer_get_att(mEle, 'nav_list');
		
		if(currentID != mID){
			return;
		}
		
		mEle.attr('pagelayer-click-render', 1);
	});

	menuEle.removeAttr('pagelayer-click-render');
	
});

// We need to render the original content before we can start editing
pagelayer_add_action('pagelayer_element_clicked', function(e, jEle){
	
	var menus = jQuery(pagelayer_editable).find('.pagelayer-wp_menu');
	
	// Prevent unnecessary render
	if(!jEle.hasClass('pagelayer-wp_menu') || menus.length < 2 || pagelayer_empty(jEle.attr('pagelayer-click-render'))){
		return;
	}

	jEle.find('[pagelayer-tag="pl_nav_menu_item"]').each(function(){
		var cEle = jQuery(this),
		postID = pagelayer_get_att(cEle, 'ID');		

		if(!(postID in pagelayer_menus_items_ref)){
			return;
		}
		
		var ref_data = pagelayer_menus_items_ref[postID];
		
		if(!('is_dirty' in ref_data) || pagelayer_empty(ref_data['is_dirty'])){
			return;
		}
		
		pagelayer_sc_render(jEle);
		
		return false; // Break the loop

	});
	
});

// Render the post navigation
function pagelayer_render_end_pl_post_nav(el){

	jQuery.ajax({
		url: pagelayer_ajax_url+'&action=pagelayer_post_nav&postID='+pagelayer_postID,
		type: 'post',
		data: {
			pagelayer_nonce: pagelayer_ajax_nonce,
			data: el['atts'],
		},
		async:false,
		success: function(response){
			//console.log(response);
			var obj = jQuery.parseJSON(response);
			el.$.find('.pagelayer-prev-post').html(obj['atts']['prev_link']);
			el.$.find('.pagelayer-next-post').html(obj['atts']['next_link']);
		}
	});
	
}

// Render the site title
function pagelayer_render_pl_wp_title(el){	
	//console.log(el.tmp);

	// Use default logo
	if(pagelayer_empty(el.atts['logo_img_type'])){
		
		// But is there a default logo
		if(!pagelayer_empty(pagelayer_site_logo)){		
			el.atts['func_image'] = pagelayer_site_logo[el.atts['logo_img_size']+'-url'] || pagelayer_site_logo['url'];
			
			el.atts['logo_img-title'] = pagelayer_empty(pagelayer_site_logo.title) ? '' : pagelayer_site_logo.title;
			el.atts['logo_img-alt'] = pagelayer_empty(pagelayer_site_logo.alt) ? '' : pagelayer_site_logo.alt;
		}
	
	// Custom logo
	}else{
		el.atts['func_image'] = el.tmp['logo_img-'+el.atts['logo_img_size']+'-url'] || el.tmp['logo_img-url'];
		el.atts['func_image'] = pagelayer_empty(el.atts['func_image']) ? el.atts['logo_img'] : el.atts['func_image'];
	}
}

// Render the Post comment
function pagelayer_render_end_pl_post_comment(el){
	
	var postID = pagelayer_postID;
		
	if(el['atts']['post_type'] == 'custom' && el['atts']['post_id']){
		postID = el['atts']['post_id'];
	}
	
	jQuery.ajax({
		url: pagelayer_ajax_url+'&action=pagelayer_post_comment&postID='+postID,
		type: 'post',
		data: {
			pagelayer_nonce: pagelayer_ajax_nonce,
		},
		success: function(response){
			el.$.find('.pagelayer-post-comment-container').html(response);
		}
	});

}

var pagelayer_post_info_timer = {};

// Render the Post info list
function pagelayer_render_pl_post_info_list(el){
	
	el.atts['post_info_content'] = 1;
	
	// Clear any previous timeout
	clearTimeout(pagelayer_post_info_timer[el.id]);
	
	// Set a timer for constant change
	pagelayer_post_info_timer[el.id] = setTimeout(function(){
	
		// Make the call
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_post_info&postID='+pagelayer_postID,
			type: 'post',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				el: el.atts,
			},
			success: function(response){
				var obj = jQuery.parseJSON(response);
				//console.log(obj);el['atts'] = obj;
				
				if( pagelayer_empty(obj['post_info_content']) ){
					el.$.find('.pagelayer-post-info-list-container').remove();
					return;
				}
				
				el.$.find('.pagelayer-post-info-list-container').show();
				el.$.find('.pagelayer-post-info-label').html(obj['post_info_content']);
				el.$.find('.pagelayer-post-info-icon img').attr('src', obj['avatar_url']);
				el.$.find('.pagelayer-post-info-list-container > a').attr('href', obj['link']);
			}
		});
		
	}, 500);
	
}

// Render the Post info list
function pagelayer_render_html_pl_post_info_list(el){
	el.$.find('.pagelayer-post-info-list-container').hide();
}

// Render the contact form
function pagelayer_render_pl_contact(el){
	
	// Set post id in atts 
	el.atts['con_post_id'] = pagelayer_postID;
	el.atts['grecaptcha'] = pagelayer_recaptch_site_key;	
}

// Render the contact form
function pagelayer_render_end_pl_contact(el){
	
	jQuery(el.$).find('.pagelayer-recaptcha').each(function(){
		var recaptcha = jQuery(this);
		var widgetID = recaptcha.attr('recaptcha-widget-id');
		
		if( !pagelayer_empty(window.grecaptcha) && (!pagelayer_empty(widgetID) || widgetID == 0) ){
			grecaptcha.reset(widgetID);
		}else{
			pagelayer_recaptcha_loader(recaptcha, true);
		}
	});
	
	// Showing contact form message in the editor only.
	if(el.atts['show_msg_box']){
		var msgBox = el.$.find('.pagelayer-message-box');
		if(el.$.find('.pagelayer-message-box').length==2){
			msgBox.eq(0).text('Demo success box');
			msgBox.eq(0).addClass('pagelayer-cf-msg-suc');
			msgBox.eq(1).text('Demo failed box');
			msgBox.eq(1).addClass('pagelayer-cf-msg-err');
		}
	}
	
	pagelayer_set_atts(el.$, 'con_post_id', pagelayer_postID);	
	
}

function pagelayer_render_end_pl_heading(el) {
	pagelayer_search_widgets(el.$);
}

function pagelayer_render_end_pl_text(el) {
	pagelayer_search_widgets(el.$);
}

// Render the contact form
function pagelayer_render_pl_contact_item(el){ 
	var html = '';
	var options = '';
	var placeholder = '';
	var required = '';
	
	if(!pagelayer_empty(el.atts['required'])){
		required = 'required';
	}

	if(!pagelayer_empty(el.atts['label_name']) && pagelayer_empty(el.atts['label_as_holder'])){
		html = '<label for="'+el.atts['field_name']+'">'+
				'<span class="pagelayer-form-label">'+el.atts['label_name']+'</span>';
				
		if(!pagelayer_empty(required)){
			html += ' *';
		}
		
		html += '</label>';
	}
		
	if(!pagelayer_empty(el.atts['label_as_holder'])){
		placeholder = el.atts['label_name'];
	}else{
		if(!pagelayer_empty(el.atts['placeholder'])) placeholder = el.atts['placeholder'];
	}
	
	// File accept
	var file_accept = '.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.odt,.avi,.ogg,.m4a,.mov,.mp3,.mp4,.mpg,.wav,.wmv';
	
	if(!pagelayer_empty(el.atts['accept_file'])){
		file_accept = el.atts['accept_file'];
	}
	
	if(el.atts['field_type'] == 'select'){
	html += '<select name="'+el.atts['field_name']+'" '+required+'>'
		if(!pagelayer_empty(el.atts['label_name']) && !pagelayer_empty(el.atts['label_as_holder'])){
		   html += '<option value="" disabled selected>'+el.atts['label_name']+'</option>';
		}else{
			html += '<option value="" disabled selected>---</option>';
		}
		
		if(!pagelayer_empty(el.atts['values'])){
			options = el.atts['values'].split("\n");
			for(var x in options){
				html += '<option value="'+options[x].trim()+'">'+options[x].trim()+'</option>';
			}
		}
		html += '</select>';
	}else if(el.atts['field_type'] == 'checkbox'){
		if(!pagelayer_empty(el.atts['values'])){
			options = el.atts['values'].split("\n");
			html += '<div class="pagelayer-radcheck-holder pagelayer-contact-checkbox" '+required+'>';
			for(var x in options){
				html += '<div><input type="checkbox" id="'+el.id+options[x].trim()+'" name="'+el.atts['field_name']+'[]" '+
				'value="'+options[x].trim()+'" /><label for="'+el.id+options[x].trim()+'" class="pagelayer-form-label">'+options[x].trim()+'</label></div>';
			}
			html += '</div>';
		}
	}else if(el.atts['field_type'] == 'radio'){
		if(!pagelayer_empty(el.atts['values'])){
			options = el.atts['values'].split("\n");
			html += '<div class="pagelayer-radcheck-holder">';
			for(var x in options){
				html += '<div><input type="radio" name="'+el.atts['field_name']+'" '+
				'value="'+options[x].trim()+'" '+required+'/><span>'+options[x].trim()+'</span></div>';
			}
			html += '</div>';
		}
	}else if(el.atts['field_type'] == 'textarea'){
		html += '<textarea name="'+el.atts['field_name']+'" rows="'+el.atts['textarea_rows']+'" placeholder="'+placeholder+'" '+
				''+required+'></textarea>';
	}else if(el.atts['field_type'] == 'file'){
		html += '<input type="'+el.atts['field_type']+'" '+
				'name="'+el.atts['field_name']+'" placeholder="'+placeholder+'" accept="'+file_accept+'" '+required+' />';
	}else if(el.atts['field_type'] == 'label'){
		html += '';
	}else{
		html += '<input type="'+el.atts['field_type']+'" '+
				'name="'+el.atts['field_name']+'" placeholder="'+placeholder+'" '+required+'/>';
	}
	
	el.atts['fieldhtml'] = html;
	
}

// Render the post content
function pagelayer_render_pl_post_content(el){
	el.atts['post_content'] = 'Post Content Holder';
	el.CSS.css.push({'sel': '{{element}} .entry-content', 'val': 'min-height:20px;background-color:#e3e3e3;'});
}

// Render the post excertp
function pagelayer_render_pl_post_excerpt(el){
	el.tmp['post_excerpt'] = '<div class="pagelayer-post-excerpt pagelayer-empty-widget"></div>';
}

// Render the flipbox
function pagelayer_render_pl_flipbox(el){
	var jEle = el.$;
	el.atts['func_image'] = el.tmp['heading_image-'+el.atts['heading_image_size']+'-url'] || el.tmp['heading_image-url'];
	el.atts['func_image'] = el.atts['func_image'] || el.atts['heading_image'];
	
	var back = pagelayer_get_att(jEle, 'back_section');
	if(back){
		jEle.attr('back_section', back);
	}else{
		jEle.removeAttr('back_section', back);
	}
}

// Render the Testimonial Slider
function pagelayer_render_end_pl_testimonial_slider(el){
	pagelayer_owl_destroy(el.$, '.pagelayer-testimonials-holder');
	pagelayer_pl_testimonial_slider(el.$);
}

// Render the countdown
function pagelayer_render_pl_countdown(el){
	if(pagelayer_empty(el.atts['custom_label_text'])){
		el.atts['days_label_text'] = 'Days';
		el.atts['hours_label_text'] = 'Hours';
		el.atts['minutes_label_text'] = 'Minutes';
		el.atts['seconds_label_text'] = 'Seconds';
	}	
}	

// Render the countdown
function pagelayer_render_end_pl_countdown(el){ 
	var jEle = el.$;
	var exp = pagelayer_get_att(jEle, 'display_expired_text');
	if(exp){
		jEle.attr('display_expired_text', exp);
	}else{
		jEle.removeAttr('display_expired_text', exp);
	}
	
	pagelayer_countdown(jEle);
	
	if(pagelayer_empty(el['atts']['days']) && pagelayer_empty(el['atts']['hours']) && pagelayer_empty(el['atts']['minutes']) && pagelayer_empty(el['atts']['seconds']) ){
		jEle.find('.pagelayer-countdown-counter').html('<h2>Countdown Timer Holder</h2>');
	}
  
}

// Render the share
function pagelayer_render_pl_share(el){
	
	if('text' in el.atts){
		el.atts['icon_label'] = el.atts['text'];
		return;
	}
		
	var icon = '';
	
	if(!pagelayer_empty(el.atts['icon'])){
		var icon_splited = el.atts['icon'].split(' fa-');
		icon = icon_splited[1];
	}
	
	var labelList = { 'Facebook' : ['facebook', 'facebook-official', 'facebook-f', 'facebook-messenger', 'facebook-square'],
		'Twitter' : ['twitter', 'twitter-square'],
		'Google+' : ['google-plus', 'google-plus-square', 'google-plus-g'],
		'Instagram' : ['instagram'],
		'Linkedin' : ['linkedin', 'linkedin-square', 'linkedin-in'],
		'Pinterest' : ['pinterest', 'pinterest-p', 'pinterest-square'],
		'Reddit' : ['reddit-alien', 'reddit-square', 'reddit'],
		'Skype' : ['skype'],
		'Stumbleupon' : ['stumbleupon', 'stumbleupon-circle'],
		'Telegram' : ['telegram', 'telegram-plane'],
		'Tumblr' : ['tumblr', 'tumblr-square'],
		'VK' : ['vk'],
		'Weibo' : ['weibo'],
		'WhatsApp' : ['whatsapp', 'whatsapp-square'],
		'WordPress' : ['wordpress', 'wordpress-simple'],
		'Xing' : ['xing', 'xing-square'],
		'Delicious' : ['delicious'],
		'Dribbble' : ['dribbble', 'dribbble-square'],
		'Snapchat' : ['snapchat-ghost'],
		'Pocket' : ['get-pocket'],
		'Email' : ['envelope', 'envelope-open', 'envelope-o']
	}
	
	jQuery.each(labelList, function(key, value){
		if(jQuery.inArray(icon, value) != -1){
			el.atts['icon_label'] = key;
		}
	});
	
}

// Render the share icon
function pagelayer_render_end_pl_share(el){
	pagelayer_social(el.$, '.pagelayer-share-content');
}

// copyright rendering function
var pagelayer_copyright;
function pagelayer_render_pl_copyright(el){
	if(pagelayer_empty(el.atts['copyright_text'])){
		return;
	}
	pagelayer_copyright = el.atts['copyright_text'];
	
}

// Render the animated heading
function pagelayer_render_pl_anim_heading(el){
	
	el.atts['rotate_html'] = '';
	
	// Creates html for rotating text
	if(!pagelayer_empty(el.atts['rotate_text'])){
		
		var rotate_text = '';
		rotate_text = el.atts['rotate_text'].split(',');
		
		el.atts['rotate_html'] += '<div class="pagelayer-animated-heading pagelayer-rotating-text pagelayer-words-wrapper">';
		
		jQuery.each(rotate_text, function(i){
			el.atts['rotate_html'] += '<span';
			if(i == 0){
				el.atts['rotate_html'] += ' class="pagelayer-is-visible"';
			}
			el.atts['rotate_html'] += '>' + rotate_text[i] + '</span>';
		});
		
		el.atts['rotate_html'] += '</div>';
	   
	}
	
	// Required classes for particular rotate
	el.atts['rotate_req'] = '';
	var letters = ['pagelayer-aheading-rotate2', 'pagelayer-aheading-rotate3', 'type', 'pagelayer-aheading-scale'];
	
	if(jQuery.inArray(el.atts['animations'], letters) != -1){
		el.atts['rotate_req'] = 'letters ';
	}
	
	if(el.atts['animations'] == 'pagelayer-aheading-clip'){
		el.atts['rotate_req'] = 'is-full-width ';
	}
	
}

// Render animated heading
function pagelayer_render_end_pl_anim_heading(el){
	var jEle = el.$;
	pagelayer_anim_heading(jEle);	
}

function pagelayer_render_pl_post_title(el){
	el['atts']['open_html_tag'] = !pagelayer_empty(el['atts']['html_tag']) ? '<'+el['atts']['html_tag']+'>' : '';
	el['atts']['close_html_tag'] = !pagelayer_empty(el['atts']['html_tag']) ? '</'+el['atts']['html_tag']+'>' : '';
}
////////////////
// Freemium End
////////////////
