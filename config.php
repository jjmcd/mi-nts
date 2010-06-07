<?php
/* $Revision: 1.2 $ */
	/*--------------------------------------------------\
	| 		|               | config.php        |
	|---------------------------------------------------|
	| Web-ERP - http://web-erp.sourceforge.net          |
	| by Logic Works Ltd                                |
	|---------------------------------------------------|
	|                                                   |
	\--------------------------------------------------*/

// User configurable variables
//---------------------------------------------------

//DefaultLanguage
$DefaultLanguage ='en_US';

// Whether to display the demo login and password or not 
$allow_demo_mode = False;

// Application version
$Version = '3.00';

// The timezone of the business - this allows the possibility of having
// the web-server on a overseas machine but record local time
// this is not necessary if you have your own server locally
// putenv('TZ=Europe/London');
// putenv('Australia/Melbourne');
// putenv('Australia/Sydney');
putenv('TZ=America/New_York');

// Connection information for the database
// $host is the computer ip address or name where the database is located
// assuming that the web server is also the sql server
$host = 'localhost';

//The type of db server being used - currently only postgres or mysql
//$dbType = 'mysql';
//$dbType = 'postgres';
//$dbType = 'mysql';

$DatabaseName = 'mi-nts_org_-_website';

// sql user & password
$dbuser = 'wb8rcraaaaaaaaaa';
$dbpassword = 'wb8rcr';
$dbp= 'd2I4cmNy';
$dppE = array( 'bG9jYWxob3N0', 'd2I4cmNyYWFhYWFhYWFhYQ==', 'd2I4cmNy' );
$aa0 = 'bG9jYWxob3N0';
$aa1 = 'd2I4cmNyYWFhYWFhYWFhYQ==';
$aa2 = 'd2I4cmNy';
$aa3 = 'bWktbnRzX29yZ18tX3dlYnNpdGU=';

//The maximum time that a login session can be idle before automatic logout
//time is in seconds  3600 seconds in an hour
$SessionLifeTime = 3600;

//The maximum time that a script can execute for before the web-server should terminate it
$MaximumExecutionTime =120;

//The path to which session files should be stored in the server
//this can be left commented out if only one company is running on the server
//However if multiple webERP installations are on the same server then a separate session directory is required for each install
//$SessionSavePath = '/tmp';


// which encryption function should be used
//$CryptFunction = "md5"; // MD5 Hash
//$CryptFunction = "sha1"; // SHA1 Hash
$CryptFunction = ""; // Plain Text



// END OF USER CONFIGURABLE VARIABLES




/*The $rootpath is used in most scripts to tell the script the installation details of the files.

NOTE: In some windows installation this command doesn't work and the administrator must set this to the path of the installation manually:
eg. if the files are under the webserver root directory then rootpath =''; if they are under weberp then weberp is the rootpath - notice no additional slashes are necessary.
*/

$rootpath = dirname($_SERVER['PHP_SELF']);
//$rootpath = '/web-erp';

/* Report all errors except E_NOTICE
This is the default value set in php.ini for most installations but just to be sure it is forced here
turning on NOTICES destroys things */

error_reporting (E_ALL & ~E_NOTICE);

/*Dont modify this bit
function required if gettext is not installed */

if (!function_exists('_')){
	function _($text){
		return ($text);
	}
}

/*Make sure there is nothing - not even spaces after this last ?> */
?>