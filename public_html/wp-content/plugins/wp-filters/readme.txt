=== WP-Filters ===
Contributors: gobanana
Tags: filter, filters, dynamic, live, sort, sorting
Requires at least: 2.0
Tested up to: 3.1
Stable tag: 1.0

A live post filtering plugin. Choose which categories to show in the filter and watch the post show/hide without reloading.

== Description ==

This plugin will output a list of your chosen categories and filter the posts on the fly using jQuery animation.

The setting pages allows you to select which categories should appear, whether or not to include jQuery (if you don't already have it in the <head>) and a place to define the posts container. This should be either the class or id of the div that contains all the posts for filtering.

**I am open to suggestions to improve the plugin !**

== Installation ==

1. Upload the wp-filters folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php filters_generate(); ?>` in your templates where you want the filters to appear.

You can find some settings on "Settings"/"WP-Filters" page in your admin panel.

== Frequently Asked Questions ==



== Screenshots ==

No screenshots yet.

== Changelog ==
= 1.1 =
Some minor clean up. 
- Instead of listing the filters using wp_list_categories, the categories are now manually printed with the href being the slug of the category.
- Fixed some jQuery issues
- On load, the selected filter (if any) gets assigned the class "current"
- Created a demo page: http://gobanana.ca/wp-filters/wp-filters-demo

= 1.0 =
Launch!

== Plugin Site ==
http://gobanana.ca/wp-filters