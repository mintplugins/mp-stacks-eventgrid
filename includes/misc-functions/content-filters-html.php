<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks EventGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * This function hooks to the brick output. If it is supposed to be a 'eventgrid', then it will output the eventgrid
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_eventgrid( $default_content_output, $mp_stacks_content_type, $post_id ){
	
	//If this stack content type is NOT set to be a eventgrid	
	if ($mp_stacks_content_type != 'eventgrid'){
		
		return $default_content_output;
		
	}
	
	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$eventgrid_output = mp_stacks_eventgrid_output( $post_id );
	
	//Return
	return $eventgrid_output['eventgrid_output'] . $eventgrid_output['load_more_button'] . $eventgrid_output['eventgrid_after'];

}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_eventgrid', 10, 3);

/**
 * Output more posts using ajax
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_eventgrid_ajax_load_more(){
	
	if ( !isset( $_POST['mp_stacks_grid_post_id'] ) || !isset( $_POST['mp_stacks_grid_offset'] ) ){
		return;	
	}
	
	$post_id = $_POST['mp_stacks_grid_post_id'];
	$post_offset = $_POST['mp_stacks_grid_offset'];

	//Because we run the same function for this and for "Load More" ajax, we call a re-usable function which returns the output
	$eventgrid_output = mp_stacks_eventgrid_output( $post_id, true, $post_offset );
	
	echo json_encode( array(
		'items' => $eventgrid_output['eventgrid_output'],
		'button' => $eventgrid_output['load_more_button'],
		'animation_trigger' => $eventgrid_output['animation_trigger']
	) );
	
	die();
			
}
add_action( 'wp_ajax_mp_stacks_eventgrid_load_more', 'mp_eventgrid_ajax_load_more' );
add_action( 'wp_ajax_nopriv_mp_stacks_eventgrid_load_more', 'mp_eventgrid_ajax_load_more' );

/**
 * Run the Grid Loop and Return the HTML Output, Load More Button, and Animation Trigger for the Grid
 *
 * @access   public
 * @since    1.0.0
 * @param    Void
 * @param    $post_id Int - The ID of the Brick
 * @param    $loading_more string - If we are loading more through ajax, this will be true, Defaults to false.
 * @param    $post_offset Int - The number of posts deep we are into the loop (if doing ajax). If not doing ajax, set this to 0;
 * @return   Array - HTML output from the Grid Loop, The Load More Button, and the Animation Trigger in an array for usage in either ajax or not.
 */
function mp_stacks_eventgrid_output( $post_id, $loading_more = false, $post_offset = NULL ){
	
	global $wp_query;
	
	//Enqueue all js scripts used by grids.
	mp_stacks_grids_enqueue_frontend_scripts( 'eventgrid' );
	
	//If we are NOT doing ajax get the parent's post id from the wp_query.
	if ( !defined( 'DOING_AJAX' ) ){
		$queried_object_id = $wp_query->queried_object_id;
	}
	//If we are doing ajax, get the parent's post id from the AJAX-passed $_POST['mp_stacks_queried_object_id']
	else{
		$queried_object_id = isset( $_POST['mp_stacks_queried_object_id'] ) ? $_POST['mp_stacks_queried_object_id'] : NULL;
	}
	
	//Get this Brick Info
	$post = get_post($post_id);
	
	$eventgrid_output = NULL;
	
	//Get taxonomy term repeater (new way)
	$eventgrid_taxonomy_terms = mp_core_get_post_meta($post_id, 'eventgrid_taxonomy_terms', '');
	
	//Download per row
	$eventgrid_per_row = mp_core_get_post_meta($post_id, 'eventgrid_per_row', '3');
	
	//Download per page
	$eventgrid_per_page = mp_core_get_post_meta($post_id, 'eventgrid_per_page', '9');
	
	//Setup the WP_Query args
	$eventgrid_args = array(
		'order' => 'DESC',
		'paged' => 0,
		'post_status' => 'publish',
		'posts_per_page' => $eventgrid_per_page,
		'post__not_in' => array($queried_object_id),
		'tax_query' => array(
			'relation' => 'OR',
		)
	);
	
	//If we are using Offset
	if ( !empty( $post_offset ) ){
		//Add offset args to the WP_Query
		$eventgrid_args['offset'] = $post_offset;
	}
	//Alternatively, if we are using brick pagination
	else if ( isset( $wp_query->query['mp_brick_pagination_slugs'] ) ){
		
		//Get the brick slug
		$pagination_brick_slugs = explode( '|||', $wp_query->query['mp_brick_pagination_slugs'] );
		
		$pagination_brick_page_numbers = explode( '|||', $wp_query->query['mp_brick_pagination_page_numbers'] );
		
		$brick_pagination_counter = 0;
	
		//Loop through each brick in the url which has pagination
		foreach( $pagination_brick_slugs as $brick_slug ){
			//If this brick is the one we want to paginate
			if ( $brick_slug == $post->post_name ){
				//Add page number to the WP_Query
				$eventgrid_args['paged'] = $pagination_brick_page_numbers[$brick_pagination_counter];
				//Set the post offset variable to start at the end of the current page
				$post_offset = isset( $eventgrid_args['paged'] ) ? ($eventgrid_args['paged'] * $eventgrid_per_page) - $eventgrid_per_page : 0;
			}
			
			//Increment the counter which aligns $pagination_brick_page_numbers to $pagination_brick_slugs
			$brick_pagination_counter = $brick_pagination_counter + 1;
		}
		
	}
	
	//If there are tax terms selected to show and one of those is "All Events"
	if ( is_array( $eventgrid_taxonomy_terms ) && !empty( $eventgrid_taxonomy_terms[0]['taxonomy_term'] ) && $eventgrid_taxonomy_terms[0]['taxonomy_term'] == 'all_mp_event' ){
			$eventgrid_args['post_type'] = 'mp_event';
	}
	//Otherwise, if no "All" has been selected, if there are specific tax terms selected to show
	else if ( is_array( $eventgrid_taxonomy_terms ) && !empty( $eventgrid_taxonomy_terms[0]['taxonomy_term'] ) ){
		
		//Loop through each term the user added to this eventgrid
		foreach( $eventgrid_taxonomy_terms as $eventgrid_taxonomy_term ){
		
			//Show an event category of the users choosing
			
			//Explode the term and the tax name apart
			$taxonomy_explode = explode( '*', $eventgrid_taxonomy_term['taxonomy_term'] );
			$taxonomy_term_id = isset( $taxonomy_explode[0] ) ? $taxonomy_explode[0] : NULL;
			$taxonomy_name = isset( $taxonomy_explode[1] ) ? $taxonomy_explode[1] : NULL;
			
			//Add the category we want to show to the WP_Query
			$eventgrid_args['tax_query'][] = array(
				'taxonomy' => $taxonomy_name,
				'field'    => 'id',
				'terms'    => $taxonomy_term_id,
				'operator' => 'IN'
			);		
			
		}
	}
	else{
		return false;	
	}
	
	//Show Download Images?
	$eventgrid_featured_images_show = mp_core_get_post_meta_checkbox($post_id, 'eventgrid_featured_images_show', true);
	
	//Download Image width and height
	$eventgrid_featured_images_width = mp_core_get_post_meta( $post_id, 'eventgrid_featured_images_width', 500 );
	$eventgrid_featured_images_height = mp_core_get_post_meta( $post_id, 'eventgrid_featured_images_height', 0 );
	
	//Get the options for the grid placement - we pass this to the action filters for text placement
	$grid_placement_options = apply_filters( 'mp_stacks_eventgrid_placement_options', NULL, $post_id );
	
	//Get the JS for animating items - only needed the first time we run this - not on subsequent Ajax requests.
	if ( !$loading_more ){
		
		//Here we set javascript for this grid
		$eventgrid_output .= apply_filters( 'mp_stacks_grid_js', NULL, $post_id, 'eventgrid' );
		
	}
	
	//Add HTML that sits before the "grid" div
	$eventgrid_output .= !$loading_more ? apply_filters( 'mp_stacks_grid_before', NULL, $post_id, 'eventgrid', $eventgrid_taxonomy_terms ) : NULL; 
	
	//Get Download Output
	$eventgrid_output .= !$loading_more ? '<div class="mp-stacks-grid ' . apply_filters( 'mp_stacks_grid_classes', NULL, $post_id, 'eventgrid' ) . '">' : NULL;
			
	//Create new query for stacks
	$eventgrid_query = new WP_Query( apply_filters( 'eventgrid_args', $eventgrid_args ) );
	
	$css_output = NULL;
	
	//Default this to 0. If there are posts to show, load the "Load More" button. If not, no load more button.
	$total_posts = 0;
	
	//Loop through the stack group		
	if ( $eventgrid_query->have_posts() ) { 
		
		//This is the total number of events for which the "load more" button will show.
		$total_posts = 99999999999999999;
		
		while( $eventgrid_query->have_posts() ) : $eventgrid_query->the_post(); 
		
				$grid_post_id = get_the_ID();
										
				//Reset Grid Classes String
				$source_counter = 0;
				$post_source_num = NULL;
				$grid_item_inner_bg_color = NULL;
				
				//If there are multiple tax terms selected to show
				if ( is_array( $eventgrid_taxonomy_terms ) && !empty( $eventgrid_taxonomy_terms[0]['taxonomy_term'] ) ){					
					
					//Loop through each term the user added to this eventgrid
					foreach( $eventgrid_taxonomy_terms as $eventgrid_taxonomy_term ){
																		
						//If the current post has this term, make that term one of the classes for the grid item
						if ( has_term( $eventgrid_taxonomy_term['taxonomy_term'], 'event_category', $grid_post_id ) ){
							
							//Store the source this post belongs to
							$post_source_num = $source_counter;
														
							if ( !empty( $eventgrid_taxonomy_term['taxonomy_bg_color'] ) ){
								$grid_item_inner_bg_color = $eventgrid_taxonomy_term['taxonomy_bg_color'];
							}
							
						}
						
						$source_counter = $source_counter + 1;
						
					}
				}
				
				//Add our custom classes to the grid-item 
				$class_string = 'mp-stacks-grid-source-' . $post_source_num . ' mp-stacks-grid-item mp-stacks-grid-item-' . $grid_post_id . ' ';
				//Add all posts that would be added from the post_class wp function as well
				$class_string = join( ' ', get_post_class( $class_string, $grid_post_id ) );
				$class_string = apply_filters( 'mp_stacks_grid_item_classes', $class_string, $post_id, 'eventgrid' ); 
				
				//Get the Grid Item Attributes
				$grid_item_attribute_string = apply_filters( 'mp_stacks_grid_attribute_string', NULL, $eventgrid_taxonomy_terms, $grid_post_id, $post_id, 'eventgrid', $post_source_num );
				
				$eventgrid_output .= '<div class="' . $class_string . '" ' . $grid_item_attribute_string . '>';
					$eventgrid_output .= '<div class="mp-stacks-grid-item-inner" ' . (!empty( $grid_item_inner_bg_color ) ? 'mp-default-bg-color="' . $grid_item_inner_bg_color . '"' : NULL) . '>';
					
					//Add htmloutput directly inside this grid item
					$eventgrid_output .= apply_filters( 'mp_stacks_grid_inside_grid_item_top', NULL, $eventgrid_taxonomy_terms, $post_id, 'eventgrid', $grid_post_id, $post_source_num );
										
					//Microformats
					$eventgrid_output .= '
					<article class="microformats hentry" style="display:none;">
						<h2 class="entry-title">' . get_the_title() . '</h2>
						<span class="author vcard"><span class="fn">' . get_the_author() . '</span></span>
						<time class="published" datetime="' . get_the_time('Y-m-d H:i:s') . '">' . get_the_date() . '</time>
						<time class="updated" datetime="' . get_the_modified_date('Y-m-d H:i:s') . '">' . get_the_modified_date() .'</time>
						<div class="entry-summary">' . mp_core_get_excerpt_by_id($grid_post_id) . '</div>
					</article>';
					
					//If we should show the featured images
					if ($eventgrid_featured_images_show){
						
						$eventgrid_output .= '<div class="mp-stacks-grid-item-image-holder">';
							
							$link = get_permalink();							
							$lightbox_link = mp_core_add_query_arg( array( 'mp_eventgrid_lightbox' => true ), $link );	
							$non_lightbox_link = $link;
							$lightbox_class = 'mp-stacks-iframe-height-match-lightbox-link';
							$target = 'mfp-width="1290px"';
							$mp_event_start_date = NULL;
							$mp_event_end_date = NULL;
							
							//Create variables for each URL variable (really we want the mp_event_start_date URL variable).
							wp_parse_str( $link, $link_url_variables );		
							foreach( $link_url_variables as $link_url_variable_key => $link_url_variable_value ){
								
								//If this URL variable has mp_event_start_date in it
								if ( strpos( $link_url_variable_key, 'mp_event_start_date' ) !== false ){
									$mp_event_start_date = $link_url_variable_value;
								}
								
								//If this URL variable has mp_event_end_date in it
								if ( strpos( $link_url_variable_key, 'mp_event_end_date' ) !== false ){
									$mp_event_end_date = $link_url_variable_value;
								}
							}
							
							//If the mp_event_start_date wasn't in the URL, get it from the post meta directly
							if ( !isset( $mp_event_start_date ) || empty( $mp_event_start_date ) ){
								$mp_event_start_date = mp_events_get_mp_event_start_date( $post_id );	
							}
							
							//If the mp_event_end_date wasn't in the URL, get it from the post meta directly
							if ( !isset( $mp_event_end_date ) || empty( $mp_event_end_date ) ){
								$mp_event_end_date = mp_events_get_mp_event_end_date( $post_id );	
							}
							
							$grid_placement_options['mp_event_start_date'] = $mp_event_start_date;
							$grid_placement_options['mp_event_end_date'] = $mp_event_end_date;
				
							$eventgrid_output .= '<a mp_lightbox_alternate_url="' . $lightbox_link . '" href="' . $non_lightbox_link . '" class="' . $lightbox_class . '" ' . $target . ' class="mp-stacks-grid-image-link" title="' . the_title_attribute( 'echo=0' ) . '" alt="' . the_title_attribute( 'echo=0' ) . '">';
							
							$eventgrid_output .= '<div class="mp-stacks-grid-item-image-overlay"></div>';
							
							//Get the featured image and crop according to the user's specs
							if ( $eventgrid_featured_images_height > 0 && !empty( $eventgrid_featured_images_height ) ){
								$featured_image = mp_core_the_featured_image($grid_post_id, $eventgrid_featured_images_width, $eventgrid_featured_images_height);
							}
							else{
								$featured_image = mp_core_the_featured_image( $grid_post_id, $eventgrid_featured_images_width );	
							}
							 
							$eventgrid_output .= '<img src="' . $featured_image . '" class="mp-stacks-grid-item-image" title="' . the_title_attribute( 'echo=0' ) . '" alt="' . the_title_attribute( 'echo=0' ) . '" />';
							
							//Top Over
							$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-top">';
							
								$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table">';
								
									$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table-cell">';
																				
										//Filter Hook to output HTML into the "Top" and "Over" position on the featured Image
										$eventgrid_output .= apply_filters( 'mp_stacks_eventgrid_top_over', NULL, $grid_post_id, $grid_placement_options );
									
									$eventgrid_output .= '</div>';
									
								$eventgrid_output .= '</div>';
							
							$eventgrid_output .= '</div>';
							
							//Middle Over
							$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-middle">';
							
								$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table">';
								
									$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table-cell">';
									
										//Filter Hook to output HTML into the "Middle" and "Over" position on the featured Image
										$eventgrid_output .= apply_filters( 'mp_stacks_eventgrid_middle_over', NULL, $grid_post_id, $grid_placement_options );
									
									$eventgrid_output .= '</div>';
									
								$eventgrid_output .= '</div>';
							
							$eventgrid_output .= '</div>';
							
							//Bottom Over
							$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-bottom">';
							
								$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table">';
								
									$eventgrid_output .= '<div class="mp-stacks-grid-over-image-text-container-table-cell">';
										
										//Filter Hook to output HTML into the "Bottom" and "Over" position on the featured Image
										$eventgrid_output .= apply_filters( 'mp_stacks_eventgrid_bottom_over', NULL, $grid_post_id, $grid_placement_options );
									
									$eventgrid_output .= '</div>';
									
								$eventgrid_output .= '</div>';
							
							$eventgrid_output .= '</div>';
							
							$eventgrid_output .= '</a>';
							
						$eventgrid_output .= '</div>';
						
					}
					
					//Below Image Area Container:
					$eventgrid_output .= '<div class="mp-stacks-grid-item-below-image-holder">';
					
						//Filter Hook to output HTML into the "Below" position on the featured Image
						$eventgrid_output .= apply_filters( 'mp_stacks_eventgrid_below', NULL, $grid_post_id, $grid_placement_options );
				
					$eventgrid_output .= '</div>';
				
				$eventgrid_output .= '</div></div>';
								
				//Increment Offset
				$post_offset = $post_offset + 1;
				
		endwhile;
	}
	
	//If we're not doing ajax, add the stuff to close the eventgrid container and items needed after
	if ( !$loading_more ){
		$eventgrid_output .= '</div>';
	}
	
	
	//jQuery Trigger to reset all eventgrid animations to their first frames
	$animation_trigger = '<script type="text/javascript">jQuery(document).ready(function($){ $(document).trigger("mp_core_animation_set_first_keyframe_trigger"); });</script>';
	
	//Assemble args for the load more output
	$load_more_args = array(
		 'meta_prefix' => 'eventgrid',
		 'total_posts' => $total_posts, 
		 'posts_per_page' => $eventgrid_per_page, 
		 'paged' => $eventgrid_args['paged'], 
		 'post_offset' => $post_offset,
		 'brick_slug' => $post->post_name
	);
	
	return array(
		'eventgrid_output' => $eventgrid_output,
		'load_more_button' => apply_filters( 'mp_stacks_eventgrid_load_more_html_output', $load_more_html = NULL, $post_id, $load_more_args ),
		'animation_trigger' => $animation_trigger,
		'eventgrid_after' => '<div class="mp-stacks-grid-item-clearedfix"></div><div class="mp-stacks-grid-after"></div>'
	);
		
}