<?php
$config = array();

//============================ BASE ============================//
$config['default_return_type'] 						= "JSON";		// The defualt format to return reponses in if format is not setin request | Supports XML, HTML and JSON
$config['full_api_lockdown'] 						= false;		// If set to true the system will only respond to keys with 'full' access level

//=========================== MYSQL ============================//
$config['mysql_server'] 							= "localhost";	// The host address of the MySQL server to be used by modules that use 'database.inc.php'
$config['mysql_user'] 								= "Icebreath";	// User for MySQL
$config['mysql_password'] 							= "hackme";		// Password for the MySQL user

//==================== ICECAST MODULE CONFIG ===================//
$config['icecast']['RADIO_SERVER'] 					= "localhost";	// The location of the Icecast server
$config['icecast']['RADIO_SERVER_PORT'] 			= "8000";		// Port that the Icecast server is runninf on
$config['icecast']['RADIO_SERVER_NAME'] 			= "admin";		// The name of the admin account for the Icecast server
$config['icecast']['RADIO_SERVER_PASS'] 			= "hackme";		// Password for the admin accounr
$config['icecast']['ALLOW_MOUNT_SELECTION'] 		= true;			// Defines if the module so check for a requested mount point
$config['icecast']['RADIO_DEFAULT_MOUNT'] 			= "stream";		// The default mount point to check if no mount point was selected
$config['icecast']['RADIO_MOUNT_BACKUPS'] 			= false;		// Defines if backup mount points are allowed to be checked by the module
$config['icecast']['RADIO_BACKUP_ARRAY'] 			= array();		// The Array of mount points to check if the default is offline and no mount point is selected
$config['icecast']['GET_ONLINE']					= true;			// If set to true the module will return if the mount point is online
$config['icecast']['GET_MOUNT'] 					= true;			// If set to true the module will return the name of the mount point
$config['icecast']['GET_BITRATE'] 					= true;			// If set to true the module will return the bitrate of the stream on the mount point
$config['icecast']['GET_WEBSITE'] 					= true;			// If set to true the module will return the website related to this stream | 'server_url'
$config['icecast']['GET_NAME'] 						= true;			// If set to true the module will return the name of the DJ
$config['icecast']['GET_DESCRIPTION'] 				= true;			// If set to true the module will return the disception of the stream on the mount point
$config['icecast']['GET_TYPE'] 						= true;			// If set to true the module will return the 'Content-type' of the stream
$config['icecast']['GET_SOURCE'] 					= true; 		// If set to true the module will return the source address of the stream
$config['icecast']['GET_LISTENERS'] 				= true;			// If set to true the module will return the ammount of listeners on the mount point
$config['icecast']['GET_TITLE'] 					= true;			// If set to true the module will return the title of the current song
$config['icecast']['GET_MAXLISTEN'] 				= true;			// If set to true the module will return the max ammount of listeners allowed on this mount point
$config['icecast']['GET_LISTENURL'] 				= true;			// If set to true the module will return the URI required to tune in straight to the mount point
$config['icecast']['GET_AGENT'] 					= true;			// If set to true the module will return 'User-Agent' the streamer is using
$config['icecast']['GET_LISTENPEAK'] 				= true; 		// If set to true the module will return the peak count of listeners that have connected to this mount point
$config['icecast']['GET_GENRE'] 					= true;			// If set to true the module will return the genre of this mount point and its stream
$config['icecast']['DO_EXPLODE_TITLE'] 				= true;			// If set to true the module will return the module with split the 'TITLE' into song name and song artist
$config['icecast']['EXPLODE_TYPE'] 					= " - "; 		// Sets how the module should split the title

//============================ KEYS ============================//
// Defines the API keys and their levels
$config['keys'] = array(
	'r73d2ob0236493t445X8sh0MGd826722d41' => array('full'),
	'MG06g64xp7676s0IiZ77126328b352FK' => array('np_get', 'cover_get'));

//==============================================================//
define("CMS_GLOBAL_CONFIG" , serialize($config));
?>