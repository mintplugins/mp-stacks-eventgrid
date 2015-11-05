<?php
/**
 * This file contains the enqueue scripts function for the eventgrid plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks + EventGrid
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Make EventGrid Content Type Centered by default
 *
 * @access   public
 * @since    1.0.0
 * @param    $centered_content_types array - An array containing a string for each content-type that should default to centered brick alignment.
 * @param    $centered_content_types array - An array containing a string for each content-type that should default to centered brick alignment.
 */
function mp_stacks_eventgrid_centered_by_default( $centered_content_types ){
	
	$centered_content_types['eventgrid'] = 'eventgrid';
	
	return $centered_content_types;
	
}
add_filter( 'mp_stacks_centered_content_types', 'mp_stacks_eventgrid_centered_by_default' );

/**
* Change the mp_events url slug from "events" to "mp-events" so that the user can have a page called "events" without it changing to "events-2".
*/
function mp_stacks_eventgrid_mp_events_slug( $args ) {

	// Arguments
	$args['rewrite']['slug'] = 'mp-events';
	
	return $args;		

}
add_filter( 'mp_events_post_type_args', 'mp_stacks_eventgrid_mp_events_slug' ); 