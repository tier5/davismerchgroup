<?php
//$db_server = "localhost";
//$db_server = "74.80.222.57";
require_once 'config.php';
$debug="off";                                   // set to on for the little debug code i have set on/off
$PHPLIBDIR="/var/www/html/phplib/";               // base dir for getting libs
$PHPLIB=$PHPLIBDIR;
$JSONLIB=$PHPLIBDIR."jsonwrapper/";
$isMailServer ="true";
$mailServerAddress = "colomx.i2net.com"; //Specify mail server address (eg. $mailServerAddress = "mail.i2net.com"; ) When $isMailServer = false


//date_default_timezone_set('America/New_York');                        // Eastern
//date_default_timezone_set('America/Chicago');                 // Central
//date_default_timezone_set('America/Denver');                  // Mountain
//date_default_timezone_set('America/Phoenix');                 // Mountain no DST
date_default_timezone_set('America/Los_Angeles');               // Pacific
//date_default_timezone_set('America/Anchorage');                       // Alaska
//date_default_timezone_set('America/Adak');                    // Hawaii
//date_default_timezone_set('America/Honolulu');                        // Hawaii no DST

$connection = pg_connect("host = $db_server ".
						 "dbname = $db_name ".
						 "user = $db_uname ".
						 "password = $db_pass");
// Central variables for entire module
$compquery=("SELECT \"ID\", \"client\" ".
			"FROM \"clientDB\" ".
			"WHERE \"ID\" = '1' ");
if(!($resultcomp=pg_query($connection,$compquery))){
	print("Failed compquery: " . pg_last_error($connection));
	exit;
}
while($rowcomp = pg_fetch_array($resultcomp)){
	$datacomp[]=$rowcomp;
}
	$compname="".$datacomp[0]['client']."";			// company name
        
function dd($data){
    if(is_array($data)){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();
    }
    echo $data;
    die();
}
?>
