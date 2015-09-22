<?php
/**
 * This file contains a function which checks if the MP Events plugin is installed.
 *
 * @since 1.0.0
 *
 * @package    MP Core
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
* Check to make sure the MP Events Plugin is installed (if other supported events plugins don't exist as well.
*
* @since    1.0.0
* @link     http://mintplugins.com/doc/plugin-checker-class/
* @return   array $plugins An array of plugins to be installed. This is passed in through the mp_core_check_plugins filter.
* @return   array $plugins An array of plugins to be installed. This is passed to the mp_core_check_plugins filter. (see link).
*/
if (!function_exists('mp_events_plugin_check') && !class_exists( 'Church_Theme_Content' ) ){
	function mp_events_plugin_check( $plugins ) {
		
		$add_plugins = array(
			array(
				'plugin_name' => 'MP Events',
				'plugin_message' => __('You require the MP Events plugin. Install it here.', 'mp_events_eventgrid'),
				'plugin_filename' => 'mp-events.php',
				'plugin_download_link' => 'http://mintplugins.com/repo/mp-events/?downloadfile=true',
				'plugin_info_link' => 'http://mintplugins.com/plugins/mp-events',
				'plugin_group_install' => true,
				'plugin_required' => true,
				'plugin_wp_repo' => true,
			)
		);
		
		return array_merge( $plugins, $add_plugins );
	}
}
add_filter( 'mp_core_check_plugins', 'mp_events_plugin_check' );