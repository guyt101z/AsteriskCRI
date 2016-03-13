<?php /*

  #####  ######  ### 
 #     # #     #  #  
 #       #     #  #  
 #       ######   #  
 #       #   #    #  
 #     # #    #   #  
  #####  #     # ### 

THIS IS THE CONFIGURATION FILE FOR CRI.
PLEASE READ THE README BEFORE MAKING CHANGES

*/


//======================================================================
// GLOBAL SYSTEM SETTINGS
//======================================================================

// These settings affect all users - irrespective of whatever settings
// or options are assigned to individual users.

/* BASE URL */
// Enter the canonical URL of your install, e.g. https://cri.your-domain.com
$baseurl = "";

/* PROFILE URL */
// Enter the URL for user profile, eg from Confluence
$profile_url = "";


/* ASTERISK SERVER URL */
// The url of your asterisk server, eg http://asterisk.your-domain.com
// If CRI is installed on Asterisk server, enter localhost
$asterisk_server = "localhost";

/* DOWNLOADS */
// Set true to allow users to download call recording files
$allow_download_call = true;
// Set true to allow users to download call record information
$allow_download_cdr = true;

/* CUSTOMER SERVICES QUEUE NUMBER */
// Enter the Asterisk queue number, e.g. 1234
$cs_inbound_dest = "";



//======================================================================
// DATABASE SETTINGS
//======================================================================

/* ASTERISK DATABASE */
// Details for the mysql install for asterisk
$asterisk_db_server = "localhost";
$asterisk_db_user = "user";
$asterisk_db_pass = "pass";

/* CRI DATABASE */
// Details for mysql for CRI (local)
$cri_db_server = "localhost";
$cri_db_user = "user";
$cri_db_pass = "pass";



//======================================================================
// AUTHENTICATION
//======================================================================

/* METHOD */
// Authentication method - keep as 'ad'
$auth_method = "ad";

/* ALLOWED USERS */
// AD Only - array of users allowed to access app
$ad_allowed_users = array();






//version number - no need to change this
$release = "3.0"
?>