<?php

//////////////////////////////////////////////////////////////
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

/**
 * Override customize controls class
 *
 */
class Pagelayer_Customize_Control extends WP_Customize_Control{
	
	public $show_filter = '';
	public $li_class = '';
	
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {

		parent::to_json();
		
		if(!empty($this->show_filter)){
			$this->json['show_filter'] = $this->show_filter;
		}
	}
	
	protected function render() {
		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'pagelayer-customize-control customize-control customize-control-' . $this->type;
		
		$class .= ' '.$this->li_class;

		printf( '<li id="%s" class="%s">', esc_attr( $id ), esc_attr( $class ) );
		$this->render_content();
		echo '</li>';
	}
}

/**
 * Padding control to separate general and style controls
 *
 */
class Pagelayer_Padding_Control extends Pagelayer_Customize_Control {
		
	/**
	 * The type of control being rendered
	 */
	public $type = 'pagelayer-padding-control';
	public $responsive;
	public $units;
	
	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		
		$units = (array) $this->units;
		
		// Output the label and description if they were passed in.
		if ( isset( $this->label ) && '' !== $this->label ) {
			echo '<span class="customize-control-title pagelayer-customize-control-title">' . sanitize_text_field( $this->label );
			
			if(!empty($this->responsive )){
				echo '<span class="pagelayer-devices">
					<button type="button" class="active-device" aria-pressed="true" data-device="desktop">
					<i class="dashicons dashicons-desktop"></i>
					</button>
					<button type="button"aria-pressed="false" data-device="tablet">
					<i class="dashicons dashicons-tablet"></i>
					</button>
					<button type="button" aria-pressed="false" data-device="mobile">
					<i class="dashicons dashicons-smartphone"></i>
					</button>
				</span>';
			}
			
			if(!empty($units)){
				?>
				<span class="pagelayer-units">
					<input type="hidden" class="pagelayer-unit-input" value="<?php echo esc_attr($this->value('unit')); ?>" <?php $this->link('unit'); ?>></input>
					<?php 
					foreach($units as $unit){
						echo '<span data-unit="'.$unit.'"> '.$unit.' </span>';
					}
					?>
				</span>
				<?php
			}
				
			echo '</span>';
		}
		
		$settings = array();
		
		foreach ( $this->settings as $key => $setting ){
			$key = str_replace(['_mobile', '_tablet'], '', $key);
			
			if(in_array($key, $settings)){
				continue;
			}
			
			$settings[] = $key;
		}		
		
		$screens = array('');
		
		if(!empty($this->responsive)){
			$screens = array('', '_tablet', '_mobile');
		}
		
		echo '<div class="pagelayer-paddings-holder">';
		
		foreach($screens as $screen){
			
			$show_device = '';
			if(count($screens) > 1){
				$show_device =  'data-show-device="'.(empty($screen) ? '_desktop' : $screen).'"';
			}
			
			echo '<div class="pagelayer-control-padding" '.$show_device.'>';
			foreach($settings as $setting){
				
				// Skip units for responsive
				if($setting == 'unit'){
					continue;
				}
				
				$setting_name = $setting.$screen;
	?>
		<input type="number" class="pagelayer-padding-input" value="<?php echo esc_attr($this->value($setting_name)); ?>" <?php $this->link($setting_name); ?>></input>
	<?php
			}
			
			echo '<i class="dashicons dashicons-admin-links"></i></div>';
		}
		
		echo '</div>';
	}
}

/**
 * Typography control controls
 *
 */
class Pagelayer_typo_Control extends Pagelayer_Customize_Control {
		
	/**
	 * The type of control being rendered
	 */
	public $type = 'pagelayer-typo-control';
	public $responsive;
	public $style;
	
	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
	}
	
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		global $pagelayer;
		 
		// Output the label and description if they were passed in.
		if ( isset( $this->label ) && '' !== $this->label ) {
			echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) .'</span>';
		}
		
		$settings = $pagelayer->font_settings;
		
		echo '<div class="pagelayer-typography-holder">';			
			
			$global_font = $this->value('global-font');
			
			if(!empty($global_font) && !isset($pagelayer->global_fonts[$global_font])){
				$global_font = 'primary';
			}
				
			echo '<div class="pagelayer-control-typography">';
	?>
	<div class="pagelayer-control-typo-holder <?php echo (!empty($global_font) ? 'pagelayer-global-on' : ''); ?>">
		<div class="pagelayer-control-typo-icons-holder">
			<span class="pagelayer-control-typo-icon dashicons dashicons-edit"></span>
		</div>
		<div class="pagelayer-control-typo">
			<div class="pagelayer-global-setting-font">
				<b><?php _e('Global Fonts'); ?></b>
				<span class="pagelayer-control-global-typo-icon dashicons dashicons-admin-site-alt3"></span>
				<span class="dashicons dashicons-admin-generic"></span>
				<input class="pagelayer-global-font-input" type="hidden" <?php $this->link('global-font'); ?> value="<?php echo esc_attr($global_font); ?>" data-key="<?php echo esc_attr($global_font); ?>">
				
				<div class="pagelayer-global-font-list"></div>		
			</div>		
			<?php foreach($settings as $sk => $sval){ ?>
				<div class="pagelayer-control-typo-fields">
					<label class="pagelayer-control-typo-fields-label"><?php echo $sval['label']?>
					<?php 
					$screens = array('');
					if(!empty($sval['responsive'])){
						
						$screens = array('desktop', 'tablet', 'mobile');
						
						?>
						<span class="pagelayer-devices">
							<button type="button" class="active-device" aria-pressed="true" data-device="desktop">
							<i class="dashicons dashicons-desktop"></i>
							</button>
							<button type="button"aria-pressed="false" data-device="tablet">
							<i class="dashicons dashicons-tablet"></i>
							</button>
							<button type="button" aria-pressed="false" data-device="mobile">
							<i class="dashicons dashicons-smartphone"></i>
							</button>
						</span>
					<?php } ?>
					<span class="pagelayer-typo-global-default dashicons dashicons-undo" title="<?php _e('Restore Global'); ?>"></span>
					</label>
					<?php
					foreach($screens as $screen){
		
						$show_device = '';
						$field_name = $sk;
						
						if(count($screens) > 1){
							$show_device = 'data-show-device="_'.$screen.'"';
							$field_name = $sk.($screen == 'desktop' ? '' : '_'.$screen);
						}
						
						$field_val = esc_attr($this->value($field_name));
						
						if(isset($sval['choices'])){ ?>
							
							<select name="<?php echo $field_name; ?>" <?php $this->link($field_name); ?> data-font-key="<?php echo $sk;?>" data-default-value="<?php echo $field_val; ?>" <?php echo $show_device; ?>>
								<?php
								// This add this js
								//echo pagelayer_create_font_options($sval['choices'], $this->value($field_name));
								?>
							</select>
						<?php } else { ?>
							<input name="<?php echo $field_name; ?>" type="number" <?php $this->link($field_name); ?> <?php echo $show_device; ?>>
						<?php } 
					}
					?>
				</div>
			<?php }?>
		</div>
	</div>
		<?php			
		echo '</div></div>';
	}
}

/**
 * Alpha Color Picker Custom Control
 *
 * @author Braad Martin <http://braadmartin.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker
 */
class Pagelayer_Customize_Alpha_Color_Control extends Pagelayer_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'pagelayer-alpha-color';
	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 */
	public $palette;
	/**
	 * Add support for showing the opacity value on the slider handle.
	 */
	public $show_opacity;
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		global $pagelayer;
		
		$setvalue = $this->value();
		
		// Process the palette
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}

		// Support passing show_opacity as string or boolean. Default to true.
		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

		// Output the label and description if they were passed in.
		if ( isset( $this->label ) && '' !== $this->label ) {
			echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
		}
		if ( isset( $this->description ) && '' !== $this->description ) {
			echo '<span class="description pagelayer-customize-description">' . sanitize_text_field( $this->description ) . '</span>';
		} ?>
		
			<span class="pagelayer-control-global-color-icon dashicons dashicons-admin-site-alt3"></span>
			<div class="pagelayer-global-color-list">
			<div class="pagelayer-global-setting-color">
			<b>Global Colors</b>
			<span class="dashicons dashicons-admin-generic"></span></div>
			<?php
				$gkey = '';
				if( !empty($setvalue) && $setvalue[0] == '$'){
					$gkey = substr($setvalue, 1);
					$gkey = isset($pagelayer->global_colors[$gkey]) ? $gkey : 'primary';
				}
				
				foreach($pagelayer->global_colors as $cid => $color){
				
				$active_class = '';
				if($cid == $gkey){
					$active_class = 'pagelayer-global-selected';
				}
			?>
				<div class="pagelayer-global-color-list-item <?php echo $active_class; ?>" data-global-id="<?php echo $cid; ?>">
					<span class="pagelayer-global-color-pre" style="background:<?php echo $color['value']; ?>;"></span>
					<span class="pagelayer-global-color-title"><?php echo $color['title'];?></span>
					<span class="pagelayer-global-color-code"><?php echo $color['value']; ?></span>
				</div>
			<?php }?>
			</div>
			<input class="pagelayer-alpha-color-control" type="text" data-show-opacity="<?php echo $show_opacity; ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
		<?php

	}
}

// Global color palette control
class Pagelayer_Color_Repeater_Control extends Pagelayer_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'pagelayer-alpha-color';
	/**
	 * Button labels
	 */
	public $button_label = '';
	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		
		if(empty($this->button_label)){
			$this->button_label = __( 'Add New Color', 'pagelayer' );
		}
	}
	
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		
		$values = $this->value();
		
		$decode_values = json_decode($values, true);
		
		$skip_keys = array('primary', 'secondary', 'text', 'accent');
	?>
	  <div class="pagelayer-color-palette-control">
			<?php if( !empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if( !empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<input type="hidden" class="pagelayer-color-palette-data" <?php $this->link(); ?>>			
			<?php
			foreach( $decode_values as $kk => $val){ ?>
			
			<div class="pagelayer-color-holder">
				<span class="pagelayer-color-title" contenteditable="true"><?php _e($val['title']); ?></span>
				<span class="pagelayer-color-controls <?php echo (in_array($kk, $skip_keys)? 'pagelayer-prevent-delete' : ''); ?>">
					<?php echo esc_attr($val['value']) ?> 
				</span>
				<?php if(!in_array($kk, $skip_keys)){ ?>
					<span class="customize-control-color-repeater-delete"><span class="dashicons dashicons-no-alt"></span></span>
				<?php }?>
				<input class="pagelayer-alpha-color-control" type="text" data-show-opacity="true" data-palette="true" data-default-color="<?php echo esc_attr($val['value']); ?>" data-id="<?php echo esc_attr($kk); ?>"  data-title="<?php echo esc_attr($val['title']); ?>" value="<?php echo esc_attr($val['value']); ?>" />
			</div>
			
			<?php }?>
			<button class="button customize-control-color-repeater-add" type="button"><?php echo $this->button_label; ?></button>
		</div>
	<?php
	}
}

// Global color palette control
class Pagelayer_Font_Repeater_Control extends Pagelayer_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'pagelayer-global-font';
	/**
	 * Button labels
	 */
	public $button_label = '';
	
	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		
		if(empty($this->button_label)){
			$this->button_label = __( 'Add New Font', 'pagelayer' );
		}
	}
	
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		global $pagelayer;
		
		$values = $this->value();
		
		$decode_values = (array) json_decode($values, true);
		
		$settings = $pagelayer->font_settings;
		
		$skip_keys = array('primary', 'secondary', 'text', 'accent');
	?>
	  <div class="pagelayer-font-palette-control">
			<?php if( !empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if( !empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<input type="hidden" class="pagelayer-font-palette-data" <?php $this->link(); ?>>			
			<?php
			foreach( $decode_values as $kk => $val){ ?>
			
			<div class="pagelayer-font-holder" data-id="<?php echo $kk; ?>">
				<span class="pagelayer-font-title" contenteditable="true"><?php _e($val['title']); ?></span>
				<?php if(!in_array($kk, $skip_keys)){ ?>
					<span class="customize-control-font-repeater-delete"><span class="dashicons dashicons-no-alt"></span></span>
				<?php }?>
				
				<!-- Font Start -->
				<div class="pagelayer-control-typo-holder">
					<span class="pagelayer-control-typo-icon dashicons dashicons-edit"></span>
					<div class="pagelayer-control-typo">
					
					<?php foreach($settings as $sk => $sval){ ?>
						<div class="pagelayer-control-typo-fields">
							<label class="pagelayer-control-typo-fields-label"><?php echo $sval['label']?>
							
							<?php 
							$screens = array('');
							if(!empty($sval['responsive'])){
								
								$screens = array('desktop', 'tablet', 'mobile');
								
								?>
								<span class="pagelayer-devices">
									<button type="button" class="active-device" aria-pressed="true" data-device="desktop">
									<i class="dashicons dashicons-desktop"></i>
									</button>
									<button type="button"aria-pressed="false" data-device="tablet">
									<i class="dashicons dashicons-tablet"></i>
									</button>
									<button type="button" aria-pressed="false" data-device="mobile">
									<i class="dashicons dashicons-smartphone"></i>
									</button>
								</span>
							<?php } ?>
							</label>
							
							<?php
						foreach($screens as $screen){
		
							$show_device = '';
							$field_name = $sk;
							$field_val = (empty($val['value'][$sk]) ? '' : $val['value'][$sk]);
							
							if(count($screens) > 1){
								$field_name = $sk.'['.$screen.']';
								$show_device = 'data-show-device="_'.$screen.'"';
								
								if(is_array($field_val)){
									$field_val = (empty($field_val[$screen]) ? '' : $field_val[$screen]);
								}
							}
							
							if(isset($sval['choices'])){ ?>
								<select name="<?php echo $field_name; ?>" data-font-key="<?php echo $sk;?>" data-default-value="<?php  echo $field_val; ?>" <?php echo $show_device;?>>
									<?php
									// This add this js
									//echo pagelayer_create_font_options($sval['choices'], $val['value'][$sk]);
									?>
								</select>
							<?php } else { ?>
								<input type="number" name="<?php echo $field_name; ?>" value="<?php  echo $field_val; ?>" <?php echo $show_device;?>>
							<?php 
							}
						}
						?>
						</div>
					<?php } ?>
						
					</div>
				</div>
				<!-- Font End -->
			</div>
			
			<?php }?>
			<button class="button customize-control-font-repeater-add" type="button"><?php echo $this->button_label; ?></button>
		</div>
	<?php
	}
}

/**
 * Customize control
 *
 */
class Pagelayer_Custom_Control extends Pagelayer_Customize_Control {
		
	/**
	 * The type of control being rendered
	 */
	public $type = 'pagelayer-customize-control';
	public $responsive;
	public $units;

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		
		$units = $this->units;
		$input_id         = '_customize-input-' . $this->id;
		$description_id   = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';
		switch ( $this->type ) {
			case 'checkbox':
				?>
				<span class="pagelayer-customize-inside-control-row">
					<label for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $this->label ); ?></label>
					<input
						id="<?php echo esc_attr( $input_id ); ?>"
						class="pagelayer-customize-checkbox"
						<?php echo $describedby_attr; ?>
						type="checkbox"
						value="<?php echo esc_attr( $this->value() ); ?>"
						<?php $this->link(); ?>
						<?php checked( $this->value() ); ?>
					/>
					<?php if ( ! empty( $this->description ) ) : ?>
						<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
					<?php endif; ?>
				</span>
				<?php
				break;
			case 'radio':
				if ( empty( $this->choices ) ) {
					return;
				}

				$name = '_customize-radio-' . $this->id;
				?>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
				<span class="pagelayer-customize-inside-control-row">
				<?php foreach ( $this->choices as $value => $label ) : ?>
					
						<input
							id="<?php echo esc_attr( $input_id . '-radio-' . $value ); ?>"
							class="pagelayer-customize-radio"
							type="radio"
							<?php echo $describedby_attr; ?>
							value="<?php echo esc_attr( $value ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
							data-label="<?php echo esc_html( $label ); ?>"
							/>
				<?php endforeach; ?>
				</span>
				<?php
				break;
			case 'divider':
				echo '<hr class="pagelayer-customize-divider">';
				break;
			case 'pl_slider':
			?>
				<div class="pagelayer-slider-custom-control">
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?>
					<?php if(!empty($this->responsive )){?>
						<span class="pagelayer-devices">
							<button type="button" class="active-device" aria-pressed="true" data-device="desktop">
							<i class="dashicons dashicons-desktop"></i>
							</button>
							<button type="button"aria-pressed="false" data-device="tablet">
							<i class="dashicons dashicons-tablet"></i>
							</button>
							<button type="button" aria-pressed="false" data-device="mobile">
							<i class="dashicons dashicons-smartphone"></i>
							</button>
						</span>
					<?php } ?>
					</span>
					<?php 
						if(!empty($units)){
					?>
						<span class="pagelayer-units">
							<input type="hidden" class="pagelayer-unit-input" value="<?php echo esc_attr($this->value('unit')); ?>" <?php $this->link('unit'); ?>></input>
							<?php 
							foreach($units as $unit){
								echo '<span data-unit="'.$unit.'"> '.$unit.' </span>';
							}
							?>
						</span>
					<?php }
					
						$screens = array('');
						$set_link = 'slider';
		
						if(!empty($this->responsive)){
							$screens = array('desktop' => '_desktop', 'tablet' => '_tablet', 'mobile' => '_mobile');
						}
										
						foreach($screens as $screen => $_screen){
							
							$show_device = empty($_screen)? '' : 'data-show-device="'.$_screen.'"';
							
							echo '<div class="pagelayer-control-typography" '.$show_device.'>';
					?>
						<input class="pagelayer-slider" type="range" min="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" max="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" step="<?php echo esc_attr( $this->input_attrs['step'] ); ?>" value="<?php echo esc_attr( $this->value($set_link.$_screen) ); ?>" />
						<input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value($set_link.$_screen) ); ?>" class="customize-control-slider-value" <?php $this->link($set_link.$_screen); ?> min="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" max="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" step="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"/>
					
						</div>
						<?php } ?>
				</div>
			<?php
				break;
		}
	}
}

/**
 * Switch sanitization
 *
 * @param  string   Switch value
 * @return integer	Sanitized value
 */
if ( ! function_exists( 'pagelayer_switch_sanitization' ) ) {
	function pagelayer_switch_sanitization( $input ) {
		if ( true === $input ) {
			return 1;
		} else {
			return 0;
		}
	}
}

/**
 * Alpha Color (Hex & RGBa) sanitization
 *
 * @param  string	Input to be sanitized
 * @return string	Sanitized input
 */
if ( ! function_exists( 'pagelayer_hex_rgba_sanitization' ) ) {
	function pagelayer_hex_rgba_sanitization( $input, $setting ) {
		if ( empty( $input ) || is_array( $input ) ) {
			return $setting->default;
		}

		if ( false === strpos( $input, 'rgba' ) ) {
			// If string doesn't start with 'rgba' then santize as hex color
			$input = sanitize_hex_color( $input );
		} else {
			// Sanitize as RGBa color
			$input = str_replace( ' ', '', $input );
			sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
			$input = 'rgba(' . pagelayer_in_range( $red, 0, 255 ) . ',' . pagelayer_in_range( $green, 0, 255 ) . ',' . pagelayer_in_range( $blue, 0, 255 ) . ',' . pagelayer_in_range( $alpha, 0, 1 ) . ')';
		}
		return $input;
	}
}

/**
 * Only allow values between a certain minimum & maxmium range
 *
 * @param  number	Input to be sanitized
 * @return number	Sanitized input
 */
if ( ! function_exists( 'pagelayer_in_range' ) ) {
	function pagelayer_in_range( $input, $min, $max ){
		if ( $input < $min ) {
			$input = $min;
		}
		if ( $input > $max ) {
			$input = $max;
		}
		return $input;
	}
}

// Create font options
function pagelayer_create_font_options( $args, $set ){
	$options = '';
	foreach( $args as $value => $label ){
		$_value = $value;
		
		if(is_numeric($value)){
			$_value = $label;
		}
		
		// Single item
		if(is_string($label)){
			$options .= pagelayer_sel_option( $_value, $label, $set);
			continue;
		}
		if( $value == 'default'){
			$options .= pagelayer_sel_option( '', $value, $set);
			continue;
		}
		
		$options .= '<optgroup label="'. $value .'">';
		$options .= pagelayer_create_font_options($label,  $set);
		$options .= '</optgroup>';
	}
	
	return $options;
}
