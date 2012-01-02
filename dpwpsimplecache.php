<?php
/**
 * @package dpwpsimplecache
 * @version 0.4
 */
/*
Plugin Name: DP Simple Cache
Plugin URI: https://github.com/danpai/dpwpsimplecache
Description: WordPress plugin to implement a simple cache of objects
Author: Danilo Paissan
Version: 0.4
Author URI: http://danilopaissan.net
License: W3C Software Notice and License 

http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231

By obtaining, using and/or copying this work, you (the licensee) agree that you have read, understood, and will comply with the following terms and conditions.

Permission to copy, modify, and distribute this software and its documentation, with or without modification, for any purpose and without fee or royalty is hereby granted, provided that you include the following on ALL copies of the software and documentation or portions thereof, including modifications:

- The full text of this NOTICE in a location viewable to users of the redistributed or derivative work.
- Any pre-existing intellectual property disclaimers, notices, or terms and conditions. If none exist, the W3C Software Short Notice should be included (hypertext is preferred, text is permitted) within the body of any redistributed or derivative code.
- Notice of any changes or modifications to the files, including the date changes were made. (We recommend you provide URIs to the location from which the code is derived.)
*/

global $USE_DB_SESSION_MANAGER;
$USE_DB_SESSION_MANAGER = 1;

require_once(ABSPATH . 'wp-admin/includes/misc.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(plugin_dir_path( __FILE__ ) . 'dpwpcacheclass.php');
require_once(plugin_dir_path( __FILE__ ) . 'pagesrender.php');

function dpscache_install() {
	dpscache_create_session_table();
}

function dpscache_deactivate() {
	global $dpcache;
	if(!isset( $dpcache ))
		return;
	$dpcache->flush(true);
}

function dpscache_uninstall() {}

function dpscache_add_menu(){
	add_options_page( 'DP Simple Cache', 'Simple Cache','manage_options', 'simple_cache', 'dpscache_manage_option_page' );
}

function dpscache_init() {	
	global $wpdb;
	global $USE_DB_SESSION_MANAGER;
	$tablename = $wpdb->prefix . "sessions";
	session_start();
	$session_name = session_id();
	if($USE_DB_SESSION_MANAGER){
		$session_exist = dpscache_update_session($session_name, $tablename);
		if(!$session_exist){
			session_destroy();
			dpscache_create_session($tablename);
		}
	}
}

function dpscache_create_session($tablename){
	global $wpdb;
	session_start();
	$session_name = session_id();
	$date = new DateTime();
	//delete expired sessions
	$date->modify("+10 minutes");	    
	$query = "insert into " . $tablename . " (id,expire,ip) values (%s,'" . $date->format("Y-m-d H:i:s") . "',%s)";
	$wpdb->query($wpdb->prepare($query,$session_name,$_SERVER['REMOTE_ADDR']));
}

function dpscache_delete_sessions_expired($tablename){
	global $wpdb;
	$date = new DateTime();
	$query = "delete from " . $tablename . " where expire < '" . $date->format("Y-m-d H:i:s") . "'";
	$wpdb->query($query);
}

function dpscache_update_session($session_name,$tablename){
	global $wpdb;
	
	dpscache_delete_sessions_expired($tablename);
	
	$session_found = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " . $tablename . " where id='" . $session_name . "'" ) );
	
	if(!$session_found)
		return false;
	
	$date = new DateTime();
	$date->modify("+10 minutes");
	$query = "update " . $tablename . " set expire = '" . $date->format("Y-m-d H:i:s") . "', ip=%s where id='" . $session_name . "'";
	$wpdb->query($wpdb->prepare($query,$_SERVER['REMOTE_ADDR']));
	return true;
}

function dpscache_create_session_table(){
	global $wpdb;

    $tablename = $wpdb->prefix . "sessions";
    
    if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ) {
		$sql = "CREATE TABLE " . $tablename . " (
  					id varchar(255) NOT NULL,
  					expire datetime NOT NULL,
  					ip varchar(15) DEFAULT NULL,
  					UNIQUE KEY id (id)
				)";
			
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    	dbDelta($sql);
	}
}

function dpscache_active_users(){
	global $dpcache;
	echo $dpcache->get_sessions_number();
}

add_action('init', 'dpscache_init');
add_action( 'admin_menu', 'dpscache_add_menu' );
register_activation_hook( __FILE__, 'dpscache_install' );
register_deactivation_hook( __FILE__, 'dpscache_deactivate' );
register_uninstall_hook( __FILE__, 'dpscache_uninstall');
?>