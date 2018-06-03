<?php 
/**
 * This file contains the function which set up the Dates in the Grid. 
 *
 * To use this for additional Text Overlays in a grid, duplicate this file 
 * 1. Find and replace "eventgrid" with your plugin's prefix
 * 2. Find and replace "date" with your desired text overlay name
 * 3. Make custom changes to the mp_stacks_eventgrid_date function about what is displayed.
 *
 * @since 1.0.0
 *
 * @package    MP Stacks EventGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2016, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Add the meta options for the Grid Dates to the EventGrid Metabox
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $post_id Int - The ID of the Brick
 * @return   Array - All of the placement optons needed for Date
 */
function mp_stacks_eventgrid_date_meta_options( $items_array ){		
	
	//Date Settings
	$new_fields = array(
		//Date
		'eventgrid_date_showhider' => array(
			'field_id'			=> 'eventgrid_date_settings',
			'field_title' 	=> __( 'Date Settings', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( '', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		'eventgrid_date_format' => array(
			'field_id'			=> 'eventgrid_date_format',
			'field_title' 	=> __( 'Date Format', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the date format for your Wordpress by', 'mp_stacks_eventgrid' ) . ' <a target="_blank" href="' .  admin_url( 'options-general.php' ) . '">' . __( 'clicking here', 'mp_stacks_eventgrid' ) . '</a>',
			'field_type' 	=> 'basictext',
			'field_value' => 'true',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_show' => array(
			'field_id'			=> 'eventgrid_date_show',
			'field_title' 	=> __( 'Show Dates?', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Do you want to show the Dates for these posts?', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'true',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_placement' => array(
			'field_id'			=> 'eventgrid_date_placement',
			'field_title' 	=> __( 'Date Placement', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Where would you like to place the date? Default: Over Image, Top-Left', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'select',
			'field_value' => 'over_image_top_left',
			'field_select_values' => mp_stacks_get_text_position_options(),
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_color' => array(
			'field_id'			=> 'eventgrid_date_color',
			'field_title' 	=> __( 'Date\' Color', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Select the color the dates will be. Default: #000 (Black)', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#000',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_size' => array(
			'field_id'			=> 'eventgrid_date_size',
			'field_title' 	=> __( 'Date Size', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Enter the text size the dates will be. Default: 13', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '13',
			'field_showhider' => 'eventgrid_date_settings',
		),
		
		'eventgrid_date_google_font' => array(
			'field_id'			=> 'eventgrid_date_google_font',
			'field_title' 	=> __( 'Google Font Name', 'mp_stacks'),
			'field_description' 	=> 'Enter the name of the Google Font to use for this Text <br /><a class="button" href="https://www.google.com/fonts" target="_blank">Browse Google Fonts<div  style="margin-top: 3.3px; margin-left: 5px;" class="dashicons dashicons-share-alt2"></div></a>',
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_placeholder' => __( 'Google Font Name', 'mp_stacks_googlefonts' ),
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_google_font_weight_style' => array(
			'field_id'			=> 'eventgrid_date_google_font_weight_style',
			'field_title' 	=> __( 'Font Weight/Style', 'mp_stacks'),
			'field_description' 	=> 'Set the weight of this font (If available for your chosen font)',
			'field_type' 	=> 'select',
			'field_select_values' => array( 
				'100' => 'Thin', 
				'200' => 'Extra-Light', 
				'300' => 'Light', 
				'400' => 'Normal', 
				'500' => 'Medium', 
				'600' => 'Semi-Bold', 
				'700' => 'Bold',
				'900' => 'Ultra-Bold', 
			),
			'field_value' => '',
			'field_showhider' => 'eventgrid_date_settings',
		),
		
		'eventgrid_date_spacing' => array(
			'field_id'			=> 'eventgrid_date_spacing',
			'field_title' 	=> __( 'Dates\' Spacing', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'How much space should there be between the date and any content directly above it? Default: 10', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '10',
			'field_showhider' => 'eventgrid_date_settings',
		),
		//Date animation stuff
		'eventgrid_date_animation_desc' => array(
			'field_id'			=> 'eventgrid_date_animation_description',
			'field_title' 	=> __( 'Animate the Date upon Mouse-Over', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Add keyframe animations to apply to the date and play upon mouse-over.', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'basictext',
			'field_value' => '',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_animation_repeater_title' => array(
			'field_id'			=> 'eventgrid_date_animation_repeater_title',
			'field_title' 	=> __( 'KeyFrame', 'mp_stacks_eventgrid'),
			'field_description' 	=> NULL,
			'field_type' 	=> 'repeatertitle',
			'field_repeater' => 'eventgrid_date_animation_keyframes',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_animation_length' => array(
			'field_id'			=> 'animation_length',
			'field_title' 	=> __( 'Animation Length', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the length between this keyframe and the previous one in milliseconds. Default: 500', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '500',
			'field_repeater' => 'eventgrid_date_animation_keyframes',
			'field_showhider' => 'eventgrid_date_settings',
			'field_container_class' => 'mp_animation_length',
		),
		'eventgrid_date_animation_opacity' => array(
			'field_id'			=> 'opacity',
			'field_title' 	=> __( 'Opacity', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the opacity percentage at this keyframe. Default: 100', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '100',
			'field_repeater' => 'eventgrid_date_animation_keyframes',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_animation_rotation' => array(
			'field_id'			=> 'rotateZ',
			'field_title' 	=> __( 'Rotation', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the rotation degree angle at this keyframe. Default: 0', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'eventgrid_date_animation_keyframes',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_animation_x' => array(
			'field_id'			=> 'translateX',
			'field_title' 	=> __( 'X Position', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the X position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'eventgrid_date_animation_keyframes',
			'field_showhider' => 'eventgrid_date_settings',
		),
		'eventgrid_date_animation_y' => array(
			'field_id'			=> 'translateY',
			'field_title' 	=> __( 'Y Position', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the Y position, in relation to its starting position, at this keyframe. The unit is pixels. Default: 0', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '0',
			'field_repeater' => 'eventgrid_date_animation_keyframes',
			'field_showhider' => 'eventgrid_date_settings',
		),
		//Date Background
		'eventgrid_date_bg_showhider' => array(
			'field_id'			=> 'eventgrid_date_background_settings',
			'field_title' 	=> __( 'Date Background Settings', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( '', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		'eventgrid_date_bg_show' => array(
			'field_id'			=> 'eventgrid_date_background_show',
			'field_title' 	=> __( 'Show Date Backgrounds?', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Do you want to show a background color behind the date?', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'checkbox',
			'field_value' => 'true',
			'field_showhider' => 'eventgrid_date_background_settings',
		),
		'eventgrid_date_bg_size' => array(
			'field_id'			=> 'eventgrid_date_background_padding',
			'field_title' 	=> __( 'Date Background Size', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'How many pixels bigger should the Date Background be than the Text? Default: 5', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'number',
			'field_value' => '5',
			'field_showhider' => 'eventgrid_date_background_settings',
		),
		'eventgrid_date_bg_color' => array(
			'field_id'			=> 'eventgrid_date_background_color',
			'field_title' 	=> __( 'Date Background Color', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'What color should the date background be? Default: #FFF (White)', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#FFF',
			'field_showhider' => 'eventgrid_date_background_settings',
		),
		'eventgrid_date_bg_opacity' => array(
			'field_id'			=> 'eventgrid_date_background_opacity',
			'field_title' 	=> __( 'Date Background Opacity', 'mp_stacks_eventgrid'),
			'field_description' 	=> __( 'Set the opacity percentage? Default: 100', 'mp_stacks_eventgrid' ),
			'field_type' 	=> 'input_range',
			'field_value' => '100',
			'field_showhider' => 'eventgrid_date_background_settings',
		),

	);
	
	return mp_core_insert_meta_fields( $items_array, $new_fields, 'eventgrid_meta_hook_anchor_2' );

}
add_filter( 'mp_stacks_eventgrid_items_array', 'mp_stacks_eventgrid_date_meta_options', 11 );

/**
 * Add the placement options for the Date using placement options filter hook
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $post_id Int - The ID of the Brick
 * @return   Array - All of the placement optons needed for Date
 */
function mp_stacks_eventgrid_date_placement_options( $placement_options, $post_id ){
	
	//Show Post Dates
	$placement_options['date_show'] = mp_core_get_post_meta($post_id, 'eventgrid_date_show');

	//Dates Placement
	$placement_options['date_placement'] = mp_core_get_post_meta($post_id, 'eventgrid_date_placement', 'over_image_top_left');
	
	return $placement_options;	
}
add_filter( 'mp_stacks_eventgrid_placement_options', 'mp_stacks_eventgrid_date_placement_options', 10, 2 );

/**
 * Get the HTML for the date in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id Int - The ID of the post to get the date of
 * @return   $html_output String - A string holding the html for an date in the grid
 */
function mp_stacks_eventgrid_date( $post_id, $word_limit, $read_more_text, $mp_event_start_date, $mp_event_end_date ){
		
	$the_start_date = mp_events_format_mp_event_date( $mp_event_start_date );
	$the_start_time = mp_core_get_post_meta( $post_id, 'event_start_time' );
	$the_start_date_timestamp = strtotime( $mp_event_start_date );
	$the_start_date = date( get_option( 'date_format' ), $the_start_date_timestamp );
	$time_description = mp_core_get_post_meta( $post_id, 'event_time_description', $the_start_time );
	
	//Add the calendar-style date output (fancy)
	$the_fancy_date = '
	<div class="mp-stacks-eventgrid-item-date-holder">
		<div class="mp-stacks-eventgrid-fancy-date">
			<figure>
				<header>
					<span class="mp-stacks-eventgrid-month">' . date( 'M', $the_start_date_timestamp ) . '</span>
					<span class="mp-stacks-eventgrid-year">' . date( 'Y', $the_start_date_timestamp ) . '</span>
				</header>
				<section>' . date( 'j', $the_start_date_timestamp ) . '</section>
			</figure>';
	//Add the time to the fancy date	
	$the_fancy_date .=		
			'<div class="mp-stacks-eventgrid-fancy-time">
				<section>' . $time_description . '</section>
			</div>';
	
	//Finish the date output		
	$the_fancy_date .= '</div>
	</div>';

	$the_text_only_date = mp_stacks_grid_highlight_text_html( array( 
		'class_name' => 'mp-stacks-eventgrid-item-date',
		'output_string' => $the_start_date . ' ' . $time_description, 
	) );
	
	return $the_fancy_date;	

}

/**
 * Hook the Date to the "Top" and "Over" position in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id Int - The ID of the post
 * @return   $html_output String - A string holding the html for text over a featured image in the grid
 */
function mp_stacks_eventgrid_date_top_over_callback( $eventgrid_output, $grid_post_id, $options ){
	
	//If we should show the date over the image
	if ( strpos( $options['date_placement'], 'over') !== false && strpos( $options['date_placement'], 'top') !== false && $options['date_show']){
		
		return $eventgrid_output . mp_stacks_eventgrid_date( $grid_post_id, $options['word_limit'], $options['read_more_text'], $options['mp_event_start_date'], $options['mp_event_end_date'] );

	}
	
	return $eventgrid_output;
	
}
add_filter( 'mp_stacks_eventgrid_top_over', 'mp_stacks_eventgrid_date_top_over_callback', 14, 3 );

/**
 * Hook the Date to the "Middle" and "Over" position in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $post_id Int - The ID of the post
 * @return   $html_output String - A string holding the html for text over a featured image in the grid
 */
function mp_stacks_eventgrid_date_middle_over_callback( $eventgrid_output, $grid_post_id, $options ){
	
	//If we should show the date over the image
	if ( strpos( $options['date_placement'], 'over') !== false && strpos( $options['date_placement'], 'middle') !== false && $options['date_show']){
		
		return $eventgrid_output . mp_stacks_eventgrid_date( $grid_post_id, $options['word_limit'], $options['read_more_text'], $options['mp_event_start_date'], $options['mp_event_end_date'] );

	}
	
	return $eventgrid_output;
}
add_filter( 'mp_stacks_eventgrid_middle_over', 'mp_stacks_eventgrid_date_middle_over_callback', 14, 3 );

/**
 * Hook the Date to the "Bottom" and "Over" position in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $grid_post_id Int - The ID of the post
 * @return   $html_output String - A string holding the html for text over a featured image in the grid
 */
function mp_stacks_eventgrid_date_bottom_over_callback( $eventgrid_output, $grid_post_id, $options ){
	
	//If we should show the date over the image
	if ( strpos( $options['date_placement'], 'over') !== false && strpos( $options['date_placement'], 'bottom') !== false && $options['date_show']){
		
		return $eventgrid_output . mp_stacks_eventgrid_date( $grid_post_id, $options['word_limit'], $options['read_more_text'], $options['mp_event_start_date'], $options['mp_event_end_date'] );

	}
	
	return $eventgrid_output;
	
}
add_filter( 'mp_stacks_eventgrid_bottom_over', 'mp_stacks_eventgrid_date_bottom_over_callback', 14, 3 );

/**
 * Hook the Date to the "Below" position in the grid
 *
 * @access   public
 * @since    1.0.0
 * @param    $grid_post_id Int - The ID of the post
 * @return   $html_output String - A string holding the html for text over a featured image in the grid
 */
function mp_stacks_eventgrid_date_below_over_callback( $eventgrid_output, $grid_post_id, $options ){
	
	//If we should show the date below the image
	if ( strpos( $options['date_placement'], 'below') !== false && $options['date_show']){
		
		$date_html_output = '<a href="' . get_permalink() . '" class="mp-stacks-eventgrid-date-link">';	
			$date_html_output .= mp_stacks_eventgrid_date( $grid_post_id, $options['word_limit'], $options['read_more_text'], $options['mp_event_start_date'], $options['mp_event_end_date'] );
		$date_html_output .= '</a>';
		
		return $eventgrid_output . $date_html_output;
	}
	
	return $eventgrid_output;
	
}
add_filter( 'mp_stacks_eventgrid_below', 'mp_stacks_eventgrid_date_below_over_callback', 14, 3 );

/**
 * Add the JS for the date to EventGrid's HTML output
 *
 * @access   public
 * @since    1.0.0
 * @param    $existing_filter_output String - Any output already returned to this filter previously
 * @param    $post_id String - the ID of the Brick where all the meta is saved.
 * @param    $meta_prefix String - the prefix to put before each meta_field key to differentiate it from other plugins. :EG "eventgrid"
 * @return   $new_grid_output - the existing grid output with additional thigns added by this function.
 */
function mp_stacks_eventgrid_date_animation_js( $existing_filter_output, $post_id, $meta_prefix ){
	
	if ( $meta_prefix != 'eventgrid' ){
		return $existing_filter_output;	
	}
	
	//Get JS output to animate the dates on mouse over and out
	$date_animation_js = mp_core_js_mouse_over_animate_child( '#mp-brick-' . $post_id . ' .mp-stacks-grid-item', '.mp-stacks-eventgrid-item-date-holder', mp_core_get_post_meta( $post_id, 'eventgrid_date_animation_keyframes', array() ), true, true, 'mp-brick-' . $post_id ); 
	
	return $existing_filter_output .= $date_animation_js;
}
add_filter( 'mp_stacks_grid_js', 'mp_stacks_eventgrid_date_animation_js', 10, 3 );
		
/**
 * Add the CSS for the date to EventGrid's CSS
 *
 * @access   public
 * @since    1.0.0
 * @param    $css_output String - The CSS that exists already up until this filter has run
 * @return   $css_output String - The incoming CSS with our new CSS for the date appended.
 */
function mp_stacks_eventgrid_date_css( $css_output, $post_id ){
	
	$date_css_defaults = array(
		'color' => '#000',
		'size' => 13,
		'lineheight' => 13,
		'padding_top' => 10, //aka 'spacing'
		'background_padding' => 5,
		'background_color' => '#fff',
		'background_opacity' => 100,
		'placement_string' => 'over_image_top_left',
	);

	return $css_output .= mp_stacks_grid_text_css( $post_id, 'eventgrid_date', 'mp-stacks-eventgrid-item-date', $date_css_defaults );
}
add_filter('mp_stacks_eventgrid_css', 'mp_stacks_eventgrid_date_css', 10, 2);

/**
 * Add the Google Fonts for the Grid Titles
 *
 * @param    $css_output          String - The incoming CSS output coming from other things using this filter
 * @param    $post_id             Int - The post ID of the brick
 * @param    $first_content_type  String - The first content type chosen for this brick
 * @param    $second_content_type String - The second content type chosen for this brick
 * @return   $css_output          String - A string holding the css the brick
 */
function mp_stacks_eventgrid_date_google_font( $css_output, $post_id, $first_content_type, $second_content_type ){
	
	if ( $first_content_type != 'eventgrid' && $second_content_type != 'eventgrid' ){
		return $css_output;	
	}
	
	global $mp_stacks_footer_inline_css, $mp_core_font_families;
	
	//Default settings for the MP Core Google Font Class
	$mp_core_google_font_args = array( 'echo_google_font_css' => false, 'wrap_in_style_tags' => false );
	
	$eventgrid_date_googlefont = mp_core_get_post_meta( $post_id, 'eventgrid_date_google_font' );
	$eventgrid_date_googlefontweight = mp_core_get_post_meta( $post_id, 'eventgrid_date_google_font_weight_style' );
	
	//If a font name has been entered
	if ( !empty( $eventgrid_date_googlefont ) ){
		
		//Check if a font extra (weight) has been selected and add it if so.
		$eventgrid_date_googlefontweight = isset($eventgrid_date_googlefontweight) && !empty( $eventgrid_date_googlefontweight ) ? ':' . $eventgrid_date_googlefontweight : NULL;
		$eventgrid_date_googlefont = $eventgrid_date_googlefont . $eventgrid_date_googlefontweight;
	
		//Load the Google Font using the Google Font Class in MP Core
		new MP_CORE_Font( $eventgrid_date_googlefont, $eventgrid_date_googlefont, $mp_core_google_font_args );
		$mp_stacks_footer_inline_css[$eventgrid_date_googlefont] = $mp_core_font_families[$eventgrid_date_googlefont];
		
		//Return the incoming css string plus css to apply this font family to all paragraph tags
		$css_output .=  '#mp-brick-' . $post_id . ' .mp-stacks-eventgrid-item-date, #mp-brick-' . $post_id . ' .mp-stacks-eventgrid-item-date * { font-family: \'' . $eventgrid_date_googlefont . '\';}';
	
	}
	
	return $css_output;	
}
add_filter('mp_brick_additional_css', 'mp_stacks_eventgrid_date_google_font', 10, 4);	