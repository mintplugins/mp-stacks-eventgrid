=== MP Stacks + EventGrid ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.4
Stable tag: 1.0.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add-On Plugin for MP Stacks which shows a grid of Events in a Brick. 

== Description ==

Extremely simple to set up - allows you to show Events on any page, at any time, anywhere on your website. Just put make a brick using “MP Stacks”, put the stack on a page, and set the brick’s Content-Type to be “EventGrid”.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks-eventgrid’ folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Build Bricks under the “Stacks and Bricks” menu. 
4. Publish your bricks into a “Stack”.
5. Put Stacks on pages using the shortcode or the “Add Stack” button.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/doc/mp-stacks

== Screenshots ==


== Changelog ==

= 1.0.0.4 = August 18, 2015
* Change taxonomy name to actual taxonomy of mp_calendars in content-filters-html.php. This fixes the wp_list_pluck bug which was introduced because of WordPress 4.6.
* Removed calls to undefined variable the_start_date_timestamp
* Flush rewrite rules upon installation

= 1.0.0.3 = February 21, 2015
* Fix for images smaller than 500px wide
* Add Google Font Control for all Grid Text elements.
* Make Event Descriptions have proper spacing in event lightbox.

= 1.0.0.2 = November 10, 2015
* Do Shortcodes in Event Body in Lightbox Popup.
* Make titles underneath the grid images load in the lightbox.
* Event grid does not require CTC plugin so it is removed.

= 1.0.0.1 = November 4, 2015
* Font Awesome is now pulled from MP Stacks
* Added support for "From Scratch" Isotope Filtering behavior
* Isotope Filter Groups properly set to use mp_calendar taxonomy
* Override the mp_events "event" rewrite slug so that users can use a page called "events" without a conflict
* Events per row are now passed through the mp_stacks_grid_posts_per_row_percentage function.

= 1.0.0.0 = September 21, 2015
* Original release
