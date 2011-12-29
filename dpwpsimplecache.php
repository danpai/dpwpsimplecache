<?php
/**
 * @package dpwpsimplecache
 * @version 0.1
 */
/*
Plugin Name: DP Simple Cache
Plugin URI: https://github.com/danpai/dpwpsimplecache
Description: WordPress plugin to implement a simple cache of objects
Author: Danilo Paissan
Version: 0.1
Author URI: http://danilopaissan.net
License: W3C Software Notice and License 

http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231

By obtaining, using and/or copying this work, you (the licensee) agree that you have read, understood, and will comply with the following terms and conditions.

Permission to copy, modify, and distribute this software and its documentation, with or without modification, for any purpose and without fee or royalty is hereby granted, provided that you include the following on ALL copies of the software and documentation or portions thereof, including modifications:

- The full text of this NOTICE in a location viewable to users of the redistributed or derivative work.
- Any pre-existing intellectual property disclaimers, notices, or terms and conditions. If none exist, the W3C Software Short Notice should be included (hypertext is preferred, text is permitted) within the body of any redistributed or derivative code.
- Notice of any changes or modifications to the files, including the date changes were made. (We recommend you provide URIs to the location from which the code is derived.)
*/

require_once(ABSPATH . 'wp-admin/includes/misc.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(plugin_dir_path( __FILE__ ) . 'dpwpcacheclass.php');
require_once(plugin_dir_path( __FILE__ ) . 'pagesrender.php');

function dpscache_install() {}

function dpscache_deactivate() {
	global $dpcache;
	if(!isset( $dpcache ))
		return;
	$dpcache->flush();
}

function dpscache_uninstall() {}

function dpscache_add_menu(){
	add_options_page( 'DP Simple Cache', 'Simple Cache','manage_options', __FILE__, 'dpscache_manage_option_page' );
}

function dpscache_admin_init() {
	if (!session_id())
		session_start();
}

add_action('init', 'dpscache_admin_init');
add_action( 'admin_menu', 'dpscache_add_menu' );
register_activation_hook( __FILE__, 'dpscache_install' );
register_deactivation_hook( __FILE__, 'dpscache_deactivate' );
register_uninstall_hook( __FILE__, 'dpscache_uninstall');
?>