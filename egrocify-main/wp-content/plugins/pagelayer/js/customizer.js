var pagelayer_fontHtmlArray = {};

(function($) {
	var api = wp.customize;
	
	api.bind( 'ready', function() {

		var controls = api.settings.controls;
		
		for(var control in controls){
			if( !('show_filter' in controls[control]) ){
				continue;
			}			
			
			var filter = controls[control]['show_filter'];
			for(var showParam in filter){
				var except = showParam.substr(0, 1) == '!' ? true : false;
				showParam = except ? showParam.substr(1) : showParam;
				
				// Show and Hide Controls 
				api( showParam, function( setting ){
					api.control( control, function( _control ) {
						var visibility = function() {
							var _filter = _control.params['show_filter'];
							for(var _showParam in _filter){
								var reqval = _filter[_showParam];
								var val = setting.get();
								
								var toShow = false;
								
								if(typeof reqval == 'string' && reqval == val){
									toShow = true;
								}
								
								// Its an array and a value is found, then dont show
								if(typeof reqval != 'string' && reqval.indexOf(val) > -1){
									toShow = true;
								}
								
								if(except && !toShow || !except && toShow  ){
									_control.container.show();
									return
								}
								
								_control.container.hide();
							}
							
						};

						visibility();
						setting.bind( visibility );
					});
				});
			}
		}
		
		// Expand pagelayer setting handler
		api.section('pagelayer_global_fonts_sec', function( section ){
			section.expanded.bind(function( isExpanding ){
				
				// Set default value
				section.container.find('select[data-font-key]').each(function(){
					var ref = jQuery(this),
						name = ref.attr('data-font-key'),
						value = ref.attr('data-default-value');
					
					ref.html(pagelayer_fontHtmlArray[name]).val(value);
					ref.removeAttr('data-font-key');
				});
			});
		});
		
		
	});
	
})(jQuery);

/**
 * Initialization trigger.
 */
jQuery(document).ready( function(){
	
	// Create color setting 
	pagelayer_alpha_color_control_init();
	
	var option = function(val, lang){
		var lang = lang || 'Default';
		return '<option value="'+val+'">'+lang+'</option>';
	}
	
	// Create font setting list
	for(var sk in pagelayer_global_font_settings){
		var sval = pagelayer_global_font_settings[sk];			
		if('choices' in sval){
			var fontHtml = '';
			for(  var value in sval['choices'] ) {
				
				if(typeof sval['choices'][value] !== 'object'){
					fontHtml += option(value, sval['choices'][value]);
					continue;
				}
				
				if(value != 'default'){
					fontHtml += '<optgroup label="'+value+'">';
				}
				
				for (x in sval['choices'][value]){
					fontHtml += option((jQuery.isNumeric(x) ? sval['choices'][value][x] : x), sval['choices'][value][x]);
				}
			}
			
			pagelayer_fontHtmlArray[sk] = fontHtml;
		}
	}
	
	// Show hide typography
	jQuery(document).on('click.pagelayer-typo-icon', function (e){
		var target = jQuery(e.target);
		var isTypo = target.closest('.pagelayer-control-typo');
		var isIcon = target.closest('.pagelayer-control-typo-icon');
		var typoHolder = isIcon.closest('.pagelayer-control-typo-holder');

		if(isTypo.length > 0){
			return;
		}
		
		if(isIcon.length > 0){
			
			// Set default value
			typoHolder.find('select[data-font-key]').each(function(){
				var ref = jQuery(this),
					name = ref.attr('data-font-key'),
					value = ref.attr('data-default-value');
				
				ref.html(pagelayer_fontHtmlArray[name]).val(value);
				ref.removeAttr('data-font-key');
			});
			
			var globalInput = typoHolder.find('.pagelayer-global-font-input');
			
			if(!pagelayer_empty(globalInput)){
				// Show the global values if is not customize
				typoHolder.find('.pagelayer-control-typo-fields').attr('pagelayer-set-global', 1);
				typoHolder.find('select, input').each(function(){
					var sEle = jQuery(this);
					var val = sEle.val();
					
					if(pagelayer_empty(val)){
						return true;
					}
					
					sEle.closest('.pagelayer-control-typo-fields').removeAttr('pagelayer-set-global');
				});
				
				typoHolder.find('[pagelayer-set-global="1"] .pagelayer-typo-global-default').trigger('click');
			}
			
			typoHolder.find('.pagelayer-control-typo').slideToggle(100);
			return;
		}
		
		jQuery('.pagelayer-control-typo').slideUp(100);
	});
	
	// Show hide global color option
	jQuery(document).on('click.pagelayer-global-color-icon', function (e){
		var target = jQuery(e.target);
		var isGcolor = target.closest('.pagelayer-global-color-list');
		var isGIcon = target.closest('.pagelayer-control-global-color-icon');
		
		if(isGcolor.length > 0){
			return;
		}
		
		if(isGIcon.length > 0){
			var listEle = isGIcon.closest('li').find('.pagelayer-global-color-list');
			jQuery('.pagelayer-global-color-list').not(listEle).slideUp(100);
			listEle.slideToggle(100);
			return;
		}
		
		jQuery('.pagelayer-global-color-list').slideUp(100);
	});
	
	// Hide global color option
	jQuery(document).on('focus', '.wp-color-result', function(e){
		jQuery('.pagelayer-global-color-list').slideUp(100);
	});
	
	// Show hide global fonts option
	jQuery('#customize-theme-controls').on('click.pagelayer-global-typo-icon', function (e){
		var target = jQuery(e.target);
		var isGcolor = target.closest('.pagelayer-global-font-list');
		var isGIcon = target.closest('.pagelayer-control-global-typo-icon');
		var typoHolder = isGIcon.closest('.pagelayer-control-typo-holder');
			
		if(isGcolor.length > 0){
			return;
		}
		
		if(isGIcon.length > 0){
			typoHolder.find('.pagelayer-global-font-list').slideToggle(100);
			return;
		}
		
		jQuery('.pagelayer-global-font-list').slideUp(100);
	});
	
	// Device handler
	jQuery('#customize-theme-controls').on('click', '.pagelayer-devices button', function(e){
		
		e.stopPropagation();
		
		var device = jQuery(this).data('device');
		var devices = {'desktop' : 'tablet', 'tablet' : 'mobile', 'mobile' : 'desktop'};
		jQuery('.devices-wrapper .devices [data-device="'+devices[device]+'"]').click();
	});
	
	// Add attr to detect device
	jQuery('#customize-theme-controls').attr('data-device-detector', 'desktop');
	
	// Device handler
	jQuery('.devices-wrapper .devices button[data-device]').on('click', function(e){
		
		e.stopPropagation();
		
		var device = jQuery(this).data('device');
		
		jQuery('.pagelayer-devices .active-device').removeClass('active-device');
		jQuery('.pagelayer-devices [data-device="'+device+'"]').addClass('active-device');
		
		jQuery('[data-device-detector]').attr('data-device-detector', device);
		
	});
	
	// Units handler
	jQuery('.pagelayer-units').each(function(){
		var units = jQuery(this);
		var uList = units.find('[data-unit]');
		var input = units.find('.pagelayer-unit-input');
		var uActive = units.find('[data-unit="'+input.val()+'"]');
		
		units.find('[data-unit]').on('click', function(){
			var uEle = jQuery(this);
			uList.removeClass('active');
			uEle.addClass('active');
			input.val(uEle.data('unit')).trigger('input');
		});
		
		if(uActive.length > 0){
			uActive.click();
			return;
		}
		
		//uList.first().click();
		
	});
	
	// Accordion Tab handlers
	jQuery('.pagelayer-accordion-tab').on('click', function(){
		var toggle = jQuery(this);
		var allToggle = toggle.closest('ul').find('.pagelayer-accordion-tab').not(toggle);
		
		allToggle.nextUntil('.pagelayer-accordion-tab').slideUp();
		allToggle.removeClass('pagelayer-active-accordion-tab')
		toggle.nextUntil('.pagelayer-accordion-tab').slideToggle();
		
		toggle.toggleClass('pagelayer-active-accordion-tab');
		
		var dash = toggle.find('.pagelayer-customize-heading .dashicons');
		var allDash = toggle.closest('ul').find('.pagelayer-accordion-tab .pagelayer-customize-heading .dashicons');
		
		allDash.addClass('dashicons-arrow-right-alt2');
		allDash.removeClass('dashicons-arrow-down-alt2');
		
		if(toggle.hasClass('pagelayer-active-accordion-tab')){
			dash.addClass('dashicons-arrow-down-alt2');
			dash.removeClass('dashicons-arrow-right-alt2');
		}
    
	});
	
	// Close all accordion tabs
	jQuery('.pagelayer-accordion-tab').nextUntil('.pagelayer-accordion-tab').hide();
	
	// Link padding control field handler
	jQuery('.pagelayer-control-padding').each(function(){
		pagelayer_control_padding_handler(jQuery(this));
	});

	// Link Global Color Palette
	jQuery('.pagelayer-global-setting-color .dashicons').click(function(){
		jQuery('#accordion-section-pagelayer_global_colors_sec .accordion-section-title').click();
	});

	// Link Global Font Palette
	jQuery('.pagelayer-global-setting-font .dashicons-admin-generic').click(function(){
		jQuery('#accordion-section-pagelayer_global_fonts_sec .accordion-section-title').click();
	});
	
	// Color Palette Custom Control
	pagelayer_color_palette_control_handler();
	
	// Color Palette Custom Control
	pagelayer_font_palette_control_handler();
	
	// Global color list handler
	pagelayer_global_color_list_handler();
	
	// Global font list handler
	pagelayer_global_font_list_handler();
	
	// Slider handler
	pagelayer_control_slider_handler();
	
});

// Global font list handler
function pagelayer_global_font_list_handler(){
	
	var font_list = '';
	
	// Create global font list
	for(var font in pagelayer_global_fonts){
		font_list += '<div class="pagelayer-global-font-list-item" data-global-id="'+font+'">'+
				'<span class="pagelayer-global-font-title">'+ pagelayer_global_fonts[font]['title'] +'</span>'+
			'</div>';
	}
	
	jQuery('.customize-control-pagelayer-typo-control .pagelayer-control-typo-holder').each(function(){
		var fHolder = jQuery(this);
		var fList = fHolder.find('.pagelayer-global-font-list');
		
		if(fList.length < 1){
			return;
		}
		
		// Add list of font list
		fList.append(font_list);
		
		var globalInput = fHolder.find('.pagelayer-global-font-input');
		var selectfont = globalInput.data('key');
		
		// Restore global value
		fHolder.find('.pagelayer-typo-global-default').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();
			
			var sEle = jQuery(this);
			var fieldHolder = sEle.closest('.pagelayer-control-typo-fields');
			var globalID = globalInput.val();
			
			if(pagelayer_empty(globalID) || pagelayer_empty(pagelayer_global_fonts[globalID])){
				return;
			}
			
			var allInput = fieldHolder.find('select, input');
			var name = allInput.first().attr('name');
			var setFonts = pagelayer_global_fonts[globalID]['value'];
			
			// Set default
			var modes = {desktop: '', tablet: '_tablet', mobile: '_mobile'};
			var val = '';
			
			fieldHolder.attr('pagelayer-set-global', 1);
			allInput.val(val).trigger('change');
			
			if(name in setFonts){
				val = setFonts[name];
			}
					
			if(typeof val == 'object'){
				
				for(var mode in modes){
					var _val = '';
					if(mode in val){
						_val = val[mode];
					}
					
					fieldHolder.find('[name="'+name+modes[mode]+'"]').val(_val);
				}
				
				return;
			}
			
			allInput.val(val);
		});
		
		if(fList.find('[data-global-id="'+selectfont+'"]').length > 0){
			fList.find('[data-global-id="'+selectfont+'"]').addClass('pagelayer-global-selected');
			
			// Set active
			fHolder.find('.pagelayer-control-global-typo-icon').addClass('pagelayer-active-global');
		}
		
		// On change any field we need to handle for the global
		fHolder.find('select, input').on('input', function(){
			var sEle = jQuery(this);
			var fieldHolder = sEle.closest('.pagelayer-control-typo-fields');
			
			if(fieldHolder.attr('pagelayer-set-global') == '1'){
				fieldHolder.removeAttr('pagelayer-set-global');
				fieldHolder.find('select, input').trigger('change');
			}
		});
		
	});
	
	jQuery('#customize-theme-controls').on('click', '.pagelayer-global-font-list-item', function(){
		var listItem = jQuery(this);
		var globalID = listItem.data('global-id');
		var listHolder = listItem.closest('.pagelayer-global-font-list');
		var holder = listItem.closest('.pagelayer-control-typo-holder');
		var allInputs = holder.find('select, input');
		
		// Remove global font
		if(listItem.hasClass('pagelayer-global-selected')){
			listItem.removeClass('pagelayer-global-selected');
			holder.find('.pagelayer-control-global-typo-icon').removeClass('pagelayer-active-global');
			holder.find('.pagelayer-global-font-input').val('');
			holder.removeClass('pagelayer-global-on');
			allInputs.trigger('input');
			allInputs.closest('.pagelayer-control-typo-fields').removeAttr('pagelayer-set-global');
			listHolder.hide();
			return;
		}
		
		// Remove previous selecttion
		listHolder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected')
		listHolder.hide();
		
		listItem.addClass('pagelayer-global-selected');
		
		var key = holder.find( '.pagelayer-global-font-input' ).attr( 'data-customize-setting-link' );
		
		// Empty all the typo
		allInputs.val('').trigger('input');
		allInputs.closest('.pagelayer-control-typo-fields').attr('pagelayer-set-global', 1);
		holder.addClass('pagelayer-global-on');
		
		// Set the actual option value to empty string.
		wp.customize( key, function( obj ) {
			obj.set(globalID);
		});
		
		// Apply all global values
		holder.find('.pagelayer-typo-global-default').click();
		holder.find('.pagelayer-control-global-typo-icon').addClass('pagelayer-active-global');
	});
}

// Global color list handler
function pagelayer_global_color_list_handler(){	
	
	jQuery(document).on('click', '.pagelayer-global-color-list-item', function(e, skip_update){
		
		skip_update = skip_update || false;
		
		var listItem = jQuery(this);
		var globalID = listItem.data('global-id');
		var listHolder = listItem.closest('.pagelayer-global-color-list');
		
		// Remove previous selecttion
		listHolder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		listItem.addClass('pagelayer-global-selected');
		listHolder.hide();
		
		var input = listItem.closest('li').find( '.pagelayer-alpha-color-control' )
		var code = '$'+globalID;
		var color = pagelayer_global_colors[globalID]['value'];
		
		input.unbind('change.pagelayer_global input.pagelayer_global color_change.pagelayer_global');
		
		if(!skip_update){
			var key = input.attr( 'data-customize-setting-link' );

			// Set the actual option value to empty string.
			wp.customize( key, function( obj ) {
				obj.set(code);
			});
		}
			
		// Set the actual option value to empty string.
		input.val( color );
		input.closest('.wp-picker-container').find('.wp-color-result').css({'background-color': color});
		
		input.on('change.pagelayer_global input.pagelayer_global color_change.pagelayer_global', function(){
			var colorCode = jQuery(this).val();
			if(jQuery.trim(colorCode) == color){
				return;
			}
			listItem.closest('li').find('.pagelayer-control-global-color-icon').removeClass('pagelayer-active-global');
			listHolder.find('.pagelayer-global-selected').removeClass('pagelayer-global-selected');
		});

		listItem.closest('li').find('.pagelayer-control-global-color-icon').addClass('pagelayer-active-global');
	});
	
	jQuery('.pagelayer-global-color-list-item.pagelayer-global-selected').trigger('click', [true]);
}

var pagelayer_global_colors_timmer = {};
// Color palette Custom Control
function pagelayer_color_palette_control_handler(){
	
	var global_palette = jQuery('#customize-control-pagelayer_global_colors');
		
	// Get the values from the repeater input fields and add to our hidden field
	var pagelayerGetAllInputs = function() {
		
		var pagelayer_colors_palette = {};
		
		global_palette.find('.pagelayer-alpha-color-control').each(function(){
			var cEle = jQuery(this);
			var id = cEle.data('id');

			pagelayer_colors_palette[id] = {
				'title' : cEle.closest('.pagelayer-color-holder').find('.pagelayer-color-title').text(),
				'value' : cEle.val(),
			}
		});
		
		var inputValues = JSON.stringify(pagelayer_colors_palette);
		
		// Add all the values from our repeater fields to the hidden field (which is the one that actually gets saved)
		global_palette.find('.pagelayer-color-palette-data').val(inputValues).trigger('change');
	}
	
	// Append a new row to our list of elements
	var pagelayer_add_row = function(ele, val = ''){
		
		var id = pagelayer_generate_randstr(6);
		var name =  ele.find('.pagelayer-color-holder').length - 3;
		
		var newRow = jQuery('<div class="pagelayer-color-holder"><span class="pagelayer-color-title" contenteditable="true">Color #'+name+'</span><span class="pagelayer-color-controls">'+val+'</span><span class="customize-control-color-repeater-delete"><span class="dashicons dashicons-no-alt"></span></span><input class="pagelayer-alpha-color-control" type="text" data-show-opacity="true" data-palette="true" data-default-color="'+val+'" data-id="'+id+'" data-title="New Color"/></div>');

		ele.find('.pagelayer-color-holder:last').after(newRow);
		pagelayer_alpha_color_control_init();
		
		// Update global variable
		ele.find('.pagelayer-alpha-color-control').trigger('color_change');
	}
	
	jQuery(document).on('color_change change', '#customize-control-pagelayer_global_colors .pagelayer-alpha-color-control, #customize-control-pagelayer_global_colors .pagelayer-color-title', function(){
		
		var cEle = jQuery(this);
		
		clearTimeout(pagelayer_global_colors_timmer);
		pagelayer_global_colors_timmer = setTimeout(function(){
			cEle.closest('.pagelayer-color-holder').find('.pagelayer-color-controls').html(cEle.val());
			pagelayerGetAllInputs();
		}, 300);
		
	});
	
	jQuery(document).on('input', '#customize-control-pagelayer_global_colors .pagelayer-color-title', function(){
		clearTimeout(pagelayer_global_colors_timmer);
		pagelayer_global_colors_timmer = setTimeout(function(){
			pagelayerGetAllInputs();
		}, 500);
	});
	
	// Add new item
	jQuery('.customize-control-color-repeater-add').click(function(event) {
		event.preventDefault();
		pagelayer_add_row(jQuery(this).parent());
	});

	// Remove item starting from it's parent element
	jQuery(document).on('click', '.pagelayer-color-holder .customize-control-color-repeater-delete .dashicons', function(event) {
		event.preventDefault();
		var numItems = jQuery(this).closest('.pagelayer-color-holder').remove();
		pagelayerGetAllInputs();
	});
}

// Font palette Custom Control
function pagelayer_font_palette_control_handler(){
	
	var global_palette = jQuery('#customize-control-pagelayer_global_fonts');
	
	// Get the values from the repeater input fields and add to our hidden field
	var pagelayerGetAllInputs = function() {
		
		var pagelayer_colors_palette = {};
		global_palette.find('.pagelayer-font-holder').each(function(){
			var cEle = jQuery(this);
			var id = cEle.data('id');
			var data = {};
			
			var array = cEle.find('input, textarea, select').serializeArray();
			jQuery.each(array, function () {
				
				if(this.value == ''){
					return;
				}
				
				var name = this.name;
				var value = this.value;
				
				// Is multi array
				if(name.indexOf("[") > -1){
					
					var nameArray = name.replaceAll(']', '').split('\['),	
						base = nameArray.shift(),
						last = nameArray.pop();
					
					if(typeof data[base] != 'object'){
						data[base] = {};
					}
					
					// Set base object as refrence
					var _val = data[base];
					
					for(key in nameArray){
						
						if(typeof _val[nameArray[key]] != 'object'){
							_val[nameArray[key]] = {};
						}
						
						// Change the refrence of object
						_val = _val[nameArray[key]];
					}
					
					_val[last] = value;
					return;
				}
				
				data[name] = value;
			});

			pagelayer_colors_palette[id] = {
				'title' : cEle.children('.pagelayer-font-title').text(),
				'value' : data,
			}
		});
		
		var inputValues = JSON.stringify(pagelayer_colors_palette);
		
		// Add all the values from our repeater fields to the hidden field (which is the one that actually gets saved)
		global_palette.find('.pagelayer-font-palette-data').val(inputValues).trigger('change');
	}
	
	// Append a new row to our list of elements
	var pagelayer_add_row = function(ele, val = ''){
		
		var id = pagelayer_generate_randstr(6);
		var name =  ele.find('.pagelayer-font-holder').length - 3;
		var fontHtml = '';
		
		var option = function(val, lang){
			var selected = '';//(val != prop.c['val']) ? '' : 'selected="selected"';
			var lang = lang || 'Default';
			return '<option value="'+val+'" '+selected+'>'+lang+'</option>';
		}
	
		fontHtml += '<div class="pagelayer-font-holder" data-id="'+id+'"><span class="pagelayer-font-title" contenteditable="true">New Font #'+ name +'</span><span class="customize-control-font-repeater-delete"><span class="dashicons dashicons-no-alt"></span></span><div class="pagelayer-control-typo-holder"><span class="pagelayer-control-typo-icon dashicons dashicons-edit"></span><div class="pagelayer-control-typo">';
		
		for(var sk in pagelayer_global_font_settings){
			var sval = pagelayer_global_font_settings[sk];
			
			fontHtml += '<div class="pagelayer-control-typo-fields">'+
				'<label class="pagelayer-control-typo-fields-label">'+sval['label'];
				
			if('responsive' in sval){
				fontHtml += '<span class="pagelayer-devices">'+
					'<button type="button" class="active-device" aria-pressed="true" data-device="desktop">'+
					'<i class="dashicons dashicons-desktop"></i>'+
					'</button>'+
					'<button type="button"aria-pressed="false" data-device="tablet">'+
					'<i class="dashicons dashicons-tablet"></i>'+
					'</button>'+
					'<button type="button" aria-pressed="false" data-device="mobile">'+
					'<i class="dashicons dashicons-smartphone"></i>'+
					'</button>'+
				'</span>';
			}
				
			fontHtml += '</label>';
				
				if('choices' in sval){
					fontHtml += '<select name="'+ sk +'">';
						for(  var value in sval['choices'] ) {
							if(typeof sval['choices'][value] !== 'object'){
								fontHtml += option(value, sval['choices'][value]);
								continue;
							}
							
							if(value != 'default'){
								fontHtml += '<optgroup label="'+value+'">';
							}
							
							for (x in sval['choices'][value]){
								fontHtml += option((jQuery.isNumeric(x) ? sval['choices'][value][x] : x), sval['choices'][value][x]);
							}
							
						}
					fontHtml += '</select>';
				}else{
					fontHtml += '<input type="number" name="'+ sk +'">';
				}
			fontHtml += '</div>';
		}
	
		fontHtml += '</div></div></div>';
		
		ele.find('.customize-control-font-repeater-add').before(fontHtml);
	}
	
	jQuery(document).on('input', '#customize-control-pagelayer_global_fonts input, #customize-control-pagelayer_global_fonts textarea, #customize-control-pagelayer_global_fonts select', function(){
		
		clearTimeout(pagelayer_global_colors_timmer);
		pagelayer_global_colors_timmer = setTimeout(function(){
			pagelayerGetAllInputs();
		}, 300);
	});
	
	jQuery(document).on('input', '#customize-control-pagelayer_global_fonts .pagelayer-font-title', function(){
		clearTimeout(pagelayer_global_colors_timmer);
		pagelayer_global_colors_timmer = setTimeout(function(){
			pagelayerGetAllInputs();
		}, 500);
	});
	
	// Add new item
	jQuery('.customize-control-font-repeater-add').click(function(event) {
		event.preventDefault();
		pagelayer_add_row(jQuery(this).parent());
		pagelayerGetAllInputs();
	});

	// Remove item starting from it's parent element
	jQuery('#customize-theme-controls').on('click', '.pagelayer-font-holder .customize-control-font-repeater-delete .dashicons', function(event) {
		event.preventDefault();
		var numItems = jQuery(this).closest('.pagelayer-font-holder').remove();
		pagelayerGetAllInputs();
	});
}

// Padding handler
function pagelayer_control_padding_handler(jEle){
	
	var linked = jEle.find('.dashicons-admin-links');
	var inputs = jEle.find('.pagelayer-padding-input');
	var is_same = true;
	var first_val = jEle.find('.pagelayer-padding-input').first().val();
	
	jEle.find('.pagelayer-padding-input').each(function(){			
		if(jQuery(this).val() == first_val){
			return;
		}
		is_same = false;
		return false;
	});
	
	
	if(is_same){
		linked.addClass('pagelayer-padding-linked');
	}
	
	linked.on('click', function (e){
		jQuery(this).toggleClass('pagelayer-padding-linked');
	});
	
	inputs.on('change', function(){
		
		// Are the values linked		
		if(! linked.hasClass('pagelayer-padding-linked')){
			return;
		}
		
		var val = jQuery(this).val();
		inputs.each(function(){
			jQuery(this).val(val);
			jQuery(this).trigger('input');
		});
	});
	
}

/**
 * Alpha Color Picker JS
 *
 * This file includes several helper functions and the core control JS.
 */
function pagelayer_alpha_color_control_init(){
	
	var timeOut = 0;
	
	// Loop over each control and transform it into our color picker.
	jQuery( '.pagelayer-alpha-color-control' ).each( function() {
		// Scope the vars.
		var $control, startingColor, paletteInput, showOpacity, defaultColor, palette,
			colorPickerOptions, $container, $alphaSlider, alphaVal, sliderOptions;

		// Store the control instance.
		$control = jQuery( this );
		
		if($control.closest('.wp-picker-holder').length > 0){
			return;
		}
			
		setTimeout(function(){			
			// Get a clean starting value for the option.
			startingColor = $control.val().replace( /\s+/g, '' );

			// Get some data off the control.
			paletteInput = $control.attr( 'data-palette' );
			showOpacity  = $control.attr( 'data-show-opacity' );
			defaultColor = $control.attr( 'data-default-color' );

			// Process the palette.
			if ( paletteInput.indexOf( '|' ) !== -1 ) {
				palette = paletteInput.split( '|' );
			} else if ( 'false' == paletteInput ) {
				palette = false;
			} else {
				palette = true;
			}

			// Set up the options that we'll pass to wpColorPicker().
			colorPickerOptions = {
				change: function( event, ui ) {
					var key, value, alpha, $transparency;

					key = $control.attr( 'data-customize-setting-link' );
					value = $control.wpColorPicker( 'color' );

					// Set the opacity value on the slider handle when the default color button is clicked.
					if ( defaultColor == value ) {
						alpha = pagelayer_get_alpha_value_from_color( value );
						$alphaSlider.find( '.ui-slider-handle' ).text( alpha );
					}

					// Send ajax request to wp.customize to trigger the Save action.
					wp.customize( key, function( obj ) {
						obj.set( value );
					});

					$transparency = $container.find( '.transparency' );

					// Always show the background color of the opacity slider at 100% opacity.
					$transparency.css( 'background-color', ui.color.toString( 'no-alpha' ) );
					$control.trigger('color_change');
				},
				palettes: palette // Use the passed in palette.
			};

			// Create the colorpicker.
			$control.wpColorPicker( colorPickerOptions );

			$container = $control.parents( '.wp-picker-container:first' );

			// Insert our opacity slider.
			jQuery( '<div class="alpha-color-picker-container">' +
					'<div class="min-click-zone click-zone"></div>' +
					'<div class="max-click-zone click-zone"></div>' +
					'<div class="alpha-slider"></div>' +
					'<div class="transparency"></div>' +
				'</div>' ).appendTo( $container.find( '.wp-picker-holder' ) );

			$alphaSlider = $container.find( '.alpha-slider' );

			// If starting value is in format RGBa, grab the alpha channel.
			alphaVal = pagelayer_get_alpha_value_from_color( startingColor );

			// Set up jQuery UI slider() options.
			sliderOptions = {
				create: function( event, ui ) {
					var value = jQuery( this ).slider( 'value' );

					// Set up initial values.
					jQuery( this ).find( '.ui-slider-handle' ).text( value );
					jQuery( this ).siblings( '.transparency ').css( 'background-color', startingColor );
				},
				value: alphaVal,
				range: 'max',
				step: 1,
				min: 0,
				max: 100,
				animate: 300
			};

			// Initialize jQuery UI slider with our options.
			$alphaSlider.slider( sliderOptions );

			// Maybe show the opacity on the handle.
			if( 'true' == showOpacity ){
				$alphaSlider.find( '.ui-slider-handle' ).addClass( 'show-opacity' );
			}
			
			// Move input box inside the picker holder
			$control.closest('.wp-picker-input-wrap').each(function () {
				jQuery(this).next('.wp-picker-holder').prepend(jQuery(this));
			});

			// Bind event handlers for the click zones.
			$container.find( '.min-click-zone' ).on( 'click', function() {
				pagelayer_update_alpha_value_on_color_control( 0, $control, $alphaSlider, true );
			});
			
			$container.find( '.max-click-zone' ).on( 'click', function() {
				pagelayer_update_alpha_value_on_color_control( 100, $control, $alphaSlider, true );
			});

			// Bind event handler for clicking on a palette color.
			$container.find( '.iris-palette' ).on( 'click', function() {
				var color, alpha;

				color = jQuery( this ).css( 'background-color' );
				alpha = pagelayer_get_alpha_value_from_color( color );

				pagelayer_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );

				// Sometimes Iris doesn't set a perfect background-color on the palette,
				// for example rgba(20, 80, 100, 0.3) becomes rgba(20, 80, 100, 0.298039).
				// To compensante for this we round the opacity value on RGBa colors here
				// and save it a second time to the color picker object.
				if ( alpha != 100 ) {
					color = color.replace( /[^,]+(?=\))/, ( alpha / 100 ).toFixed( 2 ) );
				}

				$control.wpColorPicker( 'color', color );
			});

			// Bind event handler for clicking on the 'Clear' button.
			$container.find( '.button.wp-picker-clear' ).on( 'click', function() {
				var key = $control.attr( 'data-customize-setting-link' );

				// The #fff color is delibrate here. This sets the color picker to white instead of the
				// defult black, which puts the color picker in a better place to visually represent empty.
				$control.wpColorPicker( 'color', '' );

				// Set the actual option value to empty string.
				wp.customize( key, function( obj ) {
					obj.set( '' );
				});

				pagelayer_update_alpha_value_on_alpha_slider( 100, $alphaSlider );
			});

			// Bind event handler for clicking on the 'Default' button.
			$container.find( '.button.wp-picker-default' ).on( 'click', function() {
				var alpha = pagelayer_get_alpha_value_from_color( defaultColor );

				pagelayer_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );
			});

			// Bind event handler for typing or pasting into the input.
			$control.on( 'input', function() {
				var value = jQuery( this ).val();
				var alpha = pagelayer_get_alpha_value_from_color( value );

				pagelayer_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );
			});

			// Update all the things when the slider is interacted with.
			$alphaSlider.slider().on( 'slide', function( event, ui ) {
				var alpha = parseFloat( ui.value ) / 100.0;

				pagelayer_update_alpha_value_on_color_control( alpha, $control, $alphaSlider, false );

				// Change value shown on slider handle.
				jQuery( this ).find( '.ui-slider-handle' ).text( ui.value );
			});
		}, timeOut);
		
		timeOut += 20;
	});
}

/**
 * Override the stock color.js toString() method to add support for
 * outputting RGBa or Hex.
 */
Color.prototype.toString = function( flag ) {

	// If our no-alpha flag has been passed in, output RGBa value with 100% opacity.
	// This is used to set the background color on the opacity slider during color changes.
	if ( 'no-alpha' == flag ) {
		return this.toCSS( 'rgba', '1' ).replace( /\s+/g, '' );
	}

	// If we have a proper opacity value, output RGBa.
	if ( 1 > this._alpha ) {
		return this.toCSS( 'rgba', this._alpha ).replace( /\s+/g, '' );
	}

	// Proceed with stock color.js hex output.
	var hex = parseInt( this._color, 10 ).toString( 16 );
	if ( this.error ) { return ''; }
	if ( hex.length < 6 ) {
		for ( var i = 6 - hex.length - 1; i >= 0; i-- ) {
			hex = '0' + hex;
		}
	}

	return '#' + hex;
};

/**
 * Given an RGBa, RGB, or hex color value, return the alpha channel value.
 */
function pagelayer_get_alpha_value_from_color( value ) {
	var alphaVal;

	// Remove all spaces from the passed in value to help our RGBa regex.
	value = value.replace( / /g, '' );

	if ( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ ) ) {
		alphaVal = parseFloat( value.match( /rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/ )[1] ).toFixed(2) * 100;
		alphaVal = parseInt( alphaVal );
	} else {
		alphaVal = 100;
	}

	return alphaVal;
}

/**
 * Force update the alpha value of the color picker object and maybe the alpha slider.
 */
function pagelayer_update_alpha_value_on_color_control( alpha, $control, $alphaSlider, update_slider ) {
	var iris, colorPicker, color;

	iris = $control.data( 'a8cIris' );
	colorPicker = $control.data( 'wpWpColorPicker' );

	// Set the alpha value on the Iris object.
	iris._color._alpha = alpha;

	// Store the new color value.
	color = iris._color.toString();

	// Set the value of the input.
	$control.val( color ).trigger('color_change');
	
	// Update the background color of the color picker.
	colorPicker.toggler.css({
		'background-color': color
	});

	// Maybe update the alpha slider itself.
	if ( update_slider ) {
		pagelayer_update_alpha_value_on_alpha_slider( alpha, $alphaSlider );
	}

	// Update the color value of the color picker object.
	$control.wpColorPicker( 'color', color );
}

/**
 * Update the slider handle position and label.
 */
function pagelayer_update_alpha_value_on_alpha_slider( alpha, $alphaSlider ){
	$alphaSlider.slider( 'value', alpha );
	$alphaSlider.find( '.ui-slider-handle' ).text( alpha.toString() );
}

/**
 * Generates random string.
 */

// Generates a random string of "n" characters
function pagelayer_generate_randstr(n, special){
	var text = '';
	var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	special = special || 0;
	if(special){
		possible = possible + '&#$%@';
	}
	
	for(var i=0; i < n; i++){
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	}

	return text;
};


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

// Slider handler
function pagelayer_control_slider_handler(){

	// Change the value of the input field as the slider is moved
	jQuery('.pagelayer-slider').on('input', function(event, ui) {
		var sliderValue = jQuery(this).val();
		jQuery(this).parent().find('.customize-control-slider-value').val(sliderValue).trigger('input');
	});
	
	// Update slider if the input field loses focus as it's most likely changed
	jQuery('.customize-control-slider-value').on('change', function() {
		var resetValue = jQuery(this).val();
		var slider = jQuery(this).parent().find('.pagelayer-slider');
		var sliderMinValue = parseInt(slider.attr('min'));
		var sliderMaxValue = parseInt(slider.attr('max'));

		// Make sure our manual input value doesn't exceed the minimum & maxmium values
		if(resetValue < sliderMinValue) {
			resetValue = sliderMinValue;
			jQuery(this).val(resetValue).trigger('input');
		}
		if(resetValue > sliderMaxValue) {
			resetValue = sliderMaxValue;
			jQuery(this).val(resetValue).trigger('input');
		}
		slider.val(resetValue);
	});
}