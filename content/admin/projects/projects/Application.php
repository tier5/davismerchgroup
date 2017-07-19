<?php
$appdir="../../";
require('../../Application.php');
$mydirectory="../..";
$image_dir = $mydirectory.'/upload_files/images/';
$file_dir = $mydirectory.'/upload_files/files/';
$img_glry_dir = $mydirectory.'/upload_files/img_glry/';
$showNoOptMsg='If No Explain why in the comment box below.';
function currURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 $pageURL = substr($pageURL,0,strrpos($pageURL,"/"));
 return $pageURL;
}
$is_client = 0;
if(isset($_SESSION['emp_type']) && isset($_SESSION['client_id']) && $_SESSION['emp_type'] == 1 && $_SESSION['client_id'] > 0)
{    
    $is_client = 1;
}
else if((isset($_SESSION['perm_admin']) AND $_SESSION['perm_admin'] == "on")||(isset($_SESSION['perm_production']) AND $_SESSION['perm_production'] == "on")
||(isset($_SESSION['perm_manager']) AND $_SESSION['perm_manager'] == "on")){
}
/*else
    {
	echo "<body bgcolor=\"#FFFFFF\">";
	echo "<br><br>";
	echo "<center>";
	echo "<font face=\"arial\">";
	echo "<b>The User ".$_SESSION['firstname']." ".$_SESSION['lastname']." does not have access to this area.</b>";
	echo "</font>";
	echo "</center>";
	echo "</body>";
	exit;
}*/
//print_r($_SESSION);

?>
