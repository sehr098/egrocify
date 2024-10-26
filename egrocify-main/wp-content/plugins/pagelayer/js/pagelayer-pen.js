/* Pagelayer Pen editor */
var pagelayer_customColor = ["#000000", "#e60000", "#ff9900", "#ffff00", "#008a00", "#0066cc", "#9933ff", "#ffffff", "#facccc", "#ffebcc", "#ffffcc", "#cce8cc", "#cce0f5", "#ebd6ff", "#bbbbbb", "#f06666", "#ffc266", "#ffff66", "#66b966", "#66a3e0", "#c285ff", "#888888", "#a10000", "#b26b00", "#b2b200", "#006100", "#0047b2", "#6b24b2", "#444444", "#5c0000", "#663d00", "#666600", "#003700", "#002966", "#3d1466"];

var pagelayer_pen_sizeList = ['normal', 'x-small', 'small', 'medium', 'large', 'x-large'];
var pagelayer_pen_lineHeight = ['0.9', '1', '1.5', '2.0', '2.5','3.0', '3.5', '4.0', '4.5', '5.0'];

class PagelayerPen{

	constructor(jEle, options) {
		var t = this;
		
		t.editor = jQuery(jEle);
		t.options = options;
		
		// Get the document of the element. It use to makes the plugin
        // compatible on iframes.
		t.doc = jEle.ownerDocument || document;
		t.tagToButton = {};
		t.optionsCounter = 0;
		t.destroyEd = true;
		t.semantic = null;
		t.DEFAULT_SEMANTIC_MAP = {
			'b': 'strong',
			'i': 'em',
			's': 'strike',
			//'strike': 'del',
			'div': 'p'
		};

		// Init editor
		t.addHandlers();
		t.init();
		
	}
	
	init(){
		var t = this;
		// Init Editor
		t.editor.addClass('pagelayer-pen');
		t.penHolder = t.addContainer();
		t.addEvents();
	}
	
	addHandlers(){
		// TODO : Add for custom plugins
		// TODO remove all execCommands
		this.handlers = {
			bold:{
				tag: 'STRONG',
				icon: '<strong><i class="fas fa-bold"></i></strong>'
			},
			italic:{
				tag: 'EM',
				icon: '<strong><i class="fas fa-italic"></i></strong>'
			},
			underline:{
				tag: 'U',
				icon: '<strong><i class="fas fa-underline"></i></strong>'
			},
			strike:{
				tag: 'strike',
				fn: 'strikethrough',
				icon: '<strong><i class="fas fa-strikethrough"></i></strong>'
			},
			h1:{
				fn: 'formatBlock',
				icon: '<strong>H<sub>1</sub></strong>'
			},
			h2:{
				fn: 'formatBlock',
				icon: '<strong>H<sub>2</sub></strong>'
			},
			h3:{
				fn: 'formatBlock',
				icon: '<strong>H<sub>3</sub></strong>'
			},
			h4:{
				fn: 'formatBlock',
				icon: '<strong>H<sub>4</sub></strong>'
			},
			h5:{
				fn: 'formatBlock',
				icon: '<strong>H<sub>5</sub></strong>'
			},
			h6:{
				fn: 'formatBlock',
				icon: '<strong>H<sub>6</sub></strong>'
			},
			p:{
				fn: 'formatBlock',
				icon: '<strong><i class="fas fa-paragraph"></i></strong>'
			},
			blockquote:{
				fn: 'formatBlock',
				icon: '<strong><i class="fas fa-quote-right"></i></strong>'
			},
			formating:{
				fn: 'formatBlock',
				fixIcon: '<strong><i class="fas fa-paragraph"></i></strong>'
			},
			unorderedlist:{
				tag: 'UL',
				fn: 'insertUnorderedList',
				icon: '<strong><i class="fas fa-list-ul"></i></i></strong>'
			},
			orderedlist:{
				tag: 'OL',
				fn: 'insertOrderedList',
				icon: '<strong><i class="fas fa-list-ol"></i></i></strong>'
			},
			sub:{
				tag: 'sub',
				fn: 'subscript',
				icon: '<strong><i class="fas fa-subscript"></i></strong>'
			},
			super:{
				tag: 'sup',
				fn: 'superscript',
				icon: '<strong><i class="fas fa-superscript"></i></strong>'
			},
			link:{
				fn: 'setLinkHandler',
				tag: 'a',
				icon: '<strong><i class="fas fa-link"></i></strong>',
			},
			image:{
				fn: 'imageBtnHandler',
				icon: '<i class="far fa-image"></i>'
			},
			align:{
				style: 'text-align',
				fn: 'formatBlock',
				icon: {
					'left': '<i class="fas fa-align-left"></i>',
					'center': '<i class="fas fa-align-center"></i>',
					'right': '<i class="fas fa-align-right"></i>',
					'justify': '<i class="fas fa-align-justify"></i>',
				}
			},
			color:{
				class: 'pagelayer-pen-color-picker',
				style: 'color',
				fn: 'commandHandler',
				fixIcon: '<svg viewbox=\"0 0 18 18\"> <line class=\"pagelayer-pen-color-label pagelayer-pen-stroke pagelayer-pen-transparent\" x1=3 x2=15 y1=15 y2=15></line> <polyline class=pagelayer-pen-stroke points=\"5.5 11 9 3 12.5 11\"></polyline> <line class=pagelayer-pen-stroke x1=11.63 x2=6.38 y1=9 y2=9></line> </svg>',
				buildBtn : 'buildColorBtnHandler',
				default : pagelayer_customColor,
				customInpute: true
			},
			background:{
				class: 'pagelayer-pen-color-picker',
				style: 'background-color',
				fn: 'commandHandler',
				fixIcon: '<svg viewbox=\"0 0 18 18\"> <g class=\"pagelayer-pen-fill pagelayer-pen-color-label\"> <polygon points=\"6 6.868 6 6 5 6 5 7 5.942 7 6 6.868\"></polygon> <rect height=1 width=1 x=4 y=4></rect> <polygon points=\"6.817 5 6 5 6 6 6.38 6 6.817 5\"></polygon> <rect height=1 width=1 x=2 y=6></rect> <rect height=1 width=1 x=3 y=5></rect> <rect height=1 width=1 x=4 y=7></rect> <polygon points=\"4 11.439 4 11 3 11 3 12 3.755 12 4 11.439\"></polygon> <rect height=1 width=1 x=2 y=12></rect> <rect height=1 width=1 x=2 y=9></rect> <rect height=1 width=1 x=2 y=15></rect> <polygon points=\"4.63 10 4 10 4 11 4.192 11 4.63 10\"></polygon> <rect height=1 width=1 x=3 y=8></rect> <path d=M10.832,4.2L11,4.582V4H10.708A1.948,1.948,0,0,1,10.832,4.2Z></path> <path d=M7,4.582L7.168,4.2A1.929,1.929,0,0,1,7.292,4H7V4.582Z></path> <path d=M8,13H7.683l-0.351.8a1.933,1.933,0,0,1-.124.2H8V13Z></path> <rect height=1 width=1 x=12 y=2></rect> <rect height=1 width=1 x=11 y=3></rect> <path d=M9,3H8V3.282A1.985,1.985,0,0,1,9,3Z></path> <rect height=1 width=1 x=2 y=3></rect> <rect height=1 width=1 x=6 y=2></rect> <rect height=1 width=1 x=3 y=2></rect> <rect height=1 width=1 x=5 y=3></rect> <rect height=1 width=1 x=9 y=2></rect> <rect height=1 width=1 x=15 y=14></rect> <polygon points=\"13.447 10.174 13.469 10.225 13.472 10.232 13.808 11 14 11 14 10 13.37 10 13.447 10.174\"></polygon> <rect height=1 width=1 x=13 y=7></rect> <rect height=1 width=1 x=15 y=5></rect> <rect height=1 width=1 x=14 y=6></rect> <rect height=1 width=1 x=15 y=8></rect> <rect height=1 width=1 x=14 y=9></rect> <path d=M3.775,14H3v1H4V14.314A1.97,1.97,0,0,1,3.775,14Z></path> <rect height=1 width=1 x=14 y=3></rect> <polygon points=\"12 6.868 12 6 11.62 6 12 6.868\"></polygon> <rect height=1 width=1 x=15 y=2></rect> <rect height=1 width=1 x=12 y=5></rect> <rect height=1 width=1 x=13 y=4></rect> <polygon points=\"12.933 9 13 9 13 8 12.495 8 12.933 9\"></polygon> <rect height=1 width=1 x=9 y=14></rect> <rect height=1 width=1 x=8 y=15></rect> <path d=M6,14.926V15H7V14.316A1.993,1.993,0,0,1,6,14.926Z></path> <rect height=1 width=1 x=5 y=15></rect> <path d=M10.668,13.8L10.317,13H10v1h0.792A1.947,1.947,0,0,1,10.668,13.8Z></path> <rect height=1 width=1 x=11 y=15></rect> <path d=M14.332,12.2a1.99,1.99,0,0,1,.166.8H15V12H14.245Z></path> <rect height=1 width=1 x=14 y=15></rect> <rect height=1 width=1 x=15 y=11></rect> </g> <polyline class=pagelayer-pen-stroke points=\"5.5 13 9 5 12.5 13\"></polyline> <line class=pagelayer-pen-stroke x1=11.63 x2=6.38 y1=11 y2=11></line> </svg>',
				buildBtn: 'buildColorBtnHandler',
				default : pagelayer_customColor,
				customInpute: true
			},
			size:{
				class: 'pagelayer-pen-size-picker',
				style: 'font-size',
				fn: 'commandHandler',
				default : pagelayer_pen_sizeList,
				customInpute: true
			},
			lineheight:{
				style: 'line-height',
				fn: 'commandHandler',
				fixIcon: '<svg viewBox="0 0 22 18" version="1.1"><g><path class="pagelayer-pen-fill" d="M 21.527344 7.875 L 9.269531 7.875 C 9.011719 7.875 8.800781 8.125 8.800781 8.4375 L 8.800781 9.5625 C 8.800781 9.875 9.011719 10.125 9.269531 10.125 L 21.527344 10.125 C 21.789062 10.125 22 9.875 22 9.5625 L 22 8.4375 C 22 8.125 21.789062 7.875 21.527344 7.875 Z M 21.527344 13.5 L 9.269531 13.5 C 9.011719 13.5 8.800781 13.75 8.800781 14.0625 L 8.800781 15.1875 C 8.800781 15.5 9.011719 15.75 9.269531 15.75 L 21.527344 15.75 C 21.789062 15.75 22 15.5 22 15.1875 L 22 14.0625 C 22 13.75 21.789062 13.5 21.527344 13.5 Z M 21.527344 2.25 L 9.269531 2.25 C 9.011719 2.25 8.800781 2.5 8.800781 2.8125 L 8.800781 3.9375 C 8.800781 4.25 9.011719 4.5 9.269531 4.5 L 21.527344 4.5 C 21.789062 4.5 22 4.25 22 3.9375 L 22 2.8125 C 22 2.5 21.789062 2.25 21.527344 2.25 Z M 6.050781 5.0625 C 6.542969 5.0625 6.785156 4.453125 6.4375 4.101562 L 3.6875 1.289062 C 3.472656 1.070312 3.125 1.070312 2.910156 1.289062 L 0.160156 4.101562 C -0.160156 4.429688 0.0117188 5.0625 0.550781 5.0625 L 2.199219 5.0625 L 2.199219 12.9375 L 0.550781 12.9375 C 0.0585938 12.9375 -0.183594 13.546875 0.160156 13.898438 L 2.910156 16.710938 C 3.125 16.929688 3.476562 16.929688 3.691406 16.710938 L 6.441406 13.898438 C 6.757812 13.570312 6.585938 12.9375 6.050781 12.9375 L 4.398438 12.9375 L 4.398438 5.0625 Z M 6.050781 5.0625 "/></g></svg>',
				default : pagelayer_pen_lineHeight,
				customInpute: true
			},
			font:{
				style: 'font-family',
				fn: 'commandHandler',
				fixIcon: '<i class="fas fa-font"></i>',
				default : pagelayer_fonts,
				buildBtn : 'buildfontBtnHandler',
			},
			viewHTML:{
				fn: 'viewHTMLBtnHandler',
				icon: '<i class="fas fa-code"></i>'
			},
			removeformat:{
				icon: '<i class="fas fa-remove-format"></i>'
			}
		}
	}
	
	addContainer(className){
		
		className = className || false;
		
		// Add Container
		var container = jQuery('.pagelayer-pen-holder');
		
		if(container.length < 1){
			jQuery('body').append('<div class="pagelayer-pen-holder"></div>');
			container = jQuery('.pagelayer-pen-holder');
		}
		
		if(!className){
			return container;
		}
		
		if(container.find('.'+className).length < 1){
			container.append('<div class="'+className+'"></div>');
		}
		
		return container.find('.'+className);
		
	}
	
	addToolbar(){
		
		// Add Toolbar
		var t = this;
		var groups = t.options.toolbar;    
		var toolbar = t.toolbar = t.addContainer('pagelayer-pen-toolbar');
		
		// Make it empty
		toolbar.empty();
		
		if (!Array.isArray(groups[0])) {
			groups = [groups];
		}
		
		var addButton = function(container, format, value){
			
			var btn = t.handlers[format];
			var icon = '';
			
			if('icon' in btn){
				var _icon = btn['icon'];
				
				if(typeof _icon == 'object' && !pagelayer_empty(_icon[value])){
					icon = _icon[value];
				}else if(typeof icon == 'string'){
					icon = _icon;
				}
			}
			
			var input = document.createElement('button');
			input.setAttribute('type', 'button');
			input.setAttribute('data-format', format);
			input.classList.add('pagelayer-pen-' + format);
			
			if('class' in btn){
				input.classList.add(btn['class']);
			}
			
			if( pagelayer_empty(value) && 'default' in btn ){
				value = btn['default'];
			}
			
			input.innerHTML = icon;
			if(value != null) {
				input.value = value;
			}
			container.appendChild(input);
		}
		
		var createoption = function(val, lang, type){
			type = type || '';
			var lang = pagelayer_empty(lang) ? 'Default' : lang;
			return '<option  value="'+val+'" type="'+type+'">'+lang+'</option>';
		}
		
		var addSelect = function(container, format, values) {
			
			var input = document.createElement('select');
			input.classList.add('pagelayer-pen-' + format);
			
			if('class' in t.handlers[format]){
				input.classList.add(t.handlers[format]['class']);
			}
			
			input.setAttribute('data-format', format);
			
			if( pagelayer_empty(values) && 'default' in t.handlers[format] ){
				values = t.handlers[format]['default'];
			}
			
			for(var kk in values){
				var options = '';
				var value = values[kk];
				
				if(typeof value == 'object') {
					if(kk != 'default'){
						options += '<optgroup label="'+pagelayer_ucwords(kk)+'">';
					}
					for(y in value){
						options += createoption((jQuery.isNumeric(y) ? value[y] : x), value[y], kk);
					}		
				}else if(value !== false) {
					options += createoption(value, value);
				} else {
					options += createoption('', '');
				}

				jQuery(input).append(options);
			}

			container.appendChild(input);
		}
		
		groups.forEach(function(controls){
			var group = document.createElement('span');
			group.classList.add('pagelayer-pen-formats');

			controls.forEach(function (control){
				var format = control;
				
				if(typeof control === 'object'){
					format = Object.keys(control)[0];
				}
				
				if( pagelayer_empty(t.handlers[format]) ){
					return;
				}

				if( typeof control === 'string' ){
					addButton(group, control);
				} else {
					var value = control[format];
					if (Array.isArray(value)) {
						addSelect(group, format, value);
					} else {
						addButton(group, format, value);
					}
				}
				
				var btn = t.handlers[format];
				t.tagToButton[(btn.tag || btn.style || format).toLowerCase()] = format;
			});
			
			// TODO skip if format is not exist
			toolbar[0].appendChild(group);
		});
		
		toolbar.find('button').on('click', function(){
			var bEle = jQuery(this);
			var format = bEle.data('format');
			
			if(! format in t.handlers){
				return;
			}
			
			var btn = t.handlers[format];
			t.currentFormat = format;
			t.execCmd(btn.fn || format, btn.param || format, btn.forceCss);
		});
		
		toolbar.find('select').on('change', function(e){
			var bEle = jQuery(this);
			var format = bEle.data('format');
			var val = bEle.val();
			
			if(! format in t.handlers){
				return;
			}
			
			var btn = t.handlers[format];
			t.currentFormat = format;
			t.execCmd(btn.fn || format, val, btn.forceCss);
		});
		
		toolbar.find('select').each(function(){
			var format = jQuery(this).data('format');
			
			if('buildBtn' in t.handlers[format]){
					
				try{
					t[t.handlers[format]['buildBtn']](this);
				}catch(e){
					try{
						t.handlers[format]['buildBtn'](this);
					}catch(e2){
						t.buildDropdown(this);
					}
				}
				
				return true;
			}
			
			t.buildDropdown(this);
		});
		
		// Add close button
		toolbar.append('<span class="pagelayer-pen-formats"><button class="pagelayer-pen-close"><i class="fas fa-times"></i></button></span>');
		
		// Hide editor on click close tool handler
		toolbar.find('.pagelayer-pen-close').on('mousedown', function(e){
			//e.preventDefault();
			t.destroyEd = true;
			t.editor.trigger('blur');
		});
				
	}
	
	execCmd(cmd, param, forceCss, skipPen){
		var t = this;
		skipPen = !!skipPen || '';

		if(cmd !== 'dropdown'){
			t.focus();
			t.restoreRange();
		}

		try{
			document.execCommand('styleWithCSS', false, forceCss || false);
		}catch(c){}

		try{
			t[cmd + skipPen](param);
		}catch(c){
			try{
				cmd(param);
			}catch(e2){
				if(cmd === 'insertHorizontalRule'){
					param = undefined;
				}else if (cmd === 'formatBlock'){ // TODO: check for && t.isIE
					param = '<' + param + '>';
				}				
			
				document.execCommand(cmd, false, param);
				t.semanticCode();
				t.restoreRange();
			}
		}
					
		if(cmd !== 'dropdown'){
			t.updateButtonStatus();
			t.editor.trigger('input');
		}
		
	}
	
	commandHandler(value){
		var t = this;
		var format = t.currentFormat;
		
		if( pagelayer_empty(format) ){
			return;
		}
		
		var btn = t.handlers[format];
		var sel = window.getSelection();
		var text = t.range.commonAncestorContainer;
		var selectedText = t.range.cloneContents();
		selectedText = jQuery('<div>').append(selectedText).html();
		
		// Also select the tag
		if(text.nodeType === Node.TEXT_NODE){
			text = text.parentNode;
		}
		
		if (text.innerHTML === selectedText && text != t.editor[0]) {
			var ele = jQuery(text);
			if('tag' in btn){
				// Replace tag
			}else if('style' in btn){
				var style = {};
				style[btn.style] = value;

				ele.css(style);
			}else if('atts' in btn){
				// Add attribute or toggle the element
			}
		} else {
			
			// TODO for toggle tags and add tags
			var html = jQuery('<span style="'+btn.style+':' + value + ';">' + selectedText + '</span>');
			
			// Remove style from all childrend
			var style = {};
			style[btn.style] = '';
			html.find('[style]').css(style);
			// TODO: remove span element that have no atts
			var node = html[0];
			var firstInsertedNode = node.firstChild;
			var lastInsertedNode = node.lastChild;
			t.range.deleteContents();
			t.range.insertNode(node);

			if(firstInsertedNode) {
				t.range.setStartBefore(firstInsertedNode);
				t.range.setEndAfter(lastInsertedNode);
			}
			
			// Is previous element empty?
			var prev = jQuery(node).prev();
			
			if( prev.length > 0 && prev.is(':empty') ){
				prev.remove();
			}
		}
		
		sel.removeAllRanges();
		sel.addRange(t.range);
		
	}
	
	formatBlock(value){
		
		var t = this,
			format = t.currentFormat,
			btn = t.handlers[format],
			startNode = t.range.startContainer,
			endNode = t.range.endContainer;
		
		if( startNode.nodeType == Node.TEXT_NODE && startNode.parentNode != t.editor[0] ){
			startNode = startNode.parentNode;
		}
		
		if( endNode.nodeType == Node.TEXT_NODE && endNode.parentNode != t.editor[0] ){
			endNode = endNode.parentNode;
		}
		
		// TODO: only for seleced content
		// Wrap text nodes in span for easier processing
		t.editor.contents().filter(function () {
			return this.nodeType === 3 && this.nodeValue.trim().length > 0;
		}).wrap('<span data-pts/>');
		
		var isLineEnd = function(lEle){
			return lEle == null || lEle.nodeName == 'BR' || t.isline(lEle);
		}

		var wrapLine = function(pLine){
			
			var pLine = jQuery(pLine),
				lineFele,
				lineEele,
				finalP;
			
			// Get Parent Element
			if(pLine.parentsUntil(t.editor).length > 0){
				pLine = pLine.parentsUntil(t.editor).last();
			}
			
			if(t.isline(pLine)){
				return pLine;
			}
			
			// Get line first element
			if(isLineEnd(pLine[0].previousSibling)){
				lineFele = pLine;
			}else{
				lineFele = pLine.prevAll().filter(function(){
					return isLineEnd(this.previousSibling);
				}).first();
			}
			
			// Get line last element
			if(isLineEnd(lineFele[0].nextSibling)){
				lineEele = lineFele;
			}else{
				lineEele = lineFele.nextAll().filter(function(){
					return isLineEnd(this.nextSibling);
				}).first();
			}
			
			// Wrap all with p tag			
			if(lineFele.is(lineEele)){
				finalP = lineFele.wrap('<p/>').parent()
			}else{
				finalP = lineFele.nextUntil(lineEele.next()).addBack().wrapAll('<p/>').parent();
			}
			
			finalP.next('br').remove();
			return finalP;
		}
		
		// Get start block lavel elements
		var $sNode = jQuery(t.blockNode(startNode));
		if($sNode.is(t.editor)){
			$sNode = wrapLine(startNode);
		}
		
		var $eNode = jQuery(t.blockNode(endNode));
		if($eNode.is(t.editor)){
			$eNode = wrapLine(endNode);
		}
		
		var $oldEle = $sNode;
		
		if(! $sNode.is($eNode) ){
			
			var findEnd = false;
      
			var addElement = function(addEle){
				if(addEle[0].nodeName == 'UL' || addEle[0].nodeName == 'OL') {
					addEle.children().each(function(){
						$oldEle = $oldEle.add(jQuery(this));
					});
					return;
				}
				$oldEle = $oldEle.add(addEle);
			}
			
			var wrapAllEle = function(nextEle){
				
				if(nextEle.is($eNode) || nextEle.find($eNode).length > 0){
					findEnd = true;
					return;
				}
				
				if(nextEle.length < 1){
					return;
				}
				
				if(!t.isline(nextEle[0])){
					nextEle = wrapLine(nextEle);
				}
				
				addElement(nextEle);				
				wrapAllEle( nextEle.next() );
			}
			
			wrapAllEle($sNode.next());
			
			// Is start Element have a another parent
			var pars = $sNode.parentsUntil(t.editor);
			
			pars.each(function(){
				var $par = jQuery(this);
				wrapAllEle($par.next());
			});
			
			if( pars.length > 0 ){
				$sNode = pars.last();
			}
			
			var nextEnd = $sNode.nextAll().filter(function(){
				return jQuery(this).is($eNode) || jQuery(this).find($eNode).length > 0;
			}).first();
      
			// Add elements
			if( nextEnd.length > 0 ){
				var $nextEle = $sNode.nextUntil(nextEnd);
				$nextEle.each(function(){
					var ulEle = jQuery(this);
					if($oldEle.has(ulEle)) return;
					addElement(ulEle);
				});
			}
			
			// Add end element
			if(nextEnd.length > 0 && !nextEnd.is($eNode) && (nextEnd[0].nodeName == 'UL' || nextEnd[0].nodeName == 'OL')){
				nextEnd.children().each(function(){
					var li = jQuery(this);
					$oldEle = $oldEle.add(li);
					if(li.is($eNode) || li.find($eNode).length > 0) return false;
				});
			}else{
				$oldEle = $oldEle.add($eNode);
			}
		}
		
		if('style' in btn){
			var style = {};
			style[btn.style] = value;

			$oldEle.css(style);
		}else if('atts' in btn){
			// Add attribute or toggle the element
			var attr = {};
			attr[btn.atts] = value;
			
			$oldEle.attr(attr);
		}else{
			// Replace tag
			var tag = value.toLowerCase();
			
			// need to find all block ele and replace this
			$oldEle.each( function(){
				
				var $cEle = jQuery(this);
				
				if($cEle.is(t.editor)){
					return;
				}
				
				// Is List element
				if($cEle.css('display') == 'list-item'){
					if( t.isline($cEle[0].firstChild)){
						$cEle.children().each(function(){
							var liChild = jQuery(this);
							
							if(t.isline(liChild[0])){
								t.replaceTag(liChild, tag, true);
								return;
							}
							// TODO: Check and need to correct
							liChild.wrap('<' + tag + '/>');
							liChild.next('br').remove();
							
						});
						return
					}
						
					$cEle.contents().wrapAll('<' + tag + '/>');
					return;
				}
				
				t.replaceTag($cEle, tag, true);
			});
		}
		
		// Get rid of pen temporary span's
		jQuery('[data-pts]', t.editor).contents().unwrap();
		t.semanticCode();
		t.restoreRange();
	}
	
	blockNode( node ){
		var t = this;
		while( !t.isline(node) && node != t.editor[0] ) {
			node = node.parentNode;
		}
		return node;
	}
	
	isline(node){
		if (node.nodeType !== Node.ELEMENT_NODE) return false;
		if (node.childNodes.length === 0) return false; // Exclude embed blocks
		var style = window.getComputedStyle(node);
		return ['block', 'list-item'].indexOf(style.display) > -1;
	}
	
	replaceTag(ele, tag, copyAttr){
		ele.wrap('<' + tag + '/>');
		
		var par = ele.parent();
		
		if(copyAttr){
			jQuery.each(ele.prop('attributes'), function () {
				par.attr(this.name, this.value);
			});
		}
    
		ele.contents().unwrap();
    
		return par;
	}
	
	semanticCode(){
		var t = this;
		t.semanticTag('b');
		t.semanticTag('i');
		t.semanticTag('s');
		t.semanticTag('strike');
		t.semanticTag('div', true);
	}
	
	semanticTag(oldTag, copyAttributes){
		var t = this;
		var newTag;

		if(t.semantic != null && typeof t.semantic === 'object' && t.semantic.hasOwnProperty(oldTag)){
			newTag = t.semantic[oldTag];
		} else if (t.DEFAULT_SEMANTIC_MAP.hasOwnProperty(oldTag)) {
			newTag = t.DEFAULT_SEMANTIC_MAP[oldTag];
		} else {
			return;
		}

		jQuery(oldTag, t.editor).each(function () {
			var $oldTag = jQuery(this);
			if($oldTag.contents().length === 0) {
				return false;
			}
			
			t.replaceTag($oldTag, newTag, copyAttributes);
		});
	}
	
	addEvents(){
		// Add Events
		var t = this,
		editor = t.editor,
		ctrl = false,
		debounceButtonStatus;
		
		var showToolBar = function(){
			
			var jEle = t.penHolder.children(':visible');
			
			if(jEle.length < 1){
				jEle = t.toolbar;
			}
			
			t.showPen(jEle);
		};
		
		// Save rage
		editor.on('focusout', function(e){
			
			if(t.destroyEd){
				t.editor.removeClass('pagelayer-pen-focused');
				t.range = null;
				return;
			}
			
			t.saveRange();

		});
		
		// Prevent to hide toolbar
		t.penHolder.on('mousedown', function(e){
			// TODO: taget only require Element
			t.destroyEd = false;
		});
		
		// On editor blur
		editor.on('blur', function(){
			
			if(!t.destroyEd){
				return;
			}
			
			t.destroy();
		});
		
		editor.on('keydown', function(){
			t.penHolder.hide();
		});
		
		editor.on('mousedown', function(){
			if(t.editor.attr('contenteditable') == 'true'){
				t.showPen();
			}
		});
		
		editor.on('mouseup keyup keydown', function(e){
			if ((!e.ctrlKey && !e.metaKey) || e.altKey) {
				setTimeout(function () { // "hold on" to the ctrl key for 50ms
					ctrl = false;
				}, 50);
			}

			clearTimeout(debounceButtonStatus);
			debounceButtonStatus = setTimeout(function () {
				t.updateButtonStatus();
			}, 50);
			
		});
		
		// Set focus on editor
		editor.on('click', function(e){
			
			if(t.editor.hasClass('pagelayer-pen-focused')){
				return;
			}
			
			t.editor.attr('contenteditable', 'true');
			t.editor.focus();
		});
		
		// Set focus on editor
		editor.on('focus', function(){
			t.destroyEd = true;
			t.addToolbar();
			t.showPen();
			t.editor.addClass('pagelayer-pen-focused');
			jQuery(window).unbind('scroll.penToobar');
			jQuery(window).on('scroll.penToobar', showToolBar);
			jQuery(document).unbind('mousemove.penToobar');
			jQuery(document).on('mousemove.penToobar', showToolBar);
		});
		
		t.semanticCode();
	}
	
	destroy(){
		var t = this;
		//t.editor.attr('contenteditable', '');
		t.penHolder.hide();
		// Removing event listeners
		jQuery(document).unbind('mousemove.penToobar');
		jQuery(window).unbind('scroll.penToobar');
	}
	
	hasFocus(){
		var t = this;
		return (
		t.doc.activeElement === t.editor ||
		t.contains( t.editor[0], t.doc.activeElement)
		);
	}
	
	contains(parent, descendant) {
		try {
			// Firefox inserts inaccessible nodes around video elements
			descendant.parentNode; // eslint-disable-line no-unused-expressions
		} catch (e) {
			return false;
		}
		return parent.contains(descendant);
	}
	
	saveRange(){
		var t = this,
		selection = t.doc.getSelection();

		t.range = null;

		if (!selection || !selection.rangeCount) {
			return;
		}

		var savedRange = t.range = selection.getRangeAt(0),
			range = t.doc.createRange(),
			rangeStart;
		range.selectNodeContents(t.editor[0]);
		range.setEnd(savedRange.startContainer, savedRange.startOffset);
		rangeStart = (range + '').length;
		t.metaRange = {
			start: rangeStart,
			end: rangeStart + (savedRange + '').length
		};
	}
	
	restoreRange(){
		var t = this,
			metaRange = t.metaRange,
			savedRange = t.range,
			selection = t.doc.getSelection(),
			range;

		if(!savedRange){
			return;
		}

		if(metaRange && metaRange.start !== metaRange.end){ // Algorithm from http://jsfiddle.net/WeWy7/3/
			var charIndex = 0,
				nodeStack = [t.editor[0]],
				node,
				foundStart = false,
				stop = false;

			range = t.doc.createRange();

			while(!stop && (node = nodeStack.pop())){
				if (node.nodeType === 3){
					var nextCharIndex = charIndex + node.length;
					if (!foundStart && metaRange.start >= charIndex && metaRange.start <= nextCharIndex) {
						range.setStart(node, metaRange.start - charIndex);
						foundStart = true;
					}
					if (foundStart && metaRange.end >= charIndex && metaRange.end <= nextCharIndex) {
						range.setEnd(node, metaRange.end - charIndex);
						stop = true;
					}
					charIndex = nextCharIndex;
				} else {
					var cn = node.childNodes,
					i = cn.length;

					while (i > 0) {
						i -= 1;
						nodeStack.push(cn[i]);
					}
				}
			}
		}

		selection.removeAllRanges();
		selection.addRange(range || savedRange);
	}
	
	getRange(){
		var t = this;
		var selection = t.doc.getSelection();
		if (selection == null || selection.rangeCount <= 0) return null;
		var range = selection.getRangeAt(0);
		if(range == null) return null;
		
		return range;
	}
	
	getRangeText(range){
		return range + '';
	}
	
	focus(){
		var t = this;
		if(t.hasFocus()) return;
		t.editor.click();
		t.editor.focus();
		t.restoreRange();
	}
	
	getBounds(range){
		var rect = range.getBoundingClientRect();
		return {
			bottom: rect.top + rect.height,
			height: rect.height,
			left: rect.left,
			right: rect.right,
			top: rect.top,
			width: 0
		};
	}
	
	showPen(jEle){
		var t = this;
		jEle = jEle || jQuery(t.toolbar);
		
		var toolBar = jQuery(t.penHolder);
		var tooltipHeight = parseInt(toolBar.css('height'));
		var range = null;
		
		if(! t.hasFocus() && t.range != null){
			range = t.range;
		}else{
			range = t.getRange();
		}
		
		if(range == null){
			toolBar.hide();
			return;
		}
		
		// Set left of toolbar
		var editorOffset = t.editor[0].getBoundingClientRect();
		var editorTop = editorOffset.top;
		var editorLeft = editorOffset.left;
		var editorbottom = editorTop + editorOffset.height - tooltipHeight;
		var toolBarTop = editorTop - 10;
		var bound = t.getBounds(range);
		
		if(bound.height == 0 && bound.top == 0 && bound.left == 0){
			toolBar.hide();
			return;
		}
		
		var boundTop = bound.top - 15;
    	
		// Set top of toolbar
		if( boundTop - tooltipHeight < 0 && bound.bottom > -5){
			toolBarTop = bound.bottom + tooltipHeight + 15;
		}else if( editorbottom - 30 < 0 ){
			toolBarTop =  editorbottom + 20;
		}else if( toolBarTop - tooltipHeight < 0 ){
			toolBarTop = tooltipHeight + 10;
		}
		
		// Show Toolbar
		toolBar.children().hide();
		toolBar.show();
		jEle.show();
		
		// Set top of toolbar
		toolBar.css('top', toolBarTop);
		
		// Set left of toobar
		var docW = jQuery(window).width() - 10;
		var toolW = toolBar.width();
		var edW = t.editor.width();
		
		if(toolW > edW){
			editorLeft = editorLeft - (toolW - edW) / 2
		}
				
		toolBar.css('left', editorLeft+'px');
		
		var tooltipLeft = toolBar.offset().left;
				
		if(tooltipLeft < 0){
			toolBar.css('left', '1px');
		}
		
		var toolRight = tooltipLeft + toolW;
		if(docW < toolRight){
			toolBar.css('left', tooltipLeft - (toolRight - docW)+'px');
		}
		
	}
	
	getContent(){
		var editor = this.editor;
		var html = editor.html();
		
		return html;
	}
	
	setContent(html){
		var t = this;
		html = html || '';
		t.editor.html(html);
		t.editor.trigger('input');
	}
	
	updateButtonStatus(){
		var t = this,
		toolbar = jQuery(t.toolbar),
		tags = t.getTagsRecursive(t.doc.getSelection().focusNode),
		activeClasses = 'pagelayer-pen-active';
		
		jQuery('.' + activeClasses, toolbar).removeClass(activeClasses);
		jQuery.each(tags, function (i, tag){
			var btnName;
			
			if(pagelayer_is_string(tag)){
				btnName = t.tagToButton[tag.toLowerCase()];
			}else{
				btnName = t.tagToButton[Object.keys(tag)[0].toLowerCase()]
			}
			
			var $btn = jQuery('[data-format="'+btnName+'"]', toolbar);
	
			if($btn.length < 1){
				return;
			}
			
			if($btn.find('.pagelayer-pen-picker-label').length > 0){
				$btn.find('.pagelayer-pen-picker-label').addClass(activeClasses);
				return;
			}
			
			$btn.addClass(activeClasses);
		});
	}
	
	getTagsRecursive(element, tags) {
		var t = this;
		var jEle = jQuery(element);
		tags = tags || (element && element.tagName ? [element.tagName] : []);

		if (element && element.parentNode) {
			element = element.parentNode;
		} else {
			return tags;
		}

		var tag = element.tagName;
		// Is this editor
		if (tag === 'DIV') {
			return tags;
		}
		
		// TODO: for all block element
		if (tag === 'P' && element.style.textAlign !== '') {
			tags.push(element.style.textAlign);
		}

		jQuery.each(t.tagHandlers, function (i, tagHandler) {
			tags = tags.concat(tagHandler(element, t));
		});
		
		tags.push(tag);
		var styles = jEle.attr('style');
		
		if(!pagelayer_empty(styles)){
			var styles = styles.split(';');

			jQuery.each(styles, function(i, style){
				style = style.split(':');
				var ss = String(style[0]).trim();
				var vv = String(style[1]).trim();

				if(pagelayer_empty(ss) || ss in tags && !pagelayer_empty(tags[ss])){
					return;
				}
				
				var obj = {};
				obj[ss] = vv;
				
				tags.push(obj);
			});
		}

		return t.getTagsRecursive(element, tags).filter(function (tag) {
			return tag != null;
		});
	}
	
	buildDropdown(select){
		
		var t = this;
		var fixIcon = '';
		
		select = jQuery(select);		
		var format = select.data('format');
		
		var selAtts = '';
		var options = '';
		var optId = `pagelayer-pen-picker-options-${t.optionsCounter}`;
		t.optionsCounter += 1;
		
		Array.from(select[0].attributes).forEach(item => {
			selAtts += ' '+item.name+'="'+ item.value +'"';
		});
		
		Array.from(select[0].options).forEach(option => {
			
			var attrs = '';
			var val = '';
			var itemInner = '';
			
			if(option.hasAttribute('value')){
				val = option.getAttribute('value');
				attrs += ' data-value="'+val+'"';
			}
			
			if(option.textContent){
				attrs += ' data-label="'+option.textContent+'"';
			}
			
			// Set icon
			if('icon' in t.handlers[format] && typeof t.handlers[format]['icon'] == 'object' && !pagelayer_empty(t.handlers[format]['icon'][val])){
				itemInner = t.handlers[format]['icon'][val];
			}
			
			options += `<span class="pagelayer-pen-picker-item" tabindex="0" role="button" ${attrs}>${itemInner}</span>`;
		});
		
		if('fixIcon' in t.handlers[format]){
			fixIcon = t.handlers[format]['fixIcon'];
		}
		
		var customInpute = '';
		
		if('customInpute' in t.handlers[format] && !pagelayer_empty(t.handlers[format]['customInpute'])){
			customInpute = '<input type="text" class="pagelayer-pen-custom-input" placeholder="Custom value">';
		}
		
		var container = jQuery(`<span ${selAtts}>
			<span class="pagelayer-pen-picker-label" tabindex="0" role="button" aria-expanded="false">${fixIcon}</span>
			<span class="pagelayer-pen-picker-options" aria-hidden="true" tabindex="-1" id="${optId}" aria-controls="${optId}">
				${options}
				${customInpute}
			</span>
		</span>`);
		
		container.addClass('pagelayer-pen-picker');
		
		select.before(container);
		select.hide();
		
		var close = function(cEle){
			cEle.removeClass('pagelayer-pen-expanded');
			cEle.find('.pagelayer-pen-picker-label').attr('aria-expanded', 'false');
			cEle.find('.pagelayer-pen-picker-options').attr('aria-hidden', 'true');
		}
		
		var selectItem = function(item, trigger = false){
			var selected = container.find('.pagelayer-pen-selected');
			var label = container.find('.pagelayer-pen-picker-label');
			var val = '';
			
			if (item === selected) return;
			if (selected != null) {
				selected.removeClass('pagelayer-pen-selected');
			}
			if(item == null) return;
			item.classList.add('pagelayer-pen-selected');
			select.selectedIndex = Array.from(item.parentNode.children).indexOf(
			item,
			);
			if (item.hasAttribute('data-value')) {
				val = item.getAttribute('data-value');
				label.attr('data-value', val);
			} else {
				label.attr('data-value', val);
			}
			if (item.hasAttribute('data-label')) {
				label.attr('data-label', item.getAttribute('data-label'));
			} else {
				label.attr('data-label', '');
			}
			
			if(!fixIcon){
				label.html(item.innerHTML);
			}
			
			if(trigger) {
				select.val(val);
				select.trigger('change');
				close(container);
			}
		}
		
		var toggleAriaAttribute = function(element, attribute) {
			element.setAttribute(
			attribute,
			!(element.getAttribute(attribute) === 'true'),
		);
}
		var togglePicker = function() {
			container.toggleClass('pagelayer-pen-expanded');
			// Toggle aria-expanded and aria-hidden to make the picker accessible
			toggleAriaAttribute(container.find('.pagelayer-pen-picker-label')[0], 'aria-expanded');
			toggleAriaAttribute(container.find('.pagelayer-pen-picker-options')[0], 'aria-hidden');
		}
		
		container.find('.pagelayer-pen-picker-item').on('click', function(){
			selectItem(this, true);
			close(container);
		});
		
		container.find('.pagelayer-pen-picker-label').on('click', function(){
			togglePicker();
		});
		
		container.find('.pagelayer-pen-custom-input').on('focusout keydown', function(e){
			
			if(e.type == 'keydown' && e.keyCode != 13){
				return;
			}
			
			e.preventDefault();
			
			var val = jQuery(this).val();
			
			if(pagelayer_empty(val)){
				return;
			}
			
			var opt = select.find('option.pagelayer-pen-custom-value');
			
			if(opt.length < 1){
				select.append('<option class="pagelayer-pen-custom-value"></option>');
				opt = select.find('option.pagelayer-pen-custom-value');
			}
			
			opt.val(val);
			select.val(val);
			select.trigger('change');
			close(container);
		});
		
		jQuery(t.toolbar).on('mousedown', function(e){
			var tEle = jQuery(this);
			var target = jQuery(e.target);
			var tPicker = target.closest('.pagelayer-pen-picker');
			
			if(target.closest('.pagelayer-pen-picker-item').length > 0) return;
			
			tEle.find('.pagelayer-pen-picker.pagelayer-pen-expanded').each(function(){
				var picker = jQuery(this);
				if(tPicker.length > 0 && tPicker.is(picker))return;
				close(picker);
			});
			
		});
		
		// TODO need to correct this function update the select
		container.on('update', function(){
			var item = container.find('.pagelayer-pen-selected');
			
			if(item.length < 1){
				item = container.find('.pagelayer-pen-picker-item').first();
			}
			
			selectItem(item[0]);
		});
		
		container.trigger('update');
		
		return container;
	}
	
	buildColorBtnHandler(item){
		var t = this;
		var select = t.buildDropdown(item);
		var format = select.data('format');
		
		// Set color
		select.find('.pagelayer-pen-picker-item').each(function(){
			var opt = jQuery(this);
			var color = opt.data('value');
			
			opt.css({'background': color});
			
			// TODO remove this and add on selecttion
			opt.on('click', function(){
				if(format == 'color'){
					opt.closest('.pagelayer-pen-picker-label').css({'text-color': color});
				}else{
					opt.closest('.pagelayer-pen-picker-label').css({'background-color': color});
				}
			});
		});
		
	}
	
	buildfontBtnHandler(item){
		var t = this;
		
		var select = t.buildDropdown(item);
		
		jQuery(item).on('change', function(){
			pagelayer_link_font_family(jQuery(this));	
		});
		
	}
	
	setLinkHandler(){
		var t = this,
			documentSelection = t.doc.getSelection(),
			node = documentSelection.focusNode,
			text = new XMLSerializer().serializeToString(documentSelection.getRangeAt(0).cloneContents()),
			url = '',
			linkBtn = 'Link',
			unlinkBtn = 'Cancel';

		while (['A', 'DIV'].indexOf(node.nodeName) < 0) {
			node = node.parentNode;
		}
		
		if(node && node.nodeName === 'A'){
			var $a = jQuery(node);
			url = $a.attr('href');
		}
		
		if(!pagelayer_empty(url)){
			linkBtn = 'Update';
			unlinkBtn = 'Unlink';
		}
    
		t.saveRange();
			
		var tooltip = this.addContainer('pagelayer-pen-link-tooltip');
		t.linkTooltip = tooltip;
		
		var html = '<input type="text" name="url" placeholder="https://example.com" value="'+url+'" autocomplete="off"><span class="pagelayer-pen-link-btn pagelayer-btn-success">'+linkBtn+'</span><span class="pagelayer-pen-unlink-btn pagelayer-btn-primary">'+unlinkBtn+'</span>';
		tooltip.html(html);
		
		var input = tooltip.find('input[name="url"]');
		
		// Keep saving old range
		var metaRange = t.metaRange;
		var savedRange = t.range;
		var restoreRange = function(){
			t.metaRange = metaRange;
			t.range = savedRange;
			t.restoreRange();
		}
		
		t.linkTooltip.find('.pagelayer-pen-link-btn').on('click', function(){
			var url = input.val();
			
			restoreRange();
			t.execCmd('createLink', url, true );
			t.editor.trigger('input');
			t.showPen();
		});
		
		t.linkTooltip.find('.pagelayer-pen-unlink-btn').on('click', function(){
			restoreRange();
			if(unlinkBtn == 'Unlink'){
				t.execCmd('unlink', undefined, undefined, true);
			}
			t.showPen();
		});
	
		t.showPen(t.linkTooltip);
	}
	
	imageBtnHandler(){
		var t = this;
		t.destroyEd = false;
		t.destroy();
		
		var frame = pagelayer_select_frame('image');
		
		// On select update the stuff
		frame.on({'select': function(){
				var state = frame.state();
				var url = '', alt = '', id = '';
				
				// External URL
				if('props' in state){
					
					url = state.props.attributes.url;
					alt = state.props.attributes.alt;
				
				// Internal from gallery
				}else{
				
					var attachment = frame.state().get('selection').first().toJSON();
					//console.log(attachment);
					
					// Set the new and URL
					url = attachment.url;
					alt = attachment.alt;
					id = attachment.id;
					
				}
				t.editor.click();
				t.restoreRange();
				t.execCmd('insertImage', url, false, true);
				var $img = jQuery('img[src="' + url + '"]:not([alt])', t.editor);
				
				$img.attr('alt', alt);
				$img.attr('pl-media-id', id);
			}
		});

		frame.open();
	}
	
	viewHTMLBtnHandler(param){
		var t = this;
		var html = t.getContent();
		t.destroyEd = false;
		t.destroy();

		// Add Container
		var HTMLviewer = jQuery('.pagelayer-pen-html-viewer');
		
		if(HTMLviewer.length < 1){
			jQuery('body').append('<div class="pagelayer-pen-html-viewer">'+
				'<div class="pagelayer-pen-html-holder">'+
					'<textarea class="pagelayer-pen-html-area"></textarea>'+
					'<div class="pagelayer-pen-html-btn">'+
						'<button class="pagelayer-pen-html-btn-update pagelayer-btn-success">Update</button>'+
						'<button class="pagelayer-pen-html-btn-cancel pagelayer-btn-secondary">Cancel</button>'+
					'</div>'+
				'</div>'+
			'</div>');
			
			HTMLviewer = jQuery('.pagelayer-pen-html-viewer');
		}
		
		HTMLviewer.find('.pagelayer-pen-html-area').val(html);
		HTMLviewer.show();
		
		HTMLviewer.find('.pagelayer-pen-html-btn-update').unbind('click');
		HTMLviewer.find('.pagelayer-pen-html-btn-update').on('click', function(){
			var html = HTMLviewer.find('.pagelayer-pen-html-area').val();
			t.range = null;
			t.editor.click();
			t.setContent(html);
			t.editor.trigger('focus');
			HTMLviewer.hide();
		});
		
		HTMLviewer.find('.pagelayer-pen-html-btn-cancel').unbind('click');
		HTMLviewer.find('.pagelayer-pen-html-btn-cancel').on('click', function(){
			t.editor.click();
			t.focus();
			HTMLviewer.hide();
		});
		
	}
}
