=== Advanced Custom Fields - Menu Field add-on ===
Contributors: chriswessels
Donate link: 
Tags: acf,acf-menu,acf-menu-field
Requires at least: 3.3
Tested up to: 3.4
Stable tag: 1.0.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This add-on creates an Advanced Custom Fields field type that renders a select element, containing the items in the associated menu.

== Description ==

This is an add-on for the [Advanced Custom Fields](http://www.advancedcustomfields.com/) WordPress plugin and will not provide any functionality to WordPress unless Advanced Custom Fields is installed and activated.

This add-on creates an Advanced Custom Fields field type that, when associated with a WordPress Menu, renders a single or optionally, a multi select element, containing the items in the menu.

The user selection is returned as a plain text string, or, if the field is configured as a multi select, as an array.

== Installation ==

1. Upload the `acf-menu-field` directory to the `/wp-content/plugins/` directory found in your web root.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Create a new custom field with the type 'WordPress Menu'.

There is no need to use ACF's `register_field();` method for this field. This plugin will, if ACF is installed and actived, register the field automatically.

You can also include this plugin directly in your theme by using `require('path_to_menu_field.php');`.

== Changelog ==

**1.0.0**
Initial release.