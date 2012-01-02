<?php

class DP_Cache {
        
    function set($key, $data) {
        $_SESSION["#$@" . $_SERVER['SERVER_NAME'] . $key] = base64_encode(serialize($data));
    }

    
    function get($key) { 	
        return unserialize(base64_decode($_SESSION["#$@" . $_SERVER['SERVER_NAME'] . $key]));
    }

        
    function delete($key) {
        unset($_SESSION["#$@" . $_SERVER['SERVER_NAME'] . $key]);
    }
    
    function get_statistics(){   	
    	$count = 0;
    	foreach ($_SESSION as $key => $value){
    		if(strpos($key,"#$@" . $_SERVER['SERVER_NAME']) !== false){
    			$count++;
    		}
    	}
    	return $count;
    }
    
    function get_sessions_number(){
    	global $wpdb;
    	global $USE_DB_SESSION_MANAGER;
    	if($USE_DB_SESSION_MANAGER){
    		$tablename = $wpdb->prefix . "sessions";
    		return $wpdb->get_var("SELECT COUNT(*) FROM " . $tablename);
    	}
    	else{
    		$this->get_statistics();
    	}
    }
    
    function get_all_values(){   	
    	$cached_element = array();
    	foreach ($_SESSION as $key => $value){
    		if(strpos($key,"#$@" . $_SERVER['SERVER_NAME']) !== false){
    			$cached_element[str_replace("#$@" . $_SERVER['SERVER_NAME'], "", $key)] = $value; 
    		}
    	}
    	return $cached_element;
    }
    
    function get_all_sessions(){
    	global $wpdb;
    	global $USE_DB_SESSION_MANAGER;
    	$result;
    	if($USE_DB_SESSION_MANAGER){
    		$tablename = $wpdb->prefix . "sessions";
    		dpscache_delete_sessions_expired($tablename);
    		$query = "select id, expire, ip from " . $tablename;
    		$result = $wpdb->get_results($query, ARRAY_A);
    	}
    	return $result;
    }
    
    function invalidate_single_session($sessid){
    	global $wpdb;
    	global $USE_DB_SESSION_MANAGER;
    	if($USE_DB_SESSION_MANAGER){
    		$tablename = $wpdb->prefix . "sessions";
    		$query = "delete from " . $tablename . " where id=%s";
    		$wpdb->query($wpdb->prepare($query,$sessid));
    	}
    }
    
    function contais($key){
    	
    	if(count($_SESSION)<1)
    		return false;
    	return array_key_exists("#$@" . $_SERVER['SERVER_NAME'] . $key, $_SESSION);
    }
    
    function flush($all=false){
    	global $wpdb;
    	global $USE_DB_SESSION_MANAGER;
    	if(!$all || !$USE_DB_SESSION_MANAGER){  	
    		foreach ($_SESSION as $key => $value){
    			if(strpos($key,"#$@" . $_SERVER['SERVER_NAME']) !== false){
    				unset($_SESSION[$key]);
    			}
    		}
    	}
    	else {
    		$date = new DateTime();
			$tablename = $wpdb->prefix . "sessions";
			$query = "truncate table " . $tablename;
			$wpdb->query($query);
    	}
    }
    
    function inspect(){
    	print_r($_SESSION);
    }

}

global $dpcache;
$dpcache = new DP_Cache();