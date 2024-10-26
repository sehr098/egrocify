<?php

//////////////////////////////////////////////////////////////
//===========================================================
// freemium.php
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

global $pagelayer;

// Posts options style
$pagelayer_posts_options = array(
	'type' => array(
		'type' => 'select',
		'label' => __pl('type'),
		'default' => 'default',
		'list' => array(
			'default' => __pl('default'),
		),
	),
	'infinite_types' => array(
		'type' => 'radio',	
		'label' => __pl('infinite_scroll'),
		'addClass' =>  ['pagelayer-infinite-posts','{{element}} .pagelayer-posts-container' => 'pagelayer-infinite-scroll-{{val}}'],
		'list' => array(
			'' => __pl('none'),
			'button' => __pl('Button'),
			'auto' => __pl('Auto'),
		),
		'req' => ['!enable_slider' => 'true'],
	),
	'alignment' => array(
		'type' => 'radio',
		'label' => __pl('alignment'),
		'addClass' =>  'pagelayer-arc-layout-{{val}}',
		'list' => array(
			'' => __pl('default'),
			'left' => __pl('left'),
			'right' => __pl('right'),
			'alt'	=> __pl('Alternative')
		),
	),
	'columns' => array(
		'type' => 'select',
		'label' => __pl('columns'),
		'np' => 1,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-posts-container' => 'grid-template-columns: repeat({{val}},1fr);'],
		'list' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
		),
	),
	'col_gap' => array(
		'type' => 'slider',
		'label' => __pl('col_gap'),
		'min' => 0,
		'step' => 1,
		'max' => 100,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-posts-container' => 'grid-column-gap: {{val}}px;'],
	),
	'row_gap' => array(
		'type' => 'slider',
		'label' => __pl('row_gap'),
		'min' => 0,
		'step' => 1,
		'max' => 100,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-posts-container' => 'grid-row-gap: {{val}}px;'],
	),
	'data_padding' => array(
		'type' => 'padding',
		'label' => __pl('padding'),
		'default' => '5,5,5,5',
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-content' => 'padding : {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
	),
	'data_margin' => array(
		'type' => 'padding',
		'label' => __pl('margin'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-col' => 'margin :{{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
	),
	'bg_color' => array(
		'type' => 'color',
		'label' => __pl('bg_color'),
		'default' => '#ffffff',
		'css' => ['{{element}} .pagelayer-wposts-col' => 'background-color:{{val}};'],
	),
	'box_shadow' => array(
		'type' => 'box_shadow',
		'label' => __pl('box_shadow'),
		'css' => ['{{element}} .pagelayer-wposts-col' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'],
	),
	'post_bor_hover' => array(
		'type' => 'radio',
		'label' => __pl('state'),
		'list' => array(
			'' => __pl('normal'),
			'hover' => __pl('hover'),
		)
	),	
	'post_border_type' => array(
		'type' => 'select',
		'label' => __pl('border_type'),
		'css' => [
			'{{element}} .pagelayer-wposts-col' => 'border-style: {{val}};',
			],
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => array(
			'post_bor_hover' => ''
		),
	),
	'post_border_color' => array(
		'type' => 'color',
		'label' => __pl('border_color_label'),
		'default' => '#42414f',
		'css' => ['{{element}} .pagelayer-wposts-col' => 'border-color: {{val}};'],
		'req' => array(
			'!post_border_type' => ''
		),
		'show' => array(
			'post_bor_hover' => ''
		),
	),
	'post_border_width' => array(
		'type' => 'padding',
		'label' => __pl('border_width'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-col' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
		'req' => [
			'!post_border_type' => ''
		],
		'show' => array(
			'post_bor_hover' => ''
		),
	),
	'post_border_radius' => array(
		'type' => 'padding',
		'label' => __pl('border_radius'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-col' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
		'req' => array(
			'!post_border_type' => '',
		),
		'show' => array(
			'post_bor_hover' => '',
		),
	),
	'post_border_type_hover' => array(
		'type' => 'select',
		'label' => __pl('border_type'),
		'css' => [
			'{{element}} .pagelayer-wposts-col:hover' => 'border-style: {{val}}',	
		],
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => array(
			'post_bor_hover' => 'hover'
		),
	),
	'post_border_color_hover' => array(
		'type' => 'color',
		'label' => __pl('border_color_hover_label'),
		'default' => '#42414f',
		'css' => ['{{element}} .pagelayer-wposts-col:hover' => 'border-color: {{val}};'],
		'req' => array(
			'!post_border_type_hover' => ''
		),
		'show' => array(
			'post_bor_hover' => 'hover'
		),
	),
	'post_border_width_hover' => array(
		'type' => 'padding',
		'label' => __pl('border_width_hover'),
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-wposts-col:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'
		],
		'req' => [
			'!post_border_type_hover' => ''
		],
		'show' => array(
			'post_bor_hover' => 'hover'
		),
	),
	'post_border_radius_hover' => array(
		'type' => 'padding',
		'label' => __pl('border_radius_hover'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-col:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
		'req' => array(
			'!post_border_type_hover' => '',
		),
		'show' => array(
			'post_bor_hover' => 'hover',	
		),
	),
	'anim_hover' => array(
		'type' => 'select',
		'label' => __pl('Post Animation'),
		'addClass' => ['{{element}} .pagelayer-wposts-col' => 'pagelayer-animation-{{val}}'],
		'list' => [
			'' => __pl('none'),
			'grow' => __pl('Grow'),
			'shrink' => __pl('Shrink'),
			'pulse' => __pl('Pulse'),
			'pulse-grow' => __pl('Pulse Grow'),
			'pulse-shrink' => __pl('Pulse Shrink'),
			'push' => __pl('Push'),
			'pop' => __pl('Pop'),
			'buzz' => __pl('Buzz'),
			'buzz-out' => __pl('Buzz Out'),
			'float' => __pl('Float'),
			'sink' => __pl('Sink'),
			'bob' => __pl('Bob'),
			'hang' => __pl('Hang'),
			'bounce-in' => __pl('Bounce In'),
			'bounce-out' => __pl('Bounce Out'),
			'rotate' => __pl('Rotate'),
			'grow-rotate' => __pl('Grow Rotate'),
			'skew-forward' => __pl('Skew Forward'),
			'skew-backward' => __pl('Skew Backward'),
			'wobble-vertical' => __pl('Wobble Vertical'),
			'wobble-horizontal' => __pl('Wobble Horizontal'),
			'wobble-bottom-to-right' => __pl('Wobble Bottom To Right'),
			'wobble-top-to-right' => __pl('Wobble Top To Right'),
			'wobble-top' => __pl('Wobble Top'),
			'wobble-bottom' => __pl('Wobble Bottom'),
			'wobble-skew' => __pl('Wobble Skew'),
		],
	)
);

// Posts thumb style
$pagelayer_thumb_style = [
	'show_thumb' => array(
		'label' => __pl('show_thumb'),
		'type' => 'checkbox',
		'default' => 'true',
		//'addAttr' => ['{{element}} a' => 'target="_blank"'],
	),
	'thumb_img_type' => array(
		'type' => 'select',
		'label' => __pl('thumb_img_type'),
		'list' => array(
			'' => __pl('default'),
			'custom' => __pl('custom')
		),
		'req' => ['show_thumb' => 'true'],
	),
	'def_thumb_img' => array(
		'type' => 'image',
		'label' => __pl('image'),
		'req' => [
			'show_thumb' => 'true',
			'thumb_img_type' => 'custom',			
		]
	),
	'thumb_size' => array(
		'type' => 'select',
		'label' => __pl('type'),
		'default' => 'medium_large',
		'list' => pagelayer_image_sizes(),
		'req' => ['show_thumb' => 'true'],
	),
	'ratio' => array(
		'type' => 'slider',
		'label' => __pl('ratio'),
		'min' => 0,
		'step' => 0.1,
		'max' => 2,
		'default' => 0.7,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-thumb' => 'padding: calc(50% * {{val}}) 0;'],
		'req' => ['show_thumb' => 'true'],
	),
];

// Posts type style
$pagelayer_title_style = [
	'show_title' => array(
		'type' => 'checkbox',
		'label' => __pl('show_title'),
		'default' => 'true',
	),
	'title_color' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'default' => '#0986c0',
		'css' => ['{{element}} .pagelayer-wposts-title' => 'color:{{val}};'],
		'req' => ['show_title' => 'true'],
	),
	'title_typo' => array(
		'type' => 'typography',
		'label' => __pl('typography'),
		'default' => ',18,,,,,,,,,',
		'css' => ['{{element}} .pagelayer-wposts-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
		'req' => ['show_title' => 'true'],
	),
	'title_spacing' => array(
		'type' => 'dimension',
		'label' => __pl('top_bottom_spacing'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-title' => 'padding-top:{{val[0]}}px; padding-bottom:{{val[1]}}px;'],
		'req' => ['show_title' => 'true'],
	),
];

// Posts meta options
$pagelayer_meta_style = [
	'meta' => array(
		'type' => 'multiselect',
		'label' => __pl('meta'),
		'default' => 'author,date',
		'list' => array(
			'date' => __pl('date'),
			'author' => __pl('author'),
			'comments' => __pl('comments'),
			'tags' => __pl('tags'),
			'category' => __pl('category'),
		),
	),
	'meta_sep' => array(
		'type' => 'text',
		'label' => __pl('separator'),
		'default' => ' | ',
	),
	'meta_color' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'css' => ['{{element}} .pagelayer-wposts-meta *' => 'color:{{val}};'],
	),
	'meta_bg' => array(
		'type' => 'color',
		'label' => __pl('bg_color'),
		'css' => ['{{element}} .pagelayer-wposts-meta' => 'background-color:{{val}};'],
	),
	'meta_align' => array(
		'type' => 'radio',
		'label' => __pl('alignment'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-meta' => 'text-align:{{val}};'],
		'list' => array(
			'left' => __pl('left'),
			'center' => __pl('center'),
			'right' => __pl('right'),
		),
	),
	'meta_spacing' => array(
		'type' => 'padding',
		'label' => __pl('spacing'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-meta' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;'],
	),
	'meta_bor_rad' => array(
		'type' => 'padding',
		'label' => __pl('border_radius'),
		'units' => ['px', '%'],
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-meta' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};-moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
	),
	'meta_typo' => array(
		'type' => 'typography',
		'label' => __pl('typography'),
		'css' => [
			'{{element}} .pagelayer-wposts-meta *' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
			'{{element}} .pagelayer-wposts-sep' => 'font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important;'
		],
	),
	'meta_tag_pos' => array(
		'type' => 'select',
		'label' => __pl('position'),
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-wposts-post' => 'position: relative;',
			'{{element}} .pagelayer-wposts-meta' => 'position: {{val}};'
		],
		'list' => array(
			'' => __pl('default'),
			'relative' => __pl('relative'),
			'absolute' => __pl('absolute')
		),
	),
	'meta_width' => [
		'type' => 'slider',
		'label' => __pl('width'),
		'screen' => 1,
		'units' => ['px','%','em'],
		'css' => ['{{element}} .pagelayer-wposts-meta' => 'width:{{val}};'],
		'min' => 0,
		'max' => 1000,
		'step' => 1,
		'req' => ['!meta_tag_pos' => '']
	],
	'meta_vposition' => [
		'type' => 'select',
		'label' => __pl('verticle_pos'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-meta' => '{{val}}:0;'],
		'list' => [
			'' => __pl('default'),
			'top' => __pl('top'),
			'bottom' => __pl('bottom')
		],
		'req' => ['!meta_tag_pos' => '']
	],
	'meta_vposition_offset' => [
		'type' => 'slider',
		'label' => __pl('ver_offset'),
		'screen' => 1,
		'units' => ['px','%','em'],
		'css' => ['{{element}} .pagelayer-wposts-meta' => '{{meta_vposition}}:{{val}};'],
		'min' => -1000,
		'max' => 1000,
		'step' => 1,
		'req' => [
			'!meta_vposition' => '',
			'!meta_tag_pos' => ''
		]
	],
	'meta_hposition' => [
		'type' => 'select',
		'label' => __pl('horizontal_pos'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-meta' => '{{val}}:0;'],
		'list' => [
			'' => __pl('default'),
			'left' => __pl('left'),
			'right' => __pl('right')
		],
		'req' => ['!meta_tag_pos' => '']
	],
	'meta_hposition_offset' => [
		'type' => 'slider',
		'label' => __pl('hor_offset'),
		'screen' => 1,
		'units' => ['px','%','em'],
		'css' => ['{{element}} .pagelayer-wposts-meta' => '{{meta_hposition}}:{{val}};'],
		'min' => -1000,
		'max' => 1000,
		'step' => 1,
		'req' => [
			'!meta_hposition' => '',
			'!meta_tag_pos' => ''
		]
	]
];

// Posts content style
$pagelayer_content_style = [
	'show_content' => array(
		'type' => 'select',
		'label' => __pl('show_content'),
		'default' => 'excerpt',
		'list' => array(
			'' => __pl('none'),
			'excerpt' => __pl('excerpt'),
			'full' => __pl('full'),
		),
	),
	'exc_length' => array(
		'type' => 'spinner',
		'label' => __pl('exc_length'),
		'min' => 0,
		'step' => 1,
		'max' => 500,
		'default' => 10,
		'req' => ['show_content' => 'excerpt']
	),
	'content_color' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'default' => '#121212',
		'css' => ['{{element}} .pagelayer-wposts-content' => 'color:{{val}};'],
	),
	'content_padding' => array(
		'type' => 'padding',
		'label' => __pl('padding'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-content .pagelayer-wposts-excerpt' => 'padding: {{val[0]}}px  {{val[1]}}px  {{val[2]}}px  {{val[3]}}px;'],
	),
	'content_typo' => array(
		'type' => 'typography',
		'label' => __pl('typography'),
		'css' => ['{{element}} .pagelayer-wposts-excerpt' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
	),
	'content_align' => array(
		'type' => 'radio',
		'label' => __pl('alignment'),
		'default' => 'left',
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-content' => 'text-align:{{val}};'],
		'list' => array(
			'left' => __pl('left'),
			'center' => __pl('center'),
			'right' => __pl('right'),
		),
	),
];

//Post Infinite Style
$pagelayer_infinite_style = array(
	'infinite_text' => array(
		'type' => 'text',	
		'label' => __pl('Button Text'),
		'default' => 'Load More',
		'req' => ['infinite_types' => 'button']	
	),
	'infinite_final' => array(
		'type' => 'text',
		'addAttr' => ['{{element}} .pagelayer_load_button' => 'data-text="{{infinite_final}}"'],	
		'label' => __pl('Post End Message'),
		'default' => 'No More Post',	
	),
	'infinite_full_width' => array(
		'type' => 'checkbox',
		'label' => __pl('stretch'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-load' => 'width: 100% !important; text-align: center;',
		'{{element}} .pagelayer_load_button' =>'text-align: center;'],
	),
	'btn_typo' => array(
		'type' => 'typography',
		'label' => __pl('quote_content_typo'),
		'css' => [
			'{{element}} .pagelayer-btn-load-text' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
			'{{element}} .pagelayer_load_button' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
		],
	),
	'load_align' => array(
		'type' => 'radio',	
		'label' => __pl('alignment'),
		'list' => array(
			'left' => __pl('left'),
			'center' => __pl('center'),
			'right' => __pl('right'),
		),
		'css' => ['{{element}} .pagelayer_load_button' => 'text-align:{{val}}'],
		'req' => ['infinite_types' => 'button',	'infinite_full_width' => '']
	),
	'load_margin' => array(
		'type' => 'padding',
		'label' => __pl('margin'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer_load_button' => 'margin :{{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
	),
	'load_btn_icon' => array(
		'type' => 'icon',
		'label' => __pl('service_box_font_icon_label'),
	),
	'load_btn_icon_position' => array(
		'type' => 'radio',
		'label' => __pl('alignment'),
		'default' => 'pagelayer-btn-icon-left',
		'list' => array(
			'pagelayer-btn-icon-left' => __pl('left'),
			'pagelayer-btn-icon-right' => __pl('right')
		),
		'req' => array(
			'!load_btn_icon' => ''
		),
	),
	'load_btn_icon_spacing' => array(
		'type' => 'slider',
		'label' => __pl('icon_spacing'),
		'min' => 1,
		'step' => 1,
		'max' => 100,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-load-icon' => 'padding: 0 {{val}}px;'],
		'req' => ['!btn_icon' => ''],
	),
	'infinite_btn_type' => array(
		'type' => 'select',
		'label' => __pl('button_type_label'),
		'default' => 'pagelayer-btn-default',
		'list' => array(
			'pagelayer-btn-default' => __pl('btn_type_default'),
			'pagelayer-btn-primary' => __pl('btn_type_primary'),
			'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
			'pagelayer-btn-success' => __pl('btn_type_success'),
			'pagelayer-btn-info' => __pl('btn_type_info'),
			'pagelayer-btn-warning' => __pl('btn_type_warning'),
			'pagelayer-btn-danger' => __pl('btn_type_danger'),
			'pagelayer-btn-dark' => __pl('btn_type_dark'),
			'pagelayer-btn-light' => __pl('btn_type_light'),
			'pagelayer-btn-link' => __pl('btn_type_link'),
			'pagelayer-btn-anim' => __pl('btn_type_anim'),
			'pagelayer-btn-custom' => __pl('btn_type_custom')
		),
	),
	'infinite_anim_type' => array(
		'type' => 'select',
		'label' => __pl('type'),
		'default' => 'glow',
		'addClass' => ['{{element}} .pagelayer-btn-load' => 'pagelayer-btn-anim-{{val}}'],
		'list' => array(
			'glow' => __pl('glow_button'),
			'thin' => __pl('thin_button'),
			'slide' => __pl('slide_button')
		),
		'req' => array(
			'infinite_btn_type' => 'pagelayer-btn-anim'
		),
	),
	'infinite_direction' => array(
		'type' => 'select',
		'label' => __pl('animation_direction'),
		'default' => 'pagelayer-btn-anim-left',
		'addClass' =>  ['{{element}} .pagelayer-btn-load' => '{{val}}'],
		'list' => array(
			'pagelayer-btn-anim-left' => __pl('left'),
			'pagelayer-btn-anim-right' => __pl('right'),
		),
		'req' => array(
			'infinite_anim_type' => 'slide',
			'infinite_btn_type' => 'pagelayer-btn-anim',
		),
	),
	'infinite_btn_size' => array(
		'type' => 'select',
		'label' => __pl('button_size_label'),
		'default' => 'pagelayer-btn-large',
		'list' => array(
			'pagelayer-btn-mini' => __pl('mini'),
			'pagelayer-btn-small' => __pl('small'),
			'pagelayer-btn-large' => __pl('large'),
			'pagelayer-btn-extra-large' => __pl('extra_large'),
			'pagelayer-btn-double-large' => __pl('double_large'),
			'pagelayer-btn-custom' => __pl('custom'),
		)
	),
	'infinite_btn_custom_size' => array(
		'type' => 'spinner',
		'label' => __pl('btn_custom_size'),
		'min' => 1,
		'step' => 1,
		'max' => 100,
		'default' => 5,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-load' => 'padding: calc({{val}}px / 2) {{val}}px;'],
		'req' => array(
			'infinite_btn_size' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
		),
	),
	'infinite_btn_hover' => array(
		'type' => 'radio',
		'label' => __pl('state'),
		'list' => array(
			'' => __pl('normal'),
			'hover' => __pl('hover'),
		),
		'req' => array(
			'infinite_btn_type' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
		),
	),
	'infinite_btn_bg_color' => array(
		'type' => 'color',
		'label' => __pl('btn_bg_color_label'),
		'default' => '#818a91',
		'css' => [
			'{{element}} .pagelayer-btn-load' => 'background-color: {{val}};',
			'{{element}} .pagelayer-btn-anim-thin' => 'background-color: unset !important;',
			'{{element}} .pagelayer-btn-anim-thin::before' => 'background-color: {{val}};',
			'{{element}} .pagelayer-btn-anim-thin::after' => 'background-color: {{val}};',
		],
		'req' => array(
			'infinite_btn_type' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
		),
		'show' => array(
			'infinite_btn_hover' => ''
		),
	),
	'infinite_btn_color' => array(
		'type' => 'color',
		'label' => __pl('btn_color_label'),
		'default' => '#ffffff',
		'css' => ['{{element}} .pagelayer-btn-load' => 'color: {{val}};'],
		'req' => array(
			'infinite_btn_type' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
		),
		'show' => array(
			'infinite_btn_hover' => ''
		),
	),
	'infinite_btn_hover_delay' => array(
		'type' => 'spinner',
		'label' => __pl('btn_hover_delay_label'),
		'desc' => __pl('btn_hover_delay_desc'),
		'min' => 0,
		'step' => 100,
		'max' => 5000,
		'default' => 400,
		'css' => ['{{element}} .pagelayer-btn-load' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
		'show' => array(
			'infinite_btn_type' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
			'infinite_btn_hover' => 'hover'
		),
	),
	'infinite_btn_bg_color_hover' => array(
		'type' => 'color',
		'label' => __pl('btn_bg_color_hover_label'),
		'css' => [
			'{{element}} .pagelayer-btn-custom:hover, {{element}} .pagelayer-btn-anim-slide:after' => 'background-color: {{val}};',
		],
		'req' => array(
			'infinite_btn_type' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
			'!infinite_anim_type' => ['glow','thin'],
		),
		'show' => array(
			'infinite_btn_hover' => 'hover',
			'!infinite_anim_type' => ['glow','thin'],
		),
	),
	'infinite_btn_color_hover' => array(
		'type' => 'color',
		'label' => __pl('btn_color_hover_label'),
		'css' => ['{{element}} .pagelayer-btn-load:hover' => 'color: {{val}};'],
		'req' => array(
			'infinite_btn_type' => ['pagelayer-btn-custom','pagelayer-btn-anim'],
		),
		'show' => array(
			'infinite_btn_hover' => 'hover'
		),
	),
	'infinite_btn_shadow' => [
		'type' => 'box_shadow',
		'label' => __pl('shadow'),
		'css' => ['{{element}} .pagelayer-btn-load' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'],
	],
	'infinite_btn_shadow_hover' => [
		'type' => 'box_shadow',
		'label' => __pl('box_shadow_hover'),
		'css' => ['{{element}} .pagelayer-btn-load:hover' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'],
	],	
	'infinite_btn_border_type' => array(
		'type' => 'select',
		'label' => __pl('border_type'),
		'css' => [
			'{{element}} .pagelayer-btn-load, {{element}} .pagelayer-btn-anim-thin:after, {{element}} .pagelayer-btn-anim-thin:before' => 'border-style: {{val}};',
			'{{element}} .pagelayer-btn-anim-thin' => 'border-style: unset !important;',
			],
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => array(
			'infinite_btn_hover' => ''
		),
	),
	'infinite_btn_border_color' => array(
		'type' => 'color',
		'label' => __pl('border_color_label'),
		'default' => '#42414f',
		'css' => ['{{element}} .pagelayer-btn-load' => 'border-color: {{val}};'],
		'req' => array(
			'!infinite_btn_border_type' => ''
		),
		'show' => array(
			'infinite_btn_hover' => ''
		),
	),
	'infinite_btn_border_width' => array(
		'type' => 'padding',
		'label' => __pl('border_width'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-load, {{element}} .pagelayer-btn-anim-thin:after, {{element}} .pagelayer-btn-anim-thin:before' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
		'req' => [
			'!infinite_btn_border_type' => ''
		],
		'show' => ['infinite_btn_hover' => ''],
	),
	'infinite_btn_border_radius' => array(
		'type' => 'padding',
		'label' => __pl('border_radius'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-load, {{element}} .pagelayer-btn-anim-glow:before' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
		'req' => array(
			'!infinite_btn_border_type' => '',
			'!infinite_anim_type' => 'slide'
		),
		'show' => array(
			'infinite_btn_hover' => '',
			'!infinite_anim_type' => 'slide'
		),
	),
	'infinite_btn_border_type_hover' => array(
		'type' => 'select',
		'label' => __pl('border_type'),
		'css' => [
			'{{element}} .pagelayer-btn-load:hover, {{element}} .pagelayer-btn-anim-thin:hover:after, {{element}} .pagelayer-btn-anim-thin:hover:before' => 'border-style: {{val}}',
			'{{element}} .pagelayer-btn-anim-thin:hover' => 'border-style: unset !important;',
		],
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => array(
			'infinite_btn_hover' => 'hover'
		),
	),
	'infinite_btn_border_color_hover' => array(
		'type' => 'color',
		'label' => __pl('border_color_hover_label'),
		'default' => '#42414f',
		'css' => ['{{element}} .pagelayer-btn-load:hover' => 'border-color: {{val}};'],
		'req' => array(
			'!btn_border_type_hover' => ''
		),
		'show' => array(
			'infinite_btn_hover' => 'hover'
		),
	),
	'infinite_btn_border_width_hover' => array(
		'type' => 'padding',
		'label' => __pl('border_width_hover'),
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-btn-load:hover, {{element}} .pagelayer-btn-anim-thin:hover:after, {{element}} .pagelayer-btn-anim-thin:hover:before' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'
		],
		'req' => [
			'!infinite_btn_border_type_hover' => ''
		],
		'show' => array(
			'infinite_btn_hover' => 'hover'
		),
	),
	'infinite_btn_border_radius_hover' => array(
		'type' => 'padding',
		'label' => __pl('border_radius_hover'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-load:hover, {{element}} .pagelayer-btn-anim-glow:before' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
		'req' => array(
			'!btn_border_type_hover' => '',
			'!anim_type' => 'slide'
		),
		'show' => array(
			'infinite_btn_hover' => 'hover',
			'!infinite_anim_type' => 'slide'
		),
	),
);

// Post More style
$pagelayer_more_style = [
	'show_more' => array(
		'type' => 'checkbox',
		'label' => __pl('show'),
	),
	'more' => array(
		'type' => 'text',
		'label' => __pl('text'),
		'default' => 'read more &#187;',
		'req' => ['show_more' => 'true'],
	),
	'more_typo' => array(
		'type' => 'typography',
		'label' => __pl('typography'),
		'css' => ['{{element}} .pagelayer-wposts-more' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
		'req' => ['show_more' => 'true'],
	),
	'full_width' => array(
		'type' => 'checkbox',
		'label' => __pl('stretch'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => 'width: 100%; text-align: center;'],
		'req' => ['show_more' => 'true'],
	),
	'align' => array(
		'type' => 'radio',
		'label' => __pl('obj_align_label'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-mdiv' => 'text-align: {{val}}'],
		'list' => array(
			'left' => __pl('left'),
			'center' => __pl('center'),
			'right' => __pl('right')
		),
		'req' => array(
			'full_width' => '',
			'show_more' => 'true',
		)
	),
	'icon' => array(
		'type' => 'icon',
		'label' => __pl('service_box_font_icon_label'),
		'req' => ['show_more' => 'true'],
	),
	'icon_position' => array(
		'type' => 'radio',
		'label' => __pl('alignment'),
		'default' => 'pagelayer-btn-icon-left',
		'list' => array(
			'pagelayer-btn-icon-left' => __pl('left'),
			'pagelayer-btn-icon-right' => __pl('right')
		),
		'req' => [
			'show_more' => 'true', 
			'!icon' => ''
		],
	),
	'icon_spacing' => array(
		'type' => 'slider',
		'label' => __pl('icon_spacing'),
		'min' => 1,
		'step' => 1,
		'max' => 100,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-icon' => 'padding: 0 {{val}}px;'],
		'req' => array(
			'!icon' => '',
			'show_more' => 'true',
		),
	),
	'btn_type' => array(
		'type' => 'select',
		'label' => __pl('button_type'),
		//'addClass' => ['{{element}} .pagelayer-btn-holder' => '{{val}}'],
		'list' => array(
			'pagelayer-btn-link' => __pl('btn_type_link'),
			'pagelayer-btn-default' => __pl('btn_type_default'),
			'pagelayer-btn-primary' => __pl('btn_type_primary'),
			'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
			'pagelayer-btn-success' => __pl('btn_type_success'),
			'pagelayer-btn-info' => __pl('btn_type_info'),
			'pagelayer-btn-warning' => __pl('btn_type_warning'),
			'pagelayer-btn-danger' => __pl('btn_type_danger'),
			'pagelayer-btn-dark' => __pl('btn_type_dark'),
			'pagelayer-btn-light' => __pl('btn_type_light'),
			'pagelayer-btn-custom' => __pl('btn_type_custom')
		),
		'req' => ['show_more' => 'true'],
	),
	'size' => array(
		'type' => 'select',
		'label' => __pl('button_size_label'),
		'list' => array(
			'pagelayer-btn-mini' => __pl('mini'),
			'pagelayer-btn-small' => __pl('small'),
			'pagelayer-btn-large' => __pl('large'),
			'pagelayer-btn-extra-large' => __pl('extra_large'),
			'pagelayer-btn-double-large' => __pl('double_large'),
			'pagelayer-btn-custom' => __pl('custom'),
		),
		'req' => ['show_more' => 'true'],
	),
	'btn_custom_size' => array(
		'type' => 'dimension',
		'label' => __pl('btn_custom_size'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-more.pagelayer-btn-holder' => 'padding: {{val[0]}}px {{val[1]}}px;'],
		'req' => array(
			'size' => 'pagelayer-btn-custom',
			'show_more' => 'true',
		),
	),
	'btn_hover' => array(
		'type' => 'radio',
		'label' => __pl('state'),
		'default' => '',
		'list' => array(
			'' => __pl('normal'),
			'hover' => __pl('hover'),
		),
		'req' => array(
			'btn_type' => 'pagelayer-btn-custom',
			'show_more' => 'true',
		),
	),
	'btn_bg_color' => array(
		'type' => 'color',
		'label' => __pl('btn_bg_color_label'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => 'background-color: {{val}};'],
		'req' => array(
			'btn_type' => 'pagelayer-btn-custom',
			'show_more' => 'true',
		),
		'show' => array(
			'btn_hover' => ''
		),
	),
	'more_color' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-more' => 'color:{{val}};'],
		'req' => array(
			'btn_type' => 'pagelayer-btn-custom',
			'show_more' => 'true',
		),
		'show' => array(
			'btn_hover' => ''
		),
	),
	'btn_hover_delay' => array(
		'type' => 'spinner',
		'label' => __pl('btn_hover_delay_label'),
		'desc' => __pl('btn_hover_delay_desc'),
		'min' => 0,
		'step' => 100,
		'max' => 5000,
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
		'show' => array(
			'btn_hover' => 'hover'
		),
		'req' => ['show_more' => 'true'],
	),
	'btn_bg_color_hover' => array(
		'type' => 'color',
		'label' => __pl('btn_bg_color_hover_label'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'background-color: {{val}};'],
		'req' => array(
			'btn_type' => 'pagelayer-btn-custom',
			'show_more' => 'true',
		),
		'show' => array(
			'btn_hover' => 'hover'
		),
	),
	'more_color_hover' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-wposts-more:hover' => 'color:{{val}};'],
		'req' => array(
			'btn_type' => 'pagelayer-btn-custom',
			'show_more' => 'true',
		),
		'show' => array(
			'btn_hover' => 'hover'
		),
	),
];

// Post More style
$pagelayer_btn_border_style = [
	'btn_bor_hover' => array(
		'type' => 'radio',
		'label' => __pl('state'),
		'default' => '',
		//'no_val' => 1,// Dont set any value to element
		'list' => array(
			'' => __pl('normal'),
			'hover' => __pl('hover'),
		)
	),	
	'btn_border_type' => array(
		'type' => 'select',
		'label' => __pl('border_type'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => 'border-style: {{val}}'],
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => array(
			'btn_bor_hover' => ''
		)
	),
	'btn_border_color' => array(
		'type' => 'color',
		'label' => __pl('border_color_label'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => 'border-color: {{val}};'],
		'req' => array(
			'!btn_border_type' => ''
		),
		'show' => array(
			'btn_bor_hover' => ''
		),
	),
	'btn_border_width' => array(
		'type' => 'padding',
		'label' => __pl('border_width'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
		'req' => [
			'!btn_border_type' => ''
		],
		'show' => array(
			'btn_bor_hover' => ''
		),
	),
	'btn_border_radius' => array(
		'type' => 'padding',
		'label' => __pl('border_radius'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
		'req' => array(
			'!btn_border_type' => ''
		),
		'show' => array(
			'btn_bor_hover' => ''
		),
	),
	'btn_border_type_hover' => array(
		'type' => 'select',
		'label' => __pl('border_type'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-style: {{val}}'],
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => array(
			'btn_bor_hover' => 'hover'
		)
	),
	'btn_border_color_hover' => array(
		'type' => 'color',
		'label' => __pl('border_color_hover_label'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-color: {{val}};'],
		'req' => array(
			'!btn_border_type_hover' => ''
		),
		'show' => array(
			'btn_bor_hover' => 'hover'
		),
	),
	'btn_border_width_hover' => array(
		'type' => 'padding',
		'label' => __pl('border_width_hover'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
		'req' => [
			'!btn_border_type_hover' => ''
		],
		'show' => array(
			'btn_bor_hover' => 'hover'
		),
	),
	'btn_border_radius_hover' => array(
		'type' => 'padding',
		'label' => __pl('border_radius_hover'),
		'screen' => 1,
		'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
		'req' => array(
			'!btn_border_type_hover' => ''
		),
		'show' => array(
			'btn_bor_hover' => 'hover'
		),
	),
];

// Archives Post title
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_archive_title', array(
		'name' => __pl('archive_title'),
		'group' => 'archive',
		'html' => '<div class="pagelayer-archive-title">'. pagelayer_get_the_title() .'</div>',
		'params' => array(
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				],
				'css' => ['{{element}} .pagelayer-archive-title' => 'text-align: {{val}}'],
			),
			'color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-archive-title' => 'color:{{val}}'],
			),
			'typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-archive-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		)
	)
);

// Archive Posts shows the posts as per the QUERY of the current page
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_archive_posts', array(
		'name' => __pl('archive_posts'),
		'group' => 'archive',
		'html' => '{{pagelayer_pagination_top}}
		<div class="pagelayer-posts-container">{{pagelayer_archive_posts}}</div>
		{{pagelayer_pagination_bottom}}
		<div if="{{infinite_types}}" class="pagelayer_load_button" data-text={{infinite_final}}>
			<a class="pagelayer-btn-holder pagelayer-btn-load pagelayer-ele-link {{infinite_btn_type}} {{infinite_btn_size}} {{load_btn_icon_position}}">
				<i if="{{load_btn_icon}}" class="{{load_btn_icon}} pagelayer-btn-load-icon"></i>
				<span if="{{infinite_text}}" class="pagelayer-btn-load-text" >{{infinite_text}}</span>
				<i if="{{load_btn_icon}}" class="{{load_btn_icon}} pagelayer-btn-load-icon"></i>
			</a>
			<div class="pagelayer-loader-holder" >
				<i class="fa fa-spinner fa-spin fa-3x fa-fw" aria-hidden="true"></i>
			</div>
		</div>',
		'params' => $pagelayer_posts_options,
		'thumb_style' => $pagelayer_thumb_style,
		'title_style' => $pagelayer_title_style,
		'meta_options' => $pagelayer_meta_style,
		'content_style' => $pagelayer_content_style,
		'more_style' => $pagelayer_more_style,
		'infinite' => $pagelayer_infinite_style,
		'paginate_links' => array(
			'pagination' => array(
				'type' => 'select',	
				'label' => __pl('pagination'),
				'default' => 'number_prev_next',
				'list' => array(
					'' => __pl('none'),
					'number' => __pl('number'),
					'number_prev_next' => __pl('number_prev_next'),
				),
			),
			'pagination_on' => array(
				'type' => 'select',	
				'label' => __pl('pagination_on'),
				'list' => array(
					'' => __pl('bottom'),
					'top' => __pl('top'),
				),
				'req' => ['!pagination' => '']
			),
			'pagi_prev_text' => array(
				'type' => 'text',	
				'label' => __pl('prev_text'),
				'default' => 'Previous',
				'req' => ['!pagination' => ['', 'number']]
			),
			'pagi_next_text' => array(
				'type' => 'text',	
				'label' => __pl('next_text'),
				'default' => 'Next',
				'req' => ['!pagination' => ['', 'number']]
			),
			'pagi_end_size' => array(
				'type' => 'spinner',	
				'label' => __pl('pagi_end_size'),
				'default' => 1,
				'req' => ['!pagination' => '']
			),
			'pagi_mid_size' => array(
				'type' => 'spinner',	
				'label' => __pl('pagi_mid_size'),
				'default' => 2,
				'req' => ['!pagination' => '']
			),
			'before_page_number' => array(
				'type' => 'text',	
				'label' => __pl('before_page_number'),
				'req' => ['!pagination' => '']
			),
			'after_page_number' => array(
				'type' => 'text',	
				'label' => __pl('after_page_number'),
				'req' => ['!pagination' => '']
			),
		),
		'paginate_links_style' => array(
			'pagi_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => [
					'{{element}} .pagelayer-pagination' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
				],
				'req' => ['!pagination' => '']
			),
			'pagi_align' => array(
				'type' => 'radio',	
				'label' => __pl('alignment'),
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-pagination' => 'text-align:{{val}}'],
				'req' => ['!pagination' => '']
			),
			'pagi_colors' => array(
				'type' => 'radio',	
				'label' => __pl('colors'),
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
					'active' => __pl('active'),
				),
				'req' => ['!pagination' => '']
			),
			'pagi_color' => array(
				'type' => 'color',	
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-pagination a.page-numbers' => 'color:{{val}}'],
				'show' => [ 'pagi_colors' => 'normal'],
				'req' => ['!pagination' => '']
			),
			'pagi_hover_color' => array(
				'type' => 'color',	
				'label' => __pl('current_color'),
				'css' => ['{{element}} .pagelayer-pagination a.page-numbers:hover' => 'color:{{val}}'],
				'show' => [ 'pagi_colors' => 'hover'],
				'req' => ['!pagination' => '']
			),
			'pagi_current_color' => array(
				'type' => 'color',	
				'label' => __pl('current_color'),
				'css' => ['{{element}} .pagelayer-pagination .current' => 'color:{{val}}'],
				'show' => [ 'pagi_colors' => 'active']
			),
			'pagi_space_between' => array(
				'type' => 'slider',	
				'label' => __pl('space_between'),
				'css' => ['{{element}} .pagelayer-pagination .page-numbers:not(:last-child)' => 'margin-right:{{val}}px'],
				'req' => ['!pagination' => '']
			),
			'pagi_padding' => array(
				'type' => 'padding',	
				'label' => __pl('padding'),
				'css' => ['{{element}} .pagelayer-pagination' => 'padding:{{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => ['!pagination' => '']
			),
		),
		'styles' => array(
			'thumb_style' => __pl('thumb_style'),
			'title_style' => __pl('title_style'),
			'meta_options' => __pl('meta_options'),
			'content_style' => __pl('content_style'),
			'more_style' => __pl('more_style'),
			'paginate_links' => __pl('paginate_links'),
			'paginate_links_style' => __pl('paginate_links_style'),
			'infinite' => __pl('infinite_scroll'),
		),
	)
);

// Site Title
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_wp_title', array(
		'name' => __pl('Site Title'),
		'group' => 'wordpress',
		'html' => '<div class="pagelayer-wp-title-content">
			<div class="pagelayer-wp-title-section">
				<a href="'.get_site_url().'" class="pagelayer-wp-title-link pagelayer-ele-link">
					<img if="{{site_logo}}" class="pagelayer-img pagelayer-wp-title-img" src="{{func_image}}" title="{{{logo_img-title}}}" alt="{{{logo_img-alt}}}"/>
					<div class="pagelayer-wp-title-holder">
						<div class="pagelayer-wp-title-heading">'.get_bloginfo( 'name' ).'</div>
						<div if="{{site_desc}}" class="pagelayer-wp-title-desc">'.get_bloginfo( 'description' ).'</div>
					</div>
				</a>
			</div>			
		<div>',
		'params' => array(
			'site_title_style' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-title-heading' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'site_title_state' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),				
			'title_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-title-heading' => 'color:{{val}};'],
				'show' => ['!site_title_state' => 'hover'],
			),
			'title_color_hover' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => ['{{element}} .pagelayer-wp-title-heading:hover' => 'color:{{val}};'],
				'show' => ['site_title_state' => 'hover'],
			),
			'title_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_icon_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'css' => ['{{element}} .pagelayer-wp-title-heading' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['site_title_state' => 'hover'],
			),
			'text-align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-wp-title-heading' => 'text-align:{{val}};'],
			),
			'title_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp-title-heading' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
			),
			'disable_title' => array(
				'type' => 'checkbox',
				'label' => __pl('disable_title'),
				'desc' => __pl('disable_title_exp'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-title-holder' => 'display: none;'],
				'req' => ['site_logo' => 'true']
			),
		),
		'site_description' => array(
			'site_desc' => array(
				'type' => 'checkbox',
				'label' => __pl('site_desc'),
				'np' => 1,
			),
			'site_desc_style' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-wp-title-desc' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'req' => ['site_desc' => 'true']
			),
			'site_desc_state' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => ['site_desc' => 'true']
			),				
			'desc_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => ['{{element}} .pagelayer-wp-title-desc' => 'color:{{val}};'],
				'show' => ['site_desc_state' => 'normal'],
				'req' => ['site_desc' => 'true']
			),
			'desc_color_hover' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => ['{{element}} .pagelayer-wp-title-desc:hover' => 'color:{{val}};'],
				'show' => ['site_desc_state' => 'hover'],
				'req' => ['site_desc' => 'true']
			),
			'desc_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_icon_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'css' => ['{{element}} .pagelayer-wp-title-desc' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['site_desc_state' => 'hover'],
				'req' => ['site_desc' => 'true']
			),
			'desc_text_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-wp-title-desc' => 'text-align:{{val}};'],
				'req' => ['site_desc' => 'true']
			),
			'desc_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp-title-desc' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => ['site_desc' => 'true']
			),
		),
		'logo_style' => array(
			'site_logo' => array(
				'type' => 'checkbox',
				'label' => __pl('site_logo'),
				'np' => 1,
				//'desc' => __pl('site_logo_desc'),
			),
			'logo_img_type' => array(
				'type' => 'select',
				'label' => __pl('logo_img_type'),
				'np' => 1,
				'list' => array(
					'' => __pl('default_logo'),
					'custom-logo' => __pl('custom_logo'),
				),
				'req' => array(
					'site_logo' => 'true'
				)
			),
			'logo_img' => array(
				'type' => 'image',
				'label' => __pl('logo_select'),
				'np' => 1,
				'req' => array(
					'site_logo' => 'true',
					'logo_img_type' => 'custom-logo',
				)
			),
			'logo_img_size' => array(
				'type' => 'radio',
				'label' => __pl('logo_size'),
				'default' => 'full',
				'list' => array(
					'full' => __pl('full'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom'),
				),
				'req' => array(
					'site_logo' => 'true'
				)
			),
			'logo_img_custom_size' => array(
				'type' => 'slider',
				'label' => __pl('logo_custom_size'),
				'min' => 10,
				'max' => 100,
				'default' => 20,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp-title-img' => 'width:{{val}}%; height: auto;'],
				'req' => array(
					'logo_img_size' => 'custom',
					'site_logo' => 'true'
				)
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'left',
				'list' => array(
					'left' => __pl('left'),
					'top' => __pl('top'),
					'right' => __pl('right')
				),
				'addClass' => ['{{element}} .pagelayer-wp-title-link' => 'pagelayer-wp-title-align-{{val}}'],
				'req' => array(
					'site_logo' => 'true'
				)
			),
			'vertical_align' => array(
				'type' => 'radio',
				'label' => __pl('vertical_alignment'),
				'default' => 'middle',
				'list' => array(
					'top' => __pl('top'),
					'middle' => __pl('middle'),
					'bottom' => __pl('bottom')
				),
				'addClass' => ['{{element}} .pagelayer-wp-title-link' => 'pagelayer-wp-title-vertical-{{val}}'],
				'req' => ['site_logo' => 'true',
							'!align' => 'top']	
			),	
		),
		'styles' => [
			'site_description' => __pl('site_desc'),			
			'logo_style' => __pl('logo_style'),			
		]
	)
);

// Copyright
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_copyright', array(
		'name' => __pl('copyright'),
		'group' => 'other',
		'icon' => 'fa fa-copyright',
		'html' => '<div class="pagelayer-copyright">
			<a href="'.home_url().'">
				{{copyright_text}}
			</a>
		</div>',
		'params' => array(
			'copyright_text' => array(
				'type' => 'textarea',
				'label' => __pl('text'),
				'default' => pagelayer_get_option('pagelayer-copyright')
			),
			'color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-copyright *, {{element}} .pagelayer-copyright' => 'color:{{val}}']
			),
			'typography' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-copyright *, {{element}} .pagelayer-copyright' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'screen' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				],
				'css' => ['{{element}} .pagelayer-copyright' => 'text-align: {{val}}'],
			),
		)
	)
);

// Primary Menu
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_wp_menu', array(
		'name' => __pl('primary_menu'),
		'group' => 'wordpress',
		'html' => '<div class="pagelayer-wp-menu-holder" data-layout="{{layout}}" data-submenu_ind="{{submenu_ind}}" data-drop_breakpoint="{{drop_breakpoint}}">
			<div class="pagelayer-primary-menu-bar"><i class="{{menu_toggle_icon}}"></i></div>
			<div class="pagelayer-wp-menu-container pagelayer-menu-type-{{layout}} pagelayer-menu-hover-{{pointer}} {{m_animation}} {{slide_style}}" data-align="{{align}}">
				<div class="pagelayer-wp_menu-close"><i class="fas fa-times"></i></div>
				{{nav_menu}}
			</div>
		</div>',
		'params' => array(
			'nav_list' => array(// Never use the same name as we are replacing in IMPORT
				'type' => 'menus',
				'label' => __pl('select_menu'),
				'np' => 1,
				'default' =>  pagelayer_get_menu_list(true),
				'list' => pagelayer_get_menu_list(),
			),
		),
		'menu_style' => [
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'default' => 'left',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-wp_menu-ul' => 'text-align:{{val}};'],
			),
			'layout' => array(
				'type' => 'select',
				'label' => __pl('layout'),
				'default' => 'horizontal',
				'list' => array(
					'horizontal' => __pl('horizontal'),
					'vertical' => __pl('vertical'),
					'dropdown' => __pl('dropdown'),
				),
			),
			'drop_breakpoint' => array(
				'type' => 'select',
				'label' => __pl('drop_breakpoint'),
				'np' => 1,
				'list' => array(
					'none' => __pl('none'),
					'mobile' => __pl('mobile'),
					'tablet' => __pl('tablet'),
				),
				'req' => [ '!layout' => 'dropdown']
			),
			'pointer' => array(
				'type' => 'select',
				'label' => __pl('pointer'),
				'default' => 'underline',
				'list' => array(
					'' => __pl('none'),
					'underline' => __pl('underline'),
					'overline' => __pl('overline'),
					'doubleline' => __pl('double_line'),
					'framed' => __pl('Framed'),
					'background' => __pl('bg_color'),
					'text' => __pl('text'),
				),
			),
			'm_animation' => array(
				'type' => 'select',
				'label' => __pl('animation'),
				'default' => 'slide',
				'list' => array(
					'none' => __pl('none'),
					'fade' => __pl('fade'),
					'slide' => __pl('slide'),
					'grow' => __pl('Grow'),
					'dropin' => __pl('drop_in'),
					'dropout' => __pl('Drop_out'),
				),
			),
			'list_style' => array(
				'type' => 'select',
				'label' => __pl('list_style'),
				'np' => 1,
				'default' => 'none',
				'list' => array(
					'none' => __pl('none'),
					'circle' => __pl('list_list_type_circle'),
					'decimal' => __pl('decimal'),
					'square' => __pl('list_list_type_square'),
					'disc' => __pl('list_list_type_disc'),
					'inherit' => __pl('inherit'),
					'upper-roman' => __pl('upper_roman'),
					'upper-alpha' => __pl('upper_alpha'),
					'lower-roman' => __pl('lower_roman'),
					'lower-alpha' => __pl('lower_alpha'),
				),
				'css' => ['{{element}} .pagelayer-wp-menu-container li.menu-item' => 'list-style: {{val}};']
			),
			'submenu_ind' => array(
				'type' => 'select',
				'label' => __pl('sbmenu_indicator'),
				'np' => 1,
				'default' => 'caret-down',
				'list' => array(
					'' => __pl('none'),
					'caret-down' => __pl('caret_down'),
					'chevron-down' => __pl('chevron'),
					'angle-down' => __pl('angle'),
					'plus' => __pl('Plus'),
					'arrow-down' => __pl('arrow_down'),
				),
			),
			'submenu_anim' => array(
				'type' => 'select',
				'label' => __pl('submenu_anim'),
				'np' => 1,
				'list' => array(
					'' => __pl('none'),
					'pulse' => __pl('Pulse'),
					'fade' => __pl('fade'),
					'fadeindown' => __pl('fadeindown'),
					'fadeinup' => __pl('fadeinup'),
					'slideindown' => __pl('slideindown'),
					'slideinup' => __pl('slideinup'),
					'zoomin' => __pl('zoomin'),
					'flip' => __pl('flip'),
				),
				'css' => ['{{element}} .pagelayer-active-sub-menu.pagelayer-mega-menu-item > .pagelayer-mega-menu,
				:not(.pagelayer-ele-wrap) > {{element}} .pagelayer-menu-type-horizontal .pagelayer-mega-menu-item:hover > .pagelayer-mega-menu,
				{{element}} .pagelayer-active-sub-menu:not(.pagelayer-mega-menu-item) > ul.sub-menu,
				{{element}} .pagelayer-menu-type-horizontal .menu-item-has-children:not(.pagelayer-mega-menu-item, .pagelayer-mega-column-item .sub-menu li):hover > ul.sub-menu,
				:not(.pagelayer-ele-wrap) > .pagelayer-wp_menu .pagelayer-menu-type-vertical[class*="pagelayer-submenu-position-"] .pagelayer-mega-menu-item:hover > .pagelayer-mega-menu,
				{{element}} .pagelayer-menu-type-vertical[class*="pagelayer-submenu-position-"] .menu-item-has-children:not(.pagelayer-mega-menu-item, .pagelayer-mega-column-item .sub-menu li):hover > ul.sub-menu' => 'animation-name: pagelayer-submenu-{{val}};animation-duration: 300ms;animation-timing-function: linear; animation-iteration-count: 1;'],
			),
			'menu_colors' => array(
				'type' => 'radio',
				'label' => __pl('Background'),
				'np' => 1,
				//'no_val' => 1,// Dont set any value to element
				'list' => [
					'' => __pl('normal'),
					'hover' => __pl('hover'),
					'active' => __pl('active'),
				],
			),
			'menu_color' => [
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li a:first-child' => 'color: {{val}};'],
				'show' => ['menu_colors' => ''],
				'screen' => 1
			],
			'menu_bg_color' => [
				'type' => 'color',
				'label' => __pl('p_bg_color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li' => 'background-color: {{val}};'],
				'show' => ['menu_colors' => ''],
				'screen' => 1
			],
			'menu_color_hover' => [
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li>a:hover' => 'color: {{val}};',
				'{{element}} .pagelayer-wp_menu-ul>li.pagelayer-active-sub-menu>a:hover' => 'color: {{val}};'],
				'show' => ['menu_colors' => 'hover'],
				'screen' => 1
			],
			'menu_bg_color_hover' => [
				'type' => 'color',
				'label' => __pl('p_bg_color'),
				'np' => 1,
				'default' => '#00ccff',
				'css' => ['{{element}} .pagelayer-menu-hover-background .pagelayer-wp_menu-ul>li:hover' => 'background-color: {{val}};','{{element}} .pagelayer-wp_menu-ul>li>a:hover:before' => 'background-color: {{val}};border-color:{{val}}',
				'{{element}} .pagelayer-wp_menu-ul>li>a:hover:after' => 'background-color: {{val}};border-color:{{val}}',
				'{{element}} .pagelayer-wp_menu-ul>li.pagelayer-active-sub-menu' => 'background-color: {{val}};'],
				'show' => ['menu_colors' => 'hover'],
				'screen' => 1
			],
			'menu_color_active' => [
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li.current-menu-item>a' => 'color: {{val}};'],
				'show' => ['menu_colors' => 'active'],
				'screen' => 1
			],
			'menu_bg_color_active' => [
				'type' => 'color',
				'label' => __pl('p_bg_color'),
				'np' => 1,
				'default' => '#00ccff',
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li.current-menu-item' => 'background-color: {{val}};'],
				'show' => ['menu_colors' => 'active'],
				'screen' => 1
			],
			'menu_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul li.menu-item > a' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'menu_pointer_height' => [
				'type' => 'slider',
				'label' => __pl('pointer_height'),
				'screen' => 1,
				'min' => 1,
				'max' => 50,
				'css' => ['{{element}} .pagelayer-menu-hover-underline:not(.none) .pagelayer-wp_menu-ul>li>a:before, {{element}} .pagelayer-menu-hover-doubleline:not(.none) .pagelayer-wp_menu-ul>li>a:before, {{element}} .pagelayer-menu-hover-doubleline:not(.none) .pagelayer-wp_menu-ul>li>a:after, {{element}} .pagelayer-menu-hover-overline:not(.none) .pagelayer-wp_menu-ul>li>a:before' => 'height:{{val}}px;',
				'{{element}} .pagelayer-menu-hover-framed .pagelayer-wp_menu-ul>li>a:hover:before' => 'border-width: {{val}}px'],
				'show' => ['pointer' => ['underline', 'overline', 'doubleline', 'framed']],
			],
			'horizontal_padding' => [
				'type' => 'slider',
				'label' => __pl('horizontal_padding'),
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li>a' => 'padding-left: {{val}}px;padding-right: {{val}}px;'],
			],
			'vertical_padding' => [
				'type' => 'slider',
				'label' => __pl('vertical_padding'),
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-ul>li>a' => 'padding-top: {{val}}px;padding-bottom	: {{val}}px;'],
			],
		],
		'submenu_style' => [
			'submenu_align' => array(
				'type' => 'radio',
				'label' => __pl('item_alignment'),
				'np' => 1,
				'default' => 'left',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} ul.sub-menu li>a' => 'justify-content:{{val}} !important;'],
			),
			'submenu_position' => [
				'type' => 'radio',
				'label' => __pl('position'),
				'default' => 'left',
				'list' => array(
					'left' => __pl('left'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-menu-type-horizontal .sub-menu' => '{{val}}:0px;', '{{element}} .pagelayer-menu-type-horizontal .sub-menu .sub-menu' => 'left:unset;{{val}}:100% !important;top:0px;'],
				'req' => ['layout' => 'horizontal'],
			],
			'submenu_v_position' => [
				'type' => 'radio',
				'label' => __pl('position'),
				'list' => array(
					'' => __pl('Default'),
					'left' => __pl('left'),
					'right' => __pl('right'),
				),
				'addClass' => ['{{element}} .pagelayer-menu-type-vertical' => 'pagelayer-submenu-position-{{val}}'],
				'req' => ['layout' => 'vertical'],
			],
			'submenu_colors' => array(
				'type' => 'radio',
				'label' => __pl('Background'),
				'np' => 1,
				//'no_val' => 1,// Dont set any value to element
				'list' => [
					'' => __pl('normal'),
					'hover' => __pl('hover'),
					'active' => __pl('active'),
				],
			),
			'submenu_color' => [
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu>li a' => 'color: {{val}};'],
				'show' => ['submenu_colors' => ''],
				'screen' => 1
			],
			'submenu_bg_color' => [
				'type' => 'color',
				'label' => __pl('bg_color'),
				'np' => 1,
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu' => 'background-color: {{val}};'],
				'show' => ['submenu_colors' => ''],
				'screen' => 1
			],
			'submenu_color_hover' => [
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu>li a:hover' => 'color: {{val}};', '{{element}} .pagelayer-wp-menu-container ul.sub-menu>li.pagelayer-active-sub-menu a:hover' => 'color: {{val}};'],
				'show' => ['submenu_colors' => 'hover'],
				'screen' => 1
			],
			'submenu_bg_color_hover' => [
				'type' => 'color',
				'label' => __pl('bg_color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu>li:hover' => 'background-color: {{val}};', '{{element}} .pagelayer-wp-menu-container ul.sub-menu>li.pagelayer-active-sub-menu' => 'background-color: {{val}};'],
				'show' => ['submenu_colors' => 'hover'],
				'screen' => 1
			],
			'submenu_color_active' => [
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu>li.current-menu-item a' => 'color: {{val}};'],
				'show' => ['submenu_colors' => 'active'],
				'screen' => 1
			],
			'submenu_bg_color_active' => [
				'type' => 'color',
				'label' => __pl('bg_color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu>li.current-menu-item' => 'background-color: {{val}};'],
				'show' => ['submenu_colors' => 'active'],
				'screen' => 1
			],
			'submenu_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu li a' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;']
			),
			'submenu_horizontal_padding' => [
				'type' => 'slider',
				'label' => __pl('horizontal_padding'),
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu li a' => 'padding-left: {{val}}px;padding-right: {{val}}px;'],
			],
			'submenu_vertical_padding' => [
				'type' => 'slider',
				'label' => __pl('vertical_padding'),
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container ul.sub-menu li a' => 'padding-top: {{val}}px;padding-bottom	: {{val}}px;'],
			],
			'submenu_left_margin' => [
				'type' => 'slider',
				'label' => __pl('left_margin'),
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp-menu-container .sub-menu a' => 'margin-left: {{val}}px;', '{{element}} .pagelayer-wp-menu-container .sub-menu .sub-menu a' => 'margin-left: calc(2 * {{val}}px);'],
			],
			'submenu_index' => [
				'type' => 'slider',
				'label' => __pl('z-index'),
				'css' => ['{{element}} .menu-item .sub-menu, {{element}} .menu-item .pagelayer-mega-menu' => 'z-index:{{val}}'],
			],
		],
		'menu_toggle' => [
			'menu_toggle_icon' => array(
				'type' => 'icon',
				'label' => __pl('icon'),
				'default' => 'fas fa-bars',
				'addAttr' => ['{{element}} .pagelayer-primary-menu-bar i' => 'data-icon="{{menu_toggle_icon}}"'],
				'desc' => __pl('menu_toggle_icon_desc')
			),
			'menu_toggle_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-primary-menu-bar' => 'text-align:{{val}}'],
			),
			'menu_toggle_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-primary-menu-bar i' => 'color:{{val}}'],
			),
			'menu_toggle_bg_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'np' => 1,
				'default' => '#0986c050',
				'css' => ['{{element}} .pagelayer-primary-menu-bar i' => 'background-color:{{val}}'],
			),
			'menu_toggle_size' => array(
				'type' => 'slider',
				'label' => __pl('font_size'),
				'default' => 30,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-primary-menu-bar i' => 'font-size:{{val}}px'],
			),
			'menu_toggle_padding' => array(
				'type' => 'dimension',
				'label' => __pl('padding'),
				'screen' => 1,
				'units' => ['px', 'em', '%'],
				'css' => ['{{element}} .pagelayer-primary-menu-bar i' => 'padding:{{val[0]}} {{val[1]}};'],
			),
		],
		'dropdown_style' => [
			'slide_style' => array(
				'type' => 'select',
				'label' => __pl('slide_style'),
				'default' => 'pagelayer-wp_menu-right',
				'list' => array(
					'pagelayer-wp_menu-down' => __pl('slide-down'),
					'pagelayer-wp_menu-right' => __pl('slide-right'),
					'pagelayer-wp_menu-left' => __pl('slide-left'),
					'pagelayer-wp_menu-full' => __pl('full_screen')
				),
				'req' => ['!drop_breakpoint' => 'none']
			),			
			'dropdown_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'screen' => 1,
				'list' => array(
					'flex-start' => __pl('left'),
					'center' => __pl('center'),
					'flex-end' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-menu-type-dropdown .pagelayer-wp_menu-ul > li > a' => 'justify-content: {{val}};'],
			),
			'menu_width' => [
				'type' => 'spinner',
				'label' => __pl('width'),
				'np' => 1,
				'default' => 30,
				'min' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-menu-type-dropdown' => 'width:{{val}}%;'],
				'req' => [
					'!drop_breakpoint' => 'none',
					'!slide_style' => ['pagelayer-wp_menu-full','pagelayer-wp_menu-down']
				]
			],
			'menu_down_width' => [
				'type' => 'spinner',
				'label' => __pl('width'),
				'default' => 100,
				'min' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-menu-type-dropdown' => 'width:{{val}}%;'],
				'req' => [
					'!drop_breakpoint' => 'none',
					'slide_style' => 'pagelayer-wp_menu-down'
				]
			],
			'menu_items_width' => [
				'type' => 'spinner',
				'label' => __pl('menu_items_width'),
				'default' => 100,
				'min' => 1,
				'max' => 100,
				'css' => ['{{element}} .pagelayer-menu-type-dropdown .pagelayer-wp_menu-ul' => 'width:{{val}}%;'],
				'req' => [
					'!drop_breakpoint' => 'none',
					'!slide_style' => ['pagelayer-wp_menu-down']
				]
			],
			'menu_posx' => array(
				'type' => 'slider',
				'label' => __pl('horizontal_pos'),
				'screen' => 1,
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 0,
				'css' => ['{{element}} .pagelayer-menu-type-dropdown .pagelayer-wp_menu-ul' => 'left: {{val}}%;'],
				'req' => [
					'!drop_breakpoint' => 'none',
					'!slide_style' => 'pagelayer-wp_menu-down'
				]
			),
			'menu_posy' => array(
				'type' => 'slider',
				'label' => __pl('verticle_postion'),
				'screen' => 1,
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 8,
				'css' => ['{{element}} .pagelayer-menu-type-dropdown .pagelayer-wp_menu-ul' => 'top: {{val}}%; transform: translateY(-{{val}}%);'],
				'req' => [
					'!drop_breakpoint' => 'none',
					'!slide_style' => 'pagelayer-wp_menu-down'
				]
			),
			'menu_bg' => array(
				'type' => 'radio',
				'label' => __pl('col_bg_styles'),
				'np' => 1,
				'default' => 'color',
				'list' => array(
					'' => __pl('none'),
					'color' => __pl('color'),
					'gradient' => __pl('gradient'),
				),
				'req' => ['!drop_breakpoint' => 'none']
			),
			'menu_items_bg' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-menu-type-dropdown' => 'background-color:{{val}}'],
				'req' => [
					'menu_bg' => 'color',
					'!drop_breakpoint' => 'none'
				],
			),
			'menu_items_gradient' => array(
				'type' => 'gradient',
				'label' => '',
				'default' => '150,#44d3f6,23,#72e584,45,#2ca4eb,100',
				'css' => ['{{element}} .pagelayer-menu-type-dropdown' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'],
				'req' => [
					'menu_bg' => 'gradient',
					'!drop_breakpoint' => 'none'
				],
			),
		],
		'close_style' =>[
			'close_size' => array(
				'type' => 'slider',
				'label' => __pl('font_size'),
				'np' => 1,
				'default' => 25,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => 'font-size:{{val}}px'],
				'req' => ['!drop_breakpoint' => 'none'],
			),
			'close_padding' => array(
				'type' => 'slider',
				'label' => __pl('padding'),
				'default' => 8,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => 'padding:{{val}}px'],
				'req' => ['!drop_breakpoint' => 'none'],
			),
			'close_pos_x' => array(
				'type' => 'slider',
				'label' => __pl('horizontal_pos'),
				'step' => 1,
				'max' => 100,
				'default' => 0,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => 'left:{{val}}%;'],
				'req' => ['!drop_breakpoint' => 'none'],
			),
			'close_pos_y' => array(
				'type' => 'slider',
				'label' => __pl('verticle_postion'),
				'step' => 1,
				'max' => 100,
				'default' => 0,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => 'top:{{val}}%;'],
				'req' => ['!drop_breakpoint' => 'none'],
			),
			'close_state' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'close_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff68',
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => 'color:{{val}}'],
				'req' => ['!drop_breakpoint' => 'none'],
				'show' => ['close_state' => '']
			),
			'close_bg_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '#00000036',
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => 'background-color:{{val}}'],
				'req' => ['!drop_breakpoint' => 'none'],
				'show' => ['close_state' => '']
			),
			'close_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 600,
				'css' => ['{{element}} .pagelayer-wp_menu-close i' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => ['close_state' => 'hover']
			),
			'close_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-wp_menu-close i:hover' => 'color:{{val}}'],
				'req' => ['!drop_breakpoint' => 'none'],
				'show' => ['close_state' => 'hover']
			),
			'close_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-wp_menu-close i:hover' => 'background-color:{{val}}'],
				'req' => ['!drop_breakpoint' => 'none'],
				'show' => ['close_state' => 'hover']
			),
		],
		'styles' => [
			'menu_style' => __pl('menu_style'),
			'submenu_style' => __pl('submenu_style'),
			'menu_toggle' => __pl('toggle_style'),
			'dropdown_style' => __pl('dropdown_style'),
			'close_style' => __pl('close_style'),
		]
	)
);

// Menu items handler
// Created this shortcode only use for menus property
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_nav_menu_item', array(
		'name' => __pl('Menu Items'),
		'group' => 'other',
		'not_visible' => 1,
		'hide_active' => 1,
		'parent' => [PAGELAYER_SC_PREFIX.'_wp_menu'],
		'icon' => 'far fa-minus-square',
		'holder' => '.pagelayer-menu-item-holder',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'element',
		],
		'html' => '<div class="pagelayer-menu-item-holder"></div>',
		'params' => array(
			'title' => array(
				'type' => 'text',
				'label' => __pl('title'),
			),
			'menu_type' => array(
				'type' => 'select',
				'label' => __pl('menu_type'),
				'desc' => __pl('menu_type_desc'),
				'list' => array(
					'' => __pl('flyout'),
					'mega' => __pl('mega_menu'),
					'column' => __pl('column_menu'),
				),
				'req' => ['menu_item_parent' => '0']
			),
			'element' => array(
				'type' => 'group',
				'label' => __pl('Inner Row'),
				'sc' => PAGELAYER_SC_PREFIX.'_inner_row',
				'count' => 1,
				'item_label' => array(
					'default' => __pl('Inner Row'),
				),
				'text' => __pl('Add new row'),
				'req' => ['menu_type' => 'mega']
			),
			'columns' => array(
				'type' => 'select',
				'label' => __pl('columns_count'),
				'screen' => 1,
				'css' => ['.menu-item-{{ID}} > .sub-menu' => 'columns: {{val}}'],
				'list' => array(
					'' => __pl('default'),
					1 => '1',
					2 => '2',
					3 => '3',
					4 => '4',
					5 => '5',
					6 => '6',
					7=> '7',
					8 => '8',
					9 => '9',
					10 => '10'
				),
				'req' => ['menu_type' => 'column']
			),
			'col_gap' => array(
				'type' => 'slider',
				'label' => __pl('col_gap'),
				'min' => 0,
				'max' => 100,
				'screen' => 1,
				'css' => ['.menu-item-{{ID}} > .sub-menu' => 'column-gap: {{val}}px;'],
				'req' => ['menu_type' => 'column']
			),
			'mega_width' => array(
				'type' => 'select',
				'label' => __pl('mega_width'),
				'list' => array(
					'' => __pl('menu_container'),
					'row_container' => __pl('row_container'),
					'custom' => __pl('custom'),
				),
				'addAttr' => 'pagelayer-mega-width="{{mega_width}}"',
				'req' => ['!menu_type' => '']
			),
			'mega_custom_width' => array(
				'type' => 'spinner',
				'label' => __pl('mega_custom_width'),
				'default' => '800',
				'addAttr' => 'pagelayer-mega-custom-width="{{mega_custom_width}}"',
				'req' => ['mega_width' => 'custom', '!menu_type' => '']
			),
			'menu_icon' => array(
				'type' => 'icon',
				'label' => __pl('icon'),
			),
		),
		'menu_setting' => array(
			'disable_link' => array(
				'type' => 'checkbox',
				'label' => __pl('disable_link'),
			),
			'hide_text' => array(
				'type' => 'checkbox',
				'label' => __pl('hide_text'),
				'css' => ['.menu-item-{{ID}} > a .pagelayer-nav-menu-title' => 'display:none']
			),
			'icon_position' => array(
				'type' => 'select',
				'label' => __pl('icon_position'),
				'list' => array(
					'' => __pl('left'),
					'right' => __pl('right'),
				),
			),
		),
		'highlight' => array(
			'highlight_label' => array(
				'type' => 'text',
				'label' => __pl('highlight_label'),
			),
			'highlight_bg' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'css' => ['.menu-item-{{ID}} > a .pagelayer-menu-highlight' => 'background:{{val}}']
			),
			'highlight_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['.menu-item-{{ID}} > a .pagelayer-menu-highlight' => 'color:{{val}}']
			),
		),
		'styles' => array(
			'menu_setting' => __pl('menu_setting'),
			'highlight' => __pl('highlight_label'),
		),
	)	
);

// Breadcrumb
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_breadcrumb', array(
		'name' => __pl('breadcrumb'),
		'group' => 'other',
		'html' => '<span if="{{prefix}}" class="pagelayer-breadcrumb-prefix">{{prefix}}</span>
			<span class="pagelayer-breadcrumb-section">'.
			pagelayer_get_breadcrumb().
		'</span>',
		//'html' => yoast_breadcrumb( '<p id="breadcrumbs">','</p>' ).pagelayer_get_breadcrumb(),
		'params' => array(
			'home' => array(
				'type' => 'text',
				'label' => __pl('home_label'),
				'np' => 1,
				'default' => 'Home',
			),
			'breadcrumb_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-breadcrumb-section a' => 'color:{{val}};']
			),
			'breadcrumb_hover' => array(
				'type' => 'color',
				'label' => __pl('hovered_color'),
				'css' => ['{{element}} .pagelayer-breadcrumb-section a:hover' => 'color:{{val}};']
			),
			'cur_color' => array(
				'type' => 'color',
				'label' => __pl('cur_color'),
				'css' => ['{{element}} .pagelayer-breadcrumb-section' => 'color:{{val}};']
			),
			'typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-breadcrumb-section' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],			
			),
			'alignment' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'css' =>'text-align:{{val}};',
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
			),
		),
		'prefix_style' => [
			'prefix' => array(
				'type' => 'text',
				'label' => __pl('breadcrumb_prefix'),
			),
			'search_prefix' => array(
				'type' => 'text',
				'label' => __pl('search_prefix'),
			),
			'404_prefix' => array(
				'type' => 'text',
				'label' => __pl('404_prefix'),
			),
			'prefix_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-breadcrumb-prefix' => 'color:{{val}};']
			),
			'prefix_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-breadcrumb-prefix' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],			
			),
		],
		'separator_style' => [
			'separator' => array(
				'type' => 'text',
				'label' => __pl('separator_style'),
				'default' => '&nbsp;&#187;&nbsp;',
			),
			'separator_color' => array(
				'type' => 'color',
				'label' => __pl('color'),				
				'np' => 1,
				'default' => '#333333',
				'css' => ['{{element}} .pagelayer-breadcrumb-sep' => 'color:{{val}};']
			),
		],
		'styles' => [
			'prefix_style' => __pl('prefix_style'),
			'separator_style' => __pl('separator_style'),
		]
	)
);

// Contact Form
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_contact', array(
		'name' => __pl('contact_form'),
		'group' => 'other',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'holder' => '.pagelayer-contact-holder',
		'html' => '<div class="pagelayer-contact-form-div pagelayer-contact-form-holder">
					<div class="pagelayer-message-box pagelayer-message-top"></div>
					<div if={{note}} class="pagelayer-contact-form-note">{{note}}</div>
					<form class="pagelayer-contact-form" id="{{form}}" name="{{name}}" onsubmit="return pagelayer_contact_submit(this, event)" method="POST">
						<div class="pagelayer-contact-holder">
						</div>
						<input type="hidden" name="cfa-pagelayer-id"/>
						<input if="{{con_post_id}}" type="hidden" name="cfa-post-id" value="{{con_post_id}}"/>
						<input if="{{contact_custom_templ}}" type="hidden" name="cfa-custom-template" value="{{contact_custom_templ}}"/>
						<input if="{{redirect_url}}" type="hidden" name="cfa-redirect" value="{{{redirect_url}}}"/>
						<div class="g-recaptcha pagelayer-recaptcha" data-sitekey="{{grecaptcha}}" if="{{captcha}}"></div>
						<div class="pagelayer-contact-submit-holder">
							<button if="{{submit}}" type="submit" form="{{form}}" class="pagelayer-contact-submit-btn pagelayer-btn-holder pagelayer-ele-link {{type}} {{size}} {{icon_position}}">
								<i class="fa fa-circle-o-notch fa-spin" style="display:none;margin-right:5px;"></i>
								<i if="{{icon}}" class="{{icon}} pagelayer-btn-icon pagelayer-cf-icon-left"></i>
								<span if="{{submit}}" class="pagelayer-btn-text">{{submit}}</span>
								<i if="{{icon}}" class="{{icon}} pagelayer-btn-icon pagelayer-cf-icon-right"></i>
							</button>
						</div>
					</form>
					<div class="pagelayer-message-box pagelayer-message-bottom"></div>
			</div>',
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('Label'),
				'sc' => PAGELAYER_SC_PREFIX.'_contact_item',
				'item_label' => array(
					'default' => __pl('Label'),
					'param' => 'label_name'
				),
				'count' => 1,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('field_name'))),
			),
			'redirect_show' => array(
				'type' => 'checkbox',
				'label' => __pl('redirect_url'),
			),
			'redirect_url' => array(
				'type' => 'link',
				'label' => __pl('redirect_urllabel'),
				'desc' => __pl('redirect_urldesc'),
				'req' => array(
					'redirect_show' => 'true'
				)
			),
			'captcha' => array(
				'type' => 'checkbox',
				'label' => __pl('use_recaptcha'),
				'default' => '',
				'desc' => __pl('use_recaptcha_desc'),
			),
			'form' => array(
				'type' => 'text',
				'label' => __pl('form_id'),
				'desc' => __pl('form_id_desc'),
				'default' => 'contact-form',
				'np' => 1
			),
			'name' => array(
				'type' => 'text',
				'label' => __pl('form_name'),
				'default' => 'Contact Form',
				'desc' => __pl('form_name_desc'),
				'np' => 1
			),
			'form_position' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'default',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-contact-form' => 'text-align: {{val}}'],
			),
			'note' => array(
				'type' => 'text',
				'label' => __pl('note'),
				'edit' => '.pagelayer-contact-form-note',
			),
			'note_color' => array(
				'type' => 'color',
				'label' => __pl('note_color'),
				'css' => ['{{element}} .pagelayer-contact-form-note' => 'color:{{val}}'],
			),
		),
		'label_style' =>[
			'form_label_color' => array(
				'type' => 'color',
				'label' => __pl('label_color'),
				'default' => '',
				'show' => ['field_state' => ''],
				'css' => ['{{element}} label' => 'color: {{val}}'],
			),
			'label_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} label' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'space_label' => array(
				'type' => 'padding',
				'label' => __pl('space_between'),
				'default' => ',,10,',
				'screen' => 1,
				'css' => ['{{element}} label' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;'],
			),
			'form_placeholder_color' => array(
				'type' => 'color',
				'label' => __pl('placeholder_color'),
				'default' => '',
				'show' => ['field_state' => ''],
				'css' => ['{{element}} ::placeholder' => 'color: {{val}}'],
			),
			'placeholder_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} ::placeholder' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'input_style' => [
			'input_state' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'np' => 1,
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'form_input_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => [
					'{{element}} textarea, {{element}} input, {{element}} select, {{element}} date' => 'color: {{val}}'
				],
				'req' => array(
					'input_state' => 'normal'
				),
			),
			'form_input_bg' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'css' => [
					'{{element}} textarea, {{element}} input, {{element}} select, {{element}} date' => 'background-color: {{val}}',
				],
				'req' => array(
					'input_state' => 'normal',
				),
			),
			'input_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('input_hover_delay_label'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'css' => ['{{element}} textarea:hover, {{element}} input:hover, {{element}} select:hover, {{element}} date:hover' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => array(
					'input_state' => 'hover'
				),
			),
			'form_input_color_hover' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => [
					'{{element}} textarea:hover, {{element}} input:hover, {{element}} select:hover, {{element}} date:hover' => 'color: {{val}}'
				],
				'req' => array(
					'input_state' => 'hover'
				),
			),
			'form_input_bg_hover' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'css' => [
					'{{element}} textarea:hover, {{element}} input:hover, {{element}} select:hover, {{element}} date:hover' => 'background-color: {{val}}'
				],
				'req' => array(
					'input_state' => 'hover'
				),
			),
			'input_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} select, {{element}} input,{{element}} textarea' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'input_height' => array(
				'type' => 'spinner',
				'label' => __pl('text_field_height'),
				'min' => 1,
				'max' => 1000,
				'step' => 1,
				'screen' => 1,
				'css' => [
					'{{element}} input, {{element}} select, {{element}} textarea'=> 'line-height: {{val}}px; min-height: {{val}}px',
				],
			),
			'input_padding' => array(
				'type' => 'dimension',
				'label' => __pl('padding'),
				'screen' => 1,
				'default' => '10,10',
				'css' => [
					'{{element}} input, {{element}} select, {{element}} textarea'=> 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[0]}}px; padding-left:{{val[1]}}px',
				],
			),
			/* 'input_state' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				'list' => [
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				],
			), */
		],
		'form_style' => [
			'field_state' => array(
				'type' => 'radio',
				'label' => '',
				'np' => 1,
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
					'focus' => __pl('focus'),
				),
			),
			'field_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => [
					'{{element}} input, {{element}} select, {{element}} textarea' => 'border-style: {{val}}',
					'{{element}} input[type="checkbox"] + label:before' => 'border-style: {{val}}'
				],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'field_state' => ''
				),
			),
			'field_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#0986c0',
				'css' => [
					'{{element}} input, {{element}} select, {{element}} textarea' => 'border-color: {{val}};',
					'{{element}} input[type="checkbox"] + label:before' => 'border-color: {{val}};'
				],
				'req' => array(
					'!field_border_type' => ''
				),
				'show' => array(
					'field_state' => ''
				),
			),
			'field_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} input, {{element}} select, {{element}} textarea, {{element}} input[type="checkbox"] + label:before' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!field_border_type' => ''
				],
				'show' => array(
					'field_state' => ''
				),
			),
			'field_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} input, {{element}} select, {{element}} textarea' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!field_border_type' => ''
				),
				'show' => array(
					'field_state' => ''
				),
			),
			'field_shadow' => array(
				'type' => 'box_shadow',
				'label' => __pl('shadow'),
				'screen' => 1,
				'css' => [
					'{{element}} input, {{element}} select, {{element}} textarea, {{element}} input[type="checkbox"] + label:before' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'
				],
				'show' => ['field_state' => ''],
			),
			'field_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_btn_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} input, {{element}} select, {{element}} textarea' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => array(
					'field_state' => 'hover'
				),
			),
			'field_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} input:hover, {{element}} select:hover, {{element}} textarea:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'field_state' => 'hover'
				),
			),
			'field_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'css' => ['{{element}} input:hover, {{element}} select:hover, {{element}} textarea:hover' => 'border-color: {{val}};'],
				'default' => '#0986c0',
				'req' => array(
					'!field_border_type_hover' => ''
				),
				'show' => array(
					'field_state' => 'hover'
				),
			),
			'field_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} input:hover, {{element}} select:hover, {{element}} textarea:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!field_border_type_hover' => ''
				],
				'show' => array(
					'field_state' => 'hover'
				),
			),
			'field_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} input:hover, {{element}} select:hover, {{element}} textarea:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!field_border_type_hover' => ''
				),
				'show' => array(
					'field_state' => 'hover'
				),
			),
			'field_shadow_hover' => array(
				'type' => 'box_shadow',
				'label' => __pl('text_shadow'),
				'screen' => 1,
				'css' => [
					'{{element}} input:hover, {{element}} select:hover, {{element}} textarea:hover' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'
				],
				'show' => ['field_state' => 'hover'],
			),
			'field_border_type_focus' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} input:focus, {{element}} select:focus, {{element}} textarea:focus' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'field_state' => 'focus'
				),
			),
			'field_border_color_focus' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'css' => ['{{element}} input:focus, {{element}} select:focus, {{element}} textarea:focus' => 'border-color: {{val}};'],
				'default' => '#0986c0',
				'req' => array(
					'!field_border_type_focus' => ''
				),
				'show' => array(
					'field_state' => 'focus'
				),
			),
			'field_border_width_focus' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} input:focus, {{element}} select:focus, {{element}} textarea:focus' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!field_border_type_focus' => ''
				],
				'show' => array(
					'field_state' => 'focus'
				),
			),
			'field_border_radius_focus' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} input:focus, {{element}} select:focus, {{element}} textarea:focus' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!field_border_type_focus' => ''
				),
				'show' => array(
					'field_state' => 'focus'
				),
			),
			'field_shadow_focus' => array(
				'type' => 'box_shadow',
				'label' => __pl('text_shadow'),
				'screen' => 1,
				'css' => [
					'{{element}} input:focus, {{element}} select:focus, {{element}} textarea:focus' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'
				],
				'show' => ['field_state' => 'focus'],
			),
		],
		'radio_style' => [
			'form_box_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '',
				'css' => [
					'{{element}} input[type="radio"]:checked:before' => 'color: {{val}}', 
					'{{element}} input[type="checkbox"]:checked + label:before' => 'color: {{val}}'
				],
			),
			'form_box_bg_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '',
				'css' => [
					'{{element}} input[type="radio"]:checked:before' => 'background-color: {{val}}', 
					'{{element}} input[type="checkbox"]:checked + label:before' => 'background-color: {{val}}'
				],
			),
			'inline_radio' => array(
				'type' => 'checkbox',
				'label' => __pl('inline'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-radcheck-holder' => 'display:flex; align-items:center;']
			),
			'radio_padding' => array(
				'type' => 'dimension',
				'label' => __pl('padding'),
				'screen' => 1,
				'default' => '10,10',
				'css' => [
					'{{element}} input[type="checkbox"] + label:before, {{element}} input[type="radio"]'=> 'height:{{val[0]}}px; min-height:{{val[0]}}px;  width:{{val[1]}}px;',
				],
			),
			'radio_spacing' => array(
				'type' => 'padding',
				'label' => __pl('space_around'),
				'screen' => 1,
				'css' => [
					'{{element}} .pagelayer-radcheck-holder>div'=> 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;',
				],
			),
		],
		'button_style' => [
			'hide_btn' => array(
				'type' => 'checkbox',
				'label' => __pl('hide_btn'),	
			),
			'submit' => array(
				'type' => 'text',
				'label' => __pl('submit_button_label'),
				'default' => 'Submit',
				'edit' => '.pagelayer-btn-text',
				'req' => ['!hide_btn' => 'true'],
			),
			'btn_typo' => array(
				'type' => 'typography',
				'label' => __pl('quote_content_typo'),
				'css' => [
					'{{element}} .pagelayer-btn-text' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
					'{{element}} .pagelayer-btn-holder' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
				],
				'req' => ['!hide_btn' => 'true'],
			),
			'stretch' => array(
				'type' => 'checkbox',
				'label' => __pl('stretch_button_label'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-contact-submit-btn' => 'width: 100%'],
				'req' => ['!hide_btn' => 'true'],
			),
			'btn_spacing' => array(
				'type' => 'padding',
				'label' => __pl('spacing'),
				'css' => ['{{element}} .pagelayer-contact-submit-btn' => 'margin-top:{{val[0]}}px; margin-right:{{val[1]}}px; margin-bottom:{{val[2]}}px; margin-left:{{val[3]}}px;'],
				'req' => [
					'!hide_btn' => 'true',
				]
			),
			'type' => array(
				'type' => 'select',
				'label' => __pl('button_type_label'),
				'default' => 'pagelayer-btn-default',
				//'addClass' => ['{{element}} .pagelayer-btn-holder' => '{{val}}'],
				'list' => array(
					'pagelayer-btn-default' => __pl('btn_type_default'),
					'pagelayer-btn-primary' => __pl('btn_type_primary'),
					'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
					'pagelayer-btn-success' => __pl('btn_type_success'),
					'pagelayer-btn-info' => __pl('btn_type_info'),
					'pagelayer-btn-warning' => __pl('btn_type_warning'),
					'pagelayer-btn-danger' => __pl('btn_type_danger'),
					'pagelayer-btn-dark' => __pl('btn_type_dark'),
					'pagelayer-btn-light' => __pl('btn_type_light'),
					'pagelayer-btn-link' => __pl('btn_type_link'),
					'pagelayer-btn-custom' => __pl('btn_type_custom')
				),
				'req' => ['!hide_btn' => 'true'],
			),
			'size' => array(
				'type' => 'select',
				'label' => __pl('button_size_label'),
				'default' => 'pagelayer-btn-small',
				'list' => array(
					'pagelayer-btn-mini' => __pl('mini'),
					'pagelayer-btn-small' => __pl('small'),
					'pagelayer-btn-large' => __pl('large'),
					'pagelayer-btn-extra-large' => __pl('extra_large'),
					'pagelayer-btn-double-large' => __pl('double_large'),
					'pagelayer-btn-custom' => __pl('custom'),
				),
				'req' => ['!hide_btn' => 'true'],
			),
			'btn_custom_size' => array(
				'type' => 'dimension',
				'label' => __pl('btn_custom_size'),
				'default' => '5,10',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[0]}}px; padding-left:{{val[1]}}px;'],
				'req' => array(
					'size' => 'pagelayer-btn-custom',
					'!hide_btn' => 'true'
				),
			),
			'contect_btn_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-contact-submit-holder' => 'text-align:{{val}}'],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'req' => ['!hide_btn' => 'true'],
			),
			'btn_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				//'no_val' => 1,// Dont set any value to element
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => array(
					'type' => 'pagelayer-btn-custom',
					'!hide_btn' => 'true'
				),
			),
			'btn_bg_color' => array(
				'type' => 'color',
				'label' => __pl('btn_bg_color_label'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-btn-holder' => 'background-color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => '',
					'!hide_btn' => 'true'
				),
			),
			'btn_color' => array(
				'type' => 'color',
				'label' => __pl('btn_color_label'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-btn-holder' => 'color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => '',
					'!hide_btn' => 'true'
				),
			),
			'btn_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('btn_hover_delay_label'),
				'desc' => __pl('btn_hover_delay_desc'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-btn-holder' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => array(
					'btn_hover' => 'hover',
					'!hide_btn' => 'true'
				),
			),
			'btn_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('btn_bg_color_hover_label'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'background-color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => 'hover',
					'!hide_btn' => 'true'
				),
			),
			'btn_color_hover' => array(
				'type' => 'color',
				'label' => __pl('btn_color_hover_label'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => 'hover',
					'!hide_btn' => 'true'
				),
			),
		],
		'icon_style' => [
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('service_box_font_icon_label'),
				'default' => '',
			),
			'icon_position' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'pagelayer-btn-icon-left',
				'list' => array(
					'pagelayer-btn-icon-left' => __pl('left'),
					'pagelayer-btn-icon-right' => __pl('right')
				),
				'req' => array(
					'!icon' => ''
				),
			),
			'icon_spacing' => array(
				'type' => 'slider',
				'label' => __pl('icon_spacing'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-icon' => 'padding: 0 {{val}}px;'],
				'req' => array(
					'!icon' => ''
				),
			),
		],
		'border_style' => [
			'btn_bor_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'np' => 1,
				'default' => '',
				//'no_val' => 1,// Dont set any value to element
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				)
			),	
			'btn_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-color: {{val}};'],
				'req' => array(
					'!btn_border_type' => ''
				),
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!btn_border_type' => ''
				],
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!btn_border_type' => ''
				),
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
			'btn_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('border_color_hover_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-color: {{val}};'],
				'req' => array(
					'!btn_border_type_hover' => ''
				),
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
			'btn_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!btn_border_type_hover' => ''
				],
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
			'btn_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!btn_border_type_hover' => ''
				),
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
		],
		'message_style' => array(
			'show_msg_box' => array(
				'type' => 'checkbox',
				'label' => __pl('show_msg_box'),
				'desc' => __pl('show_msg_box_desc')
			),
			'message_pos' => array(
				'type' => 'radio',
				'label' => __pl('position'),
				'list' => array(
					'' => __pl('top'),
					'bottom' => __pl('bottom'),
				),
				'addClass' => 'pagelayer-message-box-{{val}}'
			),
			'msg_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'css' => ['{{element}} .pagelayer-cf-msg-suc, {{element}} .pagelayer-cf-msg-err' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;'],
				
			),
			'msg_margin' => array(
				'type' => 'padding',
				'label' => __pl('margin'),
				'css' => ['{{element}} .pagelayer-cf-msg-suc, {{element}} .pagelayer-cf-msg-err' => 'margin-top:{{val[0]}}px; margin-right:{{val[1]}}px; margin-bottom:{{val[2]}}px; margin-left:{{val[3]}}px;'],
			),
			'msg_type' => array(
				'type' => 'radio',
				'label' => __pl(' '),
				'list' => array(
					'success' => __pl('Success'),
					'failed' => __pl('failed')
				)
			),
			'msg_success_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => [
					'{{element}} .pagelayer-cf-msg-suc' => 'color: {{val}}'
				],
				'show' => array(
					'msg_type' => 'success'
				),
			),
			'msg_success_bg' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'css' => [
					'{{element}} .pagelayer-cf-msg-suc' => 'background-color: {{val}}',
				],
				'show' => array(
					'msg_type' => 'success'
				),
			),
			'succ_msg_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => [
					'{{element}} .pagelayer-cf-msg-suc' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'msg_type' => 'success'
				)
			),
			'succ_msg_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#0986c0',
				'css' => [
					'{{element}} .pagelayer-cf-msg-suc' => 'border-color: {{val}};'
				],
				'req' => array(					
					'!succ_msg_border_type' => '' 
				),
				'show' => array(
					'msg_type' => 'success'
				),
			),
			'succ_msg_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-cf-msg-suc' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => array(					
					'!succ_msg_border_type' => '' 
				),
				'show' => array(
					'msg_type' => 'success'
				),
			),
			'succ_msg_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-cf-msg-suc' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(					
					'!succ_msg_border_type' => '' 
				),
				'show' => array(
					'msg_type' => 'success'
				),
			),
			
			'msg_failed_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'css' => [
					'{{element}} .pagelayer-cf-msg-err' => 'color: {{val}}'
				],
				'show' => array(
					'msg_type' => 'failed'
				),
			),
			'msg_failed_bg' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'css' => [
					'{{element}} .pagelayer-cf-msg-err' => 'background-color: {{val}}',
				],
				'show' => array(
					'msg_type' => 'failed'
				),
			),
			'fail_msg_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => [
					'{{element}} .pagelayer-cf-msg-err' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'msg_type' => 'failed'
				),
			),
			'fail_msg_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#0986c0',
				'css' => [
					'{{element}} .pagelayer-cf-msg-err' => 'border-color: {{val}};'
				],
				'req' => array(					
					'!fail_msg_border_type' => '' 
				),
				'show' => array(
					'msg_type' => 'failed'
				),
			),
			'fail_msg_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-cf-msg-err' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => array(					
					'!fail_msg_border_type' => '' 
				),
				'show' => array(
					'msg_type' => 'failed'
				),
			),
			'fail_msg_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-cf-msg-err' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => ['!fail_msg_border_type' => ''],
				'show' => ['msg_type' => 'failed'],
			)
		),
		'mail_template' => array(
			'contact_custom_templ' => array(
				'type' => 'checkbox',
				'label' => __pl('custom_templ'),
			),
			'templ_modal' => array(
				'type' => 'modal',
				'label' => __pl('create_mail_templ'),
				'show_group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
			'to_email' => array(
				'type' => 'text',
				'label' => __pl('to_email'),
				'group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
			'from_email' => array(
				'type' => 'text',
				'label' => __pl('from_email'),
				'group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
			'cont_subject' => array(
				'type' => 'text',
				'label' => __pl('subject'),
				'group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
			'cont_header' => array(
				'type' => 'textarea',
				'label' => __pl('additional_head'),
				'group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
			'cont_body' => array(
				'type' => 'textarea',
				'label' => __pl('message_body'),
				'desc' => __pl('usr_field_desc'),
				'rows' => 4,
				'group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
			'cont_use_html' => array(
				'type' => 'checkbox',
				'label' => __pl('use_html'),
				'group' => 'contact_templ_modal',
				'req' => ['contact_custom_templ' => 'true'],
			),
		),
		'styles' => [
			'label_style' => __pl('label_style'),
			'input_style' => __pl('input_style'),
			'radio_style' => __pl('radio_style'),
			'form_style' => __pl('form_style'),
			'button_style' =>  __pl('button_style'),
			'icon_style' =>  __pl('icon_style'),
			'border_style' =>  __pl('btn_border_style'),
			'message_style' =>  __pl('message_style'),
			'mail_template' =>  __pl('mail_template'),
		]		
	)
);

pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_contact_item', array(
		'name' => __pl('contact_item'),
		'group' => 'other',
		'not_visible' => 1,
		'html' => '{{fieldhtml}}',
		'parent' => [PAGELAYER_SC_PREFIX.'_contact'],
		'params' => array(
			'label_name' => array(
				'type' => 'text',
				'label' => __pl('label_name'),
				'default' => 'Input Label',
				'edit' => '.pagelayer-form-label',
				'keep_prop' => 1, // Prevent to remove props in gutenburg
				'np' => 1
			),
			'field_type' => array(
				'type' => 'select',
				'label' => __pl('input_field_type'),
				'default' => 'text',
				'list' => array(
					'text' => __pl('text'),
					'email' => __pl('e-mail'),
					'number' => __pl('number'),
					'tel' => __pl('telephone'),
					'checkbox' => __pl('checkbox'),
					'radio' => __pl('radio'),
					'textarea' => __pl('textarea'),
					'select' => __pl('select'),
					'date' => __pl('date'),
					'file' => __pl('file'),
					'label' => __pl('label'),
				),
				'np' => 1
			),
			'values' => array(
				'type' => 'textarea',
				'label' => __pl('values'),
				'default' => "One\nTwo",
				'show' => array(
					'field_type' => ['select', 'checkbox', 'radio']
				),
				'np' => 1
			),
			'textarea_rows' => array(
				'type' => 'spinner',
				'label' => __pl('row'),
				'default' => 6,
				'min' => 1,
				'max' => 100,
				'screen' => 1,
				'req' => array(
					'field_type' => ['textarea']
				),
				'np' => 1
			),
			'accept_file' => array(
				'type' => 'text',
				'label' => __pl('accept_file'),
				'req' => array(
					'field_type' => ['file']
				),
				'np' => 1
			),
			'required' => array(
				'type' => 'checkbox',
				'label' => __pl('required_label'),
				'default' => '',
			),
			'label_as_holder' => array(
				'type' => 'checkbox',
				'label' => __pl('show_label_as_placeholder'),
				'default' => '',
				'req' => ['!field_type' => ['label']],
			),
			'placeholder' => array(
				'type' => 'text',
				'label' => __pl('placeholder'),
				'default' => '',
				'req' => ['!field_type' => ['label']],
				'show' => ['!label_as_holder' => 'true'],
			),
			'field_name' => array(
				'type' => 'text',
				'label' => __pl('input_field_name'),
				'np' => 1,
				'default' => 'Fieldname',
				'req' => ['!field_type' => ['label']],
			),
			'field_width' => array(
				'type' => 'slider',
				'label' => __pl('width'),
				'default' => 100,
				'min' => 1,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{wrap}}' => 'width:{{val}}%'],
			),
			'field_display' => array(
				'type' => 'select',
				'label' => __pl('display'),
				'screen' => 1,
				'default' => '',
				'css' => ['{{wrap}}' => 'display:{{val}}'],
				'list' => array(
					'' => __pl('full'),
					'inline-block' => __pl('inline'),
				),
			),
			'space_between' => array(
				'type' => 'padding',
				'label' => __pl('space_between'),
				'default' => ',,10,',
				'screen' => 1,
				'css' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;',
			),
		)
	)
);

// Post Title
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_title', array(
		'name' => __pl('post_title'),
		'group' => 'other',
		'html' => '<div class="pagelayer-post-title">
			<a class="pagelayer-ele-link" if-ext="{{link}}" href="{{{link}}}">
				{{open_html_tag}}<span if="{{before}}">{{before}} </span>'.pagelayer_get_the_title(false).'<span if="{{after}}"> {{after}}</span>{{close_html_tag}}
			</a>
		</div>',
		'params' => array(
			'before' => array(
				'type' => 'text',
				'label' => __pl('before'),
				'np' => 1,
			),
			'after' => array(
				'type' => 'text',
				'label' => __pl('after'),
				'np' => 1,
			),
			'link' => array(
				'type' => 'link',
				'label' => __pl('image_link_label'),
				'selector' => '.pagelayer-ele-link',
			),
			'html_tag' => array(
				'type' => 'select',
				'label' => __pl('html_tag'),
				'default' => 'h1',
				'list' => [
					'' => __pl('default'),
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
			),
			'title_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#0986c0',
				'css' => [
					'{{element}} .pagelayer-post-title'=> 'color:{{val}}',
					'{{element}} .pagelayer-post-title *'=> 'color:{{val}}'
				],
			),
			'typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => ',35,,700,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-post-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'shadow' => array(
				'type' => 'shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} .pagelayer-post-title' => 'text-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}};'],
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'screen' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				],
				'css' => 'text-align: {{val}}',
			),
		)
	)
);

// Post Content
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_content', array(
		'name' => __pl('post_content'),
		'group' => 'other',
		'html' => '<div class="entry-content pagelayer-post-excerpt">{{post_content}}</div>',
		'params' => array(
			'color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-post-excerpt' => 'color:{{val}}'],
			),
			'typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-post-excerpt' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				],
				'css' => 'text-align: {{val}}'
			),
		)
	)
);

// Post Excerpt
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_excerpt', array(
		'name' => __pl('post_excerpt'),
		'group' => 'other',
		'html' => '{{{post_excerpt}}}',
		'params' => array(
			'color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'css' => ['{{element}} .pagelayer-post-excerpt' => 'color:{{val}}'],
			),
			'typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-post-excerpt' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				],
				'css' => 'text-align: {{val}}',
			),
			'pe_margin' => array(
				'type' => 'padding',
				'label' => __pl('margin'),
				'screen' => 1,
				'units' => ['px', 'em'],
				'css' => ['{{element}} .pagelayer-post-excerpt' => 'margin-top:{{val[0]}}; margin-right:{{val[1]}}; margin-bottom:{{val[2]}}; margin-left:{{val[3]}};'],
			),
		)
	)
);

// Featured Image
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_featured_img', array(
		'name' => __pl('featured_img'),
		'group' => 'other',
		'html' => '<a if-ext="{{link_type}}" href="{{func_link}}" class="pagelayer-ele-link" pagelayer-image-link-type="{{link_type}}">
			<div class="pagelayer-featured-img">{{img_html}}</div>
		</a>
		<p if="{{caption}}" class="pagelayer-featured-caption">{{caption}}</p>',
		'params' => array(
			'img' => array(
				'label' => __pl('fallback_img'),
				'type' => 'image',
				'np' => 1,
			),
			'size' => array(
				'label' => __pl('obj_image_size_label'),
				'type' => 'select',
				'default' => 'full',
				'list' => array(
					'full' => __pl('full'),
					'large' => __pl('large'),
					'medium' => __pl('medium'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom')
				)
			),
			'custom_size' => array(
				'type' => 'dimension',
				'units' => ['px', '%'],
				'screen' => 1,
				'label' => __pl('image_custom_size_label'),
				'css' => ['{{element}} img' => 'width: {{val[0]}}; height: {{val[1]}};'],
				'req' => ['size' => 'custom']
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'screen' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				],
				'css' => 'text-align: {{val}}',
			),
			'img_filter' => array(
				'type' => 'filter',
				'label' => __pl('filter'),
				'default' => '0,100,100,0,0,100,100',
				'css' => ['{{element}} img' => 'filter: blur({{val[0]}}px) brightness({{val[1]}}%) contrast({{val[2]}}%) grayscale({{val[3]}}%) hue-rotate({{val[4]}}deg) opacity({{val[5]}}%) saturate({{val[6]}}%)'],
			),
			'img_shadow' => array(
				'type' => 'box_shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} img' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}} !important;'],
			),
		),
		'link_settings' => [
			'link_type' => array(
				'label' => __pl('image_link_label'),
				'type' => 'select',
				'default' => '',
				'list' => array(
					'' => __pl('none'),
					'custom_url' => __pl('custom_url'),
					'media_file' => __pl('media_file'),
					'lightbox' => __pl('lightbox')
				)
			),
			'link' => array(
				'type' => 'link',
				'label' => __pl('image_link_label'),
				'desc' => __pl('image_link_desc'),
				'selector' => '.pagelayer-ele-link',
				'req' => array(
					'link_type' => 'custom_url'
				)
			),
			'rel' => array(
				'label' => __pl('image_rel_label'),
				'type' => 'text',
				'default' => '',
				'addAttr' => ['{{element}} a' => 'rel="{{rel}}"'],
				'req' => array(
					'link_type' => 'media_file'
				)
			),
			'target' => array(
				'label' => __pl('open_link_in_new_window'),
				'type' => 'checkbox',
				'addAttr' => ['{{element}} a' => 'target="_blank"'],
				'req' => ['link_type' => ['custom_url', 'media_file']], // For backward compatibility of the new link property in version 1.5.8, hide it for custom_url.
				'show' => ['link_type' => 'media_file']
			),
		],
		'caption_style' => [
			'caption' => array(
				'label' => __pl('gallery_grid_caption_label'),
				'desc' => __pl('gallery_grid_caption_desc'),
				'np' => 1,
				'type' => 'text',
			),
			'caption_color' => array(
				'label' => __pl('Caption Color'),
				'type' => 'color',
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-featured-caption' => 'color: {{val}}'],
			),
			'caption_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-featured-caption' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'border_style' => [
			'f_border_hover' => array(
				'type' => 'radio',
				'label' => '',
				'np' => 1,
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'img_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} img' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'f_border_hover' => ''
				),
			),
			'img_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#0986c0',
				'css' => ['{{element}} img' => 'border-color: {{val}};'],
				'req' => array(
					'!img_border_type' => ''
				),
				'show' => array(
					'f_border_hover' => ''
				),
			),
			'img_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} img' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!img_border_type' => ''
				],
				'show' => array(
					'f_border_hover' => ''
				),
			),
			'img_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} img' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'show' => array(
					'f_border_hover' => ''
				),
			),
			'img_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_btn_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 2000,
				'default' => 400,
				'css' => ['{{element}} img' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['f_border_hover' => 'hover'],
			),
			'img_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} img:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'f_border_hover' => 'hover'
				),
			),
			'img_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'css' => ['{{element}} img:hover' => 'border-color: {{val}};'],
				'default' => '#0986c0',
				'req' => array(
					'!img_border_type_hover' => ''
				),
				'show' => array(
					'f_border_hover' => 'hover'
				),
			),
			'img_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} img:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!img_border_type_hover' => ''
				],
				'show' => array(
					'f_border_hover' => 'hover'
				),
			),
			'img_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} img:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'show' => array(
					'f_border_hover' => 'hover'
				),
			),
		],
		'styles' => [
			'caption_style' => __pl('caption_style'),
			'link_settings' => __pl('link_settings'),
			'border_style' => __pl('border_style')
		]
	)
);

// Post info
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_info', array(
		'name' => __pl('post_info'),
		'group' => 'other',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements',
		],
		'holder' => '.pagelayer-post-info-container',
		'html' => '<div class="pagelayer-post-info-container pagelayer-post-info-{{layout}}"></div>',
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('post_info_list'),
				'sc' => PAGELAYER_SC_PREFIX.'_post_info_list',
				'item_label' => array(
					'default' => __pl('info_list'),
					'param' => 'type',
				),
				'count' => 1,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('post_info'))),
			),
			'layout' => array(
				'type' => 'select',
				'label' => __pl('layout'),
				'np' => 1,
				'default' => 'vertical',
				'list' => array(
					'horizontal' => __pl('horizontal'), 
					'vertical' => __pl('vertical'), 
				),
			),
			'space_between' => array(
				'type' => 'slider',
				'label' => __pl('list_spacing_label'),
				'default' => 15,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-post-info-vertical .pagelayer-post-info-list-container' => 'margin-right:{{val}}px',
				'{{element}} .pagelayer-post-info-horizontal .pagelayer-post-info-list-container' => 'margin-bottom:{{val}}px']
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'default' => 'left',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-post-info-container' => 'text-align:{{val}}']
			),
		),
		'icon_style' => array(
			'icon_size' => array(
				'type' => 'slider',
				'label' => __pl('font_size'),
				'units' => ['px', 'em', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-post-info-icon span' => 'font-size:{{val}}'],
			),
			'icon_colors' => array(
				'type' => 'radio',
				'label' => __pl('color'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'icon_color_normal' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-info-icon span' => 'color:{{val}}'],
				'show' => ['icon_colors' => 'normal']
			),
			'icon_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-info-icon span:hover' => 'color:{{val}}'],
				'show' => ['icon_colors' => 'hover']
			),
		),
		'text_style' => array(
			'input_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-post-info-list-container a' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'text_colors' => array(
				'type' => 'radio',
				'label' => __pl('color'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'text_color_normal' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-info-label' => 'color:{{val}}',
				'{{element}} .pagelayer-post-info-label a' => 'color:{{val}}'],
				'show' => ['text_colors' => 'normal']
			),
			'text_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-info-label:hover' => 'color:{{val}}',
				'{{element}} .pagelayer-post-info-label:hover a' => 'color:{{val}}'],
				'show' => ['text_colors' => 'hover']
			),
		),
		'terms_style' => array(
			'terms_text_colors' => array(
				'type' => 'radio',
				'label' => __pl('color'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'terms_text_color_normal' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-terms *' => 'color:{{val}} !important'],
				'show' => ['terms_text_colors' => 'normal']
			),
			'terms_text_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-terms *:hover' => 'color:{{val}} !important'],
				'show' => ['terms_text_colors' => 'hover']
			),
			'terms_background_color_normal' => array(
				'type' => 'color',
				'label' => __pl('background_color'),
				'css' => ['{{element}} .pagelayer-terms *' => 'background-color:{{val}}'],
				'show' => ['terms_text_colors' => 'normal']
			),
			'terms_background_color_hover' => array(
				'type' => 'color',
				'label' => __pl('background_color'),
				'css' => ['{{element}} .pagelayer-terms *:hover' => 'background-color:{{val}}'],
				'show' => ['terms_text_colors' => 'hover']
			),
			'terms_text_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-terms *' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;'],	
			),
			'terms_text_margin' => array(
				'type' => 'padding',
				'label' => __pl('margin'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-terms *' => 'margin-top:{{val[0]}}px; margin-right:{{val[1]}}px; margin-bottom:{{val[2]}}px; margin-left:{{val[3]}}px;'],
			),
			'terms_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-terms *' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
		),
		'styles' => array(
			'icon_style' => __pl('icon_style'),
			'text_style' => __pl('text_style'),
			'terms_style' => __pl('terms_style'),
		),
	)
);

// Post info list
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_info_list', array(
		'name' => __pl('post_info_list'),
		'group' => 'other',
		'html' => '<div class="pagelayer-post-info-list-container" if="{{post_info_content}}">
			<a if-ext="{{info_link}}" href="{{link}}" class="pagelayer-post-info-list-link">
				<span class="pagelayer-post-info-icon">
					<span if="{{info_icon_on}}" class="{{info_icon}}"></span>
					<img class="pagelayer-img" if="{{info_avatar}}" src="{{avatar_url}}"></span>
				</span>
				<span if="{{info_before}}" class="pagelayer-post-info-before">{{info_before}}</span>
				<span class="pagelayer-post-info-label pagelayer-{{type}}">{{post_info_content}}</span>
			</a>
		</div>',
		'not_visible' => 1,
		'parent' => [PAGELAYER_SC_PREFIX.'_post_info'],
		'params' => array(
			'type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				'default' => 'author',
				'np' => 1,
				'list' => array(
					'author' => __pl('author'),
					'date' => __pl('date'),
					'time' => __pl('time'),
					'comments' => __pl('comments'),
					'terms' => __pl('Terms'),
					'custom' => __pl('custom'),
				),
			),
			'date_format' => array(
				'type' => 'select',
				'label' => __pl('date_format'),
				'default' => 'default',
				'list' => array(
					'default' => __pl('default'),
					'0' => 'F j, Y',
					'1' => 'Y-m-d',
					'2' => 'm/d/Y',
					'3' => 'd/m/Y',
					'custom' => __pl('custom'),
				),
				'req' => ['type' => 'date'],
			),
			'date_format_custom' => array(
				'type' => 'text',
				'label' => __pl('custom_date_format'),
				'default' => 's - M -Y',
				'req' => ['date_format' => 'custom', 'type' => 'date'],
			),
			'time_format' => array(
				'type' => 'select',
				'label' => __pl('time_format'),
				'default' => 'default',
				'list' => array(
					'default' => __pl('default'),
					'0' => 'g:i a',
					'1' => 'g:i A',
					'2' => 'H:i',
					'custom' => __pl('custom'),
				),
				'req' => ['type' => 'time'],
			),
			'time_format_custom' => array(
				'type' => 'text',
				'label' => __pl('custom_time_format'),
				'default' => 'g:i a',
				'req' => ['time_format' => 'custom', 'type' => 'time'],
			),
			'taxonomy' => array(
				'type' => 'select',
				'label' => __pl('Post_taxonomy'),
				'default' => 'category',
				'list' => pagelayer_tax_list(),
				'req' => ['type' => 'terms'],
			),
			'type_custom' => array(
				'type' => 'text',
				'label' => __pl('custom'),
				'req' => ['type' => 'custom'],
			),
			'info_before' => array(
				'type' => 'text',
				'label' => __pl('before'),
				'np' => 1,
			),
			'info_avatar' => array(
				'type' => 'checkbox',
				'label' => __pl('avatar_style'),
				'req' => ['type' => 'author'],
			),
			'info_avatar_size' => array(
				'type' => 'slider',
				'label' => __pl('obj_size_label'),
				'default' => 22,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-post-info-icon img' => 'height:{{val}}px;width:{{val}}px;border-radius:50%;display:inline-block;'],
				'req' => ['info_avatar' => 'true', 'type' => 'author'],
			),
			'info_link' => array(
				'type' => 'checkbox',
				'label' => __pl('link_settings'),
				'default' => true,
				'req' => ['!type' => 'time'],
			),
			'info_custom_link' => array(
				'type' => 'text',
				'label' => __pl('custom_link'),
				'default' => '#',
				'req' => ['type' => 'custom'],
			),
			'info_icon_on' => array(
				'type' => 'checkbox',
				'label' => __pl('icon'),
				'default' => true,
				'req' => ['info_avatar' => ''],
			),
			'info_icon' => array(
				'type' => 'icon',
				'label' => __pl('icon_list'),
				'default' => 'fas fa-user-circle',
				'req' => ['info_icon_on' => 'true', 'info_avatar' => ''],
			),
		),
	)
);

// Post navigation
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_nav', array(
		'name' => __pl('post_nav'),
		'group' => 'other',
		'html' => '<div class="pagelayer-post-nav-container">
			<div class="pagelayer-prev-post">
				{{prev_link}}
			</div>
			<div class="pagelayer-post-nav-separator"></div>
			<div class="pagelayer-next-post">
				{{next_link}}
			</div>
		</div>',
		'params' => array(
			'in_same_term' => array(
				'type' => 'checkbox',
				'label' => __pl('in_same_term'),
				'np' => 1,
			),
			'taxonomies' => array(
				'type' => 'select',
				'label' => __pl('Post_taxonomy'),
				'default' => 'category',
				'list' => pagelayer_tax_list(),
				'req' => ['in_same_term' => 'true'],
			),
		),
		'nav_label' => array(
			'lables' => array(
				'type' => 'checkbox',
				'label' => __pl('label'),
				'default' => 'true',
			),
			'prev_label' => array(
				'type' => 'text',
				'label' => __pl('prev_label'),
				'np' => 1,
				'default' => __pl('Previous'),
				'req' => ['lables' => 'true'],
			),
			'next_label' => array(
				'type' => 'text',
				'label' => __pl('next_label'),
				'np' => 1,
				'default' => __pl('Next'),
				'req' => ['lables' => 'true'],
			),
			'label_colors' => array(
				'type' => 'radio',
				'label' => __pl('color'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => ['lables' => 'true'],
			),
			'label_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-nav-link' => 'color:{{val}}'],
				'show' => ['label_colors' => 'normal'],
				'req' => ['lables' => 'true'],
			),
			'label_hover_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-nav-link:hover' => 'color:{{val}}'],
				'show' => ['label_colors' => 'hover'],
				'req' => ['lables' => 'true'],
			),
			'label_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-post-nav-link' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'req' => ['lables' => 'true'],
			),
			
		),
		'nav_title' => array(
			'post_title' => array(
				'type' => 'checkbox',
				'label' => __pl('post_title'),
				'default' => 'true',
			),
			'title_colors' => array(
				'type' => 'radio',
				'label' => __pl('color'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => ['post_title' => 'true'],
			),
			'title_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-nav-title' => 'color:{{val}}'],
				'show' => ['title_colors' => 'normal'],
				'req' => ['post_title' => 'true'],
			),
			'title_hover_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-nav-title:hover' => 'color:{{val}}'],
				'show' => ['title_colors' => 'hover'],
				'req' => ['post_title' => 'true'],
			),
			'title_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => ['{{element}} .pagelayer-post-nav-title' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'req' => ['post_title' => 'true'],
			),
		),
		'nav_icon' => array(
			'arrows' => array(
				'type' => 'checkbox',
				'label' => __pl('arrows'),
				'default' => 'true',
			),
			'arrows_list' => array(
				'type' => 'select',
				'label' => __pl('arrows_list'),
				'default' => 'angle',
				'list' => array(
					'angle' => __pl('angle'),
					'arrow' => __pl('Arrow'),
					'angle-double' => __pl('angle_double'),
					'arrow-circle' => __pl('arrow_circle'),
					'arrow-circle-o' => __pl('arrow_circle_O'),
					'chevron' => __pl('chevron'),
					'chevron-circle' => __pl('chevron_circle'),
					'caret' => __pl('caret'),
					'long-arrow' => __pl('long_arrow'),
				),
				'req' => ['arrows' => 'true'],
			),
			'icon_colors' => array(
				'type' => 'radio',
				'label' => __pl('color'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => ['arrows' => 'true'],
			),
			'icon_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-nav-icon' => 'color:{{val}}'],
				'show' => ['icon_colors' => 'normal'],
				'req' => ['arrows' => 'true'],
			),
			'icon_hover_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-post-nav-icon:hover' => 'color:{{val}}'],
				'show' => ['icon_colors' => 'hover'],
				'req' => ['arrows' => 'true'],
			),
			'icon_size' => array(
				'type' => 'slider',
				'label' => __pl('font_size'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-post-nav-icon' => 'font-size:{{val}}px'],
				'req' => ['arrows' => 'true'],
			),
		),
		'nav_sep' => array(
			'disable_sep' => array(
				'type' => 'checkbox',
				'label' => __pl('disable_sep'),
				'css' => ['{{element}} .pagelayer-post-nav-separator' => 'display:none'],
			),
			'sep_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#bdbdbd',
				'css' => ['{{element}} .pagelayer-post-nav-separator' => 'background-color:{{val}}'],
				'req' => ['disable_sep' => '']
			),
			'sep_rotate' => array(
				'type' => 'slider',
				'label' => __pl('Rotate'),
				'default' => 20,
				'max' => 360,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-post-nav-separator' => 'transform: rotate({{val}}deg);'],
				'req' => ['disable_sep' => '']
			),
			'sep_width' => array(
				'type' => 'slider',
				'label' => __pl('width'),
				'default' => 1,
				'max' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-post-nav-separator' => 'width: {{val}}px;'],
				'req' => ['disable_sep' => '']
			),
			
		),
		'styles' => array(
			'nav_label' => __pl('label_style'),
			'nav_title' => __pl('title_style'),
			'nav_icon' => __pl('icon_style'),
			'nav_sep' => __pl('separator_style'),
		),
	)
);

// Post comment
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_post_comment', array(
		'name' => __pl('post_commment'),
		'group' => 'other',
		'html' => '<div class="pagelayer-post-comment-container">
				{{post_comment}}		
		</div>',
		'params' => array(
			'comment_skin' => array(
				'type' => 'select',
				'label' => __pl('skin'),
				'default' => 'theme_comment',
				'list' => array(
					'theme_comment' => __pl('theme_tamplate'), 
				),
			),
			'post_type' => array(
				'type' => 'radio',
				'label' => __pl('post_type'),
				'default' => 'current',
				'list' => array(
					'current' => __pl('current'), 
					'custom' => __pl('custom'), 
				),
			),
			'post_id' => array(
				'type' => 'select',
				'label' => __pl('post_list'),
				'default' => '',
				'list' => pagelayer_get_posts(),
				'req' => ['post_type' => 'custom']
			),
		),
	)
);

// Flipbox
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_flipbox', array(
		'name' => __pl('Flipbox'),
		'group' => 'other',
		'html' =>  '<div class="pagelayer-flipbox-container pagelayer-flipbox-{{back_section}} pagelayer-flipbox-{{flip_animation}} pagelayer-flipbox-direction-{{animation_direction}}">
			<div class="pagelayer-flipbox-overlay"></div>
			<div class="pagelayer-flipbox-main">
				<div class="pagelayer-flipbox-flipper">
					<div class="pagelayer-flipbox-box pagelayer-flipbox-front" style="background-image:url({{front_background}});">
						<div class="pagelayer-flipbox-box-overlay">
							<div class="pagelayer-flipbox-box-inner">
								<div class="pagelayer-flipbox-content">
									<div class="pagelayer-icon-holder pagelayer-service-icon pagelayer-service-{{icon_view}}">
										<i class="{{icon}} {{bg_shape}} {{icon_size}} pagelayer-animation-{{anim_hover}}"></i>						
									</div>
									<div if="{{heading_image}}" class="pagelayer-flipbox-image">
										<img class="pagelayer-img pagelayer-animation-{{anim_hover}}" src="{{func_image}}" title="{{{heading_image-title}}}" alt="{{{heading_image-alt}}}" />
									</div>
									<h2 if={{front_heading}}>{{front_heading}}</h2>
									<p if={{front_content}}>{{front_content}}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="pagelayer-flipbox-box pagelayer-flipbox-back" style="">
						<div class="pagelayer-flipbox-box-overlay">
							<div class="pagelayer-flipbox-box-inner">
								<div class="pagelayer-flipbox-content">
									<h2 if={{back_heading}}>{{back_heading}}</h2>
									<p if={{back_content}}>{{back_content}}</p>
									<a if="{{display_button}}" href="{{{back_button_url}}}" class="pagelayer-service-btn {{back_button_type}} pagelayer-ele-link pagelayer-button {{back_button_size}}">{{back_button_text}}</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>',
		'params' => array(
			'height' => array(
				'type' => 'slider',
				'label' => __pl('block_height'),
				'np' => 1,
				'min' => 100,
				'max' => 1200,
				'default' => 500,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-flipper' => 'height: {{val}}px;'],
			),
			'content_width' => array(
				'type' => 'slider',
				'label' => __pl('content_width'),
				'min' => 50,
				'max' => 100,
				'default' => 100,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-content' => 'width: {{val}}%; margin:0 auto;'],
			),
			'flip_animation' => array(
				'type' => 'select',
				'label' => __pl('animation_styles'),
				'default' => 'flip',
				'list' => array(
					'flip' => __pl('flip'),
					'slide' => __pl('slide'),
					'push' => __pl('push'),
					'zoom-in' => __pl('zoom-in'),
					'zoom-out' => __pl('zoom-out'),
					'fade' => __pl('fade'),
				),
			),
			'animation_direction' => array(
				'type' => 'select',
				'label' => __pl('animation_direction'),
				'default' => 'right',
				'list' => array(
					'up' => __pl('top'),
					'down' => __pl('bottom'),
					'right' => __pl('right'),
					'left' => __pl('left'),
				),
				'req' => array(
					'!flip_animation' => ['fade','zoom-out','zoom-in'],
				),
			),
			'animation_duration' => array(
				'type' => 'spinner',
				'label' => __pl('animation_duration'),
				'np' => 1,
				'default' => 600,
				'min' => 100,
				'step' => 50,
				'max' => 2000,
				'css' => ['{{element}} .pagelayer-flipbox-box' => 'transition: all {{val}}ms ease-in-out !important;
				-webkit-transition: all {{val}}ms ease-in-out !important;']
			),
			'flip_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-flipbox-flipper' => 'border-style: {{val}}'],
				'list' => array(
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				),
			),
			'flip_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-flipbox-flipper' => 'border-color: {{val}};'],
				'req' => array(
					'!flip_border_type' => ''
				),
			),			
			'flip_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-flipper' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => array(
					'!flip_border_type' => ''
				),
			),
			'flip_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'units' => ['px', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-flipper' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
			),			
		),
		'front_section' => array(
			'heading_element' => array(
				'type' => 'radio',
				'label' => __pl('visual_element'),
				'default' => '',
				'list' => array(
					'' => __pl('none'),
					'icon' => __pl('icon'),
					'image' => __pl('image'),
				),
			),
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('icon'),
				'default' => 'fas fa-star',
				'req' => array(
					'heading_element'=>'icon',
				),
			),
			'icon_view' => array(
				'type' => 'select',
				'label' => __pl('iconbox_icon_view'),
				'default' => 'default',
				'list' =>array(
					'default' => __pl('default'),
					'stacked' => __pl('Stacked'),
					'framed' => __pl('Framed'),
				),
				'req' => array(
					'heading_element'=>'icon',
				),
			),
			'bg_shape' => array(
				'type' => 'select',
				'label' => __pl('icon_background_shape'),
				'default' => 'pagelayer-icon-circle',
				'list' => array(
					'' => __pl('icon_shape_none'),
					'pagelayer-icon-circle' => __pl('icon_shape_circle'),
					'pagelayer-icon-square' => __pl('icon_shape_square'),
					'pagelayer-icon-rounded' => __pl('icon_shape_rounded')
				),
				'req' => array(
					'heading_element'=>'icon',
					'!icon_view' => 'default',
				),				
			),
			'icon_color_style' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'position: relative; color: {{val}};',
					'{{element}} pagelayer-flipbox-content i:before' => 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'],
				'default' => '#ffffff',
				'req' => array(
					'heading_element'=>'icon',
				),
			),
			'bg_color' => array(
				'type' => 'color',
				'label' => __pl('icon_background_color'),
				'default' => '#ef9229',
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'background-color: {{val}};'],
				'req' => array(
					'heading_element'=>'icon',
					'!bg_shape' => '',
					'icon_view' => 'stacked',
				),
				'show' => array(
					'icon_hover' => ''
				),
			),
			'icon_background_size' => array(
				'type' => 'spinner',
				'label' => __pl('icon_background_size'),
				'default' => 20,
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'padding: calc(0.5em + {{val}}px);'],
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'screen' => 1,
				'req' => array(
					'heading_element'=>'icon',
				),
			),						
			'icon_size' => array(
				'type' => 'select',
				'label' => __pl('size_label'),
				'default' => 'pagelayer-icon-large',
				'list' => array(
					'pagelayer-icon-mini' => __pl('mini'),
					'pagelayer-icon-small' => __pl('small'),
					'pagelayer-icon-large' => __pl('large'),
					'pagelayer-icon-extra-large' => __pl('extra_large'),
					'pagelayer-icon-double-large' => __pl('double_large'),
					'pagelayer-icon-custom' => __pl('custom'),
				),
				'req' => array(
					'heading_element'=>'icon',
				),
			),
			'icon_size_custom' => array(
				'type' => 'spinner',
				'label' => __pl('icon_custom_size'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 26,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'font-size: {{val}}px'],
				'req' => array(
					'icon_size' => 'pagelayer-icon-custom',
					'heading_element' => 'icon'
				),
			),
			'icon_rotate' => array(
				'type' => 'spinner',
				'label' => __pl('icon_rotate'),
				'default' => 0,
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'transform: rotate({{val}}deg)'],
				'min' => 0,
				'max' => 360,
				'step' => 1,
				'screen' => 1,
				'req' => array(
					'heading_element'=>'icon',
				),
			),			
			'icon_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'req' => array(
					'heading_element'=>'icon',
				),
			),
			'icon_border_color' => array(
				'type' => 'color',
				'label' => __pl('icon_border_color'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'border-color: {{val}};'],
				'req' => array(
					'!icon_border_type' => '',
					'heading_element' => 'icon'
				),
			),
			'icon_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!icon_border_type' => '',
					'heading_element' => 'icon'
				],
			),
			'icon_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-content i' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type' => '',
					'heading_element' => 'icon'
				),
			),
			'heading_image'=> array(
				'type' => 'image',
				'label' => __pl('image'),
				'default' => PAGELAYER_URL.'/images/default-image.png',
				'req' => array(
					'heading_element' => 'image',
				),
			),
			'heading_image_size' => array(
				'type' => 'radio',
				'label' => __pl('image_size'),
				'default' => 'full',
				'list' => array(
					'full' => __pl('full'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom'),
				),
				'req' => array(
					'heading_element' => 'image',
				),
			),
			'heading_image_custom_size' => array(
				'type' => 'slider',
				'label' => __pl('img_custom_size'),
				'min' => 0,
				'max' => 100,
				'screen' => 1,
				'default' => 50,
				'css' => ['{{element}} .pagelayer-flipbox-image img' => 'width:{{val}}%; height: auto;'],
				'req' => array(
					'heading_element' => 'image',
					'heading_image_size' => 'custom',
				)
			),
			'heading_image_spacing' => array(
				'type' => 'slider',
				'label' => __pl('spacing'),
				'min' => 0,
				'max' => 100,
				'screen' => 1,
				'default' => 20,
				'css' => ['{{element}} .pagelayer-flipbox-image img' => 'margin-bottom:{{val}}px;'],
				'req' => array(
					'heading_element' => 'image',
				)
			),
			'front-text-align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-flipbox-front .pagelayer-flipbox-box-overlay' => 'text-align:{{val}} !important;'],
			),
			'front_heading' => array(
				'type' => 'text',
				'default' => 'Flipbox Heading',
				'label' => __pl('heading_name'),
				'np' => 1,
			),
			'heading_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-flipbox-front .pagelayer-flipbox-content h2' => 'color:{{val}};'],
			),
			'heading_typography' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => 'Poppins,40,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-flipbox-front .pagelayer-flipbox-content h2' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'front_content' => array(
				'type' => 'textarea',
				'label' => __pl('content'),
				'np' => 1,
				'default' => 'Flipbox content comes here such as It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
			),
			'front_content_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-flipbox-front .pagelayer-flipbox-content p' => 'color:{{val}};'],
			),
			'front_content_typography' => array(
				'type' => 'typography',
				'label' => __pl('text_style'),
				'default' => ',16,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-flipbox-front .pagelayer-flipbox-content p' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'front_shadow' => array(
				'type' => 'box_shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} .pagelayer-flipbox-front' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}};'],
			),
			'front_background_type' => array(
				'type' => 'radio',
				'label' => __pl('background_type'),
				'default' => '',
				'list' => array(
					'color' => __pl('color'),
					'gradient' => __pl('gradient'),
					'image' => __pl('image'),
				),
			),
			'front_background_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-flipbox-front' => 'background-color:{{val}};'],
				'req' => array(
					'front_background_type' => 'color',
				),
			),
			'front_background_gradient' => array(
				'type' => 'gradient',
				'label' => __pl('background_gradient'),
				'default' => '150,#f12711,40,#f5af19,60,#f5af19,100',
				'css' => ['{{element}} .pagelayer-flipbox-front' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%) !important;'],
				'req' => array(
					'front_background_type' => 'gradient',
				),
			),
			'front_background_image' => array(
				'type' => 'image',
				'label' => __pl('image'),
				'np' => 1,
				'default' => PAGELAYER_URL.'/images/default-image.png',
				'css' => ['{{element}} .pagelayer-flipbox-front' => 'background-image:url("{{{front_background_image-url}}}") !important;'],
				'req' => array(
					'front_background_type' => 'image',
				),
			),
			'front_background_attachment' => array(
				'type' => 'select',
				'label' => __pl('background_attachment'),
				'list' => array(
					'' => __pl('default'),
					'scroll' => __pl('scroll'),
					'fixed' => __pl('fixed')
				),
				'css' => ['{{element}} .pagelayer-flipbox-front' => 'background-attachment: {{val}}'],
				'req' => array(
					'front_background_type' => 'image',
				),
			),
			'front_background_posx' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_posx'),
				'list' => array(
					'' => __pl('default'),
					'center' => __pl('center'),
					'left' => __pl('left'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-flipbox-front' => 'background-position-x: {{val}};'],
				'req' => array(
					'front_background_type' => 'image',
				),
			),
			'front_background_posy' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_posy'),
				'list' => array(
					'' => __pl('default'),
					'center' => __pl('center'),
					'top' => __pl('top'),
					'bottom' => __pl('bottom')
				),
				'css' =>  ['{{element}} .pagelayer-flipbox-front' => 'background-position-y: {{val}};'],
				'req' => array(
					'front_background_type' => 'image',
				),
			),
			'front_background_repeat' => array(
				'type' => 'select',
				'label' => __pl('repeat'),
				'css' =>  ['{{element}} .pagelayer-flipbox-front' => 'background-repeat: {{val}};'],
				'list' => array(
					'' => __pl('default'),
					'repeat' => __pl('repeat'),
					'no-repeat' => __pl('no-repeat'),
					'repeat-x' => __pl('repeat-x'),
					'repeat-y' => __pl('repeat-y'),
				),
				'req' => array(
					'front_background_type' => 'image',
				),
			),
			'front_background_size' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_size'),
				'css' =>  ['{{element}} .pagelayer-flipbox-front' => 'background-size: {{val}};'],
				'list' => array(
					'' => __pl('default'),
					'cover' => __pl('cover'),
					'contain' => __pl('contain')
				),
				'req' => array(
					'front_background_type' => 'image',
				),
			),
			'front_section_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'screen' => 1,
				'default' => '100,100,100,100',
				'css' => ['{{element}} .pagelayer-flipbox-front .pagelayer-flipbox-box-overlay' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
			),
		),		
		'back_section' => array(
			'back_section' => array(
				'type' => 'checkbox',
				'label' => __pl('back_side'),
			),
			'back-text-align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-flipbox-box-overlay' => 'text-align:{{val}} !important;'],
			),
			'back_heading' => array(
				'type' => 'text',
				'label' => __pl('title'),
				'np' => 1,
				'default' => 'Flipbox Back Heading',
			),
			'back_heading_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-flipbox-content h2' => 'color:{{val}};'],
			),
			'back_heading_typography' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => 'Poppins,40,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-flipbox-content h2' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'back_content' => array(
				'type' => 'textarea',
				'label' => __pl('content'),
				'np' => 1,
				'default' => 'Flipbox content comes here such as It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
			),
			'back_content_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'default' => '#3c3f40',
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-flipbox-content p' => 'color:{{val}};'],
			),
			'back_content_typography' => array(
				'type' => 'typography',
				'label' => __pl('text_style'),
				'default' => ',16,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-flipbox-content p' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'back_shadow' => array(
				'type' => 'box_shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} .pagelayer-flipbox-back' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}};'],
			),
			'display_button' => array(
				'type' => 'checkbox',
				'label' => __pl('button'),
			),
			'back_button_url' => array(
				'type' => 'link',
				'label' => __pl('btn_url_label'),
				'selector' => '.pagelayer-service-btn',
				'req' => array(
					'!display_button' => '',
				),
			),
			'back_button_text' => array(
				'type' => 'text',
				'label' => __pl('button_text_label'),
				'default' => 'Click Here!',	
				'req' => array(
					'!display_button' => '',
				),
			),
			'back_button_typography' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => ',20,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-service-btn' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'req' => array(
					'!display_button' => '',
				),
			),
			'back_button_type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				'default' => 'pagelayer-btn-default',
				'list' => array(
					'pagelayer-btn-default' => __pl('btn_type_default'),
					'pagelayer-btn-primary' => __pl('btn_type_primary'),
					'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
					'pagelayer-btn-success' => __pl('btn_type_success'),
					'pagelayer-btn-info' => __pl('btn_type_info'),
					'pagelayer-btn-warning' => __pl('btn_type_warning'),
					'pagelayer-btn-danger' => __pl('btn_type_danger'),
					'pagelayer-btn-dark' => __pl('btn_type_dark'),
					'pagelayer-btn-light' => __pl('btn_type_light'),
					'pagelayer-btn-link' => __pl('btn_type_link'),
					'pagelayer-btn-custom' => __pl('btn_type_custom')
				),
				'req' => array(
					'!display_button' => '',
				),
			),
			'back_button_size' => array(
				'type' => 'select',
				'label' => __pl('button_size'),
				'default' => 'pagelayer-btn-mini',
				'list' => array(
					'pagelayer-btn-mini' => __pl('mini'),
					'pagelayer-btn-small' => __pl('small'),
					'pagelayer-btn-large' => __pl('large'),
					'pagelayer-btn-extra-large' => __pl('extra_large'),
					'pagelayer-btn-double-large' => __pl('double_large'),
					'pagelayer-btn-custom' => __pl('custom')
				),
				'req' => array(
					'!display_button' => '',
				),
			),	
			'back_button_custom_size' => array(
				'type' => 'spinner',
				'label' => __pl('btn_custom_size'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-service-btn' => 'padding: calc({{val}}px / 2) {{val}}px;'],
				'req' => array(
					'back_button_size' => 'pagelayer-btn-custom',
				),
			),			
			'back_btn_spacing' => array(
				'type' => 'slider',
				'label' => __pl('spacing'),
				'min' => 0,
				'max' => 200,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-btn' => 'margin-top: {{val}}px;'],
				'req' => array(
					'!display_button' => '',
				),
			),
			'back_btn_state' => array(
				'type' => 'radio',
				'label' => __pl('button_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => array(
					'back_button_type' => 'pagelayer-btn-custom',
					'!display_button' => '',
				),
			),
			'back_button_color' => array(
				'type' => 'color',
				'label' => __pl('button_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-service-btn' => 'color:{{val}};'],
				'show' => array(
					'back_btn_state' => 'normal',
				),
			),
			'back_button_bg_color' => array(
				'type' => 'color',
				'label' => __pl('button_bg_color'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-service-btn' => 'background-color:{{val}};'],
				'show' => array(
					'back_btn_state' => 'normal',
				),
			),
			'back_btn_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('btn_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-btn' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => array(
					'back_btn_state' => 'hover',
				),
			),
			'back_button_color_hover' => array(
				'type' => 'color',
				'label' => __pl('button_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-service-btn:hover' => 'color:{{val}} !important;'],
				'show' => array(					
					'back_btn_state' => 'hover',
				),
			),
			'back_button_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('button_bg_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-service-btn:hover' => 'background-color:{{val}} !important;'],
				'show' => array(
					'back_btn_state' => 'hover'
				),
			),			
			'back_background_type' => array(
				'type' => 'radio',
				'label' => __pl('background_type'),
				'default' => '',
				'list' => array(
					'color' => __pl('color'),
					'gradient' => __pl('gradient'),
					'image' => __pl('image'),
				),
			),
			'back_background_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-flipbox-back' => 'background-color:{{val}};'],
				'req' => array(
					'back_background_type' => 'color',
				),
			),
			'back_background_gradient' => array(
				'type' => 'gradient',
				'label' => __pl('background_gradient'),
				'default' => '150,#1488CC,40,#2B32B2,60,#2B32B2,100',
				'css' => ['{{element}} .pagelayer-flipbox-back' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%) !important;'],
				'req' => array(
					'back_background_type' => 'gradient',
				),
			),
			'back_background_image' => array(
				'type' => 'image',
				'label' => __pl('image'),
				'default' => PAGELAYER_URL.'/images/default-image.png',
				'css' => ['{{element}} .pagelayer-flipbox-back' => 'background-image:url("{{{back_background_image-url}}}") !important;'],
				'req' => array(
					'back_background_type' => 'image',
				),
			),
			'back_background_attachment' => array(
				'type' => 'select',
				'label' => __pl('background_attachment'),
				'list' => array(
					'' => __pl('default'),
					'scroll' => __pl('scroll'),
					'fixed' => __pl('fixed')
				),
				'css' => ['{{element}} .pagelayer-flipbox-back' => 'background-attachment: {{val}}'],
				'req' => array(
					'back_background_type' => 'image',
				),
			),
			'back_background_posx' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_posx'),
				'list' => array(
					'' => __pl('default'),
					'center' => __pl('center'),
					'left' => __pl('left'),
					'right' => __pl('right')
				),
				'css' => ['{{element}} .pagelayer-flipbox-back' => 'background-position-x: {{val}};'],
				'req' => array(
					'back_background_type' => 'image',
				),
			),
			'back_background_posy' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_posy'),
				'list' => array(
					'' => __pl('default'),
					'center' => __pl('center'),
					'top' => __pl('top'),
					'bottom' => __pl('bottom')
				),
				'css' =>  ['{{element}} .pagelayer-flipbox-back' => 'background-position-y: {{val}};'],
				'req' => array(
					'back_background_type' => 'image',
				),
			),
			'back_background_repeat' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_repeat'),
				'css' =>  ['{{element}} .pagelayer-flipbox-back' => 'background-repeat: {{val}};'],
				'list' => array(
					'' => __pl('default'),
					'repeat' => __pl('repeat'),
					'no-repeat' => __pl('no-repeat'),
					'repeat-x' => __pl('repeat-x'),
					'repeat-y' => __pl('repeat-y'),
				),
				'req' => array(
					'back_background_type' => 'image',
				),
			),
			'back_background_size' => array(
				'type' => 'select',
				'label' => __pl('ele_bg_size'),
				'css' =>  ['{{element}} .pagelayer-flipbox-back' => 'background-size: {{val}};'],
				'list' => array(
					'' => __pl('default'),
					'cover' => __pl('cover'),
					'contain' => __pl('contain')
				),
				'req' => array(
					'back_background_type' => 'image',
				),
			),
			'back_section_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'default' => '100,100,100,100',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-flipbox-back .pagelayer-flipbox-box-overlay' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
			),			
		),
		'styles' => [
			'front_section' => __pl('front_section'),
			'back_section' => __pl('back_section'),						
		]
	)
);

// Countdown Timer
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_countdown', array(
		'name' => __pl('countdown_timer'),
		'group' => 'other',
		'html' => '<div class="pagelayer-countdown-container" pagelayer-expiry-date={{date}} pagelayer-time-type={{time_zone}}>
			<div class="pagelayer-countdown-expired">
				<p if={{expired_text}}>{{expired_text}}</p>
			</div>
			<div class="pagelayer-countdown-counter">
				<div if={{days}} class="pagelayer-countdown-days pagelayer-countdown-item pagelayer-countdown-{{display}}">
					<div class="pagelayer-days-count pagelayer-countdown-count"></div>
					<div if={{show_label}} class="pagelayer-countdown-name">
						<span if={{days_label_text}}>{{days_label_text}}</span>
					</div>
				</div>
				<div if={{hours}} class="pagelayer-countdown-hours pagelayer-countdown-item pagelayer-countdown-{{display}}">
					<div class="pagelayer-hours-count pagelayer-countdown-count"></div>
					<div if={{show_label}} class="pagelayer-countdown-name">
						<span if={{hours_label_text}}>{{hours_label_text}}</span>
					</div>
				</div>
				<div if={{minutes}} class="pagelayer-countdown-minutes pagelayer-countdown-item pagelayer-countdown-{{display}}">
					<div class="pagelayer-minutes-count pagelayer-countdown-count"></div>
					<div if={{show_label}} class="pagelayer-countdown-name">
						<span if={{minutes_label_text}} >{{minutes_label_text}}</span>
					</div>
				</div>
				<div if={{seconds}} class="pagelayer-countdown-seconds pagelayer-countdown-item pagelayer-countdown-{{display}}">
					<div class="pagelayer-seconds-count pagelayer-countdown-count"></div>
					<div if={{show_label}} class="pagelayer-countdown-name">
						<span if={{seconds_label_text}} >{{seconds_label_text}}</span>
					</div>
				</div>
			</div>
		</div>',
		'params' => array(
			'date' => array(
				'type' => 'datetime',
				'displayMode' => 'datetime', // date | datetime (default)
				'returnMode' => 'YYYY-MM-DD H:m:s', // mysql format uses here (default: Y-m-d H:i:s )
				'label' => __pl('date_picker_label'),
				'np' => 1,
				'default' => '',
			),
			'time_zone' => array(
				'type' => 'select',
				'label' => __pl('time_zone'),
				'default' => 'server',
				'list' => array(
					'server' => __pl('server_time'),
					'local' => __pl('user_local')
				),
			),			
			'number_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'np' => 1,
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-countdown-count' => 'color:{{val}}'],
			),
			'number_style' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => ',50,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-countdown-count' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],					
			),
			'number_spacing' => array(
				'type' => 'padding',
				'label' => __pl('spacing'),
				'units' => ['px', 'em', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-count' => 'margin-top: {{val[0]}}; margin-right: {{val[1]}}; margin-bottom: {{val[2]}}; margin-left: {{val[3]}};'],
			),		
			'days' => array(
				'type' => 'checkbox',
				'label' => __pl('days'),
				'np' => 1,
				'default' => 'true',
			),
			'hours' => array(
				'type' => 'checkbox',
				'label' => __pl('hours'),
				'np' => 1,
				'default' => 'true',
			),
			'minutes' => array(
				'type' => 'checkbox',
				'label' => __pl('minutes'),
				'np' => 1,
				'default' => 'true',
			),
			'seconds' => array(
				'type' => 'checkbox',
				'label' => __pl('seconds'),
				'np' => 1,
				'default' => 'true',
			),
		),
		'expired_text' =>[
			'display_expired_text' => array(
				'type' => 'checkbox',
				'label' => __pl('expired_text'),
			),
			'expired_text'  => array(
				'type' => 'text',
				'label' => __pl('text'),
				'default' => 'Countdown Timer Expired',
			),
			'expired_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-countdown-expired p' => 'color:{{val}};'],				
			),
			'expired_background_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'css' => ['{{element}} .pagelayer-countdown-expired' => 'background-color:{{val}};'],				
			),
			'expired_style' => array(
				'type' => 'typography',
				'label' => __pl('expired_style'),
				'default' => ',50,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-countdown-expired p' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],				
			),
			'expired_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'units' => ['px', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-expired' => 'padding: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
			),
			'expired_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
				'css' => ['{{element}} .pagelayer-countdown-expired' => 'text-align:{{val}};'],
			),
		],
		'text_style' =>[
			'show_label' => array(
				'type' => 'checkbox',
				'label' => __pl('show_title'),
				'np' => 1,
				'default' => 'true',
			),
			'custom_label_text'  => array(
				'type' => 'checkbox',
				'label' => __pl('custom_label_text'),
				'show' => array(
					'show_label' => 'true',
				),
			),
			'days_label_text' => array(
				'type' => 'text',
				'label' => __pl('days'),
				'default' => __pl('days'),				
				'show' => array(
					'!custom_label_text' => '',
					'!days' => '',
				),
			),
			'hours_label_text' => array(
				'type' => 'text',
				'label' => __pl('hours'),
				'default' => __pl('hours'),
				'show' => array(
					'!custom_label_text' => '',
					'!hours' => '',
				),
			),
			'minutes_label_text' => array(
				'type' => 'text',
				'label' => __pl('minutes'),
				'default' => __pl('minutes'),
				'show' => array(
					'!custom_label_text' => '',
					'!minutes' => '',
				),
			),
			'seconds_label_text' => array(
				'type' => 'text',
				'label' => __pl('seconds'),
				'default' => __pl('seconds'),
				'show' => array(
					'!custom_label_text' => '',
					'!seconds' => '',
				),
			),
			'font_color' => array(
				'type' => 'color',
				'label' => __pl('text_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-countdown-name span' => 'color:{{val}}'],
				'show' => array(
					'show_label' => 'true',
				),
			),
			'cd_text_style' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => ',18,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-countdown-name' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'show' => array(
					'show_label' => 'true',
				),				
			),
		],
		'block_styles' =>[
			'display' => array(
				'type' => 'select',
				'label' => __pl('display'),
				'default' => 'block',
				'list' => [
					'block' => __pl('block'),
					'inline' => __pl('inline'),			
				],				
			),
			'block_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '#2b1661',
				'css' => ['{{element}} .pagelayer-countdown-item' => 'background-color:{{val}}'],
			),
			'blocks_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'units' => ['px', 'em', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-item' => 'padding-top: {{val[0]}}; padding-right: {{val[1]}}; padding-bottom: {{val[2]}}; padding-left: {{val[3]}}'],
			),
			'blocks_space' => array(
				'type' => 'padding',
				'label' => __pl('block_space'),
				'units' => ['px', 'em'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-item' => 'margin-top: {{val[0]}}; margin-right: {{val[1]}}; margin-bottom: {{val[2]}}; margin-left: {{val[3]}}'],
			),
			'cd_border_state' => array(
				'type' => 'radio',
				'label' => __pl(''),
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'cd_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-countdown-item' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'cd_border_state' => ''
				),
			),
			'cd_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-countdown-item' => 'border-color: {{val}};'],
				'req' => array(
					'!cd_border_type' => ''
				),
				'show' => array(
					'cd_border_state' => ''
				),
			),			
			'cd_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-item' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!cd_border_type' => ''
				],
				'show' => array(
					'cd_border_state' => ''
				),
			),
			'cd_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'units' => ['px', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-item' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
				'show' => array(
					'cd_border_state' => ''
				),
			),
			'cd_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-countdown-item:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'cd_border_state' => 'hover'
				),
			),
			'cd_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'css' => ['{{element}} .pagelayer-countdown-item:hover' => 'border-color: {{val}};'],
				'default' => '#0986c0',
				'req' => array(
					'!cd_border_type_hover' => ''
				),
				'show' => array(
					'cd_border_state' => 'hover'
				),
			),
			'cd_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-item:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!cd_border_type_hover' => ''
				],
				'show' => array(
					'cd_border_state' => 'hover'
				),
			),
			'cd_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'units' => ['px', '%'],
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-countdown-item:hover' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};-moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],				
				'show' => array(
					'cd_border_state' => 'hover'
				),
			),
			'cd_shadow' => array(
				'type' => 'box_shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} .pagelayer-countdown-item' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}};'],
				'show' => array(
					'cd_border_state' => '',
				),				
			), 
			'cd_shadow_hover' => array(
				'type' => 'box_shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} .pagelayer-countdown-item:hover' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[4]}}px {{val[3]}} {{val[5]}};'],
				'show' => array(
					'cd_border_state' => 'hover'
				),			
			), 
		],
		'styles' => [	
			'text_style' => __pl('title_style'),
			'expired_text' => __pl('expired_text'),
			'block_styles' => __pl('block_styles'),			
		],
	)
);

// Button Group
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_btn_grp', array(
		'name' => __pl('btn_grp'),
		'group' => 'button',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('buttons'),
				'sc' => PAGELAYER_SC_PREFIX.'_btn',
				'item_label' => array(
					'default' => __pl('button'),
					'param' => 'text'
				),
				'count' => 2,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('button_name'))),
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'default' => 'center',
				'screen' => 1,
				'css' => [
					'{{element}}' => 'text-align: {{val}}',
					'{{element}} .pagelayer-btn' => 'text-align: {{val}}'
				],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			),
			'group_layout' => array(
				'type' => 'radio',
				'label' => __pl('layout'),
				'default' => 'horizontal',
				'css' => ['{{element}} > div' => 'display: inline-block;'],
				'list' => array(
					'horizontal' => __pl('horizontal'),
					'' => __pl('vertical')
				)
			),
			'hindent' => array(
				'type' => 'spinner',
				'label' => __pl('space_between'),
				'np' => 1,
				'default' => '5',
				'css' => ['{{element}} .pagelayer-btn' => 'padding-left: {{val}}px; padding-right: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 50,
				'default' => 3,
				'screen' => 1,
				'req' => ['group_layout' => 'horizontal']
			),
			'vindent' => array(
				'type' => 'spinner',
				'label' => __pl('space_between'),
				'np' => 1,
				'default' => '5',
				'css' => ['{{element}} .pagelayer-btn' => 'padding-top: {{val}}px; padding-bottom: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 50,
				'default' => 3,
				'screen' => 1,
				'req' => ['group_layout' => '']
			)
		)
	)
);
	
// Testimonial Slider
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_testimonial_slider', array(
	'name' => __pl('testimonial_slider'),
	'group' => 'other',
	'has_group' => [
		'section' => 'params',
		'prop' => 'elements'
	],
	'icon' => 'pli pli-commenting-o',
	'prevent_inside' => ['pl_slides'],
	'child_selector' => '>.pagelayer-owl-stage-outer>.pagelayer-owl-stage>.pagelayer-owl-item', // Make it very specifc
	'holder' => '.pagelayer-testimonials-holder',
	'html' => '<div class="pagelayer-testimonials-holder pagelayer-owl-holder pagelayer-owl-carousel pagelayer-owl-theme"></div>',
	'params' => array(
		'elements' => array(
			'type' => 'group',
			'label' => __pl('testimonial'),
			'sc' => PAGELAYER_SC_PREFIX.'_testimonial',
			'item_label' => array(
				'default' => __pl('testimonial'),
				'param' => 'cite'
			),
			'count' => 3,
			'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('testimonial')))
		),
	),
	'slider_options' => $pagelayer->slider_options,
	'arrow_styles' => $pagelayer->slider_arrow_styles,
	'pager_styles' => $pagelayer->slider_pager_styles,
	'styles' => [
		'slider_options' => __pl('slider_options'),
		'arrow_styles' => __pl('arrow_styles'),
		'pager_styles' => __pl('pager_styles'),
	]
));



// Pricing Table
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_pricing', array(
		'name' => __pl('pricing_table'),
		'group' => 'other',
		'has_group' => [
			'section' => 'feature_style', 
			'prop' => 'elements'
		],
		'holder' => '.pagelayer-pricing-ul',
		'html' =>  '<div class="pagelayer-pricing-details">
			<div if="{{ribbon_text}}" class="pagelayer-pricing-ribbon-container">
				<div class="pagelayer-pricing-ribbon">
					{{ribbon_text}}
				</div>
			</div>			
			<h3 if="{{plan_title}}" class="pagelayer-pricing-type">{{plan_title}}</h3>
			<h4 if="{{plan_sub_title}}" class="pagelayer-pricing-sub-title">{{plan_sub_title}}</h4>			
		</div>
		<div class="pagelayer-pricing-rate-section">
			<h4 if="{{original_price}}" class="pagelayer-pricing-price pagelayer-pricing-original">
				<span class="pagelayer-pricing-rate">
					<span if="{{currency}}">{{currency}}</span><span class="pagelayer-pricing-orig-amt">{{original_price}}</span>
				</span>
			</h4>
			<h2 class="pagelayer-pricing-price">
				<span if="{{currency}}" class="pagelayer-pricing-currency pagelayer-pricing-currency-{{currency_position}}">{{currency}}</span><span if="{{price}}" class="pagelayer-pricing-rate pagelayer-pricing-amt">{{price}}</span>
			</h2>
			<p if="{{period}}" class="pagelayer-pricing-duration">{{period}}</p>
		</div>		
		<div class="pagelayer-pricing-features">
			<ul class="pagelayer-pricing-ul"></ul>			
			<a if="{{price_button}}" href="{{{button_url}}}" class="pagelayer-pricing-btn {{button_type}} pagelayer-ele-link pagelayer-button {{button_size}}">{{button_text}}</a>
			<p if="{{additional_info}}" class="pagelayer-pricing-additional">{{additional_info}}</p>
		</div>',
		'params' => array(
			'plan_title' => array(
				'type' => 'text',
				'label' => __pl('plan_type'),
				'np' => 1,
				'default' => 'Standard',
				'edit' => '.pagelayer-pricing-type',
			),
			'title_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-type' => 'color:{{val}};'],
			),			
			'title_size' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => 'Poppins,20,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-type' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'content_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'screen' => 1,
				'css' => 'text-align: {{val}};',
				'list' => array(
					'left' => __pl('left'),
					'' => __pl('center'),
					'right' => __pl('right'),
				)
			),
		),
		//styles		
		'header_style' => [
			'header_background_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '#d55400',
				'css' => ['{{element}} .pagelayer-pricing-details' => 'background-color:{{val}} !important;'],
			),
			'header_padding' => array(
				'label' => __pl('padding'),
				'type' => 'padding',
				'screen' => 1,
				'default' => '10,10,10,10',
				'css' =>  ['{{element}} .pagelayer-pricing-details' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
			),
			'header_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-pricing-details' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
			),
			'header_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#e5e5e8',
				'css' => ['{{element}} .pagelayer-pricing-details' => 'border-color: {{val}};'],
				'req' => ['!header_border_type' => '']
			),
			'header_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'default' => '0,0,0,0',
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-pricing-details' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => ['!header_border_type' => '']
			),
			'header_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-pricing-details' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => ['!header_border_type' => '']
			),
		],
		'subtitle_style' => [
			'plan_sub_title' => array(
				'type' => 'text',
				'label' => __pl('subtitle'),
				'default' => 'For beginners',
				'edit' => '.pagelayer-pricing-sub-title',
			),
			'subtitle_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-sub-title' => 'color:{{val}}'],
			),
			'subtitle_size' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => 'Poppins,18,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-sub-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],				
			),
		],
		'price_style' => [
			'price' => array(
				'type' => 'text',
				'label' => __pl('plan_price'),
				'np' => 1,
				'default' => '49',
				'edit' => '.pagelayer-pricing-amt',
			),
			'sale' => array(
				'type' => 'checkbox',
				'label' => __pl('sale'),
			),
			'original_price' => array(
				'type' => 'text',
				'label' => __pl('old_price'),
				'default' => '100',
				'edit' => '.pagelayer-pricing-orig-amt',
				'keep_prop' => 1, // Prevent to remove props in gutenburg
				'css' => ['{{element}} .pagelayer-pricing-original '=> 'display:inline-block;'],
				'req' => array(
					'sale' => 'true'
				)
			),
			'price_size' => array(
				'label' => __pl('price_size'),
				'type' => 'typography',
				'default' => 'Poppins,45,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-price .pagelayer-pricing-rate' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'price_sale_size' => array(
				'label' => __pl('old_price_size'),
				'type' => 'typography',
				'default' => 'Poppins,35,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-original .pagelayer-pricing-rate' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'req' => array(
					'sale' => 'true'
				)
			),
			'price_state' => array(
				'type' => 'radio',
				'label' => __pl('price_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'price_line_height' => array(
				'label' => __pl('price_line_height'),
				'type' => 'slider',
				'min' => 0,
				'max' => 500,
				'default' => 30,
				'css' => ['{{element}} .pagelayer-pricing-price .pagelayer-pricing-currency' => 'line-height:{{val}}%;'],
			),
			'price_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-price .pagelayer-pricing-rate' => 'color:{{val}}'],
				'show' => ['price_state' => 'normal'],
			),
			'old_price_color' => array(
				'type' => 'color',
				'label' => __pl('old_price_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-price.pagelayer-pricing-original .pagelayer-pricing-rate' => 'color:{{val}}'],
				'show' => ['price_state' => 'normal'],
				'req' => ['sale' => 'true'],				
			),
			'old_price_line_color' => array(
				'type' => 'color',
				'label' => __pl('old_price_line_color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-pricing-price.pagelayer-pricing-original' => 'color:{{val}}'],
				'show' => ['price_state' => 'normal'],
				'req' => ['sale' => 'true'],
			),
			'price_background_color' => array(
				'type' => 'color',
				'label' => __pl('background_color'),
				'default' => '#e98b2b',
				'css' => ['{{element}} .pagelayer-pricing-rate-section' => 'background-color:{{val}}'],
				'show' => ['price_state' => 'normal'],
			),
			'price_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-pricing-price .pagelayer-pricing-rate:hover' => 'color:{{val}}'],
				'show' => ['price_state' => 'hover'],
			),
			'old_price_color_hover' => array(
				'type' => 'color',
				'label' => __pl('old_price_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-price.pagelayer-pricing-original .pagelayer-pricing-rate:hover' => 'color:{{val}}'],
				'show' => ['price_state' => 'hover'],
				'req' => ['sale' => 'true'],
			),
			'old_price_line_color_hover' => array(
				'type' => 'color',
				'label' => __pl('old_price_line_color'),
				'css' => ['{{element}} .pagelayer-pricing-price.pagelayer-pricing-original:hover' => 'color:{{val}}'],
				'show' => ['price_state' => 'hover'],
				'req' => ['sale' => 'true'],
			),
			'price_background_color_hover' => array(
				'type' => 'color',
				'label' => __pl('background_color'),
				'css' => ['{{element}} .pagelayer-pricing-rate-section:hover' => 'background-color:{{val}}'],
				'show' => ['price_state' => 'hover'],
			),
			'price_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-pricing-rate-section' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
			),
			'price_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-pricing-rate-section' => 'border-color: {{val}};'],
				'req' => ['!price_border_type' => '']
			),
			'price_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'css' =>  ['{{element}} .pagelayer-pricing-rate-section' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => ['!price_border_type' => '']
			),
			'price_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'css' =>  ['{{element}} .pagelayer-pricing-rate-section' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => ['!price_border_type' => '']
			),
			'price_margin' => array(
				'type' => 'padding',
				'label' => __pl('margin'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-pricing-rate-section' => 'margin: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
			),
			'price_padding' => array(
				'type' => 'padding',
				'label' => __pl('padding'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-pricing-rate-section' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
			),
		],
		'currency_style' => [
			'currency' => array(
				'type' => 'text',
				'label' => __pl('currency'),
				'np' => 1,
				'default' => '$',
				'edit' => '.pagelayer-pricing-currency',
			),
			'currency_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-price .pagelayer-pricing-currency' => 'color:{{val}}'],
			),
			'currency_size' => array(
				'label' => __pl('currency_size'),
				'type' => 'typography',
				'default' => 'Poppins,35,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-price .pagelayer-pricing-currency' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],				
			),
			'currency_position' => array(
				'type' => 'radio',
				'label' => __pl('position'),
				'default' => 'top',
				'list' => array(
					'top' => __pl('top'),
					'middle' => __pl('middle'),
					'bottom' => __pl('bottom'),
				),
			),
		],
		'period_style' => [
			'period_inline' => array(
				'type' => 'checkbox',
				'label' => __pl('inline'),
				'css' => ['{{element}} .pagelayer-pricing-duration' => 'display:inline-block;'],
			),
			'period' => array(
				'type' => 'text',
				'label' => __pl('period'),
				'np' => 1,
				'default' => 'Per Month',
				'edit' => '.pagelayer-pricing-duration',
			),
			'period_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-duration' => 'color:{{val}}'],
			),
			'period_size' => array(
				'type' => 'typography',
				'label' => __pl('heading_typo'),
				'default' => ',16,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-duration' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'feature_style' => [
			'elements' => array(
				'type' => 'group',
				'label' => __pl('features_item'),
				'sc' => PAGELAYER_SC_PREFIX.'_list_item',
				'item_label' => array(
					'default' => __pl('Features Item'),
					'param' => 'item'
				),
				'count' => 3,
				'text' =>strtr(__pl('add_new_item'), array('%name%' => __pl('Feature'))),
			),
			'features_background_color' => array(
				'type' => 'color',
				'label' => __pl('background_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-features' => 'background-color:{{val}}'],
			),
			'features_text_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-pricing-ul li span' => 'color:{{val}}'],
			),
			'features_text_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => ',18,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-ul li span' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],	
			),
			'pri_features_spacing' => array(
				'label' => __pl('space_between'),
				'type' => 'slider',
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-pricing-ul > :not(:last-child) ' => 'margin-bottom:{{val}}px'],
			),
			'features_padding' => array(
				'label' => __pl('padding'),
				'type' => 'padding',
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-pricing-features' => 'padding: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px !important;'],
			),
		],
		'additional_info' => [						
			'additional_info' => array(
				'type' => 'textarea',
				'label' => __pl('additional_info'),
				'default' => 'Some Additional Information',
				'edit' => '.pagelayer-pricing-additional',
			),
			'additional_text_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-pricing-additional' => 'color:{{val}}'],
			),
			'addition_text_typo' => array(
				'type' => 'typography',
				'label' => __pl('heading_typo'),
				'default' => ',16,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-additional' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],	
			),
		],
		'button_style' => [
			'price_button' => array(
				'type' => 'checkbox',
				'label' => __pl('show_btn'),
				'default' => 'true',
			),		
			'button_type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				'default' => 'pagelayer-btn-danger',
				'list' => array(
					'pagelayer-btn-default' => __pl('btn_type_default'),
					'pagelayer-btn-primary' => __pl('btn_type_primary'),
					'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
					'pagelayer-btn-success' => __pl('btn_type_success'),
					'pagelayer-btn-info' => __pl('btn_type_info'),
					'pagelayer-btn-warning' => __pl('btn_type_warning'),
					'pagelayer-btn-danger' => __pl('btn_type_danger'),
					'pagelayer-btn-dark' => __pl('btn_type_dark'),
					'pagelayer-btn-light' => __pl('btn_type_light'),
					'pagelayer-btn-link' => __pl('btn_type_link'),
					'pagelayer-btn-custom' => __pl('btn_type_custom')
				),
				'req' => array(
					'price_button' => 'true'
				),
			),
			'button_size' => array(
				'type' => 'select',
				'label' => __pl('button_size_label'),
				'default' => 'pagelayer-btn-mini',
				'list' => array(
					'pagelayer-btn-mini' => __pl('mini'),
					'pagelayer-btn-small' => __pl('small'),
					'pagelayer-btn-large' => __pl('large'),
					'pagelayer-btn-extra-large' => __pl('extra_large'),
					'pagelayer-btn-double-large' => __pl('double_large'),					
					'pagelayer-btn-custom' => __pl('custom'),					
				),
				'req' => array(
					'price_button' => 'true'
				)
			),
			'button_size_custom' => array(
				'type' => 'dimension',
				'label' => __pl('padding'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-pricing-btn' => 'padding:{{val[0]}}px {{val[1]}}px;'],
				'req' => array(
					'button_size' => 'pagelayer-btn-custom'
				)
			),
			'button_url' => array(
				'type' => 'link',
				'label' => __pl('btn_url_label'),
				'selector' => '.pagelayer-pricing-btn',
				'np' => 1,
				'req' => array(
					'price_button' => 'true'
				),
			),
			'button_text' => array(
				'type' => 'text',
				'label' => __pl('button_text_label'),
				'np' => 1,
				'default' => 'Buy This Plan',
				'edit' => '.pagelayer-pricing-btn',
				'req' => array(
					'price_button' => 'true'
				),
			),
			'btn_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'css' => [
					'{{element}} .pagelayer-pricing-btn' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
				],
			),
			'btn_spacing' => array(
				'type' => 'slider',
				'label' => __pl('spacing'),
				'min' => '0',
				'max' => '200',
				'screen' => 1,
				'default' => '10',
				'css' => ['{{element}} .pagelayer-pricing-btn' => 'margin-top: {{val}}px;'],
				'req' => [
					'price_button' => 'true',
				]
			),
			'btn_state' => array(
				'type' => 'radio',
				'label' => __pl('button_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
				'req' => array(
					'price_button' => 'true',
					'button_type' => 'pagelayer-btn-custom'
				),
			),
			'button_color' => array(
				'type' => 'color',
				'label' => __pl('iconbox_button_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-btn' => 'color:{{val}};'],
				'req' => [
					'price_button' => 'true',
					'button_type' => 'pagelayer-btn-custom',
				],
				'show' => ['btn_state' => 'normal']
			),
			'button_bg_color' => array(
				'type' => 'color',
				'label' => __pl('button_bg_color'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-pricing-btn' => 'background-color:{{val}};'],
				'req' => [
					'button_type' => 'pagelayer-btn-custom',
				],
				'show' => ['btn_state' => 'normal']
			),
			'price_btn_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-pricing-btn' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['btn_state' => 'normal']
			),
			'price_btn_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'css' => ['{{element}} .pagelayer-pricing-btn' => 'border-color: {{val}};'],
				'req' => ['!price_btn_border_type' => ''],
				'show' => ['btn_state' => 'normal']
			),
			'price_btn_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'css' =>  ['{{element}} .pagelayer-pricing-btn' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => ['!price_btn_border_type' => ''],
				'show' => ['btn_state' => 'normal']
			),
			'price_btn_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'css' =>  ['{{element}} .pagelayer-pricing-btn' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'show' => ['btn_state' => 'normal']
			),
			'pricing_btn_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('btn_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-pricing-btn' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['btn_state' => 'hover'],
			),
			'pricing_btn_color_hover' => array(
				'type' => 'color',
				'label' => __pl('iconbox_button_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-pricing-btn:hover' => 'color:{{val}};'],
				'show' => ['btn_state' => 'hover'],
			),
			'pricing_btn_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('button_bg_color_hover'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-pricing-btn:hover' => 'background-color:{{val}};'],
				'show' => ['btn_state' => 'hover'],
			),
			'price_btn_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-pricing-btn:hover' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['btn_state' => 'hover']
			),
			'price_btn_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'css' => ['{{element}} .pagelayer-pricing-btn:hover' => 'border-color: {{val}};'],
				'req' => ['!price_btn_border_type_hover' => ''],
				'show' => ['btn_state' => 'hover']
			),
			'price_btn_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'css' =>  ['{{element}} .pagelayer-pricing-btn:hover' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => ['!price_btn_border_type_hover' => ''],
				'show' => ['btn_state' => 'hover']
			),
			'price_btn_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'css' =>  ['{{element}} .pagelayer-pricing-btn:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'show' => ['btn_state' => 'hover']
			),
		],
		'ribbon_style' => [
			'ribbon' => array(
				'type' => 'checkbox',
				'label' => __pl('ribbion_display'),
			),
			'ribbon_text' => array(
				'type' => 'text',
				'label' => __pl('ribbion_text'),
				'default' => 'Popular',
				'css' => ['{{element}} .pagelayer-pricing-ribbon'=> 'display:inline-block;'],
				'req' => array(
					'ribbon' => 'true'
				)
			),
			'ribbon_text_size' => array(
				'type' => 'typography',
				'label' => __pl('ribbon_text_size'),
				'default' => ',16,,500,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-pricing-ribbon' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'req' => array(
					'ribbon' => 'true'
				)
			),
			'ribbon_text_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-pricing-ribbon' => 'color:{{val}}'],
				'req' => array(
					'ribbon' => 'true'
				)
			),
			'ribbon_background' => array(
				'type' => 'color',
				'label' => __pl('background_color'),
				'default' => '#fd6129',
				'css' => ['{{element}} .pagelayer-pricing-ribbon' => 'background-color:{{val}}'],
				'req' => array(
					'ribbon' => 'true'
				)
			),
		],
		'styles' => [
			'subtitle_style' => __pl('subtitle'),
			'header_style' => __pl('header_style'),			
			'currency_style' => __pl('currency_style'),
			'price_style' => __pl('price_style'),			
			'period_style' => __pl('period_style'),			
			'feature_style' => __pl('feature_style'),
			'button_style' => __pl('button_style'),
			'additional_info' => __pl('addition_info'),
			'ribbon_style' => __pl('ribbon_style'),
		],
   	)
);

// Social Share Group
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_share_grp', array(
		'name' => __pl('social_share'),
		'group' => 'button',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('social_share_grp'),
				'sc' => PAGELAYER_SC_PREFIX.'_share',
				'item_label' => array(
					'default' => __pl('share_item'),
					'param' => 'icon'
				),
				'count' => 3,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('share_name'))),
			),
		),
		'layout_style' => [
			'type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				//'css' => ['{{element}} .pagelayer-share-content:hover' => 'border-style: {{val}}'],
				'addClass' => 'pagelayer-share-type-{{val}}',
				'default' => 'icon-label',
				'list' => [
					'icon' => __pl('icon'),
					'icon-label' => __pl('icon-label'),
					'label' => __pl('label'),
				],
			),
			'count' => array(
				'type' => 'radio',
				'label' => __pl('count_in_line'),
				'default' => '',
				'screen' => 1,
				'css' => ['{{element}} > div' => 'width: calc(100% / {{val}});'],
				'list' => array(
					'' => __pl('auto'),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'req' => array(
					'!type' => 'icon'
				)
			),
			'bg_shape' => array(
				'type' => 'select',
				'label' => __pl('icon_background_shape'),
				'np' => 1,
				'default' => 'pagelayer-social-shape-square',
				//'css' => ['{{element}} i' => 'height:1em; width:1em; position: absolute; top: 50%; left: 50%; transform: translate(-50% , -50%);',
				//'{{element}} .pagelayer-icon-holder' => 'position: relative; min-height: 1em; min-width: 1em;'],
				'addClass' => '{{val}}',
				'list' => array(
					'pagelayer-social-bg-none' => __pl('icon_shape_none'),
					'pagelayer-social-shape-circle' => __pl('icon_shape_circle'),
					'pagelayer-social-shape-square' => __pl('icon_shape_square'),
					'pagelayer-social-shape-rounded' => __pl('icon_shape_rounded'),
					'pagelayer-social-shape-boxed' => __pl('icon_shape_boxed'),
					'pagelayer-social-outline-border' => __pl('icon_shape_outline')
				),
			),
			/* 'bg_size' => array(
				'type' => 'spinner',
				'label' => __pl('social_grp_size_label'),
				'css' => ['{{element}} .pagelayer-icon-holder' => 'height: calc(1em + {{val}}px); width: calc(1em + {{val}}px);'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 10,
				'req' => array(
					'!bg_shape' => ''
				)
			), */
			'align' => array(
				'type' => 'radio',
				'label' => __pl('obj_align_label'),
				'np' => 1,
				'default' => 'center',
				'css' => 'text-align: {{val}};',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			),
			/* 'group_layout' => array(
				'type' => 'radio',
				'label' => __pl('layout'),
				'css' => ['{{element}} > div' => 'display: inline-block;'],
				'default' => 'horizontal',
				'list' => array(
					'' => __pl('vertical'),
					'horizontal' => __pl('horizontal')
				)
			), */
			'vspace' => array(
				'type' => 'spinner',
				'label' => __pl('space_between_col'),
				'css' => ['{{element}} .pagelayer-share' => 'padding-top: {{val}}px; padding-bottom: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 2,
				'screen' => 1,
				/* 'req' => array(
					'group_layout' => 'horizontal'
				) */
			),
			'hspace' => array(
				'type' => 'spinner',
				'label' => __pl('space_between_row'),
				'css' => ['{{element}} .pagelayer-share' => 'padding-left: {{val}}px; padding-right: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 2,
				'screen' => 1,
				/* 'req' => array(
					'group_layout' => ''
				) */
			),
			'height' => array(
				'type' => 'slider',
				'label' => __pl('height'),
				'css' => ['{{element}} .pagelayer-share-content' => 'min-height: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 35,
				'screen' => 1,
			),
			/* 'width' => array(
				'type' => 'slider',
				'label' => __pl('width'),
				'css' => ['{{element}} .pagelayer-share-content' => 'min-width: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
			), */
		],
		'icon_style' => [
			'icon_size' => array(
				'type' => 'spinner',
				'label' => __pl('social_grp_size_label'),
				'css' => ['{{element}} i' => 'font-size: {{val}}px;',
					'{{element}} .pagelayer-icon-holder' => 'font-size: {{val}}px;'],
				'min' => 1,
				'step' => 1,
				'max' => 500,
				'default' => 25,
				'screen' => 1,
			),
			'icon_space' => array(
				'type' => 'spinner',
				'label' => __pl('space_around'),
				'css' => ['{{element}} .pagelayer-icon-holder' => 'padding-left: calc(0.5em + {{val}}px); padding-right: calc(0.5em + {{val}}px);'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 5,
				'screen' => 1,
			),
			'color_scheme' => array(
				'type' => 'select',
				'label' => __pl('color'),
				'default' => 'pagelayer-scheme-official',
				'addClass' => '{{val}}',
				'list' => array(
					'' => __pl('custom'),
					'pagelayer-scheme-official' => __pl('official')
				)
			),
			'social_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				//'no_val' => 1,// Dont set any value to element
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				)
			),
			'icon_color' => array(
				'type' => 'color',
				'label' => __pl('social_color_label'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-share-buttons i' => 'color: {{val}} !important;'],
				'req' => array(
					'color_scheme' => ''
				),
				'show' => ['social_hover' => '']
			),
			'icon_bg_color' => array(
				'type' => 'color',
				'label' => __pl('social_bg_color_label'),
				'default' => '#0986c0',
				'css' => ['{{element}} .pagelayer-share-content' => 'background-color: {{val}} !important;'],
				'req' => array(
					'!bg_shape' => '',
					'color_scheme' => ''
				),
				'show' => ['social_hover' => '']
			),
			'icon_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-share-content' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['social_hover' => '']
			),
			'icon_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-share-content' => 'border-color: {{val}} !important;'],
				'req' => array(
					'!icon_border_type' => '',
					'color_scheme' => ''
				),
				'show' => ['social_hover' => '']
			),
			'icon_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-share-content' => 'border-top-width: {{val[0]}}px !important; border-right-width: {{val[1]}}px !important; border-bottom-width: {{val[2]}}px !important; border-left-width: {{val[3]}}px !important'],
				'req' => [
					'!icon_border_type' => ''
				],
				'show' => ['social_hover' => '']
			),
			'icon_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-share-content' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type' => ''
				),
				'show' => ['social_hover' => '']
			),
			'social_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('btn_hover_delay_label'),
				'desc' => __pl('btn_hover_delay_desc'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-share-content' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;',
				'{{element}} .pagelayer-share-content i' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => array(
					'social_hover' => 'hover'
				),
			),
			'icon_color_hover' => array(
				'type' => 'color',
				'label' => __pl('social_color_label'),
				'css' => ['{{element}} .pagelayer-share-buttons:hover i' => 'color: {{val}} !important;'],
				'req' => array(
					'color_scheme' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
			'icon_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('social_bg_color_label'),
				'css' => ['{{element}} .pagelayer-share-content:hover' => 'background-color: {{val}} !important;'],
				'req' => array(
					'!bg_shape' => '',
					'color_scheme' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-share-content:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('border_color_hover_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-share-content:hover' => 'border-color: {{val}} !important;'],
				'req' => array(
					'!icon_border_type_hover' => '',
					'color_scheme' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-share-content:hover' => 'border-top-width: {{val[0]}}px !important; border-right-width: {{val[1]}}px !important; border-bottom-width: {{val[2]}}px !important; border-left-width: {{val[3]}}px !important'],
				'req' => [
					'!icon_border_type_hover' => ''
				],
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-share-content:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type_hover' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
		],
		'label_style' => [
			'hide_name' => array(
				'type' => 'checkbox',
				'label' => __pl('hide_name'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-icon-name span' => 'display: none;'],
			),
			'name_typo' => array(
				'type' => 'typography',
				'label' => __pl('quote_content_typo'),
				'default' => ',15,,,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-icon-name' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'label_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				)
			),
			'label_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-icon-name-span' => 'color: {{val}} !important;'],
				'show' => ['label_hover' => '']
			),
			'label_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-icon-name-span:hover' => 'color: {{val}} !important;'],
				'show' => ['label_hover' => 'hover']
			)
		],
		'styles' => [
			'layout_style' => __pl('layout_style'),
			'icon_style' => __pl('icon_style'),
			'label_style' => __pl('label_style'),
		]
	)
);

// Social Share Button
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_share', array(
		'name' => __pl('icon'),
		'group' => 'button',
		'not_visible' => 1,
		'parent' => [PAGELAYER_SC_PREFIX.'_share_grp'],
		'html' => '
				<a class="pagelayer-ele-link" title="{{icon}}" href="{{social_url}}">
					<div class="pagelayer-share-content">
						<div class="pagelayer-icon-holder pagelayer-share-buttons">
							<i class="pagelayer-social-fa {{icon}}"></i>
						</div>
						<div class="pagelayer-icon-name">
							<span class="pagelayer-icon-name-span">{{icon_label}}</span>
						</div>
					</div>
				</a>',
		'params' => array(
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'fab fa-facebook-square',
				'addAttr' => ['{{element}} .pagelayer-share-content' => 'data-icon="{{icon}}"'],
				'list' => ['facebook', 'facebook-f', 'facebook-square', 'facebook-messenger', 'twitter', 'twitter-square', 'google-plus', 'google-plus-square', 'google-plus-g', 'instagram', 'linkedin', 'linkedin-in', 'pinterest', 'pinterest-p', 'pinterest-square', 'reddit-alien', 'reddit-square', 'reddit', 'skype', 'stumbleupon', 'stumbleupon-circle', 'telegram', 'telegram-plane', 'tumblr', 'tumblr-square', 'vk', 'weibo', 'whatsapp', 'whatsapp-square', 'wordpress', 'wordpress-simple', 'xing', 'xing-square', 'delicious', 'dribbble', 'dribbble-square', 'snapchat-ghost','get-pocket', 'envelope', 'envelope-open', 'envelope-o'],
			),
			'custom_profile' => array(
				'type' => 'text',
				'label' => __pl('custom_profile'),
				'req' => ['icon' => ['fab fa-instagram', 'fab fa-dribbble', 'fab fa-dribbble-square']]
			),
			'text' => array(
				'type' => 'text',
				'label' => __pl('custom_label_text'),
				'edit' => '.pagelayer-icon-name-span',
				'np' => 1,
			),
			'target' => array(
				'label' => __pl('open_link_in_new_window'),
				'type' => 'checkbox',
				'addAttr' => ['{{element}} a' => 'target="_blank"'],
				'np' => 1,
			),
		)
	)
);

// Animated heading
pagelayer_freemium_shortcode(PAGELAYER_SC_PREFIX.'_anim_heading', array(
		'name' => __pl('animated_heading'),
		'group' => 'text',
		'innerHTML' => 'text',
		'html' =>  '<a if-ext="{{link}}" href="{{link}}">
			<div class="pagelayer-aheading-holder {{rotate_req}} {{animations}}">
				<div if="{{text}}" class="pagelayer-animated-heading pagelayer-animated-title">{{text}}</div>&nbsp;{{rotate_html}}
				<div if="{{after_text}}" class="pagelayer-animated-heading">{{after_text}}</div>
			<div class="pagelayer-blobs_1"></div><div class="pagelayer-blobs_2"></div><div class="pagelayer-blobs_3"></div><div class="pagelayer-blobs_4"></div><div class="pagelayer-blobs_5"></div><div class="pagelayer-blobs_6"></div><div class="pagelayer-blobs_7"></div>
			</div>
		</a>',
		'params' => array(
			'type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				'default' => 'effects',
				'addClass' => 'pagelayer-heading-{{val}}',
				'list' => array(
					'effects' => __pl('effects'),
					'rotating' => __pl('rotating'),
				),
			),
			'effects' => array(
				'type' => 'select',
				'label' => __pl('effects'),
				'default' => 'blobs',
				'addClass' => 'pagelayer-hEffect-{{val}}',
				'list' => array(
					'none' => __pl('none'),
					'blobs' => __pl('blobs'),
					'stroke' => __pl('stroke'),
					'shadow' => __pl('shadow'),
				),
				'req' => [ 'type' => 'effects' ]
			),
			'animations' => array(
				'type' => 'select',
				'label' => __pl('effects'),
				'default' => 'pagelayer-aheading-rotate1',
				'list' => array(
					'pagelayer-aheading-rotate1' => __pl('rotate-1'),
					'pagelayer-aheading-rotate2' => __pl('rotate-2'),
					'pagelayer-aheading-rotate3' => __pl('rotate-3'),
					'pagelayer-aheading-loading-bar' => __pl('loading-bar'),
					'pagelayer-aheading-slide' => __pl('slide'),
					'pagelayer-aheading-clip' => __pl('clip'),
					'pagelayer-aheading-zoom' => __pl('zoom'),
					'pagelayer-aheading-scale' => __pl('scale'),
					'pagelayer-aheading-push' => __pl('push'),
				),
				'req' => [ 'type' => 'rotating' ]
			),
			'hide_clip' => array(
				'label' => __pl('clip_hide'),
				'type' => 'checkbox',
				'css' => ['{{element}} .pagelayer-words-wrapper:after' => 'display:none'],
				'req' => [ 'animations' => 'pagelayer-aheading-clip' ]
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'np' => 1,
				'default' => 'center',
				'screen' => 1,
				'css' => 'text-align: {{val}};',
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				)
			),
		),
		'title_style' => [
			'text' => array(
				'type' => 'text',
				'label' => __pl('title'),
				'np' => 1,
				'default' => __pl('animated_heading'),
				'edit' => '.pagelayer-animated-title',
				'e' => [ 'v', 'f', 'r']
			),
			'rotate_text' => array(
				'type' => 'textarea',
				'label' => __pl('rotate_text'),
				'np' => 1,
				'default' => __pl('rotate_default'),
				'req' => [ 'type' => 'rotating' ]
			),
			'after_text' => array(
				'type' => 'text',
				'label' => __pl('after_text'),
				'np' => 1,
				'req' => [ 'type' => 'rotating' ]
			),
			'typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'default' => ',40,,700,,,solid,,,,',
				'css' => ['{{element}} .pagelayer-animated-heading' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'color_type' => array(
				'type' => 'radio',
				'label' => __pl(''),
				'np' => 1,
				'default' => 'color',
				'list' => array(
					'color' => __pl('color'),
					'gradient' => __pl('gradient'),
				),
			),
			'color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#A236FA',
				'css' => [
					'{{element}} .pagelayer-animated-heading' => 'background:{{val}}; -webkit-background-clip: text;',
					'{{element}}.pagelayer-hEffect-shadow .pagelayer-animated-heading' => 'color:{{val}};',
					'{{element}} .pagelayer-rotating-text *' => 'background:{{val}};  -webkit-background-clip: text;',
					'{{element}} .pagelayer-aheading-loading-bar .pagelayer-words-wrapper:after' => 'background:{{val}};',
					'{{element}} .pagelayer-aheading-clip .pagelayer-words-wrapper:after' => 'background:{{val}};'
				],
				'req' => ['color_type' => 'color']
			),
			'gradient' => array(
				'type' => 'gradient',
				'label' => '',
				'default' => '150,#44d3f6,23,#72e584,45,#2ca4eb,100',
				'css' => [
					'{{element}} .pagelayer-animated-heading' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%); -webkit-background-clip: text;',
					'{{element}} .pagelayer-rotating-text *' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%); -webkit-background-clip: text;',
					'{{element}} .pagelayer-aheading-loading-bar .pagelayer-words-wrapper:after' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);',
					'{{element}} .pagelayer-aheading-clip .pagelayer-words-wrapper:after' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'
				],
				'req' => [
					'color_type' => 'gradient',
					'!effects' => 'shadow'
				]
			),
		], 
		'misc_style' => [
			'blob_1' => array(
				'type' => 'color',
				'label' => __pl('blob_1_color'),
				'default' => '#ff1493',
				'css' => ['{{element}} .pagelayer-blobs_1' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'blob_2' => array(
				'type' => 'color',
				'label' => __pl('blob_2_color'),
				'default' => '#ff4500',
				'css' => ['{{element}} .pagelayer-blobs_2' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'blob_3' => array(
				'type' => 'color',
				'label' => __pl('blob_3_color'),
				'default' => '#00ff00',
				'css' => ['{{element}} .pagelayer-blobs_3' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'blob_4' => array(
				'type' => 'color',
				'label' => __pl('blob_4_color'),
				'default' => '#ff0000',
				'css' => ['{{element}} .pagelayer-blobs_4' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'blob_5' => array(
				'type' => 'color',
				'label' => __pl('blob_5_color'),
				'default' => '#ffff00',
				'css' => ['{{element}} .pagelayer-blobs_5' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'blob_6' => array(
				'type' => 'color',
				'label' => __pl('blob_6_color'),
				'default' => '#00ffff',
				'css' => ['{{element}} .pagelayer-blobs_6' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'blob_7' => array(
				'type' => 'color',
				'label' => __pl('blob_7_color'),
				'default' => '#ff8c00',
				'css' => ['{{element}} .pagelayer-blobs_7' => 'background:{{val}}'],
				'req' => [
					'effects' => 'blobs',
					'type' => 'effects'
				]
			),
			'stroke' => array(
				'type' => 'slider',
				'label' => __pl('stroke_thickness'),
				'min' => 1,
				'step' => 1,
				'max' => 50,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-animated-heading' => '-webkit-text-stroke: {{val}}px transparent;'],
				'req' => [ 'effects' => 'stroke' ]
			),
			'stroke_color' => array(
				'type' => 'color',
				'label' => __pl('stroke_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-animated-heading' => 'color:{{val}}'],
				'req' => [ 'effects' => 'stroke' ]
			),
			'shadow_color' => array(
				'type' => 'shadow',
				'label' => __pl('shadow'),
				'default' => '2,2,,#999999',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-animated-heading' => 'text-shadow: {{val[0]}}px {{val[1]}}px #fff, calc({{val[0]}}px * 2) calc({{val[1]}}px * 2) {{val[3]}};'],
				'req' => [ 'type' => 'effects', 'effects' => 'shadow' ]
			),
			'rotate_color' => array(
				'type' => 'color',
				'label' => __pl('rotate_color'),
				'css' => [
					'{{element}} .pagelayer-rotating-text *' => 'background:{{val}}; -webkit-background-clip: text;',
					'{{element}} .pagelayer-aheading-loading-bar .pagelayer-words-wrapper:after' => 'background:{{val}};',
					'{{element}} .pagelayer-aheading-clip .pagelayer-words-wrapper:after' => 'background:{{val}};'
				],
				'req' => ['type' => 'rotating']
			),
		],
		'styles' => [
			'title_style' => __pl('title_style'),
			'misc_style' => __pl('misc_style'),
		]
	)
);
