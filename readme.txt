=== Replace Google Fonts with Bunny Fonts ===
Contributors: antonioleutsch
Donate link: https://paypal.me/antonioleutsch
Tags: google fonts, bunny fonts, replace, gdpdr, dsgvo
Requires at least: 4.5
Tested up to: 6.0.1
Requires PHP: 5.6
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replace Google Fonts with Bunny Fonts in the HTML Markup of your WordPress site.

== Description ==

Replace Google Fonts with Bunny Fonts in the HTML Markup of your WordPress site.
It also replaces the preconnects with the correct ones.

To disable creating a preconnect to fonts.bunny.net, add the following line to your functions.php:
`add_filter('al_bunny_insert_al_bunny_preconnect', '__return_false');`

To disable removing the google fonts preconnect, add the following line to your functions.php:
`add_filter('al_bunny_remove_google_preconnect', '__return_false');`


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0 =
* initial release