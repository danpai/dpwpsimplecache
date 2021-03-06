DP Simple Cache
===============

D(ifferent)P(lace) Simple Cache is a WordPress plugin to implement a simple cache of objects at session level.

Versions
--------
###Rel. 0.1
This version is released only for testing purposes. It can be used as long as you know what you are doing.

###Rel. 0.2
This version add a custom table for session management. It can be used as long as you know what you are doing.

###Rel. 0.3
SQL errors fixed

###Rel. 0.3.1
SQL errors fixed

###Rel. 0.3.2
Algorithm fixed

###Rel. 0.4
Single session level management

Known Issues
------------
###Rel. 0.1
* None

###Rel. 0.2
* Error in session delete query

###Rel. 0.3
* Error in session delete query

###Rel. 0.3.1
* Error in session delete query 

###Rel. 0.3.2
* None

###Rel. 0.4
* None

Prerequisites
-------------
* WordPress 2.5 or higher

Installation
------------
Copy the folder dpwpsimplecache and its content into 

	your-blog/wp-content/plugins

Usage
-----
dpwpsimplecache provides a global variable $dpcache, which is an instantiation of the class DP_Cache already set up to talk to the $_SESSION. Always use the global $dpcache variable (Remember to globalize $dpcache before using it in any custom functions). 

If you don't want to track sessions into db set the $USE_DB_SESSION_MANAGER global variable to 0

	global $USE_DB_SESSION_MANAGER;
	$USE_DB_SESSION_MANAGER = 0; // default 1

Insert object;

	$dpcache->set($key,$object);
	
Get object:

	$object = $dpcache->set($key);
	
Count active users:

	$count_users = $dpcache->get_sessions_number();
	
Count objects in the current user's $_SESSION:

	$dpcache->get_statistics();
	
Get all objects in the current user's $_SESSION:

	$dpcache->get_all_values();
	
Test if an object exist in the current user's $_SESSION:

	$dpcache->contais($key);
	
Delete all objects. If the $all parameter is set to false the method delete only the current user's $_SESSION, if true truncate the entire table (default false):

	$dpcache->flush($all);
	
Prints human-readable information about all objects:

	$dpcache->inspect();
	
Delete an object in the current user's $_SESSION:

	$dpcache->delete($key);
	
Delete single session by ID:

	$dpcache->invalidate_single_session($sessid);
	
Prints the number of active sessions:

	<?php echo dpscache_active_users(); ?>
	
At any time, through the administrative page, you can:

* see all objects in the current user cache
* delete all objects in the current user cache
* force the deletion of all sessions
* force the deletion of a single session
	
License
-------
D(ifferent)P(lace) Simple Cache is released under the W3C Software Notice and License 

http://www.w3.org/Consortium/Legal/2002/copyright-software-20021231

By obtaining, using and/or copying this work, you (the licensee) agree that you have read, understood, and will comply with the following terms and conditions.

Permission to copy, modify, and distribute this software and its documentation, with or without modification, for any purpose and without fee or royalty is hereby granted, provided that you include the following on ALL copies of the software and documentation or portions thereof, including modifications:

* The full text of this NOTICE in a location viewable to users of the redistributed or derivative work.
* Any pre-existing intellectual property disclaimers, notices, or terms and conditions. If none exist, the W3C Software Short Notice should be included (hypertext is preferred, text is permitted) within the body of any redistributed or derivative code.
* Notice of any changes or modifications to the files, including the date changes were made. (We recommend you provide URIs to the location from which the code is derived.)

Author
------
List of authors:

* Danilo Paissan - danilo.paissan@differentplace.net