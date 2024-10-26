
// The active pagelayer element
var pagelayer_active = {};

// List of pagelayer icons
var pagelayer_icons = {};

// The inline editor
var pagelayer_editor = {};

// The active pagelayer element
var pagelayer_active_tab = {};

// The menu items refrence
var pagelayer_menus_items_ref = {};

// Loads the Data
function pagelayer_data(jEle, clean){
	
	var ret = new Object();
	
	// Get the data
	ret.tag = pagelayer_tag(jEle);
	ret.id = pagelayer_id(jEle);
	ret.$ = jEle;
	
	var ref_data = pagelayer_el_data_ref(jEle);
	
	// Parse the attributes
	ret.atts = JSON.parse(JSON.stringify(ref_data['attr']));
	ret.tmp = JSON.parse(JSON.stringify(ref_data['tmp']));
	
	//console.log(ret.atts);
	//console.log(ret.tmp);
	
	clean = clean || false;
	
	// Remove values which have 'req'. NOTE : 'show' ones will be allowed
	if(clean){
		
		var tag = ret.tag;
		
		// Anything to set ?
		ret.set = {};
		
		// Function to clear any att data
		var pagelayer_delete_atts = function(x){
			delete ret.atts[x];
			delete ret.atts[x+'_tablet'];// Any tablet and mobile values as well
			delete ret.atts[x+'_mobile'];
			delete ret.set[x];		
		}
		
		// All props
		var all_props = pagelayer_shortcodes[tag];
		
		// Loop through all props
		for(var i in pagelayer_tabs){
			
			var tab = pagelayer_tabs[i];
			
			section_loop1:
			for(var section in all_props[tab]){
				
				// Any section to skip by post type ?
				if(!pagelayer_empty(all_props['post_type_cats'])){					
					for(var post_type in all_props['post_type_cats']){
						if(pagelayer_post.post_type != post_type && jQuery.inArray(section, all_props['post_type_cats'][post_type]) > -1){
							continue section_loop1;
						}
					}
				}
        
				var props = section in pagelayer_shortcodes[tag] ? pagelayer_shortcodes[tag][section] : pagelayer_styles[section];
				
				// In case of widgets its possible !
				if(pagelayer_empty(props)){
					continue;
				}
				
				for(var x in props){
					
					var prop = props[x];
				
					// Any prop to skip ?
					if(!pagelayer_empty(all_props['skip_props']) && jQuery.inArray(x, all_props['skip_props']) > -1){
						pagelayer_delete_atts(x);
						continue;
					}
					
					// Are we to set this value ?
					if(!(x in ret.atts) && 'default' in prop && !pagelayer_empty(prop['default'])){
				
						// We need to make sure its not a PRO value
						if(!('pro' in prop && pagelayer_empty(pagelayer_pro))){
							
							var tmp_val = prop['default'];
							
							// If there is a unit and there is no unit suffix in atts value
							if('units' in prop){
								if(jQuery.isNumeric(tmp_val)){
									tmp_val = tmp_val+prop['units'][0];
								}else{
									var sep = 'sep' in prop ? prop['sep'] : ',';
									var tmp2 = tmp_val.split(sep);
									for(var k in tmp2){
										if(jQuery.isNumeric(tmp2[k])){
											tmp2[k] = tmp2[k]+prop['units'][0];
										}
									}
									tmp_val = tmp2.join(sep);
								}
							}
							
							//console.log(x+' - '+tmp_val);
							ret.set[x] = tmp_val;
							
						}
					}
					
					if(!('req' in prop)){
						continue;
					}
					
					//console.log('[pagelayer_data] Cleaning :'+x);
					
					// List of considerations
					var show = prop['req'];
					
					// We will hide by default
					var toShow = true;
					
					for(var showParam in show){
						var reqval = show[showParam];
						var except = showParam.substr(0, 1) == '!' ? true : false;
						showParam = except ? showParam.substr(1) : showParam;
						var val = ret.atts[showParam] || '';
						
						//console.log('Show '+x+' '+showParam+' '+reqval+' '+val);
						
						// Is the value not the same, then we can show
						if(except){
							
							if(typeof reqval == 'string' && reqval == val){
								toShow = false;
								break;
							}
							
							// Its an array and a value is found, then dont show
							if(typeof reqval != 'string' && reqval.indexOf(val) > -1){
								toShow = false;
								break;
							}
							
						// The value must be equal
						}else{
							
							 if(typeof reqval == 'string' && reqval != val){
								toShow = false;
								break;
							 }
							
							// Its an array and no value is found, then dont show
							if(typeof reqval != 'string' && reqval.indexOf(val) === -1){
								toShow = false;
								break;
							}
						}
						
					}
					
					// Are we to show ?
					if(!toShow){
						//console.log('Delete : '+x);
						pagelayer_delete_atts(x);
					}
				}
			}
		}
		
	}
	
	return ret;
	
};

// Setup the properties
function pagelayer_elpd_setup(){

	// The Dialag box of the element properties
	// pagelayer-ELPD - Element Properties Dialog
	pagelayer_elpd_html = '<div class="pagelayer-elpd-tabs">'+
			'<div class="pagelayer-elpd-tab" pagelayer-elpd-tab="settings" pagelayer-elpd-active-tab=1>Settings</div>'+
			//'<div class="pagelayer-elpd-tab" pagelayer-elpd-tab="styles">Style</div>'+
			'<div class="pagelayer-elpd-tab" pagelayer-elpd-tab="options">Options</div>'+
			'<div class="pagelayer-advanced-props pagelayer-elpd-tab pagelayer-hidden" pagelayer-elpd-tab="advanced">Advanced</div>'+
			'<div class="pagelayer-elpd-options">'+
				'<i class="pli pli-clone" ></i>'+
				'<i class="pli pli-trashcan" ></i>'+
			'</div>'+
		'</div>'+
		'<div class="pagelayer-elpd-body"></div>'+
		'<div class="pagelayer-elpd-holder"></div>';
	
	// Create the dialog box
	pagelayer.$$('#pagelayer-elpd').append(pagelayer_elpd_html);
	pagelayer_elpd = pagelayer.$$('#pagelayer-elpd');
	
	pagelayer.$$('.pagelayer-elpd-close').on('click', function(){
		pagelayer_leftbar_tab('pagelayer-shortcodes');
		pagelayer.$$('[pagelayer-widget-tab="widgets"]').click();
		pagelayer.$$('.pagelayer-elpd-header').hide();
		pagelayer.$$('.pagelayer-logo').show();
		pagelayer.$$('.pagelayer-elpd-body').removeAttr('pagelayer-element-id').empty();
		pagelayer_active = {};
	});
	
	// Copy
	pagelayer.$$('.pagelayer-elpd-options>.pli-clone').on('click', function(){
		pagelayer_copy_element(pagelayer_active.el.$);
	});
	
	// Delete
	pagelayer.$$('.pagelayer-elpd-options>.pli-trashcan').on('click', function(){
		pagelayer_delete_element(pagelayer_active.el.$);
		//pagelayer.$$('.pagelayer-elpd-close').click();
	});
	
	// The advanced props
	pagelayer_elpd.find('.pagelayer-advanced-props').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();
		var propsModal = pagelayer.$$('.pagelayer-props-modal');
		if(propsModal.find('.pagelayer-meta-iframe').length < 1){
			propsModal.find('.pagelayer-props-wrap').append('<iframe class="pagelayer-meta-iframe" src="'+ pagelayer_post_props +'" style="display:none"></iframe>');
			propsModal.find('.pagelayer-meta-iframe').load(function(){
				propsModal.find('.pagelayer-props-loading-screen').hide();				
				propsModal.find('.pagelayer-props-modal-close').css('visibility','visible');
				jQuery(this).show();
			});
		}
		
		propsModal.show();
		pagelayer.$$('.pagelayer-meta-iframe').contents().find('.pagelayer-tab-items[data-tab="post_props"]').click();
	});
		
	// The tabs
	pagelayer_elpd.find('.pagelayer-elpd-tab').on('click', function(){

		var jEle = jQuery(this);
		var attr = 'pagelayer-elpd-active-tab';
		var tab = jEle.attr('pagelayer-elpd-tab');
		
		if(tab == 'advanced'){
			return;
		}
		
		pagelayer_elpd.find('.pagelayer-elpd-tab').each(function(){
			jQuery(this).removeAttr(attr);
		});
		
		jEle.attr(attr, 1);
		
		// Trigger the showing of rows
		pagelayer_elpd_show_rows();
	});
	
};

// Open the properties
function pagelayer_elpd_open(jEle){
	
	// Set pagelayer history FALSE
	pagelayer.history_action = false;
	
	// Set the position of the element and show
	//pagelayer_elpd.css('left', pagelayer_elpd_pos[0]);
	//pagelayer_elpd.css('top', pagelayer_elpd_pos[1]);
	pagelayer_leftbar_tab('pagelayer-elpd');
	pagelayer.$$('[pagelayer-elpd-tab=settings]').show();
	pagelayer.$$('.pagelayer-elpd-header').show();
	pagelayer.$$('.pagelayer-logo').hide();
	
	// The property holder
	var holder = pagelayer.$$('.pagelayer-elpd-body');
	holder.html(' ');
	
	var el = pagelayer_elpd_generate(jEle, holder);
	
	// Set the active element
	pagelayer_active.el = el;
	
	// Set the header
	pagelayer.$$('.pagelayer-elpd-title').html('Edit '+pagelayer_shortcodes[el.tag]['name']);
	
	// Set pagelayer history TRUE
	pagelayer.history_action = true;
	
	// Render tooltips for the ELPD
	pagelayer_tooltip_setup();
	
};

// Show the properties window
function pagelayer_elpd_generate(jEle, holder){
	
	// Get the id, tag, atts, data, etc
	var el = pagelayer_data(jEle);
	//console.log(el);
	
	// Is it a valid type ?
	if(pagelayer_empty(pagelayer_shortcodes[el.tag])){
		pagelayer_error('Could not find this shortcode : '+el.tag);
		return;
	}
	
	// Set the holder
	holder.attr('pagelayer-element-id', el.id);
	//console.log(el.id);
	
	var all_props = pagelayer_shortcodes[el.tag];
	
	var sec_open_class = 'pagelayer-elpd-section-open';
	
	for(var i in pagelayer_tabs){
		var tab = pagelayer_tabs[i];
		var section_close = false;// First section always open
		
		section_loop2:
		for(var section in all_props[tab]){
			//console.log(tab+' '+section);
				
			// Any section to skip by post type ?
			if(!pagelayer_empty(all_props['post_type_cats'])){					
				for(var post_type in all_props['post_type_cats']){
					if(pagelayer_post.post_type != post_type && jQuery.inArray(section, all_props['post_type_cats'][post_type]) > -1){
						continue section_loop2;
					}
				}
			}
				
			var props = section in pagelayer_shortcodes[el.tag] ? pagelayer_shortcodes[el.tag][section] : pagelayer_styles[section];
			//console.log(props);
			
			var sec = jQuery('<div class="pagelayer-elpd-section" section="'+section+'" pagelayer-show-tab="'+tab+'">'+
					'<div class="pagelayer-elpd-section-name '+sec_open_class+'"><i class="pli"></i>'+all_props[tab][section]+'</div>'+
					'<div class="pagelayer-elpd-section-rows"></div>'+
					'</div>');
			holder.append(sec);
			
			// The row holder
			sec = sec.find('.pagelayer-elpd-section-rows');
			
			// Close all except the first section
			if(section_close){
				sec.hide().prev().removeClass(sec_open_class);
			}
			section_close = true;
			
			if('widget' in all_props && section == 'params'){
				pagelayer_elpd_widget_settings(el, sec, true);
				continue;
			}
			
			var mode = pagelayer_get_screen_mode();
	
			// Reset / Create the cache
			for(var x in props){
				
				props[x]['c'] = new Object();
				props[x]['c']['val'] = '';// Blank Val		
				props[x]['c']['name'] = x;// Add the Name of the row i.e. attribute of the element
				var prop_name = x;
				
				// Do we have screen ?
				if('screen' in props[x] && mode != 'desktop'){
					prop_name = x +'_'+mode;
				}
				
				// Set default to value of attribute if any
				if(prop_name in el.atts){
					props[x]['c']['val'] = el.atts[prop_name];
				}
				
				// Set element
				props[x]['el'] = el;
				
				// Any prop to skip ?
				if(!pagelayer_empty(all_props['skip_props']) && jQuery.inArray(x, all_props['skip_props']) > -1){
					continue;
				}
		
				// Add the row
				pagelayer_elpd_row(sec, tab, section, props, x);
				
			}
			
			// Hide empty sections
			if(sec.html().length < 1){
				//console.log(section+' - '+sec.html().length);
				sec.parent().remove();
			}
		}
	}
	
	/*// Set the default values in the PROPERTIES
	var fn_load = window['pagelayer_load_elp_'+el.tag];
	
	if(typeof fn_load == 'function'){
		fn_load(el, props);
	}*/
	
	// Hide clone and delete options
	if(!pagelayer_empty(all_props['hide_active']) && (pagelayer_empty(pagelayer_active.el) || pagelayer_active.el.id == el.id)){
		pagelayer.$$('.pagelayer-elpd-options').addClass('pagelayer-hidden');
	}else{
		pagelayer.$$('.pagelayer-elpd-options').removeClass('pagelayer-hidden');
	}
	
	// Add Advanced settings options for the props
	if(el.tag == 'pl_post_props'){
		pagelayer.$$('.pagelayer-elpd-tab[pagelayer-elpd-tab="advanced"]').removeClass('pagelayer-hidden');
	}else{
		pagelayer.$$('.pagelayer-elpd-tab[pagelayer-elpd-tab="advanced"]').addClass('pagelayer-hidden');
	}
	
	// Section open close
	holder.find('>.pagelayer-elpd-section>.pagelayer-elpd-section-name').on('click', function(){
		var _sec = jQuery(this);
		var par = _sec.parent();
		
		pagelayer_active_tab.id = el.id;
		pagelayer_active_tab.section = par.attr('section');
		
		// Get the active tab
		var active_tab = pagelayer_elpd.find('[pagelayer-elpd-active-tab]').attr('pagelayer-elpd-tab');
		
		// Close all but dont touch yourself
		holder.children().each(function (){
			var curSec = jQuery(this);
			if(par.is(curSec)) return;// Skip the current option
			if(curSec.attr('pagelayer-show-tab') != active_tab) return;// Skip the non active tabs as is
			curSec.find('.pagelayer-elpd-section-rows').hide().prev().removeClass(sec_open_class);
		});
		
		// Now toggle your self
		par.find('.pagelayer-elpd-section-rows').toggle();
		
		if(_sec.next().is(':visible')){
			_sec.addClass(sec_open_class);
		}else{
			_sec.removeClass(sec_open_class);
		}
		
	});
	
	if(!pagelayer_empty(pagelayer_active_tab) && pagelayer_active_tab.id == el.id){
		holder.find('>[section='+pagelayer_active_tab.section+']>.pagelayer-elpd-section-name').click();
	}
	
	// Handle the showing of rows
	pagelayer_elpd_show_rows();
	
	return el;
	
};

// Show a row
function pagelayer_elpd_row(holder, tab, section, props, name){

	// The Prop
	var prop = props[name];
	//console.log(tab+' '+name+' '+prop.el.tag);
	
	var fn = window['pagelayer_elp_'+prop['type']];
	
	if(typeof fn == 'function'){
		
		var row = jQuery('<div class="pagelayer-form-item" pagelayer-elp-name="'+name+'" />');
		
		// Append the row
		holder.append(row);
		
		return pagelayer_elpd_render_row(row, prop);
		
	}
	
};

// Render a row
function pagelayer_elpd_render_row(row, prop){
	
	var fn = window['pagelayer_elp_'+prop['type']];
		
	if('group' in prop){
		row.attr('pagelayer-access-item', prop.group);
	}
	
	var fn_ui = window['pagelayer_elp_'+prop['type']+'_ui'];
	
	// Is there a UI Handler ?
	if(typeof fn_ui == 'function'){
		
		fn_ui(row, prop);
		
	// Use the default mechanism
	}else{
			
		// The label
		pagelayer_elp_label(row, prop);
		
		// The main property
		fn(row, prop);
		
		// Showing default button or not
		if(pagelayer_properties_filter(prop['type']) && pagelayer_empty(row.find('.pagelayer-pro-req').length)){
			pagelayer_show_default_button(row, prop, prop.c['val']);		
		}
		
		// Is there a description ?
		if(!pagelayer_empty(prop['desc'])){
			pagelayer_elp_desc(row, prop['desc']);
		}
		
	}
	
	if('script' in prop){
		row.append('<script>'+prop.script+'</script>');
	}
	
	return row;
}

// Show the rows as per the active tab and also handle the rows that are supposed to be shown or not
function pagelayer_elpd_show_rows(){
	
	//console.log('Called');
	
	// Get the active tab
	var active_tab = pagelayer_elpd.find('[pagelayer-elpd-active-tab]').attr('pagelayer-elpd-tab');
	
	pagelayer_elpd.find('[pagelayer-show-tab]').each(function(){
		var sec = jQuery(this);
		
		// Is it the active tab ? 
		if(sec.attr('pagelayer-show-tab') != active_tab){
			sec.hide();
		}else{
			sec.show();
		}
	});
	
	// Find all Elements in the Property dialog and loop
	pagelayer_elpd.find('[pagelayer-element-id]').each(function(){
		
		var holder = jQuery(this);
		var id = holder.attr('pagelayer-element-id');
		var jEle = pagelayer_ele_by_id(id);
		var tag = pagelayer_tag(jEle);
		//console.log('Main : '+id+' - '+tag);
		//console.log(pagelayer_active);
		
		// All props
		var all_props = pagelayer_shortcodes[tag];
		
		// Loop through all props
		for(var i in pagelayer_tabs){
			
			var tab = pagelayer_tabs[i];

			for(var section in all_props[tab]){
				
				var props = section in pagelayer_shortcodes[tag] ? pagelayer_shortcodes[tag][section] : pagelayer_styles[section];
				
				for(var x in props){
					
					var prop = props[x];
					
					// If the prop is a group, we continue
					if(prop['type'] == 'group'){
						continue;
					}
					
					// Find the row
					var row = false;
					
					holder.find('[pagelayer-elp-name="'+x+'"]').each(function(){
						var j = jQuery(this);
						var _id = j.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
						//console.log(_id+' = '+id);
						
						// Is the parent the same ?
						if(_id == id){
							row = j;
						}
					});
					
					// Do you have a show or hide ?
					if(!row){
						//console.log('Not Found : '+x+' - '+id);
						continue;
					}
					
					// Is the row visible ?
					if(row.closest('[pagelayer-show-tab]').attr('pagelayer-show-tab') != active_tab){
						row.hide();
						continue;
					}
					
					// Now lets show or hide the element
					if(!('req' in prop || 'show' in prop)){
						row.show();
						continue;
					}
					
					// List of considerations
					var show = {};
					
					// We have both req and show, so lets just combine the values and then show
					// NOTE : We need to make an array and not just merge the 2 as they are references
					if('req' in prop && 'show' in prop){
						
						// Add the req values
						show = JSON.parse(JSON.stringify(prop['req']));
						
						// Now the show values need to be looped
						for(var t in prop['show']){
							show[t] = prop['show'][t];
						}
						
					}else{
						show = 'req' in prop ? prop['req'] : prop['show'];
					}
					
					// We will hide by default
					var toShow = true;
					
					for(var showParam in show){
						var reqval = show[showParam];
						var except = showParam.substr(0, 1) == '!' ? true : false;
						showParam = except ? showParam.substr(1) : showParam;
						var val = pagelayer_get_att(jEle, showParam) || '';
						
						//console.log('Show '+x+' '+showParam+' '+reqval+' '+val);
						
						// Is the value not the same, then we can show
						if(except){
							
							if(typeof reqval == 'string' && reqval == val){
								toShow = false;
								break;
							}
							
							// Its an array and a value is found, then dont show
							if(typeof reqval != 'string' && reqval.indexOf(val) > -1){
								toShow = false;
								break;
							}
							
						// The value must be equal
						}else{
							
							 if(typeof reqval == 'string' && reqval != val){
								toShow = false;
								break;
							 }
							
							// Its an array and no value is found, then dont show
							if(typeof reqval != 'string' && reqval.indexOf(val) === -1){
								toShow = false;
								break;
							}
						}
					}
					
					// Are we to show ?
					if(toShow){
						row.show();
					}else{
						row.hide();
					}
					
				}
				
			}
		}
	
	});
	
}; 

var pagelayer_widget_timer;
var pagelayer_widget_cache = {};

// Load the widget settings
function pagelayer_elpd_widget_settings(el, sec, onfocus){
	
	var show_form = function(html){
				
		sec.html('<form class="pagelayer-widgets-form">'+html+'</form>');
		
		// Handle on form data change
		sec.find('form :input').on('change', function(){					
			//console.log('Changed !');
			
			// Clear any previous timeout
			clearTimeout(pagelayer_widget_timer);
			
			// Set a timer for constant change
			pagelayer_widget_timer = setTimeout(function(){ 
				pagelayer_elpd_widget_settings(el, sec);
				//console.log('Calling');
			}, 500);
			
		});
	}
	
	// Is it onfocus ?
	onfocus = onfocus || false;
	
	// Its an onfocus
	if(onfocus && el.id in pagelayer_widget_cache){
		show_form(pagelayer_widget_cache[el.id]);
		return true;
	}
	
	var post = {};
	post['action'] = 'pagelayer_wp_widget';
	post['pagelayer_nonce']	= pagelayer_ajax_nonce;
	post['tag'] = el.tag;
	post['pagelayer-id'] = el.id;
	
	// Any atts ?
	if('widget_data' in el.atts){
		post['widget_data'] = el.atts['widget_data'];
	}
	
	// Post any existing data
	var form = sec.find('form');
  // Archive widget checkbox fix
	var inputCheckbox = form.find('input[type=checkbox]');
	for(var i=0; i<inputCheckbox.length; i++){
		if(inputCheckbox[i].value == 'on'){
			form.find('input[type=checkbox]')[i].value = 1;
		}
	}
	
	if(form.length > 0){
		//console.log(form.serialize());
		post['values'] = form.serialize();
	}
	
	jQuery.ajax({
		url: pagelayer_ajax_url,
		type: 'post',
		data: post,
		success: function(data) {
			//console.log('Widget Data');console.log(data);
			
			// Show the form
			if('form' in data){
				show_form(data['form']);
				
				// Store in cache
				pagelayer_widget_cache[el.id] = data['form'];
			}
			
			// Show the content
			if('html' in data){
				el.$.html(data['html']);
				pagelayer_sc_render(el.$);// Re-Render the CSS
			}
			
			// Any set attributes ?
			if('widget_data' in data){
				pagelayer_set_atts(el.$, 'widget_data', JSON.stringify(data['widget_data']));
			}
			
		},
		fail: function(data) {
			pagelayer_show_msg('Some error occured in getting the widget data', 'error');						
		}
	});
	
}

// Will set the attribute and also render
function _pagelayer_set_atts(row, val, no_default){
	var id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
	var jEle = jQuery('[pagelayer-id='+id+']');
	var tag = pagelayer_tag(jEle);
	var prop_name = row.attr('pagelayer-elp-name');	
	var prop = pagelayer.props_ref[tag][prop_name];
	
	// Is there a unit ?
	var uEle = row.find('.pagelayer-elp-units');
	if(uEle.length > 0 && !pagelayer_empty(val)){
		var unit = uEle.find('[selected]').html();
		if(Array.isArray(val)){
			for(var i in val){
				if(val[i].length < 1){
					continue;
				}
				val[i] = val[i]+unit;
			}
		}else{
			val = val+unit;
		}
	}
	
	// Are we in another mode ?
	var mode = ('screen' in prop && pagelayer_get_screen_mode() != 'desktop') ? '_'+pagelayer_get_screen_mode() : '';
	
	pagelayer_set_atts(jEle, prop_name+mode, val);
	
	// Are we to skip setting defaults ?
	no_default = no_default || false;
	if(!no_default){
		
		// We need to set defaults for dependents
		var hasSet = pagelayer_set_default_atts(jEle, 5);
		
		// We need to reopen the left panel
		// Note : If two simultaneous calls are made, then this will cause problems
		// Also after this is called, ROW is destroyed and no other row related stuff will work i.e. set_atts in the same calls will fail
		if(hasSet){
			pagelayer_elpd_open(jEle);
		}
	}
	//console.trace();console.log('Setting Attr');
	
	// Render
	pagelayer_sc_render(jEle);
  
	// Show default button or not
	if(pagelayer_properties_filter(prop) && pagelayer_empty(row.find('.pagelayer-pro-req').length)){
			pagelayer_show_default_button(row, prop, val);      
	}
	
	if('onchange' in prop){
		var fn = window[prop['onchange']];
		if(typeof fn === 'function'){
			fn(jEle, row, val);
		}
	}
};

// Will set the attribute but not render
function _pagelayer_set_tmp_atts(row, suffix, val){
	var id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
	var jEle = jQuery('[pagelayer-id='+id+']');
	pagelayer_set_tmp_atts(jEle, row.attr('pagelayer-elp-name')+(suffix.length > 0 ? '-'+suffix : ''), val);
};

// Will clear the attribute but not render
function _pagelayer_clear_tmp_atts(row){
	var id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
	var jEle = jQuery('[pagelayer-id='+id+']');
	pagelayer_clear_tmp_atts(jEle, row.attr('pagelayer-elp-name'));
};

// Get the attribute of images only
function _pagelayer_img_tmp_atts(row){
	var id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
	var jEle = jQuery('[pagelayer-id='+id+']');
	return pagelayer_img_tmp_atts(jEle, row.attr('pagelayer-elp-name'));
};

// Get the tmp att
function _pagelayer_get_tmp_att(row, suffix){
	var id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
	var jEle = jQuery('[pagelayer-id='+id+']');
	return pagelayer_get_tmp_att(jEle, row.attr('pagelayer-elp-name')+'-'+suffix);
};

// Create the Label
function pagelayer_elp_label(row, prop){
	row.append('<div class="pagelayer-elp-label-div" type="'+prop['type']+'"><label class="pagelayer-elp-label">'+prop['label']+'</label></div>');
	
	var label = row.children('.pagelayer-elp-label-div');
	
	// Do we have screen ?
	if('screen' in prop){
		var mode = pagelayer_get_screen_mode();
		var screen = '<div class="pagelayer-elp-screen">'+
			'<i class="pli pli-desktop" ></i>'+
			'<i class="pli pli-tablet" ></i>'+
			'<i class="pli pli-mobile" ></i>'+
			'<i class="pagelayer-prop-screen pli pli-'+mode+'" ></i>'+
		'</div>';
		label.append(screen);
		
		// Set screen mode on change
		label.find('.pli:not(.pagelayer-prop-screen)').on('click', function(){
			var mode = 'desktop';
			var jEle = jQuery(this);
			
			// Tablet ?
			if(jEle.hasClass('pli-tablet')){
				mode = 'tablet';
			}
			
			// Mobile ?
			if(jEle.hasClass('pli-mobile')){
				mode = 'mobile';
			}
			
			pagelayer_set_screen_mode(mode);
			label.find('.pagelayer-elp-screen .pli').removeClass('open');
			
		});
		
		// On change of screen handle the values
		label.find('.pagelayer-elp-screen').on('pagelayer-screen-changed', function(e){
			
			label.find('.pagelayer-elp-screen .pli').removeClass('open');
			var mode = pagelayer_get_screen_mode();
			var modes = {desktop: '', tablet: '_tablet', mobile: '_mobile'};
			
			// Get the current current new val
			prop.c['val'] = pagelayer_get_att(prop.el.$, prop.c['name']+modes[mode]);
			
			// Handle the amount
			if(pagelayer_empty(prop.c['val'])){
				prop.c['val'] = '';
			}
			
			// Remove the siblings
			label.siblings().each(function(){
				var j = jQuery(this);
				
				if(j.hasClass('pagelayer-elp-desc')){
					return;
				}
				
				j.remove();
			});
			
			// Create the vals again
			var fn = window['pagelayer_elp_'+prop['type']];
			
			// The main property
			fn(row, prop);
			
		});
		
		label.find('.pagelayer-elp-screen .pagelayer-prop-screen').on('click', function(e){
			jQuery(this).siblings().toggleClass('open');
		})
		
	}
	
	// Do we have pro version requirement ?
	if('pro' in prop && pagelayer_empty(pagelayer_pro)){
		var txt = prop['pro'].length > 1 ? prop['pro'] : pagelayer.pro_txt;
		var pro = jQuery('<div class="pagelayer-pro-req">Pro</div>');
		pro.attr('data-tlite', txt);
		label.append(pro);
	}
	
	// Do we have units ?
	if('units' in prop){
		
		var units = '';	
		var tmp_val = prop.c['val'];
		var default_unit = 0;
		
		// Get unit from value
		if(!(pagelayer_empty(tmp_val))){
			
			for(var i in prop['units']){
				if(pagelayer_is_string(tmp_val) && tmp_val.search(prop['units'][i]) != -1){
					default_unit = i;
				}else if(tmp_val[0].search(prop['units'][i]) != -1 ){
					default_unit = i;
				}
			}
		}
		
		for(var i in prop['units']){
			units += '<span '+(i == default_unit ? 'selected' : '')+'>'+prop['units'][i]+'</span>';
		}
		
		label.append('<div class="pagelayer-elp-units">'+units+'</div>');
		
		// Set unit on change
		label.find('.pagelayer-elp-units span').on('click', function(){
			
			label.find('.pagelayer-elp-units span').each(function(){
				jQuery(this).removeAttr('selected');
			});
			
			jQuery(this).attr('selected', 1);
			
		});
		
	}
	
	// Include default button
	if(pagelayer_properties_filter(prop['type']) && pagelayer_empty(row.find('.pagelayer-pro-req').length)){
		
		var defaultButton = '<span class="pagelayer-elp-default" title="'+pagelayer_l('back_to_default')+'" ><i class="fas fa-undo"></i></span>';		
		label.append(defaultButton);
		
		label.find('.pagelayer-elp-default').on('click', function(){

			prop.c['val'] = ('default' in prop) ? prop.default : '';
			_pagelayer_set_atts(row, prop.c['val']);			
			
			jQuery(this).attr('data_show',false);
      
			// Empty the row
			row.html('');
			
			// Re-render the row
			pagelayer_elpd_render_row(row, prop);
			
		});	
	}
};

// Create the Description
function pagelayer_elp_desc(row, label){
	row.append('<div class="pagelayer-elp-desc">'+label+'</div>');
};

// The Text property
function pagelayer_elp_text(row, prop){
	
	var div = '<div class="pagelayer-elp-text-div">'+
				'<input type="text" class="pagelayer-elp-text" name="'+prop.c['name']+'" value="'+pagelayer_htmlEntities(prop.c['val'])+'"></input>'+
			'</div>';
	
	row.append(div);
	
	row.find('input').on('input', function(){
		_pagelayer_set_atts(row, jQuery(this).val());// Save and Render
	});
	
};

// The Select property
function pagelayer_elp_select(row, prop){
	
	var options = '';
	var option = function(val, lang){
		var selected = (val != prop.c['val']) ? '' : 'selected="selected"';
		return '<option class="pagelayer-elp-select-option" value="'+val+'" '+selected+'>'+lang+'</option>';
	}
	
	for (x in prop['list']){
		
		// Single item
		if(typeof prop['list'][x] == 'string'){
			options += option(x, prop['list'][x]);
		
		// Groups
		}else{
			options += '<optgroup label="'+x+'">';
			
			for(var y in prop['list'][x]){
				options += option(y, prop['list'][x][y]);
			}
			
			options += '</optgroup>';
		}
	}
	
	var div = '<div class="pagelayer-elp-select-div pagelayer-elp-pos-rel">'+
				'<select class="pagelayer-elp-select pagelayer-select" name="'+prop.c['name']+'">'+options+'</select>'+
  '</div>';
			
	row.append(div);
	
	row.find('select').on('change', function(){
		
		var sEle = jQuery(this);
		
		if(sEle.attr('name') == "animation"){
			_pagelayer_trigger_anim(row, sEle.val());
		}
		
		_pagelayer_set_atts(row, sEle.val());// Save and Render		
	
	});
	
}

// The MultiSelect property
function pagelayer_elp_multiselect(row, prop){
	
	var selection = [];
	if(!pagelayer_empty(prop.c['val'])){
		//selection = JSON.parse(prop.c['val']);
		selection = prop.c['val'].split(',');
	}
	
	var options = '';
	var option = function(val, lang){
		var selected = (jQuery.inArray(val,selection) == -1 ? '' : 'selected="selected"');
		return '<li class="pagelayer-elp-multiselect-option" data-val="'+val+'" '+selected+'>'+lang+'</li>';
	}
	
	var show_sel = function(val){
		var sel_html = '';
		jQuery.each(val, function(index, value){
			sel_html += '<span class="pagelayer-elp-multiselect-selected" data-val="'+value+'">'+prop['list'][value]+' <span class="pagelayer-elp-multiselect-remove">x</span></span>';
		});
		return sel_html;
	}
	
	var setup_remove = function(){
		row.find('.pagelayer-elp-multiselect-remove').on('click', function(){
			var sVal = jQuery(this).parent().attr('data-val');
			row.find('.pagelayer-elp-multiselect-option[data-val='+sVal+']').click();
		});
	}
	
	for (x in prop['list']){
		options += option(x, prop['list'][x]);
	}
	
	var div = '<div class="pagelayer-elp-multiselect-div pagelayer-elp-pos-rel">'+
				'<div class="pagelayer-elp-multiselect">'+show_sel(selection)+'</div>'+
				'<ul class="pagelayer-elp-multiselect-ul" name="'+prop.c['name']+'">'+options+'</ul>'+
			'</div>';
  
	row.append(div);
	setup_remove();
	
	row.find('.pagelayer-elp-multiselect-option').on('click', function(){
		
		var sVal = jQuery(this).attr('data-val');
		
		if(jQuery.inArray(sVal,selection) == -1){
			selection.push(sVal);
			row.find('[data-val="'+sVal+'"]').attr('selected','selected');
		}else{
			selection.splice(jQuery.inArray(sVal,selection),1);
			row.find('[data-val="'+sVal+'"]').removeAttr('selected');
		}
		
		//_pagelayer_set_atts(row,JSON.stringify(selection));// Save and Render
		_pagelayer_set_atts(row, selection.join(','));// Save and Render
		
		row.find('.pagelayer-elp-multiselect').html(show_sel(selection));		
		setup_remove();
		
	});
	
	// Open the selector
	row.find('.pagelayer-elp-multiselect').on('click', function(){
		row.find('.pagelayer-elp-multiselect-ul').slideToggle(100);
	});
	
}

function _pagelayer_trigger_anim(row, anim){
	var id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id');
	var classList = jQuery('[pagelayer-id='+id+']').attr('class');
	classList = classList.split(/\s+/);
	//console.log(classList);
	var options = [];
	row.find('option').each(function(){
		var found = jQuery.inArray( jQuery(this).val(), classList );
		if( found != -1){
			//var found = jQuery(this).val();
			jQuery('[pagelayer-id='+id+']').removeClass(jQuery(this).val());
			//break;
		}
		//options.push(jQuery(this).val());
	});
	jQuery('[pagelayer-id='+id+']').removeClass('pagelayer-wow').addClass(anim + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		jQuery(this).removeClass(anim+ ' animated');
	});
}

// The Checkbox property
function pagelayer_elp_checkbox(row, prop){
	
	var div = '<div class="pagelayer-elp-checkbox-div">'+
				'<input type="checkbox" name="'+prop.c['name']+'" class="pagelayer-elp-checkbox" />'+
			'</div>';
	
	row.append(div);

	if(prop.c['val'].length > 0){
		row.find('input').attr('checked', 'checked');
	}else{
		row.find('input').removeAttr('checked');
	}
	
	// When the change is called
	row.find('input').on('change', function(){
		
		// We set to string true or false
		var val = jQuery(this).is(':checked') ? 'true' : '';
		
		_pagelayer_set_atts(row, val);// Save and Render
	});
	
}

// The Radio property
function pagelayer_elp_radio(row, prop){
	
	var active = 'pagelayer-elp-radio-active';
	var div = '<div class="pagelayer-elp-radio-div">';
	
	for(var x in prop.list){		
		var addclass = (prop.c['val'] == x) ? active : '';
		div += '<a class="pagelayer-elp-radio '+addclass+'" val="'+x+'">'+prop.list[x]+'</a>';
	}
	
	div += '</div>';
	
	row.append(div);
	
	row.find('.pagelayer-elp-radio').each(function(){
		
		jQuery(this).on('click', function (){
			
			// Remove existing active class
			jQuery(this).parent().find('.'+active).removeClass(active);
			
			// Set active
			jQuery(this).addClass(active);
			
			_pagelayer_set_atts(row, jQuery(this).attr('val'));// Save and Render
			
		});
		
	});
	
}

// The Image Property
function pagelayer_elp_image(row, prop){
	
	var imgObj = {};
	var isRetina = false;
	
	// Is retina images options?
	if('retina' in prop && !pagelayer_empty(prop['retina'])){
		isRetina = true;
	}
	
	// Previously saved values
	if(typeof prop.c['val'] === 'object'){
		imgObj = prop.c['val'];
	}else{
		imgObj['img'] = prop.c['val'];
	}
  
	var tmp = prop.c['name']+'-url';
	var def = pagelayer.blank_img;
		
	// Background image URls
	var src = (tmp in prop.el.tmp) ? prop.el.tmp[tmp] : ((!pagelayer_empty(imgObj['img']) && String(imgObj['img']).search(/http(|s):\/\//i) == 0) ? imgObj['img'] : def );
	
	// Do we have a URL set ?
	var style = 'style="background-image:url(\''+src+'\')"';
	
	var div = '<div class="pagelayer-elp-image-div">'+
		'<div class="pagelayer-elp-drop-zone">'+
			'<div>'+
				'<i class="fas fa-upload"></i>'+
				'<h4>'+pagelayer_l('drop_file')+'</h4>'+
				'<div class="pagelayer-elp-img-up-progress">'+
					'<div class="pagelayer-elp-img-up-bar"></div>'+
				'</div>'+
			'</div>'+
		'</div>'+
		'<div class="pagelayer-elp-image" '+style+'></div>'+
		'<div class="pagelayer-elp-image-delete"><i class="pli pli-trashcan" ></i></div>';
		
		// Retina image icon
		if(isRetina){
			div +=	'<div class="pagelayer-elp-image-retina"><i class="pli pli-eye" ></i></div>';
		}

	div +='</div>';

	// Add retina images option
	if(isRetina){
		
		var tmp_retina = prop.c['name']+'-retina-url';
		var tmp_retina_mobile = prop.c['name']+'-retina-mobile-url';
		
		var srcRetina = (tmp_retina in prop.el.tmp) ? prop.el.tmp[tmp_retina] : (('retina' in imgObj && !pagelayer_empty(imgObj['retina']) && String(imgObj['retina']).search(/http(|s):\/\//i) == 0) ? imgObj['retina'] : def );
	
		var srcRetinaMobile = (tmp_retina_mobile in prop.el.tmp) ? prop.el.tmp[tmp_retina_mobile] : (('retina_mobile' in imgObj && !pagelayer_empty(imgObj['retina_mobile']) && String(imgObj['retina_mobile']).search(/http(|s):\/\//i) == 0) ? imgObj['retina_mobile'] : def );
	
		var style_retina = 'style="background-image:url(\''+srcRetina+'\')"';
		var style_retina_mobile = 'style="background-image:url(\''+srcRetinaMobile+'\')"';
		
		div +='<div class="pagelayer-elp-label-div pagelayer-retina-label" type="image" style="display:none;">'+
			'<label class="pagelayer-elp-label">Select Retina Image</label>'+
		'</div>'+
		'<div class="pagelayer-elp-retina-image-div" style="display:none;">'+
			'<div class="pagelayer-elp-drop-zone">'+
				'<div>'+
					'<i class="fas fa-upload"></i>'+
					'<h4>'+pagelayer_l('drop_file')+'</h4>'+
					'<div class="pagelayer-elp-img-up-progress">'+
						'<div class="pagelayer-elp-img-up-bar"></div>'+
					'</div>'+
				'</div>'+
			'</div>'+
			'<div class="pagelayer-elp-image pagelayer-retina" '+style_retina+'></div>'+
			'<div class="pagelayer-elp-retina-delete"><i class="pli pli-trashcan" ></i></div>'+				
		'</div>'+
		'<div class="pagelayer-form-item">'+
			'<div class="pagelayer-elp-label-div pagelayer-retina-label" type="image" style="display:none;">'+
				'<label class="pagelayer-elp-label">Select Retina Image For Mobile</label>'+
			'</div>'+
			'<div class="pagelayer-elp-checkbox-div pagelayer-retina-label" style="display:none;">'+
				'<input type="checkbox" name="overlay" class="pagelayer-elp-checkbox pagelayer-retina-checkbox">'+
			'</div>'+
		'</div>'+
		
		'<div class="pagelayer-elp-retina-mobile-image-div" style="display:none;">'+
			'<div class="pagelayer-elp-drop-zone">'+
				'<div>'+
					'<i class="fas fa-upload"></i>'+
					'<h4>'+pagelayer_l('drop_file')+'</h4>'+
					'<div class="pagelayer-elp-img-up-progress">'+
						'<div class="pagelayer-elp-img-up-bar"></div>'+
					'</div>'+
				'</div>'+
			'</div>'+
			'<div class="pagelayer-elp-image pagelayer-retina-mobile" '+style_retina_mobile+'></div>'+
			'<div class="pagelayer-elp-retina-mobile-delete"><i class="pli pli-trashcan" ></i></div>'+
		'</div>';
	}
	
	row.append(div);
	
	if(def == src && jQuery.isNumeric(imgObj['img'])){
		wp.media.attachment(imgObj['img']).fetch().then(function (data){
			var fetch_url = wp.media.attachment(imgObj['img']).get('url')
			row.find('.pagelayer-elp-image-div .pagelayer-elp-image').css('background-image', 'url(\''+fetch_url+'\')');
			_pagelayer_set_tmp_atts(row, 'url', fetch_url);
		}).fail(function(){
			row.find('.pagelayer-elp-image-div .pagelayer-elp-image').css('background-image', 'url(\''+src+'\')')
		});
	}
	
	if(isRetina){
		if(def == srcRetina && 'retina' in imgObj && jQuery.isNumeric(imgObj['retina'])){
			wp.media.attachment(imgObj['retina']).fetch().then(function (data){
				var fetch_url = wp.media.attachment(imgObj['retina']).get('url')
				row.find('.pagelayer-retina').css('background-image', 'url(\''+fetch_url+'\')');
				_pagelayer_set_tmp_atts(row, 'retina-url', fetch_url);
			}).fail(function(){
				row.find('.pagelayer-retina').css('background-image', 'url(\''+srcRetina+'\')')
			});
		}
		
		if(def == srcRetinaMobile && 'retina_mobile' in imgObj && jQuery.isNumeric(imgObj['retina_mobile'])){
			wp.media.attachment(imgObj['retina_mobile']).fetch().then(function (data){
				var fetch_url = wp.media.attachment(imgObj['retina_mobile']).get('url')
				row.find('.pagelayer-retina-mobile').css('background-image', 'url(\''+fetch_url+'\')');
				_pagelayer_set_tmp_atts(row, 'retina-mobile-url', fetch_url);
			}).fail(function(){
				row.find('.pagelayer-retina-mobile').css('background-image', 'url(\''+srcRetinaMobile+'\')')
			});
		}
	}
	
	var getImgVal = function(val){
		
		if(typeof val === 'object' && pagelayer_length(val) == 1 && 'img' in val){
			return val['img'];
		}
		
		return val;
	}
	
	// Set an Image
	row.find('.pagelayer-elp-image').on('click', function(){
	
		var button = jQuery(this);
		var inRetina = button.hasClass('pagelayer-retina');
		var inRetinaM = button.hasClass('pagelayer-retina-mobile');
		
		// Load the frame
		var frame = pagelayer_select_frame('image');
		
		// On select update the stuff
		frame.on({
			'select': function(){
				
				var state = frame.state();
				var id = url = '';
				
				// External URL
				if('props' in state){
					
					id = url = pagelayer_parse_theme_vars(state.props.attributes.url);
				
				// Internal from gallery
				}else{
				
					var attachment = frame.state().get('selection').first().toJSON();
					
					// Set the new ID and URL
					id = attachment.id;
					url = attachment.url;			
					var old = _pagelayer_img_tmp_atts(row);
					
					//console.log(attachment);
					if(inRetina){
						// To remove past temp attr so that they are not involve in future temp values						
						delete old[prop.c['name']+'-retina-url'];
						
						// Keep a list of all sizes
						for(var x in attachment.sizes){
							_pagelayer_set_tmp_atts(row, 'retina-'+x+'-url', attachment.sizes[x].url);
							delete old[prop.c['name']+'-retina-'+x+'-url'];
						}					
						
						for(var x in old){
							
							// Skip for retina and with url atts
							if(! x.endsWith('-url') || !x.startsWith(prop.c['name']+'-retina') || x.startsWith(prop.c['name']+'-retina-mobile')){
								continue;
							}
							
							_pagelayer_set_tmp_atts(row, x, '');
						}	
						
					}else if(inRetinaM){
						
						// To remove past temp attr so that they are not involve in future temp values	
						delete old[prop.c['name']+'-retina-mobile-url'];
						
						// Keep a list of all sizes
						for(var x in attachment.sizes){
							_pagelayer_set_tmp_atts(row, 'retina-mobile-'+x+'-url', attachment.sizes[x].url);
							delete old[prop.c['name']+'-retina-mobile-'+x+'-url'];
						}

						for(var x in old){
							
							// Skip for retina and with url atts
							if(! x.endsWith('-url') || ! x.startsWith(prop.c['name']+'-retina-mobile')){
								continue;
							}
							
							_pagelayer_set_tmp_atts(row, x, '');
						}						
						
					}else{
					
						// To remove past temp attr so that they are not involve in future temp values
						delete old[prop.c['name']+'-url'];
						
						// Keep a list of all sizes
						for(var x in attachment.sizes){
							_pagelayer_set_tmp_atts(row, x+'-url', attachment.sizes[x].url);
							delete old[prop.c['name']+'-'+x+'-url'];
						}
						
						for(var x in old){
							
							// Skip for retina and with url atts
							if(! x.endsWith('-url') || x.startsWith(prop.c['name']+'-retina')){
								continue;
							}
							
							_pagelayer_set_tmp_atts(row, x, '');
						}
					}	
				}
				
				// Update thumbnail
				button.css('background-image', 'url(\''+url+'\')');
				
				// Save and render
				_pagelayer_set_tmp_atts(row, 'no-image-set', '');
				
				if(inRetina){
					_pagelayer_set_tmp_atts(row, 'retina-url', url);
					imgObj['retina'] = id;
				}else if(inRetinaM){
					_pagelayer_set_tmp_atts(row, 'retina-mobile-url', url);
					imgObj['retina_mobile'] = id;
				}else{
					_pagelayer_set_tmp_atts(row, 'url', url);
					imgObj['img'] = id;
				}
				
				_pagelayer_set_atts(row, getImgVal(imgObj));
				
			},
			// On open select the appropriate images in the media manager
			'open': function() {			
				var selection =  frame.state().get('selection');
				var wp_id = pagelayer_get_att(prop.el.$, prop.c['name']);
				
				if(typeof wp_id === 'object'){
					if(inRetina){
						wp_id = ('retina' in wp_id && !pagelayer_empty(wp_id['retina']) ? wp_id['retina'] : 0 );
					}else if(inRetinaM){
						wp_id = ('retina_mobile' in wp_id && !pagelayer_empty(wp_id['retina_mobile']) ? wp_id['retina_mobile'] : 0 );
					}else{
						wp_id = (!pagelayer_empty(wp_id['img']) ? wp_id['img'] : 0 );
					}
				}
				
				selection.reset( wp_id ? [ wp.media.attachment( wp_id ) ] : [] );
			}
		});

		frame.open(button);
		
		return false;
		
	});
	
	// Finding and assigning values in the variables
	var dropzoneParent = row.find('.pagelayer-elp-image-div');
	var dropZone = row.find('.pagelayer-elp-drop-zone');
	
	// Inserting values in image drag and drop function
	pagelayer_img_dragAndDrop(dropzoneParent, dropZone, '', row);	
	
	row.find('.pagelayer-elp-image-retina').click(function(){
		row.find('.pagelayer-retina-label').toggle();
		row.find('.pagelayer-elp-retina-image-div').toggle();
		var checkval = row.find('.pagelayer-retina-checkbox').is(":checked");
		
		if(checkval == true){
			row.find('.pagelayer-retina-checkbox').trigger("click");
		}
	});
	
	row.find('.pagelayer-retina-checkbox').click(function(){
		row.find('.pagelayer-elp-retina-mobile-image-div').toggle();
	});
		
	// Delete this
	row.find('.pagelayer-elp-image-delete').on('click', function(){
		
		// Update thumbnail
		jQuery(this).parent().find('.pagelayer-elp-image').css('background-image', 'url(\''+def+'\')');
		
		// Set to blank and render
		_pagelayer_set_atts(row, '', true);
				
		imgObj['img'] = def;
		
		_pagelayer_set_tmp_atts(row, 'no-image-set', 1);
		_pagelayer_set_tmp_atts(row, 'url', def);
		_pagelayer_set_atts(row, getImgVal(imgObj));
	});
	
	row.find('.pagelayer-elp-retina-delete').on('click', function(){
		// Update thumbnail
		jQuery(this).parent().find('.pagelayer-elp-image').css('background-image', 'url(\''+def+'\')');
		delete imgObj['retina'];
    
		_pagelayer_set_tmp_atts(row, 'retina-url', def);
		_pagelayer_set_atts(row, getImgVal(imgObj));
		
	});
	
	row.find('.pagelayer-elp-retina-mobile-delete').on('click', function(){
		
		// Update thumbnail
		jQuery(this).parent().find('.pagelayer-elp-image').css('background-image', 'url(\''+def+'\')');
		delete imgObj['retina_mobile'];
		
		// Set to blank and render
		_pagelayer_set_tmp_atts(row, 'retina-mobile-url', def);
		_pagelayer_set_atts(row, getImgVal(imgObj));
		
	});
}

// Main image drag and drop function
function pagelayer_img_dragAndDrop(dropzoneParent, dropZone, jEle, row){
	
	var reset_dragging = false;
	
	dropzoneParent.on('dragover', function(e){
		e.preventDefault();
		// Checking that the dragged element is a file or not
		var dt = e.originalEvent.dataTransfer;
		if(dt.types && (dt.types.indexOf ? dt.types.indexOf('Files') != -1 : dt.types.contains('Files'))){
			if(e.originalEvent.dataTransfer.items[0].type.search('image/')!=-1){
				dropZone.show();
				reset_dragging = true;				
			}
		}
	});
	
	dropzoneParent.on('dragleave', function(e){
		var rect = this.getBoundingClientRect();
		// Checking that the cursor is in the drag area or not
		if (e.clientX >= (rect.left + rect.width) || e.clientX <= rect.left || e.clientY >= (rect.top + rect.height) || e.clientY <= rect.top) {
			dropZone.hide();
			reset_dragging = false;
        }
	});
	
	dropzoneParent.on('drop', function(e){
		
		// Is not dropable?
		if(!reset_dragging){
			return;
		}
		
		e.preventDefault();
		var pagelayer_ajax_func = {};
		
		// This function for ajax success call back
		pagelayer_ajax_func['success'] = function(obj){
			
			if(obj['success']){
					
				// Set the new ID and URL
				id = obj['data']['id'];
				url = obj['data']['url'];
				
				if(row == ''){
					// Getting Id of jEle 
					var widgetid = jEle.closest('[pagelayer-id]').attr('pagelayer-id');
					
					// Finding widget image setting using id of jEle. Finding image editor setting from all of the other settings.
					row = pagelayer.$$('[pagelayer-element-id='+widgetid+']').find('.pagelayer-elp-image').eq(0).parent().parent();
				}
				
				row.find('.pagelayer-elp-image').css('background-image', 'url(\''+url+'\')');
							
				// To remove past temp attr so that they are not involve in future temp values
				var cname = row.attr('pagelayer-elp-name');
				var old = _pagelayer_img_tmp_atts(row);
				delete old[cname+'-url'];
				
				for(var x in obj['data']['sizes']){
					_pagelayer_set_tmp_atts(row, x+'-url', obj['data']['sizes'][x]['url']);
					delete old[cname+'-'+x+'-url'];
				}
				
				for(var x in old){
					_pagelayer_set_tmp_atts(row, x+'-url', '');
				}
				
				dropZone.find('.pagelayer-elp-img-up-bar').css('width', '3%');
				dropZone.hide();
				
				// Save and render
				_pagelayer_set_tmp_atts(row, 'url', url);
				_pagelayer_set_atts(row, id);
				
			}else{
				alert(obj['data']['message']);								
			}
		}
		
		// This function for ajax before send call back
		pagelayer_ajax_func['beforeSend'] = function(xhr){
			// It activate the image widget
			if(row == ''){
				jEle.click();							
			}
		}
		
		// This function for how much file is uploaded or for progress bar
		pagelayer_ajax_func['uploadProgress'] = function(xhr){
			xhr.upload.addEventListener("progress", function(evt) {
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					percentComplete = parseInt(percentComplete * 100);
					
					if(row == ''){
						dropZone.find('.pagelayer-img-up-bar').css('width', percentComplete+'%');					
					}else{
						dropZone.find('.pagelayer-elp-img-up-bar').css('width', percentComplete+'%');											
					}
				}
			}, false);
			return xhr;
		}
		
		// Uploading image to the media library
		pagelayer_editable_paste_handler(e.originalEvent.dataTransfer.files[0], pagelayer_ajax_func);
		
		reset_dragging = false;
		
	});
}	

// The Multi Image Property
function pagelayer_elp_multi_image(row, prop){
	
	var div = '<div class="pagelayer-elp-multi_image-div">'+
				'<center><button class="pagelayer-elp-button">'+pagelayer_l('Add Images')+'</button></center>'+
				'<div class="pagelayer-elp-multi_image-thumbs"></div>'+
			'</div>';
			
	row.append(div);
	
	var tmp = prop.c['name']+'-urls';
	var ids = new Array();
	
	// Any IDs ?
	if(!pagelayer_empty(prop.c['val'])){
		ids = prop.c['val']
		if(pagelayer_is_string(ids)){
			ids = prop.c['val'].split(',');
		}
		//console.log(ids);
	}
	
	// Do we have a URL set ?
	if(!pagelayer_empty(ids)){
		if(tmp in prop.el.tmp){
			var images = JSON.parse(prop.el.tmp[tmp]);
			//console.log(images);
			
			for(var x in ids){
				row.find('.pagelayer-elp-multi_image-thumbs').append('<div class="pagelayer-elp-multi_image-thumb" style="background-image: url(\''+images['i'+ids[x]]+'\');"></div>');
			}
		}else{
			wp.media.query({ post__in: ids }).more().then(function(){
				// You attachments here normally
				// You can safely use any of them here
				// TODO: Set tmp here
				for(var x in ids){
					var fetch_url = wp.media.attachment(ids[x]).get('url');
					if(!pagelayer_empty(fetch_url)){
						row.find('.pagelayer-elp-multi_image-thumbs').append('<div class="pagelayer-elp-multi_image-thumb" style="background-image: url(\''+fetch_url+'\');"></div>');
					}
				}
			});
		}
	}
	
	var pagelayer_init_frame = function(state){
	
		var button = row.find('.pagelayer-elp-multi_image-thumbs');
		//console.log(ids);
		
		// Load the frame
		var frame = pagelayer_select_frame('multi_image', state);
		
		frame.on({
			
			'select': function(){
				
				var state = frame.state();
				var id = url = '';
				var urls = {};
				
				// External URL
				if('props' in state){
					//console.log(state);
					var urls_str = state.props.attributes.url;
					
					var urls_arr = urls_str.split(',');
					//console.log(urls_arr);
					
					button.empty();
					
					// Add to current selection
					for(var i = 0; i < urls_arr.length; i++){
						var single_url = pagelayer_parse_theme_vars(urls_arr[i]);
						urls['i'+i] = single_url;
						
						// Create thumbnails
						button.append('<div class="pagelayer-elp-multi_image-thumb" style="background-image: url(\''+single_url+'\');"></div>');
					}
					urls_arr = Object.values(urls);
					
					_pagelayer_set_tmp_atts(row, 'urls', JSON.stringify(urls));
					_pagelayer_set_atts(row, urls_arr.join());
					
				}
			},
			
			// Set the current selection if any
			'open': function(){

				// Do we have anything
				if(!pagelayer_empty(ids)){
					
					var selection = '';
					
					if(state == 'gallery-edit'){
						selection = frame.state().get('library');
					}else if(state == 'gallery-library'){
						selection = frame.state().get('selection');
					}
					
					// Add to current selection
					if(!pagelayer_empty(selection)){
						for(var x in ids){
							attachment = wp.media.attachment(ids[x]);
							attachment.fetch();
							selection.add(attachment ? [ attachment ] : [] );
						}
					}
				}
			},
			
			// When images are selected
			'update': function(selection){
				
				//console.log(selection);
				
				// Remove thumbnails
				row.find('.pagelayer-elp-multi_image-thumb').remove();
				
				//Fetch selected images
				var attachments = selection.map(function(attachment){
					attachment.toJSON();
					return attachment;
				});
				
				//console.log(attachments);
				
				var img_ids = [];
				var urls = {};
				var img_urls = {};
				var titles = {};
				var links = {};
				var captions = {};
				
				for(var i = 0; i < attachments.length; ++i){
					
					// Add Id and urls to array
					var id = attachments[i].id;
					var _id = 'i'+id;
					img_ids.push(id);
					urls[_id] = attachments[i].attributes.url;
					
					// Create thumbnails
					button.append('<div class="pagelayer-elp-multi_image-thumb" style="background-image: url(\''+attachments[i].attributes.url+'\');"></div>');
					
					//get title
					titles[_id] = attachments[i].attributes.title;
					links[_id] = attachments[i].attributes.link;
					captions[_id] = attachments[i].attributes.caption;
					
					// Create a URL
					img_urls[_id] = {}
					
					for(var x in attachments[i].attributes.sizes){
						img_urls[_id][x] = attachments[i].attributes.sizes[x].url;
					}
					
				}
				
				//console.log(img_urls);
				
				// Save and render
				_pagelayer_set_tmp_atts(row, 'urls', JSON.stringify(urls));
				_pagelayer_set_tmp_atts(row, 'all-urls', JSON.stringify(img_urls));
				_pagelayer_set_tmp_atts(row, 'all-titles', JSON.stringify(titles));
				_pagelayer_set_tmp_atts(row, 'all-links', JSON.stringify(links));
				_pagelayer_set_tmp_atts(row, 'all-captions', JSON.stringify(captions));
				_pagelayer_set_atts(row, img_ids);
				
				// Update the IDs incase the user clicks on it again
				ids = img_ids;
				
			}
			
		});
		
		frame.open(button);
		
		return false;
		
	};
	
	row.find('.pagelayer-elp-multi_image-thumbs').on('click', function(){
		pagelayer_init_frame('gallery-edit');
	});
	
	row.find('.pagelayer-elp-button').on('click', function(){
		
		if(!pagelayer_empty(ids)){
			if(isNaN(ids[0])){
				pagelayer_init_frame('embed');
			}else{
				pagelayer_init_frame('gallery-library');
			}
		}else{
			pagelayer_init_frame('gallery');
		}		
	});
	
}

// The Video Property
function pagelayer_elp_video(row, prop){
	
	var tmp = prop.c['name']+'-url';
	var src = (tmp in prop.el.tmp) ? prop.el.tmp[tmp] : prop.c['val'];
	
	var div = '<div class="pagelayer-elp-video-div pagelayer-elp-input-icon">'+
				'<input class="pagelayer-elp-video" name="'+prop.c['name']+'" type="text" value="'+src+'">'+
				'<i class="pli pli-folder-open" ></i>'+
			'</input></div>';
			
	row.append(div);
	
	row.find('.pagelayer-elp-video-div .pli').on('click', function(){
	
		var button = jQuery(this);
		
		// Load the frame
		var frame = pagelayer_select_frame('video');
		
		// On select update the stuff
		frame.on({
			
			'select': function(){
				
				var state = frame.state();
				var id = url = '';
				
				// External URL
				if('props' in state){
					
					id = url = pagelayer_parse_theme_vars(state.props.attributes.url);
				
				// Internal from gallery
				}else{
				
					var attachment = frame.state().get('selection').first().toJSON();
					//console.log(attachment);
					
					id = attachment.id;
					url = attachment.url;
				
				}
				
				// Update URL
				button.prev().val(url);
				
				// Save and render
				_pagelayer_set_tmp_atts(row, 'url', url);
				_pagelayer_set_atts(row, id);
				
			}
			
		});

		frame.open(button);
		
		return false;
		
	});
	
	// Edited the video URL directly
	row.find('.pagelayer-elp-video').on('change', function(){
		
		var input = jQuery(this);
		
		// Set the new URL
		_pagelayer_set_tmp_atts(row, 'url', input.val());
		_pagelayer_set_atts(row, input.val());
		
	});
	
}


// The Audio Property
function pagelayer_elp_audio(row, prop){
	
	var tmp = prop.c['name']+'-url';
	var src = (tmp in prop.el.tmp) ? prop.el.tmp[tmp] : prop.c['val'];
	
	var div = '<div class="pagelayer-elp-audio-div pagelayer-elp-input-icon">'+
				'<input class="pagelayer-elp-audio" name="'+prop.c['name']+'" type="text" value="'+src+'" />'+
				'<i class="pli pli-menu" ></i>'+
			'</div>';
	
	row.append(div);
	
	// Choose from media
	row.find('.pagelayer-elp-audio-div .pli').on('click', function(){
		
		var button = jQuery(this);
		
		// Load the frame
		var frame = pagelayer_select_frame('audio');
		
		frame.on({
			
			'select': function(){
				
				var state = frame.state();
				var id = url = '';
				
				// External URL
				if('props' in state){
					
					id = url = pagelayer_parse_theme_vars(state.props.attributes.url);
				
				// Internal from gallery
				}else{
				
					var attachment = frame.state().get('selection').first().toJSON();
					//console.log(attachment);
					
					id = attachment.id;
					url = attachment.url;
				
				}
				
				// Update URL
				button.prev().val(url);
				
				// Save and render
				_pagelayer_set_tmp_atts(row, 'url', url);
				_pagelayer_set_atts(row, id);
				
			}
			
		});
		
		frame.open(button);
		
		return false;
		
	});
	
	// Edited the media URL directly
	row.find('.pagelayer-elp-audio').on('change', function(){
		
		var input = jQuery(this);
		
		// Set the new URL
		_pagelayer_set_tmp_atts(row, 'url', input.val());
		_pagelayer_set_atts(row, input.val());
		
	});
	
}

// The Media Property
function pagelayer_elp_media(row, prop){
	
	var tmp = prop.c['name']+'-url';
	var src = (tmp in prop.el.tmp) ? prop.el.tmp[tmp] : prop.c['val'];
	
	var div = '<div class="pagelayer-elp-media-div pagelayer-elp-input-icon">'+
				'<input class="pagelayer-elp-media" value="'+src+'" type="text" />'+
				'<i class="pli pli-menu" ></i>'+
			'</div>';
	
	row.append(div);
	
	row.find('.pagelayer-elp-media-div .pli-menu').on('click', function(){
		
		var button = jQuery(this);
		
		// Load the frame
		var frame = pagelayer_select_frame('media');
		
		frame.on({
			
			'select': function(){
				
				var state = frame.state();
				var id = url = '';
				
				// External URL
				if('props' in state){
					
					id = url = pagelayer_parse_theme_vars(state.props.attributes.url);
				
				// Internal from gallery
				}else{
				
					var attachment = frame.state().get('selection').first().toJSON();
					//console.log(attachment);
					
					id = attachment.id;
					url = attachment.url;
				
				}
				
				// Update URL
				button.prev().val(url);
				
				// Save and render
				_pagelayer_set_tmp_atts(row, 'url', url);
				_pagelayer_set_atts(row, id);
				
			}
			
		});
		
		frame.open(button);
		
		return false;
		
	});
	
	// Edited the media URL directly
	row.find('.pagelayer-elp-media').on('change', function(){
		
		var input = jQuery(this);
		
		// Set the new URL
		_pagelayer_set_tmp_atts(row, 'url', input.val());
		_pagelayer_set_atts(row, input.val());
		
	});
	
}

// The Slider Property
function pagelayer_elp_slider(row, prop){
	
	var div = '<div class="pagelayer-elp-slider-div">'+
				  '<input type="range" class="pagelayer-elp-slider" value="'+parseFloat(prop.c['val'])+'" min="'+prop['min']+'" max="'+prop['max']+'" step="'+prop['step']+'"/>'+
				  '<input type="number" class="pagelayer-elp-slider-value" value="'+parseFloat(prop.c['val'])+'" min="'+prop['min']+'" max="'+prop['max']+'" step="'+prop['step']+'"/>'+
				'</div>'+
			'</div>';
	
	row.append(div);
	
	// Set an value in span
	row.find('.pagelayer-elp-slider-div input').on('input', function(){
		var value = parseFloat(this.value);
		var max = parseFloat(this.max);
		
		if(!pagelayer_empty(max) && value > max){
			value = max;
		}
		row.find('.pagelayer-elp-slider-div input').val(value);
		
		_pagelayer_set_atts(row, value);// Save and Render
		
	});
	
}

// The Editor proprety
function pagelayer_elp_editor(row, prop){
	
	var rows = prop.rows ? prop.rows : '8';
	
	var div = '<div class="pagelayer-elp-editor-div">'+
				'<textarea rows="'+rows+'" class="pagelayer-elp-editor" ></textarea>'+
			'</div>';
			
	row.append(div);
	
	var editor = row.find('.pagelayer-elp-editor');
	editor.val(prop.c['val']);
	
	// Handle on change
	editor.on('input', function(){
		_pagelayer_set_atts(row, pagelayer_trim(jQuery(this).val()));// Save and Render
	});
	
	return;
	// No SVG Icons for now
	jQuery.trumbowyg.svgPath = false;
	
	// Initiate the editor
	editor.trumbowyg({
		autogrow: false,
		hideButtonTexts: true,
		btns:[
			['viewHTML'],
			['wpmedia'],
			['fontfamily'],
			['formatting'],
			['undo', 'redo'], // Only supported in Blink browsers
			['fontsize'],
			['lineheight'],
			['foreColor', 'backColor',],
			['strong', 'em', 'del'],
			['horizontalRule'],
			['superscript', 'subscript'],
			['link'],
			['unorderedList', 'orderedList'],
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
			['removeformat'],
			['fullscreen']
		],
		plugins: {
			fontsize: {
				sizeList: ['12px','13px','14px','15px','16px','17px','18px','19px','20px','21px','22px','23px','24px','25px']
			}
		},
		imageWidthModalEdit: true,
		
	// Handle the changes made in the editor
	}).on('tbwchange', function(){
		_pagelayer_set_atts(row, editor.trumbowyg('html'));// Save and Render
	});
	
}

// The Link proprety
function pagelayer_elp_link(row, prop){

	var values = {};
	var settingOpt = false;
	
	// Show link options?
	if('selector' in prop){
		settingOpt = true;
	}
  
	// Previously saved values
	if(typeof prop.c['val'] === 'object'){
		values = prop.c['val'];
	}else{
		values['link'] = prop.c['val'];
	}
	  
	var tmp = prop.c['name'];
	var link = (tmp in prop.el.tmp) ? prop.el.tmp[tmp] : values['link'];
	var jEle = jQuery('[pagelayer-id='+prop.el.id+']');
	
	var div = '<div class="pagelayer-elp-link-div pagelayer-elp-input-icon '+(settingOpt ? '' : 'pagelayer-elp-link-no-addons')+'">'+
		'<input class="pagelayer-elp-link" type="text" value="'+link+'" />'+
		'<i class="pli pli-service pagelayer-elp-link-icon" title="'+pagelayer_l('link_options')+'" ></i>'+
		'<div class="pagelayer-elp-link-list">'+
		'</div>';
		
		if(settingOpt){
			div += '<div class="pagelayer-elp-link-addons">'+
				'<div  class="pagelayer-elp-link-cb-div">'+
					'<div class="pagelayer-elp-link-label-div" type="'+prop['type']+'">'+
						'<label class="pagelayer-elp-link-label">'+pagelayer_l('open_link_in_new_window')+'</label>'+
					'</div>'+
					'<div>'+
						'<input type="checkbox" name="link_new_tab" class="pagelayer-elp-checkbox" '+(!pagelayer_empty(values['target']) ? 'checked="checked"' : '')+' />'+
					'</div>'+
				'</div>'+
				'<div class="pagelayer-elp-link-cb-div" >'+
					'<div class="pagelayer-elp-link-label-div" type="'+prop['type']+'">'+
						'<label class="pagelayer-elp-link-label">'+pagelayer_l('add_nofollow')+'</label>'+
					'</div>'+
					'<div>'+
						'<input type="checkbox" name="link_no_follow" class="pagelayer-elp-checkbox" '+(!pagelayer_empty(values['rel']) ? 'checked="checked"' : '')+'/>'+
					'</div>'+
				'</div>'+
				'<div class="pagelayer-elp-link-ca" >'+
					'<div class="pagelayer-elp-link-label-div" type="'+prop['type']+'">'+
						'<label class="pagelayer-elp-link-label">'+pagelayer_l('custom_attributes')+'</label>'+
					'</div>'+
					'<div>'+
						'<input type="text" class="pagelayer-elp-text" placeholder="key = value" name="'+prop.c['name']+'" value="'+(pagelayer_empty(values['attrs']) ? '' : values['attrs'])+'"/>'+
						'<p class="pagelayer-elp-link-desc">'+pagelayer_l('link_custom_attr_desc')+'</p>'+
					'</div>'+
				'</div>'+
			'</div>';
		}
		
	div += '</div>';
	
	row.append(div);
	
	var listWrap = row.find('.pagelayer-elp-link-list');
	var time = null;
		
	//Add ID
	var addID = function(permaID){
		permaID = permaID || false;
		
		var lDiv = row.closest('[pagelayer-elp-name]').find('.pagelayer-elp-label-div');
		if(permaID){
			lDiv = lDiv.find('.pagelayer-elp-label');
			lDiv.after('<span class="pagelayer-elp-link-id">ID : '+permaID+'</span>');
		}else{
			lDiv.find('.pagelayer-elp-link-id').remove();
		}
	};
	
	if(!isNaN(values['link'])){
		addID(values['link']);
	}
	
	var getLinkVal = function(val){
		
		if(typeof val === 'object' && pagelayer_length(val) == 1 && 'link' in val){
			return val['link'];
		}
		
		return val;
	}
	
	var setTmpEmpty = function(){
		
		if( 'link' in values && !pagelayer_empty(values['link']) ){
			return;
		}
		
		_pagelayer_set_tmp_atts(row, '', '');
	}
	
	// Set a Link
	row.find('.pagelayer-elp-link').on('change', function(){
		
		var linkVal = jQuery(this).val();
		values['link'] = linkVal;
    
		// Save and Render
		_pagelayer_set_tmp_atts(row, '', linkVal);
		_pagelayer_set_atts(row, getLinkVal(values));
		
		// Remove ID Holder
		addID();

	});
	
	// Set a Link
	row.find('.pagelayer-elp-link').on('input click', function(e){
		e.stopPropagation();
		
		if(!listWrap.is(':visible')){
			listWrap.show();
		}
		
		var val = jQuery(this).val();
		
		clearTimeout(time);
		time = setTimeout(function(){

			jQuery.ajax({
				url: pagelayer_ajax_url,
				type: 'post',
				data:{
					'action' : 'wp-link-ajax',
					'_ajax_linking_nonce' : pagelayer_internal_linking_nonce,
					'search' : val,
				},
				success: function(response) {
					
					var data = jQuery.parseJSON(response);
					var html = '';
					//console.log('Link Data');console.log(response);
					
					if(pagelayer_empty(data)){
						html = pagelayer_l('custom_url');
						// Remove ID Holder
						addID();
					}else if(typeof data === 'object'){
						
						for(var key in data){
							var vals = data[key];
							html += '<div class="pagelayer-elp-link-item"  data-id="'+vals['ID']+'" data-permalink="'+vals['permalink']+'">'+
								'<div class="pagelayer-elp-link-title">'+
									'<span class="pagelayer-elp-link-item-title" title="'+vals['title']+'">'+vals['title']+'</span>'+
									'<span class="pagelayer-elp-link-item-perma" title="'+vals['permalink']+'">'+vals['permalink']+'</span>'+
								'</div>'+
								'<div class="pagelayer-elp-link-info">'+
									'<span title="'+vals['info']+'">'+vals['info']+'</span>'+
								'</div>'+
							'</div>';
						}
					}
					
					listWrap.html(html);
				},
				fail: function(data) {
					listWrap.html('Some error occured in getting the link data');
				}
			});
			
		}, 200);
		
	});
	
	listWrap.on('click', function(e){
		e.stopPropagation();
		
		var lEle = jQuery(e.target).closest('.pagelayer-elp-link-item');
		
		// IF item not found
		if(lEle.length < 1){
			return;
		}
		
		var perma = lEle.attr('data-permalink');
		var ID = lEle.attr('data-id');
		values['link'] = ID;
		
		// Save and Render
		row.find('.pagelayer-elp-link').val(perma);
		_pagelayer_set_tmp_atts(row, '', perma);
		_pagelayer_set_atts(row, getLinkVal(values));
		
		listWrap.hide();
		
		// Show ID
		addID(ID);
	});
	
	pagelayer.gDocument.on('click', function(e){
		listWrap.hide();
	});
	
	row.find('.pagelayer-elp-checkbox').on('change', function(event){
		
		var cEle = jQuery(this);
		
		// Save or delete the value
		var saveVal = function(key){
			if(cEle.is(':checked')){
				values[key] = true;
				return;
			}
			
			delete values[key];
		}
				
		switch(cEle.attr('name')){
			case 'link_new_tab':
				saveVal('target');
				break;
			case 'link_no_follow':
				saveVal('rel');
				break;
		}
		
		setTmpEmpty();
		_pagelayer_set_atts(row, getLinkVal(values));
		
	});
	
	var linkTime = '';
	row.find('.pagelayer-elp-text').on('input', function(event){
		var cEle = jQuery(this);
		
		clearTimeout(linkTime);
		linkTime = setTimeout(function(){
			values['attrs'] = cEle.val();
			
			if(pagelayer_empty(values['attrs'])){
				delete values['attrs'];
			}
			
			setTmpEmpty();
			_pagelayer_set_atts(row, getLinkVal(values));
			
		}, 500);
	});
	
	row.find('.pagelayer-elp-link-icon').on('click', function(){
		row.find('.pagelayer-elp-link-addons').slideToggle('slow');
	});
  
}

// The Textarea property
function pagelayer_elp_textarea(row, prop){
	
	var rows = prop.rows ? 'rows="'+prop.rows+' "' : '';
	
	var div = '<div class="pagelayer-elp-textarea-div">'+
				'<textarea '+rows+'class="pagelayer-elp-textarea"></textarea>'+
			'</div>';
			
	row.append(div);
	row.find('.pagelayer-elp-textarea').val(prop.c['val']);
  
	// Handle on change
	row.find('.pagelayer-elp-textarea').on('input', function(){
		_pagelayer_set_atts(row, pagelayer_trim(jQuery(this).val()));// Save and Render
	});
};

// Clear all editable
function pagelayer_clear_editable(dontDestroy){
	
	// Destroy all
	for(var x in pagelayer_editor){
		if(dontDestroy == x){
			console.log('Skipping '+dontDestroy);
			continue;
		}
		pagelayer_editor[x].pen.destroy();
	}
	
};

// Makes a field editable in the DOM
function pagelayer_make_editable(jEle, e){
	
	// The parent element
	var pEle = jEle.closest('.pagelayer-ele, [pagelayer-ref-id]');
	
	// Mainly for editing table cells as pagelayer-ref-id is used by them
	if(!pEle.hasClass('pagelayer-ele')){
		var refID = pEle.attr('pagelayer-ref-id');
		pEle = jQuery('[pagelayer-id="'+refID+'"]');
	}
	
	var prop = jEle.attr('pagelayer-editable');
	var eId = pagelayer_id(pEle)+'|'+jEle.attr('pagelayer-editable');// Editing ID
	
	// Is it already setup ?
	if(jEle.hasClass('pagelayer-pen')){
		//console.log('Already Penned');
		//pagelayer_focus_editable(jEle, e, eId);
		return true;
	}
	
	var tag = pagelayer_tag(pEle);
	var all_props = pagelayer_shortcodes[tag];
	var edit_opts;
	var fullEdit = false;
	
	for(var i in pagelayer_tabs){
		var tab = pagelayer_tabs[i];
		for(var section in all_props[tab]){	//console.log(tab+' '+section);
	
			var props = section in pagelayer_shortcodes[tag] ? pagelayer_shortcodes[tag][section] : pagelayer_styles[section];//console.log(props);
	
			// Any editor options?
			if(prop in props){
				
				if('e' in props[prop]){
					edit_opts = props[prop].e;
				}
				
				if(props[prop]['type'] == 'editor'){
					fullEdit = true;
				}
			}
		}
	}
	
	var pen_tools = {
		'inline': [ 'viewHTML',
			{'formating' : ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p']},
			'bold', 'italic', 'underline', 'strike',
			{ 'color': [] }, { 'background': [] },
			'removeformat'
		],
		'h': ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
		'headers': [{'formating' : ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']}],
		'c': [{ 'color': [] }, { 'background': [] }],
		'f': ['bold', 'italic', 'underline', 'strike'],
		'a': [{ 'align': ['left', 'center', 'right', 'justify'] }],
		'r': ['removeformat'],
		'v': ['viewHTML'],
	};
	
	// Create Toolbar Groups
	if(!('pen_tools' in pagelayer_editor)){
		pagelayer_editor['pen_tools'] = {};
	}
	
	pagelayer_editor['pen_tools'] = Object.assign(pagelayer_editor['pen_tools'], pen_tools);
	
	var toolbar_options = [];
	
	if( pagelayer_empty(edit_opts) ){
		
		if(fullEdit){
			toolbar_options = [
				[ 'viewHTML' ],
				[ 'bold', 'italic', 'underline', 'strike' ],
				[ 'sub', 'super' ],
				//[ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'blockquote'],
				[ {'formating' : ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'blockquote']}],
				[ {'align': ['left', 'center', 'right', 'justify']} ],
				[ 'image', 'link'],
				[ 'unorderedlist', 'orderedlist'],
				[ {'size': []}, {'lineheight': []}, {'font': []}],
				[ {'color': [] }, {'background': []}],
				[ 'removeformat' ]
			];
		}else{
			toolbar_options = pagelayer_editor.pen_tools['inline'];
		}
	}else{
		var options = [];
		
		if(! Array.isArray(edit_opts) ){
			edit_opts = [edit_opts];
		}
		
		for( var tt in edit_opts){
			
			var tool = edit_opts[tt];
			
			if(pagelayer_is_string(tool)){
				if(tool in pagelayer_editor['pen_tools']){
					tool = pagelayer_editor['pen_tools'][tool]
				}else{
					tool = [tool];
				}
			}
			
			options.push(tool);
		}
		
		toolbar_options = options;
	}
	
	var options = {
		class: 'pagelayer-pen',
		editor: jEle,
		toolbar: toolbar_options
	}
	
	// Setup the editor	
	pagelayer_editor[eId] = {};
	pagelayer_editor[eId].pen = new PagelayerPen(jEle, options);
	pagelayer_editor[eId].$ = jEle;
	
	// Are we the clicked object, then set the focus
	if(e){
		var target = jQuery(e.target);
		if(target.is(jEle) || jEle.find(target).length > 0){
			jEle.focus();
		}
	}
	
	// Reverse setup the event
	jEle.on('blur', function(){
		
		//pagelayer_editor[eId].pen.destroy();
		if(jEle.hasClass('pagelayer-pen-focused')){
			return;
		}
		
		var cEle = pEle;
		
		// Do we have a parent ?
		var have_parent = function(Ele){
			var pId = pagelayer_get_parent(Ele);
					
			if(pagelayer_empty(pId)){
				return;
			}
			
			cEle = pagelayer_ele_by_id(pId);
			have_parent(cEle);
		}
		
		have_parent(cEle);
		
		var is_global = pagelayer_get_global_id(cEle);
				
		if(pagelayer_empty(is_global)){
			return;
		}
    
		pagelayer_sc_render(pEle);
		
	});
	
	/*// Reverse setup the event
	jEle.on('focus', function(){
		//pagelayer_clear_editable(eId);
	});*/
	
	// Reverse setup the event
	jEle.on('input', function(){
		
		var val = pagelayer_trim(jEle.html());
		
		// Set the property as well
		pagelayer_set_atts(pEle, prop, val);
		
		// Update the property
		var input = pagelayer.$$('[pagelayer-element-id='+pagelayer_id(pEle)+']').find('[pagelayer-elp-name='+prop+']').find('input,textarea,.trumbowyg-editor');
		//console.log(input);
		
		if(input.length > 0){
			if(input.hasClass('trumbowyg-editor')){
				input.html(val);
			}else{
				input.val(val);
			}
		}
	
	});
	
}

// The Icon Property
function pagelayer_elp_icon(row, prop){
	
	var $ = jQuery;
	var sets_html = '';
	
	pagelayer_loaded_icons.forEach(function(item){
		sets_html += '<option name="'+item+'" value="'+item+'">'+item+'</option>';
	});
	
	var icons = {};
	var cur_icon_set = pagelayer_loaded_icons[0];
	var sel_icon = prop.c['val'];
	var sel_name = prop.c['val'];
	var icon_type = '';
	var sorted_icons = {};
	
	// Handle the icon name 
	var icon_name = sel_icon.split(' fa-');
	sel_name = icon_name[1];
	
	// Is there a specific list
	if('list' in prop && prop.list.length > 0){
		
		for(var i in pagelayer_icons){
			
			icons[i] = {};
			
			for(var j in pagelayer_icons[i]){
				
				icons[i][j] = {};
				var list_icons = [];
				prop.list.forEach(function(item){
					if(pagelayer_icons[i][j]['icons'].includes(item)){
						list_icons.push(item);
					}
					
				});
				icons[i][j]['icons'] = list_icons;
				icons[i][j]['pre'] = j;
			}
			
		}
	
	}else{
		icons = pagelayer_icons;
	}
	
	// Icon function
	var icon_html = function(name, cat){
		return '<span class="pagelayer-elp-icon-span">'+
			'<i class="'+cat+' fa-'+name+'" icon="'+name+'" ></i> '+name+
		'</span>';
	}
	
	var div = '<div class="pagelayer-elp-icon-div">'+
		'<div class="pagelayer-elp-icon-preview">'+
			'<i class="'+sel_icon+'"></i>'+
			'<span class="pagelayer-elp-icon-name">'+
			(pagelayer_empty(sel_name)?'Choose icon':sel_name)+
			'</span>'+
		'</div>'+
		'<span class="pagelayer-elp-icon-open">▼</span>'+
		'<span class="pagelayer-elp-icon-close" '+(pagelayer_empty(sel_name)? 'style="display:none"': '')+'><b>&times;&nbsp;</b></span>'+
	'</div>';
	
	row.append(div);
	
	// Make all icons list
	var html = '<div class="pagelayer-elp-icon-selector">';
	
	if(pagelayer_loaded_icons.length > 1){
		html += '<select class="pagelayer-elp-icon-sets">'+sets_html+'</select>';
	}
	
	html += '<span class="pagelayer-elp-icon-type">'+
		'<p data-tab="fas" class="active">'+pagelayer_l('Solid')+'</p>'+
		'<p data-tab="far">'+pagelayer_l('Regular')+'</p>'+
		'<p data-tab="fab">'+pagelayer_l('Brand')+'</p>'+
	'</span>'+
	'<input type="text" class="pagelayer-elp-search-icon" name="search-icon" placeholder="'+pagelayer_l('search')+'">'+
	'<div class="pagelayer-elp-icon-list">';

	for(var y in icons[cur_icon_set]){
		//console.log(icons[x][y])
		for(var z in icons[cur_icon_set][y]['icons']){
			html += icon_html(icons[cur_icon_set][y]['icons'][z], y);
		}
	}
	
	html += '</div>'+
	'</div>';
	
	row.append(html);
	
	// Open the selector
	row.find('.pagelayer-elp-icon-div').on('click', function(){
		row.find('.pagelayer-elp-icon-selector').slideToggle();
	});
	
	/*// When the set changes
	row.find('.pagelayer-elp-icon-sets').on('change', function(){
		var v = cur_icon_set = jQuery(this).val();
		var span = '';
		
		for(var x in icons[v]){
		
			for(var z in icons[v][x]['icons']){
				span += icon_html(icons[v][x]['icons'][z], x);
			}
			
		}
		
		if(cur_icon_set == 'font-awesome5'){
			row.find('.pagelayer-elp-icon-type').show();
			sorted_icons = icons[cur_icon_set]['fas'];
			row.find('.pagelayer-elp-icon-type [data-tab="fas"]').click();
		}else{
			row.find('.pagelayer-elp-icon-type').hide();
		}
		
		row.find('.pagelayer-elp-icon-list').empty().html(span);
		
		if(row.find('.pagelayer-elp-search-icon').val() != ''){
			row.find('.pagelayer-elp-search-icon').keyup();
		}
		
	});*/
	
	// Handle type of icon
	row.find('.pagelayer-elp-icon-type p').on('click', function(){		
		jQuery(this).toggleClass('active');
		row.find('.pagelayer-elp-search-icon').keyup();
	});
	
	// Handle search of icon
	row.find('.pagelayer-elp-search-icon').on('keyup', function(){
	
		var v = this.value;
		var span = '';
		v = v.toLowerCase();
		v = v.replace(/\s+/g, '-');
		//console.log(sorted_icons);
		
		row.find('.pagelayer-elp-icon-type p.active').each(function(){				
			var tab = jQuery(this).data('tab');
			tab = tab.toLowerCase();
			
			var cat = icons['font-awesome5'][tab]['icons'];
			
			for(var x in cat){
				if(cat[x].includes(v) || v.length < 1){
					span += icon_html(cat[x], tab);
				}
			}
		});
		
		row.find('.pagelayer-elp-icon-list').empty().html(span);
		
	});
	
	// Handle click within the icon selector
	row.find('.pagelayer-elp-icon-list').on('click', function(e){
		
		var jEle = jQuery(e.target);
		var i = jEle.children().attr('class');
		var name = jEle.children().attr('icon');
		
		if(pagelayer_empty(name)){
			return false;
		}
		
		// Set the icon in this list
		row.find('.pagelayer-elp-icon-preview').html('<i class="'+i+'"></i><span class="pagelayer-elp-icon-name">'+name+'</span>');
		row.find('.pagelayer-elp-icon-selector').slideUp();
		
		_pagelayer_set_atts(row, i);// Save and Render
		
		row.find('.pagelayer-elp-icon-close').show();
		return false;
		
	});
	
	// Delete the icon
	row.find('.pagelayer-elp-icon-close').on('click', function(){
		
		// Set the icon in this list
		row.find('.pagelayer-elp-icon-preview').html('<i class=""></i><span class="pagelayer-elp-icon-name">'+pagelayer_l('choose_icon')+'</span>');
		
		// Save and Render
		_pagelayer_set_atts(row, '');
		jQuery(this).hide();
		return false;
	});
	
}

// The Access Property
function pagelayer_elp_access(row, prop){
	
	var div = '<div class="pagelayer-elp-access-div">'+
		'<span class="pagelayer-elp-access"><i class="pli pli-caret-right" ></i></span>'+
		'<div class="pagelayer-elp-access-holder"></div>'+
	'</div>';
	
	row.append(div);
	
	var holder = row.find('.pagelayer-elp-access-holder');
	
	row.find('.pagelayer-elp-access').on('click', function(){
		
		// Setup first
		if(holder.children().length < 1){
			var p = row.parent().find('[pagelayer-access-item='+prop.show_group+']').detach();
			p.appendTo(holder);
			p.addClass('pagelayer-access-item-visible');
		}
		
		// Show and hide
		if(holder.is(':visible')){
			holder.hide();
			row.find('.pli-caret-right').removeClass('pli-caret-open');
		}else{
			holder.show();
			row.find('.pli-caret-right').addClass('pli-caret-open');
		}
	});
	
};

// The Modal Property
function pagelayer_elp_modal(row, prop){
	
	var style = pagelayer_empty(prop.width) ? '' : 'style="width:'+prop.width+'"';
	
	var div = '<div class="pagelayer-elp-modal-div">'+
		'<span class="pagelayer-elp-modal"><i class="pli pli-window" ></i></span>'+
		'<div class="pagelayer-elp-modal-wrapper">'+
			'<div class="pagelayer-elp-modal-wrap" '+style+'>'+
				'<div class="pagelayer-elp-modal-header">'+
					prop.label +'<i class="pagelayer-elp-modal-close pli pli-cross" aria-hidden="true"></i>'+
				'</div><hr>'+
				'<div class="pagelayer-elp-modal-holder"></div>'+
			'</div>'+
		'</div>'+
	'</div>';
	
	row.append(div);
	
	var wrapper = row.find('.pagelayer-elp-modal-wrapper');
	var holder = row.find('.pagelayer-elp-modal-holder');
	
	row.find('.pagelayer-elp-modal').on('click', function(){
		
		// Setup first
		if(holder.children().length < 1){
			
			var p = row.parent().find('[pagelayer-access-item='+prop.show_group+']').detach();
			p.appendTo(holder);
			p.addClass('pagelayer-access-item-visible');
		}
		
		// Show and hide
		wrapper.show();
		
	});
	
	// Close Modal Property
	row.find('.pagelayer-elp-modal-close').on('click', function(){
		wrapper.hide();
	});
	
	// On click Pagelayer setting icon
	wrapper.on('click', function(event){
		var target = jQuery(event.target);
		
		if(target.closest('.pagelayer-elp-modal-wrap').length > 0){
			return;
		}
		
		wrapper.hide();
	});
  
};

// The Color Property
function pagelayer_elp_color(row, prop){
	
	var val = prop.c['val'];
	var is_global = pagelayer_is_global_color(val);
	var global_active = '';
	
	// If global color not exist
	if(!pagelayer_empty(is_global)){
		val = pagelayer_global_colors[is_global]['value'];
		global_active = 'pagelayer-active-global';
	}
	
	var div = '<div class="pagelayer-elp-color-div-holder">'+
		'<div class="pagelayer-elp-color-global '+global_active+'"></div>'+
		'<div class="pagelayer-elp-color-div">'+
			'<div class="pagelayer-elp-color-preview"></div>'+
			'<span class="pagelayer-elp-remove-color"><i class="pli pli-cross" ></i></span>'+
		'</div>'+
		'<div class="pagelayer-global-color-list">'+
			'<div class="pagelayer-global-setting-color">'+
				'<b>Global Colors</b><span class="pli pli-service"></span>'+
			'</div>';
			
			for( cid in pagelayer_global_colors ){
				
				var color = pagelayer_global_colors[cid];
				var active_class = '';
				
				if(cid == is_global){
					active_class = 'pagelayer-global-selected';
				}

				div += '<div class="pagelayer-global-color-list-item '+ active_class +'" data-global-id="'+ cid +'">'+
					'<span class="pagelayer-global-color-pre" style="background:'+ color['value'] +'"></span>'+
					'<span class="pagelayer-global-color-title">'+ color['title'] +'</span>'+
					'<span class="pagelayer-global-color-code">'+  color['value'] +'</span>'+
				'</div>';
			}
	 div += '</div></div>';
	
	row.append(div);
	
	row.find('.pagelayer-elp-color-preview').css('background', val);
	
	var picker = new pagelayer_Picker({
		parent : row.find('.pagelayer-elp-color-div')[0],
		popup : 'left',
		color : val,
		doc: window.parent.document
	});
	
	var preview = row.find('.pagelayer-elp-color-preview');
	
	// If no val, then set blank
	if(pagelayer_empty(val)){
		preview.addClass('pagelayer-blank-preview');
	}
	
	var handle_white = function(col){	
		if(col.charAt(1) == 'f'){
			preview.addClass('pagelayer-white-border');
		}else{
			preview.removeClass('pagelayer-white-border');
		}
	}
	
	handle_white(val);
	
	// Handle selected color
	picker.onChange = function(color) {		
		preview.removeClass('pagelayer-blank-preview').css('background', color.rgbaString);
		handle_white(color.hex);
		_pagelayer_set_atts(row, color.hex);// Save and Render
		
		// Remove global
		row.find('.pagelayer-elp-color-global').removeClass('pagelayer-active-global');
		row.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		row.find('.pagelayer-global-color-list').hide();
	};
	
	picker.onOpen = picker.onChange;
	
	row.find('.pagelayer-elp-remove-color').on('click', function(event){
		event.stopPropagation();
		picker.setColor(prop.default, true);
		preview.addClass('pagelayer-blank-preview');		
		handle_white('');
		_pagelayer_set_atts(row, ' ');// Save and Render
	});
	
	// Handle for global color
	row.find('.pagelayer-elp-color-global').on('click', function(e){
		row.find('.pagelayer-global-color-list').slideToggle();
	});

	row.find('.pagelayer-global-setting-color').on('click', function(e){
		e.stopPropagation();

		if(jQuery(e.target).closest('.pli-service').length < 1){
			return;
		}
		window.open(pagelayer_customizer_url+'&autofocus%5Bsection%5D=pagelayer_global_colors_sec', '_blank');
	});
		
	// Handle for global color
	row.find('.pagelayer-global-color-list-item ').on('click', function(e){
		e.stopPropagation();
		
		var listItem = jQuery(this);
		var globalID = listItem.data('global-id');
		var listHolder = row.find('.pagelayer-global-color-list');
		
		// Remove previous selecttion
		listHolder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		listItem.addClass('pagelayer-global-selected');
		row.find('.pagelayer-elp-color-global').addClass('pagelayer-active-global');
		listHolder.slideUp();
				
		var color = pagelayer_global_colors[globalID]['value'];
		preview.removeClass('pagelayer-blank-preview').css('background', color);
		handle_white(color);
		_pagelayer_set_atts(row, '$'+globalID);// Save and Render
		
	});
}

// The Spinner property
function pagelayer_elp_spinner(row, prop){
	
	var div = '<div class="pagelayer-elp-spinner-div">'+
				'<input type="number" class="pagelayer-elp-spinner" name="'+prop.c['name']+'"'+
				' min="'+prop['min']+'" max="'+prop['max']+'" step="'+prop['step']+'" value="'+parseFloat(prop.c['val'])+'"/>'+
			'</div>';
			
	row.append(div);
	
	row.find('input').on('input', function(){
		var value = parseFloat(this.value);
		var max = parseFloat(this.max);
		
		if(!pagelayer_empty(max) && value > max){
			value = max;
		}
		_pagelayer_set_atts(row, value);// Save and Render
	});
	
}

// The Group Property
function pagelayer_elp_group(row, prop){
	
	var btnHidden = '';
	
	// Hide button, clone and delete
	if(!pagelayer_empty(prop['hide'])){
		btnHidden = 'pagelayer-hidden';
	}
	
	// Remove the pagelayer-show-tab
	row.removeAttr('pagelayer-show-tab');
	
	var div = '<div class="pagelayer-elp-group-div"></div>'+
			'<center><button class="pagelayer-elp-button '+btnHidden+'">'+prop['text']+'</button></center>';
	
	row.append(div);
	
	// Add button
	var add_item = function(row){
		
		var ele_id = row.closest('[pagelayer-element-id]').attr('pagelayer-element-id') || '';
		var pEle = jQuery('[pagelayer-id="'+ele_id+'"]');
		
		// First add the element inside the group element
		var id = pagelayer_element_add_child(pEle, prop['sc'], prop);
		//pagelayer_element_setup('[pagelayer-id='+id+']', true);
		
		show_item(id);
	
	};
	
	// Show the properties of the existing things
	var show_item = function(id, sel){
		
		// To append after an existing item
		sel = sel || false;
		
		// If pagelayer id empty then return
		if(pagelayer_empty(id)){
			return false;
		}
		
		// Since the element is added very fast, we reselect via jQuery for it to re-access the dom
		jEle = jQuery('[pagelayer-id="'+id+'"]');
		
		var label_param = prop['item_label']['param'] || '';
		var title = pagelayer_get_att(jEle, label_param) || prop['item_label']['default'];
		
		// We need to get the correct value for select based params which are the label
		var child_props = pagelayer_shortcodes[prop.sc];
		for(var section in child_props){
			for(var _param in child_props[section]){
				if(child_props[section][_param]['type'] == 'select'){
					if(title in child_props[section][_param]['list']){
						title = child_props[section][_param]['list'][title];
					}
				}
			}
		}
		
		// Create the HTML
		var holder = jQuery('<div class="pagelayer-elp-group-item" pagelayer-group-item-id="'+id+'">'+
				'<div class="pagelayer-elp-group-item-head">'+
					'<span class="pagelayer-elp-group-item-drag"><i class="pli pli-menu" ></i></span>'+
					'<span class="pagelayer-elp-group-item-title">'+title+'</span>'+
					'<span class="pagelayer-elp-group-item-clone '+btnHidden+'"><i class="pli pli-clone" ></i></span>'+
					'<span class="pagelayer-elp-group-item-del '+btnHidden+'"><i class="pli pli-trashcan" ></i></span>'+
				'</div>'+
				'<div class="pagelayer-elp-group-item-body"></div>'+
			'</div>');
		
		// Append to the row
		if(sel){
			row.find(sel).after(holder);
		}else{
			row.find('.pagelayer-elp-group-div').first().append(holder);
		}
		
		// Setup the toggle
		holder.find('.pagelayer-elp-group-item-title').first().on('click', function(){
			var rEle = holder.find('.pagelayer-elp-group-item-body').first();
			var r_id = holder.attr('pagelayer-group-item-id');
			
			// If the props are not already setup
			if(rEle.html().length < 1){
			
				pagelayer_elpd_generate(jQuery('[pagelayer-id="'+r_id+'"]'), rEle);
				
				// Change the group item title
				var tmp_title = holder.find('[pagelayer-elp-name="'+label_param+'"] [name="'+label_param+'"]');
		
				if(tmp_title.length > 0){
					jQuery(tmp_title).on('input', function(){						
						holder.find('.pagelayer-elp-group-item-title').html(tmp_title.val());
					});
				}
				
			}
			
			rEle.toggle();
		});
		
		// Clone the item
		holder.find('.pagelayer-elp-group-item-head .pli-clone').on('click', function(){
			
			// If the element have any parent
			var jEle = jQuery('[pagelayer-id="'+id+'"]');
			var par = pagelayer_get_parent(jEle);
			var clone_ele = pagelayer_copy_element(jEle);
			//console.log(clone_ele);console.log('[pagelayer-group-item-id="'+id+'"]');
			show_item(clone_ele, '[pagelayer-group-item-id="'+id+'"]');
			
			if(par){
				pagelayer_sc_render(pagelayer_ele_by_id(par));
			}
		});
		
		// Delete the item
		holder.find('.pagelayer-elp-group-item-head .pli-trashcan').on('click', function(){
			
			// If the element have any parent
			var jEle = jQuery('[pagelayer-id="'+id+'"]');
			var par = pagelayer_get_parent(jEle);
			holder.remove();
			pagelayer_delete_element(jEle);
			
			if(par){
				pagelayer_sc_render(pagelayer_ele_by_id(par));
			}
		});
		
	};
		
	// Setup the drag
	pagelayer.$$(".pagelayer-elp-group-div").sortable({
		axis: 'y',
		nested : false,
		vertical : true,
		handle : ".pagelayer-elp-group-item-drag",
		placeholder: "pagelayer-drag-highlight",
		start : function(event, ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);
		},
		stop : function(event, ui){
			var end_pos = ui.item.index();
			var id = jQuery(ui.item).closest('[pagelayer-group-item-id]').attr('pagelayer-group-item-id');
			var jEle = jQuery('[pagelayer-id="'+id+'"]');
			pagelayer_moving_element(jEle, ui.item.data('start_pos'), end_pos);
			var par = pagelayer_get_parent(jEle);				
			if(par){
				pagelayer_sc_render(pagelayer_ele_by_id(par));
			}
		}
	});
	
	// Handle click of the group
	row.find('.pagelayer-elp-button').on('click', function(){
		if('pro' in prop && pagelayer_empty(pagelayer_pro)){
			pagelayer_pro_notice();
			return;
		}
		add_item(row);
	});
	
	// Find the existing items
	prop.el.$.find('[pagelayer-parent="'+prop.el['id']+'"]').each(function(){
		var jEle = jQuery(this);
		var id = pagelayer_assign_id(jEle);
		show_item(id);
	});
};

function pagelayer_pro_notice(){
	var div = pagelayer.$$('.pagelayer-pro-notice');
	
	div.find('.pagelayer-pro-x').click(function(){
		div.hide();
	});
	
	div.show();
}

// Moving an element
function pagelayer_moving_element(jEle, start_pos, end_pos){	
	if(start_pos==end_pos){
		return;
	}
	
	var id = pagelayer_assign_id(jEle);

	// Is there a wrap
	var wrap = pagelayer_wrap_by_id(id);

	var par = wrap.parent();
	var children = par.children("div");	
	
  // This is required for Owl Carousel
	if(children.length==1){
		par = par.parent();
		children = par.children("div");
	}
	
	var element = children.eq(start_pos).detach();
	if(end_pos < start_pos){
		children.eq(end_pos).before(element);
	}else{
		children.eq(end_pos).after(element);
	}		
}

// The Datetime Property
function pagelayer_elp_datetime(row, prop){
		
	var div = '<div class="pagelayer-elp-datetime-div">'+
				'<input type="date" class="pagelayer-elp-datetime" name="'+prop.c['name']+'" value="'+prop.c['val']+'" />'+
        '</div>';
	
	row.append(div);
	
	row.find('.pagelayer-elp-datetime').on('change', function(){
		_pagelayer_set_atts(row, jQuery(this).val());// Save and Render
	});
	
};

// The padding property
function pagelayer_elp_padding(row, prop){
	var val = ['', '', '', ''];
	
	if(!pagelayer_empty(prop.c['val'])){
		val = prop.c['val'];
		if(pagelayer_is_string(val)){
			val = val.split(',');
		}
	}
	
	var div = '<div class="pagelayer-elp-padding-div">'+
				'<input type="number" class="pagelayer-elp-padding" value="'+parseFloat(val[0])+'"></input>'+
				'<input type="number" class="pagelayer-elp-padding" value="'+parseFloat(val[1])+'"></input>'+
				'<input type="number" class="pagelayer-elp-padding" value="'+parseFloat(val[2])+'"></input>'+
				'<input type="number" class="pagelayer-elp-padding" value="'+parseFloat(val[3])+'"></input>'+
				'<i class="pli pli-link" ></i>'+
			'</div>';
	
	row.append(div);
	
	// Is the value linked ?
	var link = row.find('.pagelayer-elp-padding-div i');
	var isLinked = 1;
	//isLinked = isLinked == 2 ? false : true;
	//console.log(isLinked);
	var tmp_val = val[0];
	
	for(var p_val in val){

		// Check if unlinked
		if(tmp_val != val[p_val] ){
			isLinked = 0;
		}
		tmp_val = val[p_val];
	}
	
	if(isLinked){
		link.addClass('pagelayer-elp-padding-linked');
	}else{
		link.removeClass('pagelayer-elp-padding-linked');
	}
	
	// Handle link on click
	link.on('click', function(){
		
		var linked = link.hasClass('pagelayer-elp-padding-linked');
		
		if(linked){
			link.removeClass('pagelayer-elp-padding-linked');
		}else{
			link.addClass('pagelayer-elp-padding-linked');
		}
		
	});
	
	row.find('input').on('input', function(){
		
		// Are the values linked
		var linked = row.find('.pagelayer-elp-padding-div .pli').hasClass('pagelayer-elp-padding-linked');
		
		if(linked){
			var val = jQuery(this).val();
			row.find('input').each(function(){
				jQuery(this).val(val);
			});
		}
		
		var vals = [];
		
		// Get all values
		row.find('input').each(function(){
			var val = jQuery(this).val();
			vals.push(val ? val : 0);
		});
		
		_pagelayer_set_atts(row, vals);// Save and Render
	});
	
};

// The shadow property
function pagelayer_elp_shadow(row, prop){
	
	var val =['','','',''];
	
	// Do we have a val ?
	if(!pagelayer_empty(prop.c['val'])){
		val = prop.c['val'];
		if(pagelayer_is_string(val)){
			val = val.split(',');
		}
	}
	
	//var val = {color: '', blur: '', horizontal: '', vertical: ''};
	
	var div = '<span class="pagelayer-prop-edit"><i class="pli pli-pencil"></i></span>'+
		'<div class="pagelayer-elp-shadow-div">'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-horizontal">'+
			'<label class="pagelayer-elp-label">Horizontal</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="-100" step="1" class="pagelayer-elp-shadow-blur" value="'+val[0]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-vertical">'+
			'<label class="pagelayer-elp-label">Vertical</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="-100" step="1" class="pagelayer-elp-shadow-blur" value="'+val[1]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-blur">'+
			'<label class="pagelayer-elp-label">Blur</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="0" step="1" class="pagelayer-elp-shadow-blur" value="'+val[2]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-color">'+
			'<label class="pagelayer-elp-label">Color</label>'+
			'<div class="pagelayer-elp-color-div">'+
				'<div class="pagelayer-elp-color-preview"></div>'+
				'<span class="pagelayer-elp-remove-color"><i class="pli pli-cross" ></i></span>'+
			'</div>'+
		'</div>'+
	'</div>';
			
	row.append(div);
	
	row.find('.pagelayer-prop-edit').on('click', function(){
		row.find('.pagelayer-elp-shadow-div').toggleClass('pagelayer-prop-show');
	});
	
	var preview = row.find('.pagelayer-elp-color-preview');	
	preview.css('background', val[3]);
	
	var picker = new pagelayer_Picker({
		parent : row.find('.pagelayer-elp-color-div')[0],
		popup : 'left',
		color : val[3],
		doc: window.parent.document
	});
	
	// If no val, then set blank
	if(pagelayer_empty(val[3])){
		preview.addClass('pagelayer-blank-preview');
	}
	
	var handle_white = function(col){	
		if(col.charAt(1) == 'f'){
			preview.addClass('pagelayer-white-border');
		}else{
			preview.removeClass('pagelayer-white-border');
		}
	}
	
	handle_white(val[3]);
	
	// Handle selected color
	picker.onChange = function(color) {
		preview.removeClass('pagelayer-blank-preview').css('background', color.rgbaString);
		handle_white(color.hex);
		val[3] = (color.hex ? color.hex : '');
		_pagelayer_set_atts(row, val);
	};
	
	// Remove Color
	row.find('.pagelayer-elp-remove-color').on('click', function(event){
		event.stopPropagation();
		picker.setColor(prop.default, true);
		preview.addClass('pagelayer-blank-preview');		
		handle_white('');
		val[3] = '';
		_pagelayer_set_atts(row, val);
	});
	
	row.find('input').on('input', function(){
		var i = 0;
		row.find('.pagelayer-elp-shadow-input').each(function(){
			var value = jQuery(this).val();
			val[i] = (value ? value : '');
			i++;
		});
		_pagelayer_set_atts(row, val);
	});
	
}

// The box shadow property
function pagelayer_elp_box_shadow(row, prop){
	
	var val = ['','','','','',''];
	
	// Do we have a val ?
	if(!pagelayer_empty(prop.c['val'])){
		val = prop.c['val'];
		if(pagelayer_is_string(val)){
			val = val.split(',');
		}
	}
	
	var val_pos = ['horizontal','vertical','blur','color','spread','inset'];
	
	var div = '<span class="pagelayer-prop-edit"><i class="pli pli-pencil"></i></span>'+
		'<div class="pagelayer-elp-shadow-div">'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-horizontal">'+
			'<label class="pagelayer-elp-label">Horizontal</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="-100" step="1" class="pagelayer-elp-shadow-blur" name="horizontal" value="'+val[0]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-vertical">'+
			'<label class="pagelayer-elp-label">Vertical</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="-100" step="1" class="pagelayer-elp-shadow-blur" name="vertical" value="'+val[1]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-blur">'+
			'<label class="pagelayer-elp-label">Blur</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="0" step="1" class="pagelayer-elp-shadow-blur" name="blur" value="'+val[2]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-spread">'+
			'<label class="pagelayer-elp-label">Spread</label>'+
			'<input class="pagelayer-elp-shadow-input" type="number" max="100" min="0" step="1" class="pagelayer-elp-shadow-spread" name="spread" value="'+(val[4] ? val[4] : 0 )+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-color">'+
			'<label class="pagelayer-elp-label">Color</label>'+
			'<div class="pagelayer-elp-color-div">'+
				'<div class="pagelayer-elp-color-preview"></div>'+
				'<span class="pagelayer-elp-remove-color"><i class="pli pli-cross" ></i></span>'+
			'</div>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-shadow-inset">'+
			'<label class="pagelayer-elp-label">Shadow</label>'+
			'<select class="pagelayer-elp-shadow-input pagelayer-elp-select" name="inset" type="checkbox" class="pagelayer-elp-shadow-inset">'+
				'<option value="">Outset</option>'+
				'<option value="inset"'+(pagelayer_empty(val[5]) ? '' : ' selected' )+'>Inset</option>'+
			'</select>'+
		'</div>'+
	'</div>';
			
	row.append(div);
	
	row.find('.pagelayer-prop-edit').on('click', function(){
		row.find('.pagelayer-elp-shadow-div').toggleClass('pagelayer-prop-show');
	});
	
	var preview = row.find('.pagelayer-elp-color-preview');	
	preview.css('background', val[3]);
	
	var picker = new pagelayer_Picker({
		parent : row.find('.pagelayer-elp-color-div')[0],
		popup : 'left',
		color : val[3],
		doc: window.parent.document
	});
	
	// If no val, then set blank
	if(pagelayer_empty(val[3])){
		preview.addClass('pagelayer-blank-preview');
	}
	
	var handle_white = function(col){	
		if(col.charAt(1) == 'f'){
			preview.addClass('pagelayer-white-border');
		}else{
			preview.removeClass('pagelayer-white-border');
		}
	}
	
	handle_white(val[3]);
	
	// Handle selected color
	picker.onChange = function(color) {
		row.find('.pagelayer-elp-color-preview').removeClass('pagelayer-blank-preview').css('background', color.rgbaString);
		handle_white(color.hex);
		val[3] = (color.hex ? color.hex : '');
		_pagelayer_set_atts(row, val);
	};
	
	// Remove Color
	row.find('.pagelayer-elp-remove-color').on('click', function(event){
		event.stopPropagation();
		picker.setColor(prop.default, true);
		preview.addClass('pagelayer-blank-preview');		
		handle_white('');
		val[3] = '';
		_pagelayer_set_atts(row, val);
	});
	
	// Onchange set props
	row.find('.pagelayer-elp-shadow-input').on('input change', function(){
		//var i = 0;
		row.find('.pagelayer-elp-shadow-input').each(function(){
			var value = jQuery(this).val();
			var name = jQuery(this).attr('name');
			val[val_pos.indexOf(name)] = (value ? value : '');
			//i++;
		});
		_pagelayer_set_atts(row, val);
	});
	
}

// The filter property
function pagelayer_elp_filter(row, prop){
	
	var val = [0,100,100,0,0,100,100];
	
	// Do we have a val ?
	if(!pagelayer_empty(prop.c['val'])){
		val = prop.c['val'];
		if(pagelayer_is_string(val)){
			val = val.split(',');
		}
	}
	
	var filters = [['blur','10','0.1'],['brightness','200','1'],['contrast','200','1'],['grayscale','200','1'],['hue','360','1'],['opacity','100','1'],['saturate','200','1']];
	
	var div = '<span class="pagelayer-prop-edit"><i class="pli pli-pencil"></i></span>'+
		'<div class="pagelayer-elp-filter-div">';
		
		jQuery.each(val,function(key, value){
			div += '<div class="pagelayer-elp-prop-grp pagelayer-elp-filter-'+filters[key][0]+'">'+
				'<label class="pagelayer-elp-label">'+filters[key][0]+'</label>'+
				'<input class="pagelayer-elp-slider pagelayer-elp-filter-input" type="range" max="'+filters[key][1]+'" min="0" step="'+filters[key][2]+'" class="pagelayer-elp-filter-'+filters[key][0]+'" value="'+value+'"></input>'+
				'<span class="pagelayer-elp-filter-val">'+value+'</span>'+
			'</div>';
		});
		
	div += '</div>';
			
	row.append(div);
	
	row.find('.pagelayer-prop-edit').on('click', function(){
		row.find('.pagelayer-elp-filter-div').toggleClass('pagelayer-prop-show');
	});
	
	row.find('input').on('input', function(){
		var val = [];
		jQuery(this).parent().find('span').html(this.value);
		row.find('.pagelayer-elp-filter-input').each(function(){
			var value = jQuery(this).val();
			val.push(value ? value : 'none');
		});
		_pagelayer_set_atts(row, val);
	});
	
}

// The gradient property
function pagelayer_elp_gradient(row, prop){
	
	var val = ['','','','','','',''];
	
	// Do we have a val ?
	if(!pagelayer_empty(prop.c['val'])){
		val = prop.c['val'];
		if(pagelayer_is_string(val)){
			val = val.split(',');
		}
	}
	
	var setColor = [val[1], val[3], val[5]];
	
	//var val = {color: '', blur: '', horizontal: '', vertical: ''};
	var getColorList = function(num){
  
		var is_global = pagelayer_is_global_color(setColor[num]);
		var global_list = '<div class="pagelayer-global-color-list">'+
		'<div class="pagelayer-global-setting-color">'+
			'<b>Global Colors</b><span class="pli pli-service"></span>'+
		'</div>';
    
		for( cid in pagelayer_global_colors ){
			
			var color = pagelayer_global_colors[cid];
			var active_class = '';
			
			if(cid == is_global){
				active_class = 'pagelayer-global-selected';
			}
			
			// If global color not exist
			if(!pagelayer_empty(is_global)){
				setColor[num] = pagelayer_global_colors[is_global]['value'];
			}
			
			global_list += '<div class="pagelayer-global-color-list-item '+ active_class +'" data-global-id="'+ cid +'">'+
				'<span class="pagelayer-global-color-pre" style="background:'+ color['value'] +'"></span>'+
				'<span class="pagelayer-global-color-title">'+ color['title'] +'</span>'+
				'<span class="pagelayer-global-color-code">'+  color['value'] +'</span>'+
			'</div>';
		}
		global_list += '</div>';
		
		return global_list;
	}
	
	var div = '<div class="pagelayer-elp-gradient-div">'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-angle">'+
			'<label class="pagelayer-elp-label">Angle</label>'+
			'<input class="pagelayer-elp-gradient-input" type="number" max="360" min="0" step="1" class="pagelayer-elp-gradient-angle" value="'+val[0]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-color">'+
			'<label class="pagelayer-elp-label">Color 1</label>'+
			'<div class="pagelayer-elp-color-div-holder">'+
				'<div class="pagelayer-elp-color-global"></div>'+
				'<div class="pagelayer-elp-color-div">'+
					'<div class="pagelayer-elp-gradient-color1 pagelayer-elp-color-preview"></div>'+
				'</div>'+
				getColorList(0)+
			'</div>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-per1">'+
			'<label class="pagelayer-elp-label">Percentage 1</label>'+
			'<input class="pagelayer-elp-gradient-input" type="number" max="100" min="-100" step="1" class="pagelayer-elp-gradient-per1" value="'+val[2]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-color">'+
			'<label class="pagelayer-elp-label">Color 2</label>'+
			'<div class="pagelayer-elp-color-div-holder">'+
				'<div class="pagelayer-elp-color-global"></div>'+
				'<div class="pagelayer-elp-color-div">'+
					'<div class="pagelayer-elp-gradient-color2 pagelayer-elp-color-preview"></div>'+
				'</div>'+
				getColorList(1)+
			'</div>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-per2">'+
			'<label class="pagelayer-elp-label">Percentage 2</label>'+
			'<input class="pagelayer-elp-gradient-input" type="number" max="100" min="0" step="1" class="pagelayer-elp-gradient-per2" value="'+val[4]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-color">'+
			'<label class="pagelayer-elp-label">Color 3</label>'+
			'<div class="pagelayer-elp-color-div-holder">'+
				'<div class="pagelayer-elp-color-global"></div>'+
				'<div class="pagelayer-elp-color-div">'+
					'<div class="pagelayer-elp-gradient-color3 pagelayer-elp-color-preview"></div>'+
				'</div>'+
				getColorList(2)+
			'</div>'+
		'</div>'+
		'<div class="pagelayer-elp-prop-grp pagelayer-elp-gradient-per3">'+
			'<label class="pagelayer-elp-label">Percentage 3</label>'+
			'<input class="pagelayer-elp-gradient-input" type="number" max="100" min="0" step="1" class="pagelayer-elp-gradient-per3" value="'+val[6]+'"></input>'+
		'</div>'+
	'</div>';
			
	row.append(div);
	var i = 0;
	row.find('.pagelayer-elp-color-preview').each(function(){
		jQuery(this).css('background', setColor[i]);
		i++;
	});
	
	// Remove global
	var removeGlobal = function(holder){
		holder.find('.pagelayer-elp-color-global').removeClass('pagelayer-active-global');
		holder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		holder.find('.pagelayer-global-color-list').hide();
	}
	
	var picker1 = new pagelayer_Picker({
		parent : row.find('.pagelayer-elp-gradient-color1')[0],
		popup : 'left',
		color : setColor[0],
		doc: window.parent.document
	});
	
	// Handle selected color
	picker1.onChange = function(color) {
		var cPreview = row.find('.pagelayer-elp-gradient-color1')
		cPreview.css('background', color.rgbaString);
		val[1] = (color.hex ? color.hex : '');
		_pagelayer_set_atts(row, val);
		
		removeGlobal(cPreview.closest('.pagelayer-elp-color-div-holder'));
	};
	
	var picker2 = new pagelayer_Picker({
		parent : row.find('.pagelayer-elp-gradient-color2')[0],
		popup : 'left',
		color : setColor[1],
		doc: window.parent.document
	});
	
	// Handle selected color
	picker2.onChange = function(color) {
		var cPreview = row.find('.pagelayer-elp-gradient-color2');
		cPreview.css('background', color.rgbaString);
		val[3] = (color.hex ? color.hex : '');
		_pagelayer_set_atts(row, val);
		
		removeGlobal(cPreview.closest('.pagelayer-elp-color-div-holder'));
	};
	
	var picker3 = new pagelayer_Picker({
		parent : row.find('.pagelayer-elp-gradient-color3')[0],
		popup : 'left',
		color : setColor[2],
		doc: window.parent.document
	});
	
	// Handle selected color
	picker3.onChange = function(color) {
		var cPreview = row.find('.pagelayer-elp-gradient-color3');
		cPreview.css('background', color.rgbaString);
		val[5] = (color.hex ? color.hex : '');
		_pagelayer_set_atts(row, val);
		
		removeGlobal(cPreview.closest('.pagelayer-elp-color-div-holder'));
	};
	
	row.find('input').on('input', function(){
		var i = 0;
		row.find('.pagelayer-elp-gradient-input').each(function(){
			var value = jQuery(this).val();
			val[i] = (value ? value : '');
			i = i+2;
		});
		_pagelayer_set_atts(row, val);
	});
	
	row.find('.pagelayer-global-selected').each(function(){
		jQuery(this).closest('.pagelayer-elp-color-div-holder').find('.pagelayer-elp-color-global').addClass('pagelayer-active-global');
	});
	
	// Handle for global color
	row.find('.pagelayer-elp-color-global').on('click', function(e){
		jQuery(this).closest('.pagelayer-elp-color-div-holder').find('.pagelayer-global-color-list').slideToggle();
	});

	row.find('.pagelayer-global-setting-color').on('click', function(e){
		e.stopPropagation();

		if(jQuery(e.target).closest('.pli-service').length < 1){
			return;
		}
		
		window.open( pagelayer_customizer_url + '&autofocus%5Bsection%5D=pagelayer_global_colors_sec', '_blank' );
	});
		
	// Handle for global color
	row.find('.pagelayer-global-color-list-item ').on('click', function(e){
		e.stopPropagation();
		
		var listItem = jQuery(this);
		var globalID = listItem.data('global-id');
		var listHolder = listItem.closest('.pagelayer-global-color-list');
		var colorHolder = listItem.closest('.pagelayer-elp-color-div-holder');
		var colorPreview = colorHolder.find('.pagelayer-elp-color-preview');
		
		// Remove previous selecttion
		listHolder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		listItem.addClass('pagelayer-global-selected');
		colorHolder.find('.pagelayer-elp-color-global').addClass('pagelayer-active-global');
		listHolder.slideUp();
				
		var color = pagelayer_global_colors[globalID]['value'];
		colorPreview.removeClass('pagelayer-blank-preview').css('background', color);
		
		var i = 1;
		
		if(colorPreview.hasClass('pagelayer-elp-gradient-color2')){
			i = 3;
		}
		
		if(colorPreview.hasClass('pagelayer-elp-gradient-color3')){
			i = 5;
		}
		
		val[i] = '$'+globalID;
		_pagelayer_set_atts(row, val);// Save and Render
		
	});
	
}

function pagelayer_elp_font_family(row, prop){
	
	var options = '';
	var option = function(val, lang, type){
		var selected = (val != prop.c['val']) ? '' : 'selected="selected"';
		var lang = pagelayer_empty(lang) ? 'Default' : lang;
		return '<option class="pagelayer-elp-select-option" value="'+val+'" type="'+type+'" '+selected+'>'+lang+'</option>';
	}

	for(y in pagelayer_fonts){
		if(y != 'default'){
			options += '<optgroup label="'+pagelayer_ucwords(y)+'">';
		}
		for (x in pagelayer_fonts[y]){
			options += option((jQuery.isNumeric(x) ? pagelayer_fonts[y][x] : x), pagelayer_fonts[y][x], y);
		}		
	}
	
	var div = '<div class="pagelayer-elp-select-div pagelayer-elp-pos-rel">'+
				'<select class="pagelayer-elp-select pagelayer-select" name="'+prop.c['name']+'">'+options+'</select>'+
  '</div>';
			
	row.append(div);
	
	row.find('select').on('change', function(){
		
		var sEle = jQuery(this);
		
		pagelayer_link_font_family(sEle);
		_pagelayer_set_atts(row, sEle.val());// Save and Render		
	
	});
	
}

// The typography property
function pagelayer_elp_typography(row, prop){
	
	var val = pagelayer_parse_typo(prop.c['val'], true);
	var is_typo = pagelayer_is_global_typo(prop.c['val']);
	var global_active = '';
	var save_timer = {};
	
	// Load value of tablet and mobile
	var val_tablet = pagelayer_get_att(prop.el.$, prop.c['name']+'_tablet');
	var val_mobile = pagelayer_get_att(prop.el.$, prop.c['name']+'_mobile');
	
	val_tablet = pagelayer_parse_typo(val_tablet);
	val_mobile = pagelayer_parse_typo(val_mobile);
	
	
	// If global color not exist
	if(!pagelayer_empty(is_typo)){
		global_active = 'pagelayer-active-global';
	}
	
	var select = {
		'style' : {'' : 'Default', 'normal' : 'Normal', 'italic' : 'Italic', 'oblique' : 'Oblique'},
		'weight' : {'' : 'Default', '100' : '100', '200' : '200', '300' : '300', '400' : '400', '500' : '500', '600' : '600', '700' : '700', '800' : '800', '900' : '900', 'normal' : 'Normal', 'lighter' : 'Lighter', 'bold' : 'Bold', 'bolder' :'Bolder', 'unset' : 'Unset'},
		'variant' : {'' : 'Default', 'normal' : 'Normal', 'small-caps' : 'Small Caps'},
		'deco-line' : {'' : 'Default', 'none' : 'None', 'overline' : 'Overline', 'line-through' : 'Line Through', 'underline' : 'Underline', 'underline overline' : 'Underline Overline'},
		'deco-style' : {'' : 'Default', 'solid' : 'Solid', 'double' : 'Double', 'dotted' : 'Dotted', 'dashed' : 'Dashed', 'wavy' : 'Wavy'},
		'transform' : {'' : 'Default', 'capitalize' : 'Capitalize', 'uppercase' : 'Uppercase', 'lowercase' : 'Lowercase'},
		'fonts' : pagelayer_fonts,
	}
	
	var option = function(val, lang, setVal){
		var selected = (val.toLowerCase() != setVal.toLowerCase()) ? '' : 'selected="selected"';

		var lang = pagelayer_empty(lang) ? 'Default' : lang;
		return '<option value="'+val+'" '+selected+'>'+ lang +'</option>';
	}
	
	var font_options = '';
	var font_option = function(val, lang, type, setVal){
		var selected = (val != setVal) ? '' : 'selected="selected"';
		var lang = pagelayer_empty(lang) ? 'Default' : lang;
		return '<option class="pagelayer-elp-typo-sele-op" value="'+val+'" type="'+type+'" '+selected+'>'+lang+'</option>';
	}

	for(y in select['fonts']){
		if(y != 'default'){
			font_options += '<optgroup label="'+pagelayer_ucwords(y)+'">';
		}
		for (x in select['fonts'][y]){
			font_options += font_option((jQuery.isNumeric(x) ? select['fonts'][y][x] : x), select['fonts'][y][x], y, val[0]);
		}		
	}
	
	var modes = {desktop: '', tablet: '_tablet', mobile: '_mobile'};
	var mode = pagelayer_get_screen_mode();
	var screen = '<div class="pagelayer-elp-screen">'+
		'<i class="pli pli-desktop" ></i>'+
		'<i class="pli pli-tablet" ></i>'+
		'<i class="pli pli-mobile" ></i>'+
		'<i class="pagelayer-prop-screen pli pli-'+mode+'" ></i>'+
	'</div>';
	
	var div = '<span class="pagelayer-elp-typo-edit-div">'+
			'<i class="pli pli-pencil"></i>'+
		'</span>'+
		'<div class="pagelayer-elp-typo-div" pagelayer-screen-mode="'+mode+'">'+
		'<div class="pagelayer-elp-typo-fonts">'+
			'<div class="pagelayer-elp-global-typo">'+
				'<label class="pagelayer-elp-label">'+pagelayer_l('global_fonts')+'</label>'+
				'<span class="pagelayer-elp-typo-icons">'+
					'<span class="pagelayer-elp-global-icon '+global_active+'"></span>'+
					'<span class="pli pli-service"></span>'+
				'</span>'+
				'<div class="pagelayer-global-font-list">';

						for( cid in pagelayer_global_fonts ){
							
							var font = pagelayer_global_fonts[cid];

							div += '<div class="pagelayer-global-font-list-item" data-global-id="'+ cid +'">'+
								'<span class="pagelayer-global-font-title">'+font['title']+'</span>'+
							'</div>';
						}
				 div += '</div>'+
			'</div>'+
			'<div class="pagelayer-elp-typo pagelayer-elp-typo-family">'+
				'<label class="pagelayer-elp-label">'+pagelayer_l('font_family')+'</label>'+
				'<select class="pagelayer-elp-typo-input pagelayer-elp-select" name="font-family">'+font_options+'</select>'+
			'</div>';
	
	div += '<div class="pagelayer-elp-typo pagelayer-elp-typo-size">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('font_size')+' '+screen+'</label>'+
			'<input name="font-size"  pagelayer-show-device="desktop" class="pagelayer-elp-typo-input" type="number" max="200" min="0" step="1" value="'+val[1]+'"></input>'+
			'<input name="font-size_tablet"  pagelayer-show-device="tablet" class="pagelayer-elp-typo-input" type="number" max="200" min="0" step="1" value="'+val_tablet[1]+'"></input>'+
			'<input name="font-size_mobile" pagelayer-show-device="mobile" class="pagelayer-elp-typo-input" type="number" max="200" min="0" step="1" value="'+val_mobile[1]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-style">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('font_style')+'</label>'+
			'<select name="font-style" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	
	jQuery.each(select['style'],function(key, value){
		div += option(key, value, val[2]);
	});
			div +='</select>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-weight">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('font_weight')+' '+screen+'</label>'+
			'<select name="font-weight" pagelayer-show-device="desktop" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['weight'],function(key, value){
		div += option(key, value, val[3]);
	});
			
			div += '</select>'+
			'<select name="font-weight_tablet" pagelayer-show-device="tablet" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['weight'],function(key, value){
		div += option(key, value, val_tablet[3]);
	});
			
			div += '</select>'+
			'<select name="font-weight_mobile" pagelayer-show-device="mobile" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['weight'],function(key, value){
		div += option(key, value, val_mobile[3]);
	});
			
			div += '</select>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-variant">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('font_variant')+'</label>'+
			'<select name="font-variant" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['variant'],function(key, value){
		div += option(key, value, val[4]);
	});
				
			div += '</select>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-deco-line">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('decoration_line')+'</label>'+
			'<select name="text-decoration-line" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['deco-line'],function(key, value){
		div += option(key, value, val[5]);
	});
				
			div += '</select>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-deco-style">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('decoration_style')+'</label>'+
			'<select name="text-decoration-style" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['deco-style'],function(key, value){
		div += option(key, value, val[6]);
	});
				
			div += '</select>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-height">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('line_height')+' '+screen+'</label>'+
			'<input name="line-height" class="pagelayer-elp-typo-input" pagelayer-show-device="desktop" type="number" max="15" min="0" step="0.1" value="'+val[7]+'"></input>'+
			'<input name="line-height_tablet" pagelayer-show-device="tablet" class="pagelayer-elp-typo-input" type="number" max="15" min="0" step="0.1" value="'+val_tablet[7]+'"</input>'+
			'<input name="line-height_mobile" class="pagelayer-elp-typo-input" pagelayer-show-device="mobile" type="number" max="15" min="0" step="0.1" value="'+val_mobile[7]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-transform">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('text_transform')+'</label>'+
			'<select name="text-transform" class="pagelayer-elp-typo-input pagelayer-elp-select">';
	jQuery.each(select['transform'],function(key, value){
		div += option(key, value, val[8]);
	});
				
			div += '</select>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-lspacing">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('text_spacing')+' '+screen+'</label>'+
			'<input name="letter-spacing" pagelayer-show-device="desktop" class="pagelayer-elp-typo-input" type="number" max="10" min="-10" step="0.1" value="'+val[9]+'"></input>'+
			'<input name="letter-spacing_tablet" pagelayer-show-device="tablet" class="pagelayer-elp-typo-input" type="number" max="10" min="-10" step="0.1" value="'+val_tablet[9]+'"></input>'+
			'<input name="letter-spacing_mobile" pagelayer-show-device="mobile" class="pagelayer-elp-typo-input" type="number" max="10" min="-10" step="0.1" value="'+val_mobile[9]+'"></input>'+
		'</div>'+
		'<div class="pagelayer-elp-typo pagelayer-elp-typo-wspacing">'+
			'<label class="pagelayer-elp-label">'+pagelayer_l('word_spacing')+' '+screen+'</label>'+
			'<input name="word-spacing" pagelayer-show-device="desktop" class="pagelayer-elp-typo-input" type="number" max="50" min="0" step="1" value="'+val[10]+'"></input>'+
			'<input name="word-spacing_tablet" pagelayer-show-device="tablet" class="pagelayer-elp-typo-input" type="number" max="50" min="0" step="1" value="'+val_tablet[10]+'"></input>'+
			'<input name="word-spacing_mobile" pagelayer-show-device="mobile" class="pagelayer-elp-typo-input" type="number" max="50" min="0" step="1" value="'+val_mobile[10]+'"></input>'+
		'</div>'+
	'</div>'+
	'</div>';
			
	row.append(div);
	
	if(pagelayer_empty(val[5]) || val[5]=='none'){
		row.find('.pagelayer-elp-typo-deco-style').hide();
	}
	
	row.find('.pagelayer-elp-typo-edit-div .pli-pencil').on('click', function(){
		row.find('.pagelayer-elp-typo-div').toggleClass('pagelayer-prop-show');				
	});
	
	var save_typography = function(){
		var globalEle = row.find('.pagelayer-global-selected');
		var atts = {};
    
		atts[prop.c['name']] = {};
		atts[prop.c['name']+'_tablet'] = {};
		atts[prop.c['name']+'_mobile'] = {};
		
		if(globalEle.length > 0){
			atts[prop.c['name']]['global-font'] = globalEle.attr('data-global-id');
		}
		
		row.find('.pagelayer-elp-typo-input').each(function(){
			
			var iEle = jQuery(this);
			var name = iEle.attr('name');
			var value = iEle.val();
			var isGlobal = iEle.closest('[pagelayer-set-global]');
			
			if((value == '' && isGlobal.length < 1 && globalEle.length < 1) || isGlobal.length > 0){
				return;
			}
			
			if(name.indexOf('_tablet') > -1){
				name = name.replace('_tablet', '');
				atts[prop.c['name']+'_tablet'][name] = value;
				return;
			}
			
			if(name.indexOf('_mobile') > -1){
				name = name.replace('_mobile', '');
				atts[prop.c['name']+'_mobile'][name] = value;
				return;
			}
			
			atts[prop.c['name']][name] = value;
		});
				
		pagelayer_set_atts(prop.el.$, atts);
		pagelayer_sc_render(prop.el.$); // Render
	}
	
	row.find('.pagelayer-elp-typo-input').on('change', function(e){
		
		var jEle = jQuery(e.target);
		
		pagelayer_link_font_family(jEle);
		jEle.closest('[pagelayer-set-global]').removeAttr('pagelayer-set-global');
		
		// Save value
		save_typography();
	});
	
	row.find('.pagelayer-elp-typo-deco-line select').on('change', function(){
		var value = jQuery(this).val();
		if(pagelayer_empty(value) || value=='none'){
			row.find('.pagelayer-elp-typo-deco-style').hide();
		}else{
			row.find('.pagelayer-elp-typo-deco-style').show();
		}
	});
		
	// Handle for global font
	row.find('.pagelayer-elp-global-typo .pagelayer-elp-global-icon').on('click', function(e){
		e.stopPropagation();
		row.find('.pagelayer-global-font-list').slideToggle();
		
	});
	
	row.find('.pagelayer-elp-global-typo .pli-service').on('click', function(e){
		e.stopPropagation();
		window.open(pagelayer_customizer_url+'&autofocus%5Bsection%5D=pagelayer_global_fonts_sec', '_blank');
	});
	
	// Added restore global val
	row.find('.pagelayer-elp-typo > .pagelayer-elp-label').each(function(){
		var label = jQuery(this);
		var defaultButton = '<span class="pagelayer-typo-default" title="'+pagelayer_l('restore_global')+'" ><i class="fas fa-undo"></i></span>';		
		label.append(defaultButton);
		
		label.find('.pagelayer-typo-default').on('click', function(e, skip_save){
			
			skip_save = skip_save || false;
			
			var globalID = row.find('.pagelayer-global-selected').data('global-id');
			
			if(pagelayer_empty(globalID) || pagelayer_empty(pagelayer_global_fonts[globalID])){
				return;
			}
			
			var setFonts = pagelayer_global_fonts[globalID]['value'];
			var holder = label.closest('.pagelayer-elp-typo');
			var inputs = holder.find('.pagelayer-elp-typo-input');
			var name = inputs.first().attr('name');
			var val = '';
			
			holder.attr('pagelayer-set-global', 1);
			
			if(name in setFonts){
				val = setFonts[name];
			}
			
			if(typeof val == 'object'){
				for(var mode in modes){
					var _val = '';
					if(mode in val){
						_val = val[mode];
					}
					
					holder.find('.pagelayer-elp-typo-input[name="'+name+modes[mode]+'"]').val(_val);
				}
			}else{
				
				if(inputs.length > 1){
					inputs.val('');
				}
				
				inputs.first().val(val);
			}
			
			if(skip_save){
				return;
			}
			
			// save value
			clearTimeout(save_timer);
			save_timer = setTimeout(save_typography, 200);
			
		});	
	});
	
	// Handle for global font
	row.find('.pagelayer-global-font-list-item').on('click', function(e){
		e.stopPropagation();
		
		var listItem = jQuery(this);
		var fontSelect = row.find('.pagelayer-elp-typo-family .pagelayer-elp-typo-input');
		
		// Remove global typo 
		if(listItem.hasClass('pagelayer-global-selected')){
			row.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
			row.find('.pagelayer-elp-global-icon').removeClass('pagelayer-active-global');
			row.find('[pagelayer-set-global]').removeAttr('pagelayer-set-global');
			row.find('.pagelayer-global-on').removeClass('pagelayer-global-on');
			
			// To save and render the typo 
			fontSelect.trigger('change');
			return;
		}
		
		var globalID = listItem.data('global-id');
		var listHolder = row.find('.pagelayer-global-font-list');
		
		// Remove previous selecttion
		listHolder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		listItem.addClass('pagelayer-global-selected');
		row.find('.pagelayer-elp-global-icon').addClass('pagelayer-active-global');
		row.find('.pagelayer-elp-typo-fonts').addClass('pagelayer-global-on');
		listHolder.slideUp();
		
		pagelayer_link_font_family(fontSelect); // Apply google fonts
		
		// Set global value to all fields and save
		row.find('.pagelayer-elp-label .pagelayer-typo-default').click();
	});
	
	// Active global typography
	if(!pagelayer_empty(is_typo)){
		row.find('[data-global-id="'+is_typo+'"]').addClass('pagelayer-global-selected');
		row.find('.pagelayer-elp-global-icon').addClass('pagelayer-active-global');
		row.find('.pagelayer-elp-typo-fonts').addClass('pagelayer-global-on');
		
		// Show the global values if is not customize
		row.find('.pagelayer-elp-typo').attr('pagelayer-set-global', 1);
		row.find('.pagelayer-elp-typo').find('select, input').each(function(){
			var sEle = jQuery(this);
			var val = sEle.val();
			
			if(pagelayer_empty(val)){
				return true;
			}
			
			sEle.closest('.pagelayer-elp-typo').removeAttr('pagelayer-set-global');
		});
		
		row.find('[pagelayer-set-global="1"] .pagelayer-typo-default').trigger('click', [true]);
	}
	
	// Set screen mode on change
	row.find('.pagelayer-elp-screen .pli:not(.pagelayer-prop-screen)').on('click', function(){
		var mode = 'desktop';
		var jEle = jQuery(this);
		
		// Tablet ?
		if(jEle.hasClass('pli-tablet')){
			mode = 'tablet';
		}
		
		// Mobile ?
		if(jEle.hasClass('pli-mobile')){
			mode = 'mobile';
		}
		
		pagelayer_set_screen_mode(mode);
		row.find('.pagelayer-elp-screen .pli').removeClass('open');
	});
	
	row.find('.pagelayer-elp-screen').on('pagelayer-screen-changed', function(e){
		var mode = pagelayer_get_screen_mode();
		row.find('[pagelayer-screen-mode]').attr('pagelayer-screen-mode', mode);
	});
	
	row.find('.pagelayer-elp-screen .pagelayer-prop-screen').on('click', function(e){
		jQuery(this).siblings().toggleClass('open');
	});
	
}

// The dimension property
function pagelayer_elp_dimension(row, prop){
	
	var val = ['', ''];
	
	if(!pagelayer_empty(prop.c['val'])){
		
		val = prop.c['val'];
		if(pagelayer_is_string(val)){
			val = val.split(',');
			//console.log(val);
		}
		
	}
	
	var div = '<div class="pagelayer-elp-dimension-div">'+
		'<input type="number" class="pagelayer-elp-dimension" value="'+parseFloat(val[0])+'"></input>'+
		'<input type="number" class="pagelayer-elp-dimension" value="'+parseFloat(val[1])+'"></input>'+
		'<i class="pli pli-link" ></i>'+
	'</div>';
	
	row.append(div);
	
	// Is the value linked ?
	var link = row.find('.pagelayer-elp-dimension-div .pli');
	var isLinked = 1;
	var tmp_val = val[0];
	
	for(var p_val in val){

		// Check if unlinked
		if(tmp_val != val[p_val] ){
			isLinked = 0;
		}
		tmp_val = val[p_val];
	}
	
	if(isLinked){
		link.addClass('pagelayer-elp-dimension-linked');
	}else{
		link.removeClass('pagelayer-elp-dimension-linked');
	}
	
	// Handle link on click
	link.on('click', function(){
		
		var linked = link.hasClass('pagelayer-elp-dimension-linked');
		
		if(linked){
			link.removeClass('pagelayer-elp-dimension-linked');
		}else{
			link.addClass('pagelayer-elp-dimension-linked');
		}
				
	});
	
	row.find('input').on('input', function(){
		
		// Are the values linked
		var linked = row.find('.pagelayer-elp-dimension-div .pli').hasClass('pagelayer-elp-dimension-linked');
		
		if(linked){
			var val = jQuery(this).val();
			row.find('input').each(function(){
				jQuery(this).val(val);
			});
		}
		
		var vals = [];
		
		// Get all values
		row.find('input').each(function(){
			var val = jQuery(this).val();
			vals.push(val ? val : 0);
		});
		
		_pagelayer_set_atts(row, vals);// Save and Render
	});
	
};

var first_time_cat = true;
// Post Category property
function pagelayer_elp_postCategory(row, prop){

	if(pagelayer_empty(pagelayer_post_categories)){
		return;
	}
  
	// Placing the checked categories on the top.
	var checked_on_top = function(with_checkbox){
		var checked_list = '';
		var unchecked_list = '';
		
		jQuery(with_checkbox).children().each(function(){
			var list = this;
			var temp = jQuery(list).find('input[checked=checked]');
			
			if(!pagelayer_empty(temp.length)){
				checked_list += list.outerHTML;
			}else{
				unchecked_list += list.outerHTML;
			}
			
		});
    
		return ('<div class="pagelayer-post-cat-div" ><ul class="pagelayer-post-category" >'+checked_list+unchecked_list+'</ul></div>');
	}
	
	// Getting checked and unchecked categories on opening of page props settings.
	if(first_time_cat == false){
	
		var $div = jQuery('<div>').html(pagelayer_post_categories.with_checkbox);
		$div.find('input[type=checkbox]').attr('checked', false);		
		
		if(!pagelayer_empty(prop.c['val'])){
			
			var check_val = prop.c['val'];
			if(pagelayer_is_string(check_val)){
				check_val = check_val.split(',');
			}
			
			for(var no in check_val){
				$div.find('input[type=checkbox][value='+check_val[no]+']').attr('checked', true);
			}			
		}
		
		pagelayer_post_categories.with_checkbox = $div.html();
    
	}
	
	first_time_cat = false;
	
	// For making insert new categories functionality.				
	row.append(checked_on_top(pagelayer_post_categories.with_checkbox));
	
	var div = '<div class="pagelayer-elp-postCategory">'+
		'<span class="pagelayer-add-cat-btn">Add New Category</span>'+
		'<form style="display:none;">'+
			'<div>'+
			'<label>New Category Name</label>'+
			'<input type="text" name="category_name" required>'+					
			'</div>'+
			'<div>'+
			'<label>Parent Category</label>'+
			'<div class="pagelayer-parent-category"></div>'+
			'</div>'+
			'<button type="submit" class="pagelayer-cat-submit" >Add New Category</button>'+
		'</form>'+
	  '</div>';
	  
	row.append(div);
	
	// For making categories drop down options and adding an empty option.
	if(!pagelayer_empty(pagelayer_post_categories.without_checkbox)){			
		var options = pagelayer_post_categories.without_checkbox.replace('>', '><option class="level-0" value="0">--No Parent--</option>');
		var options = jQuery(options);	
		row.find('.pagelayer-parent-category').append(options);
	}
	
	// For initiating ajax call when user create new category
	row.find('form').on('submit', function(e){
		e.preventDefault();
		jQuery.ajax({
			type: 'post',
			url: pagelayer_ajax_url+'&action=pagelayer_get_cat_checkboxes',
			dataType: 'json',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				'postid': pagelayer_postID,
				'new_cat': row.find('form').serialize()
			},
			success: function(obj){
				
				if(pagelayer_empty(obj)){
					return;
				}	
				
				if('error' in obj){
					alert(obj.error);
				}
				
				if(!pagelayer_empty(obj.new_cat_id)){						
					obj.with_checkbox = obj.with_checkbox.replace('value="'+obj.new_cat_id+'"', 'value="'+obj.new_cat_id+'" checked="checked"'); 						
				}	
				
				var new_cat_elem = jQuery(obj.with_checkbox).find('input[value='+obj.new_cat_id+']').closest('li');
				var post_cat = row.find('.pagelayer-post-category');
        
				// Does the new element have no parents ? Then prepend the <LI> to the existing list shown
				if(!pagelayer_empty(new_cat_elem.parent('.pagelayer-post-category').length)){
					post_cat.prepend(new_cat_elem);
				}else{
          
					// Siblings are already there ?
					if(!pagelayer_empty(new_cat_elem.siblings().length)){
						post_cat.find('#'+new_cat_elem.parent().parent('li').attr('id')).children('ul').append(new_cat_elem);
					// No siblings, hence append
					}else{
						new_cat_elem = new_cat_elem.parent();
						post_cat.find('#'+new_cat_elem.closest('li').attr('id')).append(new_cat_elem);
					}
					
					post_cat.prepend(new_cat_elem.parentsUntil('.pagelayer-post-category').last());
				}				
				
				row.find('#pagelayer_cat_parent').replaceWith(obj.without_checkbox.replace('>', '><option class="level-0" value="0">--No Parent--</option>'));
				
				row.find('input[name="category_name"]').val('');
				row.find('#pagelayer_cat_parent option[value="0"]').attr('selected', true);
				checked_cat(row.find('.pagelayer-post-cat-div'));
				event_function();
				pagelayer_post_categories = obj;
			}
		});
	});
	
	// Show and hide 'Add new Category' button.
	row.find('.pagelayer-add-cat-btn').on('click', function(){
		row.find('form').toggle('fast');
	});
	
	var checked_cat = function(elem){
		var jEle = elem.find('input:checked');
		var cat_array = [];
		for(var checked_input of jEle){
			cat_array.push(jQuery(checked_input).attr('value'));
		}
		_pagelayer_set_atts(row, cat_array);
	};
	
	var event_function = function(){row.find('.pagelayer-post-cat-div').on('change', function(){
			checked_cat(jQuery(this));
		});
	};
	event_function();
}

var first_time_tag = true;
// Post tags property
function pagelayer_elp_postTags(row, prop){

	if(pagelayer_empty(pagelayer_post_tags)){
		return;
	}
	
	var div = '<div class="pagelayer-elp-postTags" >'+
				'<div class="pagelayer-post-tags" >'+
					'<input type="text" autocomplete="off" class="pagelayer-elp-postTags-inp" autofocus="autofocus"/>'+
					'<ul class="pagelayer-postTags-list" >'+
					'</ul>'+
				'</div>'+
			'</div>';
	
	row.append(div);
	
	// Single tag html
	var singleTag = function(tags){
		var html = '';
		jQuery.each(tags, function(index, value){
			if(pagelayer_empty(value['term_id'])){
				return;
			}
			html += '<span class="pagelayer-elp-tags-ele" data-val="'+value['term_id']+'"><span class="pagelayer-tags-label" >'+value['name']+'</span><span class="pagelayer-elp-tags-remove"><i class="fas fa-times"></i></span></span>';
		});
		return html;
	}
	
	// Single list item html
	var singleLi= function(tags){
		var html = '';
		jQuery.each(tags, function(index, value){
			html += '<li data-val="'+value['term_id']+'">'+value['name']+'</li>';
		});
		return html;
	}	
	
	// For making new tags as well as removing using keyboard inputs.
	var keypresses = function(obj){
		row.find('.pagelayer-elp-postTags-inp').on('keydown', function(e){
			var val = e.target.value.trim();
			var keycode = (event.keyCode ? event.keyCode : event.which);
			
			if(keycode == '13' || keycode == '188'){
				
				for(var tag of obj.allTags){
					if(tag['name']==val){
						insertTags(val, tag['term_id']);
						return false;
					}
				}
				
				jQuery.ajax({
					url: pagelayer_ajax_url+'&action=pagelayer_get_post_tags',
					type: 'post',
					dataType: 'json',
					data: {
						pagelayer_nonce: pagelayer_ajax_nonce,
						'postid': pagelayer_postID,
						'new_tag': val
					},
					success: function(resp){
						if(pagelayer_empty(resp)){
							return;
						}	
						if('error' in resp){
							alert(resp.error);
						}
						if(!pagelayer_empty(resp.tag_id)){
							insertTags(val, resp.tag_id);
							tagSearching(resp);
							pagelayer_post_tags = resp;
						}
					}
				});
				
				return false;
			}else if(keycode == '8'){
				if(!pagelayer_empty(val)){
					return true;
				}
				row.find('.pagelayer-post-tags').children('span').last().remove();
				selected_tags();
			}
			return true;
		});
	}
	
	// Inserting tags in the Metabox.
	var insertTags = function(name, tag_id){
		var newItem = [];
		newItem[0] = {	
			name:name, 
			term_id:tag_id 
		};
		row.find('.pagelayer-post-tags').children('input').before(singleTag(newItem));
		row.find('.pagelayer-elp-postTags .pagelayer-elp-postTags-inp').val('').focus();
		tag_remove();
		selected_tags();
	}
	
	// Removing tags by clicking on the x button.
	var tag_remove = function(){
		row.find('.pagelayer-elp-tags-remove').each(function(){
			jQuery(this).on('click',function(){
				jQuery(this).parent().remove();
				selected_tags();
			});
		});
	}	
	
	// For searching tag name in the list of the fetched tags
	var tagSearching = function(obj){
		row.find('.pagelayer-elp-postTags-inp').off('keyup');
		row.find('.pagelayer-elp-postTags-inp').on("keyup", function() {
			var value = jQuery(this).val().toLowerCase();
			
			var listUl = row.find('.pagelayer-postTags-list');
			listUl.empty();
			
			if(value.length<2){
				return;
			}
			
			var listValues = obj.allTags.filter(function(currentValue){
				if(currentValue.name.indexOf(this)>-1){
					var temp = false;
					var tags = row.find('.pagelayer-post-tags').children('span');
					for(var indi of tags){
						if(jQuery(indi).attr('data-val')==currentValue.term_id){
							temp = true;
						}
					}
					if(temp==false){
						return currentValue;
					}
				}
			}, value);
			
			if(!pagelayer_empty(listValues.length)){
				listUl.append(singleLi(listValues));
				listUl.children().each(function(index, value){
					var ele = jQuery(this);
					ele.off('click');
					ele.on('click', function(){
						insertTags(ele.text(), ele.attr('data-val'));
						listUl.empty();
					});
				});
			}
			
			
		});
	}
	  
  var tagsArray = pagelayer_post_tags.postTags;
  
	// Getting tags on opening of page props settings.
	if( first_time_tag == false ){
  
		var i=0;
		var tags_array = [];
    
		// Create array for needed term_id with corresponding to the name.
		if(!pagelayer_empty(prop.c['val'])){
			
			var tags_val = prop.c['val'];
			if(pagelayer_is_string(tags_val)){
				tags_val = tags_val.split(',');
			}
			
			for(var name in tags_val){
				tags_array[i] = pagelayer_post_tags.allTags.find(function(val){return val['name'] == tags_val[name]});
				i++;
			}			
		}
		
		tagsArray = tags_array;		
	}
  
	row.find('.pagelayer-post-tags').prepend(singleTag(tagsArray));		
  
	first_time_tag = false;
			
	tagSearching(pagelayer_post_tags);
	
	keypresses(pagelayer_post_tags);
	
	tag_remove();
	
	var selected_tags = function(){
		var jEle = row.find('.pagelayer-elp-postTags .pagelayer-elp-tags-ele');
		var tag_array = [];
		for(var selec_tag of jEle){
			tag_array.push(jQuery(selec_tag).text());
		}
		_pagelayer_set_atts(row, tag_array);
	};
}

function pagelayer_elp_permalink(row, prop){
	
	var tmp = '';
	var link = '';
	
	if(!pagelayer_empty(pagelayer_permalink_structure)){
		tmp = pagelayer_post_permalink.replace(/\/$/,'');
		link = tmp.substring(0, tmp.lastIndexOf('/'));
				
		var new_link = link+'/'+prop.c['val'];
		prop.default = pagelayer_post.post_name;
		
		var div = '<div class="pagelayer-elp-text-div">'+
					'<input type="text" class="pagelayer-elp-text" name="'+prop.c['name']+'" value="'+pagelayer_htmlEntities(prop.c['val'])+'"></input>'+
					'<a href="'+pagelayer_post_permalink+'" class="pagelayer-elp-permalink-a" target="_blank" >'+new_link+'</a></p>'+
				'</div>';		
	}else{
		var div = '<div class="pagelayer-elp-text-div">'+
					'<a href="'+pagelayer_post.guid+'" class="pagelayer-elp-permalink-a" target="_blank" >'+pagelayer_post.guid+'</a></p>'+
				'</div>';
	}
	
	row.append(div);
	
	setTimeout(function(){
		row.find(".pagelayer-post-type").html(pagelayer_post.post_type);
	}, 1000);
	
	var string_to_slug = function (str){
		str = str.replace(/^\s+|\s+$/g, ''); // trim
		str = str.toLowerCase();
      
		// remove accents, swap ñ for n, etc
		var char_map = {
			// Latin
			'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C', 
			'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I', 
			'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ő': 'O', 
			'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', 'Ý': 'Y', 'Þ': 'TH', 
			'ß': 'ss', 
			'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c', 
			'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i', 
			'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o', 
			'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th', 
			'ÿ': 'y',

			// Latin symbols
			'©': '(c)',

			// Greek
			'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
			'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', 'Ν': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
			'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
			'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', 'Ώ': 'W', 'Ϊ': 'I',
			'Ϋ': 'Y',
			'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
			'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
			'ρ': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
			'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
			'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', 'ΐ': 'i',

			// Turkish
			'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
			'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g', 

			// Russian
			'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh',
			'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O',
			'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
			'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
			'Я': 'Ya',
			'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
			'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
			'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
			'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
			'я': 'ya',

			// Ukrainian
			'Є': 'Ye', 'І': 'I', 'Ї': 'Yi', 'Ґ': 'G',
			'є': 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',

			// Czech
			'Č': 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U', 
			'Ž': 'Z', 
			'č': 'c', 'ď': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
			'ž': 'z', 

			// Polish
			'Ą': 'A', 'Ć': 'C', 'Ę': 'e', 'Ł': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z', 
			'Ż': 'Z', 
			'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
			'ż': 'z',

			// Latvian
			'Ā': 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N', 
			'Š': 'S', 'Ū': 'u', 'Ž': 'Z', 
			'ā': 'a', 'č': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
			'š': 's', 'ū': 'u', 'ž': 'z'
		};
		
		for(var k in char_map) {
			str = str.replace(new RegExp(k, 'g'), char_map[k]);
		}
		
		str = str.replace('.', '-')// replace a dot by a dash
			.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
			.replace(/\s+/g, '-') // collapse whitespace and replace by a dash
			.replace(/-+/g, '-') // collapse dashes
			.replace( /\//g, '' ); // collapse all forward-slashes

		return str;
	}
	
	var editSlug = function(jEle, val){
		
		// Convert to slug
		val = string_to_slug(val);
		
		var new_link = link+'/'+val;
		var a = row.find('a');
		a.html(new_link);
		jEle.val(val);
    
		return val;
	}
	
	var input = row.find('input');
	
	if(pagelayer_empty(prop.c['val'])){
		editSlug(input, pagelayer_post.post_title);
		
		input.on('focusin', function(){
			if(!pagelayer_empty(pagelayer_get_att(prop.el.$, prop.c['name']))){
				return;
			}
			
			editSlug(input, pagelayer_get_att(prop.el.$, 'post_title'));
		});
	}
	
	input.on('focusout', function(){
		var val = jQuery(this).val();
		val = editSlug(jQuery(this), val);
		
		if(pagelayer_empty(pagelayer_get_att(prop.el.$, prop.c['name']))){
			return;
		}
		_pagelayer_set_atts(row, val);// Save and Render
	});
	
	input.on('input', function(){
		var new_link = link+'/'+jQuery(this).val();
		var a = row.find('a');
		a.html(new_link);
		_pagelayer_set_atts(row, jQuery(this).val());// Save and Render
	});	
}

// The Datetime Property
function pagelayer_elp_postDate(row, prop){
	
	var date_array = prop.c['val'].split(" ");
	
	var div = '<div class="pagelayer-elp-postdate-div">'+
				'<input type="date" class="pagelayer-elp-postdate" name="'+prop.c['name']+'" value="'+date_array[0]+'" />'+
				'<input type="time" class="pagelayer-elp-postdate" name="'+prop.c['name']+'" value="'+date_array[1]+'" />'+
			'</div>';
	
	row.append(div);
		
	row.find('.pagelayer-elp-postdate-div').on('change', function(){
		var date_string = jQuery(this).children().eq(0).val() +' '+ jQuery(this).children().eq(1).val();
		_pagelayer_set_atts(row, date_string);// Save and Render
	});
	
};

// The button Property
function pagelayer_elp_trashButton(row, prop){
		
	var div = '<div class="pagelayer-elp-trash-button-div">'+
				'<button class="pagelayer-elp-trash-button">Move to trash</button>'+
			'</div>';
	
	row.append(div);
		
	row.find('.pagelayer-elp-trash-button').on('click', function(event){
		event.preventDefault();
		if(!confirm(pagelayer_l('delete_post_conf'))){
			return;
		}
		//console.log(pagelayer_postID);
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_trash_post',
			type: 'post',
			dataType: 'json',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				'postid': pagelayer_postID
			},
			success: function(resp){
					
				if('error' in resp){
					alert(resp.error);
				}
				
				if('url' in resp){
					window.top.location.href = resp.url;
				}
			}
		});
	});
	
};

// The Menus list property
function pagelayer_elp_menus(row, prop){
	
	var jEle = prop.el.$;
	var options = '';
	var option = function(val, lang){
		var selected = (val != prop.c['val']) ? '' : 'selected="selected"';
		return '<option class="pagelayer-elp-select-option" value="'+val+'" '+selected+'>'+lang+'</option>';
	}
	
	for(x in prop['list']){
		
		// Single item
		if(typeof prop['list'][x] == 'string'){
			options += option(x, prop['list'][x]);
		
		// Groups
		}else{
			options += '<optgroup label="'+x+'">';
			
			for(var y in prop['list'][x]){
				options += option(y, prop['list'][x][y]);
			}
			
			options += '</optgroup>';
		}
	}
	
	var div = '<div class="pagelayer-elp-select-div pagelayer-elp-pos-rel">'+
			'<select class="pagelayer-elp-select pagelayer-elp-select-menus" name="'+prop.c['name']+'">'+options+'</select>'+
	'</div>'+
	'<div class="pagelayer-elp-menu-items-holder pagelayer-elp-pos-rel"></div>';
			
	row.append(div);
		
	// Show the properties of the existing things
	var show_item = function(item, child_elements, depth){
		
		depth = depth || 0;
		
		var title = item['title'] || '';
		
		// Create the HTML
		var holder = jQuery('<div class="pagelayer-elp-group-item pagelayer-menu-depth-'+depth+'" pagelayer-menu-item="'+item['ID']+'">'+
			'<div class="pagelayer-elp-group-item-head">'+
				'<span class="pagelayer-elp-group-item-drag"><i class="pli pli-menu" ></i></span>'+
				'<span class="pagelayer-elp-group-item-title">'+title+'</span>'+
			'</div>'+
			'<div class="pagelayer-elp-group-item-body"></div>'+
			'<div class="menu-item-transport"></div>'+
		'</div>');
		
		// Append to the row
		row.find('.pagelayer-elp-menu-items-holder').append(holder);
		
		// Setup the toggle
		holder.find('.pagelayer-elp-group-item-title').first().on('click', function(){
			
			var editArea = jEle.find('.pagelayer-mega-editor-'+item['ID']);
			var child = editArea.find('[pagelayer-tag="pl_nav_menu_item"]');
			var cid;
			
			if(child.length < 1){
				
				// First add the element inside the group element
				var _child = jQuery('<div pagelayer-tag="pl_nav_menu_item"></div>');
				
				editArea.append(_child);
				
				cid = pagelayer_onadd(_child, false);
				
				child = jQuery('[pagelayer-id='+cid+']');
				
				// Set Attributes
				pagelayer_set_atts(child, item);
			}else{
				cid = pagelayer_id(child);
			}
      			
			var rEle = holder.find('.pagelayer-elp-group-item-body').first();
			
			holder.attr('pagelayer-group-item-id', cid);
			
			// If the props are not already setup
			if(rEle.html().length < 1){
				pagelayer_elpd_generate(jQuery('[pagelayer-id="'+cid+'"]'), rEle);
				
				// Change the group item title
				var tmp_title = holder.find('[pagelayer-elp-name="'+item['title']+'"] [name="'+item['title']+'"]');
		
				if(tmp_title.length > 0){
					jQuery(tmp_title).on('input', function(){						
						holder.find('.pagelayer-elp-group-item-title').html(tmp_title.val());
					});
				}
			}
			
			if(!rEle.is(':visible')){
				jQuery('.pagelayer-active-mega-menu').removeClass('pagelayer-active-mega-menu');
				jEle.find('.pagelayer-mega-menu-item.menu-item-'+item['ID']).addClass('pagelayer-active-mega-menu');
			}
			
			rEle.slideToggle();
		});
		
		// Add child elements
		if(!pagelayer_empty(child_elements[item['ID']])){
			
			depth++;
			
			for(var i in child_elements[item['ID']]){
				show_item(child_elements[item['ID']][i], child_elements, depth);
			}
		}
		
		holder.on('change', 'select[name="menu_type"]', function(){
			var mType = jQuery(this).val();
			var rowGroup = holder.find('[pagelayer-elp-name="element"]');

			if(mType != 'mega' || rowGroup.find('.pagelayer-elp-group-div .pagelayer-elp-group-item').length > 0){
				return;
			}
			
			rowGroup.find('.pagelayer-elp-button').click();
			
		});
	};
  
	var createItemsList = function(menuID){
		
		// Remove previous items
		row.find('.pagelayer-elp-menu-items-holder').empty();
		
		if(!(menuID in pagelayer_menus_items_list)){
			return;
		}
		
		var $elements = pagelayer_menus_items_list[menuID];
		var top_level_elements = [];
		var children_elements  = [];
		
		for($e in $elements){
			
			// Make a referrer of each menu 
			pagelayer_menus_items_ref[$elements[$e]['ID']] = $elements[$e];
			
			if ( pagelayer_empty( $elements[$e]['menu_item_parent'] ) ) {
				top_level_elements.push($elements[$e]);
			} else {
				if(pagelayer_empty(children_elements[ $elements[$e]['menu_item_parent'] ])){
					children_elements[ $elements[$e]['menu_item_parent'] ] = [];
				}
				children_elements[ $elements[$e]['menu_item_parent'] ].push($elements[$e]);
			}
		}
		
		for(var i in top_level_elements){
			show_item(top_level_elements[i], children_elements, 0);
		}
		
	}
	
	createItemsList(prop.c['val']);
  
	row.find('select.pagelayer-elp-select-menus').on('change', function(){
		
		var ID = jQuery(this).val();
		
		// Load Menu list
		createItemsList(ID);
		
		_pagelayer_set_atts(row, ID);// Save and Render		
	
	});
	
}

// Select frame to upload media
function pagelayer_select_frame(tag, state){
	
	var state = state || '';
	//console.log(state);
	
	var frame;
	
	switch(tag){
		
		// Multi image selection frame
		case 'multi_image':
		
			frame = wp.media({
				
				id: 'pagelayer-wp-multi-image-library',
				frame: 'post',
				state: state,
				title: pagelayer_l('frame_multi_image'),
				multiple: true,
				library: wp.media.query({type: 'image'}),
				button: {
					text: pagelayer_l('insert')
				},
				
			});
			
			break;
		
		// Media selection frame
		case 'media':
		
			frame = wp.media({
				
				id: 'pagelayer-wp-media-library',
				frame: 'post',
				state: 'pagelayer-media',
				title: pagelayer_l('frame_media'),
				multiple: false,
				states: [
					new wp.media.controller.Library({
						id: 'pagelayer-media',
						title: pagelayer_l('frame_media'),
						multiple: false,
						date: true
					})
				],
				button: {
					text: pagelayer_l('insert')
				},
				
			});
			
			break;
		
		//Default frame(for image, video, audio)
		default:
		
			frame = wp.media({
				
				id: 'pagelayer-wp-'+tag+'-library',
				frame: 'post',
				state: 'pagelayer-'+tag,
				title: pagelayer_l('frame_media'),
				multiple: false,
				library: wp.media.query({type: tag}),
				states: [
					new wp.media.controller.Library({
						id: 'pagelayer-'+tag,
						title: pagelayer_l('frame_media'),
						library: wp.media.query( { type: tag } ),
						multiple: false,
						date: true
					})
				],
				button: {
					text: pagelayer_l('insert')
				},
				
			});
			
			break;
	}
	
	frame.on({
		'menu:render:default': function(view){
			view.unset('insert');
			view.unset('gallery');
			view.unset('featured-image');
			view.unset('playlist');
			view.unset('video-playlist');
		},
	}, this);
	
	return frame;
	
}

// function to show default button
function pagelayer_show_default_button(row, prop, value){
	
	// Default button is visible or not
	if(row.find('.pagelayer-elp-default').attr('data_show')){
		return;
	}
	
	// value is an object or not
	if(typeof value == 'object'){
		// Checking value for NaN, empty and default.
		
		for(var i = 0; i < pagelayer_length(value); i++){
			if(value[i]!= prop.default && value[i] == value[i] && value[i] != ''){
				row.find('.pagelayer-elp-default').attr('data_show',true);			
				break;
			}		
		}		
	}else{
		if('default' in prop && value!=prop.default){
			row.find('.pagelayer-elp-default').attr('data_show',true);			
		}else if(value!=prop.default && value==value && value!=''){
			row.find('.pagelayer-elp-default').attr('data_show',true);			
		}
	}
}

// Function which checks the properties to not to show default button
function pagelayer_properties_filter(property){
	var propTypeDefault = ['image', 'text', 'editor', 'textarea', 'checkbox', 'access', 'modal', 'group', 'radio', 'postCategory', 'postTags', 'postDate', 'gradient'];
	
	return (jQuery.inArray(property, propTypeDefault) == -1)
}

// Link font family
function pagelayer_link_font_family(sEle){
	
	var value = sEle.val();
	
	if(sEle.val() == 'Default'){
		return;
	}
	
	value = value.replace(' ', '+');
	var t = sEle.find("option:selected").attr('type');
	switch(t){
		case 'google':
			if(jQuery('#pagelayer-google-fonts').length == 0){
				if(value==''){
					return;
				}
				jQuery('head').append('<link id="pagelayer-google-fonts" href="https://fonts.googleapis.com/css?family='+value+':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">');
				
			}else{
				var url = jQuery('#pagelayer-google-fonts').attr('href');
				if(url.indexOf(value) == -1){
					url = url+'|'+value+':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i';
					jQuery('#pagelayer-google-fonts').attr('href', url);
				}
			}
			break;
			
		case 'custom':
			if(!pagelayer_empty(jQuery('style[id='+value+'_plf]').length)){
				break;
			}
			jQuery.ajax({
				url: pagelayer_ajax_url+'&action=pagelayer_custom_font',
				type: 'POST',
				dataType: 'json',
				data: {
					'pagelayer_nonce': pagelayer_ajax_nonce,
					'font_name': value
				},
				success: function(data) {					
					if('style' in data){
						jQuery('body').append(data['style']);
					}
				}
			});
			break;
	}
}