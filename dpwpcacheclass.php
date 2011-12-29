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
    
    function get_all_values(){
    	
    	$cached_element = array();
    	foreach ($_SESSION as $key => $value){
    		if(strpos($key,"#$@" . $_SERVER['SERVER_NAME']) !== false){
    			$cached_element[str_replace("#$@" . $_SERVER['SERVER_NAME'], "", $key)] = $value; 
    		}
    	}
    	return $cached_element;
    }
    
    function contais($key){
    	
    	if(count($_SESSION)<1)
    		return false;
    	return array_key_exists("#$@" . $_SERVER['SERVER_NAME'] . $key, $_SESSION);
    }
    
    function flush(){
    	
    	foreach ($_SESSION as $key => $value){
    		if(strpos($key,"#$@" . $_SERVER['SERVER_NAME']) !== false){
    			unset($_SESSION[$key]);
    		}
    	}
    }
    
    function inspect(){
    	print_r($_SESSION);
    }

}

global $dpcache;
$dpcache = new DP_Cache();