<?php
/**
 * This file contains the function keeps the MP Stacks EventGrid plugin up to date.
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
 * Check for updates for the MP Stacks EventGrid Plugin by creating a new instance of the MP_CORE_Plugin_Updater class.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
 if (!function_exists('mp_stacks_eventgrid_update')){
	function mp_stacks_eventgrid_update() {
		$args = array(
			'software_name' => 'MP Stacks + EventGrid', //<- The exact name of this Plugin.
			'software_api_url' => 'http://mintplugins.com',//The URL where EventGrid and mp_repo are installed and checked
			'software_filename' => 'mp-stacks-eventgrid.php',
			'software_licensed' => false, //<-Boolean
		);
		
		//Since this is a plugin, call the Plugin Updater class
		$mp_stacks_features_plugin_updater = new MP_CORE_Plugin_Updater($args);
	}
 }
add_action( 'init', 'mp_stacks_eventgrid_update' );
