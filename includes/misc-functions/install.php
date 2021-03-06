<?php
/**
 * Installaion hooks for MP Stacks + EventGrid
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package     MP Stacks + EventGrid
 * @subpackage  Functions
 *
 * @copyright   Copyright (c) 2015, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the sample stack page,
 * flushing rewrite rules to initiate the new 'stacks' slug.<br />
 * After successful install, the user is redirected to the MP Stacks Welcome
 * screen.
 *
 * @since 1.0
 * @global $wpdb
 * @global $mp_stacks_eventgrid_options
 * @global $wp_version
 * @return void
 */
function mp_stacks_eventgrid_install() {
	global $wp_version, $mp_stacks_eventgrid_activation, $wp_rewrite;
	
	//Call flush_rules() as a method of the $wp_rewrite object. This will refresh the permalinks.
	$wp_rewrite->flush_rules();
	
	$active_theme = wp_get_theme();
	
	//Notify
	wp_remote_post( 'http://tracking.mintplugins.com', array(
		'method' => 'POST',
		'timeout' => 1,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => array( 
			'mp_track_event' => true, 
			'event_product_title' => 'MP Stacks + EventGrid', 
			'event_action' => 'activation', 
			'event_url' => get_bloginfo( 'wpurl'),
			'wp_version' => $wp_version,
			'active_plugins' => json_encode( get_option('active_plugins') ),
			'active_theme' => $active_theme->get( 'Name' ),
		),
		'cookies' => array()
		)
	);
	
	$mp_stacks_eventgrid_activation = true;

}
register_activation_hook( MP_STACKS_EVENTGRID_PLUGIN_FILE, 'mp_stacks_eventgrid_install' );

/**
 * Runs in the shutdown function upon activation so we can flush the rewrite rules after the new ones have been added.
 *
 * @since 1.0
 * @global $wpdb
 * @global $mp_stacks_eventgrid_activation
 * @return void
 */
function mp_stacks_eventgrid_activation_shutdown(){
	
	global $mp_stacks_eventgrid_activation;
	
	if ( $mp_stacks_eventgrid_activation ){
		//Flush the rewrite rules
		flush_rewrite_rules();
	}
	
}
add_action( 'shutdown', 'mp_stacks_eventgrid_activation_shutdown' );