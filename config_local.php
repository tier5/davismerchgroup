<?php
/* Database cofiguration */
global $base_url, $db_server, $db_name, $db_uname, $db_pass;

$db_server = "localhost";
$db_name = 'php_intranet_davismerch';                          // database name
$db_uname= "globaluniformuser";                              // username to connect to database
$db_pass= "globaluniformpassword";                                // password of username to connecto to database
$base_url = "http://localhost/davismerchgroup/";

/****************  Configuration for live  ************************/
/*
$db_server = "davis-pgsql.i2net.com";
$db_name = "php_intranet_davismerch";                          // database name
$db_uname= "davisuser";                              // username to connect to database
$db_pass= "davispassword";                                // password of username to connecto to database
$base_url = "http://internal.davismerchgroup.com/";
*/